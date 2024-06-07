<?php
require_once('config.php');

$query = "SELECT * FROM categories";
$categoriesResult = $conn->query($query);
?>

<body>
  <!-- Navigation -->
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="/portal-berita">Plus Randubelang</a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle mr-2" href="#" id="navbarDropdownNewsCategory" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Kategori Berita
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownNewsCategory">
                    <?php
                        while ($category = $categoriesResult->fetch_assoc()) {
                            echo '<a class="dropdown-item" href="category?id=' . $category['category_id'] . '">' . htmlspecialchars($category['category_name']) . '</a>';
                        }
                    ?>
                    </div>
                </li>
                <form class="form-inline my-2 my-lg-0" action="/portal-berita/search" method="GET">
                    <input class="form-control mr-sm-2" type="search" name="query" placeholder="Cari" aria-label="Search" required>
                    <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Cari</button>
                </form>
                </ul>
            </div>
        </div>
    </nav>
</body>
