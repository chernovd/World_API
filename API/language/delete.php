<?php

    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // include database and object file
    include_once '../config/database.php';
    include_once '../objects/language.php';
    
    //database Connection
    $database = new Database();
    $db = $database->getConnection();

    //requestMethod
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    
    //city object
    $language = new Language($db);

    switch($requestMethod) {
        case 'POST':
               
            //language name to be deleted
            $language->Language = $_GET['Language'];
         
            // delete the language
            if($language->delete())
            {
                // set response code
                http_response_code(200);
            
                //Deleted
                echo json_encode(array("message" => "Language was deleted."));
            }
            
            //unable to delete the language
            else
            {
                // set response code
                http_response_code(503);
            
                //Failed
                echo json_encode(array("message" => "Unable to delete language."));
            }
                    break;
                default:
                header("HTTP/1.0 405 Method Not Allowed");
                break;
            }

?>