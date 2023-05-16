<?php
include 'db.php';
$conn = mysqli_connect('localhost', $db_id, $db_pw, $db_name);
$query = "SELECT * FROM tbl ORDER BY rt DESC LIMIT 1;";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
?>

<link rel="stylesheet" href="css/nav.css" />
<div class="navbody">
  <div class="space">
    <a class="sa" href="status.php">
      <div class="clickbox">
        <img class="vector" src="img/Vector.png"></img>
        <span class="name">기숙사 상태</span>
        <?php
        echo "<span> 습도 : " . $row['humidity'] . "</span>";
        echo "<span> 온도 : " . $row['temperature'] . "</span>";
        ?>
      </div>
    </a>
    <a class="sa" href="manage.php">
      <div class="clickbox">
        <img class="vector" src="img/Vector-1.png"></img>
        <span class="name">출입자 관리</span>
        <?php
        if ($row['motion'] == 0) {
          echo "<span>침입안함</span>";
        } elseif ($row['motion'] == 1) {
          echo "<span>침입함</span>";
        }
        ?>
      </div>
    </a>
    <a class="sa" href="control.php">
      <div class="clickbox">
        <img class="vector" src="img/Vector-2.png"></img>
        <span class="name">원격제어</span>
        <?php
        if ($row['click'] == 1) {
         echo "<span>최근 : " . $row['rt'] . "</span>";
        } else {
        echo "<span>최근 요청이 없습니다.</span>";
      }
?>
      </div>
    </a>
  </div>
</div>
