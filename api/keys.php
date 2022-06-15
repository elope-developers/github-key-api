<?php
require_once("db_connect.php");

class Key {

    function getKeys(){
        $db_connect = new db_connect();
        
        $params = [
            "sql" => "select * from `keys`",
            "bind_keys" => "",
            "bind_values" => [],
        ];

        $response = $db_connect->query($params);

        return $response;
    }

    function getKey($id){
        $db_connect = new db_connect();
        
        $params = [
            "sql" => "select * from `keys` where id = ? ",
            "bind_keys" => "i",
            "bind_values" => [$id],
        ];

        $response = $db_connect->query($params);
        return $response;
    }

}

?>