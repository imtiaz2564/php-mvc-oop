<?php

class login
{
    // table fields
    public $id;
    public $username;
    public $password;
    // message string
    public $id_msg;
    public $username_msg;
    public $password_msg;
    // constructor set default value
    function __construct()
    {
        $id=0;$username=$password="";
        $id_msg=$username_msg=$password_msg="";
    }
}

?>