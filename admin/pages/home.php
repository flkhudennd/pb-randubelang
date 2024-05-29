<?php require_once('config.php'); ?>

<main>
    <div class="container-fluid">
        <h1>Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body display-4">
                    <?php
                        $query = "SELECT COUNT(*) AS total_category FROM categories";

                        $result = mysqli_query($conn, $query);
                        
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            $totalCategories = $row['total_category'];
                            echo $totalCategories;
                        } else {
                            echo "Error counting categories: " . mysqli_error($conn);
                        }
                        ?>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white" href="/portal-berita/dashboard?page=categories">Total Categories</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body display-4">
                        <?php
                        $query = "SELECT COUNT(*) AS total_news FROM news";

                        $result = mysqli_query($conn, $query);
                        
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            $totalNews = $row['total_news'];
                            echo $totalNews;
                        } else {
                            echo "Error counting news: " . mysqli_error($conn);
                        }
                        ?>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white" href="/portal-berita/dashboard?page=news">Total News</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white mb-4">
                    <div class="card-body display-4">
                        <?php
                        $query = "SELECT COUNT(*) AS total_complaints FROM complaints";

                        $result = mysqli_query($conn, $query);
                        
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            $totalComplaints = $row['total_complaints'];
                            echo $totalComplaints;
                        } else {
                            echo "Error counting complaints: " . mysqli_error($conn);
                        }
                        ?>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white" href="/portal-berita/dashboard?page=complaints">Total Complaints</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
