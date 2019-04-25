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

$Truck_ID = mysqli_real_escape_string($mysqli, $request->truck_ID);
$category = mysqli_real_escape_string($mysqli, $request->category);
$description = mysqli_real_escape_string($mysqli, $request->description);
$price = mysqli_real_escape_string($mysqli, $request->price);

$check = $mysqli->prepare("SELECT `Category_ID` FROM `Item_Categories` WHERE `Category_Desc` = ? AND `Truck_ID` = ?");


$check->bind_param("ss", $category, $Truck_ID);
if($check->execute()){
    if($check->num_rows != 0){
        $check->bind_result($catID);
        $check->fetch();
    }
    else {
        $add = $mysqli->prepare("INSERT INTO Item_Categories (Truck_ID, Category_Desc) VALUES ('$Truck_ID','$category')");
        //$add->bind_param("ss", $Truck_ID, $category);
        if($add->execute()){
            $get = $mysqli->prepare("SELECT `Category_ID` FROM `Item_Categories` WHERE `Category_Desc` = ? AND `Truck_ID` = ?");
            $get->bind_param("ss", $category, $Truck_ID);
            if($get->execute()){
                if($get->num_rows !=0){
                    $get->bind_result($catID);
                    $get->fetch();
                }
                else {
                    $rows['Success'] = false;
                    $rows['Message'] = "Something went wrong. Please try again.";
                    echo json_encode($rows);
                    die();
                }
            }
            else {
                $rows['Success'] = false;
                $rows['Message'] = "Something went wrong. Please try again.";
                echo json_encode($rows);
                die();
            }
        }
        else {
            $rows['Success'] = false;
            $rows['Message'] = "Something went wrong. Please try again.";
            echo json_encode($rows);
            die();
        }
    }
}
else{
    $rows['Success'] = false;
    $rows['Message'] = "Something went wrong. Please try again.";
    echo json_encode($rows);
    die();
}

$stmt = $mysqli->prepare("INSERT INTO `Menu_Items` (`Truck_ID`, `Price`, `Description`, `Category_ID`) VALUES ( ?, ?, ?, ?)");

$stmt->bind_param("ssss", $Truck_ID, $price, $description, $catID);
if($stmt->execute()) {
    $rows['Success'] = true;
    $rows['Message'] = "Success! Your item was successfully added.";
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