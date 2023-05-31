<?php
include 'db.php';
date_default_timezone_set('Asia/Seoul');

$did = $_GET['did']; // IP 주소
$temp = $_GET['temp'];
$humi = $_GET['humi'];
$motion = $_GET['motion'];
$date = date("Y-m-d H:i:s");
$click = '0';

// MySQL에 접속
$conn = mysqli_connect('localhost', $db_id, $db_pw, $db_name);

// users 테이블에서 IP 주소와 동일한 ID 값을 가져옴
$query = "SELECT id FROM users WHERE ip_address = '$did'";
$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_assoc($result)) {
  $id = $row['id'];

  // tbl 테이블에 데이터 삽입
  $query = "INSERT INTO tbl (rt, humidity, temperature, motion, click, account) VALUES ('$date', $humi, $temp, $motion, $click, $id)";
  $result = mysqli_query($conn, $query);

  if ($result) {
    echo "데이터가 성공적으로 삽입되었습니다.";
  } else {
    echo "데이터 삽입에 실패하였습니다.";
  }
} else {
  echo "ID 값을 찾을 수 없습니다.";
}

mysqli_close($conn);
?>
