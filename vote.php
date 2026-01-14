<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$action = ($_POST['type'] ?? '') === 'dislike' ? 'dislike' : 'like';

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$cookieName = 'vote_' . $id;
$currentVote = $_COOKIE[$cookieName] ?? null;

if ($currentVote === null) {

    $stmt = $pdo->prepare("UPDATE books SET {$action}s = {$action}s + 1 WHERE id = :id");
    $stmt->execute([':id' => $id]);
    setcookie($cookieName, $action, time() + 60*60*24*365, "/");

} elseif ($currentVote === $action) {
  
    $stmt = $pdo->prepare("UPDATE books SET {$action}s = {$action}s - 1 WHERE id = :id");
    $stmt->execute([':id' => $id]);
    setcookie($cookieName, '', time() - 3600, "/");

} else {

    $stmt = $pdo->prepare("
        UPDATE books 
        SET likes = likes + :l, dislikes = dislikes + :d
        WHERE id = :id
    ");

    $stmt->execute([
        ':l' => $action === 'like' ? 1 : -1,
        ':d' => $action === 'dislike' ? 1 : -1,
        ':id' => $id
    ]);

    setcookie($cookieName, $action, time() + 60*60*24*365, "/");
}

header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
exit;
