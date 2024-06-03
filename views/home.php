<?php
require_once('config.php');

function displayComplaintForm() {
  echo <<<HTML
  <div class="modal fade" id="aduanModal" tabindex="-1" role="dialog" aria-labelledby="aduanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="aduanModalLabel">Form Aduan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" action="">
            <div class="form-group">
              <label for="message-text" class="col-form-label" rows="4">Pesan:</label>
              <textarea class="form-control" id="message-text" name="message" placeholder="(Tuliskan aduan anda dengan detail, antumkan lokasi kejadian jika diperlukan)" required></textarea>
            </div>
            <div class="form-group">
              <label for="sender-name" class="col-form-label">Nama Pengirim:</label>
              <input type="text" class="form-control" id="sender-name" name="sender-name" required>
            </div>
            <div class="form-group">
              <label for="email" class="col-form-label">Email:</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="anonimCheckbox" name="anonimCheckbox">
              <label class="form-check-label" for="anonimCheckbox">
                Kirim sebagai anonim
              </label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Kirim Aduan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  HTML;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {

  $message = $_POST['message'];
  $senderName = $_POST['sender-name'];
  $email = $_POST['email'];

  if (isset($_POST['anonimCheckbox'])) {
    $senderName = "Anonim";
    $email = "anonim@example.com";
  }

  $query = "INSERT INTO complaints (name, email_sender, is_anonymous, complaint_message, created_at) VALUES ('$senderName', '$email', '" . (isset($_POST['anonimCheckbox']) ? 1 : 0) . "', '$message', NOW())";

  if ($conn->query($query) === TRUE) {
    header("Location: /portal-berita?success=true");  
    exit;  
  } else {
    header("Location: /portal-berita?error=true");  
    exit;
  }
} else {
  displayComplaintForm();
}

$newsPerPage = 3;
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($currentPage - 1) * $newsPerPage;

$queryTotalNews = "SELECT COUNT(*) AS total FROM news";
$resultTotalNews = $conn->query($queryTotalNews);
$rowTotalNews = $resultTotalNews->fetch_assoc();
$totalNews = $rowTotalNews['total'];
$totalPages = ceil($totalNews / $newsPerPage);

$query = "SELECT * FROM news
          LEFT JOIN categories ON news.category_id = categories.category_id
          ORDER BY news.created_at DESC
          LIMIT $offset, $newsPerPage";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Portal Berita Randubelang</title>

  <link href="assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
  <link href="assets/css/modern-news.css" rel="stylesheet">
</head>

<body>
  <?php 
  require_once('templates/navigation.php');
  require_once('templates/header.php');
  ?>

  <div class="container">
    <h1 class="my-4">Berita Terbaru</h1>

    <div class="row">
      <?php while ($news = $result->fetch_assoc()): ?>
        <div class="col-lg-4 mb-4">
          <a href="news-detail?id=<?php echo $news['news_id']; ?>" style="text-decoration: none; color: inherit;">
            <div class="card h-100">
              <img class="card-img-top" src="<?php echo $news['img_url']; ?>" alt="<?php echo $news['title']; ?>" loading="lazy">
              <div class="card-body">
                <p class="news-category"><?php echo $news['category_name']; ?></p>
                <h4 class="card-title"><?php echo $news['title']; ?></h4>
                <p class="card-text"><?php echo substr($news['content'], 0, 120); ?>...</p>
              </div>
              <div class="card-footer text-muted">
                <p class="date-news"><?php echo strftime('%A, %d %B %Y', strtotime($news['created_at'])); ?></p>
              </div>
            </div>
          </a>
        </div>
      <?php endwhile; ?>
    </div>

    <!-- Pagination -->
    <nav aria-label="Pagination">
      <ul class="pagination justify-content-center mt-4">
        <li class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
          <a class="page-link" href="?page=1" tabindex="-1" aria-disabled="true">&lt;</a>
        </li>
        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
          <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
          </li>
        <?php endfor; ?>
        <li class="page-item <?php echo ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
          <a class="page-link" href="?page=<?php echo $totalPages; ?>">&gt;</a>
        </li>
      </ul>
    </nav>

    <hr>

      <div class="aduan">
        <button class="btn btn-md btn-danger btn-block" data-toggle="modal" data-target="#aduanModal">
            <span">Pesan Aduan</span>
        </button>
      </div>
    </div>

  <?php require_once('templates/footer.php'); ?>
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const aduanButton = document.querySelector('.btn-danger');
      const anonimCheckbox = document.getElementById('anonimCheckbox');
      const senderNameInput = document.getElementById('sender-name');
      const senderEmailInput = document.getElementById('email');

      aduanButton.addEventListener('click', function(event) {
        event.preventDefault(); 
        $('#aduanModal').modal('show'); 
      });

      anonimCheckbox.addEventListener('change', function() {
        const isAnonim = this.checked;
        senderNameInput.disabled = isAnonim;
        senderEmailInput.disabled = isAnonim;
        senderNameInput.value = isAnonim ? '' : senderNameInput.value;
        senderEmailInput.value = isAnonim ? '' : senderEmailInput.value;
      });

      const urlParams = new URLSearchParams(window.location.search);
      if (urlParams.has('success')) {
        alert('Pesan aduan berhasil dikirim!');
      } else if (urlParams.has('error')) {
        alert('Terjadi kesalahan saat mengirim aduan. Silakan coba lagi nanti.');
      }
    });
  </script>
</body>
</html>
