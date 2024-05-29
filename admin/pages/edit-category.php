<?php
require_once('config.php');

if(isset($_GET['id']) && !empty($_GET['id'])) {
    $category_id = $_GET['id'];
    
    $query = "SELECT category_name FROM categories WHERE category_id = $category_id";
    $result = $conn->query($query);
    
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $category_name = $row['category_name'];
    } else {
        echo "Category not found.";
        exit();
    }
} else {
    echo "Invalid category ID.";
    exit();
}

if(isset($_POST['edit_category'])) {
    $new_category_name = $_POST['category_name'];

    $query = "UPDATE categories SET category_name = '$new_category_name' WHERE category_id = $category_id";
    if($conn->query($query) === TRUE) {
        header("Location: /portal-berita/dashboard?page=categories");
        exit();
    } else {
        echo "Error updating category: " . $conn->error;
    }
}
?>

<main>
    <div class="container-fluid">
        <h1>Edit Category</h1>
        <form method="post">
            <div class="form-group">
                <label for="category_name">Category Name:</label>
                <input type="text" class="form-control" id="category_name" name="category_name" value="<?php echo htmlspecialchars($category_name); ?>">
            </div>
            <button type="submit" class="btn btn-primary" name="edit_category">Update</button>
        </form>
    </div>
</main>
