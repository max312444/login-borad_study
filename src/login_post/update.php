<?php session_start();

require_once("./db_conf.php");

$db_conn = new mysqli(
    db_info::DB_URL,
    db_info::USER_ID,
    db_info::PASSWD,
    db_info::DB
);

$db_conn->set_charset("utf8mb4");

$id = $_GET['id'] ?? null;

if ($db_conn->connect_errno) {
    $_SESSION['error'] = "DB 연결 실패";
    header("Location: view.php");
    exit;
}

