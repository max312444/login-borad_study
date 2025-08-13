<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>로그인</h2>

    <?php if (isset($_SESSION['error']))
        echo "<p style='color:red'>".htmlspecialchars($_SESSION['error'])."</p>";
        unset($_SESSION['error']);
    ?>
    <form action="login_process.php" method="post">
        <fieldset>
            <legend>로그인 정보 입력</legend>
            <label>아이디: <input type="text" name="username" required></label><br>
            <label>비밀번호: <input type="password" name="password" required></label><br>
            <button type="submit">로그인</button>
        </fieldset>
    </form>
    <a href="register.php">회원가입</a>  
</body>
</html>