<?php 
session_start();
require_once('./db_conf.php');

// DB 연결
$db_conn = new mysqli(db_info::DB_URL, db_info::USER_ID, db_info::PASSWD, db_info::DB);
if ($db_conn->connect_errno) {
    $_SESSION['error'] = "DB 연결에 실패했습니다.";
    header("Location: register.php");
    exit;
}
$db_conn->set_charset("utf8mb4");

// id 가져오기 
$id = $_POST['id'] ?? null;
if (!$id) {
    $_SESSION['error'] = "잘못된 접근입니다.";
    header("Location: main.php");
    exit;
}

// 입력 파트
$title_raw = trim($_POST['title'] ?? '');
$name_raw = trim($_POST['name'] ?? '');
$content_raw = trim($_POST['content'] ?? '');

$title = $db_conn->real_escape_string($title_raw);
$name = $db_conn->real_escape_string($name_raw);
$content = $db_conn->real_escape_string($content_raw);

$sql = "
    UPDATE posts SET title = '$title', name = '$name', content = '$content'
    WHERE id = $id
";

if ($db_conn->query($sql)) {
    $db_conn->close();
    header("Location: main.php");
    exit;
} else {
    $_SESSION['error'] = "수정 중 오류가 발생했습니다.";
    header("Location: edit.php?id=$id");
    exit;
}
