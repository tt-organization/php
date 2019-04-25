<?php
$rows = array();
$fp = fopen('/log.txt', 'w');
$postdata = file_get_contents("php://input");
fwrite($fp, $postdata);
$request = json_decode($postdata);
$mysqli = mysqli_connect("localhost", "mattgoo1_mike_w", "youaremyfavorite", "mattgoo1_Truck_Tracker");
//If connection to database fails
if(mysqli_connect_errno()) {
    $rows['Success'] = false;
    $rows['Message'] = "Unable to connect to database, please try again later";
    echo json_encode($rows);
    die();
}

$truckName = mysqli_real_escape_string($mysqli, $request->truckName);
$openMonday = mysqli_real_escape_string($mysqli, $request->mondayOpen);
$closeMonday = mysqli_real_escape_string($mysqli, $request->mondayClose);
$openTuesday = mysqli_real_escape_string($mysqli, $request->tuesdayOpen);
$closeTuesday = mysqli_real_escape_string($mysqli, $request->tuesdayClose);
$openWednesday = mysqli_real_escape_string($mysqli, $request->wednesdayOpen);
$closeWednesday = mysqli_real_escape_string($mysqli, $request->wednesdayClose);
$openThursday = mysqli_real_escape_string($mysqli, $request->thursdayOpen);
$closeThrusday = mysqli_real_escape_string($mysqli, $request->thursdayClose);
$openFriday = mysqli_real_escape_string($mysqli, $request->fridayOpen);
$closeFriday = mysqli_real_escape_string($mysqli, $request->fridayClose);
$openSaturday = mysqli_real_escape_string($mysqli, $request->saturdayOpen);
$closeSaturday = mysqli_real_escape_string($mysqli, $request->saturdayClose);
$openSunday = mysqli_real_escape_string($mysqli, $request->sundayOpen);
$closeSunday = mysqli_real_escape_string($mysqli, $request->sundayClose);
$userID = mysqli_real_escape_string($mysqli, $request->userID);

$stmt = $mysqli->prepare("INSERT INTO `Trucks` (`Truck_Name`, `Open_Sunday`, `Close_Sunday`, `Open_Monday`, `Close_Monday`, `Open_Tuesday`, `Close_Tuesday`, `Open_Wednesday`, `Close_Wednesday`, `Open_Thursday`, `Close_Thursday`, `Open_Friday`, `Close_Friday`, `Open_Saturday`, `Close_Saturday`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

$stmt->bind_param("sssssssssssssss", $truckName, $openSunday, $closeSunday, $openMonday, $closeMonday, $openTuesday, $closeTuesday, $openWednesday, $closeWednesday, $openThursday, $closeThrusday, $openFriday, $closeFriday, $openSaturday, $closeSaturday);
if($stmt->execute()) {
	$stmt->close();
    $getId = $mysqli->query("SELECT Truck_ID FROM Trucks WHERE Truck_Name='$truckName' ORDER BY Truck_ID DESC LIMIT 1");
    $truckID = mysqli_fetch_assoc($getId)['Truck_ID'];
    $ustmt = $mysqli->prepare("UPDATE Users SET Truck_ID = $truckID WHERE Email = ?");
    $ustmt->bind_param("s", $userID);
    $ustmt->execute();
    $rows['Success'] = true;
    $rows['Message'] = "Success! Your truck was successfully added.";
    $rows['Truck_ID'] = $truckID;
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