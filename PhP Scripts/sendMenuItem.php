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

$stmt = $mysqli->prepare("SELECT `Category_ID` FROM `Item_Categories` WHERE `Category_Desc` = ? AND `Truck_ID` = ?");


$stmt->bind_param("si", $category, $Truck_ID);
if($stmt->execute()){
	$stmt->bind_result($catID);
	$stmt->fetch();
	$temp = $stmt->num_rows;
	$stmt->close();
	if($temp != 0){
		$stmt = $mysqli->prepare("INSERT INTO `Menu_Items` (`Truck_ID`, `Price`, `Description`, `Category_ID`) VALUES ( ?, ?, ?, ?)");
		$stmt->bind_param("ssss", $Truck_ID, $price, $description, $catID);
		if($stmt->execute()) {
			$rows['Success'] = true;
			$rows['Message'] = "Success! Your item was successfully added.";
			$rows['Item']['Category_ID'] = $catID;
			echo json_encode($rows);
			die();
		}else {
			$rows['Success'] = false;
			$rows['Message'] = "Something went wrong, please try again.";
			echo json_encode($rows);
			die();
		}
	} else {
		$stmt = $mysqli->prepare("INSERT INTO Item_Categories (Truck_ID, Category_Desc) VALUES ('$Truck_ID','$category')");
        $stmt->bind_param("ss", $Truck_ID, $category);
        if($stmt->execute()){
			$stmt->close();
            $stmt = $mysqli->prepare("SELECT `Category_ID` FROM `Item_Categories` WHERE `Category_Desc` = ? AND `Truck_ID` = ?");
            $stmt->bind_param("ss", $category, $Truck_ID);
            if($stmt->execute()){
				$stmt->bind_result($catID);
                $stmt->fetch();
				$stmt->close();
                $stmt = $mysqli->prepare("INSERT INTO `Menu_Items` (`Truck_ID`, `Price`, `Description`, `Category_ID`) VALUES ( ?, ?, ?, ?)");
				$stmt->bind_param("ssss", $Truck_ID, $price, $description, $catID);
				if($stmt->execute()) {
					$rows['Success'] = true;
					$rows['Message'] = "Success! Your item was successfully added.";
					$rows['Item']['Category_ID'] = $catID;
					echo json_encode($rows);
					die();
				}else {
					$rows['Success'] = false;
					$rows['Message'] = "Something went wrong, please try again.";
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

	/*
	$rows['Success'] = $category;
	$rows['Message'] = $Truck_ID;
	$rows['Rows'] = $temp;
	$rows['catID'] = $catID;
	echo json_encode($rows);
	die();
*/
}


else {
    $rows['Success'] = false;
    $rows['Message'] = "Something went wrong, please try again.";
    echo json_encode($rows);
    die();
}
?>