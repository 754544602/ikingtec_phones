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

$point_num=count($waypoint);

$feedback="true";

$mysql_str="SELECT * FROM waypoint_table WHERE code=".$waypoint[0]["id"];
$query = mysqli_query($conn,$mysql_str);
$query_num=mysqli_num_rows($query);
if($query_num >0){
    $mysql_str="DELETE FROM waypoint_table WHERE code=".$waypoint[0]["id"];
    mysqli_query($conn,$mysql_str);
}

for($i=0;$i<$point_num;$i++){
    $mysql_str="INSERT INTO waypoint_table(`name`, `code`, `number`, `longitude`, `latitude`, `altitude`, `speed`, `direction`, `time`, `yaw`, `pitch`, `zoom`, `action`, `type`) VALUES ('".$waypoint[$i]["name"]."','".$waypoint[$i]["num"]."','".$waypoint[$i]["index"]."','".$waypoint[$i]["longitude"]."','".$waypoint[$i]["latitude"]."','".$waypoint[$i]["altitude"]."','".$waypoint[$i]["speed"]."','".$waypoint[$i]["direction"]."','".$waypoint[$i]["time"]."','".$waypoint[$i]["yaw"]."','".$waypoint[$i]["pitch"]."','".$waypoint[$i]["zoom"]."','".$waypoint[$i]["action"]."','".$waypoint[$i]["type"]."')";
    $ret=mysqli_query($conn,$mysql_str);
    if(!$ret){
        $feedback = "false";
        echo $feedback;
    }
}

mysqli_close($conn);

echo $feedback;

?>
