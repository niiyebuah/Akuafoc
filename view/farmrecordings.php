<?php
// Initialize the session
session_start();
// // Check if the user is logged in, if not then redirect him to login page
// if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
//     header("location: login.php");
//     exit;
// }

include_once ("../controllers/IOT.php");
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

  <!-- Title -->
  <title>Akuafo Farm Records </title>

  <!-- Styles -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap" rel="stylesheet">
  <link href="../plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../plugins/font-awesome/css/all.min.css" rel="stylesheet">
  <link href="../plugins/perfectscroll/perfect-scrollbar.css" rel="stylesheet">

  <!-- Theme Styles -->
  <link href="../css/main.min.css" rel="stylesheet">
  <link href="../css/custom.css" rel="stylesheet"> 
  
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
          <!-- <a class="navbar-brand" href="index.html"></a> -->
          <!--<a href="
          ">AKUAFO</a>-->
        </div>
        <div class="" id="headerNav">
          <ul class="navbar-nav">
            <li class="nav-item dropdown">
              <a class="nav-link profile-dropdown" href="#" id="profileDropDown" role="button" data-bs-toggle="dropdown"
                aria-expanded="false"><img src="../images/avatars/avatar.jpg" alt=""></a>
              <div class="dropdown-menu dropdown-menu-end profile-drop-menu" aria-labelledby="profileDropDown">
                <a class="dropdown-item" href="../view/farmrecordings.php"><i data-feather="edit"></i>Farm Recordings<span
                    class="badge rounded-pill bg-success">12</span></a>
                <!-- <a class="dropdown-item" href="#"><i data-feather="check-circle"></i>Tasks</a> -->
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
        <li>
          <a href="dashboard.php"><i data-feather="home"></i>Dashboard</a>
        </li>
        <li>
          <li class="active-page">
          <a href="farmrecordings.php"><i data-feather="inbox"></i>Farm Recordings</a>
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
        <div class="row">
          <div class="col">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="card">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-body">
            <div style="text-align: center;">
              <h5 style="font-family: Arial, sans-serif; font-size: 20px; font-weight: bold;">REAL TIME DATA ON RECORDINGS FROM SENSORS</h5>
            </div>            
              <p class="card-title"></p>
              <div class="table-responsive">
                                
               <!-- HTML code for the table and the refresh button -->
                <table class="table">
                  <thead>
                  <tr>
                    <th scope="col" style="font-family: Arial, sans-serif; font-size: 16px; font-weight: bold;">#Id</th>
                    <th scope="col" style="font-family: Arial, sans-serif; font-size: 16px; font-weight: bold;">Date & Time</th>
                    <th scope="col" style="font-family: Arial, sans-serif; font-size: 16px; font-weight: bold;">Humidity</th>
                    <th scope="col" style="font-family: Arial, sans-serif; font-size: 16px; font-weight: bold;">Temperature</th>
                    <th scope="col" style="font-family: Arial, sans-serif; font-size: 16px; font-weight: bold;">Soil Moisture</th>
                    <th scope="col" style="font-family: Arial, sans-serif; font-size: 16px; font-weight: bold;">Water Level</th>
                  </tr>
                  </thead>
                  <tbody id="table-body">
                  <?php
                  // Connect to database and query for irrigation data
                  $servername = "us-cdbr-east-06.cleardb.net";
                  $username = "bf2c0c01ffee34";
                  $password = "c513e60b";
                  $dbname = "heroku_6f21400d1c5e59a";

                  // Create connection
                  $conn = new mysqli($servername, $username, $password, $dbname); 

                  // Function to fetch data from the database and return as an HTML string
                  function fetch_data() {
                    global $conn;
                    $html = '';
                    $sql = "SELECT * FROM sensor_data ORDER BY Date DESC LIMIT 10";
                    $result = mysqli_query($conn, $sql);
                    if ($result->num_rows > 0) {
                      while ($row = mysqli_fetch_assoc($result)) {
                        $SensorDataId = $row['SensorDataId'];
                        $Time = $row['Date'];
                        $humidity = $row['humidity'];
                        $temperature = $row['temperature'];
                        $soilmoisture = $row['soilmoisture'];
                        $waterlevel = $row['waterlevel'];

                        $html .= '<tr>
                          <td>'.$SensorDataId.'</td>
                          <td>'.$Time.'</td>
                          <td>'.$humidity.'</td>
                          <td>'.$temperature.'</td>
                          <td>'.$soilmoisture.'</td>
                          <td>'.$waterlevel.'</td>
                        </tr>';
                      }
                    }
                    return $html;
                  }

                  // Call the fetch_data() function to initially populate the table
                  echo fetch_data();
                ?>
                  </tbody>
                </table>
                <!-- JavaScript code for refreshing the table -->                

                <!-- Add an ID to the refresh button -->
                <button id="refresh-btn">Refresh</button>
                <button onclick="window.location.href='history.php'">View Archives</button>


                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                  $(document).ready(function() {
                    // Refresh the table when the refresh button is clicked
                    $("#refresh-btn").click(function() {
                      $.ajax({
                        url: "../view/farmrecordings.php",
                        success: function(data) {
                          // Find the table body element and update its content
                          $("#table-body").html($(data).find("#table-body").html());
                          // Show a popup with a message
                          var popup = $("<div></div>")
                          .html("Table has been refreshed successfully!")
                          .css({
                            "position": "fixed",
                            "top": "70%",
                            "left": "55%",
                            "transform": "translate(-50%, -50%)",
                            "background-color": "#333",
                            "color": "#fff",
                            "padding": "10px",
                            "border-radius": "5px",
                            "opacity": 0,
                            "z-index": 9999
                          })
                            .appendTo("body");

                          // Fade in the popup and fade it out after 3 seconds
                          popup.animate({ opacity: 1 }, 500, function() {
                            setTimeout(function() {
                              popup.animate({ opacity: 0 }, 500, function() {
                                popup.remove();
                              });
                            }, 3000);
                          });
                        }
                      });
                    });
                  });
                </script>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  </div>

  <!-- Javascripts -->
  <script src="../plugins/jquery/jquery-3.4.1.min.js"></script>
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  <script src="../plugins/bootstrap/js/bootstrap.min.js"></script>
  <script src="https://unpkg.com/feather-icons"></script>
  <script src="../plugins/perfectscroll/perfect-scrollbar.min.js"></script>
  <script src="../js/main.min.js"></script>


</body>
</html>

  
</body>

</html>
