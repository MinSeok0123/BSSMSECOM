<?php
include 'db.php';
$conn = mysqli_connect('localhost', $db_id, $db_pw, $db_name);

$recentUpdate = '';

if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $deleteQuery = "DELETE FROM tbl WHERE id = $deleteId";
    mysqli_query($conn, $deleteQuery);
    echo "<script>alert('삭제가 성공적으로 처리되었습니다.');</script>";
}

$query = "SELECT * FROM tbl ORDER BY id ASC LIMIT 7;";
$result = mysqli_query($conn, $query);

$query = "SELECT * FROM tbl ORDER BY id DESC LIMIT 1;";
$result_recent = mysqli_query($conn, $query);
if ($row = mysqli_fetch_assoc($result_recent)) {
    $rtTimestamp = strtotime($row['rt']);
    $currentTimestamp = time();
    $timeDiff = $currentTimestamp - $rtTimestamp;

    if ($timeDiff < 60) {
        $recentUpdate = $timeDiff . '초 전';
    } elseif ($timeDiff < 3600) {
        $recentUpdate = floor($timeDiff / 60) . '분 전';
    } else {
        $recentUpdate = floor($timeDiff / 3600) . '시간 전';
    }
}
?>

<link rel="stylesheet" href="css/table.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="tablebox">
    <table>
        <caption>출입자 관리</caption>
        <span id="recent-update">최근 업데이트: <?php echo $recentUpdate; ?></span>
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

        // 업데이트된 최근 업데이트 시간 가져오기
        $.ajax({
            url: 'get_recent_update.php', // 최근 업데이트 시간을 가져올 PHP 파일 경로
            type: 'POST',
            success: function(data) {
                $('#recent-update').text('최근 업데이트: ' + data); // 최근 업데이트 시간 업데이트
            }
        });
    }, 1000); // 1초마다 데이터를 업데이트
});
</script>
