<?php
require_once('config.php');

$query = "SELECT * FROM news
            LEFT JOIN categories ON news.category_id = categories.category_id";
$result = $conn->query($query);
?>

<main>
    <div class="container-fluid">
        <h1>News</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="/portal-berita/dashboard" class="text-dark">Dashboard</a></li>
            <li class="breadcrumb-item active">News List</li>
        </ol>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <p><i class="fas fa-table mr-2"></i>
                    News List</p>
                    <a href="/portal-berita/dashboard?page=add-news" class="btn btn-primary" role="button" aria-pressed="true">Add News</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col" class="col-lg-6">Title</th>
                            <th scope="col">Category</th>
                            <th scope="col"">Created At</th>
                            <th scope="col" class="col-lg-2">Action</th>
                        </tr> 
                    </thead>
                    <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $created_at = new DateTime($row['created_at']);
                            
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['category_name']) . "</td>";
                            echo "<td>";
                            echo strftime('%A, %d %B %Y', strtotime($row['created_at'])) . "<br>";
                            echo date('H:i', strtotime($row['created_at'])) . " WIB";
                            echo "</td>";
                            echo "<td>";
                            echo '<a href="/portal-berita/dashboard?page=view-news&id=' . $row['news_id'] . ' " class="btn btn-sm btn-primary text-white" data-toggle="tooltip" data-placement="right" title="View"><i class="fas fa-eye"></i></a> ';
                            echo '<a href="/portal-berita/dashboard?page=edit-news&id=' . $row['news_id'] . ' " class="btn btn-sm btn-warning text-white" data-toggle="tooltip" data-placement="right" title="Edit"><i class="fas fa-file-pen"></i></a> ';
                            echo '<a href="/portal-berita/dashboard?page=delete-news&id=' . $row['news_id'] . ' " class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="right" title="Delete" onclick="return confirm(\'Are you sure you want to delete this news?\')"><i class="fas fa-trash"></i></a>';
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No news found.</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
