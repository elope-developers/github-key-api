<?php
require_once("db_connect.php");

class Vehicle {

    function getVehicles(){
        $db_connect = new db_connect();
        
        $params = [
            "sql" => "select * from `vehicles`",
            "bind_keys" => "",
            "bind_values" => [],
        ];

        $response = $db_connect->query($params);

        return $response;
    }

    function getVehicle($id){
        $db_connect = new db_connect();
        
        $params = [
            "sql" => "select * from `vehicles` where id = ? ",
            "bind_keys" => "i",
            "bind_values" => [$id],
        ];

        $response = $db_connect->query($params);
        return $response;
    }
}

?>