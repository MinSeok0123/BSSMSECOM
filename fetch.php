<?php
include 'db.php';
$conn = mysqli_connect('localhost', $db_id, $db_pw, $db_name);

$query = "SELECT * FROM tbl ORDER BY id ASC LIMIT 7;";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>".$row['id']."</td>";
    echo "<td>".$row['rt']."</td>";
    echo "<td>".($row['motion'] == 1 ? 'O' : 'X')."</td>";
    $rtTime = strtotime($row['rt']);
    $startTime = strtotime('07:30');
    $endTime = strtotime('20:30');
    $inWorkHours = $row['motion'] == 1 && $rtTime >= $startTime && $rtTime <= $endTime;
    echo "<td>".($inWorkHours ? 'O' : 'X')."</td>";
    echo "<td>";
    echo '<a style="text-decoration: none;" href="?delete_id='.$row['id'].'" onclick="return confirm(\'정말로 삭제 하시겠습니까?\');" class="delete">삭제</a>';
    echo "</td>";
    echo "</tr>";
}
?>
