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

$stmt = $db_conn->prepare("SELECT * FROM posts3 WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시물 수정</title>
</head>
<body>
    <h2>게시물 수정란<h2>

    <?php 
    if (isset($_SESSION['error'])) {
        echo "<p style='color:red'>" . htmlspecialchars($_SESSION['error']) . "</p>";
        unset($_SESSION['error']);
    }
    ?>

    <form action="update_process.php" method="post">
        <fieldset>
            <legend>게시물 수정</legend>
            <input type="hidden" name="id" value="<?= htmlspecialchars($post['id'] ?? '') ?>">

            <label for="title">제목: </label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title'] ?? '') ?>"><br>

            <label for="name">이름: </label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($post['name'] ?? '') ?>"><br>

            <label for="content">내용: </label>
            <textarea id="content" name="content" rows="6" cols="50"><?= htmlspecialchars($post['content'] ?? '') ?></textarea><br>

            <label for="password">비밀번호: </label>
            <input type="password" name="password" require><br>

            <input type="submit" value="수정완료">
        </fieldset>
    </form>
</body>
</html>
