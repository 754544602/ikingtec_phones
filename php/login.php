<?php

$username=$_POST["username"];
$password=$_POST["password"];
$userid="";

$success="false";
$device_info="";

$dbhost='localhost';
//$dbhost='47.106.185.144';
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

$query = mysqli_query($conn,"SELECT * FROM user_table WHERE username ='".$username."'");

if($query){
    $user_info=mysqli_fetch_array($query);
    if($user_info['password']==$password){
        $userid=$user_info['sn'];
        $success="true";
    }
}

mysqli_close($conn);

$login_info=array("username"=>$username,"id"=>$userid,"login_state"=>$success);
echo json_encode($login_info);
return;
?>
