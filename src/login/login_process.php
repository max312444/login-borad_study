<?php session_start();

// db 접속 정보 가져오기
require_once("./db_conf.php");

// db 연결 설정 구문
$db_const = new mysqli(
    db_info::DB_URL,
    db_info::USER_ID,
    db_info::PASSWD,
    db_info::DB
);

// 연결 실패 처리 구문
if ($db_conn->connect_errno) {
    $_SESSION['error'] = "DB 연결 실패";
    header("Location: login_process.php");
    exit;
}

// 사용자 입력
$username_raw = trim($_POST['username'] ?? '');
$password_raw = trim($_POST['password'] ?? '');

// 유효성 검사
if($username_raw === '' || $password_raw === '') {
    $_SESSION['error'] = "아이디와 비밀번호를 전부 입력하십시오";
    header("Location: login.php");
    exit;
}

// sql 이젝션 방지용 문자열 이스케이프 처리
$username = $db_const->real_escape_string($username_raw);

// DB 조회
$query = "SELECT * FROM users WHERE username = '$username'";
$result = $db_const->query($query);

// DB 연결 종료
$db_const->close();

// 사용자 조회 결과 확인 및 인증 처리
if ($result && $row = $result->fetch_assoc()) {
    // DB의 비밀번호와 비교
    if (password_verify($password_raw, $row['password'])) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['name'] = $row['name'];
        header("Location: welcome.php");
        exit;
    } else {
        // 일지하지 않을 때
        $_SESSION['error'] = "비밀번호가 틀렸습니다.";
        header("Location: login.php");
        exit;
    }
// 아이디가 DB에 존재하지 않을 경우
} else {
    $_SESSION['error'] = "아이디가 존재하지 않습니다.";
    header("Location: login.php");
    exit;
}