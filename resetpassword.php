<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
  $verificationCode = $_POST["verificationCode"];
  $newPassword = $_POST["newPassword"];

  // 인증 코드 검증
  $sql = "SELECT * FROM users WHERE username='$username' AND verification_code='$verificationCode'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    // 비밀번호 재설정
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT); // 비밀번호 해시 생성
    $updateSql = "UPDATE users SET password='$hashedPassword', verification_code='' WHERE username='$username'";
    if ($conn->query($updateSql) === TRUE) {
      echo '<script>alert("비밀번호가 성공적으로 재설정되었습니다. 로그인해주세요.");</script>';
      echo '<script>window.location.href = "login.php";</script>';
      exit();
    } else {
      echo "Error: " . $updateSql . "<br>" . $conn->error;
    }
  } else {
    echo '<script>alert("인증 코드가 올바르지 않습니다.");</script>';
  }

  $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>비밀번호 재설정</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <style>
    .error {
      color: red;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 mt-5">
        <div class="card">
          <div class="card-header text-center">
            <img src="img/secom.png" alt="logo" class="img-fluid mx-auto d-block" style="max-height: 100px;">
          </div>
          <div class="card-body">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
              <div class="form-group">
                <label for="username">아이디</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $_GET['username']; ?>" readonly>
              </div>
              <div class="form-group">
                <label for="verificationCode">인증 코드</label>
                <input type="text" class="form-control" id="verificationCode" name="verificationCode" required>
              </div>
              <div class="form-group">
                <label for="newPassword">새로운 비밀번호</label>
                <input type="password" class="form-control" id="newPassword" name="newPassword" required>
              </div>
              <div class="row justify-content-center">
                <div class="col-sm-6">
                  <input type="submit" class="btn btn-primary btn-block" value="비밀번호 재설정">
                </div>
                <div class="col-sm-6">
                  <a href="login.php" class="btn btn-secondary btn-block">뒤로 가기</a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>
