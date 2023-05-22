<?php
include 'db.php';
date_default_timezone_set('Asia/Seoul');

$temp = $_GET['temp'];
$humi = $_GET['humi'];
$motion = $_GET['motion'];
$date = date("Y-m-d H:i:s");
$click = '0';

// MySQL에 접속
$conn = mysqli_connect('localhost', $db_id, $db_pw, $db_name);

$query = "INSERT INTO tbl (rt, humidity, temperature, motion, click) VALUES ('$date', $humi, $temp, $motion, $click)";
$result = mysqli_query($conn, $query);

if ($result) {
  echo "데이터가 성공적으로 삽입되었습니다.";
} else {
  echo "데이터 삽입에 실패하였습니다.";
}

mysqli_close($conn);
?>