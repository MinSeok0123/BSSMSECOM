<?php
session_start();
include 'db.php';
$conn = mysqli_connect('localhost', $db_id, $db_pw, $db_name);
$id = $_SESSION["id"];
$query = "SELECT * FROM tbl WHERE account = '$id'ORDER BY rt DESC LIMIT 1;";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

$response = array(
  'temperature' => $row['temperature'],
  'humidity' => $row['humidity']
);

header('Content-Type: application/json');
echo json_encode($response);
?>
