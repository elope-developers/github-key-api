<?php

require_once("keys.php");
require_once("technicians.php");
require_once("vehicles.php");
require_once("key_vehicles.php");
require_once("orders.php");

/* This file is responsible for fulfilling all orders 
    -- create an order (this will essentially)
    -- retreive all orders
    -- retreive a single order
    -- update an order
    -- delete an order
*/

$data = json_decode(file_get_contents('php://input')); // the data that we are working with via PUT | DELETE | POST (if any)
$request_method = $_SERVER["REQUEST_METHOD"]; // the request method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // the url
$uri = explode( '/', $uri ); // parsed url
$endpoint = $uri[3]; // the endpoint



switch($endpoint){
    case "orders": // get the orders
        if($request_method === "PUT" || $request_method === "POST"){
            // use $data passed from json input
            if(isset($data->order)){
                $order = new Order();
                echo json_encode(["status"=>200, "orders"=>$order->updateOrder($data->order)]); // will return true or false
            }else{
                echo json_encode(["status"=>500,"message" => "Order Data Not Set"]);
            }
        }  else if($request_method === "DELETE") {
            if(isset($data->id)){
                $order = new Order();
                echo json_encode(["status"=>200, "orders"=>$order->deleteOrder($data->id)]); // will return true or false
            }else{
                echo json_encode(["status"=>500,"message" => "Order ID Not Set"]);
            }
        } else if($request_method === "GET") {
            $order = new Order();
            if(isset($_GET["id"])){
                // GET INDVIDUAL ORDER
                echo json_encode(["status"=>200,"orders"=>$order->getOrder($_GET['id'])]);
            } else if(isset($_GET["order_by"])){
                // GET ALL ORDERS
                echo json_encode(["status"=>200,"orders"=>$order->getOrders($_GET["order_by"])]);
            }else{
                echo json_encode(["status"=>200,"orders"=>$order->getOrders()]);
            }
        } else{
            echo json_encode(["status"=>500,"message" => "Bad Request Method"]);
        }
        break;
    case "keys": // get keys
         // Allow GET Requests Only
         if($request_method === "GET"){
            $key = new Key();
            if(isset($_GET["id"])){
                // GET INDVIDUAL KEY
                echo json_encode(["status"=>200,"keys"=>$key->getKey($_GET['id'])]);
            }else{
                // GET ALL KEYS
                echo json_encode(["status"=>200,"keys"=>$key->getKeys()]);
            }
        } else {
            echo json_encode(["status"=>500,"message" => "Bad Request Method"]);
        }       
        break;
    case "technicians": // get technicians
        // Allow GET Requests Only
        if($request_method === "GET"){
            $technician = new Technician();
            if(isset($_GET["id"])){
                // GET INDVIDUAL TECH
                echo json_encode(["status"=>200,"technicians"=>$technician->getTechnician($_GET['id'])]);
            }else{
                // GET ALL TECHS
                echo json_encode(["status"=>200,"technicians"=>$technician->getTechnicians()]);
            }
        } else {
            echo json_encode(["status"=>500,"message" => "Bad Request Method"]);
        }
        
        break;
    case "vehicles": // get vehicles
        // Allow GET Requests Only
        if($request_method === "GET"){
            $vehicle = new Vehicle();
            if(isset($_GET["id"])){
                // GET INDVIDUAL VEHICLE
                echo json_encode(["status"=>200,"vehicles"=>$vehicle->getVehicle($_GET['id'])]);
            }else{
                // GET ALL VEHICLE
                echo json_encode(["status"=>200,"vehicles"=>$vehicle->getVehicles()]);
            }
        } else {
            echo json_encode(["status"=>500,"message" => "Bad Request Method"]);
        }
        break;

    case "key_vehicles": // get vehicles
            // Allow GET Requests Only
            if($request_method === "GET"){
                $kv = new KeyVehicle();
                if(isset($_GET["key_id"])){ // return a vehicle
                    // GET vehicle
                    echo json_encode(["status"=>200,"vehicles"=>$kv->getVehicleByKeyId($_GET['key_id'])]);
                }else if(isset($_GET["vehicle_id"])){ // return a key
                    // GET key
                    echo json_encode(["status"=>200,"keys"=>$kv->getKeyByVehicleId($_GET["vehicle_id"])]);
                }else if(isset($_GET["id"])){ // return a kev_vehicle pair
                    echo json_encode(["status"=>200,"kvs"=>$kv->getKeyVehicle($_GET['id'])]);
                }else{ // return all key_vehicle pairs
                    echo json_encode(["status"=>200,"kvs"=>$kv->getKeyVehicles()]);
                }
            } else {
                echo json_encode(["status"=>500,"message" => "Bad Request Method"]);
            }  
        break;

        default:
            echo json_encode(["status"=>500,"message" => "Bad endpoint: $endpoint"]);
            break;
}

/*

    // Create the order
    case "POST":
    case "PUT":
        break;
    
    case "GET":
        if(isset($_GET["id"])){
            // Get single order
            
        }else{
            // Get all orders
        }
    case "DELETE":
        if(isset($_GET["id"])){
            // delte the 
        }else{

        }
        break;
*/
?>