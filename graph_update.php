<?php
session_start();
include 'db.php';
$conn = mysqli_connect($db_host, $db_id, $db_pw, $db_name);
$id = $_SESSION["id"];
$query = "SELECT * FROM tbl WHERE account = '$id' ORDER BY rt DESC LIMIT 7;";
$result = mysqli_query($conn, $query);

$temperatureData = [];
$humidityData = [];
$timeData = [];

while ($row = mysqli_fetch_assoc($result)) {
    $formattedTime = date("g시 i분 s초", strtotime($row['rt']));
    $temperatureData[] = $row['temperature'];
    $humidityData[] = $row['humidity'];
    $timeData[] = $formattedTime;
}

$data = [
    'temperatureData' => $temperatureData,
    'humidityData' => $humidityData,
    'timeData' => $timeData
];

echo json_encode($data);
?>
