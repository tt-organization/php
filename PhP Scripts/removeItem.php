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

$id = mysqli_real_escape_string($mysqli, $request->id);
$name = mysqli_real_escape_string($mysqli, $request->name);

$stmt = $mysqli->prepare("DELETE FROM Menu_Items WHERE Item_ID = ?");
$stmt->bind_param("s", $id);

if($stmt->execute()){
    $rows["Success"] = true;
    $rows["Message"] = "Item has been removed.";
    echo json_encode($rows);
    die();
}
else {
    $rows["Success"] = false;
    $rows["Message"] = "There was an error in removing the item. Please try again later or contact us for help.";
    echo json_encode($rows);
    die();
}
