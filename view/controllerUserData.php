<?php 
session_start();
require "connection.php";
$email = "";
$name = "";
$errors = array();

include_once ("../controllers/IOT.php");


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

set_include_path('../');
require_once('php-mailer/src/Exception.php');
require_once('php-mailer/src/PHPMailer.php');
require_once('php-mailer/src/SMTP.php');



$conn = new mysqli($servername, $username, $password, $dbname); 
$sql="SELECT * FROM sensor_data ORDER BY SensorDataId DESC LIMIT 1";
$result= mysqli_query($conn,$sql);

$row=mysqli_fetch_assoc($result);

$Time=$row['Date'];
$SensorDataId=$row['SensorDataId'];
$humidity=$row['humidity'];
$temperature=$row['temperature'];
$soilmoisture=$row['soilmoisture'];
$waterlevel=$row['waterlevel'];

// Query database for soil moisture data
$sql = "SELECT SensorDataId, soilmoisture, Date FROM sensor_data ORDER BY SensorDataId DESC LIMIT 10";
$result = mysqli_query($conn, $sql);

// Create array to hold data for soil moisture graph
$soil_moisture_data = array();
while($row = mysqli_fetch_assoc($result)) {
    $soil_moisture_data[] = $row;
}

// Convert soil moisture data to JSON format
$json_soil_moisture_data = json_encode($soil_moisture_data);


// Query database for temperature data
$sql = "SELECT SensorDataId, temperature, Date  FROM sensor_data ORDER BY SensorDataId DESC LIMIT 10";
$result = mysqli_query($conn, $sql);

// Create array to hold data for temperature graph
$temperature_data = array();
while($row = mysqli_fetch_assoc($result)) {
    $temperature_data[] = $row;
}

// Convert temperature data to JSON format
$json_temperature_data = json_encode($temperature_data);

// Query database for water level data
$sql = "SELECT SensorDataId, waterlevel, Date FROM sensor_data ORDER BY SensorDataId DESC LIMIT 10";
$result = mysqli_query($conn, $sql);

// Create array to hold data for water level graph
$water_level_data = array();
while($row = mysqli_fetch_assoc($result)) {
    $water_level_data[] = $row;
}

// Convert water level data to JSON format
$json_water_level_data = json_encode($water_level_data);

// Query database for humidity data
$sql = "SELECT SensorDataId, humidity, Date FROM sensor_data ORDER BY SensorDataId DESC LIMIT 10";
$result = mysqli_query($conn, $sql);

// Create array to hold data for humidity graph
$humidity_data = array();
while($row = mysqli_fetch_assoc($result)) {
    $humidity_data[] = $row;
}

// Convert humidity data to JSON format
$json_humidity_data = json_encode($humidity_data);

// Close database connection
mysqli_close($conn);

//if user signup button
if(isset($_POST['signup'])){
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
    if($password !== $cpassword){
        $errors['password'] = "Confirm password not matched!";
    }
    $email_check = "SELECT * FROM usertable WHERE email = '$email'";
    $res = mysqli_query($con, $email_check);
    if(mysqli_num_rows($res) > 0){
        $errors['email'] = "Email that you have entered is already exist!";
    }
    if(count($errors) === 0){
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $code = rand(999999, 111111);
        $status = "notverified";
        $insert_data = "INSERT INTO usertable (name, email, password, code, status)
                        values('$name', '$email', '$encpass', '$code', '$status')";
        $data_check = mysqli_query($con, $insert_data);
        if($data_check){
            $subject = "Email Verification Code - AKUAFO";
            $message = "Your verification code is <br><strong>$code</strong>";
            $sender = "aaronyebuah1234@gmail.com";

            $MAIL_HOST = "smtp.gmail.com";
            $MAIL_PORT = 465;
            $MAIL_USERNAME = "aaronyebuah1234@gmail.com";
            $MAIL_PASSWORD = "iuxflgyzxpsesvux";                 
            $MAIL_ENCRYPTION = "ssl";

            $mail = new PHPMailer(true);
            $mail->isSMTP(); //Send using SMTP
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
            $mail->SMTPDebug = 3;
            $mail->SMTPAuth = true; //Enable SMTP authentication
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Host = $MAIL_HOST; //Set the SMTP server to send through
            $mail->Username = $MAIL_USERNAME; //SMTP username
            $mail->Password = $MAIL_PASSWORD;
            $mail->Port = $MAIL_PORT;
            $mail->setFrom($sender);
            $mail->addAddress($email);
            // $mail->addAddress($MAIL_USERNAME);           // To receive copy of email in your gmailinbox
            // $mail->addAddress('your.personal@emailaddress.com');           // Add any email here to receive copy of email
            $mail->Subject = $subject;
            $mail->isHTML(true);
            $mail->Body = $message;
        
            
            if($mail->send()){
                $info = "We've sent a verification code to your email - $email";
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                header('location: user-otp.php');
                exit();
            }else{
                $errors['otp-error'] = "Failed while sending code!";
            }
        }else{
            $errors['db-error'] = "Failed while inserting data into database!";
        }
    }

}
    //if user click verification code submit button
    if(isset($_POST['check'])){
        $_SESSION['info'] = "";
        $otp_code = mysqli_real_escape_string($con, $_POST['otp']);
        $check_code = "SELECT * FROM usertable WHERE code = $otp_code";
        $code_res = mysqli_query($con, $check_code);
        if(mysqli_num_rows($code_res) > 0){
            $fetch_data = mysqli_fetch_assoc($code_res);
            $fetch_code = $fetch_data['code'];
            $email = $fetch_data['email'];
            $code = 0;
            $status = 'verified';
            $update_otp = "UPDATE usertable SET code = $code, status = '$status' WHERE code = $fetch_code";
            $update_res = mysqli_query($con, $update_otp);
            if($update_res){
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;
                header('location: login.php');
                exit();
            }else{
                $errors['otp-error'] = "Failed while updating code!";
            }
        }else{
            $errors['otp-error'] = "You've entered incorrect code!";
        }
    }

    //if user click login button
    if(isset($_POST['login'])){
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $check_email = "SELECT * FROM usertable WHERE email = '$email'";
        $res = mysqli_query($con, $check_email);
        if(mysqli_num_rows($res) > 0){
            $fetch = mysqli_fetch_assoc($res);
            $fetch_pass = $fetch['password'];
            if(password_verify($password, $fetch_pass)){
                $_SESSION['email'] = $email;
                $status = $fetch['status'];
                if($status == 'verified'){
                  $_SESSION['email'] = $email;
                  $_SESSION['password'] = $password;
                    header('location: dashboard.php');
                }else{
                    $info = "It's look like you haven't still verify your email - $email";
                    $_SESSION['info'] = $info;
                    header('location: user-otp.php');
                }
            }else{
                $errors['email'] = "Incorrect email or password!";
            }
        }else{
            $errors['email'] = "It's look like you're not yet a member! Click on the bottom link to signup.";
        }
    }

    //if user click continue button in forgot password form
    if(isset($_POST['check-email'])){
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $check_email = "SELECT * FROM usertable WHERE email='$email'";
        $run_sql = mysqli_query($con, $check_email);
        if(mysqli_num_rows($run_sql) > 0){
            $code = rand(999999, 111111);
            $insert_code = "UPDATE usertable SET code = $code WHERE email = '$email'";
            $run_query =  mysqli_query($con, $insert_code);
            if($run_query){
                $subject = "Password Reset Code";
                $message = "Your password reset code is $code";
                $sender = "aaronyebuah1234@gmail.com";
                
                $MAIL_HOST = "smtp.gmail.com";
                $MAIL_PORT = 465;
                $MAIL_USERNAME = "aaronyebuah1234@gmail.com";
                $MAIL_PASSWORD = "iuxflgyzxpsesvux";                 
                $MAIL_ENCRYPTION = "ssl";

                $mail = new PHPMailer(true);
                $mail->isSMTP(); //Send using SMTP
                // $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
                $mail->SMTPDebug = 3;
                $mail->SMTPAuth = true; //Enable SMTP authentication
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Host = $MAIL_HOST; //Set the SMTP server to send through
                $mail->Username = $MAIL_USERNAME; //SMTP username
                $mail->Password = $MAIL_PASSWORD;
                $mail->Port = $MAIL_PORT;
                $mail->setFrom($sender);
                $mail->addAddress($email);
                // $mail->addAddress($MAIL_USERNAME);           // To receive copy of email in your gmailinbox
                // $mail->addAddress('your.personal@emailaddress.com');           // Add any email here to receive copy of email
                $mail->Subject = $subject;
                $mail->isHTML(true);
                $mail->Body = $message;
            
                
                if($mail->send()){
                    $info = "We've sent a Password reset otp verification to your email - $email";
                    $_SESSION['info'] = $info;
                    $_SESSION['email'] = $email;
                    $_SESSION['password'] = $password;
                    header('location: reset-code.php');
                    exit();
                }else{
                    $errors['otp-error'] = "Failed while sending code!";
                }
            }else{
                $errors['db-error'] = "Something went wrong!";
            }
        }else{
            $errors['email'] = "This email address does not exist!";
        }
    }

    //if user click check reset otp button
    if(isset($_POST['check-reset-otp'])){
        $_SESSION['info'] = "";
        $otp_code = mysqli_real_escape_string($con, $_POST['otp']);
        $check_code = "SELECT * FROM usertable WHERE code = $otp_code";
        $code_res = mysqli_query($con, $check_code);
        if(mysqli_num_rows($code_res) > 0){
            $fetch_data = mysqli_fetch_assoc($code_res);
            $email = $fetch_data['email'];
            $_SESSION['email'] = $email;
            $info = "Please create a new password that you don't use on any other site.";
            $_SESSION['info'] = $info;
            header('location: new-password.php');
            exit();
        }else{
            $errors['otp-error'] = "You've entered incorrect code!";
        }
    }

    //if user click change password button
    if(isset($_POST['change-password'])){
        $_SESSION['info'] = "";
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
        if($password !== $cpassword){
            $errors['password'] = "Confirm password not matched!";
        }else{
            $code = 0;
            $email = $_SESSION['email']; //getting this email using session
            $encpass = password_hash($password, PASSWORD_BCRYPT);
            $update_pass = "UPDATE usertable SET code = $code, password = '$encpass' WHERE email = '$email'";
            $run_query = mysqli_query($con, $update_pass);
            if($run_query){
                $info = "Your password changed. Now you can login with your new password.";
                $_SESSION['info'] = $info;
                header('Location: password-changed.php');
            }else{
                $errors['db-error'] = "Failed to change your password!";
            }
        }
    }
    
   //if login now button click
    if(isset($_POST['login-now'])){
        header('Location: login.php');
    }
?>