<?php
session_start();
include 'db.php';
$conn = mysqli_connect($db_host, $db_id, $db_pw, $db_name);
$id = $_SESSION["id"];
$query = "SELECT * FROM tbl WHERE account = '$id' ORDER BY rt DESC LIMIT 1;";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>온도 및 습도 도넛 차트</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
</head>
<body>
<div style="width:85%; justify-content: space-between; align-items: center; display:flex;">
<div style="position:relative;width:280px; height:170px; border-radius:10px;">
<div style="width:120px; margin-left:25px; margin-top:25px;">
    <canvas id="temperatureChart"></canvas>
</div>
<div style="position:absolute; right:0; width:130px; height:60%; top:30px; display:flex; justify-content: space-around; flex-direction: column;">
<span style="font-size:20px;">온도</span>
<span id="temperatureValue" style="font-size:25px; font-weight:bold;"></span>
</div>
    <div id="temperatureCap" style="position:absolute;top:73px;left:62px;text-align:center;font-size:20px;font-family:Arial, sans-serif;"></div>
</div>
<div style="position:relative;width:280px; height:170px; border-radius:10px;">
<div style="width:120px; margin-left:25px; margin-top:25px;">
    <canvas id="humidityChart"></canvas>
</div>
<div style="position:absolute; right:0; width:130px; height:60%; top:30px; display:flex; justify-content: space-around; flex-direction: column;">
<span style="font-size:20px;">습도</span>
<span id="humidityValue" style="font-size:25px; font-weight:bold;"></span>
</div>
    <div id="humidityCap" style="position:absolute;top:73px;left:62px;text-align:center;font-size:20px;font-family:Arial, sans-serif;"></div>
</div>
</div>

<script>
$(document).ready(function() {
    setInterval(refreshData, 1000); 

    function refreshData() {
        $.ajax({
            url: 'update_chart.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#temperatureValue').text(data.temperature + ' °C');
                $('#temperatureCap').text(data.temperature + '°C');

                $('#humidityValue').text(data.humidity + ' %');
                $('#humidityCap').text(data.humidity + '%');

                temperatureChart.data.datasets[0].data[0] = data.temperature;
                temperatureChart.data.datasets[0].data[1] = 100 - data.temperature;
                temperatureChart.update();

                humidityChart.data.datasets[0].data[0] = data.humidity;
                humidityChart.data.datasets[0].data[1] = 100 - data.humidity;
                humidityChart.update();
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    }
});

var temperature = <?php echo $row['temperature']; ?>;
var humidity = <?php echo $row['humidity']; ?>;

var temperatureCtx = document.getElementById('temperatureChart').getContext('2d');
var temperatureChart = new Chart(temperatureCtx, {
    type: 'doughnut',
    data: {
        labels: ['온도 (°C)', ''],
        datasets: [
            {
                data: [temperature, 100 - temperature],
                backgroundColor: ['#E74040', 'lightgray']
            }
        ]
    },
    options: {
        responsive: true,
        cutoutPercentage: 70,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

var humidityCtx = document.getElementById('humidityChart').getContext('2d');
var humidityChart = new Chart(humidityCtx, {
    type: 'doughnut',
    data: {
        labels: ['습도 (%)', ''],
        datasets: [
            {
                data: [humidity, 100 - humidity],
                backgroundColor: ['#40C0E7', 'lightgray']
            }
        ]
    },
    options: {
        responsive: true,
        cutoutPercentage: 70,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>
</body>
</html>
