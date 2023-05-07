<?php

// Get the JSON contents
$json = file_get_contents('php://input');

// decode the json data
$data = json_decode($json, true);

$sessionID = $data['sessionID'];
$userID = $data['userID'];
$newSession = $data['newSession'];
$msisdn = $data['msisdn'];
$userData = $data['userData'];
$network = $data['network'];

if($newSession && $userData == "*928*303#"){
  // This is a new session
  $message = "Welcome to Arkesel Bank\n";
  $message .= "1. Check Balance\n";
  $message .= "2. Buy Bundle";
  $continueSession = true;
} else if($newSession == false && $userData == "1"){
  // Implement Check balance functionality for 1
  $message = "Your account balance is GHc 2.00";
  $continueSession = false;
} else if ($newSession == false && $userData == "2") {
  // Implement Check balance functionality for 2
  $message = "No packages available for subscription";
  $continueSession = false;
}else {
  //Invalid Option Selected
  $message = "Invalid input";
  $continueSession = false;
}

$response = [
  "sessionID" => $sessionID,
  "userID" => $userID,
  "msisdn" => $msisdn,
  "message" => $message,
  "continueSession" => $continueSession
];

http_response_code(200);

// treat this as json
header('Content-Type: application/json');

echo json_encode($response);
