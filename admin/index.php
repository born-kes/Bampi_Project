<?php $time_licznik = microtime(true);
	ob_start();
	session_start();
	require_once('../inc/functions_admin.php'); 
	error_reporting(E_ALL ^ E_NOTICE);
    verifyLogin();
	
		if(!empty($_GET['go'] ) && $_GET['go']=='ajax' ){
            fileInclude('module');
		  exit;
		}

?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <style type="text/css" media="all">
        @import url("style.css");
        @import url("http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.min.css");
    </style>
	<title>Panel Administracyjny</title>
    <script src="<?php //http://code.jquery.com ?>../j/jquery-1.10.2.min.js" type="text/javascript"></script>
    <script src="<?php //http://code.jquery.com/ui/1.10.4/ ?>../j/jquery-ui.min.js" type="text/javascript"></script>

    <link rel="stylesheet" type="text/css" href="../j/CLEditor/jquery.cleditor.css" />
    <script src="../j/CLEditor/jquery.cleditor.min.js"></script>

</head>
<body>
<?php
    include('module/menu_global.php');
    include('module/menu_boczne.php');
  //  include('module/edit.php');
?>
	<div id="top">
		<div id="wrap">
			<h1>Lekki<span style="color:#356AA0;">CMS</span></h1>
			<div class="right">
				Zalogowany jako Administrator <a href="?go=logout" class="button">Wyloguj się</a> <a href="../" target="_blank" class="button">Zobacz stronę</a>
			</div>
			<div style="clear: both;"></div>
			<ul>
                <?php $listaLi=fileList('../admin/pages');
                for( $x = 0; $x < count($listaLi); $x++ ) {
                    echo '<li '.(@$_GET['go']==$listaLi[$x]?'class="active"':'').'><a href="?go='.autPhp($listaLi[$x]).'">'.autPhp($listaLi[$x]).'</a></li>';

                } ?>
			</ul>
		</div>
	</div>

	<div id="wrap">
		<?php fileInclude('pages'); ?>
		<div class="footer"><p>Lekki<span style="color:#356AA0;">CMS</span> napisał <a href="http://klocus.pl">Klocek</a>.</p></div>
	</div>
<?php
    include('module/licznik.php');
?>
</body>
</html>
<?php ob_end_flush(); ?>