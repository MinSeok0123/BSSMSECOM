<?php
session_start();
include 'db.php';

$conn = mysqli_connect($db_host, $db_id, $db_pw, $db_name);

$query = "SELECT * FROM time WHERE id = '".$_SESSION["id"]."' LIMIT 1;";
$result_time = mysqli_query($conn, $query);

if (mysqli_num_rows($result_time) > 0) {
    $row_time = mysqli_fetch_assoc($result_time);
    $startTime = $row_time['start'];
    $endTime = $row_time['end'];
} else {
    $startTime = '07:30';
    $endTime = '20:30';
}

$page = isset($_POST['page']) ? $_POST['page'] : 1;
$recordsPerPage = 7;
$offset = ($page - 1) * $recordsPerPage;

$query = "SELECT * FROM tbl WHERE account = '".$_SESSION["id"]."'";

if (isset($_POST['filter']) && $_POST['filter'] === '1') {
    $query .= " AND motion = 1";
} elseif (isset($_POST['filter']) && $_POST['filter'] === '2') {
    $query .= " AND motion = 1 AND (TIME(rt) >= '$startTime' AND TIME(rt) <= '$endTime')";
} else {
    $query .= " AND motion != 0";
}

$query .= " ORDER BY id DESC LIMIT $offset, $recordsPerPage;";

$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $rtDateTime = strtotime($row['rt']);
    $rtFormatted = date("H:i", $rtDateTime);
    $rtDate = date("Y-m-d", $rtDateTime);
    $inWorkHours = $row['motion'] == 1 && $rtFormatted >= $startTime && $rtFormatted <= $endTime && $rtDate == date("Y-m-d");
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
