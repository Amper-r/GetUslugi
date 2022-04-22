<?php
    require_once __DIR__ ."./phpFiles/recordBdInJson.php";
    require_once __DIR__ ."./phpFiles/databaseConnect.php";
    //RecordJson();

    $connect = ConnectDb();

    $fun = $_GET["task"];
    try {
        $fun($connect, $_GET["uslugaId"]);
    } catch (\Throwable $th) {
        die("<span style=font-size:16px;><b>Error:</b> Function ".$fun." not found</span>");
    }


    function getUslugi($connect, $id){
        $query = $connect->query("SELECT * FROM products WHERE has_electonic_view=1");
        while($row = $query->fetch_assoc()){
            $data[] = $row;
        }

        if(!$data){
            die("<span style=font-size:16px;><b>Error:</b> Not found</span>");
        }

        $status = 200;
        header('HTTP/1.1 '.$status.' Unauthorized', true, $status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    function getUsluga($connect, $id){
        if($id > 0){
            $query = $connect->query("SELECT * FROM products WHERE id=".$id."");
            $data = $query->fetch_assoc();
            
            if(!$data){
                die("<span style=font-size:16px;><b>Error:</b> Record with <b>ID=".$id."</b> not found</span>");
            }

            $status = 200;
            header('HTTP/1.1 '.$status.' Unauthorized', true, $status);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        else{
            die("<span style=font-size:16px;><b>Error:</b> The ID parameter is undefined or less than or equal to 0</span>");
        }
    }
?>