<?php
    // use core\LIB;
    require_once 'model/sportsModel.php';
    // require 'model/sports.php';
    require_once 'config/database.php';
    require_once 'model/loginModel.php';
    require_once 'core/LIB.php';
    require_once 'model/login.php';
    require_once 'model/sports.php';
    require_once 'testController.php';    
    
    session_status() === PHP_SESSION_ACTIVE ? TRUE : session_start();

    class loginController {
        function __construct() 
        {
            $this->objconfig = new DatabaseConfig\database();
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
                case 'update' :                    
                    $this->update();
                    break;
                case 'delete' :                    
                    $this->delete();
                    break;						
                case 'test' :                    
                    $this->test();
                    break;
                case 'list' :                    
                    $this->list();
                    break;                            
    			default:
                    $this->loginView();
			}
        }
        public function test() {
            $testController = new testController();
            $testController->demo();
        }
        public function loginView() 
        {
            include "view/login.php"; 
        }
        public function login() 
        {
            $error = '';
            $loginObj = new login();
            // $username = htmlspecialchars($_POST['username']);
            // $password = md5(htmlspecialchars($_POST['password']));
            // $isLogin = $this -> objsm ->loginRecord($username , $password);
            $loginObj->username = trim(htmlspecialchars($_POST['username']));
            $loginObj->password = trim(htmlspecialchars($_POST['password']));
            $chkLogin = $this->checkLoginValidation($loginObj); 
            $isLogin = $this -> objsm ->loginRecord($loginObj);
            //echo $chkLogin;
           // echo $isLogin;
           // die();
            if($chkLogin) {

                if($isLogin == 1) {
                    $this->list();
                }
                else {
                    $error ='Wrong Credentials';
                    $_SESSION['err'] = $error;//add session obj           
                    $this->loginView();
                }
            }
            else{
                $_SESSION['logintb1']=serialize($loginObj);//add session obj           
                $this->loginView();                
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
                            //$this->list();
                            $this->pageRedirect("view/list.php"); 
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
        
        // page redirection
		public function pageRedirect($url)
		{
			header('Location:'.$url);
        }
        
        function checkLoginValidation($loginObj)
        {
            $noerror=true;
            
            // Validate category        
            if(empty($loginObj->username)){
                $loginObj->username_msg = "Field is empty.";$noerror=false;
            } 
            // elseif(!filter_var($loginObj->username, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
            //     $loginObj->username_msg = "Invalid entry.";$noerror=false;
            // }
            //else{$loginObj->username_msg ="";}            
            // Validate name            
            if(empty($loginObj->password)){
                $loginObj->password_msg = "Field is empty.";$noerror=false;     
            } 
            // elseif(!filter_var($loginObj->password, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
            //     $loginObj->password_msg = "Invalid entry.";$noerror=false;
            // }
            //else{$loginObj->password_msg ="";}
            return $noerror;

        }
         // update record
         public function update()
         {
             try
             {
                 
                 if (isset($_POST['updatebtn'])) 
                 {
                     $sporttb=unserialize($_SESSION['sporttbl0']);
                     $sporttb->id = trim($_POST['id']);
                     $sporttb->category = trim($_POST['category']);
                     $sporttb->name = trim($_POST['name']);                    
                     // check validation  
                     $chk=$this->checkValidation($sporttb);
                     if($chk)
                     {
                         $res = $this->objsm1->updateRecord($sporttb);	                        
                         if($res){			
                             $this->list();                           
                         }else{
                             echo "Somthing is wrong..., try again.";
                         }
                     }else
                     {         
                         $_SESSION['sporttbl0']=serialize($sporttb);      
                         $this->pageRedirect("view/update.php");                
                     }
                 }elseif(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
                     $id=$_GET['id'];
                     $result=$this->objsm1->selectRecord($id);
                     $row=mysqli_fetch_array($result);  
                     $sporttb=new sports();                  
                     $sporttb->id=$row["id"];
                     $sporttb->name=$row["name"];
                     $sporttb->category=$row["category"];
                     $_SESSION['sporttbl0']=serialize($sporttb);
                     $this->pageRedirect('view/update.php');
                 }else{
                     echo "Invalid operation.";
                 }
             }
             catch (Exception $e) 
             {
                 $this->close_db();				
                 throw $e;
             }
        }
        // delete record
        public function delete()
		{
            try
            {
                if (isset($_GET['id'])) 
                {
                    $id=$_GET['id'];
                    $res=$this->objsm1->deleteRecord($id);                
                    if($res){
                        //$this->pageRedirect('index.php');
                        $this->list();                           
                        
                    }else{
                        echo "Somthing is wrong..., try again.";
                    }
                }else{
                    echo "Invalid operation.";
                }
            }
            catch (Exception $e) 
            {
                $this->close_db();				
                throw $e;
            }
        } 
    }