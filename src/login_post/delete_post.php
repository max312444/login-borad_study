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
    <title>게시물 삭제</title>
</head>
<body>
    <h2>게시물 삭제<h2>

    <?php 
    if (isset($_SESSION['error'])) {
        echo "<p style='color:red'>" . htmlspecialchars($_SESSION['error']) . "</p>";
        unset($_SESSION['error']);
    }
    ?>
    
<form action="delete_post_process.php" method="post">
    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

    <label for="password">비밀번호: </label>
    <input type="password" name="password" required><br><br>

    <button type="submit">삭제하기</button>
</form>
    <form action="main.php" method=post>
        <button type="submit">돌아가기</button>
    </form>
</body>
</html>
