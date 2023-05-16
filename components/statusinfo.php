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
        <div class="topitem"></div>
        <div class="topitem"></div>
        </div>

        <div class="bottom">
        <div class="bottomitem"></div>
        <div class="bottomitem"></div>
        </div>
        </div>
    <div class="graph">
    <span class="tit">Graph</span>
    <div class="graphwrap"></div>
    </div>
    </div>

</div>