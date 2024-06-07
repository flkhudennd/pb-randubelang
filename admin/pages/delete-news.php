<?php
require_once('config.php');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $news_id = (int)$_GET['id'];
    $page_number = isset($_GET['page_number']) ? (int)$_GET['page_number'] : 1;

    // Ambil nama file gambar sebelum menghapus berita
    $query_img = "SELECT img_url FROM news WHERE news_id = $news_id";
    $result_img = $conn->query($query_img);

    if ($result_img && $result_img->num_rows > 0) {
        $row_img = $result_img->fetch_assoc();
        $img_filename = $row_img['img_url'];

        // Hapus file gambar
        $img_path = $img_filename;
        if (file_exists($img_path)) {
            unlink($img_path);
        }
        
        // Hapus berita dari database
        $query = "DELETE FROM news WHERE news_id = $news_id";
        if ($conn->query($query) === TRUE) {
            header("Location: /portal-berita/dashboard?page=news&page_number=$page_number");
            exit();
        } else {
            echo "Error deleting news: " . $conn->error;
        }
    } else {
        echo "Error retrieving image filename: " . $conn->error;
    }
} else {
    echo "Invalid news ID.";
    exit();
}
?>
