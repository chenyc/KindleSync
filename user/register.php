<?php
header('Content-Type:text/html; charset=utf-8');


if($_SERVER['REQUEST_METHOD']=='POST' &&  $_REQUEST["userid"] != "" && $_REQUEST["encode_password"] != "" && $_REQUEST["username"] != "" )
{
	$success = 1;
	$show = false;
	//保存注册信息到数据库
	$mysql = new SaeMysql();
	//检测用户名是否已存在
	$sql = "select count(1) as cnt from kindle_user where userid = '".$_REQUEST["userid"]."'";
	$data = $mysql->getData($sql);
	if($data[0]['cnt'] > 0)
	{
		$errmsg = "用户名已存在，请更换用户名！";
		$show = true;
	}
	else{
		$sql = "insert into kindle_user (userid,password,username) values('%s','%s','%s')";
		$query = sprintf($sql, $_REQUEST["userid"], $_REQUEST["encode_password"],$_REQUEST["username"]);
		$mysql->runSql( $query );
		if( $mysql->errno() != 0 )
		{
			die( "Error:" . $mysql->errmsg() );
			$success = 0;
			$show = true;
		}
		$mysql->closeDb();

		if($success == 1){
			echo "<script>alert('注册成功');window.location='/user/login.php';</script>";
		}
		else
		{
			echo "<script>alert('注册失败');";
		}
}
}
else if($_SERVER['REQUEST_METHOD']=='POST')
{
	$errmsg = "用户名，密码，昵称都不能为空！";
	$show = true;
}
else
{
	$show = true;
}
?>

<?php
if($show){
	?>

<html>
<head>
<title>Kindle摘录同步</title>
<script type="text/javascript" src="/js/md5-min.js"></script>
</head>
<body>
<p></p>
<div align="center" style="top: 20%">
<h2>Kindle摘录同步</h2>
<h2>用户注册</h2>
<h5><font color="red"><?=$errmsg?></font></h5>
<form name="regfrm" action="register.php" method="post">
<table border="0">
	<tr>
		<td>用户名：</td>
		<td><input type="text" name="userid" /> <font color="red">*</font> (建议使用邮箱地址）</td>
	</tr>
	<tr>
		<td>密码：</td>
		<td><input type="password" name="password" id="password" /> <font
			color="red">*</font> <input type="hidden" name="encode_password"
			id="encode_password">(可与微博密码不同)</td>
	</tr>
	<tr>
		<td>昵称：</td>
		<td><input type="text" name="username" /> <font color="red">*</font></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="button" value=" 注 册 "
			onclick="do_submit()"> <input type="button" value=" 返 回  "
			onclick="javascript:window.location='/user/login.php'"></td>
	</tr>
</table>
</form>
</div>

<script type="text/javascript">
	function do_submit()
	{
		var hash=hex_md5(document.getElementById("password").value); 
		document.getElementById("encode_password").value =  hash;
		document.getElementById("password").value = "";
		document.regfrm.submit();
	}
</script>
</body>
</html>
<?php
}
?>
