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
$id = $_GET['id'] ?? null;
if (!$id) {
    $_SESSION['error'] = "잘못된 접근입니다.";
    header("Location: main.php");
    exit;
}

$stmt = $db_conn->prepare("DELETE FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();
    $db_conn->close();
    header("Location: main.php");
    exit;
} else {
    $_SESSION['error'] = "삭제 중 오류가 발생했습니다.";
    header("Location: view.php?id=$id");
    exit;
}
