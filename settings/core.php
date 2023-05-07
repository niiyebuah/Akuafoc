<?php
//start session
session_start(); 

//for header redirection
ob_start();

//funtion to check for login
function logged_in(){
if (isset($_SESSION["customer_id"])){
    return true;
};
    return false;
}

function session_login ($cid, $user_role){
    $_SESSION["customer_id"] = $cid;
    $_SESSION["user_role"] = $user_role;
}

//function to get user ID
function user_ID(){
    return $_SESSION["customer_id"];
}

//function to check for role (admin, customer, etc)
function admin_user(){
    return $_SESSION["user_role"] == 1;
}

//function to check for logout
function logged_out(){
 unset ($_SESSION["customer_id"]);
 unset ($_SESSION["user_role"]);
}

?>