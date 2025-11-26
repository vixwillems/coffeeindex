<?php
require 'config.php';

// Security Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
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
    <div class="max-w-7xl mx-auto px-6">
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

<div class="max-w-7xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold" style="color: var(--secondary);">Admin Dashboard</h1>
            <p class="text-sm text-gray-600 mt-1">Manage your coffee collection</p>
        </div>
        
        <a href="edit.php" class="btn">
            ➕ Add New Coffee
        </a>
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

<footer class="max-w-7xl mx-auto p-6 mt-10 border-t border-gray-200 text-center">
    <p class="text-xs text-gray-400">
        &copy; <?= date('Y') ?> Coffee Index - Admin Panel
    </p>
</footer>

</body>
</html>