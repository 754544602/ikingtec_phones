<?php
$dbhost='localhost';
$dbuser = 'root';
$dbpass = 'ikingtec909';

$waypoint = json_decode($_POST["data"],true);

$conn = mysqli_connect($dbhost, $dbuser, $dbpass);
if(! $conn ){
    echo "sql wrong";
    return;
}
$db_selected=mysqli_select_db($conn, 'ikingtec');
if (!$db_selected){
    echo "database wrong";
    return;
}
mysqli_query($conn,"set names utf8");

$feedback="true";

$mysql_str="SELECT * FROM waypoint_table WHERE (code=".$waypoint["id"]." AND number=".$waypoint["index"].")";
$query = mysqli_query($conn,$mysql_str);
$query_num=mysqli_num_rows($query);

if($query_num >0){
    $mysql_str="UPDATE waypoint_table SET longitude = '".$waypoint["longitude"]."', latitude = '".$waypoint["latitude"]."', altitude='".$waypoint["altitude"]."', speed = '".$waypoint["speed"]."', direction = '".$waypoint["direction"]."', time = '".$waypoint["time"]."',yaw = '".$waypoint["yaw"]."', pitch = '".$waypoint["pitch"]."', zoom = '".$waypoint["zoom"]."', action = '".$waypoint["action"]."', type = '".$waypoint["type"]."' WHERE code = '".$waypoint["id"]."' AND number = '".$waypoint["index"]."'";
}
else{
    $mysql_str="INSERT INTO waypoint_table(`name`, `code`, `number`, `longitude`, `latitude`, `altitude`, `speed`, `direction`, `time`, `yaw`, `pitch`, `zoom`, `action`, `type`) VALUES ('".$waypoint["name"]."','".$waypoint["id"]."','".$waypoint["index"]."','".$waypoint["longitude"]."','".$waypoint["latitude"]."','".$waypoint["altitude"]."','".$waypoint["speed"]."','".$waypoint["direction"]."','".$waypoint["time"]."','".$waypoint["yaw"]."','".$waypoint["pitch"]."','".$waypoint["zoom"]."','".$waypoint["action"]."','".$waypoint["type"]."')";
}

$ret=mysqli_query($conn,$mysql_str);
if(!$ret){
     $feedback = "false";
}

mysqli_close($conn);

echo $feedback;

?>
