<?php

require("../controllers/customer_controller.php");
require("../settings/core.php");

//form validation with php
$errors = array();


if (isset($_POST["submit"])) {
    $password = $_POST['loginpassword'];
    $cus_email =$_POST['loginemail'];
    

    if(empty($cus_email)){
        array_push($errors, "Enter Email");
    }

    if(empty($password)){
        array_push($errors, "Enter Password");
    }

    $data = duplicate_email($cus_email);
    if (empty($data)) {
        array_push($errors, "Invalid Email or Password"); 
    }


    if (empty($errors)) {
        $logincheck = login_customer_ctrl($cus_email,$password);

        if ($logincheck){
            $results = logincustomer($cus_email);
            echo $results['user_role']; 
            $_SESSION['name'] = $results['customer_name'];
            $_SESSION['customer_id'] = $results['customer_id'];
            $_SESSION['customer_email'] = $results['customer_email'];
            $_SESSION['role'] = $results['user_role'];
            $_SESSION['loggedin']=true;
    
            if($_SESSION['role'] == 1){
            header("Location: ../view/dashboard.php");
            }else{
            header("Location: ../view/dashboard.php");
            }
    
        } else{
            $errors = array();
            array_push($errors, "Invalid Email or Password"); 
            $_SESSION['errors'] = $errors;
            header('location: ../view/login.php'); 
        }


    } else {
     
        $_SESSION['errors'] = $errors;
        header('location: ../view/login.php'); 
    }
    
    


}

?>