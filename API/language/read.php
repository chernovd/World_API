<?php

    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    // include database and object files
    include_once '../config/database.php';
    include_once '../objects/language.php';

    //database Connection
    $database = new Database();
    $db = $database->getConnection();
    
    //requestMethod
    $requestMethod = $_SERVER["REQUEST_METHOD"];

    // initialize object
    $language = new Language($db);

    switch($requestMethod) {
        case 'GET':
            if(isset($_GET['Language'])) 
            {
                $language->Language = $_GET['Language'];

                if(!empty($_GET['Language']))  
                { 
                    if( $_SERVER["CONTENT_TYPE"] == 'application/json')
                {
                    $data["Languages"] = $language->readOne();
                                    
                    // set response code
                    http_response_code(200);
        
                    //make it json format
                    echo json_encode($data);
        
                    header("Content-Type: application/json; charset=UTF-8");  
                }
                else if($_SERVER["CONTENT_TYPE"] == 'application/xml')
                {
                    $data = $language->readOne();
    
                    if(count($data))
                    {
                        // set response code 
                        http_response_code(200);
        
                        //make it xml format
                        createXMLfile($data);
        
                        header("Content-Type: application/xml; charset=UTF-8");
                    }
                }
                }
                else
                {
                    // set response code
                    http_response_code(404);

                    //language does not exist
                    echo json_encode(array("message" => "Language does not exist."));
                }
            } 
            else 
            {            
                if( $_SERVER["CONTENT_TYPE"] == 'application/json')
            {
                $data["Languages"] = $language->read();
                                
                // set response code
                http_response_code(200);
    
                //make it json format
                echo json_encode($data);
    
                header("Content-Type: application/json; charset=UTF-8");  
            }
            else if($_SERVER["CONTENT_TYPE"] == 'application/xml')
            {
                $data = $language->read();
                
                header("Content-Type: application/xml; charset=UTF-8");

                if(count($data))
                {
                    // set response code 
                    http_response_code(200);
    
                    //make it xml format
                    createXMLfile($data);
    
                    
                }
            }   
                
            }
            break;
        default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
    }
    function createXMLfile($language_arr)
    {

        $domtree = new DOMDocument('1.0', 'UTF-8');
        $root      = $domtree->createElement('Languages');
        $root->setAttributeNS
        (
            // namespace
            'http://www.w3.org/2001/XMLSchema-instance',
            // attribute name including namespace prefix
            'xsi:noNamespaceSchemaLocation',
            // attribute value
            'http://localhost:8080/api/language/languages_schema.xsd'
        );
        $root = $domtree->appendChild($root);

        for($i=0; $i<count($language_arr); $i++)
        {
            $CountryCode        =  $language_arr[$i]['Language']['CountryCode'];
            $Language        =  $language_arr[$i]['Language']['Language']; 
            $IsOfficial        =  $language_arr[$i]['Language']['IsOfficial']; 
            $Percentage        =  $language_arr[$i]['Language']['Percentage']; 
            
            //create the root element of the xml tree */
            $track = $domtree->createElement("Language");
            //append it to the document created */
            $track = $root->appendChild($track);

            $track->appendChild($domtree->createElement('CountryCode', $CountryCode));
            $track->appendChild($domtree->createElement('Language', $Language));
            $track->appendChild($domtree->createElement('IsOfficial', $IsOfficial));
            $track->appendChild($domtree->createElement('Percentage', $Percentage));
    
        }

        echo $domtree->saveXML();
    }
?>