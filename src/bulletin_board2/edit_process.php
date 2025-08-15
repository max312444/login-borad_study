<?php 
session_start();
require_once("./db_conf.php");

$db_conn = new mysqli(db_info::DB_URL, db_info::USER_ID, db_info::PASSWD, db_info::DB);
if($db_conn->connect_error) {
    $_SESSION['error'] = "DB 연결 실패";
    header("Location: make_post.php");
    exit;
}

$db_conn->set_charset("utf8mb4");
