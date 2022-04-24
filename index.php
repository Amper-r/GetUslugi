<?php
    require_once __DIR__ ."./phpFiles/recordBdInJson.php";
    require_once __DIR__ ."./phpFiles/databaseConnect.php";
    require_once __DIR__ ."./phpFiles/xml.php";
    //RecordJson();

    //FTP XML UPLOAD
    $ftp_server = '37.140.192.158';
    $ftp_user_name = 'u1656895_practic';
    $ftp_user_pass = 'iJ2rC8cN9x';
    $ftp_data = array("ftp_server" => $ftp_server, "ftp_user_name" => $ftp_user_name, "ftp_user_pass" => $ftp_user_pass);

    $connect = ConnectDb();

    $fun = $_GET["task"];
    $ids = $_GET["uslugaId"];
    $has_electonic_view = $_GET["has_electonic_view"];
    try {
        if(isset($fun)){
            $fun($connect, $ids, $has_electonic_view, $ftp_data);
        }
    } catch (\Throwable $th) {
        echo $th."<br>";
        die("<span style=font-size:16px;><b>Error:</b> Function ".$fun." not found</span>");
    }


    function getUslugi($connect, $id, $has_electonic_view, $ftp_data){
        if(!isset($has_electonic_view)){
            $has_electonic_view = 1;
        }
        $has_electonic_view_str =  $has_electonic_view < 0 ? "" : "WHERE has_electonic_view=".$has_electonic_view;

        $query = $connect->query("SELECT * FROM products ".$has_electonic_view_str."");
        while($row = $query->fetch_assoc()){
            $data[] = $row;
        }

        if(!$data){
            die("<span style=font-size:16px;><b>Error:</b> No data found with the specified parameters</span>");
        }

        UpdateXML($data, "./result.xml", $ftp_data["ftp_server"], $ftp_data["ftp_user_name"], $ftp_data["ftp_user_pass"]);
        $status = 200;
        header('HTTP/1.1 '.$status.' Unauthorized', true, $status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    function getUsluga($connect, $ids, $has_electonic_view, $ftp_data){
        if(!isset($ids)){
            die("<span style=font-size:16px;><b>Error:</b> Missing uslugaId argument</span>");
        }
        if(!is_array($ids)){
            $ids = array($ids);
        }

        $data = array();
        foreach ($ids as $id) {
            if($id > 0){
                $query = $connect->query("SELECT * FROM products WHERE id=".$id."");
                $item = $query->fetch_assoc();
                if($item){
                    $data[] = $item;
                }
                else{
                    die("<span style=font-size:16px;><b>Error:</b> Record with <b>ID=".$id."</b> not found</span>");
                }
            }
            else{
                die("<span style=font-size:16px;><b>Error:</b> The ID parameter is undefined or less than or equal to 0</span>");
            }
        }
        if($data){
            UpdateXML($data, "./result.xml", $ftp_data["ftp_server"], $ftp_data["ftp_user_name"], $ftp_data["ftp_user_pass"]);
            $status = 200;
            header('HTTP/1.1 '.$status.' Unauthorized', true, $status);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
    }
?>