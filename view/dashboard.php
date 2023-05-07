
<?php require_once "controllerUserData.php"; ?>
<?php 
$email = $_SESSION['email'];
$password = $_SESSION['password'];
if($email != false && $password != false){
    $sql = "SELECT * FROM usertable WHERE email = '$email'";
    $run_Sql = mysqli_query($con, $sql);
    if($run_Sql){
        $fetch_info = mysqli_fetch_assoc($run_Sql);
        $status = $fetch_info['status'];
        $code = $fetch_info['code'];
        if($status == "verified"){
            if($code != 0){
                header('Location: reset-code.php');
            }
        }else{
            header('Location: user-otp.php');
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Responsive Admin Dashboard Template">
        <meta name="keywords" content="admin,dashboard">
        <meta name="author" content="stacks">
        <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        
        <!-- Title -->
        <title>Akuafo Dashboard</title>

        <!-- Styles -->
        <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap" rel="stylesheet">
        <link href="../plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../plugins/font-awesome/css/all.min.css" rel="stylesheet">
        <link href="../plugins/perfectscroll/perfect-scrollbar.css" rel="stylesheet">
        <link href="../plugins/apexcharts/apexcharts.css" rel="stylesheet">

      
        <!-- Theme Styles -->
        <link href="../css/main.min.css" rel="stylesheet">
        <link href="../css/custom.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
      <div class='loader'>
        <!-- <div class='spinner-grow text-primary' role='status'>
          <span class='sr-only'>Loading...</span>
        </div> -->
      </div>
        <div class="page-container">
            <div class="page-header">
                <nav class="navbar navbar-expand-lg d-flex justify-content-between">
                  <div class="" id="navbarNav">
                    <ul class="navbar-nav" id="leftNav">
                      <li class="nav-item">
                        <a class="nav-link" id="sidebar-toggle" href="#"><i data-feather="arrow-left"></i></a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Home</a>
                      </li>
                    </ul>
                    </div>
                    <div class="logo">


                      <!-- <a class="navbar-brand" href="index.php"></a> -->
                  <!--<a href="index.php">AKUAFO</a>-->
                  </div>
                    <div class="" id="headerNav">
                      <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                          <a class="nav-link profile-dropdown" href="#" id="profileDropDown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><img src="../images/avatars/avatar.jpg" alt=""></a>
                          <div class="dropdown-menu dropdown-menu-end profile-drop-menu" aria-labelledby="profileDropDown">
                            <a class="dropdown-item" href="../view/farmrecordings.php"><i data-feather="edit"></i>Farm Recordings<span class="badge rounded-pill bg-success">12</span></a>
                            <!-- <a class="dropdown-item" href="../view/newfarm.php"><i data-feather="check-circle"></i>Tasks</a> -->
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../view/changepassword.php"><i data-feather="settings"></i>Change Password</a>
                            <a class="dropdown-item" href="../actions/logout.php"><i data-feather="log-out"></i>Logout</a>
                          </div>
                        </li>
                      </ul>
                  </div>
                </nav>
            </div>
            <div class="page-sidebar">
                <ul class="list-unstyled accordion-menu">
                  <li class="sidebar-title">
                  </li>
                  <li class="active-page">
                    <a href="index.php"><i data-feather="home"></i>Dashboard</a>
                  </li>
                  <li>
                    <a href="../view/farmrecordings.php"><i data-feather="inbox"></i>Farm Recordings</a>
                  </li>
                  <li>
                    <a href="../pump/esp-outputs.php"><i data-feather="calendar"></i>Control System</a>
                  </li>
                  <li>
                    <a href="history.php"><i data-feather="clock"></i>Archives</a>
                  </li>
                </ul>
            </div>
            <div class="page-content">
              <div class="main-wrapper">
                <div class="text-center"> 
                  <div class="row d-flex justify-content-center">
                    <div class="col-md-6 col-xl-3">
                      <div class="card stat-widget">
                        <div class="card-body">
                          <h5 class="card-title" style="text-align:center;">Humidity</h5>
                          <div class="card-footer text-muted" title="<?php echo $Time; ?>" style="text-align:center;">Last updated on: <?php echo $Time; ?></div>
                          <h2><?php echo $humidity; ?></h2>
                          <div class="progress">
                            <div class="progress-bar bg-info progress-bar-striped" role="progressbar" style="width: <?php echo $humidity; ?>%" aria-valuenow="<?php echo $humidity; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  <div class="col-md-6 col-xl-3">
                    <div class="card stat-widget">
                        <div class="card-body">
                        <h5 class="card-title" style="text-align:center;">Temperature</h5>
                        <!--<h2>4940</h2> -->
                              <div class="card-footer text-muted" title="<?php echo $Time; ?>" style="text-align:center;">Last updated on: <?php echo $Time; ?></div>
                              <h2><?php echo $temperature; ?></h2>
                              <!--<p>Date</p> -->
                              <div class="progress">
                                <div class="progress-bar bg-success progress-bar-striped" role="progressbar" style="width: <?php echo $temperature; ?>%" aria-valuenow="<?php echo $temperature; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                              </div>
                        </div>
                    </div>
                  </div>
                  <div class="col-md-6 col-xl-3">
                    <div class="card stat-widget">
                        <div class="card-body">
                        <h5 class="card-title" style="text-align:center;">Soil moisture</h5>
                            <!--<h2>4940</h2> -->
                            <div class="card-footer text-muted" title="<?php echo $Time; ?>" style="text-align:center;">Last updated on: <?php echo $Time; ?></div>                             
                            <h2><?php echo $soilmoisture; ?></h2>
                              <!--<p>Date</p> -->
                              <div class="progress">
                                <div class="progress-bar bg-danger progress-bar-striped" role="progressbar" style="width: <?php echo $soilmoisture; ?>%" aria-valuenow="<?php echo $soilmoisture; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                              </div>
                        </div>
                    </div>
                  </div>
                  <div class="col-md-6 col-xl-3">
                    <div class="card stat-widget">
                        <div class="card-body">
                        <h5 class="card-title" style="text-align:center;">Water Level</h5>
                              <div class="card-footer text-muted" title="<?php echo $Time; ?>" style="text-align:center;">Last updated on: <?php echo $Time; ?></div>
                              <h2><?php echo $waterlevel; ?></h2>
                              <!--<p>Date</p> -->
                              <div class="progress">
                                <div class="progress-bar bg-primary progress-bar-striped" role="progressbar" style="width: <?php echo $waterlevel; ?>%" aria-valuenow="<?php echo $waterlevel; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                              </div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <style>
              .chart-container {
              width: 45%;
              margin: 20px;
              display: inline-block;
              border: 1px solid #ccc;
              border-radius: 5px;
              box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
              padding: 10px;
            }

            .chart-title {
              font-size: 18px;
              font-weight: bold;
              margin-bottom: 10px;
              text-align: center;
            }
            </style>

            <div class="chart-container">
              <h2 class="chart-title">Soil Moisture</h2>
              <canvas id="soilMoistureChart"></canvas>
            </div>

            <div class="chart-container">
              <h2 class="chart-title">Temperature</h2>
              <canvas id="temperatureChart"></canvas>
            </div>

            <div class="chart-container">
              <h2 class="chart-title">Humidity</h2>
              <canvas id="humidityChart"></canvas>
            </div>

            <div class="chart-container">
              <h2 class="chart-title">Water Level</h2>
              <canvas id="waterLevelChart"></canvas>
            </div>


            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
              // Convert JSON data to JavaScript arrays for each chart
              var soilMoistureData = <?php echo $json_soil_moisture_data ?>;
              var temperatureData = <?php echo $json_temperature_data ?>;
              var humidityData = <?php echo $json_humidity_data ?>;
              var waterLevelData = <?php echo $json_water_level_data ?>;

              // Create arrays to hold labels and values for each chart
              var soilMoistureLabels = [];
              var soilMoistureValues = [];
              soilMoistureData.forEach(function(item) {
                  var date = new Date(item.Date); // Convert to JavaScript Date object
                  soilMoistureLabels.push(item.Date);
                  soilMoistureValues.push(item.soilmoisture);
              });

              var temperatureLabels = [];
              var temperatureValues = [];
              temperatureData.forEach(function(item) {
                  var date = new Date(item.Date); // Convert to JavaScript Date object
                  temperatureLabels.push(item.Date);
                  temperatureValues.push(item.temperature);
              });

              var humidityLabels = [];
              var humidityValues = [];
              humidityData.forEach(function(item) {
                  var date = new Date(item.Date); // Convert to JavaScript Date object
                  humidityLabels.push(item.Date);
                  humidityValues.push(item.humidity);
              });

              var waterLevelLabels = [];
              var waterLevelValues = [];
              waterLevelData.forEach(function(item) {
                  var date = new Date(item.Date); // Convert to JavaScript Date object
                  waterLevelLabels.push(item.Date);
                  waterLevelValues.push(item.waterlevel);
              });

              // Create charts using Chart.js
              var soilMoistureChart = new Chart(document.getElementById("soilMoistureChart"), {
                type: 'line',
                data: {
                    labels: soilMoistureLabels,
                    datasets: [{
                        label: 'Soil Moisture',
                        data: soilMoistureValues,
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                }
              });

              var temperatureChart = new Chart(document.getElementById("temperatureChart"), {
                type: 'line',
                data: {
                    labels: temperatureLabels,
                    datasets: [{
                        label: 'Temperature',
                        data: temperatureValues,
                        fill: false,
                        borderColor: 'rgb(255, 99, 132)',
                        tension: 0.1
                    }]
                }
              });

              var humidityChart = new Chart(document.getElementById("humidityChart"), {
                type: 'line',
                data: {
                    labels: humidityLabels,
                    datasets: [{
                        label: 'Humidity',
                        data: humidityValues,
                        fill: false,
                        borderColor: 'rgb(54, 162, 235)',
                        tension: 0.1
                    }]
                }
              });

              var waterLevelChart = new Chart(document.getElementById("waterLevelChart"), {
                type: 'line',
                data: {
                    labels: waterLevelLabels,
                    datasets: [{
                        label: 'Water Level',
                        data: waterLevelValues,
                        fill: false,
                        borderColor: 'rgb(153, 102, 255)',
                        tension: 0.1
                    }]
                }
              });

            </script>
          
        <!-- Javascripts -->
        <script src="../plugins/jquery/jquery-3.4.1.min.js"></script>
        <script src="https://unpkg.com/@popperjs/core@2"></script>
        <script src="../plugins/bootstrap/js/bootstrap.min.js"></script>
        <script src="https://unpkg.com/feather-icons"></script>
        <script src="../plugins/perfectscroll/perfect-scrollbar.min.js"></script>
        <script src="../plugins/apexcharts/apexcharts.min.js"></script>
        <script src="../js/main.min.js"></script>
        <script src="../js/pages/dashboard.js"></script>
    </body>
</html>

