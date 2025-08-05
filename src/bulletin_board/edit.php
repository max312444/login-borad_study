<?php 
session_start(); 
require_once('../bulletin_board/db_conf.php');

$db_conn = new mysqli(db_info::DB_URL, db_info::USER_ID, db_info::PASSWD, db_info::DB);
if ($db_conn->connect_errno) {
    $_SESSION['error'] = "DB 연결에 실패했습니다.";
    exit;
}
$db_conn->set_charset("utf8mb4");

$id = $_GET['id'] ?? null;
if (!$id) {
    die("잘못된 접근입니다.");
}

$stmt = $db_conn->prepare("SELECT * FROM posts WHERE id = ?");
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
    <title>수정하기</title>
</head>
<body>
    <h2>수정하기</h2>

    <?php 
    if (isset($_SESSION['error'])) {
        echo "<p style='color:red'>" . htmlspecialchars($_SESSION['error']) . "</p>";
        unset($_SESSION['error']);
    }
    ?>

    <form action="edit_process.php" method="post">
        <fieldset>
            <legend>게시물 수정</legend>

            <input type="hidden" name="id" value="<?= htmlspecialchars($post['id']) ?>">

            <label for="title">제목:</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required><br><br>

            <label for="name">이름:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($post['name']) ?>" required><br><br>

            <label for="content">내용:</label>
            <textarea id="content" name="content" rows="6" cols="50" required><?= htmlspecialchars($post['content']) ?></textarea><br><br>

            <input type="submit" value="수정완료">
        </fieldset>
    </form>
</body>
</html>
