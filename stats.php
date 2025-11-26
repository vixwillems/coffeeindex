<?php
require 'config.php';

// Fetch current coffee
$current_sql = "SELECT * FROM beans WHERE is_current = 1 LIMIT 1";
$current_result = $conn->query($current_sql);
$current_coffee = $current_result->fetch_assoc();

// Fetch all beans for statistics
$all_beans = $conn->query("SELECT * FROM beans");

// Calculate statistics
$total_bags = 0;
$total_weight = 0;
$total_spent = 0;
$altitudes = [];
$roasters = [];
$countries = [];
$processes = [];
$varietals = [];
$tasting_notes_all = [];

while ($bean = $all_beans->fetch_assoc()) {
    $total_bags++;
    $total_weight += $bean['weight'];
    $total_spent += $bean['price'];
    
    if (!empty($bean['altitude']) && is_numeric($bean['altitude'])) {
        $altitudes[] = (int)$bean['altitude'];
    }
    
    if (!empty($bean['roaster'])) {
        $roasters[$bean['roaster']] = ($roasters[$bean['roaster']] ?? 0) + 1;
    }
    
    if (!empty($bean['country'])) {
        $countries[$bean['country']] = ($countries[$bean['country']] ?? 0) + 1;
    }
    
    if (!empty($bean['process'])) {
        $processes[$bean['process']] = ($processes[$bean['process']] ?? 0) + 1;
    }
    
    if (!empty($bean['varietals'])) {
        $vars = array_map('trim', explode(',', $bean['varietals']));
        foreach ($vars as $v) {
            $varietals[$v] = ($varietals[$v] ?? 0) + 1;
        }
    }
    
    if (!empty($bean['tasting_notes'])) {
        $notes = array_map('trim', explode(',', $bean['tasting_notes']));
        foreach ($notes as $note) {
            $tasting_notes_all[$note] = ($tasting_notes_all[$note] ?? 0) + 1;
        }
    }
}

// Sort arrays
arsort($roasters);
arsort($countries);
arsort($processes);
arsort($varietals);
arsort($tasting_notes_all);

// Calculate averages
$avg_weight = $total_bags > 0 ? $total_weight / $total_bags : 0;
$avg_price_per_bag = $total_bags > 0 ? $total_spent / $total_bags : 0;
$avg_price_per_100g = $total_weight > 0 ? ($total_spent / $total_weight) * 100 : 0;

$avg_altitude = !empty($altitudes) ? array_sum($altitudes) / count($altitudes) : 0;
$min_altitude = !empty($altitudes) ? min($altitudes) : 0;
$max_altitude = !empty($altitudes) ? max($altitudes) : 0;

// Country emoji mapping
$country_emojis = [
    'Ethiopia' => '🇪🇹',
    'Kenya' => '🇰🇪',
    'Brasil' => '🇧🇷',
    'Brazil' => '🇧🇷',
    'Honduras' => '🇭🇳',
    'Colombia' => '🇨🇴',
    'Guatemala' => '🇬🇹',
    'Costa Rica' => '🇨🇷',
    'Rwanda' => '🇷🇼',
    'Burundi' => '🇧🇮'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Coffee Statistics - Coffee Index</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="flex flex-col min-h-screen bg-tertiary">

<nav class="shadow mb-6 bg-secondary">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between h-16">
            <div class="flex space-x-8 items-center">
                <a href="index.php" class="text-xl font-bold transition">
  					<span class="text-tertiary">☕ Coffee</span><span class="text-primary">Index</span>
				</a>
                
                <a href="index.php" class="font-medium border-b-2 border-transparent transition menu-item">Index</a>
                <a href="stats.php" class="font-medium border-b-2 border-transparent transition menu-item" style="color: var(--primary) !important;">Statistics</a>
            </div>

            <div class="flex items-center space-x-4">
                <a href="https://vixwillems.eu" class="btn text-xs">
                    ← Back to vixwillems.eu
                </a>

                <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                    <a href="admin.php" class="btn text-xs">
                        🔧 Admin
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

<div class="max-w-6xl mx-auto p-6 flex-grow">
    <div class="mb-6">
        <h1 class="text-3xl font-bold" style="color: var(--secondary);">📊 Coffee Statistics</h1>
        <p class="text-sm text-gray-600 mt-1">Insights and analytics from your coffee collection</p>
    </div>

    <!-- Currently Drinking -->
    <?php if ($current_coffee): ?>
    <div class="flex justify-center mb-10">
        <div class="text-white p-8 rounded-2xl shadow-xl text-center max-w-md w-full current-coffee-card">
            <h2 class="text-2xl font-extrabold mb-3">☕ Currently Drinking</h2>
            <p class="font-bold text-xl"><?= htmlspecialchars($current_coffee['coffee_name']) ?></p>
            <p class="mt-2">
                <span class="font-semibold"><?= htmlspecialchars($current_coffee['roaster']) ?></span><br>
                <?= $country_emojis[$current_coffee['country']] ?? '🌍' ?> <?= htmlspecialchars($current_coffee['country']) ?><br>
                <span class="italic">"<?= htmlspecialchars($current_coffee['tasting_notes']) ?>"</span>
            </p>
        </div>
    </div>
    <?php else: ?>
    <div class="flex justify-center mb-10">
        <div class="text-white p-8 rounded-2xl shadow-xl text-center max-w-md w-full" style="background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);">
            <h2 class="text-2xl font-extrabold mb-3">☕ Currently Drinking</h2>
            <p class="text-lg">No coffee selected</p>
            <p class="text-sm mt-2 opacity-80">Set a coffee as "currently drinking" in the admin panel</p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Stats tiles -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-xl shadow stats-card">
            <h2 class="text-xl font-semibold mb-2 text-gray-700">Consumption</h2>
            <div class="flex items-baseline space-x-2">
                <p class="text-4xl font-bold stat-number"><?= $total_bags ?></p>
                <p class="text-gray-500">bags total</p>
            </div>
            <div class="mt-2 text-sm text-gray-600">
                <p>Avg weight: <strong><?= number_format($avg_weight, 0) ?> g</strong></p>
                <p>Total consumed: <strong><?= number_format($total_weight / 1000, 2) ?> kg</strong></p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow stats-card">
            <h2 class="text-xl font-semibold mb-3 text-gray-700">Price Stats</h2>
            <div class="grid grid-cols-2 gap-2 text-sm">
                <div>Total Spent:</div>
                <div class="font-bold text-right"><?= number_format($total_spent, 2) ?> €</div>
                <div>Avg / bag:</div>
                <div class="font-bold text-right"><?= number_format($avg_price_per_bag, 2) ?> €</div>
                <div>Avg / 100g:</div>
                <div class="font-bold text-right"><?= number_format($avg_price_per_100g, 2) ?> €</div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow stats-card">
            <h2 class="text-xl font-semibold mb-3 text-gray-700">Altitude (MASL)</h2>
            <?php if (!empty($altitudes)): ?>
            <div class="grid grid-cols-2 gap-2 text-sm">
                <div>Average:</div>
                <div class="font-bold text-right"><?= number_format($avg_altitude, 0) ?> m</div>
                <div>Min:</div>
                <div class="font-bold text-right"><?= number_format($min_altitude, 0) ?> m</div>
                <div>Max:</div>
                <div class="font-bold text-right"><?= number_format($max_altitude, 0) ?> m</div>
            </div>
            <?php else: ?>
            <p class="text-sm text-gray-500">No altitude data available</p>
            <?php endif; ?>
        </div>
    </div>

    <h2 class="text-2xl font-bold mb-6 pb-2 section-heading">Breakdowns</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 align-top">
        
        <!-- Roasters -->
        <?php if (!empty($roasters)): ?>
        <div class="bg-white p-5 rounded-lg shadow h-full breakdown-card">
            <h3 class="font-bold text-lg mb-4 text-gray-700">Roasters</h3>
            <ol class="list-decimal pl-5 space-y-2 text-sm text-gray-700">
                <?php foreach (array_slice($roasters, 0, 10) as $roaster => $count): ?>
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2"><?= htmlspecialchars($roaster) ?></span>
                        <span class="counter"><?= $count ?></span>
                    </div>
                </li>
                <?php endforeach; ?>
            </ol>
        </div>
        <?php endif; ?>

        <!-- Countries -->
        <?php if (!empty($countries)): ?>
        <div class="bg-white p-5 rounded-lg shadow h-full breakdown-card">
            <h3 class="font-bold text-lg mb-4 text-gray-700">Countries</h3>
            <ol class="list-decimal pl-5 space-y-2 text-sm text-gray-700">
                <?php foreach ($countries as $country => $count): ?>
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2">
                            <?= $country_emojis[$country] ?? '🌍' ?> <?= htmlspecialchars($country) ?>
                        </span>
                        <span class="counter"><?= $count ?></span>
                    </div>
                </li>
                <?php endforeach; ?>
            </ol>
        </div>
        <?php endif; ?>

        <!-- Processes -->
        <?php if (!empty($processes)): ?>
        <div class="bg-white p-5 rounded-lg shadow h-full breakdown-card">
            <h3 class="font-bold text-lg mb-4 text-gray-700">Processes</h3>
            <ol class="list-decimal pl-5 space-y-2 text-sm text-gray-700">
                <?php foreach ($processes as $process => $count): ?>
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2"><?= htmlspecialchars($process) ?></span>
                        <span class="counter"><?= $count ?></span>
                    </div>
                </li>
                <?php endforeach; ?>
            </ol>
        </div>
        <?php endif; ?>

        <!-- Varieties -->
        <?php if (!empty($varietals)): ?>
        <div class="bg-white p-5 rounded-lg shadow h-full breakdown-card">
            <h3 class="font-bold text-lg mb-4 text-gray-700">Varieties</h3>
            <ol class="list-decimal pl-5 space-y-2 text-sm text-gray-700">
                <?php foreach (array_slice($varietals, 0, 10) as $varietal => $count): ?>
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2"><?= htmlspecialchars($varietal) ?></span>
                        <span class="counter"><?= $count ?></span>
                    </div>
                </li>
                <?php endforeach; ?>
            </ol>
        </div>
        <?php endif; ?>
		
        <!-- Tasting Notes -->
        <?php if (!empty($tasting_notes_all)): ?>
		<div class="bg-white p-5 rounded-lg shadow h-full breakdown-card">
            <h3 class="font-bold text-lg mb-4 text-gray-700">Tasting Notes</h3>
            <ol class="list-decimal pl-5 space-y-2 text-sm text-gray-700">
                <?php foreach (array_slice($tasting_notes_all, 0, 15) as $note => $count): ?>
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2"><?= htmlspecialchars($note) ?></span>
                        <span class="counter"><?= $count ?></span>
                    </div>
                </li>
                <?php endforeach; ?>
            </ol>
        </div>
        <?php endif; ?>
    </div>

    <?php if ($total_bags === 0): ?>
    <div class="text-center py-12">
        <p class="text-gray-500 text-lg">No data yet. Start tracking your coffee!</p>
    </div>
    <?php endif; ?>
</div>

<footer class="max-w-7xl mx-auto p-6 mt-10 border-t border-gray-200 text-center w-full">
    <p class="text-sm text-gray-500">
        Inspiration & Idea by <a href="https://hugo.cafe" target="_blank" style="color: var(--secondary);" class="font-semibold hover:underline">Hugo.cafe</a>
    </p>
    <p class="text-xs text-gray-400 mt-1">
        &copy; <?= date('Y') ?> Coffee Index
    </p>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const card = document.querySelector('.current-coffee-card');
  
  if (card) {
    card.addEventListener('mousemove', function(e) {
      const rect = card.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      
      const xPercent = (x / rect.width) * 100;
      const yPercent = (y / rect.height) * 100;
      
      card.style.background = `
        radial-gradient(circle at ${xPercent}% ${yPercent}%, 
          rgba(255, 255, 255, 0.2) 0%, 
          transparent 50%),
        linear-gradient(135deg, #f8b2d1 0%, #024757 100%)
      `;
    });
    
    card.addEventListener('mouseleave', function() {
      card.style.background = 'linear-gradient(135deg, #f8b2d1 0%, #024757 100%)';
    });
  }
});
</script>

</body>
</html>