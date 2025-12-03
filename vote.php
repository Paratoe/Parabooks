<?php
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }
$id = (int)($_POST['id'] ?? 0);
$type = ($_POST['type'] ?? '') === 'dislike' ? 'dislikes' : 'likes';
if (!$id) { header('Location: index.php'); exit; }


$cookieName = 'vote_'.$type.'_'.$id;
if (!isset($_COOKIE[$cookieName])) {
    $stmt = $pdo->prepare("UPDATE books SET $type = $type + 1 WHERE id = :id");
    $stmt->execute([':id'=>$id]);
    setcookie($cookieName, '1', time()+60*60*24*365, "/");
}
header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
exit;
