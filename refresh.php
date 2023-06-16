<?php
session_start();
include 'db.php';
$conn = mysqli_connect($db_host, $db_id, $db_pw, $db_name);

$query = "SELECT temperature, humidity, motion, click, rt FROM tbl WHERE account = '".$_SESSION["id"]."' ORDER BY rt DESC LIMIT 1;";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

$response = array(
  'temperature' => $data['temperature'],
  'humidity' => $data['humidity'],
  'motion' => $data['motion'],
  'click' => $data['click'],
  'rt' => $data['rt']
);

echo json_encode($response);
?>
