<?php
require("../controllers/customer_controller.php");
$errors = array();

//form validation with php
if (isset($_POST["submit"])) {
    $fname = $_POST['fullname'];
    $lname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    $contact = $_POST['contact'];

    //Email Verification
    $apiKey = '79905cbcf44b8c0f3f8b0d6f230075f9a011ce3ff273bc4e4430ec2d1753';
    $url = 'https://api.quickemailverification.com/v1/verify?&apikey='.$apiKey.'&email='.$email;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response);

    if ($result->status == 'valid') {
        echo 'Email is valid!';
    } elseif ($result->status == 'invalid') {
        echo 'Email is invalid.';
    } else {
        echo 'Unable to verify email.';
    }

    //regex password
    $pattern = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/";

    if(empty($fname)){
        array_push($errors, "Enter first name");
    }
    
    if(empty($lname)){
        array_push($errors, "Enter last name");
    }

    if(empty($email)){
        array_push($errors, "Enter Email");
    }

    if(empty($password)){
        array_push($errors, "Enter password");
    }

    if(empty($contact)){
        array_push($errors, "Enter a valid contact");
    }

    if ($password != $confirm) {
        array_push($errors, "Passwords must match"); 
    }

    if (preg_match($pattern, $password) != 1) {
        array_push($errors, "Password must contain at least one number and one uppercase 
        and lowercase letter, a symbol and at least 6 or more characters"); 
    }

    if(strlen($contact) != 10){
        array_push($errors, "Invalid contact info");  
    }

    if ($contact[0] != '0') {
        array_push($errors, "Invalid Number format"); 
    }

    if (count($errors) == 0) {
        // encryption of password using hash.
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $check = addcustomer_ctrl($fname, $lname, $email, $hash, $contact);
        if ($check) {
            echo "Registration Successful";
            // send email to user
            $to = $email;
            $subject = "Thank you for signing up to the Akuafo Website";
            $message = "Dear $fname, \r\n\r\nThank you for signing up to the Akuafo Website.";
            $headers = "From: aaron.adom-malm@ashesi.edu.gh";
            mail($to, $subject, $message, $headers);
            header("Location: ../view/login.php");
        } else {
            echo "Registration failed";
            header('location: ../view/register.php'); 
        }
    } else {
        session_start();
        $_SESSION['errors'] = $errors;
        header('location: ../view/register.php'); 
    }
}
?>