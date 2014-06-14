/**
 * Created by Monika Lukasz on 14.06.14.
 * $microTimeStart => microTimeStart
 */
$(function(){
    $.ajax('?go=ajax').done(function(a,b){
        $('#licznik_end').text(function(){
            return ( a- microTimeStart .toFixed(6) ) + ' s';
        });
    });
});