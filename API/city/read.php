<?php

        // required headers
        header("Access-Control-Allow-Origin: *");
        
        // include database and object files
        include_once '../config/database.php';
        include_once '../objects/city.php';
        
        // database Connection
        $database = new Database();
        $db = $database->getConnection();
        
        //requestMethod
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        // initialize object
        $city = new City($db);

        //$file_type = $_GET['type'];

        switch($requestMethod) 
        {
            case 'GET':
                if(isset($_GET['Name'])) 
                {
                    $city->Name = $_GET['Name'];
            
                    if(!empty($_GET['Name']))  
                    {     
                        if( $_SERVER["CONTENT_TYPE"] == 'application/json')
                        {
                            $data["Cities"] = $city->readOne();
                                
                            // set response code
                            http_response_code(200);
    
                            //make it json format
                            echo json_encode($data);
    
                            header("Content-Type: application/json; charset=UTF-8");  
                        }
                        else if($_SERVER["CONTENT_TYPE"] == 'application/xml')
                        {
                            $data = $city->readOne();

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

                        //city does not exist
                        echo json_encode(array("message" => "City does not exist."));
                    }
                } 
                else 
                {
                    if( $_SERVER["CONTENT_TYPE"] == 'application/json')
                    {
                        header("Content-Type: application/json; charset=UTF-8");

                        $data["Cities"] = $city->read();
                        
                        // set response code
                        http_response_code(200);

                        //make it json format
                        echo json_encode($data); 
                    }
                    else if($_SERVER["CONTENT_TYPE"] == 'application/xml')
                    {
                        header("Content-Type: application/xml; charset=UTF-8");
                                
                        $data = $city->read();

                        if(count($data))
                        {
                            // set response code
                            http_response_code(200);

                            //make it xml format
                            createXMLfile($data);
                        }
                    }
               
                }                
        }
        
        //create xml file
        function createXMLfile($city_arr)
        {

                $domtree = new DOMDocument('1.0', 'UTF-8');
                $root      = $domtree->createElement('Cities');
                //$root->setAttribute('xsi:noNamespaceSchemaLocation', 'cities_schema.xsd');
                $root->setAttributeNS(
                    // namespace
                    'http://www.w3.org/2001/XMLSchema-instance',
                    // attribute name including namespace prefix
                    'xsi:noNamespaceSchemaLocation',
                    // attribute value
                    'http://localhost:8080/api/city/cities_schema.xsd'
                );
                $root = $domtree->appendChild($root);

                for($i=0; $i<count($city_arr); $i++)
                {
                    $id = $city_arr[$i]["City"]["ID"];
                    $Language = implode(",", $city_arr[$i]["City"]["Language"]);
                    $Name = $city_arr[$i]["City"]["Name"]; 
                    $CountryCode = $city_arr[$i]["City"]["CountryCode"]; 
                    $District = $city_arr[$i]["City"]["District"]; 
                    $Population = $city_arr[$i]["City"]["Population"]; 
                    $Country = $city_arr[$i]["City"]["Country"]; 
                    $Percentage = implode(",", $city_arr[$i]["City"]["Lang_Percentage"]); 
                    
                    //create the root element of the xml tree */
                    $track = $domtree->createElement("City");
                    //append it to the document created */
                    $track = $root->appendChild($track);
                    $track->appendChild($domtree->createElement('ID', $id));
                    $track->appendChild($domtree->createElement('Name', $Name));
                    $track->appendChild($domtree->createElement('CountryCode', $CountryCode));
                    $track->appendChild($domtree->createElement('Country', $Country));
                    $track->appendChild($domtree->createElement('Population', $Population));
                    $track->appendChild($domtree->createElement('District', htmlentities($District)));
                    $track->appendChild($domtree->createElement('Languages', $Language));
                    $track->appendChild($domtree->createElement('Lang_Percentage', $Percentage));
                }
                
                echo $domtree->saveXML();
        }
?>