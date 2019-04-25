<?php
    //we have to set the header of the response so that they know it is json
header('Content-type:application/json;charset=utf-8');
//Get info from request
$postdata = file_get_contents("php://input");

$request = json_decode($postdata);
//connect to the database
$mysqli = mysqli_connect("localhost", "mattgoo1_mike_w", "youaremyfavorite", "mattgoo1_Truck_Tracker");
//run your query here
$truck = mysqli_real_escape_string($mysqli, $request->Truck_ID); // Gets truck ID from form request
$truckResult = $mysqli->query("SELECT * FROM Trucks WHERE Truck_ID = $truck");
$rows = array();

if($truckResult){
	while ($r = mysqli_fetch_assoc($truckResult)) { 
		$rows['Truck'][] = $r;  // Is there a better way to gather the information from the query so it is more easily accessed???
	}
	$truckResult->free_result();
} else {
	$rows['Success'] = false;
	$rows['Message'] = "Truck not found";
}

$menuResult = $mysqli->query("SELECT * 
							  FROM Menu_Items m INNER JOIN Item_Categories ic ON m.Category_ID = ic.Category_ID 
							  WHERE m.Truck_ID = $truck
							  ORDER BY ic.Category_ID");
if($menuResult){
	while ($r = mysqli_fetch_assoc($menuResult)) {
		$rows['Menu'][] = $r;
	}
	$menuResult->free_result();
} else {
	$rows['Success'] = false;
	$rows['Message'] = "Menu not found";
}

echo json_encode(utf8ize($rows));
return json_encode(utf8ize($rows));


function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}
?>