<?php
session_start();
header('Content-Type:text/html; charset=utf-8');
$_SESSION['userid'] = '1';
$_SESSION['username'] = '阳春面';
?>

<form method="post" action="weibolist.php">
<input type="submit" value="登录" />
</form>
