<?php
require_once 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $newsContent = $_POST['news-content'];
    $categoryId = $_POST['category'];
    $image = $_FILES['file-image'];
    $image_caption = $_POST['img-caption'];

    $paragraphs = explode("\n", $newsContent);
    $formattedContent = '';
    foreach ($paragraphs as $paragraph) {
        $trimmedParagraph = trim($paragraph);
        if (!empty($trimmedParagraph)) {
            $formattedContent .= '<p>' . htmlspecialchars($trimmedParagraph) . '</p>';
        }
    }

    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($image["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $check = getimagesize($image["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $message = "File is not an image.";
        $uploadOk = 0;
    }

    if ($image["size"] > 5000000) {
        $message = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        $message = "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($image["tmp_name"], $targetFile)) {
            $stmt = $conn->prepare("INSERT INTO news (title, content, img_url, img_caption, category_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssi", $title, $formattedContent, $targetFile, $image_caption, $categoryId);
            
            if ($stmt->execute()) {
                $message = "The news has been added.";
                header("Location: /portal-berita/dashboard?page=news");
                exit();
            } else {
                $message = "Error: " . $stmt->error;
            }
        } else {
            $message = "Sorry, there was an error uploading your file.";
        }
    }
}

$query = "SELECT category_id, category_name FROM categories";
$categoriesResult = $conn->query($query);
?>

<main>
    <div class="container-fluid">
        <h1>Add News</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="/portal-berita/dashboard" class="text-dark">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/portal-berita/dashboard?page=news" class="text-dark">News List</a></li>
            <li class="breadcrumb-item active">Add News</li>
        </ol>
        <?php if ($message): ?>
            <div class="alert alert-info" role="alert">
                <?= $message ?>
            </div>
        <?php endif; ?>
        <div class="card mb-4">
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title" class="form-control mb-3" required>
                        
                        <label for="news-content">Content</label>
                        <textarea class="form-control mb-3" name="news-content" id="news-content" required></textarea>
                        
                        <label for="file-image">Add Image</label>
                        <input type="file" class="form-control-file mb-3" id="file-image" name="file-image" required>

                        <label for="img-caption">Add Image Caption</label>
                        <input type="text" name="img-caption" id="img-caption" class="form-control mb-3" required>
                        
                        <label for="categories">Choose Category</label>
                        <select id="categories" name="category" class="form-control">
                            <option value="" selected>Choose...</option>
                            <?php
                            if ($categoriesResult->num_rows > 0) {
                                while ($row = $categoriesResult->fetch_assoc()) {
                                    echo '<option value="' . $row['category_id'] . '">' . htmlspecialchars($row['category_name']) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1">Add</button>
                </form>
            </div>
        </div>
    </div>
</main>
