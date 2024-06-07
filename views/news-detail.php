<?php 
require_once('config.php');

$news_id = isset($_GET['id']) ? $_GET['id'] : die('Error: Berita tidak ditemukan.');

$query = "SELECT news.*, categories.category_name FROM news
            LEFT JOIN categories ON news.category_id = categories.category_id
            WHERE news.news_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $news_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('Error: Berita tidak ditemukan.');
}

$news = $result->fetch_assoc();
$stmt->close();

$queryCategories = "SELECT * FROM categories";
$resultCategories = $conn->query($queryCategories);

$category_id = $news['category_id'];

$queryRelatedNews = "SELECT * FROM news WHERE category_id = ? AND news_id != ? LIMIT 4";
$stmtRelated = $conn->prepare($queryRelatedNews);
$stmtRelated->bind_param("ii", $category_id, $news_id);
$stmtRelated->execute();
$resultRelated = $stmtRelated->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($news['title']); ?></title>
    <link href="assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/modern-news.css" rel="stylesheet">
</head>
<body>
    <?php require_once('templates/navigation.php'); ?>

    <div class="container">
        <ol class="breadcrumb mt-4 mb-3">
            <li class="breadcrumb-item"><a href="/portal-berita">Home</a></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($news['title']); ?></li>
        </ol>
        <h1 class="mb-3"><?php echo htmlspecialchars($news['title']); ?></h1>

        <div class="row">
            <div class="col-lg-8">
              <h4><?php echo htmlspecialchars($news['category_name']); ?></h4>
              <p>Admin Randubelang, <?php echo strftime('%A %d %B %Y', strtotime($news['created_at'])); ?><p>
              <img class="img-fluid rounded" src="<?php echo htmlspecialchars($news['img_url']); ?>" alt="" loading="lazy">
              <blockquote class="blockquote text-center mb-4">
                  <?php echo htmlspecialchars($news['img_caption']); ?>
              </blockquote>
              <div class="news-content">
                  <?php echo $news['content']; ?>
              </div>
            </div>

            <div class="col-md-4 mt-4">
                <!-- Sidebar Widgets Column -->
                <!-- <div class="card mb-4 mt-5">
                    <h5 class="card-header">Kolom Pencarian</h5>
                    <div class="card-body">
                        <div class="input-group" action="/portal-berita/search" method="GET">
                            <input class="form-control mr-sm-2" type="search" name="query" placeholder="Cari" aria-label="Search" required>
                            <span class="input-group-btn">
                                <button class="btn btn-outline-info" type="submit">Cari</button>
                            </span>
                        </div>
                    </div>
                </div> -->

                <div class="card mb-4 mt-5">
                    <h5 class="card-header">Kategori</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <ul class="list-unstyled mb-0">
                                    <?php 
                                    $counter = 0; 
                                    while ($category = $resultCategories->fetch_assoc()): 
                                        if ($counter < $resultCategories->num_rows / 2): ?>
                                            <li><a href="category?id=<?php echo $category['category_id']; ?>"><?php echo htmlspecialchars($category['category_name']); ?></a></li>
                                        <?php endif;
                                        $counter++;
                                    endwhile; ?>
                                </ul>
                            </div>
                            <div class="col-lg-6">
                                <ul class="list-unstyled mb-0">
                                    <?php 
                                    $counter = 0; 
                                    $resultCategories->data_seek(0); 
                                    while ($category = $resultCategories->fetch_assoc()): 
                                        if ($counter >= $resultCategories->num_rows / 2): ?>
                                            <li><a href="category?id=<?php echo $category['category_id']; ?>"><?php echo htmlspecialchars($category['category_name']); ?></a></li>
                                        <?php endif;
                                        $counter++;
                                    endwhile; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <hr>

        <!-- Related News -->
        <h2 class="my-4">Related News</h2>
            <div class="row mb-4">    
                <?php while ($relatedNews = $resultRelated->fetch_assoc()): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="news-detail?id=<?php echo $relatedNews['news_id']; ?>" style="text-decoration: none; color: inherit;">
                        <div class="card h-100">
                            <img class="card-img-top" src="<?php echo htmlspecialchars($relatedNews['img_url']); ?>" alt="" loading="lazy">
                            <div class="card-body">
                                <p class="news-category"><?php echo htmlspecialchars($news['category_name']); ?></p>
                                <h5 class="card-title"><?php echo htmlspecialchars($relatedNews['title']); ?></h5>
                            </div>
                            <div class="card-footer text-muted">
                                <p class="date-news"><?php echo strftime('%A %d %B %Y', strtotime($relatedNews['created_at'])); ?></p>
                            </div>
                        </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    <?php require_once('templates/footer.php'); ?>

    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
