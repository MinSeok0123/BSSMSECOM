<?php
include 'db.php';
$conn = mysqli_connect($db_host, $db_id, $db_pw, $db_name);

session_start();
$sessionId = $_SESSION["id"];
$selectQuery = "SELECT ip_address FROM users WHERE id = $sessionId";
$result = mysqli_query($conn, $selectQuery);
$row = mysqli_fetch_assoc($result);
$ipAddress = $row["ip_address"];
?>

<link rel="stylesheet" href="css/click.css" />

<div class="clickbody">
    <img class="openbtn" onclick="handleClick()" src="img/image 3.png" alt="btn">
    <img id="hand" class="hand" src="img/image 4.png" alt="hand" style="display: none;">
</div>

<script>
var canClick = true;

function handleClick() {
  if (!canClick) {
    alert("1초 안에 여러 번 요청할 수 없습니다.");
    return;
  }

  var hand = document.getElementById("hand");
  hand.style.display = (hand.style.display === "none") ? "block" : "none";

  canClick = false;
  updateClickValue();

  setTimeout(function() {
    hand.style.display = "none";
    canClick = true;
  }, 1000);

  <?php
  $updateQuery = "UPDATE tbl SET click = 1 ORDER BY id DESC LIMIT 1;";
  mysqli_query($conn, $updateQuery);
  ?>
}

function updateClickValue() {
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "http://<?php echo $ipAddress; ?>/click?param=open", true);
  xhr.send();
}
</script>
