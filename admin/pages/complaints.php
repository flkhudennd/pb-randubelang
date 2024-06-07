<?php
require_once('config.php');

$complaintsPerPage = 5;
$currentPage = isset($_GET['page_number']) ? (int)$_GET['page_number'] : 1;
$currentPage = max(1, $currentPage);
$offset = ($currentPage - 1) * $complaintsPerPage;

$queryTotalComplaints = "SELECT COUNT(*) AS total FROM complaints";
$resultTotalComplaints = $conn->query($queryTotalComplaints);

if (!$resultTotalComplaints) {
    die("Error querying total complaints: " . $conn->error);
}

$rowTotalComplaints = $resultTotalComplaints->fetch_assoc();
$totalComplaints = $rowTotalComplaints['total'];
$totalPages = ceil($totalComplaints / $complaintsPerPage);

$query = "SELECT * FROM complaints LIMIT $offset, $complaintsPerPage";
$result = $conn->query($query);

if (!$result) {
    die("Error querying complaints: " . $conn->error);
}

$complaints = [];
while ($row = $result->fetch_assoc()) {
    $complaints[] = $row;
}

$startEntry = $offset + 1;
$endEntry = min($offset + $complaintsPerPage, $totalComplaints);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint List</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <h1>Complaints</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="/portal-berita/dashboard" class="text-dark">Dashboard</a></li>
            <li class="breadcrumb-item active">Complaint List</li>
        </ol>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <p><i class="fas fa-table mr-2"></i> Complaint List</p>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col" class="col-lg-5">Message</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($complaints) > 0): ?>
                            <?php foreach ($complaints as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['complaint_id']) ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['email_sender']) ?></td>
                                    <td><?= htmlspecialchars($row['complaint_message']) ?></td>
                                    <td>
                                        <?= strftime('%A, %d %B %Y', strtotime($row['created_at'])) ?><br>
                                        <?= date('H:i', strtotime($row['created_at'])) ?> WIB
                                    </td>
                                    <td>
                                        <a href="/portal-berita/dashboard?page=delete-complaint&id=<?= $row['complaint_id'] ?>&page_number=<?= $currentPage ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this complaint?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6">No complaints found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6">
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <p class="lead mt-4">Showing <?= $startEntry ?> to <?= $endEntry ?> of <?= $totalComplaints ?> entries</p>
                                </div>
                                <nav aria-label="Pagination">
                                        <ul class="pagination justify-content-center mt-4">
                                            <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                                                <a class="page-link" href="?page=complaints&page_number=<?= $currentPage - 1 ?>" tabindex="-1" aria-disabled="<?= ($currentPage <= 1) ? 'true' : 'false' ?>">&lt;</a>
                                            </li>
                                            <?php if ($currentPage > 3): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=complaints&page_number=1">1</a>
                                                </li>
                                                <?php if ($currentPage > 4): ?>
                                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                                                <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                                                    <a class="page-link" href="?page=complaints&page_number=<?= $i ?>"><?= $i ?></a>
                                                </li>
                                            <?php endfor; ?>
                                            <?php if ($currentPage < $totalPages - 2): ?>
                                                <?php if ($currentPage < $totalPages - 3): ?>
                                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                                <?php endif; ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=complaints&page_number=<?= $totalPages ?>"><?= $totalPages ?></a>
                                                </li>
                                            <?php endif; ?>
                                            <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                                                <a class="page-link" href="?page=complaints&page_number=<?= $currentPage + 1 ?>" aria-disabled="<?= ($currentPage >= $totalPages) ? 'true' : 'false' ?>">&gt;</a>
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
