<?php
include 'db.php';
$conn = mysqli_connect('localhost', $db_id, $db_pw, $db_name);

$query = "SELECT * FROM tbl ORDER BY id ASC LIMIT 1;";
$result_recent = mysqli_query($conn, $query);
if ($row = mysqli_fetch_assoc($result_recent)) {
    $rtTimestamp = strtotime($row['rt']);
    $currentTimestamp = time();
    $timeDiff = $currentTimestamp - $rtTimestamp;

    if ($timeDiff < 60) {
        $recentUpdate = $timeDiff . '초 전';
    } elseif ($timeDiff < 3600) {
        $recentUpdate = floor($timeDiff / 60) . '분 전';
    } else {
        $recentUpdate = floor($timeDiff / 3600) . '시간 전';
    }

    echo $recentUpdate;
} else {
    echo 'N/A'; 
}
?>
