<?php ob_start();
$microTimeStart = microtime(true);//  header("Location: ./admin/");
session_start();
error_reporting(E_ALL ^ E_NOTICE);
//require_once('inc/functions.php');
//require_once('inc/functions_admin.php');
require_once('inc/medoo.php');
require_once('inc/fileMenager.php');
require_once('inc/template.php');

$url = (is_null(@$_GET['page'])?'home':$_GET['page']).'.php';

$file = new fileMenager();
$tpl = new templateMenager(
    array(
        array( 'menu'=>$file->load('cache/menu.html' )->data() ),
        //'config'=>$file->load('inc/config.php' )->data(),
        'page'=>$file->load("pages/$url" )->data()
    )
);

    ob_end_flush();