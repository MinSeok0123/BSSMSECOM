<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // 데이터베이스 연결
  include 'db.php';
  $servername = 'localhost';
  $username = $db_id;
  $password = $db_pw;
  $dbname = $db_name;

  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 사용자 입력 데이터 가져오기
  $username = $_POST["username"];
  $password = $_POST["password"];

  // 사용자 인증
  $sql = "SELECT * FROM users WHERE username='$username'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row["password"])) {
      $_SESSION["username"] = $username;
      header("Location: main.php"); // 로그인 성공 시 이동할 페이지
      exit();
    } else {
      echo '<script>alert("아이디 또는 비밀번호가 올바르지 않습니다.");</script>';
    }
  } else {
    echo '<script>alert("아이디 또는 비밀번호가 올바르지 않습니다.");</script>';
  }

  $conn->close();
}
?>
<!DOCTYPE html>
<link rel="stylesheet" href="css/login.css" />
<html>
<head>
  <title>로그인</title>
  <style>
    .error {
      color: red;
    }
  </style>
</head>
<body>
  <div class="loginbody">
    <h2>로그인</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <label for="username">아이디:</label>
      <input type="text" id="username" name="username" required><br>

      <label for="password">비밀번호:</label>
      <input type="password" id="password" name="password" required><br><br>

      <input type="submit" value="로그인">
    </form>
  </div>
</body>
</html>
