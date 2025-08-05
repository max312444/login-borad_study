<?php 
session_start(); 
require_once('../bulletin_board/db_conf.php');

$db_conn = new mysqli(db_info::DB_URL, db_info::USER_ID, db_info::PASSWD, db_info::DB);
if ($db_conn->connect_errno) {
    $_SESSION['error'] = "DB 연결에 실패했습니다.";
    exit;
}
$db_conn->set_charset("utf8mb4");

$id = $_GET['id'] ?? null;
if (!$id) {
    die("잘못된 접근입니다.");
}

$stmt = $db_conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>삭제하기</title>
</head>
<body>

    <?php 
    if (isset($_SESSION['error'])) {
        echo "<p style='color:red'>" . htmlspecialchars($_SESSION['error']) . "</p>";
        unset($_SESSION['error']);
    }
    ?>
    <a href="delete_post_process.php?id=<?= htmlspecialchars($post['id']) ?>">삭제하기</a>
</body>
</html>
