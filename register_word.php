<?php
require_once 'Database.php'; // Databaseクラスを読み込み

// Databaseクラスのインスタンス生成（自動的に接続される）
$db = new Database();

$message = ''; // 登録結果を表示するためのメッセージ用

// フォームが送信されたら処理を行う
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 入力値を受け取る
    // XSS対策として trim() + htmlspecialchars() などを行う
    $word            = trim($_POST['word'] ?? '');
    $wordTranslation = trim($_POST['word_translation'] ?? '');

    // バリデーション: 空でないか確認
    if ($word === '' || $wordTranslation === '') {
        $message = '単語と訳は必ず入力してください。';
    } else {
        try {
            // INSERT 文を準備
            $sql = "INSERT INTO words (word, word_translation)
                    VALUES (:word, :translation)";
            // プレースホルダを使って安全に実行
            $stmt = $db->query($sql, [
                ':word'       => $word,
                ':translation' => $wordTranslation
            ]);

            // 成功・失敗の判定
            if ($stmt !== false) {
                $message = '新しい単語を登録しました！';
            } else {
                $message = '登録に失敗しました。';
            }
        } catch (PDOException $e) {
            $message = 'DBエラー: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>単語登録フォーム</title>
</head>
<body>
  <h1>単語登録フォーム</h1>

  <!-- 登録結果のメッセージ表示 -->
  <?php if ($message !== ''): ?>
    <p style="color: red;"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
  <?php endif; ?>

  <!-- 単語登録用フォーム -->
  <form action="" method="post">
    <div>
      <label for="word">単語:</label><br>
      <input type="text" name="word" id="word" required>
    </div>
    <br>
    <div>
      <label for="word_translation">訳:</label><br>
      <input type="text" name="word_translation" id="word_translation" required>
    </div>
    <br>
    <input type="submit" value="登録">
  </form>

  <!-- 一覧ページへ戻るリンク例 （任意）-->
  <p><a href="index.php">→ 登録された単語一覧へ</a></p>
</body>
</html>
