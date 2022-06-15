<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// This class is responsible for setting up a mysqli connect to the cleardb
/*
        connection - this method returns a connection the connection

    */
class db_connect
{

    /// This function runs a query based on the database connection
    /*
            params - array containing:
                - sql 
                - bind_keys
                - bind_values
        */

    function query($params)
    {
        // do database stuff here

        // each array must have 2 parts
        // param keys (s,i,d, etc...) as $params['keys']
        // param values ("user_id")
        $conn = connection();
        if ($conn) {
            $stmt = mysqli_prepare($conn, $params["sql"]);
            if(sizeOf($params["bind_values"]) > 0){
                mysqli_stmt_bind_param($stmt, $params["bind_keys"], ...$params["bind_values"]);
            }

            $stmt->execute();
            $result = $stmt->get_result(); // get the mysqli result
            $data = $result->fetch_all(MYSQLI_ASSOC);
        } else {
            exit("bad conn");
        }
        return $data;
    }

    function update($params){
        $conn = connection();
        if ($conn) {
            $stmt = mysqli_prepare($conn, $params["sql"]);
            if(sizeOf($params["bind_values"]) > 0){
                mysqli_stmt_bind_param($stmt, $params["bind_keys"], ...$params["bind_values"]);
            }

           return $stmt->execute();
           
        } else {
            exit("bad conn");
        }
    }
}
/// This function establishes a connection to the database
function connection()
{
    $url = parse_url(getenv("CLEARDB_DATABASE_URL"));
    $server = $url["host"];
    $username = $url["user"];
    $password = $url["pass"];
    $db = substr($url["path"], 1);
    
    $connection = new mysqli($server, $username, $password, $db);

    return $connection;
}
?>