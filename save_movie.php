<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require __DIR__ . "/config.php";

// Save favorite movies
$result = addFavourite();
exit;

function addFavourite() {
    global $conn;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $poster = $_POST['poster'];
        $userId = $_POST['userId'];

        // Prepare the SQL statement with the correct field names
        $stmt = $conn->prepare("INSERT INTO favorite_movies (movie_title, poster_url, user_id) VALUES (?, ?, ?)");
        // Bind the parameters
        $stmt->bind_param("ssi", $title, $poster, $userId);
        
        if ($stmt->execute()) {
            return ["success" => true, "message" => "Movie Added to favorites successfully!"];
        } else {
            return ["success" => false, "message" => "Failed to add movie: " . $stmt->error];
        }
    }
}
// get fav movies by id 
function getFavouriteMovies($userId) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT id, movie_title, poster_url FROM favorite_movies WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $movies = [];
    while ($row = $result->fetch_assoc()) {
        $movies[] = $row;
    }
    
    $stmt->close();
    return $movies;
}




