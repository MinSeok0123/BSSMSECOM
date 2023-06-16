<?php
include 'db.php';
$conn = mysqli_connect($db_host, $db_id, $db_pw, $db_name);

session_start();
$sessionId = $_SESSION["id"];
$selectQuery = "SELECT ip_address FROM users WHERE id = $sessionId";
$result = mysqli_query($conn, $selectQuery);
$row = mysqli_fetch_assoc($result);
$ipAddress = $row["ip_address"];

$query = "SELECT * FROM time WHERE id = '".$_SESSION["id"]."' LIMIT 1;";
$result_time = mysqli_query($conn, $query);
if ($row_time = mysqli_fetch_assoc($result_time)) {
    $startTime = $row_time['start'];
    $endTime = $row_time['end'];
} else {
    $startTime = '07:30';
    $endTime = '20:30';
}

$recentUpdate = '';

if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $deleteQuery = "DELETE FROM tbl WHERE id = $deleteId";
    mysqli_query($conn, $deleteQuery);
    echo "<script>alert('삭제가 성공적으로 처리되었습니다.');</script>";
}

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$recordsPerPage = 7;
$offset = ($page - 1) * $recordsPerPage;

$query = "SELECT * FROM tbl WHERE account = '".$_SESSION["id"]."'";

if (isset($_GET['filter']) && $_GET['filter'] === '1') {
    $query .= " AND motion = 1";
} elseif (isset($_GET['filter']) && $_GET['filter'] === '2') {
    $query .= " AND motion = 1 AND (TIME(rt) >= '$startTime' AND TIME(rt) <= '$endTime')";
} else {
    $query .= " AND motion != 0";
}

$query .= " ORDER BY id DESC LIMIT $offset, $recordsPerPage;";

$result = mysqli_query($conn, $query);

$query = "SELECT COUNT(*) as total FROM tbl WHERE account = '".$_SESSION["id"]."'";

if (isset($_GET['filter']) && $_GET['filter'] === '1') {
    $query .= " AND motion = 1";
} elseif (isset($_GET['filter']) && $_GET['filter'] === '2') {
    $query .= " AND motion = 1 AND (TIME(rt) >= '$startTime' AND TIME(rt) <= '$endTime')";
} else {
    $query .= " AND motion != 0";
}

$totalRowsResult = mysqli_query($conn, $query);
$totalRows = mysqli_fetch_assoc($totalRowsResult)['total'];
$totalPages = ceil($totalRows / $recordsPerPage);

date_default_timezone_set('Asia/Seoul');

$query = "SELECT * FROM tbl WHERE motion = '1' and account = '".$_SESSION["id"]."' ORDER BY id DESC LIMIT 1;";
$result_recent = mysqli_query($conn, $query);
if ($row = mysqli_fetch_assoc($result_recent)) {
    $rtTimestamp = strtotime($row['rt']);
    $currentTimestamp = strtotime(date_default_timezone_get());
    $timeDiff = $currentTimestamp - $rtTimestamp;

    if ($timeDiff < 60) {
        $recentUpdate = $timeDiff . '초 전';
    } elseif ($timeDiff < 3600) {
        $recentUpdate = floor($timeDiff / 60) . '분 전';
    } elseif ($timeDiff < 86400) {
        $recentUpdate = floor($timeDiff / 3600) . '시간 전';
    } elseif ($timeDiff < 2592000) {
        $recentUpdate = floor($timeDiff / 86400) . '일 전';
    } elseif ($timeDiff < 31536000) {
        $recentUpdate = floor($timeDiff / 2592000) . '달 전';
    } else {
        $recentUpdate = floor($timeDiff / 31536000) . '년 전';
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>출입자 관리</title>
    <link rel="stylesheet" href="css/table.css">
    <style>
        <?php
        include 'tablestyle.php';
        ?>
    </style>
</head>

<body>
    <div class="tablebox">
        <div class="tablewrap">
            <table>
                <div class="tablenav">
                <div class="tablenavcenter">
                <div class="entermanage">
                <span class="출입자관리">출입자 관리 </span>
                <span class="출입자관리수"><?php echo $totalRows; ?></span>
                </div>
                <div class="fillternavwrap">
                    <!-- <div class="출입여부">
                    <a class="출입여부t" href="?page=<?php echo $page; ?>&filter=1">출입 여부</a>
                    </div> -->
                    <div class="일과출입">
                    <a class="일과출입t" href="?page=<?php echo $page; ?>&filter=2">일과 출입 여부</a>
                    </div>
                    <div class="전체보기">
                    <a class="전체보기t" href="?page=<?php echo $page; ?>">전체보기</a>
                    </div>
                <div class="school" id="modify-work-hours-btn">
                    <span class="schoolicon">일과시간 수정</span>
                </div>
                </div>
                </div>
                </div>
                <thead>
                    <div class="thead">
                        <span class="id">번호</span>
                        <span class="rt">출입시간</span>
                        <span class="motion">출입여부</span>
                        <span class="check">일과시간 출입</span>
                        <span class="del">삭제</span>
                    </div>
                </thead>
                <div id="table-body" class="table-body">
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        $rtDateTime = strtotime($row['rt']);
                        $rtFormatted = date("H:i", $rtDateTime);
                        $rtDate = date("Y-m-d", $rtDateTime);
                        $inWorkHours = $row['motion'] == 1 && $rtFormatted >= $startTime && $rtFormatted <= $endTime && $rtDate == date("Y-m-d");
                        echo '<div class="tblcenter">';
                        echo '<div class="tbl">';
                        echo '<div class="RGB-S" style="' . (($inWorkHours && $row['motion'] == 1) ? 'background-color: #F9DEDF;' : (($row['motion'] == 1) ? 'background-color: #E2F0DB;' : 'background-color: #DEE9F9;')) . '"></div>';
                        echo '<span class="id">' . $row['id'] . "</span>";
                        echo '<span class="rt">' . $row['rt'] . "</span>";
                        echo '<span class="motion">' . ($row['motion'] == 1 ? 'O' : 'X') . "</span>";
                        echo '<span class="check">' . ($inWorkHours ? 'O' : 'X') . "</span>";
                        echo '<div class="del">';
                        echo '<a href="?delete_id=' . $row['id'] . '" onclick="return confirm(\'정말로 삭제 하시겠습니까?\');" class="delete">삭제</a>';
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                }
                    ?>
                </div>
            </table>
            <tfoot>
                <td colspan="5" class="tablefoot">
                    <div style="position:relative; display: flex; width: 100%; height: 70px; justify-content: center; align-items: center;">
                        <div style="width:40px;height:40px; display:flex; justify-content: center; align-items: center; text-align:center;">
                            <?php if ($page > 1) : ?>
                                <a style="width:40px;height:40px; display:flex; align-items: center; justify-content: center;" href="?page=<?php echo $page - 1; ?>">
                                    <img style="width:10px;height:15px; filter: grayscale(100%);" src="img/abblack.png"></img>
                                </a>
                            <?php else: ?>
                                <div style="width:40px;height:40px; display:flex; align-items: center; justify-content: center;">
                                    <img style="width:10px;height:15px; filter: grayscale(100%);" src="img/abgray.png"></img>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php
                        $startPage = max(1, $page - 2);
                        $endPage = min($startPage + 4, $totalPages);
                        for ($i = $startPage; $i <= $endPage; $i++) : ?>
                            <div style="width:40px;height:40px; margin:5px; cursor:pointer; <?php if ($i == $page) echo 'background-color:black;'; else echo 'background-color:none;';?> border-radius:10px; display:flex; justify-content: center; align-items: center; text-align:center;">
                                <a style="text-decoration: none; width:40px;height:40px; display:flex; justify-content: center; text-align:center; align-items:center; font-weight:bold; <?php if ($i == $page) echo 'color:white;'; else echo 'color:black;';?>" href="?page=<?php echo $i . (isset($_GET['filter']) ? '&filter=' . $_GET['filter'] : ''); ?>"><?php echo $i; ?></a>
                            </div>
                        <?php endfor; ?>
                        <?php if ($endPage < $totalPages) : ?>
                            <div style="width:40px;height:40px; display:flex; justify-content: center; align-items: center; text-align:center;">
                                <span style="margin-right: 5px;">...</span>
                                <a style="text-decoration: none; width:40px;height:40px; display:flex; justify-content: center; text-align:center; align-items:center; font-weight:bold; color:black;" href="?page=<?php echo $totalPages . (isset($_GET['filter']) ? '&filter=' . $_GET['filter'] : ''); ?>"><?php echo $totalPages; ?></a>
                            </div>
                        <?php endif; ?>
                        <div style="width:40px;height:40px; display:flex; justify-content: center; align-items: center; text-align:center;">
                            <?php if ($page < $totalPages) : ?>
                                <a style="width:40px;height:40px; display:flex; align-items: center; justify-content: center;" href="?page=<?php echo $page + 1; ?>">
                                    <img style="width:10px;height:15px; filter: grayscale(100%);" src="img/afblack.png"></img>
                                </a>
                            <?php else: ?>
                                <div style="width:40px;height:40px; display:flex; align-items: center; justify-content: center;">
                                    <img style="width:10px;height:15px; filter: grayscale(100%);" src="img/afgray.png"></img>
                                </div>
                            <?php endif; ?>
                        </div>
                        <span class="recent" id="recent-update">최근 업데이트: <?php echo $recentUpdate; ?></span>
                    </div>
                </td>
            </tfoot>
        </div>
    </div>
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="time">
                <span class="timetext">🏫 일과시간</span>
            </div>
            <div class="form">
                <div class="formwrap">
                    <form id="work-hours-form">
                        <div class="inputwrap">
                            <label class="label">등교시간</label>
                            <input class="input" placeholder="등교시간" type="time" id="start-time" name="start-time" value="<?php echo $startTime; ?>">
                        </div>
                        <div class="inputwrap">
                            <label class="label">하교시간</label>
                            <input class="input" placeholder="하교시간" type="time" id="end-time" name="end-time" value="<?php echo $endTime; ?>">
                        </div>
                </div>
            </div>
            <div class="submitwrap">
                <input onclick="updateTime()" class="submit" type="submit" value="저장">
            </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

        function updateTime() {
        var startTime = document.getElementById("start-time").value;
        var endTime = document.getElementById("end-time").value;

        var xhr = new XMLHttpRequest();
        var url = "http://<?php echo $ipAddress; ?>";
        var params = "param=" + startTime;

        xhr.open("GET", url + "/start?" + params, true);
        xhr.send();

        xhr = new XMLHttpRequest(); // 새로운 XMLHttpRequest 객체 생성
        params = "param=" + endTime;
        xhr.open("GET", url + "/end?" + params, true);
        xhr.send();
        }



        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const filterParam = urlParams.get('filter');

            if (filterParam === '2') {
            document.querySelector('.일과출입').style.backgroundColor = '#F9DEDF';
            } else {
            document.querySelector('.전체보기').style.backgroundColor = '#DEE9F9';
            }

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

                if (!/^([01]\d|2[0-3]):([0-5]\d)$/.test(startTime)) {
                    alert("등교시간을 올바른 형식으로 입력하세요 (예: 09:00).");
                    startTimeInput.focus();
                    return;
                }

                if (!/^([01]\d|2[0-3]):([0-5]\d)$/.test(endTime)) {
                    alert("하교시간을 올바른 형식으로 입력하세요 (예: 18:30).");
                    endTimeInput.focus();
                    return;
                }

                if (startTime.trim() === "") {
                    alert("등교시간이 비어있습니다.");
                    startTimeInput.focus();
                    return;
                }

                if (endTime.trim() === "") {
                    alert("하교시간이 비어있습니다.");
                    endTimeInput.focus();
                    return;
                }


                $.ajax({
                    url: 'update_hours.php',
                    type: 'POST',
                    data: {
                        start: startTime,
                        end: endTime
                    },
                    success: function(response) {
                        console.log(response);
                        alert(response);
                        modal.style.display = "none";
                    }
                });
            });

            /////////////////////////////
            setInterval(function() {
                $.ajax({
                    url: 'fetch.php',
                    type: 'POST',
                    data: {
                        page: '<?php echo $page; ?>',
                        filter: '<?php echo isset($_GET["filter"]) ? $_GET["filter"] : ""; ?>'
                    },
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
