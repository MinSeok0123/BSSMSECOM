<?php
session_start();
if (!isset($_SESSION["username"])) {
  echo '<script>alert("로그인이 필요합니다.");</script>';
  echo '<script>window.location.href = "login.php";</script>';
  exit();
}
?>

<!DOCTYPE html>
<link rel="stylesheet" href="css/main.css">
<html lang="ko">
<head>
    <title>출입자 관리</title>
</head>
<body style="margin:0px;">
<header>
<?php
    include 'header.php';
    ?>
</header>
<div>
<?php
    include 'components/table.php';
    ?>
</div>
<footer>
    <?php
    include 'footer.php';
    ?>
</footer>
</body>
</html>
