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
    $_SESSION['error'] = "DB 연결에 실패했습니다.";
    header("Location: register.php");
    exit;
}
$db_conn->set_charset("utf8mb4");

// 입력받은 값이 공백인지 아닌지 확인 id 값은 수정하기 누를 때 가져옴
$id = $_POST['id'] ?? null;
$title_raw = trim($_POST['title'] ?? '');
$name_raw = trim($_POST['name'] ?? '');
$content_raw = trim($_POST['content'] ?? '');
$password_raw = trim($_POST['password'] ?? '');

if (!$id) {
    $_SESSION['error'] = "잘못된 접근입니다.";
    header("Location: update.php");
    exit;
}

// DB에서 비밀번호 확인
$sql = "SELECT password FROM posts3 WHERE id = ?";
$stmt = $db_conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row && password_verify($password_raw, $row['password'])) {
    $_SESSION['id'] = $id;
} else {
    $_SESSION['error'] = "비밀번호가 일치하지 않습니다.";
    header("Location: update.php?id=" . $id);
    exit;
}

// 값 대입
$title = $db_conn->real_escape_string($title_raw);
$name = $db_conn->real_escape_string($name_raw);
$content = $db_conn->real_escape_string($content_raw);

// UPDATE 실행
$sql = "UPDATE posts3 SET title=?, name=?, content=? WHERE id=?";
$stmt = $db_conn->prepare($sql);
$stmt->bind_param("ssss", $title, $name, $content, $id);

if ($stmt->execute()) {
    $stmt->close();
    $db_conn->close();
    header("Location: main.php");
    exit;
} else {
    $_SESSION['error'] = "수정 실패: " . $stmt->error;
    header("Location: update.php?id=" . $id);
    exit;
}
?>
