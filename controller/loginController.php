<?php
    // namespace controller;
    // use core\LIB;
    require 'model/sportsModel.php';
    // require 'model/sports.php';
    require_once 'config/database.php';
    require 'model/loginModel.php';
    require_once 'core/LIB.php';
    require 'model/sports.php';    
    
    session_status() === PHP_SESSION_ACTIVE ? TRUE : session_start();

    class loginController {
        function __construct() 
        {
            $this->objconfig = new database();
            $this->objsm =  new loginModel($this->objconfig);
            $this->objsm1 =  new sportsModel($this->objconfig);
        }
        public function mvcHandler() 
		{
			$act = isset($_GET['act']) ? $_GET['act'] : NULL;
			switch ($act) 
			{
                case 'login' :                    
					$this->login();
                    break;
                case 'add' :                    
                    $this->insert();
                    break;						
				default:
                    $this->loginView();
			}
		}
        public function loginView() 
        {
            include "view/login.php"; 
        }
        public function login() 
        {
            $username = htmlspecialchars($_POST['username']);
            $password = md5(htmlspecialchars($_POST['password']));
            $isLogin = $this -> objsm ->loginRecord($username , $password);
            if($isLogin == 1) {
                $this->list();
            }
            else {
                echo "Wrong credential";
                die();
            }
        }
        public function list(){
            $result=$this->objsm1->selectRecord(0);
            include "view/list.php";                                        
        }
        // add new record
		public function insert()
		{
            try{
                $sporttb=new sports();
                //$sporttb=new login();
                if (isset($_POST['addbtn'])) 
                {   
                    // read form value
                    $sporttb->category = trim(htmlspecialchars($_POST['category']));
                    $sporttb->name = trim(htmlspecialchars($_POST['name']));
                    //call validation
                    $chk=$this->checkValidation($sporttb);                    
                    if($chk)
                    {   
                        //call insert record            
                        $pid = $this -> objsm1 ->insertRecord($sporttb);
                        if($pid>0){			
                            $this->list();
                        }else{
                            echo "Somthing is wrong..., try again.";
                        }
                    }else
                    {    
                        $_SESSION['sporttbl0']=serialize($sporttb);//add session obj           
                        $this->pageRedirect("view/insert.php");                
                    }
                }
            }catch (Exception $e) 
            {
                $this->close_db();	
                throw $e;
            }
        }
        // check validation
		public function checkValidation($sporttb)
        {    $noerror=true;
            // Validate category        
            if(empty($sporttb->category)){
                $sporttb->category_msg = "Field is empty.";$noerror=false;
            } elseif(!filter_var($sporttb->category, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
                $sporttb->category_msg = "Invalid entry.";$noerror=false;
            }else{$sporttb->category_msg ="";}            
            // Validate name            
            if(empty($sporttb->name)){
                $sporttb->name_msg = "Field is empty.";$noerror=false;     
            } elseif(!filter_var($sporttb->name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
                $sporttb->name_msg = "Invalid entry.";$noerror=false;
            }else{$sporttb->name_msg ="";}
            return $noerror;
        }
    }