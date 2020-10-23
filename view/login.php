<?php
//    require_once 'core/LIB.php';
?>
<?php
        require_once 'model/login.php'; 
        //session_start();             
        $loginObj=isset($_SESSION['logintb1'])?unserialize($_SESSION['logintb1']):new login();            
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="~/../libs/fontawesome/css/font-awesome.css" rel="stylesheet" />    
    <link rel="stylesheet" href="~/../libs/bootstrap.css"> 
    <script src="~/../libs/jquery.min.js"></script>
    <script src="~/../libs/bootstrap.js"></script>
    <!-- <link rel="stylesheet" href="../libs/bootstrap.css"> -->
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo isset($_SESSION['err'])? 'error':''; session_destroy(); ?></div>
                    <div class="page-header">
                        <h2>Login</h2>
                    </div>
                    <p>Please Login</p>
                    <form action="../php-mvc-oop/index.php?act=login" method="post" >
                        <div class="form-group <?php echo (!empty($loginObj->username_msg)) ? 'has-error' : ''; ?>">
                            <label>User Name</label>
                            <input type="text" name="username" class="form-control" value="<?php //echo $sporttb->category; ?>">
                            <span class="help-block"><?php echo $loginObj->username_msg;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($loginObj->password_msg)) ? 'has-error' : ''; ?>">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" value="<?php //echo $sporttb->name; ?>">
                            <span class="help-block"><?php echo $loginObj->password_msg;?></span>
                        </div>
                        <!-- <input type="text" name="accnt" placeholder="accnt number" /> -->
                        <?//=LIB::csrfInput();?>
                        <!-- <input type="hidden" name="csrf_token" value="<?php// echo generate_token();?>" /> -->
                        <input type="submit" name="addbtn" class="btn btn-primary" value="Submit">
                        <a href="../php-mvc-oop/index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
