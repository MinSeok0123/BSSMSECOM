<?php
session_start();

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
  $password = $_POST["password"];

  // 사용자 인증
  $sql = "SELECT * FROM users WHERE username='$username'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row["password"])) {
      $_SESSION["username"] = $username;
      $_SESSION["id"] = $row["id"];

      // Check if the id already exists in the "time" table
      $checkSql = "SELECT * FROM time WHERE id='" . $_SESSION["id"] . "'";
      $checkResult = $conn->query($checkSql);
      if ($checkResult->num_rows == 0) {
        // Insert data into the "time" table
        $insertSql = "INSERT INTO time (id, start, end) VALUES ('" . $_SESSION["id"] . "', '07:30', '20:40')";
        if ($conn->query($insertSql) === TRUE) {
          header("Location: main.php"); // 로그인 성공 시 이동할 페이지
          exit();
        } else {
          echo "Error: " . $insertSql . "<br>" . $conn->error;
        }
      } else {
        header("Location: main.php"); // 로그인 성공 시 이동할 페이지
        exit();
      }
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
    <div class="loginwrap">
      <img class="loginlogo" src="img/secom.png" alt="logo">
      <!-- <span class="loginlogo">BSSM SECOM</span> -->
      <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <div class="idwrap">
      <label class="idlabel" for="username">아이디</label>
      <input class="id" type="text" id="username" name="username" required>
      </div>

      <div class="pwwrap">
      <label class="pwlabel" for="password">비밀번호</label>
      <input class="pw" type="password" id="password" name="password" required>
      </div>

      <div class="loginbtnwrap">
      <input class="loginbtn" type="submit" value="로그인">
      <div class="fun">
      <a class="goregist" href="register.php">회원가입</a>
      <span class="sep">|</span>
      <a class="gofindpw" href="findpw.php">비밀번호 찾기</a>
      </div>
      </div>
    </form>
  </div>
  </div>
</body>
</html>
