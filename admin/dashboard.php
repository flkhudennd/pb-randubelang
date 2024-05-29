<?php
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: /portal-berita/pb-admin');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="/portal-berita/assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="/portal-berita/assets/css/modern-news.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body>
<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark fixed-top d-flex justify-content-between">
    <a class="navbar-brand" href="">Dashboard Randubelang</a>
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownAdmin" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button"><i class="fas fa-user"></i></a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownAdmin">
                <a class="dropdown-item" href="#">Profil</a>
                <a class="dropdown-item" href="#">Pengaturan</a>
                <hr class="dropdown-divider">
                <a class="dropdown-item btn-danger" href="#" id="logoutLink">Logout</a>
            </div>
        </li>
    </ul>
</nav>

<!-- Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin keluar dari website?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmLogout">Yes, Logout</button>
      </div>
    </div>
  </div>
</div>

<!-- Sidebar -->
<div class="d-flex">
    <div class="sb-sidenav bg-dark">
        <div class="sb-sidenav-menu bg-dark">
            <div class="nav">
                <a class="nav-link mt-3" href="/portal-berita/dashboard">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt mr-3"></i> Dashboard</div>
                </a>
                <a class="nav-link mt-3" href="/portal-berita/dashboard?page=news">
                    <div class="sb-nav-link-icon"><i class="fas fa-newspaper mr-3"></i> News</div>
                </a>
                <a class="nav-link mt-3" href="/portal-berita/dashboard?page=categories">
                    <div class="sb-nav-link-icon"><i class="fas fa-list mr-3"></i> Categories</div>
                </a>
                <a class="nav-link mt-3" href="/portal-berita/dashboard?page=complaints">
                    <div class="sb-nav-link-icon"><i class="fas fa-comment mr-3"></i> Complaints</div>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="sb-sidenav-content">
        <main>
            <div class="container-fluid">
                <?php
                $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING);
                switch ($page) {
                    case 'news':
                        include __DIR__ . '/pages/news.php';
                        break;
                    case 'categories':
                        include __DIR__ . '/pages/categories.php';
                        break;
                    case 'complaints':
                        include __DIR__ . '/pages/complaints.php';
                        break;
                    case 'add-news':
                        include_once __DIR__ . '/pages/add-news.php';
                        break;
                    case 'view-news':
                        include_once __DIR__ . '/pages/view-news.php';
                        break;
                    case 'edit-news':
                        include_once __DIR__ . '/pages/edit-news.php';
                        break;
                    case 'delete-news':
                        include_once __DIR__ . '/pages/delete-news.php';
                        break;
                    case 'add-categories':
                        include_once __DIR__ . '/pages/add-categories.php';
                        break;
                    case 'edit-category':
                        include_once __DIR__ . '/pages/edit-category.php';
                        break;
                    case 'delete-category':
                        include_once __DIR__ . '/pages/delete-category.php';
                        break;
                    case 'delete-complaint':
                        include_once __DIR__ . '/pages/delete-complaint.php';
                        break;
                    default:
                        include __DIR__ . '/pages/home.php';
                }
                ?>
            </div>
        </main>
    </div>
</div>


<!-- jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        $('#logoutLink').on('click', function(e) {
            e.preventDefault();
            $('#logoutModal').modal('show');
        });

        $('#confirmLogout').on('click', function() {
            window.location.href = '/portal-berita/logout';
        });
    });

    
</script>
</body>
</html>
