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
// 감지 언어 설정
$db_conn->set_charset("utf8mb4");

// 입력 파트
$title_raw = trim($_POST['title'] ?? '');
$name_raw = trim($_POST['name'] ?? '');
$password_raw = trim($_POST['password'] ?? '');
$content_row = trim($_POST['content'] ?? '');

// 값을 전부 입력했는지 확인
if ($title_raw === '' || $name_raw === '' || $password_raw === '' || $content_row === '') {
    $_SESSION['error'] = "모든 필드를 입력하세요.";
    header("Location: make_post.php");
    exit;
}

// 해싱
$password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);

// 모든 값이 다 입력되면. 값 적용
$title = $db_conn->real_escape_string($title_raw);
$name = $db_conn->real_escape_string($name_raw);
$password = $db_conn->real_escape_string($password_hashed);
$content = $db_conn->real_escape_string($content_row);

// DB에 저장
$sql = "
    INSERT INTO posts (title, name, password, content)
    VALUES ('$title', '$name', '$password', '$content')
";

// 저장 되면 메인으로 이동
if ($db_conn->query($sql)) {
    $db_conn->close();
    header("Location: main.php");
    exit;
}
