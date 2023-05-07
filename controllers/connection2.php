<?php
$servername = "us-cdbr-east-06.cleardb.net";
$username = "bf2c0c01ffee34";
$password = "c513e60b";
$dbname = "heroku_6f21400d1c5e59a";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//Handle GET request from IoT device
if(isset($_GET['humidity']) && isset($_GET['temperature']) && isset($_GET['soilmoisture']) && isset($_GET['waterlevel'])) {
    //Extract data from GET request

    $humidity = $_GET['humidity'];
    $temperature = $_GET['temperature'];
    $soilmoisture = $_GET['soilmoisture'];
    $waterlevel = $_GET['waterlevel'];
  
    //Insert data into database or perform other actions
    //...
  }
  

// Query for latest irrigation data

$sql = "INSERT INTO sensor_data (humidity, temperature, soilmoisture, waterlevel) VALUES ($humidity,$temperature,$soilmoisture,$waterlevel)";

$result = mysqli_query($conn, $sql);

if($result)
  echo "New record inserted Successfully!";

else
  echo "Sorry Try again";

$conn->close();

?>