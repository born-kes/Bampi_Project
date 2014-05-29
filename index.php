<?php $start = microtime(true);
    ob_start();	session_start();	error_reporting(E_ALL ^ E_NOTICE);

$typ=(isset($_GET['typ'])?$_GET['typ']:'html');
require_once('inc/functions.php');

require_once('inc/themes.php');

$Temple = file_get_contents('themes/'.$config['theme'].'/template.html');
echo swap_string($Temple,$BLOKS);


    ob_end_flush();
?>