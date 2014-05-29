<?php
/**
 * Created by PhpStorm.
 * User: Mar
 * Date: 25.02.14
 * Time: 15:16
 *
 * @class function  array_conect();
 */
//$Blok=array('head','body','title','menu','content','footer');


session_start();
if(isset($_SESSION['user']) && $_SESSION['user']>0){
    $BLOKS['head'].='
    <script type="text/javascript" src="<?php //http://code.jquery.com ?>../j/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="<?php //http://code.jquery.com/ui/1.10.4/ ?>../j/jquery-ui.min.js"></script>';
    $BLOKS['css'].='
    @import url("http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.min.css");';

}

$config = file_get_data('inc/config.php');
$BLOKS=file_get_data('themes/'.$config['theme'].'/.block.php');
if(empty($BLOKS)){
      $Blok=array(
          'head'    =>'',
          'css'     =>'',
          'title'   =>'',
          'body'    =>'',
          'menu'    =>'',
          'content' =>'',
          'footer'  =>'',
      );
    filesaveData('themes/'.$config['theme'].'/','.block',$Blok);
    $BLOKS=$Blok;
}
$Standart = array(
    'menu'    => MenuLoad(),
//    'content' => fileInclude(@$_GET['page'], 'content'),
    'page_title'=> fileInclude(@$_GET['page'], 'title'),
    'this_url'  => $_SERVER['REQUEST_URI']
);
$BLOKS= array_conect ($BLOKS,$Standart);

$page = fileInclude(@$_GET['page'], 'all');
if(isset($page['page_function'])){ Function_page(@$page , @$config); unset($page['page_function']); }

$page['countent']=stripslashes($page['countent']);
$BLOKS= array_conect ($BLOKS,$config);
$BLOKS= array_conect ($BLOKS,$page );

foreach($BLOKS as $bloks => $val){
    if(!preg_match('/{{[a-zA-Z0-9_]+.}}/', $val) )
        $BLOKS[$bloks]= replaceWidgets($BLOKS[$bloks]);
}


