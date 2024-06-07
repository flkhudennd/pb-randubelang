<?php
require_once('config.php');

if(isset($_GET['id']) && !empty($_GET['id'])) {
    $category_id = $_GET['id'];
    $page_number = isset($_GET['page_number']) ? (int)$_GET['page_number'] : 1;
    
    // Delete category
    $query = "DELETE FROM categories WHERE category_id = $category_id";
    if($conn->query($query) === TRUE) {
        header("Location: /portal-berita/dashboard?page=categories&page_number=$page_number");
        exit();
    } else {
        echo "Error deleting category: " . $conn->error;
    }
} else {
    echo "Invalid category ID.";
    exit();
}
?>