<?php
//Check if user is logged in
if(!isset($_SESSION['customer_id'])) {
  // Redirect user to login page if not logged in
  header("Location: login.php");
  exit;
}
$username = "root";
$password = "";
$dbname = "akuafo_db";

$conn = new mysqli($servername, $username, $password, $dbname); 
session_start();
$customer_id = $_SESSION['customer_id']; // assuming you have stored the customer ID in the session variable

// get user input for other fields
$farm_id = $_POST['farm_id'];
$farm_name = $_POST['farm_name'];
$crop_type = $_POST['crop_type'];
$farm_size = $_POST['farm_size'];
$irrigation_method = $_POST['irrigation_method'];

// prepare the SQL statement
$sql = "INSERT INTO farmer (customer_id, farm_id, farm_name, crop_type, farm_size, irrigation_method) VALUES ('$customer_id', '$farm_id', '$farm_name', '$crop_type', '$farm_size', '$irrigation_method')";
$result= mysqli_query($conn,$sql);

// execute the SQL statement
if (mysqli_query($conn, $sql)) {
    echo "Record inserted successfully";
} else {
    echo "Error inserting record: " . mysqli_error($conn);
}

// close the database connection
mysqli_close($conn);

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Log in to Akuafo</title>
  <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/login.css">
</head>
<body>
  <main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
    <div class="container">
      <div class="card login-card">
        <div class="row no-gutters">
          <div class="col-md-7">
            <img src="../images/loginpic.jpg" alt="login" class="login-card-img">
          </div>
          <div class="col-md-5">
            <div class="card-body">
              <div class="brand-wrapper">
               <h6>Welcome To Akuafo</h6>
              </div>
            
                  <div class="form-group">
                    <h1>Add Farm</h1>
                    <form method="post">
                        <label for="farm_name">Farm Name</label>
                        <input type="text" name="farm_name" required>
                        <br>
                        <label for="crop_type">Crop Type</label>
                        <input type="text" name="crop_type" required>
                        <br>
                        <label for="farm_size">Farm Size</label>
                        <input type="number" name="farm_size" required>
                        <br>
                        <label for="irrigation_method">Irrigation Method</label>
                        <input type="text" name="irrigation_method" required>
                        <br>
                        <input type="submit" name="submit" value="Add Farm">
                    </form>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>
