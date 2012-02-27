<?php
session_start();
header('Content-Type:text/html; charset=utf-8');
$success = 1;
if(!isset($_SESSION['userid']))
{
	header("Location: /user/login.php");
}
else
{
	$mysql = new SaeMysql();
	$sql = "select id,userid,username,password,sina,qq,douban,fanfou from kindle_user where userid = '".$_SESSION['userid']."'";
	$data = $mysql->getData($sql);
	if( $mysql->errno() != 0 )
	{
		die( "Error:" . $mysql->errmsg() );
		$success = 0;
	}
	$uid = $data[0]['id'];
	$_SESSION['userid'] = $data[0]['userid'];
	$_SESSION['username'] = $data[0]['username'];
	$_SESSION['userinfo'] = $data[0];
}

?>

<?php if ($success == 1){
	?>


<html>
<head>
<title>Kindle摘录同步</title>
</head>


<body>
<div align="center">
<h2>Kindle摘录同步</h2>
<h2>微博绑定</h2>
<table border="1" cellpadding="2" width="400px">
	<tr>
		<td>当前登录用户</td>
		<td><?=$_SESSION['username']?></td>
	</tr>
	<tr>
		<td>新浪微博</td>
		<td><?php if($_SESSION['userinfo']['sina'] == 1) {
			echo "<font color='green'>已绑定</font>";}else{echo "未绑定";}?> <input
			type="button" value=" 绑 定 "
			onclick="javascript:window.location='/sina/request_token.php'" /></td>
	</tr>
	<tr>
		<td>腾讯微博</td>
		<td><?php if($_SESSION['userinfo']['qq'] == 1) {
			echo "<font color='green'>已绑定</font>";}else{echo "未绑定";}?> <input
			type="button" value=" 绑 定 "
			onclick="javascript:window.location='/qq/auth.php'" /></td>
	</tr>
	<tr>
		<td>豆瓣说</td>
		<td><?php if($_SESSION['userinfo']['douban'] == 1) {
			echo "<font color='green'>已绑定</font>";}else{echo "未绑定";}?> <input
			type="button" value=" 绑 定 "
			onclick="javascript:window.location='/douban/request_token.php'" /></td>
	</tr>
	<tr>
		<td>饭否微薄</td>
		<td><?php if($_SESSION['userinfo']['fanfou'] == 1) {
			echo "<font color='green'>已绑定</font>";}else{echo "未绑定";}?> <input
			type="button" value=" 绑 定 "
			onclick="javascript:window.location='/fanfou/request_token.php'" /></td>
	</tr>
</table>
<p></p>

<input type="button" value="微博格式设置"
	onclick="javascript:window.location='/user/user_weibo_format.php'">
<input type="button" value="查看我的摘录"
	onclick="javascript:window.location='/comment/user_comments.php?uid=<?=$uid?>'"> 

<input type="button" value="我的摘录RSS"
	onclick="javascript:window.location='/comment/rss.php?uid=<?=$uid?>'"> 
	
<input type="button" value=" 退 出 "
	onclick="javascript:window.location='/user/logout.php'"></div>
</body>


</html>












			<?php
}
?>