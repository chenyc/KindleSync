<?php

session_start();
include_once( 'config.php' );
include_once( 'saet.ex.class.php' );
header('Content-Type:text/html; charset=utf-8');


$o = new SaeTOAuth( WB_AKEY , WB_SKEY , $_SESSION['keys']['oauth_token'] , $_SESSION['keys']['oauth_token_secret']  );

$last_key = $o->getAccessToken(  $_REQUEST['oauth_verifier'] ) ;

if(isset($last_key['oauth_token']) && isset($last_key['oauth_token_secret'])){

	//保存授权码到数据库
	$access_token = $last_key['oauth_token'];
	$oauth_token_secret = $last_key['oauth_token_secret'];

	$mysql = new SaeMysql();
	$sql = "delete from `kindle_config` where name = 'access_token' and type = 'sina' and userid = '".$_SESSION['userid']."'";
	$mysql->runSql($sql);
	if( $mysql->errno() != 0 )
	{
		die( "Error:" . $mysql->errmsg() );
	}

	$sql = "insert into `kindle_config` (name,value,type,userid) values('access_token','".$access_token."','sina','".$_SESSION['userid']."')";
	$mysql->runSql($sql);
	if( $mysql->errno() != 0 )
	{
		die( "Error:" . $mysql->errmsg() );
	}

	$sql = "delete from `kindle_config` where name = 'oauth_token_secret' and type = 'sina' and userid = '".$_SESSION['userid']."'";
	$mysql->runSql($sql);
	if( $mysql->errno() != 0 )
	{
		die( "Error:" . $mysql->errmsg() );
	}

	$sql = "insert into `kindle_config` (name,value,type,userid) values('oauth_token_secret','".$oauth_token_secret."','sina','".$_SESSION['userid']."')";
	$mysql->runSql($sql);
	if( $mysql->errno() != 0 )
	{
		die( "Error:" . $mysql->errmsg() );
	}

	$sql = "update kindle_user set sina = 1 where userid = '".$_SESSION['userid']."'";
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



