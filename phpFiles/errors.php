<?php
    require_once __DIR__ ."/databaseConnect.php";

    function AddError($error, $errorDesc = null){
        $connect = ConnectDb();

        $date = date('Y-m-d H:i:s');
        $query =  $connect->query("INSERT INTO `errors` (`id`, `date_time`, `error`, `error_desc`) VALUES (NULL, '".$date."', '".$error."', '".$errorDesc."');");
        echo $errorDesc."<br>";
        die("<span style=font-size:16px;><b>Error:</b> ".$error."</span>");
    }
?>