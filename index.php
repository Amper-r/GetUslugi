<?php
    require_once __DIR__ ."/phpFiles/recordBdInJson.php";
    require_once __DIR__ ."/phpFiles/databaseConnect.php";
    require_once __DIR__ ."/phpFiles/xml.php";
    require_once __DIR__ ."/phpFiles/errors.php";
    //RecordJson();

    //FTP XML UPLOAD
    $ftp_server = '37.140.192.158';
    $ftp_user_name = 'u1656895_practic';
    $ftp_user_pass = '';
    $ftp_data = array("ftp_server" => $ftp_server, "ftp_user_name" => $ftp_user_name, "ftp_user_pass" => $ftp_user_pass);

    $connect = ConnectDb();

    $fun = $_GET["task"];
    $ids = $_GET["uslugaId"];
    $has_electronic_view  = $_GET["has_electronic_view "];
    try {
        if(isset($fun)){
            $fun($connect, $ids, $has_electronic_view , $ftp_data);
        }
    } catch (\Throwable $th) {
        AddError("Function ".$fun." not found", $th);
    }


    function getUslugi($connect, $id, $has_electronic_view , $ftp_data){
        if(!isset($has_electronic_view )){
            $has_electronic_view  = 1;
        }
        $has_electronic_view _str =  $has_electronic_view  < 0 ? "" : "WHERE has_electronic_view =".$has_electronic_view ;

        $query = $connect->query("SELECT * FROM products ".$has_electronic_view _str."");
        while($row = $query->fetch_assoc()){
            $data[] = $row;
        }

        if(!$data){
            AddError("No data found with the specified parameters");
        }

        UpdateXML($data, "./result.xml", $ftp_data["ftp_server"], $ftp_data["ftp_user_name"], $ftp_data["ftp_user_pass"]);
        $status = 200;
        header('HTTP/1.1 '.$status.' Unauthorized', true, $status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    function getUsluga($connect, $ids, $has_electronic_view , $ftp_data){
        if(!isset($ids)){
            AddError("Missing uslugaId argument");
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
                    AddError("Record with ID=".$id."");
                }
            }
            else{
                AddError("The ID parameter is undefined or less than or equal to 0");
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