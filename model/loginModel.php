<?php
	
	class loginModel
	{
		// set database config for mysql
		function __construct($consetup)
		{
			$this->host = $consetup->host;
			$this->user = $consetup->user;
			$this->pass =  $consetup->pass;
			$this->db = $consetup->db;            					
		}
		// open mysql data base
		public function open_db()
		{
			$this->condb=new mysqli($this->host,$this->user,$this->pass,$this->db);
			if ($this->condb->connect_error) 
			{
    			die("Erron in connection: " . $this->condb->connect_error);
			}
		}
		// close database
		public function close_db()
		{
			$this->condb->close();
        }
		
		public function loginRecord($obj)
		{
			try
			{	$password = md5($obj->password);
				$this->open_db();
				$query=$this->condb->prepare("SELECT username , password FROM login WHERE username=? and password=?");
				$query->bind_param("ss",$obj->username,$password);
				$query->execute();
                $res= $query->get_result();
				$query->close();
				$this->close_db();
				$row = mysqli_fetch_array($res,MYSQLI_ASSOC);
				$count = mysqli_num_rows($res);
				return $count;
			}
			catch (Exception $e) 
			{
				$this->close_db();	
            	throw $e;
        	}
		}
    }    
?>