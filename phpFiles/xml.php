<?php
    require_once __DIR__ ."/databaseConnect.php";

    function UpdateXML($array, $xmlPath, $ftp_server, $ftp_user_name, $ftp_user_pass){
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        $date = date('Y-m-d H:i:s');
        $str = "<?xml version='1.0' encoding='utf-8'?><table border='1' style='border-collapse:collapse; text-transform: capitalize;' DateTime='".$date."'><tr><th>ID:</th><th>Name:</th><th>Organization:</th><th>Payment:</th><th>State duty payment:</th><th>Has electonic view:</th></tr>";
        foreach ($array as $value) {
            $payment = $value['payment'] ? "true" : "false";
            $state_duty_payment = $value['state_duty_payment'] ? "true" : "false";

            $str = $str."<tr><th>".$value['id']."</th><th>".$value['name']."</th><th>".$value['organization']."</th><th>".$payment."</th><th>".$state_duty_payment."</th><th>".$value['has_electonic_view']."</th></tr>";
        }
        $str = $str."</table>";
        $dom->loadXML($str);
        $xml = $dom->saveXML();
        $dom->save($xmlPath);
        
        $ch = curl_init();
        $localfile = $xmlPath;
        $remotefile = 'result.xml';
        $fp = fopen($localfile, 'r');
        curl_setopt($ch, CURLOPT_URL, 'ftp://'.$ftp_user_name.':'.$ftp_user_pass.'@'.$ftp_server.'/files/v1/'.$remotefile);
        curl_setopt($ch, CURLOPT_UPLOAD, 1);
        curl_setopt($ch, CURLOPT_INFILE, $fp);
        curl_setopt($ch, CURLOPT_INFILESIZE, filesize($localfile));
        curl_exec ($ch);
        $error_no = curl_errno($ch);
        curl_close ($ch);
        if ($error_no != 0) {
            AddError("File uploaded error");
        }
        else{
            $connect = ConnectDb();

            $date = date('Y-m-d H:i:s');
            $query =  $connect->query("INSERT INTO `errors` (`id`, `status`, `date_time`, `error`, `error_desc`) VALUES (NULL, 'success', '".$date."', NULL, NULL);");
        }
    }
?>