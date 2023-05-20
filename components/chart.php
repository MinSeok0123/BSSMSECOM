<?php
include 'db.php';
$conn = mysqli_connect('localhost', $db_id, $db_pw, $db_name);
$query = "SELECT * FROM tbl ORDER BY rt DESC LIMIT 1;";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>온도 및 습도 도넛 차트</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div style="width:85%; justify-content: space-between; align-items: center; display:flex;">
<div style="position:relative;width:280px; height:170px; border-radius:10px;">
<div style="width:120px; margin-left:25px; margin-top:25px;">
    <canvas id="temperatureChart"></canvas>
</div>
<div style="position:absolute; right:0; width:130px; height:60%; top:30px; display:flex; justify-content: space-around; flex-direction: column;">
<span style="font-size:20px;">온도</span>
<?php echo '<span style="font-size:25px; font-weight:bold;">' . $row['temperature'] . " °C</span>"; ?>
</div>
    <div id="cap" style="position:absolute;top:73px;left:62px;text-align:center;font-size:20px;font-family:Arial, sans-serif;">
	<?php echo $row['temperature']; ?>°C
	</div>
</div>
<div style="position:relative;width:280px; height:170px; border-radius:10px;">
<div style="width:120px; margin-left:25px; margin-top:25px;">
    <canvas id="humidityChart"></canvas>
</div>
<div style="position:absolute; right:0; width:130px; height:60%; top:30px; display:flex; justify-content: space-around; flex-direction: column;">
<span style="font-size:20px;">습도</span>
<?php echo '<span style="font-size:25px; font-weight:bold;">' . $row['humidity'] . " %</span>"; ?>
</div>
    <div id="cap" style="position:absolute;top:73px;left:62px;text-align:center;font-size:20px;font-family:Arial, sans-serif;">
	<?php echo $row['humidity']; ?>%
	</div>
</div>
</div>

    <script>
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
