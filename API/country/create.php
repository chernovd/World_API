<?php

    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // get database connection
    include_once '../config/database.php';
    
    // instantiate country object
    include_once '../objects/country.php';
    
    //database Connection
    $database = new Database();
    $db = $database->getConnection();

    //requestMethod
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    
    $country = new Country($db);

    switch($requestMethod) {
        case 'POST':

            if( $_SERVER["CONTENT_TYPE"] == 'application/json')
            {
                 header("Content-Type: application/json");

                 //decode json imput
                 $data = json_decode(file_get_contents("php://input"));   
            }
            else if($_SERVER["CONTENT_TYPE"] == 'application/xml')
            {
                header("Content-Type: application/xml");

                //decode xml imput
                $xml = trim(file_get_contents("php://input"));
                $new = simplexml_load_string($xml);
                $json = json_encode($new); 
                $data = json_decode($json);

            }
            //make sure data is not empty
            if
            (
                !empty($data->Code) &&
                !empty($data->Name) &&
                !empty($data->Continent) &&
                !empty($data->Region) &&
                !empty($data->SurfaceArea) &&
                !empty($data->IndepYear) &&
                !empty($data->Population) &&
                !empty($data->LifeExpectancy) &&
                !empty($data->GNP) &&
                !empty($data->GNPOld) &&
                !empty($data->LocalName) &&
                !empty($data->GovernmentForm) &&
                !empty($data->HeadOfState) &&
                !empty($data->Capital) &&
                !empty($data->Code2)   
            )
            {
            
                //country property values
                $country->Code = $data->Code;
                $country->Name = $data->Name;
                $country->Continent = $data->Continent;
                $country->Region = $data->Region;
                $country->SurfaceArea = $data->SurfaceArea;
                $country->IndepYear = $data->IndepYear;
                $country->Population = $data->Population;
                $country->LifeExpectancy = $data->LifeExpectancy;
                $country->GNP = $data->GNP;
                $country->GNPOld = $data->GNPOld;
                $country->LocalName = $data->LocalName;
                $country->GovernmentForm = $data->GovernmentForm;
                $country->HeadOfState = $data->HeadOfState;
                $country->Capital = $data->Capital;
                $country->Code2 = $data->Code2;
                $country->Created = date('Y-m-d H:i:s');
                
                // create the country
                if($country->create())
                {
                    // set response code
                    http_response_code(201);
            
                    //Created
                    $js_encode = json_encode(array('message'=>'Country was created'));
                    echo $js_encode;
                }
            
                //unable to create the country
                else
                {
                    // set response code
                    http_response_code(503);
            
                    //Failed
                    $js_encode = json_encode(array('message'=>'Country creation failed.'));
                    echo $js_encode;
                }
            }
            
            //data is incomplete
            else
            {
                // set response code
                http_response_code(400);
            
                //fill in data
                $js_encode = json_encode(array('message'=>'Data is incomplete.'));
                echo $js_encode;
            }  
            break;
        default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
    }
    
?>