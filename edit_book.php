<?php
require 'db.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = :id");
$stmt->execute([':id'=>$id]);
$book = $stmt->fetch();
if (!$book) { header('Location: index.php'); exit; }
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim((string)filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS));
    $author = trim((string)filter_input(INPUT_POST, 'author', FILTER_SANITIZE_SPECIAL_CHARS));
    $genre = trim((string)filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_SPECIAL_CHARS));
    $description = trim((string)filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    if ($title === '') $errors[] = 'Titel is verplicht.';
    if ($author === '') $errors[] = 'Auteur is verplicht.';
    if (!$errors) {
        $stmt = $pdo->prepare("UPDATE books SET title=:t, author=:a, genre=:g, description=:d WHERE id=:id");
        $stmt->execute([':t'=>$title,':a'=>$author,':g'=>$genre,':d'=>$description,':id'=>$id]);
        header('Location: book.php?id='.$id);
        exit;
    }
}
?>
<!doctype html>
<html lang="nl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Bewerk boek</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container">
  <h1 class="mt-4">Bewerk boek</h1>
  <?php if ($errors): ?><div class="alert alert-danger"><?=implode('<br>', array_map('htmlspecialchars', $errors))?></div><?php endif; ?>
  <form method="post">
    <div class="mb-3"><label class="form-label">Titel</label><input class="form-control" name="title" value="<?=htmlspecialchars($book['title'])?>"></div>
    <div class="mb-3"><label class="form-label">Auteur</label><input class="form-control" name="author" value="<?=htmlspecialchars($book['author'])?>"></div>
    <div class="mb-3"><label class="form-label">Genre</label><input class="form-control" name="genre" value="<?=htmlspecialchars($book['genre'])?>"></div>
    <div class="mb-3"><label class="form-label">Beschrijving</label><textarea class="form-control" name="description" rows="4"><?=htmlspecialchars($book['description'])?></textarea></div>
    <button class="btn btn-primary" type="submit">Opslaan</button>
    <a class="btn btn-secondary" href="book.php?id=<?=$id?>">Annuleren</a>
  </form>
</div>
</body>
</html>
