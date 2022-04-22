<?php
    require_once __DIR__ ."./phpFiles/recordBdInJson.php";
    require_once __DIR__ ."./phpFiles/databaseConnect.php";
    //RecordJson();

    $connect = ConnectDb();

    $query = $connect->query("SELECT * FROM `products`");

    while($row = $query->fetch_assoc()){
        $data[] = $row;
    }


    $status = 200;
    header('HTTP/1.1 '.$status.' Unauthorized', true, $status);
    echo json_encode($data);
?>