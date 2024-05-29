<?php
require_once('config.php');

$query = "SELECT * FROM complaints";
$result = $conn->query($query);
?>

<main>
    <div class="container-fluid">
        <h1>Complaints</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="/portal-berita/dashboard" class="text-dark">Dashboard</a></li>
            <li class="breadcrumb-item active">Complaints List</li>
        </ol>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <p><i class="fas fa-table mr-2"></i>
                    Complaints List</p>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th scope="col" class="col-md-1">#</th>
                            <th scope="col" class="col-md-2">Name</th>
                            <th scope="col" class="col-md-2">Email</th>
                            <th scope="col" class="col-md-5">Message</th>
                            <th scope="col" class="col-md-2">Created At</th>
                            <th scope="col" class="col-md-1">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['complaint_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email_sender']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['complaint_message']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                            echo "<td>";
                            echo '<a href="/portal-berita/dashboard?page=delete-complaint&id=' . $row['complaint_id'] . '" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="right" title="Delete"><i class="fas fa-trash"></i></a>';
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