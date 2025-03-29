<?php
// register.php - User registration page
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__. "/config.php";
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $message = "Passwords do not match";
    } else {
        $result = registerUser($username, $email, $password, $confirmPassword);
        $message = $result['message'];

        session_start();
        if ($result['success']) {
            $_SESSION['registered'] = true;
            header("Location: login.php");
            exit;
        }

    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Registration</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-100 flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-4 text-center">User Registration</h2>

            <?php if (!empty($message)): ?>
            <div class="<?php echo strpos($message, 'successful') !== false ? 'text-green-600' : 'text-red-600'; ?> text-center mb-4">
                <?php echo $message; ?>
            </div>
            <?php endif; ?>

            <form method="post" action="" novalidate class="space-y-4">
                <div>
                    <label for="username" class="block text-gray-700">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label for="email" class="block text-gray-700">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label for="password" class="block text-gray-700">Password:</label>
                    <input type="password" id="password" name="password" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label for="confirm_password" class="block text-gray-700">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400">
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Register</button>
            </form>

            <p class="mt-4 text-center text-gray-600">Already have an account? <a href="login.php" class="text-blue-500 hover:underline">Login here</a></p>
        </div>
    </body>
</html>