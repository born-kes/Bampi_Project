<?php
/**
 *
 */

/**
 * Podstawowa tresc
 */
$form = array(
    array(
        'legend'=>'Tytuł Podstrony',
        'name'=>'title',
        'type'=>'text',
        'value'=> (isset($_POST['title'])?$_POST['title']:(isset($page['title'])?$page['title']:'') )
    ),
    array(
        'legend'=>'Nazwa w adresie',
        'name'=>'file',
        'type'=>'text',
        'value'=> (isset($_POST['file'])?$_POST['file']:(isset($_GET['file'])?$_GET['file']:'') )
    ),
    array(
        'legend'=>'Treść',
        'name'=>'content',
        'type'=>'textarea',
        'style'=>'width: 758px;height: 250px;',
        'value'=> (isset($_POST['content'])?$_POST['content']:(isset($page['content'])?$page['content']:'') )
    ),
    array(
        'legend'=>'CkEdytor',
        'type'=>'button',
        'value'=>'Włącz',
        'onclick'=>"$('textarea').cleditor();return false;"
    ),
    array(
        'legend'=>'',
        'type'=>'submit',
        'value'=>'Zapisz'
    )
);
//fileSaveData('../pages/','.wytyczne.php',$form);

$Hook = array(
    'loading'=>'',
    'loading_go'=>'',
    'loading_validacja'=>'',
    'loadink_fin'=>'',

    'weryfikation'=>'',
    'weryfikation_go'=>'',
    'weryfikation_validacja'=>'',
    'weryfikation_fin'=>'',

    'conection'=>'',
    'conection_go'=>'',
    'conection_validacja'=>'',
    'conection_fin'=>'',


    'final'=>'',
    'final_go'=>'',
    'final_validacja'=>'',
    'final_fin'=>'',

    /*
    ''=>'',
    '_go'=>'',
    '_validacja'=>'',
    '_fin'=>'',
    */
);
//fileSaveData('../inc/','.hook.php',$form);

// Zaslania całą strone
//<div style="height: 100%; top: 0px; width: 100%; position: absolute; background: none repeat-x scroll 0 0 rgba(0, 0, 0, 0.6);"></div>


$users = array('admin' => array('admin',3) );
fileSaveData('../inc/','.users.php', $users);

getMsg();