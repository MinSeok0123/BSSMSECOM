<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // 데이터베이스 연결
  include 'db.php';
  $servername = $db_host;
  $username = $db_id;
  $password = $db_pw;
  $dbname = $db_name;

  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 사용자 입력 데이터 가져오기
  $username = $_POST["username"];

  // 아이디 중복 검사
  $sql = "SELECT * FROM users WHERE username='$username'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    echo "duplicate";
  } else {
    echo "not_duplicate";
  }

  $conn->close();
}
?>
