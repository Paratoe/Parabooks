<?php
require 'db.php';
$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM books WHERE id = :id");
    $stmt->execute([':id'=>$id]);
}
header('Location: index.php');
exit;
