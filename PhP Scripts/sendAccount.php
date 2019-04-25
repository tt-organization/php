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

$fname = mysqli_real_escape_string($mysqli, $request->firstName);
$lname = mysqli_real_escape_string($mysqli, $request->lastName);
$email = mysqli_real_escape_string($mysqli, $request->email);
$pass = mysqli_real_escape_string($mysqli, $request->password);
$tel = mysqli_real_escape_string($mysqli, $request->phone);

$result = mysqli_query($mysqli, "SELECT * FROM Users WHERE Email = '$email'");
$count = mysqli_num_rows($result);

if($count > 0) {
    $rows['Email'] = $email;
    $rows['Success'] = false;
    $rows['Message'] = "Email is already in use, please enter a different email";
    echo json_encode($rows);
	die();
}

//Create Salt
$salt = random_bytes(8);

//encrypt pass
$pass = crypt($pass, $salt);

$stmt = $mysqli->prepare("INSERT INTO Users (First_Name, Last_Name, Email, Password, Phone, Salt) VALUES (?,?,?,?,?,?)");
//$stmt = $mysqli->prepare("INSERT INTO Users (First_Name, Last_Name, Email, [Password], Phone, Salt) VALUES ('$fname','$lname', '$email','$pass','$tel','$salt')");
$stmt->bind_param("ssssss", $fname, $lname, $email, $pass, $tel, $salt);
//Try executing insert statement
if($stmt->execute()) {
    $getId = $mysqli->query("SELECT Email FROM Users WHERE Email ='$email' ORDER BY Email DESC LIMIT 1");
    $rows['Success'] = true;
    $rows['Message'] = "Success! Your account was created.";
    $rows['User_ID'] = mysqli_fetch_assoc($getId)['Email'];
}
else {
    $rows['Success'] = false;
    $rows['Message'] = "Something went wrong, please try again.";
}

echo json_encode($rows);

?>