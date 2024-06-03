<?php
require_once('config.php');

$newsPerPage = 5;
$currentPage = isset($_GET['page_number']) ? (int)$_GET['page_number'] : 1;
$currentPage = max(1, $currentPage);
$offset = ($currentPage - 1) * $newsPerPage;

$queryTotalNews = "SELECT COUNT(*) AS total FROM news";
$resultTotalNews = $conn->query($queryTotalNews);

if (!$resultTotalNews) {
    die("Error querying total news: " . $conn->error);
}

$rowTotalNews = $resultTotalNews->fetch_assoc();
$totalNews = $rowTotalNews['total'];
$totalPages = ceil($totalNews / $newsPerPage);

$query = "SELECT news.*, categories.category_name 
          FROM news 
          LEFT JOIN categories ON news.category_id = categories.category_id 
          LIMIT $offset, $newsPerPage";
$result = $conn->query($query);

if (!$result) {
    die("Error querying news: " . $conn->error);
}

$news = [];
while ($row = $result->fetch_assoc()) {
    $news[] = $row;
}

$startEntry = $offset + 1;
$endEntry = min($offset + $newsPerPage, $totalNews);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News List</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <h1>News</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="/portal-berita/dashboard" class="text-dark">Dashboard</a></li>
            <li class="breadcrumb-item active">News List</li>
        </ol>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <p><i class="fas fa-table mr-2"></i> News List</p>
                    <a href="/portal-berita/dashboard?page=add-news" class="btn btn-primary" role="button" aria-pressed="true">Add News</a>
                </div>
            </div>
            <div class="card-body">
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
                        <?php if (count($news) > 0): ?>
                            <?php foreach ($news as $row): ?>
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
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4">No news found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4">
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <p class="lead mt-4">Showing <?= $startEntry ?> to <?= $endEntry ?> of <?= $totalNews ?> entries</p>
                                </div>
                                <nav aria-label="Pagination">
                                    <ul class="pagination justify-content-center mt-4">
                                        <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                                            <a class="page-link" href="?page=news&page_number=<?= $currentPage - 1 ?>" tabindex="-1" aria-disabled="<?= ($currentPage <= 1) ? 'true' : 'false' ?>">&lt;</a>
                                        </li>
                                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                            <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                                                <a class="page-link" href="?page=news&page_number=<?= $i ?>"><?= $i ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                                            <a class="page-link" href="?page=news&page_number=<?= $currentPage + 1 ?>" aria-disabled="<?= ($currentPage >= $totalPages) ? 'true' : 'false' ?>">&gt;</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
