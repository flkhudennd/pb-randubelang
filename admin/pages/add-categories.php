<?php
require_once('config.php'); 

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = filter_input(INPUT_POST, 'category_name', FILTER_SANITIZE_STRING);

    if ($category_name) {
        $stmt = $conn->prepare("INSERT INTO categories (category_name) VALUES (?)");

        if ($stmt) {
            $stmt->bind_param("s", $category_name);

            if ($stmt->execute()) {
                $message = 'Kategori berhasil ditambahkan.';
            } else {
                $message = 'Terjadi kesalahan saat menambahkan kategori.';
            }

            $stmt->close();
        } else {
            $message = 'Gagal menyiapkan pernyataan: ' . $conn->error;
        }
    } else {
        $message = 'Nama kategori tidak boleh kosong.';
    }
}

$conn->close();
?>

<main>
    <div class="container-fluid">
        <h1>Add Category</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="/portal-berita/dashboard" class="text-dark">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/portal-berita/dashboard?page=categories" class="text-dark">Category List</a></li>
            <li class="breadcrumb-item active">Add Category</li>
        </ol>
        <?php if ($message): ?>
            <div class="alert alert-info" role="alert">
                <?= $message ?>
            </div>
        <?php endif; ?>
        <div class="card mb-4">
            <div class="card-body">
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="category_name">Category Name</label>
                        <input type="text" name="category_name" id="category_name" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1">Add</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script src="assets/vendor/jquery/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>