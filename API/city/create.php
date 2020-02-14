<?php

    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // get database connection
    include_once '../config/database.php';
    
    // instantiate city object
    include_once '../objects/city.php';

    //requestMethod
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    
    //database Connection
    $database = new Database();
    $db = $database->getConnection();
    
    $city = new City($db);
    
    switch($requestMethod) {
        case 'POST':

            if( $_SERVER["CONTENT_TYPE"] == 'application/json')
            {
                 header("Content-Type: application/json");

                 // decode json input
                 $cityData = json_decode(file_get_contents("php://input"));   
            }
            else if($_SERVER["CONTENT_TYPE"] == 'application/xml')
            {
                header("Content-Type: application/xml");

                // decode xml input
                $xml = trim(file_get_contents("php://input"));
                $new = simplexml_load_string($xml);
                print_r($new);
                $json = json_encode($new); 
                $cityData = json_decode($json);

            }
            if(
                !empty($cityData->Name) &&
                !empty($cityData->CountryCode) &&
                !empty($cityData->District) &&
                !empty($cityData->Population)   
              )
            {
                // set city property values
                $city->Name = $cityData->Name;
                $city->CountryCode = $cityData->CountryCode;
                $city->District = $cityData->District;
                $city->Population = $cityData->Population;
                $city->Created = date('Y-m-d H:i:s');
                
                // create the city
                if($city->create())
                {
                    // set response code 
                    http_response_code(201);
            
                    // Created
                    $js_encode = json_encode(array('message'=>'City was created'));
                    echo $js_encode;
                }
            
                //unable to create the city
                else
                {
                    // set response code
                    http_response_code(503);
            
                    // Creation failed
                    $js_encode = json_encode(array('message'=>'City creation failed.'));
                    echo $js_encode;
                }
            }
            
            //data is incomplete
            else
            {
                // set response code
                http_response_code(400);
            
                //data is incomplete
                $js_encode = json_encode(array('message'=>'Data is incomplete.'));
                echo $js_encode;
            }  
            break;
        default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
    }
    
?>