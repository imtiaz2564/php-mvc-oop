<?php
	session_unset();
    // require_once  'controller/sportsController.php';
    require_once  'controller/loginController.php';			
    // $controller = new sportsController();	
    // $controller->mvcHandler();
    $loginController = new loginController();
    $loginController->mvcHandler();
?>