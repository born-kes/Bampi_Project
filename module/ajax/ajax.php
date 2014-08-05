<?php

if( !isset($_POST['id']) ){
    echo microtime(true);
    exit;
}else{
    $this->loadInclude("module/sql/sql.php");
     sql();
    exit;
}
//    switch($_POST['form'])
//    {
//        case 'thead':break;
//    }

