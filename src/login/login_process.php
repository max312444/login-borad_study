<?php
session_start();

require_once("./db_conf.php");

$db_const = new mysqli(
    db_info::DB_URL,
    db_info::USER_ID,
    db_info::PASSWD,
    db_info::DB
);

if ($db_const->connect_error) {
    $_SESSION['error'] = "DB연결에 실패했습니다.";
    header("Location: login.php");
    exit;
}

$username_raw = trim($_POST['username']);
$password_raw = trim($_POST['password']);

if ($username_raw === '' || $password_raw === '') {
    $_SESSION['error'] = "모든 필드를 입력해주세요.";
    header("Location: login.php");
    exit;
}

$username = $db_const->real_escape_string($username_raw);

$query = " SELECT * FROM users WHERE username = '$username'";
$result = $db_const->query($query);

$db_const->close();

if ($result && $row = $result->fetch_assoc()) {
    if (password_verify($password_raw, $row['password'])) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['name'] = $row['name'];
        header("Location: main.php");
        exit;
    } else {
        $_SESSION['error'] = "비밀번호가 틀렸습니다.";
        header("Location: login.php");
        exit;
    }
} else {
    $_SESSION['error'] = "아이디가 틀렸습니다.";
    header("Location: login.php");
    exit;
}