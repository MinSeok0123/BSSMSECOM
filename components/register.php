<?php
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
  $ipAddress = $_POST["ip_address"];

  // 아이디 중복 검사
  $sql = "SELECT * FROM users WHERE username='$username'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    echo '<script>alert("이미 사용 중인 아이디입니다.");</script>';
    exit();
  }

  // 비밀번호 검사
  if (strlen($password) < 4) {
    echo '<script>window.onload = function() { alert("비밀번호는 최소 4자 이상이어야 합니다."); document.getElementById("password").focus(); }</script>';
    exit();
  }

  // 비밀번호 해시화
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // 사용자 등록
  $sql = "INSERT INTO users (username, password, ip_address) VALUES ('$username', '$hashedPassword', '$ipAddress')";
  if ($conn->query($sql) === TRUE) {
    echo '<script>alert("회원가입이 완료되었습니다.");</script>';
    header("Location: login.php"); 
  } else {
    echo '<script>alert("Error: ' . $sql . '\n' . $conn->error . '");</script>';
  }

  $conn->close();
}
?>
<!DOCTYPE html>
<link rel="stylesheet" href="css/register.css" />
<html>
<head>
  <title>회원가입</title>
  <style>
    .error {
      color: red;
    }
  </style>
<script>
function checkDuplicate() {
  var username = document.getElementById("username").value;
  if (username !== "") {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          var response = xhr.responseText;
          if (response === "duplicate") {
            document.getElementById("duplicateError").innerText = "이미 사용 중인 아이디입니다.";
            document.getElementById("duplicateError").style.color = "red";
            document.getElementById("password").disabled = true;
            document.getElementById("ip_address").disabled = true;
          } else {
            document.getElementById("duplicateError").innerText = "사용 가능한 아이디입니다.";
            document.getElementById("duplicateError").style.color = "blue";
            document.getElementById("password").disabled = false;
            document.getElementById("ip_address").disabled = false;
          }
        } else {
          console.log("Error: " + xhr.status);
        }
      }
    };
    xhr.open("POST", "check_id.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("username=" + username);
  } else {
    document.getElementById("password").disabled = true;
    document.getElementById("ip_address").disabled = true;
  }
}

function checkPassword() {
  var password = document.getElementById("password").value;
  var ipAddressField = document.getElementById("ip_address");
  if (password.length < 4) {
    document.getElementById("passwordError").innerText = "비밀번호는 최소 4자 이상이어야 합니다.";
    document.getElementById("passwordError").style.color = "red";
    ipAddressField.disabled = true; // 아이피 주소 필드를 비활성화
  } else {
    document.getElementById("passwordError").innerText = "";
    ipAddressField.disabled = false; // 아이피 주소 필드를 활성화
  }
}
</script>
</head>
<body>
  <div class="registerbody">
    <h2>회원가입</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <label for="username">아이디:</label>
      <input type="text" id="username" name="username" required>
      <button type="button" onclick="checkDuplicate()">중복확인</button><br>
      <span id="duplicateError" class="error"></span><br>

      <label for="password">비밀번호:</label>
      <input type="password" id="password" name="password" required onkeyup="checkPassword()"><br>
      <span id="passwordError" class="error"></span><br>

      <label for="ip_address">아이피 주소:</label>
      <input type="text" id="ip_address" name="ip_address" required><br><br>

      <input type="submit" value="회원가입">
    </form>
  </div>
</body>
</html>
