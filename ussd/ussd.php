<?php


$servername = "us-cdbr-east-06.cleardb.net";
$username = "bf2c0c01ffee34";
$password = "c513e60b";
$dbname = "heroku_6f21400d1c5e59a";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$json = file_get_contents('php://input');
// decode the json data
$data = json_decode($json, true);
// Use dummy data if no request data was sent ..
$data = $data ? $data : use_dummy_ussd_data();

$sessionID = $data['sessionID'];
$userID = $data['userID'];
$newSession = $data['newSession'];
$msisdn = $data['msisdn'];
$userData = trim($data['userData']);
$network = $data['network'];
$continueSession = false;
$message = "";
$ussd_code = "*928*984#";
$i_ussd = true;     // accept any request with any ussd code


/*
*************************************************************************************************************************
*************** New USSD Session Menu 
*************************************************************************************************************************
*/
if ($newSession) {

    if ($i_ussd || (!$i_ussd && ($userData == "" || $userData == "{$ussd_code}"))) {
    // if ($userData == "" || $userData == "{$ussd_code}") {
        // This is the first request. Note how we start the response with CON
        $message = "Welcome to Akuafo, what would you like to check?\n";
        $message .= "1) Farm Sensor Readings\n";
        $message .= "2) Irrigation Control";

        $continueSession = true;
        
        $session_fb = save_session($sessionID, $msisdn, '', $conn);
        if (!$session_fb) {
            $message = "An error occured! Please try again.";
            $continueSession = false;
        }
    } else {
        $message = $userData;
        $message .= " is not identified!\n";
        $message .= "Please dial {$ussd_code} instead";
    }
}

if (!$newSession) {
    // Get the previous session record
    $session = get_session($sessionID, $msisdn, $conn);
    // split the previous session record by * to get the list of options that were entered by user in previous screens the menu
    // **********************************************************************************************
    // Example. Stages[0] is the first option that was selected in the screen 1
    //          Stages[1] is the option that was selected in the screen 2 from screen 1 ...
    //          Stages[3] is the option that was selected in the screen 3 from screen 2 after screen 1 ...
    // ************************************************************************************************************
    $stages = explode('*', $session['udata']);
    
    if ($userData == "0") {

        $message = "Welcome to Akuafo, what would you like to check?\n";
        $message .= "1) Farm Sensor Readings\n";
        $message .= "2) Irrigation Control";

        $continueSession = true;
        
        $session_fb = save_session($sessionID, $msisdn, $userData, $conn);
        if (!$session_fb) {
            $message = "An error occured! Please try again.";
            $continueSession = false;
        }

    } else {

        if ($stages[0] == "" || $stages[0] == null) {
            //  Userdata is from first level Menu
            if ($userData == "1") {

                // Business logic for first level response
        
                $readings = "SELECT * FROM sensor_data ORDER BY SensorDataId DESC LIMIT 1";
                $results = mysqli_query($conn, $readings);
                $results->data_seek(0);
                $res = $results->fetch_array(MYSQLI_ASSOC);
        
                $humidity = $res['humidity'];
                $temperature = $res['temperature'];
                $soilmoisture = $res['soilmoisture'];
                $waterlevel = $res['waterlevel'];
        
                $message = "Your Farm Readings are";
                $message .= "\nHumidity sensor: " . $humidity;
                $message .= "\nTemperature sensor: " . $temperature;
                $message .= "\nSoil moisture sensor: " . $soilmoisture;
                $message .= "\nWater level sensor: " . $waterlevel;
                $message .= "\n\nPress 0 to return to main menu\n";
        
                $continueSession = true;
        
                $session_fb = save_session($sessionID, $msisdn, $userData, $conn);
                if (!$session_fb) {
                    $message = "An error occured! Please try again.";
                    $continueSession = false;
                }
        
            } else if ($userData == "2") {
                $message = "Turn pump on/off \n";
                $message .= "1. Turn ON \n";
                $message .= "2. Turn OFF \n";
        
                $continueSession = true;
        
                $session_fb = save_session($sessionID, $msisdn, $userData, $conn);
                if (!$session_fb) {
                    $message = "An error occured! Please try again.";
                    $continueSession = false;
                }
        
            } else {
                $message = "Wrong input!";
            }

        } else if ($stages[0] == "2") {
            // Userdata is from option2 from initail menu 
            if ($userData == "1") {
                // function to start pump can be called here!
                $message = "Pump will be turn on(this might take a few seconds)";
        
                $session_fb = save_session($sessionID, $msisdn, $userData, $conn);
                if (!$session_fb) {
                    $message = "An error occured! Please try again.";
                    $continueSession = false;
                }
                
            } else if ($userData == "2") {
                // function to close pump can be called here!
                $message = "Pump will be turn off(this might take a few seconds)";
        
                $session_fb = save_session($sessionID, $msisdn, $userData, $conn);
                if (!$session_fb) {
                    $message = "An error occured! Please try again.";
                    $continueSession = false;
                }  
            } 
        } else {
            $message = "Wrong input!";
        }
    } 

}





http_response_code(200);
header('Content-Type: application/json');

echo json_encode([
    'sessionID' => $sessionID,
    'msisdn' => $msisdn,
    'userID' => $userID,
    'continueSession' => $continueSession,
    'message' => $message,
]);


/*
****************************************************************************************************
********************** Actions and Functions
****************************************************************************************************
*/

function use_dummy_ussd_data () {
    $chx = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
    $s = substr(str_repeat(str_shuffle($chx), 3), 0, 10);
    return [
        "sessionID" => $s,
        "userID" => "SAMPLE_USER_ID",
        "newSession" => true,
        "msisdn" => "233509999999",
        "userData" => "",
        "network" => "VODAFONE"
    ];
}

/**
 * This function gets previous session data for the msisdn and current ussd session
 * @param string $sessionID 
 * @param string $msisdn 
 * @param mysqli $conn
 * @return array|bool|null session data 
 */
function get_session ($sessionID, $msisdn, $conn):array|bool|null {
    $query = "SELECT * FROM `ussd_sessions` WHERE `session_id` = '$sessionID' AND `msisdn` = '$msisdn'";
    $session_data = mysqli_query($conn, $query);
    $session_data->data_seek(0);
    $session_data = $session_data->fetch_array(MYSQLI_ASSOC);
    return $session_data;
    // $session = $session_data['udata'];
    // $stages = explode('*', $session);
    // return $stages ? $stages : false;
}


/**
 * This function saves new user data to previous session data for the msisdn and current ussd session
 * @param string $sessionID 
 * @param string $msisdn 
 * @param string $user_data 
 * @param mysqli $conn
 * @return bool query result
 */
function save_session ($sessionID, $msisdn, $udata, $conn):bool {
    $session = get_session($sessionID, $msisdn, $conn);
    if (!$session) {
        // create new session
        $query = "INSERT INTO `ussd_sessions` (`session_id`, `msisdn`, `udata`)
                    VALUES('$sessionID', '$msisdn', '')";
        if (mysqli_query($conn, $query)) {
            return true;
        } else {
            return false;
        }
    } else {
        // update session u_data
        if ($udata == "0") {
            $data = "";
        } else {
            $old_data = $session['udata'];
            $data = $old_data . $udata . '*';
        }
        $query = "UPDATE `ussd_sessions` SET `udata` = '$data'
            WHERE `session_id` = '$sessionID' AND `msisdn` = '$msisdn'";
        if (mysqli_query($conn, $query)) {
            return true;
        } else {
            return false;
        }
    }
}


?>