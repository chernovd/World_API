<?php

    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    // include database and object files
    include_once '../config/database.php';
    include_once '../objects/country.php';


    //database Connection
    $database = new Database();
    $db = $database->getConnection();

    //requestMethod
    $requestMethod = $_SERVER["REQUEST_METHOD"];

    //country object
    $country = new Country($db);

    switch($requestMethod) 
    {
    case 'GET':
        if(isset($_GET['Name'])) 
        {
            $country->Name = $_GET['Name'];
            
            if(!empty($_GET['Name']))  
            { 
                if( $_SERVER["CONTENT_TYPE"] == 'application/json')
                {
                    $data["Countries"] = $country->readOne();
                                    
                    // set response code
                    http_response_code(200);
        
                    //make it json format
                    echo json_encode($data);
        
                    header("Content-Type: application/json; charset=UTF-8");  
                }
                else if($_SERVER["CONTENT_TYPE"] == 'application/xml')
                {
                    $data = $country->readOne();
    
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

                    //country does not exist
                    echo json_encode(array("message" => "Country does not exist."));
            }
        
        } 
        else 
        { 
            if( $_SERVER["CONTENT_TYPE"] == 'application/json')
            {
                $data["Countries"] = $country->read();
                                
                // set response code
                http_response_code(200);
    
                //make it json format
                echo json_encode($data);
    
                header("Content-Type: application/json; charset=UTF-8");  
            }
            else if($_SERVER["CONTENT_TYPE"] == 'application/xml')
            {
                $data = $country->read();
                
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
    }
    function createXMLfile($country_arr)
    {

        $domtree = new DOMDocument('1.0', 'UTF-8');
        $root      = $domtree->createElement('Countries');
        $root->setAttributeNS(
            // namespace
            'http://www.w3.org/2001/XMLSchema-instance',
            // attribute name including namespace prefix
            'xsi:noNamespaceSchemaLocation',
            // attribute value
            'http://localhost:8080/api/country/countries_schema.xsd'
        );
        $root = $domtree->appendChild($root);

        for($i=0; $i<count($country_arr); $i++)
        {
            $Code        =  $country_arr[$i]['Country']['Code'];
            $Name        =  $country_arr[$i]['Country']['Name']; 
            $Continent        =  $country_arr[$i]['Country']['Continent']; 
            $Region        =  $country_arr[$i]['Country']['Region']; 
            $SurfaceArea        =  $country_arr[$i]['Country']['SurfaceArea']; 
            $IndepYear        =  $country_arr[$i]['Country']['IndepYear']; 
            $Name        =  $country_arr[$i]['Country']['Name']; 
            $Continent        =  $country_arr[$i]['Country']['Continent']; 
            $Region        =  $country_arr[$i]['Country']['Region']; 
            $SurfaceArea        =  $country_arr[$i]['Country']['SurfaceArea']; 
            $IndepYear        =  $country_arr[$i]['Country']['IndepYear']; 
            $Population        =  $country_arr[$i]['Country']['Population']; 
            $LifeExpectancy        =  $country_arr[$i]['Country']['LifeExpectancy']; 
            $GNP        =  $country_arr[$i]['Country']['GNP']; 
            $GNPOld        =  $country_arr[$i]['Country']['GNPOld']; 
            $LocalName        =  $country_arr[$i]['Country']['LocalName']; 
            $GovernmentForm        =  $country_arr[$i]['Country']['GovernmentForm']; 
            $HeadOfState        =  $country_arr[$i]['Country']['HeadOfState']; 
            $Capital        =  $country_arr[$i]['Country']['Capital']; 
            $Code2        =  $country_arr[$i]['Country']['Code2']; 
        
        //create the root element of the xml tree */
        $track = $domtree->createElement("Country");
        //append it to the document created */
        $track = $root->appendChild($track);
        

        $track->appendChild($domtree->createElement('Code', $Code));
        $track->appendChild($domtree->createElement('Name', $Name));
        $track->appendChild($domtree->createElement('Continent', $Continent));
        $track->appendChild($domtree->createElement('Region', $Region));
        $track->appendChild($domtree->createElement('SurfaceArea', $SurfaceArea));
        $track->appendChild($domtree->createElement('IndepYear', $IndepYear));
        $track->appendChild($domtree->createElement('Population', $Population));
        $track->appendChild($domtree->createElement('LifeExpectancy', $LifeExpectancy));
        $track->appendChild($domtree->createElement('GNP', $GNP));
        $track->appendChild($domtree->createElement('GNPOld', $GNPOld));
        $track->appendChild($domtree->createElement('LocalName', $LocalName));
        $track->appendChild($domtree->createElement('GovernmentForm', $GovernmentForm));
        $track->appendChild($domtree->createElement('HeadOfState', $HeadOfState));
        $track->appendChild($domtree->createElement('Capital', $Capital));
        $track->appendChild($domtree->createElement('Code2', $Code2));

        }
        echo $domtree->saveXML();
    }
?>