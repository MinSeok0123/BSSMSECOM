<?php
include 'db.php';
$conn = mysqli_connect('localhost', $db_id, $db_pw, $db_name);

$page = isset($_POST['page']) ? $_POST['page'] : 1;
$limit = 7;
$offset = ($page - 1) * $limit;

$query = "SELECT * FROM tbl ORDER BY id DESC LIMIT $offset, $limit;";
$result = mysqli_query($conn, $query);

$query = "SELECT * FROM time LIMIT 1;";
$result_time = mysqli_query($conn, $query);
if ($row_time = mysqli_fetch_assoc($result_time)) {
    $startTime = $row_time['start'];
    $endTime = $row_time['end'];
} else {
    $startTime = '07:30';
    $endTime = '20:30';
}

while ($row = mysqli_fetch_assoc($result)) {
    $rtTime = strtotime($row['rt']);
    $rtFormatted = date("H:i", $rtTime);
    $inWorkHours = $row['motion'] == 1 && $rtFormatted >= $startTime && $rtTime <= $endTime;
    echo '<div class="tblcenter">';
    echo '<div class="tbl">';
    echo '<div class="RGB-S" style="' . (($inWorkHours && $row['motion'] == 1) ? 'background-color: #F9DEDF;' : (($row['motion'] == 1) ? 'background-color: #E2F0DB;' : 'background-color: #DEE9F9;')) . '"></div>';
    echo '<span class="id">' . $row['id'] . "</span>";
    echo '<span class="rt">' . $row['rt'] . "</span>";
    echo '<span class="motion">' . ($row['motion'] == 1 ? 'O' : 'X') . "</span>";
    echo '<span class="check">' . ($inWorkHours ? 'O' : 'X') . "</span>";
    echo '<div class="del">';
    echo '<a href="?delete_id=' . $row['id'] . '" onclick="return confirm(\'정말로 삭제 하시겠습니까?\');" class="delete">삭제</a>';
    echo "</div>";
    echo "</div>";
    echo "</div>";
}
?>