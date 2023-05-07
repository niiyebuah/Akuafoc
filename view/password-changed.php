<?php require_once "controllerUserData.php"; ?>
<?php
if($_SESSION['info'] == false){
    header('Location: login.php');  
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Login - Akuafo</title>
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
                                    <?php 
                                    if(isset($_SESSION['info'])){
                                        ?>
                                        <div class="alert alert-success text-center">
                                        <?php echo $_SESSION['info']; ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                        <form action="login.php" method="POST">
                                            <div class="form-group">
                                                <input class="btn btn-block login-btn mb-4" type="submit" name="login-now" value="Login Now">
                                            </div>
                                        </form>
                                 </div>
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