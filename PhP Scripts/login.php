<?php
// Set array for success
$rows = array();
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$mysqli = mysqli_connect("localhost", "mattgoo1_mike_w", "youaremyfavorite", "mattgoo1_Truck_Tracker");
//If connection to database fails
if(mysqli_connect_errno()){
    $rows['Success'] = false;
    $rows['Message'] = "Unable to connect to database, please try again later";
    echo json_encode($rows);
	die();
}

$email = $request->username;
$pass = $request->password;
// $rows['InitialPass'] = $pass;
// $rows['email'] = $email;


$result = mysqli_query($mysqli, "SELECT Email, Password, Salt, Truck_ID FROM Users WHERE Email = '$email'");
$count = mysqli_num_rows($result);

if($count == 0) {
    $rows['Success'] = false;
    $rows['Message'] = "Username or password is incorrect.  Please contact support if the problem persists. Not Found.";
    echo json_encode($rows);
	die();
}
$r = $result->fetch_assoc();
//Get Salt
$salt = $r['Salt'];
$realPass = $r['Password'];
$truckID = $r['Truck_ID'];
//encrypt pass
$pass = crypt($pass, $salt);
if($pass != $realPass){
    $rows['Success'] = false;
    $rows['Message'] = "Username or password is incorrect.  Please contact support if the problem persists.";
	// $rows['Pass'] = $pass;
	// $rows['RealPass'] = $realPass;
    echo json_encode($rows);
	die();
}
$rows['Success'] = true;
$rows['Message'] = "Success! Successfully Logged in";
$rows['Truck_ID'] = $truckID;
echo json_encode($rows);
die();

?>