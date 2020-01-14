<?php
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

$feedback="true";

$mysql_str="SELECT * FROM waypoint_table WHERE code=00000002";
$query = mysqli_query($conn,$mysql_str);
$query_num=mysqli_num_rows($query);
if($query_num >0){
    $mysql_str="DELETE FROM waypoint_table WHERE code=00000002";
    mysqli_query($conn,$mysql_str);
}

mysqli_close($conn);

?>
