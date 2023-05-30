<?php
session_start();
echo '<script>console.log("Username: '.$_SESSION["username"].'");</script>';
echo '<script>console.log("ID: '.$_SESSION["id"].'");</script>';
?>

<!DOCTYPE html>
<link rel="stylesheet" href="css/main.css">
<html lang="ko">
<head>
    <title>메인페이지</title>
</head>
<body style="margin:0px;">
<header>
<?php
    include 'header.php';
    ?>
</header>
<div>
    <?php
    include 'components/nav.php';
    ?>
</div>
<footer>
    <?php
    include 'footer.php';
    ?>
</footer>
</body>
</html>