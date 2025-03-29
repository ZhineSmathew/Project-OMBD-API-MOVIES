<?php
// dashboard.php - Protected dashboard page
require_once __DIR__. "/config.php";
require_once __DIR__. "/getMovieDetails.php";

// Check if user is logged in
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'] ?? 'User';
$userId = $_SESSION['user_id'] ?? 0;
if ($userId) {
    $favMovies = getFavouriteMovies($userId);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Search</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="script.js"></script>
</head>

<body class="bg-gray-50 p-8">
    <div class="bg-white shadow-xl rounded-2xl p-8 max-w-2xl mx-auto">
        <div class="header flex justify-between items-center mb-6" data-user-id="<?php echo htmlspecialchars($userId); ?>">
            <h1 class="text-2xl font-bold text-gray-800">Welcome, <?php echo htmlspecialchars($username); ?></h1>
            <a href="logout.php" class="text-red-500 hover:text-red-700 text-lg">Logout</a>
        </div>

        <h3 class="text-xl font-semibold mb-4 text-gray-700">Search Movies</h3>
        <div class="flex gap-3 mb-6">
            <input type="text" id="movieSearch" placeholder="Search by title..." class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-400">
            <button id="searchBtn" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-800">Search</button>
        </div>
        <div id="movieResult" class="mt-6"></div>
    </div>

    <div class="max-w-2xl mx-auto mt-8">
        <h2 class="text-2xl font-semibold mb-4 text-gray-700">Your Favorite Movies List</h2>
        <ul class="space-y-3">
            <?php if (!empty($favMovies)): ?>
            <?php foreach ($favMovies as $movie): ?>
            <li class="flex justify-between items-center bg-white shadow-md p-4 rounded-lg">
                <div class="flex items-center gap-4">
                    <img src="<?php echo htmlspecialchars($movie['poster_url']); ?>" alt="Poster" class="w-12 h-12 rounded-full">
                    <span class="text-gray-800 font-medium"><?php echo htmlspecialchars($movie['movie_title']); ?></span>
                </div>
                <button class="delete-movie px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-700" data-id="<?php echo $movie['id']; ?>">Delete</button>
            </li>
            <?php endforeach; ?>
            <?php else: ?>
            <li class="text-gray-500">No Favorite Movies found.</li>
            <?php endif; ?>
        </ul>
    </div>

    <div id="loader" class="hidden fixed inset-0 bg-gray-100 bg-opacity-75 flex items-center justify-center z-50">
        <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-500"></div>
    </div>
</body>

</html>