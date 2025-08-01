<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>게시물 작성</title>
</head>
<body>

<h2>게시물 작성</h2>

<?php if (isset($_SESSION['error']))
    echo "<p style='color:red'>".htmlspecialchars($_SESSION['error'])."</p>";
    unset($_SESSION['error']);
?>

<form action="make_post_process.php" method="post">
    <fieldset>
        <legend>게시물 작성</legend>

        <label for="title">제목:</label>
        <input type="text" id="title" name="title" required><br><br>

        <label for="name">이름:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="password">비밀번호:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="content">내용:</label>
        <textarea id="content" name="content" rows="6" cols="50" required></textarea><br><br>

        <input type="submit" value="작성완료">
    </fieldset>
</form>

</body>
</html>
