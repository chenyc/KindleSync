<?php
/**
 * 豆瓣发布
 *
 *
 */

require('OAuth.php');
require('config.php');

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
	$sql = "select value from kindle_config where name='access_token' and type = 'douban' and userid='".$_SESSION['userid']."'";
	$data = $mysql->getData($sql);
	$token = $data[0]['value'];

	$sql = "select value from kindle_config where name='oauth_token_secret' and type = 'douban' and userid='".$_SESSION['userid']."'";
	$data = $mysql->getData($sql);
	$token_secret = $data[0]['value'];

	// 创建一个 OAuthConsumer 对象。
	$consumer = new OAuthConsumer($api_key, $api_key_secret);

	$url = 'http://api.douban.com/miniblog/saying';
	$acc_token = new OAuthConsumer($token, $token_secret);
	$acc_req = OAuthRequest::from_consumer_and_token($consumer, $acc_token, "POST", $url);
	$acc_req->sign_request($sig_method, $consumer, $acc_token);

	$header = array('Content-Type: application/atom+xml', $acc_req->to_header('http://www.yourappdomain.com'));
	$requestBody = "<?xml version='1.0' encoding='UTF-8'?>".
			"<entry xmlns:ns0=\"http://www.w3.org/2005/Atom\" xmlns:db=\"http://www.douban.com/xmlns/\">".
			"<content>".$_REQUEST['text']."</content></entry>";

	$ch = curl_init();
	curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
	curl_setopt($ch,CURLOPT_HEADER,1);
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch,CURLOPT_POST,1);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $requestBody);
	$result = curl_exec($ch);
	curl_close($ch);
	var_dump($result);
}
?>

<head>
<title>douban</title>
</head>
<h2>发送新微博</h2>
<form action="send_weibo.php" method="post"><input type="text"
	name="text" style="width: 300px" /> &nbsp;<input type="submit" /></form>
