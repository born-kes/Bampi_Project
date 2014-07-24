<?php  $time_licznik = microtime(true);
/* //http://code.jquery.com/jquery-1.10.2.min.js
 * //http://code.jquery.com/ui/1.10.4/jquery-ui.min.js
 */
	ob_start();
	session_start();
error_reporting(E_ALL ^ E_NOTICE);
require_once('../inc/functions_admin.php');
require_once('../inc/fileMenager.php');
require_once('../inc/template.php');

    verifyLogin();
	
		if(!empty($_GET['go'] ) && $_GET['go']=='ajax' ){
            fileInclude('module');
		  exit;
		}
$file = new fileMenager();




//include('module/menu_global.php');
//   include('module/menu_boczne.php');
//  include('module/edit.php');


$tpl = new templateMenager();
$tpl->html( $tpl -> li(  $file->lista('pages/') ) ,'menu' );


//var_dump( $tpl->tags() );
 //$file->loadInclude('pages/'.@$_GET['go']);
$whot_page = autPhp(@$_GET['go']);
if(! $file->loadInclude('pages/'.$whot_page.'.php') )
$file->loadInclude('pages/'.$whot_page.'/'.$whot_page.'.php');

/*
//include('module/licznik.php');
$content= $file->load('pages/'.$_GET['go'])->data();


       $v= $GLOBALS['file'] -> load('module/menu_global.php')->data();
        eval($v);
echo $v;


//$tpl->html(,'content');
*/
ob_end_flush();