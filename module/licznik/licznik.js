/**
 * $microTimeStart => microTimeStart => {{time}}
 */
$(function(){
    $.ajax('?go=ajax').done(function(a,b){
        $('#licznik_end').text(function(){
            return ( a- {{time}} .toFixed(6) ) + ' s';
        });
    });
});