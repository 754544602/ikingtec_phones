<?php
header("content-type:text/html;charset=utf-8");

$username="ikingtec";
$dbhost='localhost';
$dbuser = 'root';
$dbpass = 'ikingtec909';

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
$query = mysqli_query($conn,"SELECT * FROM device_table WHERE user ='".$username."'");

$device_info=array();


while($device_query=mysqli_fetch_array($query)){
    $device=array("type"=>$device_query["type"],"longitude"=>$device_query["longitude"],"latitude"=>$device_query["latitude"],"altitude"=>$device_query["altitude"],"location"=>$device_query["location"],"id"=>$device_query["sn"],"sn"=>$device_query["code"]);    
    array_push($device_info,$device);
}

mysqli_close($conn);
echo json_encode($device_info);
return;
?>
