<?php
//connect to database class
require_once("../settings/db_class.php");

/**
 *@author Gerald Darko
 */

class customerclass extends db_connection
{
	//--INSERT--//

	function insertcustomer($cus_fname, $cus_lname, $cus_email, $cus_pass, $cus_contact)
	{
		$sql = "INSERT INTO `customer`(`customer_firstname`, `customer_lastname`, `customer_email`, `customer_pass`, `customer_contact`) VALUES ('$cus_fname', '$cus_lname','$cus_email','$cus_pass','$cus_contact')";

		return $this->db_query($sql);
	}

	//--SELECT--//
	function logincustomer($cus_email)
	{

		$sql = "SELECT * FROM customer WHERE customer_email = '$cus_email'";

		return $this->db_fetch_one($sql);
	}

	function user_email($c_id)
	{
		$sql = "SELECT customer_email FROM customer WHERE customer_id = '$c_id'";

		return $this->db_fetch_one($sql);
	}



	function select_one_user($c_id)
	{
		$sql = "SELECT * FROM customer WHERE customer_id = '$c_id'";

		return $this->db_fetch_one($sql);
	}

	function select_user()
	{
		$sql = "SELECT * FROM `customer`";

		$prods = $this->db_fetch_all($sql);
		return $prods;
	}

	function select_email($email)
	{
		$sql = "SELECT customer_email FROM customer WHERE customer_email = '$email'";
		return $this->db_fetch_one($sql);
	}

}
