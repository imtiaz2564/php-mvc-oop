<?php
    
    // namespace controller;
    // use core\LIB;
    require 'model/sportsModel.php';
    // require 'model/sports.php';
    require_once 'config/database.php';
    require 'model/loginModel.php';
    require_once 'core/LIB.php';
    
    
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
            $password = htmlspecialchars($_POST['password']);
            $isLogin = $this -> objsm ->loginRecord($username , $password);
            if($isLogin == 1){
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
    }