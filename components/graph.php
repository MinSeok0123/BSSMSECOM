<?php
session_start();
include 'db.php';
$conn = mysqli_connect('localhost', $db_id, $db_pw, $db_name);
$id = $_SESSION["id"];
$query = "SELECT * FROM tbl WHERE account = '$id' ORDER BY rt ASC LIMIT 7;";
$result = mysqli_query($conn, $query);
?>


<!DOCTYPE html>
<html>
<head>
    <title>온습도 그래프</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <canvas id="myChart"></canvas>
    <script>
    var temperatureData = [];
    var humidityData = [];
    var timeData = [];

    <?php
    while ($row = mysqli_fetch_assoc($result)) {
    $formattedTime = date("g시 i분 s초", strtotime($row['rt']));
    echo "temperatureData.push(" . $row['temperature'] . ");";
    echo "humidityData.push(" . $row['humidity'] . ");";
    echo "timeData.push('" . $formattedTime . "');";
}
    ?>

    var ctx = document.getElementById('myChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: timeData,
            datasets: [
                {
                    label: '온도 (°C)',
                    data: temperatureData,
                    borderColor: '#E74040',
                    fill: false
                },
                {
                    label: '습도 (%)',
                    data: humidityData,
                    borderColor: '#40C0E7',
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: '시간'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: '값'
                    }
                }
            }
        }
    });
    </script>
</body>
</html>
