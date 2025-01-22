<?php
/**
 * Databaseクラス
 * PDOをラップし、接続や基本的なクエリ実行をまとめる
 */
class Database
{
    // PDOインスタンスを保持
    private $pdo;

    // 接続情報（必要に応じて private/protected で管理）
    private $host     = 'mysql3103.db.sakura.ne.jp';
    private $dbname   = 'portfolio-t_tango';
    private $username = 'portfolio-t_tango';
    private $password = '5693-dennou';
    private $charset  = 'utf8mb4';

    /**
     * コンストラクタで自動的に接続
     */
    public function __construct()
    {
        $this->connect();
    }

    /**
     * データベースに接続し、PDOインスタンスを生成
     */
    private function connect()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";

        try {
            // PDOインスタンスの作成
            $this->pdo = new PDO($dsn, $this->username, $this->password);

            // エラーモードを例外モードに設定
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            // 接続エラー時の処理
            echo "データベース接続エラー: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
            exit;
        }
    }

    /**
     * 外部から PDO インスタンスを取得したい場合のゲッター
     */
    public function getConnection()
    {
        return $this->pdo;
    }

    /**
     * パラメータバインドを柔軟に行いたいときに使うprepareメソッド
     */
    public function prepare($sql)
    {
        try {
            return $this->pdo->prepare($sql);
        } catch (PDOException $e) {
            echo "プリペアエラー: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
            return false;
        }
    }

    /**
     * クエリ実行用メソッド（SELECTなど結果を返す用途）
     * プレースホルダ付き SQL とパラメータを受け取り、プリペアドステートメントで実行
     */
    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            // 実行エラー時の処理
            echo "クエリエラー: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
            return false;
        }
    }

    /**
     * INSERT / UPDATE / DELETE など行数が変わるクエリを実行する場合
     * 実行後の行数を返せるようにする例
     */
    public function execute($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            // 更新された行数を返す
            return $stmt->rowCount();
        } catch (PDOException $e) {
            // 実行エラー時の処理
            echo "実行エラー: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
            return false;
        }
    }
}
