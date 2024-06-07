<?php
require_once('config.php');

$categoryPerPage = 5;
$currentPage = isset($_GET['page_number']) ? (int)$_GET['page_number'] : 1;
$currentPage = max(1, $currentPage);
$offset = ($currentPage - 1) * $categoryPerPage;

$queryTotalCategory = "SELECT COUNT(*) AS total FROM categories";
$resultTotalCategories = $conn->query($queryTotalCategory);

if (!$resultTotalCategories) {
    die("Error querying total categories: " . $conn->error);
}

$rowTotalCategory = $resultTotalCategories->fetch_assoc();
$totalCategories = $rowTotalCategory['total'];
$totalPages = ceil($totalCategories / $categoryPerPage);

$query = "SELECT * FROM categories
          LIMIT $offset, $categoryPerPage";
$result = $conn->query($query);

if (!$result) {
    die("Error querying categories: " . $conn->error);
}

$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

$startEntry = $offset + 1;
$endEntry = min($offset + $categoryPerPage, $totalCategories);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category List</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <h1>Categories</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="/portal-berita/dashboard" class="text-dark">Dashboard</a></li>
            <li class="breadcrumb-item active">Category List</li>
        </ol>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <p><i class="fas fa-table mr-2"></i> Category List</p>
                    <a href="/portal-berita/dashboard?page=add-category" class="btn btn-primary" role="button" aria-pressed="true">Add Category</a>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col" class="col-lg-10">Category</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($categories) > 0): ?>
                            <?php foreach ($categories as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['category_name']) ?></td>
                                    <td>
                                        <a href="/portal-berita/dashboard?page=edit-category&id=<?= $row['category_id'] ?>&page_number=<?= $currentPage ?>" class="btn btn-sm btn-warning text-white" title="Edit"><i class="fas fa-file-pen"></i></a>
                                        <a href="/portal-berita/dashboard?page=delete-category&id=<?= $row['category_id'] ?>&page_number=<?= $currentPage ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this category?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="2">No categories found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4">
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <p class="lead mt-2">Showing <?= $startEntry ?> to <?= $endEntry ?> of <?= $totalCategories ?> entries</p>
                                    </div>
                                        <nav aria-label="Pagination">
                                        <ul class="pagination justify-content-center mt-4">
                                            <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                                                <a class="page-link" href="?page=categories&page_number=<?= $currentPage - 1 ?>" tabindex="-1" aria-disabled="<?= ($currentPage <= 1) ? 'true' : 'false' ?>">&lt;</a>
                                            </li>
                                            <?php if ($currentPage > 3): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=categories&page_number=1">1</a>
                                                </li>
                                                <?php if ($currentPage > 4): ?>
                                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                                                <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                                                    <a class="page-link" href="?page=categories&page_number=<?= $i ?>"><?= $i ?></a>
                                                </li>
                                            <?php endfor; ?>
                                            <?php if ($currentPage < $totalPages - 2): ?>
                                                <?php if ($currentPage < $totalPages - 3): ?>
                                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                                <?php endif; ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=categories&page_number=<?= $totalPages ?>"><?= $totalPages ?></a>
                                                </li>
                                            <?php endif; ?>
                                            <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                                                <a class="page-link" href="?page=categories&page_number=<?= $currentPage + 1 ?>" aria-disabled="<?= ($currentPage >= $totalPages) ? 'true' : 'false' ?>">&gt;</a>
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
