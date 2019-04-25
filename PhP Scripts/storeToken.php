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
$token = mysqli_real_escape_string($mysqli, $request->access_token); // Gets truck ID from form request
$result = $mysqli->query("UPDATE Users SET access_token = '$token' WHERE truck_id = $truck");

if( $result ){
	$message = "Success";
} else {
	$message = "Error storing access token";
}

echo json_encode(['message' => $message]);
return json_encode(['message' => $message]);
	
