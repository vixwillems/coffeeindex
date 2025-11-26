<?php
require 'config.php';

// Fetch all beans
$sql = "SELECT * FROM beans ORDER BY purchase_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Coffee Index</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="style.css">
</head>
<body class="bg-tertiary">

<nav class="shadow mb-6 bg-secondary">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between h-16">
            <div class="flex space-x-8 items-center">
                <a href="" class="text-xl font-bold transition bg-secondary">
  					<span class="text-tertiary">☕ Coffee</span><span class="text-primary">Index</span>
				</a>
                
                <a href="" class="font-medium border-b-2 border-transparent transition menu-item">Index</a>
                <a href="stats" class="font-medium border-b-2 border-transparent transition menu-item">Statistics</a>
            </div>

            <div class="flex items-center space-x-4">
                <a href="https://vixwillems.eu" class="btn text-xs font-semibold text-gray-600 border border-gray-300 px-3 py-2 rounded hover:bg-gray-100 transition">
                    ← Back to vixwillems.eu
                </a>

                <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                    <a href="admin.php" class="btn bg-gray-800 text-white text-xs font-bold px-3 py-2 rounded hover:bg-black transition">
                        Admin Dashboard
                    </a>
                <?php else: ?>
                    <a href="login.php" class="text-xs text-gray-300 hover:text-gray-500">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<div class="max-w-7xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl text-gray-900 font-bold">Dataset</h1>
    </div>

    <div class="overflow-x-auto shadow-lg rounded-lg bg-white">
    <table class="min-w-full text-sm text-left border-collapse">
        <thead class="bg-gray-200 text-gray-900">
            <tr>
                <th class="p-3">Roaster</th>
                <th class="p-3">Coffee Name</th>
                <th class="p-3">Type</th>
                <th class="p-3">Country</th>
                <th class="p-3">Region</th>
                <th class="p-3">Farm</th>
                <th class="p-3">Producer</th>
                <th class="p-3">Varietals</th>
                <th class="p-3">Altitude</th>
                <th class="p-3">Process</th>
                <th class="p-3">Roast Level</th>
                <th class="p-3">Tasting Notes</th>
                <th class="p-3">Weight</th>
                <th class="p-3">Price</th>
                <th class="p-3">€/100g</th>
                <th class="p-3">Drunk As</th>
				<th class="p-3">Purchase Date</th>
            </tr>
        </thead>

        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr class="border-b hover:bg-gray-100">
                <td class="p-3">
    				<?php if(!empty($row['roaster_url'])): ?>
   				    	<a href="<?= htmlspecialchars($row['roaster_url']) ?>" target="_blank" class="text-blue-600 hover:underline"><?= htmlspecialchars($row['roaster']) ?></a>
    				<?php else: ?>
        				<?= htmlspecialchars($row['roaster']) ?>
    				<?php endif; ?>
				</td>
                <td class="p-3 font-semibold">
    				<?php if(!empty($row['coffee_url'])): ?>
        				<a href="<?= htmlspecialchars($row['coffee_url']) ?>" target="_blank" class="text-blue-600 hover:underline"><?= htmlspecialchars($row['coffee_name']) ?></a>
    				<?php else: ?>
        				<?= htmlspecialchars($row['coffee_name']) ?>
    				<?php endif; ?>
				</td>
                <td class="p-3"><?= htmlspecialchars($row['type']) ?></td>
                <td class="p-3"><?= htmlspecialchars($row['country']) ?></td>
                <td class="p-3"><?= htmlspecialchars($row['region']) ?></td>
                <td class="p-3"><?= htmlspecialchars($row['farm']) ?></td>
                <td class="p-3"><?= htmlspecialchars($row['producer']) ?></td>
                <td class="p-3"><?= htmlspecialchars($row['varietals']) ?></td>
                <td class="p-3"><?= htmlspecialchars($row['altitude']) ?></td>
                <td class="p-3"><?= htmlspecialchars($row['process']) ?></td>
                <td class="p-3"><?= htmlspecialchars($row['roast_level']) ?></td>
                <td class="p-3 italic"><?= htmlspecialchars($row['tasting_notes']) ?></td>
                <td class="p-3"><?= htmlspecialchars($row['weight']) ?></td>
                <td class="p-3 whitespace-nowrap"><?= htmlspecialchars($row['price']) ?> €</td>
                <td class="p-3 whitespace-nowrap"><?= htmlspecialchars($row['price_per_100g']) ?> €</td>
                <td class="p-3"><?= htmlspecialchars($row['drunk_as']) ?></td>
				<td class="p-3 whitespace-nowrap"><?= htmlspecialchars($row['purchase_date']) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    </div>
</div>
<footer class="max-w-9xl mx-auto p-6 mt-10 border-t border-gray-200 text-center">
    <p class="text-sm text-gray-500">
        Inspiration & Idea by <a href="https://hugo.cafe" target="_blank" class="text-blue-600 hover:underline">Hugo.cafe</a>
    </p>
    <p class="text-xs text-gray-400 mt-1">
        &copy; <?= date('Y') ?> Vix Willems
    </p>
</footer>
</body>
</html>