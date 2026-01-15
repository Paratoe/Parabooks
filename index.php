<?php
require 'db.php';
$search = trim((string)($_GET['q'] ?? ''));
if ($search !== '') {
    $stmt = $pdo->prepare("SELECT * FROM books WHERE title LIKE :s OR author LIKE :s ORDER BY created_at DESC");
    $stmt->execute([':s' => "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM books ORDER BY created_at DESC");
}
$books = $stmt->fetchAll();
?>
<!doctype html>
<html lang="nl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Boekenreviewsite</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php">Boekenreviewsite</a>
    <form class="d-flex" method="get" action="index.php">
      <input class="form-control me-2" type="search" name="q" placeholder="Zoek op titel of auteur" value="<?=htmlspecialchars($search)?>">
      <button class="btn btn-light" type="submit">Zoeken</button>
    </form>
  </div>
</nav>
<div class="container">
  <div class="d-flex justify-content-between mb-3">
    <h1>Boeken</h1>
    <a class="btn btn-success align-self-center" href="add_book.php">Nieuw boek</a>
  </div>
  <?php foreach ($books as $b): ?>
    <div class="card mb-3">
      <div class="card-body">
        <h2 class="card-title"><?=htmlspecialchars($b['title'])?></h2>
        <h3 class="card-subtitle mb-2 text-muted"><?=htmlspecialchars($b['author'])?> — <?=htmlspecialchars($b['genre'])?></h3>
        <p class="card-text"><?=nl2br(htmlspecialchars($b['description']))?></p>
        <a href="book.php?id=<?=$b['id']?>" class="btn btn-primary btn-sm">Bekijk</a>
        <a href="edit_book.php?id=<?=$b['id']?>" class="btn btn-outline-secondary btn-sm">Bewerk</a>
        <a href="delete_book.php?id=<?=$b['id']?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Verwijderen?')">Verwijder</a>
        <div class="float-end">
          <form method="post" action="vote.php" class="d-inline">
            <input type="hidden" name="id" value="<?=$b['id']?>">
            <input type="hidden" name="type" value="like">
            <button class="btn btn-light btn-sm" type="submit">👍 <?= $b['likes'] ?></button>
          </form>
          <form method="post" action="vote.php" class="d-inline">
            <input type="hidden" name="id" value="<?=$b['id']?>">
            <input type="hidden" name="type" value="dislike">
            <button class="btn btn-light btn-sm" type="submit">👎 <?= $b['dislikes'] ?></button>
          </form>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
</body>
</html>
