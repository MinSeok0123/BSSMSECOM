<?php
session_start();
include 'db.php';
$conn = mysqli_connect($db_host, $db_id, $db_pw, $db_name);
$id = $_SESSION["id"];
$query = "SELECT * FROM tbl WHERE account = '$id'ORDER BY rt DESC LIMIT 1;";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
?>

<link rel="stylesheet" href="css/statusinfo.css" />

<div class="statusbody">
    <div class="between">
        <div class="status">
            <span class="tit">기숙사 상태</span>
            <div class="top">
                <div class="topitem">
                    <div class="itemwrap">
                        <img src="img/Vector-3.png" />
                        <span class="text"> 온도 : <span id="temperature"></span> °C</span>
                    </div>
                </div>
                <div class="topitem">
                    <div class="itemwrap">
                        <img src="img/Vector-4.png" />
                        <span class="text"> 습도 : <span id="humidity"></span> %</span>
                    </div>
                </div>
            </div>
            <div class="bottom">
                <div class="bottomitem">
                    <div class="bottomwrap">
                        <img src="img/Vector-6.png" />
                        <span>방 온도가 높습니다!</span>
                    </div>
                </div>
                <div class="bottomitem">
                    <div class="bottomwrap">
                        <img src="img/Vector-5.png" />
                        <span>방 습도가 높습니다!</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="graph">
            <span class="tit">Graph</span>
            <a href="alldata.php">
            <div class="alldata">
                <img class="chart" src="img/chart.png" alt="chart">
            </div>
            </a>
            <div class="graphwrap">
                <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; width: 100%; height: 100%;">
                    <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; width: 100%; height: 60%">
                        <?php include 'components/graph.php'; ?>
                    </div>
                    <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; width: 100%; height: 40%">
                        <?php include 'components/chart.php'; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
  var initialTemperature = parseInt("<?php echo $row['temperature']; ?>");
  var initialHumidity = parseInt("<?php echo $row['humidity']; ?>");

  function updateValues() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'update_status.php', true);

    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        var updatedTemperature = parseInt(response.temperature);
        var updatedHumidity = parseInt(response.humidity);

        temperatureElement.innerHTML = updatedTemperature;

        humidityElement.innerHTML = updatedHumidity;
      }
    };

    xhr.send();
  }
  var temperatureElement = document.getElementById("temperature");
  temperatureElement.innerHTML = initialTemperature;

  var humidityElement = document.getElementById("humidity");
  humidityElement.innerHTML = initialHumidity;

  updateValues();

  setInterval(updateValues, 1000);
</script>
