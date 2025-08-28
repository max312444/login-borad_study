<?php session_start();

require_once('./db_conf.php');

$db_conn = new mysqli (
    db_info::DB_URL,
    db_info::USER_ID,
    db_info::PASSWD,
    db_info::DB
);

if ($db_conn->connect_errno) {
    $_SESSION['error'] = "DB 연결 실패";
    header("Location: make_post.php");
    exit;
}

$db_conn->set_charset("utf8mb4");

$title_raw = trim($_POST['title'] ?? '');
$name_raw = trim($_POST['name'] ?? '');
$password_raw = trim($_POST['password'] ?? '');
$content_raw = trim($_POST['content'] ?? '');

if ($title_raw === '' || $name_raw === '' || $password_raw === '' || $content_raw === '') {
    $_SESSION['error'] = "모든 필드를 입력하세요.";
    header("Location: make_post.php");
    exit;
}

$password_hased = password_hash($password_raw, PASSWORD_DEFAULT);

$title = $db_conn->real_escape_string($title_raw);
$name = $db_conn->real_escape_string($name_raw);
$password = $db_conn->real_escape_string($password_hased);
$content = $db_conn->real_escape_string($content_raw);

$sql = "
        INSERT INTO posts3 (title, name, password, content)
        VALUES ('$title', '$name', '$password', '$content')
    ";

if ($db_conn->query($sql)) {
    $db_conn->close();
    header("Location: main.php");
    exit;
}