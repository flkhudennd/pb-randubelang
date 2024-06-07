<?php 
require_once('config.php');

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("location: dashboard");
    exit;
}

$login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT admin_id, username, password FROM admins WHERE username = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $username, $stored_password);
            if ($stmt->fetch()) {
                if ($password === $stored_password) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['id'] = $id;
                    $_SESSION['username'] = $username;
                    $_SESSION['first_login'] = true;

                    header("location: dashboard");
                    exit;
                } else {
                    $login_err = "Password salah.";
                }
            }
        } else {
            $login_err = "Username tidak ditemukan.";
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - PB Admin</title>
  <link href="assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
  <link href="assets/css/modern-news.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-4 mt-lg-4">
                <?php if (!empty($login_err)) { echo '<div class="alert alert-danger">' . $login_err . '</div>'; } ?>
                <div class="card shadow-lg border-0 rounded-lg mt-lg-5">
                <div class="card-header">
                    <h3 class="text-center font-weight-light my-4">Login Admin</h3>
                </div>
                <div class="card-body">
                    <form action="pb-admin" id="cclogin-frm" method="post">
                    <div class="form-floating mb-3">
                        <label for="inputUsername">Username</label>
                        <input type="text" class="form-control" id="inputUsername" name="username" required>
                    </div>
                    <div class="form-floating mb-3">
                        <label for="passwordlogin">Password</label>  
                        <input type="password" class="form-control" id="passwordlogin" name="password" required>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                        <a class="small" href="/portal-berita">Kembali</a>
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>