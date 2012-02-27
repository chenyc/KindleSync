<?php
session_start();
header('Content-Type:text/html; charset=utf-8');
if(!isset($_SESSION['userid']))
{
	header("Location: /user/login.php");
}

$mysql = new SaeMysql();
$sql = "select id, userid,username,title,comment from `kindle_comments` where userid = (select userid from kindle_user where id = ".$_REQUEST['uid'].") order by update_date desc";
$data = $mysql->getData( $sql );
?>
<html>
<head>
<title>Kindle摘录同步</title>
<link id="RSSLink" title="RSS" type="application/rss+xml"
	rel="alternate" href="http://kindlesync.sinaapp.com/comment/rss.php?uid=<?=$_REQUEST['uid']?>" />
</head>
<body>
<div style="margin: 10px">
<h2 align="center"><?=$_SESSION['username']?>的Kindle摘录列表</h2>
<?php
if(is_array($data))    //add
{
	foreach ($data as $value)
	{
		?>

<div>发布人：<?=$value['username']?>,出自：<?=$value['title']?>  
	
	<!--
	<input type="button" value=" 编辑 " onclick='do_edit("<?=$value['id']?>")' />
	<input type="button" value=" 删除 " onclick='do_delete("<?=$value['id']?>")' />
	-->
<br>
		<?=$value['comment']?></div>
<p><?php 
	}
}
?>

</div>

<script lang="javascript">
	function do_edit(id)
	{
		
	}
	
	function do_delete(id)
	{
		
	}
</script>

</body>
</html>
