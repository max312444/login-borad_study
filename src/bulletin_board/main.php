<?php 
session_start();
require_once('../bulletin_board/db_conf.php');

$db_conn = new mysqli(db_info::DB_URL, db_info::USER_ID, db_info::PASSWD, db_info::DB);
if ($db_conn->connect_errno) {
    $_SESSION['error'] = "DB 연결에 실패했습니다.";
}
$db_conn->set_charset("utf8mb4");

// 페이지네이션
$posts_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page - 1) * $posts_per_page;

// 전체 페이지 수
$result_total = $db_conn->query("SELECT COUNT(*) as total FROM posts");
$total_row = $result_total->fetch_assoc();
$total_posts = (int)$total_row['total'];
$total_pages = ceil($total_posts / $posts_per_page);

// 페이지에 해당하는 게시물만 가져오기
$stmt = $db_conn->prepare("SELECT * FROM posts ORDER BY created_at DESC LIMIT ?, ?");
$stmt->bind_param("ii", $offset, $posts_per_page);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>main page</title>
</head>
<body>
    <h1>게시판</h1>
    <?php if (isset($_SESSION['error']))
        echo "<p style='color:red'>".htmlspecialchars($_SESSION['error'])."</p>";
        unset($_SESSION['error']);
    ?>
        <fieldset>
            <legend>게시물 목록</legend>
                <table border="1" cellpadding="8" cellspacing="0" style="margin-top: 10px;">
            <tr>
                <th>순번</th>
                <th>등록번호</th>
                <th>제목</th>
                <th>작성자</th>
                <th>작성일</th>
                <th>수정일</th>
            </tr>

            <?php
            if ($result) {
                while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <tb></tb>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
                <td><?= $row['updated_at'] ? $row['updated_at'] : '-' ?></td>
                    <td>
                    <form action="view.php" method="get" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit">상세보기</button>
                    </form>
                </td>
            </tr>
            <?php
                endwhile;
            if ($result->num_rows === 0): ?>
                <tr>
                    <td colspan="4">게시물이 없습니다.</td>
                </tr>
            <?php endif;
            }
            ?>
        </table>
        <form action="make_post.php" method="post">
            <input type="submit" value="게시물 작성">
        </form>
    </fieldset>
</body>
</html>