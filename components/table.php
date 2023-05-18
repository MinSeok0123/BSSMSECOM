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

$query = "SELECT * FROM tbl ORDER BY id DESC LIMIT 7;";
$result = mysqli_query($conn, $query);

$query = "SELECT * FROM tbl ORDER BY id ASC LIMIT 1;";
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


$query = "SELECT * FROM time LIMIT 1;";
$result_time = mysqli_query($conn, $query);
if ($row_time = mysqli_fetch_assoc($result_time)) {
    $startTime = $row_time['start'];
    $endTime = $row_time['end'];
} else {
    $startTime = '07:30';
    $endTime = '20:30';
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>출입자 관리</title>
    <link rel="stylesheet" href="css/table.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 60%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="tablebox">
        <div class="tablewrap">
            <table>
                <caption>출입자 관리</caption>
                <span id="recent-update">최근 업데이트: <?php echo $recentUpdate; ?></span>
                <button id="modify-work-hours-btn">일과시간 수정</button>
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
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['rt'] . "</td>";
                        echo "<td>" . ($row['motion'] == 1 ? 'O' : 'X') . "</td>";
                        $rtTime = strtotime($row['rt']);
                        $rtFormatted = date("H:i", $rtTime);
                        $inWorkHours = $row['motion'] == 1 && $rtFormatted >= $startTime && $rtTime <= $endTime;
                        echo "<td>" . ($inWorkHours ? 'O' : 'X') . "</td>";
                        echo "<td>";
                        echo '<a style="text-decoration: none;" href="?delete_id=' . $row['id'] . '" onclick="return confirm(\'정말로 삭제 하시겠습니까?\');" class="delete">삭제</a>';
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
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>일과 시간 수정</h3>
            <form id="work-hours-form">
        <label for="start-time">등교 시간:</label>
        <input type="text" id="start-time" name="start-time" value="<?php echo $startTime; ?>">

        <label for="end-time">하교 시간:</label>
        <input type="text" id="end-time" name="end-time" value="<?php echo $endTime; ?>">

        <input type="submit" value="저장">
    </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            var modal = document.getElementById("myModal");
            var btn = document.getElementById("modify-work-hours-btn");
            var span = document.getElementsByClassName("close")[0];

            btn.addEventListener("click", function() {
                modal.style.display = "block";
            });

            span.addEventListener("click", function() {
                modal.style.display = "none";
            });

            window.addEventListener("click", function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            });
            //////////////////////////

            var form = document.getElementById("work-hours-form");

            form.addEventListener("submit", function(event) {
                event.preventDefault();

                var startTimeInput = document.getElementById("start-time");
                var endTimeInput = document.getElementById("end-time");

                var startTime = startTimeInput.value;
                var endTime = endTimeInput.value;

                $.ajax({
                    url: 'update_hours.php',
                    type: 'POST',
                    data: {
                        start: startTime,
                        end: endTime
                    },
                    success: function(response) {
                        // Handle the response if needed
                        console.log(response);
                        modal.style.display = "none";
                    }
                });
            });

            /////////////////////////////
            setInterval(function() {
                $.ajax({
                    url: 'fetch.php', 
                    type: 'POST',
                    success: function(data) {
                        $('#table-body').html(data); 
                    }
                });
                $.ajax({
                    url: 'get_recent_update.php',
                    type: 'POST',
                    success: function(data) {
                        $('#recent-update').text('최근 업데이트: ' + data);
                    }
                });
                console.log('<?php echo $startTime; ?>');
                console.log('<?php echo $endTime; ?>');
                console.log('최근 업데이트 : <?php echo $rtFormatted; ?>');
            }, 1000);
        });
    </script>
</body>

</html>
