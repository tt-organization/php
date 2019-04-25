<?php
//we have to set the header of the response so that they know it is json
header('Content-type:application/json;charset=utf-8');
//connect to the database
$mysqli = mysqli_connect("localhost", "mattgoo1_mike_w", "youaremyfavorite", "mattgoo1_Truck_Tracker");
//run your query here
$result = $mysqli->query("SELECT t.Truck_ID, Truck_Name, Location, Open_Sunday, Close_Sunday, Open_Monday, Close_Monday, Open_Tuesday, Close_Tuesday, Open_Wednesday, Close_Wednesday, Open_Thursday, Close_Thursday, Open_Friday, Close_Friday, Open_Saturday, Close_Saturday, Latitude, Longitude, Option_Desc FROM Trucks t LEFT JOIN (Truck_Options truckO INNER JOIN Options o on o.Option_ID = truckO.Option_ID) on t.Truck_ID = truckO.Truck_ID");
//this is going to be the array we return.  It's going to be a 2d array which we turn into JSON
$rows = array();
//iterate through th result, each row becomes an array in our 2D array
while($r = mysqli_fetch_assoc($result)) {
    $rows['Trucks'][] = $r;
}
//free the result
$result->free_result();
//alot going on here, we need to convert the strings to UTF-8 so i stole the function below from StackOverFlow.  The json_encode turns the array into JSON.
//we can echo it to view the results in a browser, or just return the results to Angular for now I will echo then return
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