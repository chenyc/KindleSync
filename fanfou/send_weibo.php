<?php
/**
 * 豆瓣发布
 *
 *
 */

require('client.php');
require('config.php');
require('oauth.php');

//打开session
session_start();

if(!isset($_SESSION['userid']))
{
	header("Location: /user/login.php");
}

header('Content-Type: text/html; charset=utf-8');

if( isset($_REQUEST['text']) )
{

	$mysql = new SaeMysql();
	$sql = "select value from kindle_config where name='access_token' and type = 'fanfou' and userid='".$_SESSION['userid']."'";
	$data = $mysql->getData($sql);
	$token = $data[0]['value'];

	$sql = "select value from kindle_config where name='oauth_token_secret' and type = 'fanfou' and userid='".$_SESSION['userid']."'";
	$data = $mysql->getData($sql);
	$token_secret = $data[0]['value'];

	//发布微薄
	$ffclient = new FFClient(FF_AKEY , FF_SKEY,$token,$token_secret);
	$ffclient->update($_REQUEST['text']);
}
?>

<head>
<title>douban</title>
</head>
<h2>发送新微博</h2>
<form action="send_weibo.php" method="post"><input type="text"
	name="text" style="width: 300px" /> &nbsp;<input type="submit" /></form>
