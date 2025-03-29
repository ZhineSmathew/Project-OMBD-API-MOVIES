<?php

require __DIR__ . "/config.php";

// Get favorite movies by user ID
function getFavouriteMovies($userId)
{
    global $conn;

    // Prepare the SQL statement
    if ($stmt = $conn->prepare("SELECT id, movie_title, poster_url, is_fav FROM favorite_movies WHERE user_id = ? AND is_deleted != 1")) {
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
}
