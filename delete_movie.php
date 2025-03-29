<?php
require __DIR__ . "/config.php";

// Save favorite movies
$result = deleteMovieByid();
exit;

function deleteMovieByid() {
    global $conn;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $movieId = $_POST['movie_id'];

        // Prepare the SQL statement to delete the movie from the favorites
        $stmt = $conn->prepare("UPDATE favorite_movies SET is_deleted = 1 WHERE id = ?");
        $stmt->bind_param("i", $movieId);

        if ($stmt->execute()) {
            echo json_encode(['status' => "success", 'message' => "Movie removed from favorites successfully!"]);
        } else {
            echo json_encode(['status' => "error", 'message' => "Failed to remove movie: " . $stmt->error]);
        }
    }
}




