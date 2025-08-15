<?php 
session_start();
require_once("./db_conf.php");

$db_conn = new mysqli(db_info::DB_URL, db_info::USER_ID, db_info::PASSWD, db_info::DB);

if ($db_conn->connect_errno) {
    die("연결 실패");
}
$db_conn->set_charset("utf8mb4");

$id = $_GET['id'] ?? NULL;

// DB 데이터 가져오기
$stmt = $db_conn->prepare("SELECT * FROM posts2 WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
?>

<h2><?= htmlspecialchars($post['id']) ?></h2>
<p><strong>작성자: </strong> <?= htmlspecialchars($post['name']) ?></p>
<p><strong>작성일: </strong> <?= htmlspecialchars($post['created_at']) ?></p>
<p><strong>수정일: </strong> <?= $post['updated_at'] ? htmlspecialchars($post['updated_at']) : '-' ?></p>
<p><strong>본문: </strong> <?= nl2br(htmlspecialchars($post['content'])) ?></p>


<form action="edit.php" method="get">
    <input type="hidden" name="id" value="<?= $post['id'] ?>">
    <button type="submit">수정하기</button>

<form action="delete.php" method="get">
    <input type="hidden" name="id" value="<?= $post['id'] ?>">
    <button type="submit">삭제하기</button>

<form action="main.php" method="get">
    <button type="submit">메인으로</button>
</form>