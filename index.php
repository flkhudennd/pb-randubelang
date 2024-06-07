<?php
session_start();

// Routing utama
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request = rtrim($request, '/');
$request = str_replace("/portal-berita", "", $request);

switch ($request) {
    case '':
        require_once __DIR__ . "/views/home.php";
        break;
    case '/news-detail':
        require_once __DIR__ . "/views/news-detail.php";
        break;
    case '/pb-admin':
        require_once __DIR__ . "/admin/pb-admin.php";
        break;
    case '/dashboard':
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
            require_once __DIR__ . "/admin/dashboard.php";
        } else {
            header('Location: /portal-berita/pb-admin');
            exit;
        }
      break;
    case '/category':
        require_once __DIR__ . "/views/category.php";
        break;
    case '/all-news':
        require_once __DIR__ . "/views/all-news.php";
        break;
    case '/search':
        require_once __DIR__ . "/views/search.php";
        break;
        case '/add-profile':
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                require_once __DIR__ . "/admin/add-profile.php";
            } else {
                header('Location: /portal-berita/pb-admin');
                exit;
            }
            break;
        case '/edit-profile':
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                require_once __DIR__ . "/admin/edit-profile.php";
            } else {
                header('Location: /portal-berita/pb-admin');
                exit;
            }
            break;
    case '/logout':
        require_once __DIR__ . "/admin/logout.php";
        break;
    default:
        http_response_code(404);
        require_once __DIR__ . "/views/404.php";
}
?>
