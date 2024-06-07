<?php
require_once('config.php');

$message = ''; 

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $category_id = $_GET['id'];
    
    $query = "SELECT category_name FROM categories WHERE category_id = $category_id";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $category_name = $row['category_name'];
    } else {
        $message = "Category not found.";
    }
} else {
    $message = "Invalid category ID.";
}

if (isset($_POST['edit_category'])) {
    $new_category_name = $_POST['category_name'];

    $query = "UPDATE categories SET category_name = '$new_category_name' WHERE category_id = $category_id";
    if ($conn->query($query) === TRUE) {
        $message = "Category updated successfully.";
    } else {
        $message = "Error updating category: " . $conn->error;
    }
}
?>

<main>
    <div class="container-fluid">
        <h1>Edit Category</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="/portal-berita/dashboard" class="text-dark">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/portal-berita/dashboard?page=categories" class="text-dark">Categories</a></li>
            <li class="breadcrumb-item active">Edit Category</li>
        </ol>
        <?php if ($message): ?>
            <div class="alert alert-info" role="alert">
                <?= $message ?>
            </div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="category_name">Category Name:</label>
                <input type="text" class="form-control" id="category_name" name="category_name" value="<?php echo htmlspecialchars($category_name); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary" name="edit_category">Update</button>
        </form>
    </div>
</main>
