<?php
session_start();
include 'db.php';
$conn = mysqli_connect('localhost', $db_id, $db_pw, $db_name);

if (isset($_POST['start']) && isset($_POST['end'])) {
    $startTime = $_POST['start'];
    $endTime = $_POST['end'];

    $updateQuery = "UPDATE time SET start = '$startTime', end = '$endTime' WHERE id = '".$_SESSION["id"]."' LIMIT 1";
    mysqli_query($conn, $updateQuery);

    echo "일과 시간이 성공적으로 업데이트되었습니다.";
}
?>
