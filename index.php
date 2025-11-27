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
<style>
.column-selector {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-out;
}
.column-selector.open {
    max-height: 500px;
}
.sortable-header {
    cursor: pointer;
    user-select: none;
}
.sortable-header:hover {
    background-color: rgba(248, 178, 209, 0.1);
}
.sort-arrow {
    display: inline-block;
    margin-left: 4px;
    opacity: 0.3;
}
.sort-arrow.active {
    opacity: 1;
}
</style>
</head>
<body class="bg-tertiary">

<nav class="shadow mb-6 bg-secondary">
    <div class="max-w-full mx-auto px-6">
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

<div class="max-w-full mx-auto px-6 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold" style="color: var(--secondary);">📊 Coffee Dataset</h1>
            <p class="text-sm text-gray-600 mt-1">Complete collection of all tracked coffees</p>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="toggleColumnSelector()" class="text-gray-600 hover:text-gray-900 transition" title="Column Settings">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </button>
            <div class="text-right">
                <p class="text-sm text-gray-600">Total Entries</p>
                <p class="text-2xl font-bold" style="color: var(--secondary);"><?= $total_count ?></p>
            </div>
        </div>
    </div>

    <!-- Column Selector -->
    <div id="columnSelector" class="column-selector bg-white rounded-lg shadow-lg mb-6 p-6" style="border: 2px solid var(--tertiary);">
        <h3 class="font-bold text-lg mb-4" style="color: var(--secondary);">⚙️ Select Columns to Display</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" class="column-toggle" data-column="0" checked>
                <span class="text-sm">Roaster</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" class="column-toggle" data-column="1" checked>
                <span class="text-sm">Coffee Name</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" class="column-toggle" data-column="2" checked>
                <span class="text-sm">Type</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" class="column-toggle" data-column="3" checked>
<span class="text-sm">Country</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" class="column-toggle" data-column="4" checked>
                <span class="text-sm">Region</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" class="column-toggle" data-column="5" checked>
                <span class="text-sm">Farm</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" class="column-toggle" data-column="6" checked>
                <span class="text-sm">Producer</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" class="column-toggle" data-column="7" checked>
                <span class="text-sm">Varietals</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" class="column-toggle" data-column="8" checked>
                <span class="text-sm">Altitude</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" class="column-toggle" data-column="9" checked>
                <span class="text-sm">Process</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" class="column-toggle" data-column="10" checked>
                <span class="text-sm">Roast Level</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" class="column-toggle" data-column="11" checked>
                <span class="text-sm">Tasting Notes</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" class="column-toggle" checked data-column="12">
                <span class="text-sm">Weight</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" class="column-toggle" data-column="13" checked>
                <span class="text-sm">Price</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" class="column-toggle" data-column="14" checked>
                <span class="text-sm">€/100g</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" class="column-toggle" data-column="15" checked>
                <span class="text-sm">Drunk As</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" class="column-toggle" data-column="16" checked>
                <span class="text-sm">Purchase Date</span>
            </label>
        </div>
    </div>

    <div class="overflow-x-auto shadow-lg rounded-lg bg-white" style="border: 2px solid var(--tertiary);">
        <table id="coffeeTable" class="min-w-full text-sm text-left border-collapse">
            <thead>
                <tr>
                    <th class="p-3 sortable-header" data-column="0">Roaster <span class="sort-arrow">↕</span></th>
                    <th class="p-3 sortable-header" data-column="1">Coffee Name <span class="sort-arrow">↕</span></th>
                    <th class="p-3 sortable-header" data-column="2">Type <span class="sort-arrow">↕</span></th>
                    <th class="p-3 sortable-header" data-column="3">Country <span class="sort-arrow">↕</span></th>
                    <th class="p-3 sortable-header" data-column="4">Region <span class="sort-arrow">↕</span></th>
                    <th class="p-3 sortable-header" data-column="5">Farm <span class="sort-arrow">↕</span></th>
                    <th class="p-3 sortable-header" data-column="6">Producer <span class="sort-arrow">↕</span></th>
                    <th class="p-3 sortable-header" data-column="7">Varietals <span class="sort-arrow">↕</span></th>
                    <th class="p-3 sortable-header" data-column="8">Altitude <span class="sort-arrow">↕</span></th>
                    <th class="p-3 sortable-header" data-column="9">Process <span class="sort-arrow">↕</span></th>
                    <th class="p-3 sortable-header" data-column="10">Roast Level <span class="sort-arrow">↕</span></th>
                    <th class="p-3 sortable-header" data-column="11">Tasting Notes <span class="sort-arrow">↕</span></th>
                    <th class="p-3 sortable-header" data-column="12">Weight <span class="sort-arrow">↕</span></th>
                    <th class="p-3 sortable-header" data-column="13">Price <span class="sort-arrow">↕</span></th>
                    <th class="p-3 sortable-header" data-column="14">€/100g <span class="sort-arrow">↕</span></th>
                    <th class="p-3 sortable-header" data-column="15">Drunk As <span class="sort-arrow">↕</span></th>
					<th class="p-3 sortable-header" data-column="16">Purchase Date <span class="sort-arrow">↕</span></th>
                </tr>
            </thead>

            <tbody>
            <?php 
            $result->data_seek(0); // Reset pointer
            while($row = $result->fetch_assoc()): ?>
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

<footer class="max-w-full mx-auto p-6 mt-10 border-t border-gray-200 text-center">
    <p class="text-sm text-gray-500">
        Inspiration & Idea by <a href="https://hugo.cafe" target="_blank" style="color: var(--secondary);" class="font-semibold hover:underline">Hugo.cafe</a>
    </p>
    <p class="text-xs text-gray-400 mt-1">
        &copy; <?= date('Y') ?> Vix Willems
    </p>
</footer>

<script>
// Toggle column selector
function toggleColumnSelector() {
    const selector = document.getElementById('columnSelector');
    selector.classList.toggle('open');
}

// Column visibility
document.querySelectorAll('.column-toggle').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const columnIndex = parseInt(this.dataset.column);
        const table = document.getElementById('coffeeTable');
        const headerCells = table.querySelectorAll('thead th');
        const rows = table.querySelectorAll('tbody tr');
        
        headerCells[columnIndex].style.display = this.checked ? '' : 'none';
        rows.forEach(row => {
            row.children[columnIndex].style.display = this.checked ? '' : 'none';
        });
        
        // Save preference
        localStorage.setItem(`column_${columnIndex}_visible`, this.checked);
    });
    
    // Load saved preferences
    const columnIndex = parseInt(checkbox.dataset.column);
    const saved = localStorage.getItem(`column_${columnIndex}_visible`);
    if (saved !== null) {
        checkbox.checked = saved === 'true';
        checkbox.dispatchEvent(new Event('change'));
    }
});

// Sortable columns
let sortDirection = {};
document.querySelectorAll('.sortable-header').forEach(header => {
    header.addEventListener('click', function() {
        const columnIndex = parseInt(this.dataset.column);
        const table = document.getElementById('coffeeTable');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        // Toggle sort direction
        sortDirection[columnIndex] = sortDirection[columnIndex] === 'asc' ? 'desc' : 'asc';
        
        // Sort rows
        rows.sort((a, b) => {
            const aVal = a.children[columnIndex].textContent.trim();
            const bVal = b.children[columnIndex].textContent.trim();
            
            // Try numeric comparison first
            const aNum = parseFloat(aVal.replace(/[^\d.-]/g, ''));
            const bNum = parseFloat(bVal.replace(/[^\d.-]/g, ''));
            
            if (!isNaN(aNum) && !isNaN(bNum)) {
                return sortDirection[columnIndex] === 'asc' ? aNum - bNum : bNum - aNum;
            }
            
            // String comparison
            return sortDirection[columnIndex] === 'asc' 
                ? aVal.localeCompare(bVal) 
                : bVal.localeCompare(aVal);
        });
        
        // Reappend sorted rows
        rows.forEach(row => tbody.appendChild(row));
        
        // Update arrow indicators
        document.querySelectorAll('.sort-arrow').forEach(arrow => arrow.classList.remove('active'));
        const arrow = this.querySelector('.sort-arrow');
        arrow.classList.add('active');
        arrow.textContent = sortDirection[columnIndex] === 'asc' ? '↑' : '↓';
    });
});
</script>

</body>
</html>