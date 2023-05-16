<?php
include 'db.php';
$conn = mysqli_connect('localhost', $db_id, $db_pw, $db_name);
$query = "SELECT * FROM tbl ORDER BY rt DESC LIMIT 1;";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
?>

<link rel="stylesheet" href="css/statusinfo.css" />

<div class="statusbody">
    <div class="between">
    <div class="status">
        <span class="tit">기숙사 상태</span>
        <div class="top">
        <div class="topitem">
            <div class="itemwrap">
            <img src="img/Vector-3.png"></img>
            <?php
            echo '<span class="text"> 온도 : ' . $row['temperature'] . " °C</span>";
        ?>
        </div>
        </div>
        <div class="topitem">
        <div class="itemwrap">
        <img src="img/Vector-4.png"></img>
        <?php
             echo '<span class="text"> 습도 : ' . $row['humidity'] . " %</span>";
        ?>
        </div>
        </div>
        </div>
        <div class="bottom">
        <div class="bottomitem">
            <div class="bottomwrap">
        <img src="img/Vector-6.png"></img>
        <span>방 온도가 높습니다!</span>
        </div>
        </div>
        <div class="bottomitem">
        <div class="bottomwrap">
        <img src="img/Vector-5.png"></img>
        <span>방 습도가 높습니다!</span>
        </div>
        </div>
        </div>
        </div>
    <div class="graph">
    <span class="tit">Graph</span>
    <div class="graphwrap"></div>
    </div>
    </div>

</div>