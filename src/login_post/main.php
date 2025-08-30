<?php session_start(); 

require_once("./db_conf.php");

$db_conn = new mysqli (
    db_info::DB_URL,
    db_info::USER_ID,
    db_info::PASSWD,
    db_info::DB
);

if ($db_conn->connect_errno) {
    $_SESSION['error'] = "DB 연결 실패";
}
// 언어 설정 
$db_conn->set_charset("utf8mb4");

// 페이지네이션 기본 설정
$posts_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$offset = ($page - 1) * $posts_per_page;

// 전체 페이지수 계산 부분
$result_total = $db_conn->query("SELECT COUNT(*) as total FROM posts3");
$total_row = $result_total->fetch_assoc();
$total_posts = (int)$total_row['total'];
$total_pages = ceil($total_posts / $posts_per_page);

// 작성일 기준으로 페이지에 게시물 가져오기
$stmt = $db_conn->prepare("SELECT * FROM posts3 ORDER BY created_at DESC LIMIT ?, ?");
$stmt->bind_param("ii", $offset, $posts_per_page);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시판 메인 화면</title>
    <style>
        .pagination a {
            margin: 0 4px;
            text-decoration: none;
        }
        .pagination .active {
            font-weight: bold;
            color: red;
        }
    </style>
</head>
<body>
    <h2>게시판 메인 화면</h2>
    <form action="logout.php">
        <button type="submit">로그아웃</button><br>
    </form>
    <?php if (isset($_SESSION['error'])) {
        echo "<p style='color:red'>" . htmlspecialchars($_SESSION['error']);
        unset($_SESSION['error']);
    } ?>

    <form action="make_post.php">
        <button type="submit">게시물 작성</button>
    </form>
    <fieldset>
        <legend>게시물 목록</legend>
        <table border="1" cellpadding="8" cellspacing="0" style="margin-top: 10px;">
            <tr>
                <th>등록번호</th>
                <th>제목</th>
                <th>작성자</th>
                <th>작성일</th>
                <th>수정일</th>
                <th>상세보기</th>
            </tr>

            <?php
            if ($result) {
                while ($row = $result->fetch_assoc()):
            ?>
            <tr>
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
                    <td colspan="6">게시물이 없습니다!</td>
                </tr>
                <?php endif;
            }
            ?>
        </table>
    </fieldset>

    <!-- 페이지네이션 -->
    <div class="pagination">
        <?php if ($page > 1): ?> <!-- $page 가 1보다 클 때는 이전 버튼 사용 가능. 1보다 작거나 같으면 이전 버튼 없음 -->
            <a href="?page=<?= $page - 1 ?>"> 이전</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?> <!-- 1부터 페이지 수만큼 반복. 번호 클릭시 해당 페이지에 들어있는 게시물들 출력 -->
            <a href="?page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a> <!-- 현재페이지가 몇번인지 강조 -->
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?> <!-- $page가 마지막 페이지보다 작으면 다음 버튼 사용 마지막에 도착하면 다음 버튼 없음 -->
            <a href="?page=<?= $page + 1 ?>">다음</a>
        <?php endif; ?>
    </div>

</body>
</html>
