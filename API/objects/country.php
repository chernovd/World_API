<?php

    class Country extends Database
    {

        // read countries
        function read()
        {

        // select query
        $query = "SELECT * FROM country ORDER BY Name";

        $result = $this->getConnection()->query($query); 
            $numRows = $result->num_rows;
            if($numRows > 0)
            {
                $data = array();
                while ($row = $result->fetch_assoc())
                {
                    $country_item["Country"] = array
                    (
                        "Code" =>  $row['Code'],
                        "Name" => $row['Name'],
                        "Continent" => $row['Continent'],
                        "Region" => $row['Region'],
                        "SurfaceArea" => (int)$row['SurfaceArea'],
                        "IndepYear" => (int)$row['IndepYear'],
                        "Population" => (int)$row['Population'],
                        "LifeExpectancy" => (int)$row['LifeExpectancy'],
                        "GNP" => (int)$row['GNP'],
                        "GNPOld" => (int)$row['GNPOld'],
                        "LocalName" => $row['LocalName'],
                        "GovernmentForm" => $row['GovernmentForm'],
                        "HeadOfState" => $row['HeadOfState'],
                        "Capital" => (int)$row['Capital'],
                        "Code2" => $row['Code2']      
                    );

                    array_push($data, $country_item);
                }

                return $data;
            }
            else
            {
                // set response code 
                http_response_code(404);

                // no countries found
                echo json_encode(array("message" => "No records found."));
                
                die();
            }

        }
        // read one country
        function readOne()
        {

             // query to read one country
            $query = "SELECT Code, Name, Continent, Region, SurfaceArea, IndepYear, Population, LifeExpectancy, GNP, GNPOld, LocalName, GovernmentForm, HeadOfState, Capital, Code2 FROM country WHERE Name = ?";

            $result = $this->getConnection()->prepare($query); 
            $result->bind_param("s", $this->Name);
            $result->bind_result($Code, $Name, $Continent, $Region, $SurfaceArea, $IndepYear, $Population, $LifeExpectancy, $GNP, $GNPOld, $LocalName, $GovernmentForm, $HeadOfState, $Capital, $Code2);
            $result->execute();

            $data = array();

            while($result->fetch())
            {
                $country_item["Country"] = array
            (
                "Code" =>  $Code,
                "Name" => $Name,
                "Continent" => $Continent,
                "Region" => $Region,
                "SurfaceArea" => $SurfaceArea,
                "IndepYear" => $IndepYear,
                "Population" => $Population,
                "LifeExpectancy" => (int)$LifeExpectancy,
                "GNP" => $GNP,
                "GNPOld" => $GNPOld,
                "LocalName" => $LocalName,
                "GovernmentForm" => $GovernmentForm,
                "HeadOfState" => $HeadOfState,
                "Capital" => $Capital,
                "Code2" => $Code2      
            );

            array_push($data, $country_item);
            }
            
            return $data;
             

        } 
        

        // create country
        function create()
        {
        
            // query to insert country
            $query = "INSERT INTO
                    country (Code, Name, Continent, Region, SurfaceArea, IndepYear, Population, LifeExpectancy, GNP, GNPOld, LocalName, GovernmenForm, HeadOfState, Capital, Code2, Created)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
            // prepare query
            $result = $this->getConnection()->prepare($query); 
        
            // sanitize
            $this->Code=htmlspecialchars(strip_tags($this->Code));
            $this->Name=htmlspecialchars(strip_tags($this->Name)); 
            $this->Continent=htmlspecialchars(strip_tags($this->Continent));
            $this->Region=htmlspecialchars(strip_tags($this->Region));
            $this->SurfaceArea=htmlspecialchars(strip_tags($this->SurfaceArea));
            $this->IndepYear=htmlspecialchars(strip_tags($this->IndepYear));
            $this->Population=htmlspecialchars(strip_tags($this->Population));
            $this->LifeExpectancy=htmlspecialchars(strip_tags($this->LifeExpectancy));
            $this->GNP=htmlspecialchars(strip_tags($this->GNP));
            $this->GNPOld=htmlspecialchars(strip_tags($this->GNPOld));
            $this->LocalName=htmlspecialchars(strip_tags($this->LocalName));
            $this->GovernmenForm=htmlspecialchars(strip_tags($this->GovernmenForm));
            $this->HeadOfState=htmlspecialchars(strip_tags($this->HeadOfState));
            $this->Capital=htmlspecialchars(strip_tags($this->Capital));
            $this->Code2=htmlspecialchars(strip_tags($this->Code2));
            $this->Created=htmlspecialchars(strip_tags($this->Created));
        
            if($result)
            { 
                // bind values
                $result->bind_param("sssssssisssssss", $this->Code, $this->Name, $this->Continent, $this->SurfaceArea, $this->IndepYear, $this->Population, $this->LifeExpectancy, $this->GNP, $this->GNPOld, $this->LocalName, $this->GovernmenForm, $this->HeadOfState, $this->Capital, $this->Code2, $this->Created);
                if($result->execute())
                {
                    return true;
                }
                else
                {
                    print_r($result->error);
                }
            }
            else
            {
                print_r($result->error);
            }
             
        }

        // update the country
        function update()
        {
        
            // update query
            $query = "UPDATE
                     country
                     SET
                        Code = ?,
                        Name = ?,
                        Continent = ?,
                        SurfaceArea = ?,
                        IndepYear = ?,
                        Population = ?,
                        LifeExpectancy = ?,
                        GNP = ?,
                        GNPOld = ?,
                        LocalName = ?,
                        GovernmentForm = ?,
                        HeadOfState = ?,
                        Capital = ?,
                        Code2 = ?,
                        Modified = ?
                     WHERE
                        Code = ?";
        
            // prepare query
            $result = $this->getConnection()->prepare($query); 
        
            // sanitize
            $this->Code=htmlspecialchars(strip_tags($this->Code));
            $this->Name=htmlspecialchars(strip_tags($this->Name)); 
            $this->Continent=htmlspecialchars(strip_tags($this->Continent));
            $this->Region=htmlspecialchars(strip_tags($this->Region));
            $this->SurfaceArea=htmlspecialchars(strip_tags($this->SurfaceArea));
            $this->IndepYear=htmlspecialchars(strip_tags($this->IndepYear));
            $this->Population=htmlspecialchars(strip_tags($this->Population));
            $this->LifeExpectancy=htmlspecialchars(strip_tags($this->LifeExpectancy));
            $this->GNP=htmlspecialchars(strip_tags($this->GNP));
            $this->GNPOld=htmlspecialchars(strip_tags($this->GNPOld));
            $this->LocalName=htmlspecialchars(strip_tags($this->LocalName));
            $this->GovernmentForm=htmlspecialchars(strip_tags($this->GovernmentForm));
            $this->HeadOfState=htmlspecialchars(strip_tags($this->HeadOfState));
            $this->Capital=htmlspecialchars(strip_tags($this->Capital));
            $this->Code2=htmlspecialchars(strip_tags($this->Code2));
            $this->Modified=htmlspecialchars(strip_tags($this->Modified));
        
            if($result)
            { 
                // bind values
                $result->bind_param("sssssssisssssss", $this->Code, $this->Name, $this->Continent, $this->SurfaceArea, $this->IndepYear, $this->Population, $this->LifeExpectancy, $this->GNP, $this->GNPOld, $this->LocalName, $this->GovernmenForm, $this->HeadOfState, $this->Capital, $this->Code2, $this->Created);
                if($result->execute())
                {
                    return true;
                }
                else
                {
                    print_r($result->error);
                }
            }
            else
            {
                print_r($result->error);
            }
           
        }
        // delete the country
        function delete()
        {
        
            // delete query
            $query = "DELETE FROM country WHERE Code = ?";
        
            // prepare query
            $result = $this->getConnection()->prepare($query); 
        
            // sanitize
            $this->Code=htmlspecialchars(strip_tags($this->Code));
        
            // execute query
            if($result)
            { 
                // bind values
                $result->bind_param("s", $this->Code);
                if($result->execute())
                {
                    return true;
                }
                else
                {
                    print_r($result->error);
                }
            }
            else
            {
                print_r($result->error);
            }
        }
    }

?>