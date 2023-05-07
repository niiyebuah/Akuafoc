<?php require_once "controllerUserData.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign-up Akuafo</title>
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
                        <img src="../images/regg.jpg" alt="login" class="login-card-img">
                    </div>
                    <div class="col-md-5">
                        <div class="card-body">
                        <div class="brand-wrapper">
                            <h6>Welcome To Akuafo</h6>
                        </div>
                          <p class="login-card-description">Sign Up</p>
                            <form action="signup-user.php" method="POST" autocomplete="">
                                <?php
                                if(count($errors) == 1){
                                    ?>
                                    <div class="alert alert-danger text-center">
                                        <?php
                                        foreach($errors as $showerror){
                                            echo $showerror;
                                        }
                                        ?>
                                    </div>
                                    <?php
                                }elseif(count($errors) > 1){
                                    ?>
                                    <div class="alert alert-danger">
                                        <?php
                                        foreach($errors as $showerror){
                                            ?>
                                            <li><?php echo $showerror; ?></li>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="form-group">
                                    <input class="form-control" type="text" name="name" placeholder="Full Name" required value="<?php echo $name ?>">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" type="email" name="email" placeholder="Email Address" required value="<?php echo $email ?>">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" type="password" name="password" placeholder="Password" required>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" type="password" name="cpassword" placeholder="Confirm password" required>
                                </div>
                                <div class="form-group">
                                    <input class="btn btn-block login-btn mb-4" type="submit" name="signup" value="Signup">
                                </div>
                                <p class="login-card-footer-text">Already have an account? <a href="login.php" class="text-reset">Login</a></p>
                            </form>
                </div>
            </div>
        </div>
    </main>  
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>  
</body>
</html>