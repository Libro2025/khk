<?php

include_once 'dbConfig.php';

class Crud extends dbConfig
{

    function __construct()
    {
        parent::__construct();
    }

    function getData($query)
    {
        $result = $this->conn->query($query);
        $row_cnt = mysqli_num_rows($result);
        $row = [];
        
        if($row_cnt == 0){
            return $row;
        }else{
           while($row=$result->fetch_assoc())
            {
                $rows[] = $row;
            }    
            return $rows; 
        }
      }

      
    function findData($query)
    {
        $result = $this->conn->query($query);
           
        $row_cnt = mysqli_num_rows($result);
         
        return $row_cnt;
    }

    function execute($query)
    {
        $result = $this->conn->query($query);
        
        if(!$result)
        {
            $fmsg = "Greška kod izvršavanja SQl upita";
            return false;
        } 
        else 
        {
            return true;
        }
    }

    function delete($id, $table)
    {
        $query = "DELETE FROM $table WHERE id = $id";
        $result = $this->conn->query($query);

        if(!$result)
        {
            echo 'Greška kod brisanja zapisa '.$id.' iz tablice '.$table;
            return false;
        } 
        else
        {
            return true;
        }
    }

    function escape($value)
    {
        return $this->conn->real_escape_string($value);
    }
    
}
