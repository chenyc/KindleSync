<?php

session_start();
if(!isset($_SESSION['userid']))
{
	header("Location: /user/login.php");
}


header('Content-Type:text/html; charset=utf-8');
error_reporting('0');
//设置include_path 到 OpenSDK目录
set_include_path(dirname(__FILE__) . '/lib/');
require_once 'OpenSDK/Tencent/Weibo.php';

include 'appkey.php';

OpenSDK_Tencent_Weibo::init($appkey, $appsecret);

$mysql = new SaeMysql();
$sql = "select value from kindle_config where name='access_token' and type = 'qq' and userid='".$_SESSION['userid']."'";
$data = $mysql->getData($sql);
$_SESSION[OpenSDK_Tencent_Weibo::ACCESS_TOKEN] = $data[0]['value'];

$sql = "select value from kindle_config where name='oauth_token_secret' and type = 'qq' and userid='".$_SESSION['userid']."'";
$data = $mysql->getData($sql);
$_SESSION[OpenSDK_Tencent_Weibo::OAUTH_TOKEN_SECRET] = $data[0]['value'];

?>
<head>
<title>douban</title>
</head>
<h2>发送新微博</h2>
<form action="send_weibo.php" method="post"><input type="text"
	name="text" style="width: 300px" /> &nbsp;<input type="submit" /></form>

<?php

if( isset($_REQUEST['text']) )
{
	$result =  OpenSDK_Tencent_Weibo::call('t/add', array(
		'content' => $_REQUEST['text'],
		'clientip' => '220.181.136.234',
	), 'POST') ;

	echo "<p>errcode:".$result["errcode"]."</p>";
	// 发送微博
	//$o->post( "http://api.t.sina.com.cn/statuses/update.json" , array( 'status' => $_REQUEST['text'] ) );
	echo "<p>发送完成</p>";

	echo "errcode=0   表示成功   errcode=4   表示有过多脏话   errcode=5   禁止访问，如城市，uin黑名单限制等   errcode=6   删除时：该记录不存在。
			发表时：父节点已不存在   errcode=8   内容超过最大长度：420字节 （以进行短url处理后的长度计） 
			  errcode=9   包含垃圾信息：广告，恶意链接、黑名单号码等
			     errcode=10   发表太快，被频率限制   errcode=11   源消息已删除，如转播或回复时 
			       errcode=12   源消息审核中   errcode=13   重复发表";

}

?>