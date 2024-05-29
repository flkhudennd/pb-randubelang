<?php
require_once('config.php');

$query = "SELECT * FROM categories";
$result = $conn->query($query);
?>

<main>
    <div class="container-fluid">
        <h1>Categories</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="/portal-berita/dashboard" class="text-dark">Dashboard</a></li>
            <li class="breadcrumb-item active">Category List</li>
        </ol>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <p><i class="fas fa-table mr-2"></i>
                    Category List</p>
                    <a href="/portal-berita/dashboard?page=add-categories" class="btn btn-primary" role="button" aria-pressed="true">Add Category</a>
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
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['category_name']) . "</td>";
                            echo "<td>";
                            echo '<a href="/portal-berita/dashboard?page=edit-category&id=' . $row['category_id'] . '" class="btn btn-sm btn-warning text-white" data-toggle="tooltip" data-placement="right" title="Edit"><i class="fas fa-file-pen"></i></a> ';
                            echo '<a href="/portal-berita/dashboard?page=delete-category&id=' . $row['category_id'] . '" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="right" title="Delete" onclick="return confirm(\'Are you sure you want to delete this category?\')"><i class="fas fa-trash"></i></a>';
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>No categories found.</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
