<?php
if(isset($_POST)){
print_r(@$_POST);
if(isset($_POST['img']) && is_array($_POST['img']) )
foreach($_POST['img'] as $key => $val){
    echo $key.' $key => $val '. $val.'<br>';
}
}
print_r(menuLoad() );
//eval('include(\'module/edit.php\');');
?>
<script type="text/javascript">
function drop(node){
	var str=''; 	
	if(node.get(0).nodeName==undefined)
	return;
		str+=' '+node.get(0).nodeName;
	if(node.attr('id')!=undefined)
		str+='#'+node.attr('id');
	if(node.attr('class')!=undefined)
		str+='.'+node.attr('class');
	if(node.attr('type')!=undefined)
		str+='['+node.attr('type')+']';
if(	str!='')	
return str+' ';
return str;
}

$(document).ready(function() {
    var i=0;
$("#dodaj").click(function() {
var new_div = $("<div>");
    new_div.attr("class", "div");
    var new_label = $("<label>");
        var new_input = $("<input>");
        new_input.attr("type", "text");
        new_input.attr("name", "img[]");//+(i++)
        var new_link = $("<a>");
            new_link.attr("href", "#");
            new_link.html("usuń");
            new_link.click(function() {
            $(this).parent("label").remove();
            });
            new_label.append(new_input);
            new_label.append(new_link);

            new_div.append(new_label);

            $("fieldset").append(new_div);
            return false;
            });
    $('input').attr('style','width:60px;');
    $('input').autoGrowInput({
        comfortZone: 5,
        minWidth: 80,
        maxWidth: 2000
    });

/*	$( "*", document.body ).click(function( event ) {
  event.stopPropagation();
  var str='';
	if($( this ).parent().attr('id')!=undefined)
		str+=' #'+$( this ).parent(2).attr('id');
	else
  str+=$( this ).parent().get( 0 ).nodeName;
	
  str+=' '+$( this ).get( 0 ).nodeName;
  
  
//  var domElement = $( this ).get( 0 ).nodeName;
  alert( str +multiclick);
});*/

$( "*", document.body ).bind({
	 dblclick: function(event) {
  event.stopPropagation();
  var str=	drop($( this ).parent().parent().parent().parent() )+
			drop($( this ).parent().parent().parent() )+
			drop($( this ).parent().parent() )+
			drop($( this ).parent() )+
			drop($( this ) );
	alert('str:'+str);
	},
	mouseenter: function() {
  var str=	drop($( this ).parent().parent().parent().parent() )+
			drop($( this ).parent().parent().parent() )+
			drop($( this ).parent().parent() )+
			drop($( this ).parent() )+
			drop($( this ) );
  
   $('#where').text(str);
  }
});
});
(function($){
// jQuery autoGrowInput plugin by James Padolsey
// See related thread: http://stackoverflow.com/questions/931207/is-there-a-jquery-autogrow-plugin-for-text-fields

$.fn.autoGrowInput = function(o) {
        o = $.extend({
            maxWidth: 1000,
            minWidth: 0,
            comfortZone: 70
        }, o);
        this.filter('input:text').each(function(){
            var minWidth = o.minWidth || $(this).width(),
                val = '',
                input = $(this),
                testSubject = $('<tester/>').css({
                    position: 'absolute',
                    top: -9999,
                    left: -9999,
                    width: 'auto',
                    fontSize: input.css('fontSize'),
                    fontFamily: input.css('fontFamily'),
                    fontWeight: input.css('fontWeight'),
                    letterSpacing: input.css('letterSpacing'),
                    whiteSpace: 'nowrap'
                }),
                check = function() {
                    if (val === (val = input.val())) {return;}
// Enter new content into testSubject
                    var escaped = val.replace(/&/g, '&amp;').replace(/\s/g,'&nbsp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                    testSubject.html(escaped);
// Calculate new width + whether to change
                    var testerWidth = testSubject.width(),
                        newWidth = (testerWidth + o.comfortZone) >= minWidth ? testerWidth + o.comfortZone : minWidth,
                        currentWidth = input.width(),
                        isValidWidthChange = (newWidth < currentWidth && newWidth >= minWidth)
                            || (newWidth > minWidth && newWidth < o.maxWidth);
// Animate width
                    if (isValidWidthChange) {
                        input.width(newWidth);
                    }
                };
            testSubject.insertAfter(input);
            $(this).bind('keyup keydown blur update', check);
        });
        return this;
    };
})(jQuery);

</script>
<h1> oto ja </h1>
            <form action="" name="photoAdd" method="post" id="addForm1">
                <fieldset>

                    <legend>Taka ładna rameczka dla Form :)</legend>

                    <div class="div">
                        <a href="#" id="dodaj">dodaj pole uploadu</a>
                    </div>

                    <div class="div">
                        <input type="text" name="fSubmit" value="Załaduj" class="submit">
                    </div>
                    <div class="div">
                        <input type="tekst" name="fSubmit" value="Załaduj" class="submit">
                    </div>
                    <div class="div">
                        <input type="submit" name="fSubmit" value="Załaduj" class="submit">
                    </div>

                </fieldset>
            </form>
<?php
/**
 * Created by PhpStorm.
 * User: Mar
 * Date: 10.02.14
 * Time: 14:41


//tworzymy nową, pustą listę select o id: podkategorie i ją dołączamy do formularza
select = '<select id="podkategorie"></select>';
$('#formularz fieldset').append(select);
var lista = $('#podkategorie');

//ukrywamy listę. Potrzebne to będzie do uzyskania animacji pojawienia się elementu na stronie
lista.hide();

//generowanie kolejnych opcji listy
$.each(data, function(key, val){
var option = $('<option/>');
option.attr('value', key)
.html(val)
.appendTo(lista);
});
<div class="div">
<a href="#" id="dodaj">dodaj pole uploadu</a>
</div>

<div class="div">
<input type="radio" name="fSubmit" value="Załaduj" class="submit">
</div>

 */unset($select);
$select['type']='select';
$select['value']= array(1,'page'=>'page','trzy'=>3);
$select['name']='select';
$select['id']='id_select';
$select['spec']='page';
$select['sufix']='<br>';
$form[]=$select;


$form[] = array(
    'type'=>'radio',
    'value'=> array('page','trzy',3),
    'name'=>'radio',
    'spec'=>'trzy',
    'sufix'=>'<br>',
        'prefix'=>' <legend> radio </legend>'
);
$form[] = array(
    'type'=>'checkbox',
    'value'=> array('page','trzy',3),
    'name'=>'checkbox',
    'id'=>'id_checkbox',
    'spec'=>3,
    'sufix'=>'<br>'

);
//var_dump ($form);
//print_r($form);
echo form($form,'Pokazowy Form');
?>
<p id="where">test</p>
