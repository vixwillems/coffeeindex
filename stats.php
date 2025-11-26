<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Coffee Stats</title>
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
                <a href="" class="text-xl font-bold transition bg-secondary">
  					<span class="text-tertiary">☕ Coffee</span><span class="text-primary">Index</span>
				</a>
                
                <a href="" class="font-medium border-b-2 border-transparent transition menu-item">Index</a>
                <a href="stats" class="font-medium border-b-2 border-transparent transition menu-item">Statistics</a>
            </div>

            <div class="flex items-center space-x-4">
                <a href="https://vixwillems.eu" class="btn">
                    ← Back to vixwillems.eu
                </a>

                <a href="login.php" class="text-xs text-gray-300 hover:text-gray-500">Login</a>
            </div>
        </div>
    </div>
</nav>

<div class="max-w-6xl mx-auto p-6 flex-grow">
    <h1 class="text-3xl font-bold mb-6">📊 Coffee Statistics</h1>

    <!-- Currently Drinking (highlighted, centered) -->
    <div class="flex justify-center mb-10">
        <div class="text-white p-8 rounded-2xl shadow-xl text-center max-w-md w-full current-coffee-card">
            <h2 class="text-2xl font-extrabold mb-3">☕ Currently Drinking</h2>
            <p class="font-bold text-xl truncate">Kenya Kianjege (Peaberry)</p>
            <p class="mt-2">
                <span class="font-semibold">MOK</span><br>
                🇰🇪 Kenya<br>
                <span class="italic">"Fruity and bright, blue grape, lemon, white tea, bold body"</span>
            </p>
        </div>
    </div>

    <!-- Stats tiles -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-xl shadow stats-card">
            <h2 class="text-xl font-semibold mb-2 text-gray-700">Consumption</h2>
            <div class="flex items-baseline space-x-2">
                <p class="text-4xl font-bold stat-number">3</p>
                <p class="text-gray-500">bags total</p>
            </div>
            <div class="mt-2 text-sm text-gray-600">
                <p>Avg weight: <strong>275 g</strong></p>
                <p>Total consumed: <strong>0.83 kg</strong></p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow stats-card">
            <h2 class="text-xl font-semibold mb-3 text-gray-700">Price Stats</h2>
            <div class="grid grid-cols-2 gap-2 text-sm">
                <div>Total Spent:</div>
                <div class="font-bold text-right">54.70 €</div>
                <div>Avg / bag:</div>
                <div class="font-bold text-right">18.23 €</div>
                <div>Avg / 100g:</div>
                <div class="font-bold text-right">6.63 €</div>
            </div>
        </div>

        <!-- MASL Tile -->
        <div class="bg-white p-6 rounded-xl shadow stats-card">
            <h2 class="text-xl font-semibold mb-3 text-gray-700">Altitude (MASL)</h2>
            <div class="grid grid-cols-2 gap-2 text-sm">
                <div>Average:</div>
                <div class="font-bold text-right">1,950 m</div>
                <div>Min:</div>
                <div class="font-bold text-right">1,950 m</div>
                <div>Max:</div>
                <div class="font-bold text-right">1,950 m</div>
            </div>
        </div>
    </div>


    <h2 class="text-2xl font-bold mb-6 pb-2 section-heading">Breakdowns</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 align-top">
        
        <div class="bg-white p-5 rounded-lg shadow h-full breakdown-card">
            <h3 class="font-bold text-lg mb-4 text-gray-700">Roasters</h3>
            <ol class="list-decimal pl-5 space-y-2 text-sm text-gray-700">
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2">Square Mile Coffee Roasters</span>
                        <span class="counter">1</span>
                    </div>
                </li>
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2">Wakuli</span>
                        <span class="counter">1</span>
                    </div>
                </li>
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2">MOK</span>
                        <span class="counter">1</span>
                    </div>
                </li>
            </ol>
        </div>

        <div class="bg-white p-5 rounded-lg shadow h-full breakdown-card">
            <h3 class="font-bold text-lg mb-4 text-gray-700">Countries</h3>
            <ol class="list-decimal pl-5 space-y-2 text-sm text-gray-700">
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2">🇪🇹 Ethiopia</span>
                        <span class="counter">2</span>
                    </div>
                </li>
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2">🇧🇷 Brasil</span>
                        <span class="counter">1</span>
                    </div>
                </li>
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2">🇭🇳 Honduras</span>
                        <span class="counter">1</span>
                    </div>
                </li>
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2">🇰🇪 Kenya</span>
                        <span class="counter">1</span>
                    </div>
                </li>
            </ol>
        </div>

        <div class="bg-white p-5 rounded-lg shadow h-full breakdown-card">
            <h3 class="font-bold text-lg mb-4 text-gray-700">Processes</h3>
            <ol class="list-decimal pl-5 space-y-2 text-sm text-gray-700">
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2">Washed</span>
                        <span class="counter">2</span>
                    </div>
                </li>
            </ol>
        </div>

        <div class="bg-white p-5 rounded-lg shadow h-full breakdown-card">
            <h3 class="font-bold text-lg mb-4 text-gray-700">Varieties</h3>
            <ol class="list-decimal pl-5 space-y-2 text-sm text-gray-700">
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2">Gibirinna 74110</span>
                        <span class="counter">1</span>
                    </div>
                </li>
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2">Serto 74112</span>
                        <span class="counter">1</span>
                    </div>
                </li>
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2">SL28</span>
                        <span class="counter">1</span>
                    </div>
                </li>
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2">SL34</span>
                        <span class="counter">1</span>
                    </div>
                </li>
            </ol>
        </div>
		
		<div class="bg-white p-5 rounded-lg shadow h-full breakdown-card">
            <h3 class="font-bold text-lg mb-4 text-gray-700">Tasting Notes</h3>
            <ol class="list-decimal pl-5 space-y-2 text-sm text-gray-700">
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2">Nectarine</span>
                        <span class="counter">1</span>
                    </div>
                </li>
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2">Toffee</span>
                        <span class="counter">1</span>
                    </div>
                </li>
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2">Floral</span>
                        <span class="counter">1</span>
                    </div>
                </li>
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2">Chocolate</span>
                        <span class="counter">1</span>
                    </div>
                </li>
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2">Nuttiness</span>
                        <span class="counter">1</span>
                    </div>
                </li>
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2">Fruity</span>
                        <span class="counter">1</span>
                    </div>
                </li>
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2">Fruity and bright</span>
                        <span class="counter">1</span>
                    </div>
                </li>
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2">blue grape</span>
                        <span class="counter">1</span>
                    </div>
                </li>
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2">lemon</span>
                        <span class="counter">1</span>
                    </div>
                </li>
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2">white tea</span>
                        <span class="counter">1</span>
                    </div>
                </li>
                <li class="pl-2">
                    <div class="flex justify-between items-center w-full">
                        <span class="font-medium truncate pr-2">bold body</span>
                        <span class="counter">1</span>
                    </div>
                </li>
            </ol>
        </div>

    </div>
</div>

<footer class="max-w-7xl mx-auto p-6 mt-10 border-t border-gray-200 text-center w-full">
    <p class="text-sm text-gray-500">
        Inspiration & Idea by <a href="https://hugo.cafe" target="_blank" class="text-blue-600 hover:underline">Hugo.cafe</a>
    </p>
    <p class="text-xs text-gray-400 mt-1">
        &copy; 2025 Coffee Index
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
      
      // Calculate percentage position
      const xPercent = (x / rect.width) * 100;
      const yPercent = (y / rect.height) * 100;
      
      // Update gradient position
      card.style.background = `
        radial-gradient(circle at ${xPercent}% ${yPercent}%, 
          rgba(255, 255, 255, 0.2) 0%, 
          transparent 50%),
        linear-gradient(135deg, #f8b2d1 0%, #024757 100%)
      `;
    });
    
    card.addEventListener('mouseleave', function() {
      // Reset to original gradient
      card.style.background = 'linear-gradient(135deg, #f8b2d1 0%, #024757 100%)';
    });
  }
});
</script>

</body>
</html>