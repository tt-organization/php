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

$truck = mysqli_real_escape_string($mysqli, $request->Truck_ID);

$values = "";
	
$sunOpen = mysqli_real_escape_string($mysqli, $request->SundayOpen);
if ( $sunOpen != "" ) {
		$values .= "Open_Sunday = $sunOpen ";
}
$sunClose = mysqli_real_escape_string($mysqli, $request->SundayClose);
if ( $sunClose != "" ) {
		if ($values != "") { $values .= ", "; }
		$values .= "Close_Sunday = $sunClose ";
}	
$monOpen = mysqli_real_escape_string($mysqli, $request->MondayOpen);
if ( $monOpen != "" ) {
		if ($values != "") { $values .= ", "; }
		$values .= "Open_Monday = $monOpen ";
}
$monClose = mysqli_real_escape_string($mysqli, $request->MondayClose);
if ( $monClose != "" ) {
		if ($values != "") { $values .= ", "; }
		$values .= "Close_Monday = $monClose ";
}	
$tueOpen = mysqli_real_escape_string($mysqli, $request->TuesdayOpen);
if ( $tueOpen != "" ) {
		if ($values != "") { $values .= ", "; }
		$values .= "Open_Tuesday = $tueOpen ";
}
$tueClose = mysqli_real_escape_string($mysqli, $request->TuesdayClose);
if ( $tueClose != "" ) {
		if ($values != "") { $values .= ", "; }
		$values .= "Close_Tuesday = $tueClose ";
}	
$wedOpen = mysqli_real_escape_string($mysqli, $request->WednesdayOpen);
if ( $wedOpen != "" ) {
		if ($values != "") { $values .= ", "; }
		$values .= "Open_Wednesday = $wedOpen ";
}
$wedClose = mysqli_real_escape_string($mysqli, $request->WednesdayClose);
if ( $wedClose != "" ) {
		if ($values != "") { $values .= ", "; }
		$values .= "Close_Wednesday = $wedClose ";
}	
$thuOpen = mysqli_real_escape_string($mysqli, $request->ThursdayOpen);
if ( $thuOpen != "" ) {
		if ($values != "") { $values .= ", "; }
		$values .= "Open_Thursday = $thuOpen ";
}
$thuClose = mysqli_real_escape_string($mysqli, $request->ThursdayClose);
if ( $thuClose != "" ) {
		if ($values != "") { $values .= ", "; }
		$values .= "Close_Thursday = $thuClose ";
}	
$friOpen = mysqli_real_escape_string($mysqli, $request->FridayOpen);
if ( $friOpen != "" ) {
		if ($values != "") { $values .= ", "; }
		$values .= "Open_Friday = $friOpen ";
}
$friClose = mysqli_real_escape_string($mysqli, $request->FridayClose);
if ( $friClose != "" ) {
		if ($values != "") { $values .= ", "; }
		$values .= "Close_Friday = $friClose ";
}	
$satOpen = mysqli_real_escape_string($mysqli, $request->SaturdayOpen);
if ( $satOpen != "" ) {
		if ($values != "") { $values .= ", "; }
		$values .= "Open_Saturday = $satOpen ";
}
$satClose = mysqli_real_escape_string($mysqli, $request->SaturdayClose);
if ( $satClose != "" ) {
		if ($values != "") { $values .= ", "; }
		$values .= "Close_Saturday = $satClose ";
}

$result = $mysqli->query("UPDATE Trucks SET $values WHERE truck_id = $truck");	
echo json_encode(['result' => $result]);

