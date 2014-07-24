$(document).ready(function() {
    $('input[type="text"]').keyup(function(){
        $(this).attr({width: 'auto', size: ($(this).val().length+parseInt($(this).val().length *0.15) )});
    });
    $('input[type="text"]').each(function(){
        $(this).attr({width: 'auto', size: ($(this).val().length+parseInt($(this).val().length *0.15) )});
    });
});