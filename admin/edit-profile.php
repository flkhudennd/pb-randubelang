<?php
require_once('config.php');

$message = '';

if (isset($_SESSION['id']) && is_numeric($_SESSION['id'])) {
    $admin_id = intval($_SESSION['id']);  

    $query = "SELECT admin_id, username, email FROM admins WHERE admin_id = $admin_id";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = $row['username'];
        $email = $row['email'];
    } else {
        $message = "Admin not found.";
    }
} else {
    $message = "Invalid admin ID.";
}

if (isset($_POST['edit_admin'])) {
    $new_username = $_POST['username'];
    $new_password = !empty($_POST['password']) ? $_POST['password'] : ''; 
    $new_email = $_POST['email'];

    if ($new_password) {
        $query = "UPDATE admins SET username = '$new_username', password = '$new_password', email = '$new_email' WHERE admin_id = $admin_id";
    } else {
        $query = "UPDATE admins SET username = '$new_username', email = '$new_email' WHERE admin_id = $admin_id";
    }

    if ($conn->query($query) === TRUE) {
        $message = "Proflie updated successfully.";
    } else {
        $message = "Error updating profile: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/modern-news.css" rel="stylesheet">
</head>
<body>
<main>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 mt-lg-3">
            <h1>Edit Profile</h1>
            <?php if ($message): ?>
                <div class="alert alert-info" role="alert">
                    <?= $message ?>
                </div>
            <?php endif; ?>
            <form method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password (leave blank if not changing):</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                    <a class="small" href="/portal-berita/dashboard">Kembali</a>
                    <button type="submit" class="btn btn-primary" name="edit_admin">Update Admin</button>
                </div>    
            </form>
            </div>
        </div>
    </div>
</main>

<!-- jQuery and Bootstrap JS -->
<script src="assets/vendor/jquery/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
