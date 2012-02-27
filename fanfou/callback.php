<?php

session_start();
require_once('config.php');
require_once('oauth.php');
require_once('client.php');

header('Content-Type:text/html; charset=utf-8');


$o = new OAuth( FF_AKEY , FF_SKEY , $_SESSION['temp']['oauth_token'] , $_SESSION['temp']['oauth_token_secret']  );

$last_key = $o -> getAccessToken(  $_SESSION['temp']['oauth_token'] ) ;

if(isset($last_key['oauth_token']) && isset($last_key['oauth_token_secret'])){

	//保存授权码到数据库
	$access_token = $last_key['oauth_token'];
	$oauth_token_secret = $last_key['oauth_token_secret'];

	$mysql = new SaeMysql();
	$sql = "delete from `kindle_config` where name = 'access_token' and type = 'fanfou' and userid = '".$_SESSION['userid']."'";
	$mysql->runSql($sql);
	if( $mysql->errno() != 0 )
	{
		die( "Error:" . $mysql->errmsg() );
	}

	$sql = "insert into `kindle_config` (name,value,type,userid) values('access_token','".$access_token."','fanfou','".$_SESSION['userid']."')";
	$mysql->runSql($sql);
	if( $mysql->errno() != 0 )
	{
		die( "Error:" . $mysql->errmsg() );
	}

	$sql = "delete from `kindle_config` where name = 'oauth_token_secret' and type = 'fanfou' and userid = '".$_SESSION['userid']."'";
	$mysql->runSql($sql);
	if( $mysql->errno() != 0 )
	{
		die( "Error:" . $mysql->errmsg() );
	}

	$sql = "insert into `kindle_config` (name,value,type,userid) values('oauth_token_secret','".$oauth_token_secret."','fanfou','".$_SESSION['userid']."')";
	$mysql->runSql($sql);
	if( $mysql->errno() != 0 )
	{
		die( "Error:" . $mysql->errmsg() );
	}

	$sql = "update kindle_user set fanfou = 1 where userid = '".$_SESSION['userid']."'";
	$mysql->runSql($sql);
	if( $mysql->errno() != 0 )
	{
		die( "Error:" . $mysql->errmsg() );
	}

	$mysql->closeDb();
	echo "<script>alert('绑定成功!');window.location='/user/userinfo.php'</script>";
}
else {
	echo "<script>alert('授权失败，请 重试!');window.location='/user/userinfo.php'</script>";
}

?>
