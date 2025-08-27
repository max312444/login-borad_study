<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시판 메인 화면</title>
</head>
<body>
    <h2>게시판 메인 화면</h2>
    <?php if(isset($_SESSION['username'])) { ?>
        <p><a href="./logout.php">로그아웃</a></p>
    <?php
     } else {
        header("Location: login.php");
     } ?>
    <!-- 게시물 목록 -->
         <!-- 페이지네이션 한 페이지당 5개씩 -->
          <!-- 노출 되는 부분은 제목, 작성자, 작성일, 수정일, 옆에 상세보기 버튼 -->


    <!-- 게시물 작성 버튼 -->
    <form action="make_post.php" method="get" style="display:inline;">
        <button type="submit">게시물 작성</button>
</body>
</html>