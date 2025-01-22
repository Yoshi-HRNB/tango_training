<?php
require_once 'Database.php';

header('Content-Type: application/json');

try {
    // Databaseクラスのインスタンス生成
    $db = new Database();
    $pdo = $db->getConnection(); // DatabaseクラスにgetConnection()メソッドがあると仮定
    $stmt = $pdo->prepare('SELECT word, word_translation FROM words ORDER BY RAND() LIMIT 5');
    $stmt->execute();
    $words = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($words);
} catch (PDOException $e) {
    http_response_code(500); // HTTPステータスコード500を設定
    echo json_encode(['error' => $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500); // その他の例外に対しても500エラーを設定
    echo json_encode(['error' => 'An unexpected error occurred: ' . $e->getMessage()]);
}
?>
