<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인</title>
</head>
<body>
    <h2>로그인</h2>

    <?php if (isset($_SESSION['error'])) 
        echo "<p style='color:red'>" . htmlspecialchars($_SESSION['error']) . "</p>";
        unset($_SESSION['error']);
    ?>
    <!-- 폼으로 작성 시트 생성 -->
    <form action="login_process.php" method="post">
        <fieldset>
            <legend>로그인</legend>
            <label>아이디 : <input type="text" name="id" require></label><br>
            <label>비밀번호 : <input type="password" name="password" require></label><br>
            <button style="submit">로그인</button>
        </fieldset>
    </form>        
    <a href="register.php">회원가입</a>  
</body>
</html>