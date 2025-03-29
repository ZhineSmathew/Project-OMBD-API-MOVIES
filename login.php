<?php
// login.php - User login page
require __DIR__. "/config.php";

$message = '';

session_start();
if (isset($_SESSION['registered']) && $_SESSION['registered']) {
    $message = "Registration successful! You can now login.";
    unset($_SESSION['registered']);  // Clear the session flag
}   

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $result = loginUser($username, $password);

    if ($result['success']) {
        // Redirect to dashboard after successful login
        header("Location: dashboard.php");
        exit;
    } else {
        $message = $result['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-center">User Login</h2>

        <?php if (!empty($message)): ?>
        <div class="<?php echo strpos($message, 'successful') !== false ? 'text-green-600' : 'text-red-600'; ?> text-center mb-4">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>
            
        <form method="post" action="" novalidate class="space-y-4">
            <div>
                <label for="username" class="block text-gray-700">Username:</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label for="password" class="block text-gray-700">Password:</label>
                <input type="password" id="password" name="password" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400">
            </div>

            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">Login</button>

            <p class="mt-4 text-center text-gray-600">Don't have an account? <a href="register.php" class="text-blue-500 hover:underline">Register here</a></p>
        </form>
    </div>
</body>
</html>
