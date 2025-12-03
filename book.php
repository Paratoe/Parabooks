<?php
require 'db.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = :id");
$stmt->execute([':id'=>$id]);
$book = $stmt->fetch();
if (!$book) { header('Location: index.php'); exit; }


$stmt = $pdo->prepare("SELECT * FROM reviews WHERE book_id = :id ORDER BY created_at DESC");
$stmt->execute([':id'=>$id]);
$reviews = $stmt->fetchAll();


$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_review') {
    $text = trim((string)filter_input(INPUT_POST, 'review_text', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $rating = (int)($_POST['rating'] ?? 0);
    if ($text === '') $errors[] = 'Review tekst is verplicht.';
    if ($rating < 1 || $rating > 5) $errors[] = 'Onjuiste beoordeling.';

    $cookieName = 'review_'.$id;
    if (isset($_COOKIE[$cookieName])) $errors[] = 'Je hebt al een review voor dit boek geplaatst.';
    if (!$errors) {
        $cookieKey = bin2hex(random_bytes(12));
        $stmt = $pdo->prepare("INSERT INTO reviews (book_id, review_text, rating, cookie_key) VALUES (:b,:t,:r,:k)");
        $stmt->execute([':b'=>$id,':t'=>$text,':r'=>$rating,':k'=>$cookieKey]);
        setcookie($cookieName, $cookieKey, time() + 60*60*24*365, "/");
        header('Location: book.php?id='.$id);
        exit;
    }
}
?>
<!doctype html>
<html lang="nl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?=htmlspecialchars($book['title'])?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container">
  <a href="index.php" class="btn btn-link mt-3">← Terug</a>
  <div class="card mb-3">
    <div class="card-body">
      <h2><?=htmlspecialchars($book['title'])?></h2>
      <h6 class="text-muted"><?=htmlspecialchars($book['author'])?> — <?=htmlspecialchars($book['genre'])?></h6>
      <p><?=nl2br(htmlspecialchars($book['description']))?></p>
      <div>
        <span class="me-2">👍 <?= $book['likes'] ?></span>
        <span>👎 <?= $book['dislikes'] ?></span>
      </div>
    </div>
  </div>

  <h3>Reviews</h3>
  <?php foreach ($reviews as $r): ?>
    <div class="card mb-2">
      <div class="card-body">
        <p><?=nl2br(htmlspecialchars($r['review_text']))?></p>
        <small class="text-muted">Beoordeling: <?= (int)$r['rating'] ?> / 5 — <?= $r['created_at'] ?></small>
      </div>
    </div>
  <?php endforeach; ?>

  <h4 class="mt-4">Plaats een review</h4>
  <?php if ($errors): ?><div class="alert alert-danger"><?=implode('<br>', array_map('htmlspecialchars', $errors))?></div><?php endif; ?>
  <form method="post">
    <input type="hidden" name="action" value="add_review">
    <div class="mb-3"><label class="form-label">Review</label><textarea name="review_text" class="form-control" rows="4"></textarea></div>
    <div class="mb-3"><label class="form-label">Beoordeling</label>
      <select name="rating" class="form-select" style="width:120px">
        <option value="5">5</option><option value="4">4</option><option value="3">3</option><option value="2">2</option><option value="1">1</option>
      </select>
    </div>
    <button class="btn btn-primary" type="submit">Verstuur review</button>
  </form>
</div>
</body>
</html>
