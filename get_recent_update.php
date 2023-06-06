<?php
session_start();
include 'db.php';
$conn = mysqli_connect('localhost', $db_id, $db_pw, $db_name);

date_default_timezone_set('Asia/Seoul');

$query = "SELECT * FROM tbl WHERE motion = '1' and account = '".$_SESSION["id"]."' ORDER BY id DESC LIMIT 1;";
$result_recent = mysqli_query($conn, $query);
if ($row = mysqli_fetch_assoc($result_recent)) {
    $rtTimestamp = strtotime($row['rt']);
    $currentTimestamp = strtotime(date_default_timezone_get());
    $timeDiff = $currentTimestamp - $rtTimestamp;

    if ($timeDiff < 60) {
        $recentUpdate = $timeDiff . '초 전';
    } elseif ($timeDiff < 3600) {
        $recentUpdate = floor($timeDiff / 60) . '분 전';
    } elseif ($timeDiff < 86400) {
        $recentUpdate = floor($timeDiff / 3600) . '시간 전';
    } elseif ($timeDiff < 2592000) {
        $recentUpdate = floor($timeDiff / 86400) . '일 전';
    } elseif ($timeDiff < 31536000) {
        $recentUpdate = floor($timeDiff / 2592000) . '달 전';
    } else {
        $recentUpdate = floor($timeDiff / 31536000) . '년 전';
    }

    echo $recentUpdate;
} else {
    echo 'N/A'; 
}
?>