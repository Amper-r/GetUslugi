<?php
    function UpdateXML($array, $xmlPath){
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        $date = date('Y-m-d H:i:s');
        $str = "<?xml version='1.0' encoding='utf-8'?><table border='1' style='border-collapse:collapse;' Data='".$date."'><tr><th>ID:</th><th>Name:</th><th>Organization:</th><th>Payment:</th><th>State duty payment:</th><th>Has electonic view:</th></tr>";
        foreach ($array as $value) {
            $payment = $value['payment'] ? "true" : "false";
            $state_duty_payment = $value['state_duty_payment'] ? "true" : "false";

            $str = $str."<tr><th>".$value['id']."</th><th>".$value['name']."</th><th>".$value['organization']."</th><th>".$payment."</th><th>".$state_duty_payment."</th><th>".$value['has_electonic_view']."</th></tr>";
        }
        $str = $str."</table>";
        $dom->loadXML($str);
        $xml = $dom->saveXML();
        echo $xml;
        $dom->save($xmlPath);
    }
?>