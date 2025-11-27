<?php
require 'config.php';

// Security Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Handle CSV Export
if (isset($_GET['action']) && $_GET['action'] == 'export_csv') {
    $result = $conn->query("SELECT * FROM beans ORDER BY purchase_date DESC");
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=coffee_index_' . date('Y-m-d') . '.csv');
    
    $output = fopen('php://output', 'w');
    
    // Header row
    fputcsv($output, ['ID', 'Purchase Date', 'Roaster', 'Roaster URL', 'Coffee Name', 'Coffee URL', 
                      'Type', 'Country', 'Region', 'Farm', 'Producer', 'Varietals', 'Altitude', 
                      'Process', 'Roast Level', 'Tasting Notes', 'Weight', 'Price', 'Price per 100g', 
                      'Drunk As', 'Score', 'Is Current']);
    
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
    
    fclose($output);
    exit;
}

// Handle CSV Import
if (isset($_POST['import_csv']) && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file']['tmp_name'];
    
    if (($handle = fopen($file, 'r')) !== FALSE) {
        $header = fgetcsv($handle); // Skip header row
        $imported = 0;
        
        while (($data = fgetcsv($handle)) !== FALSE) {
            // Skip if not enough columns
            if (count($data) < 20) continue;
            
            $stmt = $conn->prepare("INSERT INTO beans (purchase_date, roaster, roaster_url, coffee_name, coffee_url, type, country, region, farm, producer, varietals, altitude, process, roast_level, tasting_notes, weight, price, price_per_100g, drunk_as, score) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            
            $stmt->bind_param("sssssssssssssssdddsd",
                $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8], 
                $data[9], $data[10], $data[11], $data[12], $data[13], $data[14], $data[15], 
                $data[16], $data[17], $data[18], $data[19], $data[20]
            );
            
            if ($stmt->execute()) $imported++;
        }
        
        fclose($handle);
        $import_message = "Successfully imported $imported entries!";
    }
}

// Handle Actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    if ($_GET['action'] == 'delete') {
        $stmt = $conn->prepare("DELETE FROM beans WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
    
    if ($_GET['action'] == 'set_current') {
        $conn->query("UPDATE beans SET is_current = 0");
        $stmt = $conn->prepare("UPDATE beans SET is_current = 1 WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
    
    header("Location: admin.php");
    exit;
}

$result = $conn->query("SELECT * FROM beans ORDER BY purchase_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - Coffee Index</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="bg-tertiary">

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
                <a href="index.php" class="btn text-xs">
                    👁️ View Site
                </a>
                
                <a href="logout.php" class="text-xs font-semibold" style="color: var(--tertiary); text-decoration: none;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--tertiary)'">
                    🚪 Logout
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="max-w-full mx-auto px-6 py-6">
    <?php if (isset($import_message)): ?>
        <div class="mb-6 p-4 rounded-lg" style="background-color: rgba(34, 197, 94, 0.1); border-left: 4px solid #22c55e;">
            <p class="text-green-700 font-medium">✅ <?= $import_message ?></p>
        </div>
    <?php endif; ?>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold" style="color: var(--secondary);">Admin Dashboard</h1>
            <p class="text-sm text-gray-600 mt-1">Manage your coffee collection</p>
        </div>
        
        <div class="flex gap-3">
            <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="btn text-xs">
                📥 Import CSV
            </button>
            <a href="admin.php?action=export_csv" class="btn text-xs">
                📤 Export CSV
            </a>
            <a href="edit.php" class="btn text-xs">
                ➕ Add New Coffee
            </a>
        </div>
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden" style="border: 2px solid var(--tertiary);">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead>
                    <tr>
                        <th class="p-3">Current?</th>
                        <th class="p-3">Date</th>
                        <th class="p-3">Roaster</th>
                        <th class="p-3">Coffee</th>
                        <th class="p-3">Country</th>
                        <th class="p-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr class="border-b" style="border-color: rgba(242, 229, 213, 0.5);">
                        <td class="p-3 text-center">
                            <?php if($row['is_current']): ?>
                                <span style="background: linear-gradient(135deg, var(--primary), #fbc4dc); color: var(--secondary);" class="px-3 py-1 rounded-full text-xs font-bold">
                                    ☕ DRINKING
                                </span>
                            <?php else: ?>
                                <a href="admin.php?action=set_current&id=<?= $row['id'] ?>" 
                                   class="text-xs font-medium hover:underline"
                                   style="color: var(--secondary);"
                                   onclick="return confirm('Set this as currently drinking?')">
                                    Select
                                </a>
                            <?php endif; ?>
                        </td>
                        <td class="p-3 text-gray-700"><?= htmlspecialchars($row['purchase_date']) ?></td>
                        <td class="p-3 text-gray-800"><?= htmlspecialchars($row['roaster']) ?></td>
                        <td class="p-3 font-semibold" style="color: var(--secondary);"><?= htmlspecialchars($row['coffee_name']) ?></td>
                        <td class="p-3 text-gray-700"><?= htmlspecialchars($row['country']) ?></td>
                        <td class="p-3 text-right space-x-3">
                            <a href="edit.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline font-medium text-xs">
                                ✏️ Edit
                            </a>
                            <a href="admin.php?action=delete&id=<?= $row['id'] ?>" 
                               onclick="return confirm('Are you sure you want to delete this coffee?')" 
                               class="text-red-600 hover:underline font-medium text-xs">
                                🗑️ Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 text-center text-sm text-gray-500">
        <p>Total entries: <strong><?= $result->num_rows ?></strong></p>
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4" style="border: 3px solid var(--primary);">
        <h2 class="text-2xl font-bold mb-4" style="color: var(--secondary);">📥 Import CSV</h2>
        <p class="text-sm text-gray-600 mb-6">Upload a CSV file with your coffee data. The file should include columns for all coffee attributes.</p>
        
        <form method="post" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select CSV File</label>
                <input type="file" name="csv_file" accept=".csv" required 
                       class="w-full border-2 rounded-lg p-2" style="border-color: var(--tertiary);">
            </div>
            
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" 
                        class="px-4 py-2 text-gray-600 hover:text-gray-900 font-medium">
                    Cancel
                </button>
                <button type="submit" name="import_csv" class="btn">
                    Upload & Import
                </button>
            </div>
        </form>
    </div>
</div>

<footer class="max-w-full mx-auto p-6 mt-10 border-t border-gray-200 text-center">
    <p class="text-xs text-gray-400">
        &copy; <?= date('Y') ?> Coffee Index - Admin Panel
    </p>
</footer>

</body>
</html>