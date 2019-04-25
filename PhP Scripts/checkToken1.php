<?php
//we have to set the header of the response so that they know it is json
header('Content-type:application/json;charset=utf-8');
//Get info from request
$postdata = file_get_contents("php://input");

$request = json_decode($postdata);
//connect to the database
$mysqli = mysqli_connect("localhost", "mattgoo1_mike_w", "youaremyfavorite", "mattgoo1_Truck_Tracker");

if(mysqli_connect_errno()){
	return json_encode(['message' => "Error connecting to database"]);
}
$truck = mysqli_real_escape_string($mysqli, $request->Truck_ID); // Gets truck ID from form request
$result = $mysqli->query("SELECT `access_token` FROM `Users` WHERE `Truck_ID`='$truck'");

if( $result ){
	$message = "Success";
	$token = mysqli_fetch_assoc($result);
} else {
	$message = "Error retrieving access token";
}

echo json_encode(['access_token' => $token, 'message' => $message]);
?>