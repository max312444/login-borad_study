<?php 

mysqli_report(MYSQLI_REPORT_OFF);

session_start(); 

// db_conf.php 호출
require_once('./db_conf.php');

// DB 입력값 불러오기 db_conf.php 에서
$db_conn = new mysqli(
    db_info::DB_URL,
    db_info::USER_ID,
    db_info::PASSWD,
    db_info::DB
);

// DB 연결 실패시 처리
if ($db_conn->connect_errno) {
    $_SESSION['error'] = "DB 연결에 실패했습니다.";
    header("Location: register.php");
    exit;
}

// 사용자 입력값 확인
$username_raw = trim($_POST['username'] ?? '');
$password_raw = trim($_POST['password'] ?? '');
$name_raw = trim($_POST['name']);

// 입력을 전부하는지 확인
if ($username_raw === '' || $password_raw === '' || $name_raw === '') {
    $_SESSION['error'] = "모든 필드를 입력하세요.";
    header("Location: register.php");
    exit;
}

// 비밀번호는 hash해서 저장
$password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);

// 값 적용
$username = $db_conn->real_escape_string($username_raw);
$password = $db_conn->real_escape_string($password_hashed);
$name = $db_conn->real_escape_string($name_raw);

// DB에 값 전송 명령어
$sql = "
        INSERT INTO users (username, password, name)
        VALUE ('$username', '$password', '$name')
";

// 데이터 저장이 완료되면 로그인으로
if ($db_conn->query($sql)) {
    $db_conn->close();
    $_SESSION['success'] = "회원가입이 완료되었습니다.";
    header("Location: login.php");
    exit;
// 아이디 중복 혹은 예기치 못한 실패 처리
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
