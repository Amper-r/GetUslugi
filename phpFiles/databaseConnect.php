<?php
    function ConnectDb(){
        $server = "localhost";
        $username = "root";
        $password = "";
        $dbname = "shop";
        $charset = "utf8";
        
        $connect = new mysqli($server, $username, $password, $dbname);

        if($connect->connect_error){
            die("Ошибка соединения");
        }

        if(!$connect->set_charset($charset)){
            echo "Ошибка установки кодировки";
        }

        return $connect;
    }
?>