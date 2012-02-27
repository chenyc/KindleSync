<?php
/**
 * 腾讯微博登录验证
 *
 *
 */
error_reporting('0');
//设置include_path 到 OpenSDK目录
set_include_path(dirname(__FILE__) . '/lib/');
require_once 'OpenSDK/Tencent/Weibo.php';

include 'appkey.php';

OpenSDK_Tencent_Weibo::init($appkey, $appsecret);

//打开session
session_start();
header('Content-Type: text/html; charset=utf-8');
$exit = false;

if( isset($_GET['oauth_token']) && isset($_GET['oauth_verifier']))
{
	//从Callback返回时
	if(OpenSDK_Tencent_Weibo::getAccessToken($_GET['oauth_verifier']))
	{
		#$uinfo = OpenSDK_Tencent_Weibo::call('user/info');
		#var_dump($uinfo);

		//保存授权码到数据库
		$access_token = $_SESSION[OpenSDK_Tencent_Weibo::ACCESS_TOKEN];
		$oauth_token_secret = $_SESSION[OpenSDK_Tencent_Weibo::OAUTH_TOKEN_SECRET];

		$mysql = new SaeMysql();
		$sql = "delete from `kindle_config` where name = 'access_token' and type = 'qq' and userid = '".$_SESSION['userid']."'";
		$mysql->runSql($sql);
		if( $mysql->errno() != 0 )
		{
			die( "Error:" . $mysql->errmsg() );
		}

		$sql = "insert into `kindle_config` (name,value,type,userid) values('access_token','".$access_token."','qq','".$_SESSION['userid']."')";
		$mysql->runSql($sql);
		if( $mysql->errno() != 0 )
		{
			die( "Error:" . $mysql->errmsg() );
		}

		$sql = "delete from `kindle_config` where name = 'oauth_token_secret' and type = 'qq' and userid = '".$_SESSION['userid']."'";
		$mysql->runSql($sql);
		if( $mysql->errno() != 0 )
		{
			die( "Error:" . $mysql->errmsg() );
		}

		$sql = "insert into `kindle_config` (name,value,type,userid) values('oauth_token_secret','".$oauth_token_secret."','qq','".$_SESSION['userid']."')";;
		$mysql->runSql($sql);
		if( $mysql->errno() != 0 )
		{
			die( "Error:" . $mysql->errmsg() );
		}

		$sql = "update kindle_user set qq = 1 where userid = '".$_SESSION['userid']."'";
		$mysql->runSql($sql);
		if( $mysql->errno() != 0 )
		{
			die( "Error:" . $mysql->errmsg() );
		}
		$mysql->closeDb();

		echo "<script>alert('绑定成功!');window.location='/user/userinfo.php'</script>";

	}
	else
	{
		//var_dump($_SESSION);
		echo "<script>alert('获得Access Tokn 失败!');window.location='/user/userinfo.php'</script>";
	}
	$exit = true;
}
else if(isset($_GET['go_oauth']))
{
	$callback = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	$request_token = OpenSDK_Tencent_Weibo::getRequestToken($callback);
	$url = OpenSDK_Tencent_Weibo::getAuthorizeURL($request_token);
	header('Location: ' . $url);
}
else
{
	header("Location: auth.php?go_oauth");
}