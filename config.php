<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
// config.php - Database configuration
$host = 'sql12.freesqldatabase.com';
$database = 'sql12770136';
$username = 'sql12770136';
$password = 'ailriQqzAz';

// database connection
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// User registration function
if (!function_exists('registerUser')) {
    function registerUser($username, $email, $password, $confirmPassword)
    {
        global $conn;

        // Validate input fields
        if (empty($username)) {
            return ["success" => false, "message" => "Username is required"];
        }
        
        if (empty($email)) {
            return ["success" => false, "message" => "Email is required"];
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["success" => false, "message" => "Invalid email format"];
        }
        
        if (empty($password)) {
            return ["success" => false, "message" => "Password is required"];
        }
        
        if (strlen($password) < 8) {
            return ["success" => false, "message" => "Password must contain at least 8 characters"];
        }        
        
        if (!preg_match("/[a-zA-Z]/", $password)) {
            return ["success" => false, "message" => "Password must contain at least one letter"];
        }
        
        if (!preg_match("/[0-9]/", $password)) {
            return ["success" => false, "message" => "Password must contain at least one numeric value"];
        }
        
        if ($password !== $confirmPassword) {
            return ["success" => false, "message" => "Passwords do not match"];
        }
        

        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return ["success" => false, "message" => "Username or email already exists"];
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        if ($stmt->execute()) {
            return ["success" => true, "message" => "Registration successful"];
        } else {
            return ["success" => false, "message" => "Registration failed: " . $stmt->error];
        }
    }
}

// User login function
if (!function_exists('loginUser')) {
    function loginUser($username, $password)
    {
        global $conn;

        // Validate input
        if (empty($username) || empty($password)) {
            return ["success" => false, "message" => "Username and password are required"];
        }

        // Get user from database
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Start session and set user data
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['logged_in'] = true;

                return ["success" => true, "message" => "Login successful", "user_id" => $user['id']];
            } else {
                return ["success" => false, "message" => "Invalid password"];
            }
        } else {
            return ["success" => false, "message" => "User not found"];
        }
    }
}

// Logout function
if (!function_exists('logoutUser')) {
    function logoutUser()
    {
        session_start();
        $_SESSION = array();
        session_destroy();
        return ["success" => true, "message" => "Logout successful"];
    }
}

// Check if user is logged in
if (!function_exists('isLoggedIn')) {
    function isLoggedIn()
    {
        session_start();
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
}
