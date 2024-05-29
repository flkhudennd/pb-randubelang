<?php
require_once('config.php');

if(isset($_GET['id']) && !empty($_GET['id'])) {
    $news_id = $_GET['id'];
    
    // Delete news
    $query = "DELETE FROM news WHERE news_id = $news_id";
    if($conn->query($query) === TRUE) {
        header("Location: /portal-berita/dashboard?page=news");
        exit();
    } else {
        echo "Error deleting news: " . $conn->error;
    }
} else {
    echo "Invalid news ID.";
    exit();
}
?>