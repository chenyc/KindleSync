<?php
require_once('config.php');
require_once('oauth.php');

$o = new OAuth( FF_AKEY , FF_SKEY  );

$keys = $o -> getRequestToken();

$aurl = $o -> getAuthorizeURL( $keys['oauth_token'] ,false , FF_CALLBACK);

session_start();

$_SESSION['temp'] = $keys;

header("Location: $aurl");

?>