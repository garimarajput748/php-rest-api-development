<?php

class db
{

    private $servername = "localhost";

    private $username = "root";

    private $password = "";

    private $dbname = "world_web";

    private $conn;
    
    // //////////////////////////////// C O N S T R U C T O R ////////////////////////////
  
    function __construct()
    {
        // connection
        try {
            $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
            // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return "Connected successfully";
            // return $this->conn;
        } catch (PDOException $e) {
            return "Connection failed: " . $e->getMessage();
        }
    }
    
	///////////////////////// S E L E C T F U N C T I O N //////////////////////////////
	
    function select($table_name,$condtion="")
    {
        $stmt = $this->conn->prepare("SELECT * FROM $table_name WHERE 1 $condtion");
		// print_r($stmt);exit;
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    
  
    /////////////////////////////////// I N S E R T __ F U N C T I O N //////////////////////////
  
    function insertdata($table, $data)
    {
        $fields = array_keys($data);
        $values = array_values($data);
        
        try {
            $query = "INSERT INTO " . $table . " (" . implode(",", $fields) . ") VALUES ( :" . implode(", :", $fields) . " )";
            // echo $query;exit();
            $stmt = $this->conn->prepare($query);
            foreach ($data as $key => &$val) {
                $stmt->bindParam(":$key", $val);
            }
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            return "<br>Error: " . $e->getMessage();
        }
    }
    
 
 ////////////////////////////// D E L E T E F U N C T I O N ////////////////////////

    function delete($table_name,$condtion)
    {   $con="";
		//loop for separate fields and values for condtion statement 
		foreach ($condtion as $k=>$v){  
            $con .= $k ." =  '$v' ,";
        }
        $con=rtrim($con,",");
		
        try {
            // prepare sql and bind parameters
            $sql = "DELETE FROM $table_name WHERE $con";
            $stmt = $this->conn->query($sql);
           // $stmt->bindParam(':ID', $id);
            $stmt->execute();
            return "records deleted successfully";
        } catch (PDOException $e) {
            return "<br>Error: " . $e->getMessage();
        }
    }
    
//////////////////////////////////// U P D A T E F U N C T I O N /////////////////////////////
    function update($table_name, $data, $condtion="")
    {
        $col="";		
        foreach ($data as $k=>$v){     //loop for separate fields and values 
            $col .= $k ." =  '$v' ,";
        }
        $col=rtrim($col,",");
		
        try {           
            $sql = "UPDATE $table_name SET $col WHERE 1 $condtion";
          //echo $sql;
		//	exit;	
           $stmt = $this->conn->query($sql);       
           $stmt->execute(); 
           return "upadte successfully.";
        }
        catch (PDOException $e) {
            return "<br>Error: " . $e->getMessage();
        } 
    }
}
?>