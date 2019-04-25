<?php
$rows = array();
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$mysqli = mysqli_connect("localhost", "mattgoo1_mike_w", "youaremyfavorite", "mattgoo1_Truck_Tracker");
//If connection to database fails
if(mysqli_connect_errno()) {
    $rows['Success'] = false;
    $rows['Message'] = "Unable to connect to database, please try again later";
    echo json_encode($rows);
    die();
}

$Truck_ID = mysqli_real_escape_string($mysqli, $request->Truck_ID);
$latitude = mysqli_real_escape_string($mysqli, $request->latitude);
$longitude = mysqli_real_escape_string($mysqli, $request->longitude);


$stmt = $mysqli->prepare("UPDATE `Trucks` SET `Latitude` = ?, `Longitude` = ? WHERE `Truck_ID`=?");
$stmt->bind_param("dds", $latitude, $longitude, $Truck_ID);
if($stmt->execute()) {
    $rows['Success'] = true;
    $rows['Message'] = "Success! Your location was successfully added.";
    echo json_encode($rows);
    die();
}
else {
    $rows['Success'] = false;
    $rows['Message'] = "Something went wrong, please try again.";
    echo json_encode($rows);
    die();
}

?>