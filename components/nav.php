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
?>

<link rel="stylesheet" href="css/nav.css" />
<div class="navbody">
  <div class="space">
    <a class="sa" href="status.php">
      <div class="clickbox" id="status">
        <img class="vector" src="img/Vector.png"></img>
        <span class="name">기숙사 상태</span>
        <div class="summary">
          <span id="temperature">온도: <?php echo $temperature; ?> °C</span>
          &nbsp&nbsp
          <span id="humidity">습도: <?php echo $humidity; ?> %</span>
        </div>
      </div>
    </a>
    <a class="sa" href="manage.php">
      <div class="clickbox" id="manage">
        <img class="vector" src="img/Vector-1.png"></img>
        <span class="name">출입자 관리</span>
        <div class="summary">
          <span id="motion">
            <?php
            if ($row['motion'] == 0) {
              echo '<span style="color: #0984E3">침입안함</span>';
            } elseif ($row['motion'] == 1) {
              echo '<span style="color: #EB2F06">침입함</span>';
            }
            ?>
          </span>
        </div>
      </div>
    </a>
    <a class="sa" href="control.php">
      <div class="clickbox" id="control">
        <img class="vector" src="img/Vector-2.png"></img>
        <span class="name">원격제어</span>
        <div class="summary">
          <span id="click">
            <?php
            if ($row['click'] == 1) {
              echo "최근: " . $row['rt'];
            } else {
              echo "최근 요청이 없습니다.";
            }
            ?>
          </span>
        </div>
      </div>
    </a>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  function refreshData() {
    $.ajax({
      url: "refresh.php",
      dataType: "json",
      success: function(data) {
        $("#temperature").text("온도: " + data.temperature + " °C");
        $("#humidity").text("습도: " + data.humidity + " %");
        if (data.motion == 0) {
          $("#motion").html('<span style="color: #0984E3">침입안함</span>');
        } else if (data.motion == 1) {
          $("#motion").html('<span style="color: #EB2F06">침입함</span>');
        }
        if (data.click == 1) {
          $("#click").text("최근: " + data.rt);
        } else {
          $("#click").text("최근 요청이 없습니다.");
        }
      },
      error: function() {
        console.log("데이터 로딩에 실패했습니다.");
      }
    });
  }

  setInterval(refreshData, 1000);
</script>
