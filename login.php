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
        $error = "Incorrect password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="style.css">
</head>
<body class="h-screen flex justify-center items-center">

<div class="bg-white p-8 rounded shadow-md w-96">
    <h1 class="font-bold mb-6 text-center text-secondary">💼<br>
Admin Login</h1>
    
    <?php if($error): ?>
  <p class="text-red-500 text-sm mb-4 text-center"><?= $error ?></p>
    <?php endif; ?>

    <form method="post">
        <label class="block mb-2 text-sm font-bold text-gray-700">Password</label>
        <input type="password" name="password" class="w-full border rounded p-2 mb-4" required autofocus>
        <button type="submit" class="w-full p-2 rounded btn">Login</button>
    </form>
    <div class="mt-4 text-center">
        <a href="index.php" class="text-sm text-gray-500 hover:underline">← Back to Index</a>
    </div>
</div>

</body>
</html>