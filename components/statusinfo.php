<?php
include 'db.php';
$conn = mysqli_connect('localhost', $db_id, $db_pw, $db_name);
$query = "SELECT * FROM tbl ORDER BY rt DESC LIMIT 7;";
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
            <div class="graphwrap">
                <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; width: 100%; height: 100%;">
                    <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; width: 100%; height: 60%">
                        <?php include 'graph.php'; ?>
                    </div>
                    <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; width: 100%; height: 40%">
                        <?php include 'chart.php'; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fetch temperature and humidity values from PHP
    var temperature = "<?php echo $row['temperature']; ?>";
    var humidity = "<?php echo $row['humidity']; ?>";

    // Function to animate number counting
    function animateCount(element, targetValue) {
        var currentValue = 0;
        var increment = Math.ceil(targetValue / 50); // Adjust the increment value as needed

        var interval = setInterval(function() {
            currentValue += increment;
            if (currentValue >= targetValue) {
                currentValue = targetValue;
                clearInterval(interval);
            }
            element.innerHTML = currentValue;
        }, 50); // Adjust the interval time as needed
    }

    // Animate temperature count
    var temperatureElement = document.getElementById("temperature");
    animateCount(temperatureElement, temperature);

    // Animate humidity count
    var humidityElement = document.getElementById("humidity");
    animateCount(humidityElement, humidity);
</script>
