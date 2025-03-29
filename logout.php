<?php

// logout.php - User logout script
require_once 'config.php';

// Logout the user
$result = logoutUser();

// Redirect to login page
header("Location: login.php");
exit;
