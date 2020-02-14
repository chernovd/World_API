<?php

    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // include database and object file
    include_once '../config/database.php';
    include_once '../objects/city.php';
    
    //database Connection
    $database = new Database();
    $db = $database->getConnection();

    //request Method
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    
    // prepare city object
    $city = new City($db);

    switch($requestMethod) {
        case 'POST':

            $city->ID = $_GET['ID'];

                // delete the city
                if($city->delete())
                {
                    // set response code
                    http_response_code(201);
            
                    //Success
                    $js_encode = json_encode(array('message'=>'City was deleted.'));
                    echo $js_encode;
                }
            
                //unable to create the city
                else
                {
                    // set response code
                    http_response_code(503);
            
                    //Failed
                    $js_encode = json_encode(array('message'=>'Unable to delete city.'));
                    echo $js_encode;
                } 
            break;
        default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
    }
    

?>