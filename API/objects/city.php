<?php

    class City extends Database
    {

        // read cities
        function read()
        {

            // select query
            $query = "SELECT city.Name, c.Name as Country, city.ID, city.CountryCode, city.District, city.Population, GROUP_CONCAT(LANGUAGE ORDER BY IsOfficial) as 'Languages' , GROUP_CONCAT(PERCENTAGE ORDER BY IsOfficial) as 'Lang_Percentage' FROM city LEFT JOIN country c ON city.CountryCode = c.Code LEFT JOIN countrylanguage l ON city.CountryCode = l.CountryCode WHERE city.CountryCode = l.CountryCode GROUP BY city.Name";
            
            $result = $this->getConnection()->query($query); 
            $numRows = $result->num_rows;
            if($numRows > 0)
            {
                $data = array();
                while ($row = $result->fetch_assoc())
                {
                    $lang = explode(',',$row['Languages']);
                    $perc = array_map('floatval', explode(',',$row['Lang_Percentage']));
    
                    $city_item["City"] = array
                    (
                        "ID" => (int)$row['ID'],
                        "Name" => $row['Name'],
                        "CountryCode" => $row['CountryCode'],
                        "Country" => $row['Country'],
                        "Population" => (int)$row['Population'],
                        "District" => $row['District'], 
                        "Language" => $lang,
                        "Lang_Percentage" => $perc     
                    );
                    array_push($data, $city_item);
                }
                return $data;
            }
            else
            {
                // set response code 
                http_response_code(404);

                // no cities found
                echo json_encode(array("message" => "No records found."));
                
                die();
            }
        }
        // read city
        function readOne()
        {

             // query 
            $query = "SELECT city.ID, city.Name, c.Name as Country, city.CountryCode, city.District, city.Population, GROUP_CONCAT(LANGUAGE ORDER BY IsOfficial) as 'Languages' , GROUP_CONCAT(PERCENTAGE ORDER BY IsOfficial) as 'Lang_Percentage' FROM city LEFT JOIN country c ON city.CountryCode = c.Code LEFT JOIN countrylanguage l ON city.CountryCode = l.CountryCode WHERE city.Name = ?";
            
            $result = $this->getConnection()->prepare($query); 
            $result->bind_param("s", $this->Name);
            $result->bind_result($ID, $Name, $Country, $CountryCode, $District, $Population, $Languages, $Lang_Percentage);
            $result->execute();

            $data = array();

            while($result->fetch())
            {
                $lang = explode(',',$Languages);
                $perc = array_map('floatval', explode(',',$Lang_Percentage));

                $city_arr["City"] = array
            (
                "ID" => $ID,
                "Name" => $Name,
                "CountryCode" => $CountryCode,
                "Country" => $Country,
                "Population" => $Population,
                "District" => $District, 
                "Language" => $lang,
                "Lang_Percentage" => $perc     
            );

            array_push($data, $city_arr);
            }
            
            return $data;

        } 
        

        // create city
        function create()
        {
        
            // query to insert city
            $query = "INSERT INTO
                     city (Name, CountryCode, District, Population, Created)
                    VALUES (?, ?, ?, ?, ?)";
        
            // prepare query
            $result = $this->getConnection()->prepare($query); 
        
            // sanitize
            $this->Name=htmlspecialchars(strip_tags($this->Name));
            $this->CountryCode=htmlspecialchars(strip_tags($this->CountryCode));
            $this->District=htmlspecialchars(strip_tags($this->District));
            $this->Population=htmlspecialchars(strip_tags($this->Population));
            $this->Created=htmlspecialchars(strip_tags($this->Created));

            
            if($result)
            { 
                // bind values
                $result->bind_param("sssis", $this->Name, $this->CountryCode, $this->District,$this->Population,$this->Created);
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

        // update the city
        function update()
        {
        
            // update query
            $query = "UPDATE
                     city
                     SET
                        Name = ?,
                        CountryCode = ?,
                        District = ?,
                        Population = ?,
                        Modified = ?
                     WHERE
                        ID = ?";
        
            // prepare query statement
            $result = $this->getConnection()->prepare($query); 
        
            // sanitize
            $this->Name=htmlspecialchars(strip_tags($this->Name));
            $this->CountryCode=htmlspecialchars(strip_tags($this->CountryCode));
            $this->District=htmlspecialchars(strip_tags($this->District));
            $this->Population=htmlspecialchars(strip_tags($this->Population));
            $this->ID=htmlspecialchars(strip_tags($this->ID));
            $this->Modified=htmlspecialchars(strip_tags($this->Modified));
        
            if($result)
            { 
                // bind values
                $result->bind_param("sssisi", $this->Name, $this->CountryCode, $this->District,$this->Population,$this->Modified, $this->ID);
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
        // delete the city
        function delete()
        {
        
            // delete query
            $query = "DELETE FROM city WHERE ID = ?";
        
            // prepare query
            $result = $this->getConnection()->prepare($query); 
        
            if($result)
            { 
                // bind values
                $result->bind_param("i", $this->ID);
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