<?php
require_once("db_connect.php");

class KeyVehicle {

    function getKeyVehicles(){

        $db_connect = new db_connect();
        
        $params = [
            "sql" => "select * from `vehicles`",
            "bind_keys" => "",
            "bind_values" => [],
        ];

        $response = $db_connect->query($params);

        return $response;
    }

    function getKeyVehicle($id){
        $db_connect = new db_connect();
        
        $params = [
            "sql" => "select * from `key_vehicles` where vehicle_id = ?",
            "bind_keys" => "i",
            "bind_values" => [$id],
        ];

        $response = $db_connect->query($params);
        return $response;
    }

    function getVehicleByKeyId($id){
        $db_connect = new db_connect();
        
        $params = [
            "sql" => "select `vehicles`.* from `vehicles` where `vehicles`.id in ( (select vehicle_id from key_vehicles where key_id = ?));",
            "bind_keys" => "i",
            "bind_values" => [$id],
        ];

        $response = $db_connect->query($params);
        return $response;
    }

    function getKeyByVehicleId($id){
        $db_connect = new db_connect();
        
        $params = [
            "sql" => "select `keys`.* from `keys` where `keys`.id = (select key_id from key_vehicles where vehicle_id = ?);",
            "bind_keys" => "i",
            "bind_values" => [$id],
        ];

        $response = $db_connect->query($params);
        return $response;
    }

}

?>