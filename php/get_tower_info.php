<?php

$tower_name=$_POST["name"];
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
//$query_str="SELECT * FROM tower_table WHERE name ='".$tower_name."'";
$query_str="SELECT * FROM tower_table";
$query = mysqli_query($conn,$query_str);

$tower_info=array();

while($tower_query=mysqli_fetch_array($query)){
    $tower=array("index"=>$tower_query["_index"],"name"=>$tower_query["name"],"id"=>$tower_query["id"],"longitude"=>$tower_query["longitude"],"latitude"=>$tower_query["latitude"],"altitude"=>$tower_query["altitude"]);
    array_push($tower_info,$tower);
}

mysqli_close($conn);

echo json_encode($tower_info);

return;

?>
