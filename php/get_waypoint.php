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
mysqli_query($conn,"set names utf8");

$waypoint_info=array();

$query = mysqli_query($conn,"SELECT * FROM waypoint_table WHERE number = '1'");
while($waypoint_query=mysqli_fetch_array($query)){
    $line=array("id"=>'0'.$waypoint_query["type"].$waypoint_query["code"],"num"=>$waypoint_query["code"],"name"=>$waypoint_query["name"]); 
    array_push($waypoint_info,$line);
}
mysqli_close($conn);

echo json_encode($waypoint_info);

?>
