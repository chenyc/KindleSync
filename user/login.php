<?php
session_start();
header('Content-Type:text/html; charset=utf-8');

if($_SERVER['REQUEST_METHOD']=='POST' && $_REQUEST['userid'] != '' &&  $_REQUEST['encode_password'] != '' ){
	$mysql = new SaeMysql();
	$sql = "select userid,username,password,sina,qq,douban,fanfou from kindle_user where userid = '".$_REQUEST['userid']."'";
	$data = $mysql->getData($sql);
	if( $mysql->errno() != 0 )
	{
		die( "Error:" . $mysql->errmsg() );
		$success = 0;
	}

	$password = $data[0]['password'];

	if(isset($password) && $_REQUEST['encode_password'] == $password){
		$_SESSION['userid'] = $data[0]['userid'];
		$_SESSION['username'] = $data[0]['username'];
		$_SESSION['userinfo'] = $data[0];
		$success = 1;
	}
	else {
		$success = 0;
		echo "<script>alert('登录失败,请检查用户名密码是否正确！');</script>";
	}
	
	//登录成功，跳转
	if($success == 1){
		echo "<script>window.location='/user/userinfo.php';</script>";
	}
}
?>
<head>
<title>Kindle摘录同步</title>
<script type="text/javascript" src="/js/md5-min.js"></script>
</head>
<body>

<p></p>
<p></p>
<p></p>
<div align="center" style="top: 20%">
<h2>Kindle摘录同步</h2>
<h2>用户登录</h2>
<form name="loginfrm" method="post" action="login.php">用户名: <input
	type="text" name="userid" width="120px"><br>
&nbsp;密 码: <input type="password" id="password" name="password"
	width="120px">
<p><input type="hidden" id="encode_password" name="encode_password"> <input
	type="button" value=" 登 录 " onclick="do_submit();" /> <input
	type="button" value=" 注 册 "
	onclick="javascript:window.location='register.php'" />

</form>
<a href="http://chenyc.info/kindlesync/">kindle配置说明</a>
</div>
<script type="text/javascript">
	function do_submit()
	{
		var hash=hex_md5(document.getElementById("password").value); 
		document.getElementById("encode_password").value =  hash;
		document.getElementById("password").value = "";
		document.loginfrm.submit();
	}
</script>
</body>
