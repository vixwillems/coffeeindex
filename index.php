<?php
require 'config.php';

// Fetch all beans
$sql = "SELECT * FROM beans ORDER BY purchase_date DESC";
$result = $conn->query($sql);
$total_count = $result->num_rows;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Coffee Index - Dataset</title>
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
                
                <a href="index.php" class="font-medium border-b-2 border-transparent transition menu-item" style="color: var(--primary) !important;">Index</a>
                <a href="stats.php" class="font-medium border-b-2 border-transparent transition menu-item">Statistics</a>
            </div>

            <div class="flex items-center space-x-4">
                <a href="https://vixwillems.eu" class="btn text-xs">
                    ← Back to vixwillems.eu
                </a>

                <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                    <a href="admin.php" class="btn text-xs">
                        🔧 Admin Dashboard
                    </a>
                <?php else: ?>
                    <a href="login.php" class="text-xs font-semibold" style="color: var(--tertiary); text-decoration: none;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--tertiary)'">
                        Login
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<div class="max-w-7xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold" style="color: var(--secondary);">📊 Coffee Dataset</h1>
            <p class="text-sm text-gray-600 mt-1">Complete collection of all tracked coffees</p>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-600">Total Entries</p>
            <p class="text-2xl font-bold" style="color: var(--secondary);"><?= $total_count ?></p>
        </div>
    </div>

    <div class="overflow-x-auto shadow-lg rounded-lg bg-white" style="border: 2px solid var(--tertiary);">
        <table class="min-w-full text-sm text-left border-collapse">
            <thead>
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
                <tr class="border-b" style="border-color: rgba(242, 229, 213, 0.5);">
                    <td class="p-3">
        				<?php if(!empty($row['roaster_url'])): ?>
       				    	<a href="<?= htmlspecialchars($row['roaster_url']) ?>" target="_blank" class="text-blue-600 hover:underline font-medium"><?= htmlspecialchars($row['roaster']) ?></a>
        				<?php else: ?>
            				<?= htmlspecialchars($row['roaster']) ?>
        				<?php endif; ?>
					</td>
                    <td class="p-3 font-semibold" style="color: var(--secondary);">
        				<?php if(!empty($row['coffee_url'])): ?>
            				<a href="<?= htmlspecialchars($row['coffee_url']) ?>" target="_blank" class="hover:underline"><?= htmlspecialchars($row['coffee_name']) ?></a>
        				<?php else: ?>
            				<?= htmlspecialchars($row['coffee_name']) ?>
        				<?php endif; ?>
					</td>
                    <td class="p-3 text-gray-700"><?= htmlspecialchars($row['type']) ?></td>
                    <td class="p-3 text-gray-700"><?= htmlspecialchars($row['country']) ?></td>
                    <td class="p-3 text-gray-700"><?= htmlspecialchars($row['region']) ?></td>
                    <td class="p-3 text-gray-700"><?= htmlspecialchars($row['farm']) ?></td>
                    <td class="p-3 text-gray-700"><?= htmlspecialchars($row['producer']) ?></td>
                    <td class="p-3 text-gray-700"><?= htmlspecialchars($row['varietals']) ?></td>
                    <td class="p-3 text-gray-700"><?= htmlspecialchars($row['altitude']) ?></td>
                    <td class="p-3 text-gray-700"><?= htmlspecialchars($row['process']) ?></td>
                    <td class="p-3 text-gray-700"><?= htmlspecialchars($row['roast_level']) ?></td>
                    <td class="p-3 italic text-gray-600"><?= htmlspecialchars($row['tasting_notes']) ?></td>
                    <td class="p-3 text-gray-700"><?= htmlspecialchars($row['weight']) ?> g</td>
                    <td class="p-3 whitespace-nowrap font-medium"><?= number_format($row['price'], 2) ?> €</td>
                    <td class="p-3 whitespace-nowrap font-medium" style="color: var(--secondary);"><?= number_format($row['price_per_100g'], 2) ?> €</td>
                    <td class="p-3 text-gray-700"><?= htmlspecialchars($row['drunk_as']) ?></td>
					<td class="p-3 whitespace-nowrap text-gray-700"><?= htmlspecialchars($row['purchase_date']) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php if ($total_count === 0): ?>
        <div class="text-center py-12">
            <p class="text-gray-500 text-lg">No coffees yet. Start adding your collection!</p>
            <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                <a href="edit.php" class="btn mt-4 inline-block">Add Your First Coffee</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<footer class="max-w-7xl mx-auto p-6 mt-10 border-t border-gray-200 text-center">
    <p class="text-sm text-gray-500">
        Inspiration & Idea by <a href="https://hugo.cafe" target="_blank" style="color: var(--secondary);" class="font-semibold hover:underline">Hugo.cafe</a>
    </p>
    <p class="text-xs text-gray-400 mt-1">
        &copy; <?= date('Y') ?> Vix Willems
    </p>
</footer>

</body>
</html>