<?php
require_once('config.php');

$news_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($news_id) {
    $stmt = $conn->prepare("SELECT news.*, categories.category_name FROM news 
                            LEFT JOIN categories ON news.category_id = categories.category_id 
                            WHERE news.news_id = ?");
    $stmt->bind_param("i", $news_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $news = $result->fetch_assoc();
    $stmt->close();

    if ($news) {
        $created_at = new DateTime($news['created_at']);
        setlocale(LC_TIME, 'id_ID.utf8');
        $formatted_date = strftime('%A, %d-%m-%Y %H:%M WIB', $created_at->getTimestamp());
    } else {
        echo "<p>News not found.</p>";
        exit;
    }
} else {
    echo "<p>Invalid news ID.</p>";
    exit;
}
?>

<main>
    <div class="container-fluid">
        <h1>View News</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="/portal-berita/dashboard" class="text-dark">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/portal-berita/dashboard?page=news" class="text-dark">News List</a></li>
            <li class="breadcrumb-item active">View News</li>
        </ol>
        <div class="card mb-4">
            <div class="card-header">
                <h2><?php echo htmlspecialchars($news['title']); ?></h2>
            </div>
            <div class="card-body">
                <h5><?php echo htmlspecialchars($news['category_name']); ?></h5>
                <h6 class="mb-4"><?php echo htmlspecialchars($formatted_date); ?></h6>
                <?php if (!empty($news['img_url'])): ?>
                    <img src="<?php echo htmlspecialchars($news['img_url']); ?>" alt="News Image" class="img-fluid mb-3">
                <?php endif; ?>
                <blockquote class="blockquote text-center">
                    <?php echo htmlspecialchars($news['img_caption']); ?>
                </blockquote>
                <div class="text-justify">
                    <?php echo $news['content']; ?>
                </div>
            </div>
        </div>
    </div>
</main>
