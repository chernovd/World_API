<?php

    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // include database and object file
    include_once '../config/database.php';
    include_once '../objects/country.php';
    
    //database Connection
    $database = new Database();
    $db = $database->getConnection();

    //POST Method
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    
    //country object
    $country = new Country($db);

    switch($requestMethod) {
        case 'POST':
                
            //city Code to be deleted
            $country->Code = $_GET['Code'];
                
                // delete the country
                if($country->delete())
                {
                    // set response code
                    http_response_code(200);
                
                    //Deleted
                    echo json_encode(array("message" => "Country was deleted."));
                }
                
                //unable to delete the country
                else
                {
                    // set response code
                    http_response_code(503);
                
                    //Failed
                    echo json_encode(array("message" => "Unable to delete country."));
                }
                        break;
                    default:
                    header("HTTP/1.0 405 Method Not Allowed");
                    break;
                }

?>