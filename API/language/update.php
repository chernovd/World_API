<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // include database and object files
    include_once '../config/database.php';
    include_once '../objects/language.php';
    
    //database Connection
    $database = new Database();
    $db = $database->getConnection();

    //requestMethod
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    
    //language object
    $language = new Language($db);

    switch($requestMethod) 
    {
        case 'POST':

            if( $_SERVER["CONTENT_TYPE"] == 'application/json')
            {
                 header("Content-Type: application/json");

                 //decode json input
                 $data = json_decode(file_get_contents("php://input"));   
            }
            else if($_SERVER["CONTENT_TYPE"] == 'application/xml')
            {
                header("Content-Type: application/xml");

                //decode xml input
                $xml = trim(file_get_contents("php://input"));
                $new = simplexml_load_string($xml);
                $json = json_encode($new); 
                $data = json_decode($json);

            }
            //language property to be edited
            $language->Language = $data->Language;
            
            //language property values
            $language->CountryCode = $data->CountryCode;
            $language->Language = $data->Language;
            $language->IsOfficial = $data->IsOfficial;
            $language->Percentage = $data->Percentage;
            $language->Modified = date('Y-m-d H:i:s');
            
            // update the language
            if($language->update())
            {
                // set response code
                http_response_code(200);
            
                //Updated
                echo json_encode(array("message" => "Language was updated."));
            }
            
            //unable to update the language
            else
            {
                // set response code
                http_response_code(503);
            
                //Failed
                echo json_encode(array("message" => "Unable to update language."));
            }
                break;
                default:
                header("HTTP/1.0 405 Method Not Allowed");
                break;
    }
?>