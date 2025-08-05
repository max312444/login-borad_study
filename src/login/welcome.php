<?php
session_start();
// 로그인 되지 않은 상태로 이 페이지에 들어왔을 때 로그인으로 보내기
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<h2>환영합니다, <?= htmlspecialchars($_SESSION['name']) ?>님!</h2>
<a href="logout.php">로그아웃</a>
