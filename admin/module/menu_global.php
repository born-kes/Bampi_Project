 <script type="text/javascript">
$(function(){
    $( "#div_Global_Menu" ).tabs();
    $( "#div_Global_Menu .hidde").click(function(){
        $( "#div_Global_Menu li:gt(0)").toggle();
    });
    $( "#div_Global_Menu li:gt(0)").toggle();
});

</script>
<style type="text/css">
/* Globalne Manu Gurne */
#div_Global_Menu{
  z-index: 9999;
  top:-18px;
    width: 720px;
	width: -moz-max-content;
    font-size: inherit;
    border: 1px solid #403D32;
    background: none repeat scroll 0 0 #403D32;
    padding: 0;
    position: absolute;
}
#div_Global_Menu ul{
    display: block;
    list-style-type: none;
}
#div_Global_Menu div{
     display: none;
 }
#div_Global_Menu .ui-state-active {
    background: none repeat scroll 0 0 #403D32;
}
#div_Global_Menu div{
    color: #cacaca;
}
</style>

	<div id="div_Global_Menu">
        <ul id="Global_Menu">
            <li class="hidde">
                <a href="#Global_Ukryj">Ukryj</a>
            </li>
            <li>
                <a href="#Global_post">$_POST</a>
            </li>
            <li>
                <a href="#Global_get">$_GET</a>
            </li>
            <li>
                <a href="#Global_session">$_SESSION</a>
            </li>
            <li>
                <a href="#Global_cookie">$_COOKIE</a>
            </li>
            <li>
                <a href="#Global_files">$_FILES</a>
            </li>
            <li>
                <a href="#Global_server">$_SERVER</a>
            </li>
			<li>
                <a href="#Global_request">$_REQUEST</a>
            </li>
			<li>
                <a href="#Global_config">$Config</a>
            </li>
        </ul>
        <div id="Global_post"><?php echo echo_r($_POST); ?></div>
        <div id="Global_get"><?php echo echo_r($_GET); ?></div>
        <div id="Global_session"><?php echo echo_r($_SESSION); ?></div>
        <div id="Global_cookie"><?php echo echo_r($_COOKIE); ?></div>
        <div id="Global_files"><?php echo echo_r($_FILES); ?></div>
        <div id="Global_server"><?php echo echo_r($_SERVER); ?></div>
        <div id="Global_request"><?php echo echo_r($_REQUEST); ?></div>
        <div id="Global_config"><?php echo echo_r(fileLoadData('../inc/config.php')); ?></div>
	</div>

