<?php
require_once('config.php');

$query = "SELECT news.*, categories.category_name FROM news 
          LEFT JOIN categories ON news.category_id = categories.category_id";
$result = $conn->query($query);

if (!$result) {
    die("Error querying news: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All News</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/modern-news.css" rel="stylesheet">
</head>
<body>
    <?php require_once('templates/navigation.php'); ?>
    <div class="container mt-4">
        <h1 class="mb-4">All News</h1>
        <hr>
        <div class="row">
            <?php while ($news = $result->fetch_assoc()): ?>
                <div class="col-lg-4 mb-4">
                    <a href="news-detail?id=<?php echo $news['news_id']; ?>" style="text-decoration: none; color: inherit;">
                        <div class="card h-100">
                            <img class="card-img-top" src="<?php echo $news['img_url']; ?>" alt="<?php echo $news['title']; ?>" loading="lazy">
                            <div class="card-body">
                                <p class="news-category"><?php echo $news['category_name']; ?></p>
                                <h4 class="card-title"><?php echo $news['title']; ?></h4>
                                <p class="card-text"><?php echo substr($news['content'], 0, 120); ?>...</p>
                            </div>
                            <div class="card-footer text-muted">
                                <p class="date-news"><?php echo strftime('%A, %d %B %Y', strtotime($news['created_at'])); ?></p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php require_once('templates/footer.php'); ?>

    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
