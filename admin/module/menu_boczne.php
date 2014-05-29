 <script type="text/javascript">
$(function(){
 // Manu boczne left|right wysuwaj� si�

	$("#panel_left").css("left","-220px");
	$("#panel_right").css("right","-220px");
 $( "#panel_left .slide_button" ).click(function(){ 
	if($("#panel_left").css("left")=="-220px")
		$("#panel_left").animate({left: "0px"}, 500 );
	else
		$("#panel_left").animate({left: "-220px"}, 500 );
	});
 $( "#panel_right .slide_button" ).click(function(){ 
	if($("#panel_right").css("right")=="-220px")
		$("#panel_right").animate({right: "0px"}, 500 );
	else
		$("#panel_right").animate({right: "-220px"}, 500 );
	});
	});
</script>
<style type="text/css" media="all">
#panel_left{
     border-radius:10px;
	background: #223344;
	border-style: double;
	padding: 10px;
	width: 200px;
	height: 100%;
	position: fixed;
	left: 0px; 
}
 #panel_right{ 
	border-radius:10px;
	background: #223344;
	border-style: double;
	padding: 10px;
	width: 200px;
	height: 100%;
 
	position: fixed;
	right: 0px; 
}
ul#main_menu
{
	list-style: none;
}
 
#main_menu a
{
	color: #ffffff;
	text-decoration: none;
}
 
#main_menu a:hover
{
	text-decoration: underline;
}
.slide_button
{
	display: block;
	height: 100%;
	text-indent: -9999px;
	width: 24px;
	right: -4px;

	position: absolute;
	top: 0px;
}
#panel_right .slide_button
{
	left:  -4px;
}
 
.zamknij
{
	background-position: -20px 0px;
}
 
a:focus { 
outline:none; //usuwanie niebieskiej obwódki w FF
}
</style>
<div id="panel_left" style="left: -220px;">

		<ul id="main_menu">
      <li></li>
			<li id="efect"><a href="#">Portfolio</a></li>
			<li><a href="#">Kontakt</a></li>
    </ul>
		
		<a class="slide_button" href="#">Menu</a>
		
</div>
<div id="panel_right" style="right: -220px;">
		
		<ul id="main_menu">
      <li><a href="#">Strona g��wna</a></li>
			<li><a href="#">Portfolio</a></li>
			<li><a href="#">Kontakt</a></li>
    </ul>
		
		<a class="slide_button" href="#">Menu</a>
		
</div>
 
