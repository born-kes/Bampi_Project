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
fileSaveData('../pages/','.wytyczne.php',$form);