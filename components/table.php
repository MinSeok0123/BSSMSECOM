<?php
include 'db.php';
$conn = mysqli_connect('localhost', $db_id, $db_pw, $db_name);

// Check if delete button is clicked
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $deleteQuery = "DELETE FROM tbl WHERE id = $deleteId";
    mysqli_query($conn, $deleteQuery);
    echo "<script>alert('삭제가 성공적으로 처리되었습니다.');</script>";
}

$query = "SELECT * FROM tbl ORDER BY id ASC LIMIT 7;";
$result = mysqli_query($conn, $query);
?>

<link rel="stylesheet" href="css/table.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="tablebox">
    <table>
        <caption>출입자 관리</caption>
        <thead>
            <tr>
                <th>번호</th>
                <th>출입시간</th>
                <th>출입여부</th>
                <th>일과시간 출입</th>
                <th>삭제</th>
            </tr>
        </thead>
        <tbody id="table-body">
            <?php
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
        </tbody>
        <tfoot>
            <td colspan="5" class="tablefoot"></td>
        </tfoot>
    </table>
</div>

<script>
$(document).ready(function() {
    setInterval(function() {
        $.ajax({
            url: 'fetch.php', // 데이터를 가져올 PHP 파일 경로
            type: 'POST',
            success: function(data) {
                $('#table-body').html(data); // 테이블의 내용을 업데이트
            }
        });
    }, 1000); // 1초마다 데이터를 업데이트
});
</script>
