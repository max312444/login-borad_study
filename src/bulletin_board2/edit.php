<?php 
session_start();
require_once("./db_conf.php");

if ($db_conn->connect_error) {
    $_SESSION['error'] = "DB 연결에 실패했습니다.";
    header("Location: mai")
}
