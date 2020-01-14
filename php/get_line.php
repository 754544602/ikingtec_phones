<?php
    $line_array = array();
    $file = scandir("./data");
    foreach ($file as $data){
        if($data != '.' && $data != '..'){
            $file_type=pathinfo($data,PATHINFO_EXTENSION);
            if($file_type=="json"){
                $line_array[] = $data;
            }
        }
    }
    echo json_encode($line_array);
?>
