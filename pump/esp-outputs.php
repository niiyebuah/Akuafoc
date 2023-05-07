<!--
  Rui Santos
  Complete project details at https://RandomNerdTutorials.com/control-esp32-esp8266-gpios-from-anywhere/

  Permission is hereby granted, free of charge, to any person obtaining a copy
  of this software and associated documentation files.

  The above copyright notice and this permission notice shall be included in all
  copies or substantial portions of the Software.
-->
<?php
include_once('esp-database.php');

$result = getAllOutputs();
$html_buttons = null;
if ($result) {
    while ($row = $result->fetch_assoc()) {
        if ($button_checked = "checked")
            $row["state"] == "0";
        else {
            $row["state"] == "1";
        }
        $html_buttons .= '<h3>' . $row["name"] . ' - Board ' . $row["board"] . ' - GPIO ' . $row["gpio"] . ' </h3><label class="switch"><input type="checkbox" onchange="updateOutput(this)" id="' . $row["id"] . '" ' . $button_checked . '><span class="slider"></span></label> <br><br><br><br><br><br>';
    }
}

$result2 = getAllBoards();
$html_boards = null;
if ($result2) {
    $html_boards .= '<h3>Boards</h3>';
    while ($row = $result2->fetch_assoc()) {
        $row_reading_time = $row["last_request"];
        // Uncomment to set timezone to - 1 hour (you can change 1 to any number)
        //$row_reading_time = date("Y-m-d H:i:s", strtotime("$row_reading_time - 1 hours"));

        // Uncomment to set timezone to + 4 hours (you can change 4 to any number)
        //$row_reading_time = date("Y-m-d H:i:s", strtotime("$row_reading_time + 7 hours"));
        $html_boards .= '<p><strong>Board ' . $row["board"] . '</strong> - Last Request Time: ' . $row_reading_time . '</p>';
    }
}
?>

<!DOCTYPE HTML>
<html>


<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="esp-style.css">
    <title>ESP Output Control</title>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Responsive Admin Dashboard Template">
        <meta name="keywords" content="admin,dashboard">
        <meta name="author" content="stacks">
        <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->

        <!-- Title -->
        <title>Akuafo Control System</title>

        <!-- Styles -->
        <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap" rel="stylesheet">
        <link href="../plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../plugins/bootstrap/js/bootstrap.min.js" rel="stylesheet">
        <link href="../plugins/font-awesome/css/all.min.css" rel="stylesheet">
        <link href="../plugins/perfectscroll/perfect-scrollbar.css" rel="stylesheet">
        <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" rel="stylesheet"> -->
        <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->


        <!-- Theme Styles -->
        <link href="../css/main.min.css" rel="stylesheet">
        <link href="../css/custom.css" rel="stylesheet">
        <link href="esp-style.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
</head>

<body>

    <div class="page-container">

        <div class="page-header">
            <nav class="navbar navbar-expand-lg d-flex justify-content-between">
                <div class="" id="navbarNav">
                    <ul class="navbar-nav" id="leftNav">
                        <li class="nav-item">
                            <a class="nav-link" id="sidebar-toggle" href="#"><i data-feather="arrow-left"></i></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../view/dashboard.php">Home</a>
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
                            <a class="nav-link profile-dropdown" href="#" id="profileDropDown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false"><img src="../images/avatars/avatar.jpg"
                                    alt=""></a>
                            <div class="dropdown-menu dropdown-menu-end profile-drop-menu"
                                aria-labelledby="profileDropDown">
                                <a class="dropdown-item" href="#"><i data-feather="edit"></i>Farm Recordings<span
                                        class="badge rounded-pill bg-success">12</span></a>
                                <!-- <a class="dropdown-item" href="#"><i data-feather="check-circle"></i>Tasks</a> -->
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#"><i data-feather="settings"></i>Change Password</a>
                                <a class="dropdown-item" href="../actions/logout.php"><i
                                        data-feather="log-out"></i>Logout</a>
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
                    <a href="../view/dashboard.php"><i data-feather="home"></i>Dashboard</a>
                </li>
                <li>
                    <a href="../view/farmrecordings.php"><i data-feather="inbox"></i>Farm Recordings</a>
                </li>
                <li>
                <li class="active-page">
                    <a href="controlsystem.php"><i data-feather="calendar"></i>Control System</a>
                </li>
                <li>
                    <a href="../view/history.php"><i data-feather="clock"></i>Archives</a>
                </li>
            </ul>
        </div>

        <br>

        <br>
        <br>
        <br>

        <br>
        <br>
        <br>
        <br>
        <br>
        <center><h2>Akuafo Control System</h2></center>
        <center>
            ENABLE AUTOMATIC CONTROL
            <?php echo $html_buttons; ?>
            <!-- <?php echo $html_boards; ?> -->
            <center>
                <br><br>
                <div>
                    <!-- <form onsubmit="return createOutput();">
                        <h3>Create New Output</h3>
                        <label for="outputName">Name</label>
                        <input type="text" name="name" id="outputName"><br>
                        <label for="outputBoard">Board ID</label>
                        <input type="number" name="board" min="0" id="outputBoard">
                        <label for="outputGpio">GPIO Number</label>
                        <input type="number" name="gpio" min="0" id="outputGpio">
                        <label for="outputState">Initial GPIO State</label>
                        <select id="outputState" name="state">
                            <option value="0">0 = OFF</option>
                            <option value="1">1 = ON</option>
                        </select>
                        <input type="submit" value="Create Output">
                        <p><strong>Note:</strong> in some devices, you might need to refresh the page to see your newly
                            created
                            buttons or to remove deleted buttons.</p>
                    </form> -->
                </div>

                <script>
                    function updateOutput(element) {
                        var xhr = new XMLHttpRequest();
                        if (element.checked) {
                            xhr.open("GET", "esp-outputs-action.php?action=output_update&id=" + element.id + "&state=0", true);
                        }
                        else {
                            xhr.open("GET", "esp-outputs-action.php?action=output_update&id=" + element.id + "&state=1", true);
                        }
                        xhr.send();
                    }

                    function deleteOutput(element) {
                        var result = confirm("Want to delete this output?");
                        if (result) {
                            var xhr = new XMLHttpRequest();
                            xhr.open("GET", "esp-outputs-action.php?action=output_delete&id=" + element.id, true);
                            xhr.send();
                            alert("Output deleted");
                            setTimeout(function () { window.location.reload(); });
                        }
                    }

                    function createOutput(element) {
                        var xhr = new XMLHttpRequest();
                        xhr.open("POST", "esp-outputs-action.php", true);

                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                        xhr.onreadystatechange = function () {
                            if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                                alert("Output created");
                                setTimeout(function () { window.location.reload(); });
                            }
                        }
                        var outputName = document.getElementById("outputName").value;
                        var outputBoard = document.getElementById("outputBoard").value;
                        var outputGpio = document.getElementById("outputGpio").value;
                        var outputState = document.getElementById("outputState").value;
                        var httpRequestData = "action=output_create&name=" + outputName + "&board=" + outputBoard + "&gpio=" + outputGpio + "&state=" + outputState;
                        xhr.send(httpRequestData);
                    }
                    </script>
                <script src="../plugins/jquery/jquery-3.4.1.min.js"></script>
                <script src="https://unpkg.com/@popperjs/core@2"></script>
                <script src="../plugins/bootstrap/js/bootstrap.min.js"></script>
                <script src="https://unpkg.com/feather-icons"></script>
                <script src="../plugins/perfectscroll/perfect-scrollbar.min.js"></script>
                <script src="../plugins/blockui/jquery.blockUI.js"></script>
                <script src="../js/main.min.js"></script>
                <script src="../js/pages/blockui.js"></script>
          
</body>

</html>