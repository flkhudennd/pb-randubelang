<?php
require_once('config.php');

if(isset($_GET['id']) && !empty($_GET['id'])) {
    $category_id = $_GET['id'];
    
    // Delete complaint
    $query = "DELETE FROM complaints WHERE complaint_id = $category_id";
    if($conn->query($query) === TRUE) {
        header("Location: /portal-berita/dashboard?page=complaints");
        exit();
    } else {
        echo "Error deleting complaint: " . $conn->error;
    }
} else {
    echo "Invalid complaint ID.";
    exit();
}
?>