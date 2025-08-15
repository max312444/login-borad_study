<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시물 작성</title>
</head>
<body>
    <h2>게시물 작성</h2>

    <?php if (isset($_SESSION['error'])) 
        echo "<p style='color:red'>" . htmlspecialchars($_SESSION['error']) . "</p>";
        unset($_SESSION['error']);
    ?>
    <form action="make_post_process.php" method="post">
        <fieldset>
            <legend>게시물 작성 내용</legend>
            <label for="title">제목: </label>
            <input type="text" id="title" name="title" required><br><br>

            <label for="username">작성자: </label>
            <input type="text" id="username" name="username" required><br><br>

            <label for="password">비밀번호: </label>
            <input type="password" id="password" name="password" required><br><br>

            <label for="content">내용: </label>
            <input type="content" name="content" rows="6" cols="50" required><br><br>
            
            <button type="submit">작성완료</button>
        </fieldset>
    </form>
</body>
</html>