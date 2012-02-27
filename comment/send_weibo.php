<?php

session_start();
if(!isset($_SESSION['userid']))
{
	header("Location: /user/login.php");
}

header('Content-Type:text/html; charset=utf-8');
?>
<head>
<title>测试</title>
</head>

<h2>发布kindle摘录</h2>
<form action="comment_insert.php" method="post">title:<input type="text"
	name="title" style="width: 300px" /><br>
comment:<input type="text" name="comment" style="width: 300px;" />
&nbsp;<input type="submit" /></form>
