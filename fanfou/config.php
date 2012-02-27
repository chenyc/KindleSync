<?php
/*
*	这个文件是整个SDK包的配置文件。你需要在这里修改你的配置
*	包含你的akey,skey,以及回调地址等
*/

include ("../config.php");
define( "FF_AKEY" , $FANFOU_APPKEY );	//这里填写你的consumer_key
define( "FF_SKEY" , $FANFOU_APPSECRET );	//这里填写你的consumer_secret_key
define( "FF_CALLBACK", 'http://kindlesync.sinaapp.com/fanfou/callback.php' );	//这里填写你的oauth认证的回调地址，是同文件夹下的callback.php


?>
