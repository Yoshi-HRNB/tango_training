<?php
require_once 'Database.php';
$db = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $word = $_POST['word'] ?? '';
    $word_translation = $_POST['word_translation'] ?? '';

    if ($id && $word && $word_translation) {
        $sql = "UPDATE words SET word = :word, word_translation = :word_translation WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':word', $word, PDO::PARAM_STR);
        $stmt->bindValue(':word_translation', $word_translation, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}

header('Location: index.php');
exit;
