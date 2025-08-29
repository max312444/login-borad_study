<?php
session_start();
require_once("./db_conf.php");

$db_conn = new mysqli(
    db_info::DB_URL,
    db_info::USER_ID,
    db_info::PASSWD,
    db_info::DB
);

if ($db_conn->connect_errno) {
    $_SESSION['error'] = "DB 연결 실패";
    header("Location: main.php");
    exit;
}
$db_conn->set_charset("utf8mb4");

$id = $_POST['id'] ?? ($_GET['id'] ?? null);
$password_raw = trim($_POST['password'] ?? '');

if (!$id) {
    $_SESSION['error'] = "잘못된 접근입니다.";
    header("Location: main.php");
    exit;
}

// DB에서 해당 게시물 비밀번호 조회
$stmt = $db_conn->prepare("SELECT password FROM posts3 WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// 비밀번호 확인
if (!$row || !password_verify($password_raw, $row['password'])) {
    $_SESSION['error'] = "비밀번호가 일치하지 않습니다.";
    header("Location: delete_post.php?id=" . $id);
    exit;
}

// 삭제 실행
$stmt = $db_conn->prepare("DELETE FROM posts3 WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();
    $db_conn->close();
    $_SESSION['message'] = "삭제 완료!";
    header("Location: main.php");
    exit;
} else {
    $_SESSION['error'] = "삭제 실패: " . $stmt->error;
    header("Location: delete_post.php?id=" . $id);
    exit;
}
