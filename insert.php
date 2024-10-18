<?php
// DB接続用の関数
// try {
//     $pdo = new PDO('mysql:host=localhost;dbname=ranking_db;charset=utf8', 'root', '');
// } catch (PDOException $e) {
//     echo 'データベース接続失敗: ' . $e->getMessage();
//     exit();
// }

try {
    $pdo = new PDO('mysql:host=mysql3101.db.sakura.ne.jp;dbname=momodai_homayasan;charset=utf8', '', '');
} catch (PDOException $e) {
    echo 'データベース接続失敗: ' . $e->getMessage();
    exit();
}

// SQLエラー用の関数
function sql_error($stmt)
{
    $error = $stmt->errorInfo();
    exit('SQLError:' . $error[2]);
}

// データベースに接続
// $pdo = db_conn();

// ユーザー情報の挿入
$sql_user = "INSERT INTO users (username, email) VALUES (:username, :email)";
$stmt_user = $pdo->prepare($sql_user);
$stmt_user->bindValue(':username', 'test_user', PDO::PARAM_STR);
$stmt_user->bindValue(':email', 'test_user@example.com', PDO::PARAM_STR);
$status_user = $stmt_user->execute();

if ($status_user == false) {
    // SQL実行時にエラーがあった場合
    sql_error($stmt_user);
} else {
    // 挿入されたユーザーIDを取得
    $user_id = $pdo->lastInsertId();

    // kind_pointsテーブルにデータを挿入（外部キー user_id を使う）
    $sql_points = "INSERT INTO kind_points (user_id, points, date) VALUES (:user_id, :points, :date)";
    $stmt_points = $pdo->prepare($sql_points);
    $stmt_points->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_points->bindValue(':points', 100, PDO::PARAM_INT);
    $stmt_points->bindValue(':date', date('Y-m-d'), PDO::PARAM_STR);
    $status_points = $stmt_points->execute();

    if ($status_points == false) {
        sql_error($stmt_points);
    } else {
        echo "ポイントデータの挿入に成功しました";
    }
}
?>

<a href="index.php" class="back-button">← 戻る</a>

