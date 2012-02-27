<?php
session_start();
header('Content-Type:text/html; charset=utf-8');

if(!isset($_SESSION['userid']))
{
	header("Location: /user/login.php");
}

$mysql = new SaeMysql();
$sql = "select value from `kindle_config` where name = 'weibo_format' and type = 'weibo' and userid = '".$_SESSION['userid']."'";
$data = $mysql->getData($sql);
$format = $data[0]['value'];
if($format == NULL)
{
	$format = "#kindle摘录# %content% (%title%) %url%";
}

//$str = str_replace("%content%", "",$format);
//echo $str;

if($_SERVER['REQUEST_METHOD']=='POST' && $_REQUEST['format'] != ''){
	$sql = "delete from `kindle_config` where name = 'weibo_format' and type = 'weibo' and userid = '".$_SESSION['userid']."'";
	$mysql->runSql($sql);
	if( $mysql->errno() != 0 )
	{
		die( "Error:" . $mysql->errmsg() );
	}

	$sql = "insert into `kindle_config` (name,value,type,userid) values('weibo_format','".$_REQUEST['format']."','weibo','".$_SESSION['userid']."')";
	$mysql->runSql($sql);
	if( $mysql->errno() != 0 )
	{
		die( "Error:" . $mysql->errmsg() );
	}
	$mysql->closeDb();
	echo "<script>alert('设置成功!');window.location='/user/userinfo.php'</script>";
}

?>

<html>
<head>
<title>Kindle摘录同步</title>
</head>

<body>

<p></p>
<p></p>
<p></p>

<div style="width: 600px; margin: 0 auto;">
<div align="center">
<h2>微博自定义格式设置</h2>
<form name="formatfrm" method="post" action="user_weibo_format.php"><textarea
	rows="3" cols="50" name="format"><?=$format?></textarea>
<p></p>
<input type="button" value=" 保 存 " onclick="do_submit();" /> <input
	type="button" value=" 返 回 " onclick="javascript:history.go(-1)" /></form>

<div align="left">说明：<br>
%content% 摘录内容，内容过长会自动截断为合适的大小<br>
%title%   书籍标题<br>
%url%     该条摘录的链接<br>
</div>
</div>
</div>
<script type="text/javascript">
	function do_submit()
	{
		document.formatfrm.submit();
	}
</script>
</body>

</html>