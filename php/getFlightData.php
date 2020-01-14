<?php
$dbhost='localhost';
$dbuser = 'root';
$dbpass = 'ikingtec909';
$waypoint = json_decode($_POST["data"],true);

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

$query = mysqli_query($conn,"SELECT * FROM flight_data_table WHERE start_time in (select max(start_time) FROM flight_data_table GROUP BY drone_id = '".$waypoint[0]["id"]."' )");
while($point=mysqli_fetch_array($query)){
    $line=array("drone_id"=>$point["drone_id"],"startTime"=>$point["start_time"],"end_time"=>$point["endTime"],"time"=>$point["time"],"batteryData"=>$point["battery_data"],"distanceData"=>$point["distance_data"],"motor1"=>$point["motor_1"],"motor2"=>$point["motor_2"],"motor3"=>$point["motor_3"],"motor4"=>$point["motor_4"],"motor5"=>$point["motor_5"],"motor6"=>$point["motor_6"],"motor7"=>$point["motor_7"],"motor8"=>$point["motor_8"]); 
    array_push($line_info,$line);
}
mysqli_close($conn);

echo json_encode($line_info);

?>
