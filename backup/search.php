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
    <title>Search Results</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/modern-news.css" rel="stylesheet">
</head>
<body>
    <?php require_once('templates/navigation.php'); ?>

    <div class="container mt-4">
        <h1>Search Results for "<?= htmlspecialchars($searchQuery) ?>"</h1>
        <div class="card mb-4">
            <div class="card-body">
                <?php if ($result->num_rows > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col" class="col-lg-6">Title</th>
                                <th scope="col">Category</th>
                                <th scope="col">Created At</th>
                                <th scope="col" class="col-lg-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['title']) ?></td>
                                    <td><?= htmlspecialchars($row['category_name']) ?></td>
                                    <td><?= strftime('%A, %d %B %Y', strtotime($row['created_at'])) . "<br>" . date('H:i', strtotime($row['created_at'])) . " WIB" ?></td>
                                    <td>
                                        <a href="/portal-berita/dashboard?page=view-news&id=<?= $row['news_id'] ?>" class="btn btn-sm btn-primary text-white" title="View"><i class="fas fa-eye"></i></a>
                                        <a href="/portal-berita/dashboard?page=edit-news&id=<?= $row['news_id'] ?>" class="btn btn-sm btn-warning text-white" title="Edit"><i class="fas fa-file-pen"></i></a>
                                        <a href="/portal-berita/dashboard?page=delete-news&id=<?= $row['news_id'] ?>&page_number=<?= $currentPage ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this news?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No results found for "<?= htmlspecialchars($searchQuery) ?>"</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php require_once('templates/footer.php'); ?>

    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
