<?php
require_once 'config.php';

$news_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $newsContent = $_POST['news-content'];
    $categoryId = $_POST['category'];
    $image_caption = $_POST['img-caption'];

    // Format the news content
    $paragraphs = explode("\n", $newsContent);
    $formattedContent = '';
    foreach ($paragraphs as $paragraph) {
        $trimmedParagraph = trim($paragraph);
        if (!empty($trimmedParagraph)) {
            $formattedContent .= '<p>' . nl2br(strip_tags($trimmedParagraph, '<b><i><u><strong><em>')) . '</p>';
        }
    }

    if ($_FILES['file-image']['size'] > 0) {
        $image = $_FILES['file-image'];
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
            $message = "Sorry, only JPG, JPEG, and PNG files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            $message = "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($image["tmp_name"], $targetFile)) {
                $stmt = $conn->prepare("UPDATE news 
                                        SET title = ?, content = ?, img_url = ?, img_caption = ?, category_id = ?
                                        WHERE news_id = ?");
                $stmt->bind_param("ssssii", $title, $formattedContent, $targetFile, $image_caption, $categoryId, $news_id);
                
                if ($stmt->execute()) {
                    $message = "The news has been updated.";
                } else {
                    $message = "Error: " . $stmt->error;
                }
            } else {
                $message = "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $stmt = $conn->prepare("UPDATE news 
                                SET title = ?, content = ?, img_caption = ?, category_id = ?
                                WHERE news_id = ?");
        $stmt->bind_param("sssii", $title, $formattedContent, $image_caption, $categoryId, $news_id);

        if ($stmt->execute()) {
            $message = "The news has been updated.";
        } else {
            $message = "Error: " . $stmt->error;
        }
    }
}

$stmt = $conn->prepare("SELECT * FROM news WHERE news_id = ?");
$stmt->bind_param("i", $news_id);
$stmt->execute();
$result = $stmt->get_result();
$news = $result->fetch_assoc();
$stmt->close();

$query = "SELECT category_id, category_name FROM categories";
$categoriesResult = $conn->query($query);
?>

<main>
    <div class="container-fluid">
        <h1>Edit News</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="/portal-berita/dashboard" class="text-dark">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/portal-berita/dashboard?page=news" class="text-dark">News List</a></li>
            <li class="breadcrumb-item active">Edit News</li>
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
                        <input type="text" name="title" id="title" class="form-control mb-3" value="<?php echo htmlspecialchars($news['title']); ?>" required>
                        
                        <label for="news-content">Content</label>
                        <textarea class="form-control mb-3" name="news-content" id="news-content" rows="15" required><?php echo htmlspecialchars($news['content']); ?></textarea>

                        <label for="file-image">Change Image</label>
                        <input type="file" class="form-control-file mb-3" id="file-image" name="file-image">

                        <label for="img-caption">Change Image Caption</label>
                        <small class="text-muted">Leave it blank if you don't want to change the image.</small>
                        <input type="text" name="img-caption" id="img-caption" class="form-control mb-3" value="<?php echo htmlspecialchars($news['img_caption']); ?>" required>
                        
                        <label for="categories">Choose Category</label>
                        <select id="categories" name="category" class="form-control">
                            <?php
                            if ($categoriesResult->num_rows > 0) {
                                while ($row = $categoriesResult->fetch_assoc()) {
                                    $selected = ($row['category_id'] == $news['category_id']) ? "selected" : "";
                                    echo '<option value="' . $row['category_id'] . '" ' . $selected . '>' . htmlspecialchars($row['category_name']) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</main>
