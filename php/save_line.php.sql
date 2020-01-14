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




for($i=0;$i<$point_num;$i++){
    //$query = mysqli_query($conn,"SELECT * FROM waypoint_table WHERE code = '".$waypoint[$i]["id"]."' AND number = '".$waypoint[$i]["index"]."'");
    //if($query){
        $mysql_str="UPDATE waypoint_table SET longitude = '".$waypoint[$i]["longitude"]."', latitude = '".$waypoint[$i]["latitude"]."', altitude='".$waypoint[$i]["altitude"]."', speed = '".$waypoint[$i]["speed"]."', direction = '".$waypoint[$i]["direction"]."', time = '".$waypoint[$i]["time"]."',yaw = '".$waypoint[$i]["yaw"]."', pitch = '".$waypoint[$i]["pitch"]."', zoom = '".$waypoint[$i]["zoom"]."', action = '".$waypoint[$i]["action"]."', type = '".$waypoint[$i]["type"]."' WHERE code = '".$waypoint[$i]["id"]."' AND number = '".$waypoint[$i]["index"]."'";
    //}
    //else{
    //    $mysql_str="INSERT INTO waypoint_table(`name`, `code`, `number`, `longitude`, `latitude`, `altitude`, `speed`, `direction`, `time`, `yaw`, `pitch`, `zoom`, `action`, `type`) VALUES ('".$waypoint[$i]["name"]."','".$waypoint[$i]["id"]."','".$waypoint[$i]["index"]."','".$waypoint[$i]["longitude"]."','".$waypoint[$i]["latitude"]."','".$waypoint[$i]["altitude"]."','".$waypoint[$i]["speed"]."','".$waypoint[$i]["direction"]."','".$waypoint[$i]["time"]."','".$waypoint[$i]["yaw"]."','".$waypoint[$i]["pitch"]."','".$waypoint[$i]["zoom"]."','".$waypoint[$i]["action"]."','".$waypoint[$i]["type"]."')";
    //    echo '2';
    //}
    echo $mysql_str;
    $ret=mysqli_query($conn,$mysql_str);
    if(!$ret){
        $feedback = "false";
        echo $feedback;
    }
}

mysqli_close($conn);

echo $feedback;

?>
