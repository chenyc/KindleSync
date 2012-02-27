<?php
session_start();
header('Content-Type:text/html; charset=utf-8');
if( isset($_REQUEST['id']) )
{
	$mysql = new SaeMysql();
	$sql = "select userid,username,title,comment from `kindle_comments` where id = ".$_REQUEST['id'];
	$data = $mysql->getData($sql);
	if( $mysql->errno() != 0 )
	{
		die( "Error:" . $mysql->errmsg() );
	}
	$mysql->closeDb();
}
?>

<html>
	<head>
		<title>Kindle摘录</title>
	</head>
	<body>
		发布人：<?=$data[0]['username']?>
		<p>
		出自：<?=$data[0]['title']?>
		<p>	
		摘录：<?=$data[0]['comment']?>
	</body>

</html>