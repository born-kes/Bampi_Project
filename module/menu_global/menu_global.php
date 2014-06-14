<?

<div id="Global_post"><?php echo echo_r($_POST); ?></div>
<div id="Global_get"><?php echo echo_r($_GET); ?></div>
<div id="Global_session"><?php echo echo_r($_SESSION); ?></div>
<div id="Global_cookie"><?php echo echo_r($_COOKIE); ?></div>
<div id="Global_files"><?php echo echo_r($_FILES); ?></div>
<div id="Global_server"><?php echo echo_r($_SERVER); ?></div>
<div id="Global_request"><?php echo echo_r($_REQUEST); ?></div>
<div id="Global_config"><?php echo echo_r(fileLoadData('../inc/config.php')); ?></div>