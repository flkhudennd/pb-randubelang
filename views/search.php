<?php
require_once('config.php'); 

$searchQuery = filter_input(INPUT_GET, 'query', FILTER_SANITIZE_STRING);

if ($searchQuery) {
    $sql = "SELECT news.*, categories.category_name 
            FROM news 
            LEFT JOIN categories ON news.category_id = categories.category_id 
            WHERE news.title LIKE ? OR news.content LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = '%' . $searchQuery . '%';
    $stmt->bind_param('ss', $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    header('Location: /portal-berita');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/modern-news.css" rel="stylesheet">
</head>
<body>
    <?php require_once('templates/navigation.php'); ?>

    <div class="container">
        <ol class="breadcrumb mt-4 mb-3">
            <li class="breadcrumb-item"><a href="/portal-berita">Home</a></li>
            <li class="breadcrumb-item active">Halaman Hasil Pencarian</li>
        </ol>
        <h1>Hasil pencarian untuk '<?= htmlspecialchars($searchQuery) ?>'</h1>
        <hr>
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($news = $result->fetch_assoc()): ?>
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
                <p>Tidak ada hasil pencarian '<?= htmlspecialchars($searchQuery) ?>'</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
