<?php
session_start();
include 'db.php';
$conn = mysqli_connect('localhost', $db_id, $db_pw, $db_name);
$id = $_SESSION["id"];
$query = "SELECT * FROM tbl WHERE account = '$id' ORDER BY rt DESC LIMIT 7;";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>온습도 그래프</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <canvas id="myChart"></canvas>
    <script>
    function refreshChart() {
        var temperatureData = [];
        var humidityData = [];
        var timeData = [];

        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            $formattedTime = date("g시 i분 s초", strtotime($row['rt']));
            echo "temperatureData.unshift(" . $row['temperature'] . ");";
            echo "humidityData.unshift(" . $row['humidity'] . ");";
            echo "timeData.unshift('" . $formattedTime . "');";
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
        
        setInterval(function() {
            $.ajax({
                url: 'graph_update.php',
                success: function(data) {
                    var newData = JSON.parse(data);
                    
                    newData.temperatureData.reverse();
                    newData.humidityData.reverse();
                    newData.timeData.reverse();
                    
                    chart.data.datasets[0].data = newData.temperatureData;
                    chart.data.datasets[1].data = newData.humidityData;
                    chart.data.labels = newData.timeData;
                    
                    chart.update();
                }
            });
        }, 1000);
    }

    refreshChart();
    </script>
</body>
</html>
