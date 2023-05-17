<?php
include 'db.php';
$conn = mysqli_connect('localhost', $db_id, $db_pw, $db_name);

$query = "SELECT * FROM tbl ORDER BY rt DESC LIMIT 1;";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

$data = array(
  'temperature' => $row['temperature'],
  'humidity' => $row['humidity'],
  'motion' => $row['motion'],
  'click' => $row['click'],
  'rt' => $row['rt']
);

header('Content-Type: application/json');
echo json_encode($data);
?>
