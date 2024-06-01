<?php
require_once('config.php');

$query = "SELECT * FROM categories";
$categoriesResult = $conn->query($query);
?>

<body>
  <!-- Navigation -->
  <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="/portal-berita">Randubelang</a>
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="">Pesan Aduan</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownNewsCategory" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              [News Category]
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownNewsCategory">
              <?php
                while ($category = $categoriesResult->fetch_assoc()) {
                    echo '<a class="dropdown-item" href="category?id=' . $category['category_id'] . '">' . htmlspecialchars($category['category_name']) . '</a>';
                }
              ?>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownBlog" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              [Blog]
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownBlog">
              <a class="dropdown-item" href="">[Blog Home 1]</a>
              <a class="dropdown-item" href="">[Blog Home 2]</a>
              <a class="dropdown-item" href="">[Blog Post]</a>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</body>
