<?php
require_once ('config.php');

$category_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$stmtCategory = $conn->prepare("SELECT category_name FROM categories WHERE category_id = ?");
$stmtCategory->bind_param("i", $category_id);
$stmtCategory->execute();
$resultCategory = $stmtCategory->get_result();
$category = $resultCategory->fetch_assoc();
$stmtCategory->close();

$category_name = $category ? $category['category_name'] : "Kategori tidak ditemukan";

if ($category) {
    $stmtNews = $conn->prepare("SELECT news_id, title, content, img_url, img_caption, created_at FROM news WHERE category_id = ?");
    $stmtNews->bind_param("i", $category_id);
    $stmtNews->execute();
    $resultNews = $stmtNews->get_result();
    $stmtNews->close();
} else {
    $category_name = "Kategori tidak ditemukan";
    $resultNews = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori <?php echo $category_name; ?></title>
    <link href="assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/modern-news.css" rel="stylesheet">
</head>
<body>
    <?php require_once('templates/navigation.php'); ?>
    <div class="container">
        <h1 class="mt-3 mb-3">Kategori: "<?php echo $category_name; ?>"</h1>
        <hr>
        <div class="row">
            <?php if ($resultNews): ?>
                <?php while ($news = $resultNews->fetch_assoc()): ?>
                    <div class="col-lg-12 mb-2">
                        <a href="news-detail?id=<?php echo $news['news_id']; ?>" style="text-decoration: none; color: inherit;">
                        <div class="row">
                            <div class="col-lg-3">
                                <img class="img-fluid" src="<?php echo htmlspecialchars($news['img_url']); ?>" alt="<?php echo htmlspecialchars($news['img_caption']); ?>">
                            </div>
                            <div class="col-lg-9">
                                <h4><?php echo htmlspecialchars($news['title']); ?></h4>
                                <p class="text-muted"><?php echo strftime('%A, %d %B %Y', strtotime($news['created_at'])); ?></p>
                                <p><?php echo substr($news['content'], 0, 100); ?>...</p>
                            </div>
                        </div>
                        <hr>
                        </a>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Tidak ada berita dalam kategori ini.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
