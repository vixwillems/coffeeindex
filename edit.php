<?php
require 'config.php';

// Security Check
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit; }

$id = $_GET['id'] ?? null;
$bean = [];

// Initialize empty defaults
$fields = [
    'purchase_date', 'roaster', 'roaster_url', 'coffee_name', 'coffee_url', 
    'type', 'country', 'region', 'farm', 'producer', 'varietals', 'altitude', 
    'process', 'roast_level', 'tasting_notes', 'weight', 'price', 
    'drunk_as', 'score'
];

foreach ($fields as $f) { $bean[$f] = ''; }

// If Editing, fetch data
if ($id) {
    $stmt = $conn->prepare("SELECT * FROM beans WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $bean = $res->fetch_assoc();
}

// Handle Form Submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Calculate price per 100g automatically
    $weight = (float)$_POST['weight'];
    $price = (float)$_POST['price'];
    $price100 = ($weight > 0) ? ($price / $weight) * 100 : 0;

    // Define the type string for bind_param
    // 15 strings (s), 3 doubles (d) for weight/price/price100, 1 string (s) for drunk_as, 1 double (d) for score
    // Total: sssssssssssssssdddsd
    $types = "sssssssssssssssdddsd";

    if ($id) {
        // UPDATE
        $sql = "UPDATE beans SET 
            purchase_date=?, roaster=?, roaster_url=?, coffee_name=?, coffee_url=?, type=?, country=?, 
            region=?, farm=?, producer=?, varietals=?, altitude=?, process=?, roast_level=?, 
            tasting_notes=?, weight=?, price=?, price_per_100g=?, drunk_as=?, score=? 
            WHERE id=?";
        
        // Append 'i' for the ID at the end
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types . "i", 
            $_POST['purchase_date'], $_POST['roaster'], $_POST['roaster_url'], $_POST['coffee_name'], $_POST['coffee_url'],
            $_POST['type'], $_POST['country'], $_POST['region'], $_POST['farm'], $_POST['producer'], 
            $_POST['varietals'], $_POST['altitude'], $_POST['process'], $_POST['roast_level'], 
            $_POST['tasting_notes'], $_POST['weight'], $_POST['price'], $price100, 
            $_POST['drunk_as'], $_POST['score'], $id
        );
    } else {
        // INSERT
        $sql = "INSERT INTO beans (
            purchase_date, roaster, roaster_url, coffee_name, coffee_url, type, country, 
            region, farm, producer, varietals, altitude, process, roast_level, 
            tasting_notes, weight, price, price_per_100g, drunk_as, score
        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, 
            $_POST['purchase_date'], $_POST['roaster'], $_POST['roaster_url'], $_POST['coffee_name'], $_POST['coffee_url'],
            $_POST['type'], $_POST['country'], $_POST['region'], $_POST['farm'], $_POST['producer'], 
            $_POST['varietals'], $_POST['altitude'], $_POST['process'], $_POST['roast_level'], 
            $_POST['tasting_notes'], $_POST['weight'], $_POST['price'], $price100, 
            $_POST['drunk_as'], $_POST['score']
        );
    }
    
    if ($stmt->execute()) {
        header("Location: admin.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Coffee</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-50 text-gray-900 pb-10">
<div class="max-w-4xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold"><?= $id ? 'Edit Coffee' : 'Add New Coffee' ?></h1>
        <a href="admin.php" class="text-gray-500 hover:text-gray-800">Cancel</a>
    </div>

    <form method="post" class="bg-white p-8 rounded-lg shadow-lg space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><label class="block text-sm font-medium text-gray-700">Roaster</label><input type="text" name="roaster" value="<?= htmlspecialchars($bean['roaster']) ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2"></div>
            <div><label class="block text-sm font-medium text-gray-700">Roaster URL</label><input type="url" name="roaster_url" value="<?= htmlspecialchars($bean['roaster_url']) ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2"></div>
            <div><label class="block text-sm font-medium text-gray-700">Coffee Name</label><input type="text" name="coffee_name" value="<?= htmlspecialchars($bean['coffee_name']) ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2"></div>
            <div><label class="block text-sm font-medium text-gray-700">Coffee URL</label><input type="url" name="coffee_url" value="<?= htmlspecialchars($bean['coffee_url']) ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2"></div>
            <div><label class="block text-sm font-medium text-gray-700">Purchase Date</label><input type="date" name="purchase_date" value="<?= htmlspecialchars($bean['purchase_date']) ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2"></div>
            <div><label class="block text-sm font-medium text-gray-700">Type</label><input type="text" name="type" value="<?= htmlspecialchars($bean['type']) ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2"></div>
        </div>
        <hr>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div><label class="block text-sm font-medium text-gray-700">Country</label><input type="text" name="country" value="<?= htmlspecialchars($bean['country']) ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2"></div>
            <div><label class="block text-sm font-medium text-gray-700">Region</label><input type="text" name="region" value="<?= htmlspecialchars($bean['region']) ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2"></div>
            <div><label class="block text-sm font-medium text-gray-700">Altitude</label><input type="text" name="altitude" value="<?= htmlspecialchars($bean['altitude']) ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2"></div>
            <div><label class="block text-sm font-medium text-gray-700">Farm</label><input type="text" name="farm" value="<?= htmlspecialchars($bean['farm']) ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2"></div>
            <div><label class="block text-sm font-medium text-gray-700">Producer</label><input type="text" name="producer" value="<?= htmlspecialchars($bean['producer']) ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2"></div>
            <div><label class="block text-sm font-medium text-gray-700">Varietals</label><input type="text" name="varietals" value="<?= htmlspecialchars($bean['varietals']) ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2"></div>
        </div>
        <hr>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div><label class="block text-sm font-medium text-gray-700">Process</label><input type="text" name="process" value="<?= htmlspecialchars($bean['process']) ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2"></div>
            <div><label class="block text-sm font-medium text-gray-700">Roast Level</label><input type="text" name="roast_level" value="<?= htmlspecialchars($bean['roast_level']) ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2"></div>
            <div><label class="block text-sm font-medium text-gray-700">Drunk As</label><input type="text" name="drunk_as" value="<?= htmlspecialchars($bean['drunk_as']) ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2"></div>
        </div>
        <div><label class="block text-sm font-medium text-gray-700">Tasting Notes</label><input type="text" name="tasting_notes" value="<?= htmlspecialchars($bean['tasting_notes']) ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2"></div>
        <hr>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div><label class="block text-sm font-medium text-gray-700">Weight (g)</label><input type="number" step="1" name="weight" value="<?= htmlspecialchars($bean['weight']) ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2"></div>
            <div><label class="block text-sm font-medium text-gray-700">Price (€)</label><input type="number" step="0.01" name="price" value="<?= htmlspecialchars($bean['price']) ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2"></div>
            <div><label class="block text-sm font-medium text-gray-700">Score</label><input type="number" step="0.1" name="score" value="<?= htmlspecialchars($bean['score']) ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2"></div>
        </div>
        <div class="pt-4"><button type="submit" class="bg-green-600 text-white px-6 py-2 rounded font-bold hover:bg-green-700">Save Coffee</button></div>
    </form>
</div>
</body>
</html>