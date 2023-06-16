<?php
session_start();
include 'db.php';
$conn = mysqli_connect($db_host, $db_id, $db_pw, $db_name);
$id = $_SESSION["id"];

$order = isset($_GET['order']) ? $_GET['order'] : '';
$valid_orders = ['asc', 'desc'];
if (!empty($order) && in_array($order, $valid_orders)) {
    $order_sql = "ORDER BY id $order";
} else {
    $order_sql = "ORDER BY id DESC";
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_sql = '';
if (!empty($search)) {
    $search_sql = "AND (rt LIKE '%$search%' OR humidity LIKE '%$search%' OR temperature LIKE '%$search%' OR motion LIKE '%$search%' OR click LIKE '%$search%')";
}

$query = "SELECT * FROM tbl WHERE account = '$id' $search_sql $order_sql";
$result = mysqli_query($conn, $query);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$per_page = 10;
$total_rows = count($rows);
$total_pages = ceil($total_rows / $per_page);
$start = ($page - 1) * $per_page;
$paginated_rows = array_slice($rows, $start, $per_page);
if (isset($_POST['delete_all'])) {
    $delete_query = "DELETE FROM tbl WHERE account = '$id'";
    mysqli_query($conn, $delete_query);

    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>온/습도 표</title>
    <link rel="stylesheet" href="css/alldata.css" />
    <style>
        table {
            border-collapse: collapse;
            width: 60%;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .pagination {
            text-align: center;
        }

        .pagination a {
            display: inline-block;
            padding: 8px 16px;
            text-decoration: none;
            color: #000;
            background-color: #f2f2f2;
            border-radius: 5px;
        }

        .pagination a.active {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
<div class="alldatabody">
    <form method="get">
        <div>
            <label for="search">검색어:</label>
            <input type="text" name="search" id="search" value="<?php echo $search; ?>">
            <button type="submit">검색</button>
        </div>
    </form>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>RT</th>
            <th>Humidity</th>
            <th>Temperature</th>
            <th>Motion</th>
            <th>Click</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($paginated_rows as $row) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['rt']; ?></td>
                <td><?php echo $row['humidity']; ?></td>
                <td><?php echo $row['temperature']; ?></td>
                <td><?php echo $row['motion']; ?></td>
                <td><?php echo $row['click']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <div class="pagination">
        <?php
        $num_links = 5;
        $start_page = max($page - floor($num_links / 2), 1);
        $end_page = min($start_page + $num_links - 1, $total_pages);

        if ($start_page > 1) {
            echo '<a href="?page=1&order=' . $order . '&search=' . $search . '">맨 앞</a>';
            echo '<a href="?page=' . ($start_page - 1) . '&order=' . $order . '&search=' . $search . '">&laquo;</a>';
        }

        for ($i = $start_page; $i <= $end_page; $i++) {
            echo '<a href="?page=' . $i . '&order=' . $order . '&search=' . $search . '"';
            if ($page == $i) echo ' class="active"';
            echo '>' . $i . '</a>';
        }

        if ($end_page < $total_pages) {
            echo '<a href="?page=' . ($end_page + 1) . '&order=' . $order . '&search=' . $search . '">&raquo;</a>';
            echo '<a href="?page=' . $total_pages . '&order=' . $order . '&search=' . $search . '">맨 뒤</a>';
        }
        ?>
    </div>

    <div>
        <form method="get">
            <input type="hidden" name="search" value="<?php echo $search; ?>">
            <button type="submit" name="order" value="asc">오름차순 정렬</button>
            <button type="submit" name="order" value="desc">내림차순 정렬</button>
        </form>
    </div>
    <div>
        <form method="post" onsubmit="return confirm('정말로 모든 데이터를 삭제하시겠습니까?');">
            <input type="hidden" name="search" value="<?php echo $search; ?>">
            <button type="submit" name="delete_all">전체 삭제</button>
        </form>
    </div>
</div>

<script>
    function confirmDeletion() {
        return confirm("정말로 모든 데이터를 삭제하시겠습니까?");
    }
</script>

</body>
</html>
