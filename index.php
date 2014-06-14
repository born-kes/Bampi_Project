<?php $microTimeStart = microtime(true);//  header("Location: ./admin/");
    ob_start();	session_start();	error_reporting(E_ALL ^ E_NOTICE);

$typ=(isset($_GET['typ'])?$_GET['typ']:'html');
require_once('inc/functions.php');
print_r($_GET);
$Temple = temple(@$_GET['page']);

$Temple['blok'] = array_conect($Temple['blok'], $config);

echo stringSwap($Temple['theme'], $Temple['blok']);

    ob_end_flush();