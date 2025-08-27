<?php
session_start();

require_once('./db_conf.php');

$db_const = new mysqli(
    db_info::DB_URL,
    db_info::USER_ID,
    db_info::PASSWD,
    db_info::DB
);

if($db_const->connect_errno) {
    echo "DB 연결 실패";
    header("Location: login.php");
    exit;
}

// 아이디 비번 받기
$id_raw = trim($_POST['id'] ?? '');
$password_raw = trim($_POST['password'] ?? '');

// 모두 입력했는지 확인
if ($id_raw === '' || $password_raw === '') {
    $_SESSION['error'] = "모든 정보를 입력해주세요.";
    header("Location: login.php");
    exit;
}

$id = $db_const->real_escape_string($id_raw);

// 아이디 확인
$query = "SELECT * FROM myusers WHERE username = '$id'";
$result = $db_const->query($query);

if ($result && $row = $result->fetch_assoc()) {
    // 아이디가 존재할 경우 비밀번호 확인
    if (password_verify($password_raw, $row['password'])) {
        // 비밀번호가 일치할 경우 세션에 사용자 정보 저장
        $_SESSION['username'] = $row['name'];
        $_SESSION['user_id'] = $row['id'];
        header("Location: main.php");
        exit;
    } else {
        $_SESSION['error'] = "비밀번호가 올바르지 않습니다.";
        header("Location: login.php");
        exit;
    }
} else {
    $_SESSION['error'] = "아이디가 존재하지 않습니다.";
    header("Location: login.php");
    exit;
}

$db_const->close();
