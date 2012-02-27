<?php
session_start();
header('Content-Type:text/html; charset=utf-8');
header("Location: /comment/show_comment.php?id=".$_REQUEST['id']);