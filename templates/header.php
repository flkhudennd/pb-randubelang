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

function getFirstSentence($text) {
  $text = strip_tags($text); 
  $matches = [];
  preg_match('/^(.*?)(\.|\?|!)(\s|$)/', $text, $matches); 
  return $matches[0] ?? $text; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="assets/css/modern-news.css" rel="stylesheet">
</head>
<body>
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
              <h3 class="display-4 text-shadow"><?php echo htmlspecialchars($news['title']); ?></h3>
              <blockquote class="blockquote"><?php echo htmlspecialchars(getFirstSentence($news['content'])); ?></blockquote>
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
</body>
</html>