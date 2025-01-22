<?php
require_once 'Database.php';
$db = new Database();

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "SELECT id, word, word_translation, created_at FROM words";
if ($search !== '') {
    $sql .= " WHERE word LIKE :search OR word_translation LIKE :search";
}

// prepare → bindValue → execute
$stmt = $db->prepare($sql);

if ($search !== '') {
    $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
}

$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>単語一覧</title>
</head>
<body>
  <h1>単語一覧</h1>
  <form method="get" action="">
    <input type="text" name="search" placeholder="単語や訳を検索" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">
    <button type="submit">検索</button>
  </form>
  <p><a href="register_word.php">→ 新しい単語を登録する</a></p>
  <a href="Test.html">単語テスト</a><br />  </body>
  <a href="test_reveal.html">単語テスト2</a><br />  </body>
  
  <table border="1">
    <tr>
      <th>ID</th>
      <th>単語</th>
      <th>訳</th>
      <th>作成日時</th>
      <th>操作</th>
    </tr>
    <?php if (!empty($results)): ?>
      <?php foreach ($results as $row): ?>
        <tr>
          <form method="post" action="update_word.php">
            <td><?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td>
              <input type="text" name="word" value="<?php echo htmlspecialchars($row['word'], ENT_QUOTES, 'UTF-8'); ?>">
            </td>
            <td>
              <input type="text" name="word_translation" value="<?php echo htmlspecialchars($row['word_translation'], ENT_QUOTES, 'UTF-8'); ?>">
            </td>
            <td><?php echo htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td>
              <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?>">
              <button type="submit">更新</button>
            </td>
          </form>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="5">登録された単語はありません。</td></tr>
    <?php endif; ?>
  </table>
</body>
</html>
