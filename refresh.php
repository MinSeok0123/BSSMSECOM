<?php
include 'db.php';
$conn = mysqli_connect('localhost', $db_id, $db_pw, $db_name);
$query = "SELECT * FROM tbl WHERE click = 1 ORDER BY rt DESC LIMIT 1;";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if (!$row) {
  $query = "SELECT rt FROM tbl WHERE click = 1 ORDER BY rt DESC LIMIT 1;";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
}

$query = "SELECT temperature, humidity FROM tbl ORDER BY rt DESC LIMIT 1;";
$result = mysqli_query($conn, $query);
$temperature = '';
$humidity = '';
if ($result && mysqli_num_rows($result) > 0) {
  $data = mysqli_fetch_assoc($result);
  $temperature = $data['temperature'];
  $humidity = $data['humidity'];
}


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
