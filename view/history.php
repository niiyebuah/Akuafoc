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
  <title>Akuafo Farm Recordings Archives </title>

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
            <!--<a href="index.php">AKUAFO</a>-->
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
          <a href="farmrecordings.php"><i data-feather="inbox"></i>Farm Recordings</a>
        </li>
        <li>
          <a href="../pump/esp-outputs.php"><i data-feather="calendar"></i>Control System</a>
        </li>
        <li>
        <li class="active-page">
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
              <!--<h5 style="font-family: Arial, sans-serif; font-size: 20px; font-weight: bold;">FARM RECORDS ARCHIVE</h5>-->
            </div>              
            <!--<p class="card-description">This table gives you an overview of your recordings in the farms.</p>-->
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
                            // $servername = "localhost";
                            // $username = "root";
                            // $password = "";
                            // $dbname = "akuafo_db";

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
                                $records_per_page = 20;

                                // Get the current page number
                                if(isset($_GET['page']) && is_numeric($_GET['page'])) {
                                $page = $_GET['page'];
                                } else {
                                $page = 1;
                                }

                                // Calculate the offset for the query
                                $offset = ($page - 1) * $records_per_page;

                                // Get the total number of records in the database
                                $sql = "SELECT COUNT(*) AS total FROM sensor_data";
                                $result = mysqli_query($conn, $sql);
                                $row = mysqli_fetch_assoc($result);
                                $total_records = $row['total'];

                                // Calculate the total number of pages
                                $total_pages = ceil($total_records / $records_per_page);

                                // Query for the current page of data
                                $sql = "SELECT * FROM sensor_data LIMIT $offset, $records_per_page";
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

                                // Display the pagination links
                                $html .= '<tr><td colspan="6">';
                                $html .= '<nav aria-label="Page navigation example">';
                                $html .= '<ul class="pagination justify-content-center">';

                                // First page link
                                if ($page > 1) {
                                    $html .= '<li class="page-item"><a class="page-link text-dark" href="?page=1">First</a></li>';
                                }

                                // Previous page link
                                if ($page > 2) {
                                    $prev_page = $page - 1;
                                    $html .= '<li class="page-item"><a class="page-link text-dark" href="?page=' . $prev_page . '">Previous</a></li>';
                                }

                                // Page links
                                for ($i = max(1, $page - 2); $i <= min($page + 2, $total_pages); $i++) {
                                    if ($i == $page) {
                                        $html .= '<li class="page-item active"><a class="page-link text-dark" href="#">' . $i . '</a></li>';
                                    } else {
                                        $html .= '<li class="page-item"><a class="page-link text-dark" href="?page=' . $i . '">' . $i . '</a></li>';
                                    }
                                }

                                // Next page link
                                if ($page < $total_pages - 1) {
                                    $next_page = $page + 1;
                                    $html .= '<li class="page-item"><a class="page-link text-dark" href="?page=' . $next_page . '">Next</a></li>';
                                }

                                // Last page link
                                if ($page < $total_pages) {
                                    $html .= '<li class="page-item"><a class="page-link text-dark" href="?page=' . $total_pages . '">Last</a></li>';
                                }

                                $html .= '</ul></nav>';
                                $html .= '</td></tr>';

                                return $html;
                            }
                            
                            // Call the fetch_data() function to initially populate the table
                            echo fetch_data();
                        ?>

                        <!-- Creating search bar that allows search for Queries from the table using the SensorDataId -->
                        <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Search by SensorDataId" aria-label="Search by SensorDataId" aria-describedby="button-addon2" id="search-input">
                        <button class="btn btn-outline-secondary" type="button" id="button-addon2">Search</button>
                    </div>

                    <script>
                        function searchTable() {
                            // Get input value
                            var input = document.getElementById("search-input").value.trim();
                            if (input === '') {
                            // Show a pop-up message if the search box is empty
                            var popup = $("<div></div>")
                                .html("Error: Check your Search Bar")
                                .css({

                                    "position": "absolute",
                                    "top": $("#button-addon2").offset().top - 40,
                                    "left": $("#button-addon2").offset().left,
                                    "transform": "translate(-50%, -50%)",
                                    "background-color": "#333",
                                    "color": "#fff",
                                    "padding": "8px",
                                    "border-radius": "5px",
                                    "opacity": 10,
                                    "z-index": 9999,
                                    "text-align": "center"

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
                            } else {
                            // Get table rows
                            var rows = document.getElementsByTagName("tr");
                            // Loop through rows and hide those that don't match the search query
                            for (var i = 0; i < rows.length; i++) {
                                var sensorDataId = rows[i].cells[0].textContent;
                                if (sensorDataId.indexOf(input) > -1) {
                                rows[i].style.display = "";
                                } else {
                                rows[i].style.display = "none";
                                }
                            }
                            }
                        }
                        // Add event listener to search button
                        document.getElementById("button-addon2").addEventListener("click", searchTable);
                    </script>

                    </tbody>
                </table>
                <!-- JavaScript code for refreshing the table -->                

                <!-- Add an ID to the refresh button -->
                <!--<button onclick="window.location.href='farmrecordings.php'">Back</button>-->
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
