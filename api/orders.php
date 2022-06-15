<?php
require_once("db_connect.php");

class Order {

    function getOrders($order_by = "name"){ // order by name (key_name) by default
        $db_connect = new db_connect();
        
        $params = [
            "sql" => "SELECT `key_vehicles`.`key_id`,`key_vehicles`.`vehicle_id`,`keys`.name,`keys`.price,`vehicles`.make,`vehicles`.model,`vehicles`.vin,CONCAT(`technicians`.`lastname`, \",\", `technicians`.`firstname`) as technician, `orders`.* from `key_vehicles` join vehicles on vehicles.id = key_vehicles.`vehicle_id` join `keys` on `keys`.id = key_vehicles.`key_id` join `orders` on orders.vin = vehicles.vin join `technicians` on technicians.id = orders.technician_id order by `$order_by`",
            "bind_keys" => "",
            "bind_values" => [],
        ];

        $response = $db_connect->query($params);
        //$response["sql"] = $params["sql"];//.$order_by;

        return $response;
    }

    function getOrder($id){
        $db_connect = new db_connect();
        
        $params = [
            "sql" => "SELECT `key_vehicles`.`key_id`,`key_vehicles`.`vehicle_id`,`keys`.name,`keys`.price,`vehicles`.make,`vehicles`.model,`vehicles`.vin,CONCAT(`technicians`.`lastname`, \",\", `technicians`.`firstname`) as technician, `orders`.* from `key_vehicles` join vehicles on vehicles.id = key_vehicles.`vehicle_id` join `keys` on `keys`.id = key_vehicles.`key_id` join `orders` on orders.vin = vehicles.vin join `technicians` on technicians.id = orders.technician_id where orders.id = ?",
            "bind_keys" => "i",
            "bind_values" => [$id],
        ];

        $response = $db_connect->query($params);
        return $response;
    }

    function updateOrder($data){
        $db_connect = new db_connect();
        
        $data = json_decode($data);
        $id = (isset($data->id)) ? $data->id : NULL;
        $vin = (isset($data->vin)) ? $data->vin : NULL;
        $technician_id = (isset($data->technician_id)) ? $data->technician_id : NULL;


        $params = [
            "sql" => "insert into `orders` (id, technician_id, vin) values (?, ?, ?) ON DUPLICATE KEY UPDATE technician_id = ?, vin = ?",
            "bind_keys" => "iisis",
            "bind_values" => [$id, $technician_id, $vin, $technician_id, $vin],
        ];

        $response = $db_connect->update($params);
        return $response;
    }

    function deleteOrder($id){
        $db_connect = new db_connect();
        
        $params = [
            "sql" => "delete from `orders` where id = ?",
            "bind_keys" => "i",
            "bind_values" => [$id],
        ];

        $response = $db_connect->update($params);
        return $response;
    }
}

?>