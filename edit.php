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

    $types = "sssssssssssssssdddsd";

    if ($id) {
        // UPDATE
        $sql = "UPDATE beans SET 
            purchase_date=?, roaster=?, roaster_url=?, coffee_name=?, coffee_url=?, type=?, country=?, 
            region=?, farm=?, producer=?, varietals=?, altitude=?, process=?, roast_level=?, 
            tasting_notes=?, weight=?, price=?, price_per_100g=?, drunk_as=?, score=? 
            WHERE id=?";
        
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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $id ? 'Edit Coffee' : 'Add New Coffee' ?> - Coffee Index</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="bg-tertiary pb-10">

<nav class="shadow mb-6 bg-secondary">
    <div class="max-w-full mx-auto px-6">
        <div class="flex justify-between h-16">
            <div class="flex space-x-8 items-center">
                <a href="index.php" class="text-xl font-bold transition">
  					<span class="text-tertiary">☕ Coffee</span><span class="text-primary">Index</span>
				</a>
                
                <a href="index.php" class="font-medium border-b-2 border-transparent transition menu-item">Index</a>
                <a href="stats.php" class="font-medium border-b-2 border-transparent transition menu-item">Statistics</a>
                <a href="admin.php" class="font-medium border-b-2 border-transparent transition menu-item" style="color: var(--primary) !important;">Admin</a>
            </div>

            <div class="flex items-center space-x-4">
                <a href="admin.php" class="btn text-xs">
                    ← Back to Admin
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="max-w-7xl mx-auto px-6 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold" style="color: var(--secondary);">
            <?= $id ? '✏️ Edit Coffee' : '➕ Add New Coffee' ?>
        </h1>
        <p class="text-sm text-gray-600 mt-1">
            <?= $id ? 'Update the details below' : 'Fill in the details for your new coffee' ?>
        </p>
    </div>

    <form method="post" class="bg-white p-8 rounded-lg shadow-lg space-y-8" style="border: 2px solid var(--tertiary);">
        
        <!-- Basic Information -->
        <div>
            <h2 class="text-lg font-bold mb-4 pb-2" style="color: var(--secondary); border-bottom: 2px solid var(--primary);">
                ☕ Basic Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Roaster *</label>
                    <input type="text" name="roaster" value="<?= htmlspecialchars($bean['roaster']) ?>" 
                           class="w-full border border-gray-300 rounded-md p-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Roaster URL</label>
                    <input type="url" name="roaster_url" value="<?= htmlspecialchars($bean['roaster_url']) ?>" 
                           class="w-full border border-gray-300 rounded-md p-2" placeholder="https://">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Coffee Name *</label>
                    <input type="text" name="coffee_name" value="<?= htmlspecialchars($bean['coffee_name']) ?>" 
                           class="w-full border border-gray-300 rounded-md p-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Coffee URL</label>
                    <input type="url" name="coffee_url" value="<?= htmlspecialchars($bean['coffee_url']) ?>" 
                           class="w-full border border-gray-300 rounded-md p-2" placeholder="https://">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Purchase Date *</label>
                    <input type="date" name="purchase_date" value="<?= htmlspecialchars($bean['purchase_date']) ?>" 
                           class="w-full border border-gray-300 rounded-md p-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <input type="text" name="type" value="<?= htmlspecialchars($bean['type']) ?>" 
                           class="w-full border border-gray-300 rounded-md p-2" placeholder="e.g., Single Origin, Blend">
                </div>
            </div>
        </div>

        <!-- Origin Details -->
        <div>
            <h2 class="text-lg font-bold mb-4 pb-2" style="color: var(--secondary); border-bottom: 2px solid var(--primary);">
                🌍 Origin Details
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                    <input type="text" name="country" value="<?= htmlspecialchars($bean['country']) ?>" 
                           class="w-full border border-gray-300 rounded-md p-2" placeholder="e.g., Ethiopia">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Region</label>
                    <input type="text" name="region" value="<?= htmlspecialchars($bean['region']) ?>" 
                           class="w-full border border-gray-300 rounded-md p-2" placeholder="e.g., Yirgacheffe">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Altitude</label>
                    <input type="text" name="altitude" value="<?= htmlspecialchars($bean['altitude']) ?>" 
                           class="w-full border border-gray-300 rounded-md p-2" placeholder="e.g., 1800-2000m">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Farm</label>
                    <input type="text" name="farm" value="<?= htmlspecialchars($bean['farm']) ?>" 
                           class="w-full border border-gray-300 rounded-md p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Producer</label>
                    <input type="text" name="producer" value="<?= htmlspecialchars($bean['producer']) ?>" 
                           class="w-full border border-gray-300 rounded-md p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Varietals</label>
                    <input type="text" name="varietals" value="<?= htmlspecialchars($bean['varietals']) ?>" 
                           class="w-full border border-gray-300 rounded-md p-2" placeholder="e.g., Heirloom">
                </div>
            </div>
        </div>

        <!-- Processing & Roasting -->
        <div>
            <h2 class="text-lg font-bold mb-4 pb-2" style="color: var(--secondary); border-bottom: 2px solid var(--primary);">
                🔥 Processing & Roasting
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Process</label>
                    <input type="text" name="process" value="<?= htmlspecialchars($bean['process']) ?>" 
                           class="w-full border border-gray-300 rounded-md p-2" placeholder="e.g., Washed, Natural">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Roast Level</label>
                    <input type="text" name="roast_level" value="<?= htmlspecialchars($bean['roast_level']) ?>" 
                           class="w-full border border-gray-300 rounded-md p-2" placeholder="e.g., Light, Medium">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Drunk As</label>
                    <input type="text" name="drunk_as" value="<?= htmlspecialchars($bean['drunk_as']) ?>" 
                           class="w-full border border-gray-300 rounded-md p-2" placeholder="e.g., Espresso, Filter">
                </div>
            </div>
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tasting Notes</label>
                <input type="text" name="tasting_notes" value="<?= htmlspecialchars($bean['tasting_notes']) ?>" 
                       class="w-full border border-gray-300 rounded-md p-2" 
                       placeholder="e.g., Berry, chocolate, floral">
            </div>
        </div>

        <!-- Price & Rating -->
        <div>
            <h2 class="text-lg font-bold mb-4 pb-2" style="color: var(--secondary); border-bottom: 2px solid var(--primary);">
                💰 Price & Rating
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Weight (g) *</label>
                    <input type="number" step="1" name="weight" value="<?= htmlspecialchars($bean['weight']) ?>" 
                           class="w-full border border-gray-300 rounded-md p-2" placeholder="250" required>
                    <p class="text-xs text-gray-500 mt-1">Weight in grams</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price (€) *</label>
                    <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($bean['price']) ?>" 
                           class="w-full border border-gray-300 rounded-md p-2" placeholder="15.00" required>
                    <p class="text-xs text-gray-500 mt-1">Price per 100g calculated automatically</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Score (1-10)</label>
                    <input type="number" step="0.1" min="0" max="10" name="score" 
                           value="<?= htmlspecialchars($bean['score']) ?>" 
                           class="w-full border border-gray-300 rounded-md p-2" placeholder="8.5">
                    <p class="text-xs text-gray-500 mt-1">Your personal rating</p>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-between items-center pt-4">
            <a href="admin.php" class="text-gray-600 hover:text-gray-900 font-medium">
                ← Cancel
            </a>
            <button type="submit" class="btn px-8 py-3 text-base">
                💾 <?= $id ? 'Update Coffee' : 'Add Coffee' ?>
            </button>
        </div>
    </form>
</div>

</body>
</html>