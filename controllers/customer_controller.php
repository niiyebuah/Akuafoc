<?php
//connect to the user account class
include_once("../classes/customer_class.php");
require_once("../functions/function.php");




//--INSERT--//
function addcustomer_ctrl($cus_fname, $cus_lname, $cus_email, $cus_pass, $cus_contact){

// creating an instance
  $add = new customerclass;

// return method
  $run_query =  $add -> insertcustomer($cus_fname, $cus_lname, $cus_email, $cus_pass,$cus_contact);

  if ($run_query) {
    return $run_query; 
  } else {
    return false; 
  }
}


function logincustomer($cus_email){
  $login = new customerclass();
  $data = $login -> logincustomer($cus_email);
  return $data;
  
}


//--SELECT--//
//LOGIN
function login_customer_ctrl($cus_email, $cus_pass){



  // creating instance
  $login = new customerclass();

  $records = array();
  // return method
  $data = $login -> logincustomer($cus_email);

  if ($data) {
    if (verify_pass($data['customer_pass'], $cus_pass) == true) {
      return true;
    }else {
      return false;
    }
  } else {
    return false; 
  }
  
  
}

function user_email_ctrl($cid){

  // creating instance
  $user_email = new customerclass();

  // return method
  $data = $user_email -> user_email($cid);
    return $data;
}

function select_one_user($c_id){
  $user_email = new customerclass();

  // return method
  $data = $user_email -> select_one_user($c_id);

  return $data;
}

//selecting all product
function select_user_ctrl(){

  // creating instance
  $select_product = new customerclass();

  // return method
  return $select_product -> select_user();
  
}

function duplicate_email($email){

  // creating instance
  $user_email = new customerclass();

  // return method
  $data = $user_email -> select_email($email);
  return $data;
}


?>
