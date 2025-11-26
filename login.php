<?php
require 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    if ($password === ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $error = "Incorrect password. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login - Coffee Index</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<style>
    body {
        background: linear-gradient(135deg, var(--tertiary) 0%, #ffffff 100%);
    }
    .login-card {
        background: white;
        border: 3px solid var(--primary);
        box-shadow: 0 20px 40px rgba(2, 71, 87, 0.15);
    }
    .login-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
</style>
</head>
<body class="h-screen flex flex-col justify-center items-center">

<div class="mb-8 text-center">
    <a href="index.php" class="text-3xl font-bold">
        <span class="text-secondary">☕ Coffee</span><span class="text-primary">Index</span>
    </a>
</div>

<div class="login-card p-10 rounded-2xl w-96 max-w-md">
    <div class="text-center mb-6">
        <div class="text-5xl mb-3">🔐</div>
        <h1 class="text-2xl font-bold login-header mb-2">Admin Login</h1>
        <p class="text-sm text-gray-600">Enter your password to access the admin panel</p>
    </div>
    
    <?php if($error): ?>
        <div class="mb-6 p-3 rounded-lg" style="background-color: rgba(239, 68, 68, 0.1); border-left: 4px solid #ef4444;">
            <p class="text-red-700 text-sm font-medium">⚠️ <?= $error ?></p>
        </div>
    <?php endif; ?>

    <form method="post" class="space-y-5">
        <div>
            <label class="block mb-2 text-sm font-bold" style="color: var(--secondary);">Password</label>
            <input type="password" 
                   name="password" 
                   class="w-full border-2 rounded-lg p-3 transition-all" 
                   style="border-color: var(--tertiary);"
                   placeholder="Enter admin password"
                   required 
                   autofocus
                   onfocus="this.style.borderColor='var(--primary)'"
                   onblur="this.style.borderColor='var(--tertiary)'">
        </div>
        
        <button type="submit" class="w-full btn py-3 text-base font-bold">
            🚀 Login to Dashboard
        </button>
    </form>
    
    <div class="mt-6 text-center">
        <a href="index.php" class="text-sm font-medium" style="color: var(--secondary);" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--secondary)'">
            ← Back to Coffee Index
        </a>
    </div>
</div>

<div class="mt-8 text-center">
    <p class="text-xs text-gray-400">
        &copy; <?= date('Y') ?> Coffee Index
    </p>
</div>

</body>
</html>