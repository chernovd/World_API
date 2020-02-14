<?php

    class Language extends Database
    {

        // read languages
        function read()
        {

        // select languages
        $query = "SELECT * FROM countrylanguage WHERE IsOfficial = 'T' ORDER BY Language";

        // prepare query statement
        $result = $this->getConnection()->query($query); 
    
            $numRows = $result->num_rows;
            if($numRows > 0)
            {
                $data = array();
                while ($row = $result->fetch_assoc())
                {
                    $language_item["Language"] = array
                    (
                        "CountryCode" => $row['CountryCode'],
                        "Language" => $row['Language'],
                        "IsOfficial" => $row['IsOfficial'],
                        "Percentage" => (int)$row['Percentage']   
                    );

                    array_push($data, $language_item);
                }

                return $data;
            }
            else
            {
                // set response code
                http_response_code(404);

                // no languages found
                echo json_encode(array("message" => "No records found."));
                
                die();
            }

        }
        // read one language
        function readOne()
        {

             // query to read language
            $query = "SELECT CountryCode, Language, IsOfficial, Percentage FROM countrylanguage  WHERE Language = ? AND IsOfficial = 'T'";
            
             // prepare query statement
             $result = $this->getConnection()->prepare($query); 
             $result->bind_param("s", $this->Language);
             $result->bind_result($CountryCode, $Language, $IsOfficial, $Percentage);
             $result->execute();
 
             $data = array();
 
             while($result->fetch())
             {
                $language_item["Language"] = array
                (
                    "CountryCode" => $CountryCode,
                    "Language" => $Language,
                    "IsOfficial" => $IsOfficial,
                    "Percentage" => (int)$Percentage   
                );

                array_push($data, $language_item);
             }
             
             return $data;

        } 
        
        // create language
        function create()
        {
        
            // query to insert language
            $query = "INSERT INTO
                    countrylanguage (CountryCode, Language, IsOfficial, Percentage, Created)
                    VALUES (?, ?, ?, ?, ?)";
        
            // prepare query statement
            $result = $this->getConnection()->prepare($query); 
        
            // sanitize
            $this->CountryCode=htmlspecialchars(strip_tags($this->CountryCode));
            $this->Language=htmlspecialchars(strip_tags($this->Language)); 
            $this->IsOfficial=htmlspecialchars(strip_tags($this->IsOfficial));
            $this->Percentage=htmlspecialchars(strip_tags($this->Percentage));
            $this->Created=htmlspecialchars(strip_tags($this->Created));
        
            if($result)
            { 
                // bind values
                $result->bind_param("sssss", $this->CountryCode, $this->Language, $this->IsOfficial, $this->Percentage, $this->Created);
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

        // update the language
        function update()
        {
        
            // update query
            $query = "UPDATE
                     countrylanguage
                     SET
                        CountryCode = ?,
                        Language = ?,
                        IsOfficial = ?,
                        Percentage = ?,
                        Modified = ?
                     WHERE
                        Language = ?";
        
            // prepare query statement
            $result = $this->getConnection()->prepare($query); 
        
            // sanitize
            $this->CountryCode=htmlspecialchars(strip_tags($this->CountryCode));
            $this->Language=htmlspecialchars(strip_tags($this->Language)); 
            $this->IsOfficial=htmlspecialchars(strip_tags($this->IsOfficial));
            $this->Percentage=htmlspecialchars(strip_tags($this->Percentage));
            $this->Modified=htmlspecialchars(strip_tags($this->Modified));
        
            if($result)
            { 
                // bind values
                $result->bind_param("sssss", $this->CountryCode, $this->Language, $this->IsOfficial, $this->Percentage, $this->Created);
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
        // delete the language
        function delete()
        {
        
            // delete query
            $query = "DELETE FROM countrylanguage WHERE Language = ?";
        
            // prepare query statement
            $result = $this->getConnection()->prepare($query); 
        
            if($result)
            { 
                // bind value
                $result->bind_param("s", $this->Language);
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