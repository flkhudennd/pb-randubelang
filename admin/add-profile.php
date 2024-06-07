<?php
require_once('config.php');

$message = '';

if (isset($_POST['add_admin'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $query = "INSERT INTO admins (username, password, email) VALUES ('$username', '$password', '$email')";
    if ($conn->query($query) === TRUE) {
        $message = "Admin added successfully.";
    } else {
        $message = "Error adding admin: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Admin</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
</head>
<body>
<main>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 mt-lg-3">
            <h1>Add Admin</h1>
            <?php if ($message): ?>
                <div class="alert alert-info" role="alert">
                    <?= $message ?>
                </div>
            <?php endif; ?>
            <form method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                    <a href="/portal-berita/dashboard" class="small">Kembali</a>
                    <button type="submit" class="btn btn-primary" name="add_admin">Add Admin</button>
                </div>
            </form>
            </div>
        </div>
    </div>
</main>

<script src="assets/vendor/jquery/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
