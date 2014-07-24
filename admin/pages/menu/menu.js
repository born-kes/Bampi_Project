$(function() {
    $( '.m-ul' ).sortable({
        connectWith: '.m-ul',
        handle: '.przenies',
            placeholder: '.el'
}).disableSelection();
$('.edit').click(function(){
    // alert($(this).parent().find('.el:eq(0) em:eq(0)').text() );
    var ss = ''+window.location;
    window.location =  ss.replace('menu','pages')+'&feature=edit&file='+
        $(this).parent().find('.el:eq(0) em:eq(0)').text();
})
});