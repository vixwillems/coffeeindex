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
<title>Admin Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-50 text-gray-900">

<div class="max-w-7xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Admin Dashboard</h1>
        
        <div class="flex space-x-4 items-center">
            <a href="index.php" class="text-gray-600 hover:text-gray-900 text-sm">View Site</a>
            
            <a href="edit.php" class="bg-blue-600 text-white text-sm font-bold px-4 py-2 rounded hover:bg-blue-700">
                + Add New
            </a>
            
            <a href="logout.php" class="bg-red-500 text-white text-sm font-bold px-4 py-2 rounded hover:bg-red-600">
                Logout
            </a>
        </div>
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-200">
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
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3 text-center">
                        <?php if($row['is_current']): ?>
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold">DRINKING</span>
                        <?php else: ?>
                            <a href="admin.php?action=set_current&id=<?= $row['id'] ?>" class="text-gray-400 hover:text-green-600 text-xs underline">Select</a>
                        <?php endif; ?>
                    </td>
                    <td class="p-3"><?= htmlspecialchars($row['purchase_date']) ?></td>
                    <td class="p-3"><?= htmlspecialchars($row['roaster']) ?></td>
                    <td class="p-3 font-semibold"><?= htmlspecialchars($row['coffee_name']) ?></td>
                    <td class="p-3"><?= htmlspecialchars($row['country']) ?></td>
                    <td class="p-3 text-right space-x-2">
                        <a href="edit.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline">Edit</a>
                        <a href="admin.php?action=delete&id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')" class="text-red-600 hover:underline">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>