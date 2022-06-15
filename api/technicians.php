<?php
require_once("db_connect.php");

class Technician {

    function getTechnicians(){
        $db_connect = new db_connect();
        
        $params = [
            "sql" => "select * from `technicians`",
            "bind_keys" => "",
            "bind_values" => [],
        ];

        $response = $db_connect->query($params);

        return $response;
    }

    function getTechnician($id){
        $db_connect = new db_connect();
        
        $params = [
            "sql" => "select * from `technicians` where id = ? ",
            "bind_keys" => "i",
            "bind_values" => [$id],
        ];

        $response = $db_connect->query($params);
        return $response;
    }
}

?>