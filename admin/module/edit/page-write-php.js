(function($){
	
	$(function(){
		//alert('a');
		$('body').bind('keypress',submit_on_shift_enter);
		//$('textarea.input-text').bind('keypress',submit_on_shift_enter);
	});

	function submit_on_shift_enter(e) {
		if (e.keyCode==13 && (e.shiftKey || e.ctrlKey || e.metaKey)) {
			codeform_onsubmit(); 
			document.forms[0].submit();
			return false;
		}
	}
	
})(jQuery);


function doFocus() { 
	document.getElementById('textarea-for-code').focus(); 
}
function change(value) { 
	if (value=='js') window.location.href = 'http://writecodeonline.com/javascript';
	if (value=='url') window.location.href = 'http://quick-encoder.com/url';	
}
function setSelectionRange(input, selectionStart, selectionEnd) 
{
	if (input.setSelectionRange) 
	{
		input.focus();
		input.setSelectionRange(selectionStart, selectionEnd);
	}
	else if (input.createTextRange) 
	{
		var range = input.createTextRange();
		range.collapse(true);
		range.moveEnd('character', selectionEnd);
		range.moveStart('character', selectionStart);
		range.select();
	}
}

function replaceSelection (input, replaceString) 
{
	if (input.setSelectionRange) 
	{
		var selectionStart = input.selectionStart;
		var selectionEnd = input.selectionEnd;
		var scrollTop = input.scrollTop; // fix scrolling issue with Firefox
		input.value = input.value.substring(0, selectionStart) + replaceString +
		input.value.substring(selectionEnd);
		input.scrollTop = scrollTop;
		
		if (selectionStart != selectionEnd) 
		{ 
			setSelectionRange(input, selectionStart, selectionStart + 	replaceString.length);
		} 
		else 
		{
			setSelectionRange(input, selectionStart + replaceString.length, selectionStart + replaceString.length);
		}
	} 
	else if (document.selection) 
	{
		var range = document.selection.createRange();
		if (range.parentElement() == input) 
		{
			var isCollapsed = range.text == '';
			range.text = replaceString;
			if (!isCollapsed)  {
				range.moveStart('character', -replaceString.length);
				range.select();
			}
		}
	}
}
// We are going to catch the TAB key so that we can use it, Hooray!
// do this : <textarea wrap="off" onkeydown="return catchTab(this,event)" ></textarea>
function catchTab(item,e)
{
	if( navigator.userAgent.match("Gecko") ) 
	{ 
		c=e.which; 
	}
	else 
	{ 
		c=e.keyCode; 
	}
	if(c==9) 
	{
		replaceSelection(item,String.fromCharCode(9));
		setTimeout("document.getElementById('"+item.id+"').focus();",0);	
		return false;
	}
}