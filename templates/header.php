<?php
require_once('config.php');

$queryHeaderNews = "SELECT title, content, img_url FROM news ORDER BY created_at DESC LIMIT 3";
$resultHeaderNews = $conn->query($queryHeaderNews);

$headerNews = [];
if ($resultHeaderNews->num_rows > 0) {
    while ($row = $resultHeaderNews->fetch_assoc()) {
        $headerNews[] = $row;
    }
}
?>

<header>
  <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
      <?php foreach ($headerNews as $index => $news): ?>
        <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $index; ?>" class="<?php echo $index === 0 ? 'active' : ''; ?>"></li>
      <?php endforeach; ?>
    </ol>
    <div class="carousel-inner" role="listbox">
      <?php foreach ($headerNews as $index => $news): ?>
        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>" style="background-image: url('<?php echo $news['img_url']; ?>')">
          <div class="carousel-caption d-none d-md-block">
            <h3><?php echo htmlspecialchars($news['title']); ?></h3>
            <p><?php echo substr($news['content'], 0, 120); ?>...</p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
</header>
