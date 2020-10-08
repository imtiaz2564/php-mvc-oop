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
        
        // fetch record
		public function loginRecord($username , $password)
		{
			try
			{	
				$this->open_db();
				$query=$this->condb->prepare("SELECT username , password FROM login WHERE username='$username' and password='$password'");
				// $query->bind_param("ss",$obj->category,$obj->name);
				$query->execute();
                $res= $query->get_result();
                
				// $last_id=$this->condb->username;
				$query->close();
				$this->close_db();
				$row = mysqli_fetch_array($res,MYSQLI_ASSOC);
				$count = mysqli_num_rows($res);
				return $count;
				// if($count == 1) {
				// 	// session_register("myusername");
				// 	// $_SESSION['login_user'] = $myusername;
					
				//  }else {
				// 	$error = "Your Login Name or Password is invalid";
				//  }
				// print_r($count);
                // die();
                // return $last_id;
			}
			catch (Exception $e) 
			{
				$this->close_db();	
            	throw $e;
        	}
        }
    }    
?>