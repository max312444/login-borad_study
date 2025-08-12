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
    $_SESSION['error'] = "DB연결에 실패했습니다.";
    header("Location: register.php");
    exit;
}

$username_raw = trim($_POST['username'] ?? '');
$password_raw = trim($_POST['password'] ?? '');
$name_raw = trim($_POST['name'] ?? '');

if ($username_raw === '' || $password_raw === '' || $name_raw === '') {
    $_SESSION['error'] = "모든 필드를 입력해주세요.";
    header("Location: register.php");
    exit;
}

$password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);

$username = $db_conn->real_escape_string($username_raw);
$password_raw = $db_conn->real_escape_string($password_hashed);
$name_raw = $db_conn->real_escape_string($name_raw);

$sql = "
        INSERT INTO users (username, password, name)
        VALUE ('$username', '$password', '$name')
";

if ($db_conn->query($sql)) {
    $db_conn->close();
    $_SESSION['success'] = "회원가입이 완료되었습니다.";
    header("Location: login.php");
    exit;

} else {
    if ($db_conn->errno === 1062) {
        $_SESSION['error'] = "이미 사용중인 아이디입니다.";
    } else {
        $_SESSION['error'] = "회원가입에 실패했습니다.";
        error_log("[REGISTER ERROR] " . $db_conn->error);
    }
    $db_conn->close();

    header("Location: register.php");
    exit;
}
?>