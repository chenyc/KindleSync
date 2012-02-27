<?php

require_once('short.php');
require_once('../config.php');

session_start();
header('Content-Type:text/html; charset=utf-8');
if(!isset($_SESSION['userid']))
{
	header("Location: /user/login.php");
}


$success = 1;

if(isset($_REQUEST['title']) && isset($_REQUEST['comment']))
{
	//获取微博格式
	$mysql = new SaeMysql();
	$sql = "select value from `kindle_config` where name = 'weibo_format' and type = 'weibo' and userid = '".$_SESSION['userid']."'";
	$data = $mysql->getData($sql);
	$format = $data[0]['value'];
	if($format == NULL)
	{
		$format = "#kindle摘录# %content% (%title%) %url%";
	}
	
	//组建微博内容
	//$weibo_txt =  '#kindle摘录# ';
	$title = trim($_REQUEST['title']);
	$comment = trim($_REQUEST['comment']);
	
	$mysql = new SaeMysql();
	$sql = "select ifnull(max(`id`),0) as maxid from `kindle_comments`";
	$data = $mysql->getData( $sql );
	$id =  $data[0]['maxid'] + 1;
	
	//缩短url
	$url = "http://kindlesync.sinaapp.com/comment/show_comment.php?id=".$id;
	$url =  shortenSinaUrl($url);
	
	//$str = $weibo_txt.$title." ".$url;
	
	$str = str_replace("%content%", "",$format);
	$str = str_replace("%title%", $title,$str);
	$str = str_replace("%url%", $url,$str);
	
	$left = 276 - (strlen($str) + mb_strlen($str,'utf-8'))/2;
	
	$len = mb_strlen(mb_convert_encoding($comment, "gbk", "utf-8"));
	
	if($left < $len){
		$weibo_body = mb_convert_encoding(mb_strcut(mb_convert_encoding($comment, "gbk", "utf-8"),0,$left),"utf-8","gbk");
	}else
	{
		$weibo_body = $comment;
	}
	
	$weibo_txt = str_replace("%content%", $weibo_body,$format);
	$weibo_txt = str_replace("%title%", $title,$weibo_txt);
	$weibo_txt = str_replace("%url%", $url,$weibo_txt);


	$sql = "INSERT  INTO `kindle_comments` (`id`,`userid`,`username`,`title`,`comment`,`weibo_txt`) VALUES (%s,'%s','%s','%s','%s','%s') " ;
	$query = sprintf($sql,$id,$_SESSION['userid'],$_SESSION['username'],$_REQUEST['title'],$_REQUEST['comment'],$weibo_txt);

	$mysql->runSql( $query );
	if( $mysql->errno() != 0 )
	{
		die( "Error:" . $mysql->errmsg() );
		$success = 0;
	}
	else{

	//发布到新浪微博
	if($_SESSION['userinfo']['sina'] == 1 && $SINA_ENABLE){
		$f = new SaeFetchurl();
		$f->setMethod('post');
		$f->setPostData( array('text'=> $weibo_txt) );
		$f->setCookies($_COOKIE);
		$ret = $f->fetch('http://kindlesync.sinaapp.com/sina/send_weibo.php');

		//抓取失败时输出错误码和错误信息
		if ($ret === false){
			var_dump($f->errno(), $f->errmsg());
			$success = 0;
		}

		$sql = "update `kindle_comments` set sina = 1 where id=".$id;
		$mysql->runSql($sql );
		if( $mysql->errno() != 0 )
		{
			die( "Error:" . $mysql->errmsg() );
			$success = 0;
		}
	}



	//发布到腾讯微博
	if($_SESSION['userinfo']['qq'] == 1 && $QQ_ENABLE){
		$f = new SaeFetchurl();
		$f->setMethod('post');
		$f->setPostData( array('text'=> $weibo_txt) );
		$f->setCookies($_COOKIE);
		$ret = $f->fetch('http://kindlesync.sinaapp.com/qq/send_weibo.php');

		//抓取失败时输出错误码和错误信息
		if ($ret === false){
			var_dump($f->errno(), $f->errmsg());
			$success = 0;
		}

		$sql = "update `kindle_comments` set qq = 1 where id=".$id;
		$mysql->runSql($sql );
		if( $mysql->errno() != 0 )
		{
			die( "Error:" . $mysql->errmsg() );
			$success = 0;
		}
	}

	
	
	//发布到饭否微博
	if($_SESSION['userinfo']['fanfou'] == 1 && $FANFOU_ENABLE){
		$f = new SaeFetchurl();
		$f->setMethod('post');
		$f->setPostData( array('text'=> $weibo_txt) );
		$f->setCookies($_COOKIE);
		$ret = $f->fetch('http://kindlesync.sinaapp.com/fanfou/send_weibo.php');

		//抓取失败时输出错误码和错误信息
		if ($ret === false){
			var_dump($f->errno(), $f->errmsg());
			$success = 0;
		}

		$sql = "update `kindle_comments` set fanfou = 1 where id=".$id;
		$mysql->runSql($sql );
		if( $mysql->errno() != 0 )
		{
			die( "Error:" . $mysql->errmsg() );
			$success = 0;
		}
	}
	
	//发布到豆瓣说
	if($_SESSION['userinfo']['douban'] == 1 && $DOUBAN_ENABLE){
	
		//重新计算豆瓣的字符数
		$left = 138 - mb_strlen($str,'utf-8');
		$weibo_body = mb_substr($comment,0,$left,'utf-8');
		$weibo_txt = str_replace("%content%", $weibo_body,$format);
		$weibo_txt = str_replace("%title%", $title,$weibo_txt);
		$weibo_txt = str_replace("%url%", $url,$weibo_txt);

	
	
		$f = new SaeFetchurl();
		$f->setMethod('post');
		$f->setPostData( array('text'=> $weibo_txt) );
		$f->setCookies($_COOKIE);
		$ret = $f->fetch('http://kindlesync.sinaapp.com/douban/send_weibo.php');

		//抓取失败时输出错误码和错误信息
		if ($ret === false){
			var_dump($f->errno(), $f->errmsg());
			$success = 0;
		}

		$sql = "update `kindle_comments` set douban = 1 where id=".$id;
		$mysql->runSql($sql );
		if( $mysql->errno() != 0 )
		{
			die( "Error:" . $mysql->errmsg() );
			$success = 0;
		}
	}

}

$mysql->closeDb();

if($success == 1){
		echo "发布成功！";
	}
else
{
	echo "发布失败！";
}
}

?>
