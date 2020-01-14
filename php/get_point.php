<?php
$dbhost='localhost';
$dbuser = 'root';
$dbpass = 'ikingtec909';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass);
if(! $conn ){
    return;
}
$db_selected=mysqli_select_db($conn, 'ikingtec');
if (!$db_selected){
    return;
}

$line_info=array();
mysqli_query($conn,"set names utf8");
$query = mysqli_query($conn,"SELECT * FROM waypoint_table WHERE code = '".$_POST["id"]."'");
while($point=mysqli_fetch_array($query)){
    $line=array("id"=>'0'.$point["type"].$point["code"],"name"=>$point["name"],"index"=>$point["number"],"longitude"=>$point["longitude"],"latitude"=>$point["latitude"],"altitude"=>$point["altitude"],"speed"=>$point["speed"],"direction"=>$point["direction"],"time"=>$point["time"],"yaw"=>$point["yaw"],"pitch"=>$point["pitch"],"zoom"=>$point["zoom"],"action"=>$point["action"],"type"=>$point["type"],"num"=>$point["code"]); 
    array_push($line_info,$line);
}
mysqli_close($conn);

echo json_encode($line_info);

?>
