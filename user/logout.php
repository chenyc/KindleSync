<?php
session_start();
unset($_SESSION['userid']);
unset($_SESSION['username']);
unset($_SESSION['userinfo']);

session_destroy();

if(!isset($_SESSION['userid']))
{
	header("Location: /user/login.php");
}
