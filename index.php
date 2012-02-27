<?php
session_start();
header('Content-Type:text/html; charset=utf-8');
if(!isset($_SESSION['userid']))
{
	header("Location: /user/login.php");
}
else {
	header("Location: /user/userinfo.php");
}
?>