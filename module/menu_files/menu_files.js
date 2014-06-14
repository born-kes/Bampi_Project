/**
 * Created by Monika Lukasz on 14.06.14.
 */
$(function(){
    //pierwsze wczytanie spisu drzewa katalog�w
    $( "#cat_" ).bind("click",function(){
        ajax("#cat_", "../");
        $(this).unbind("click");
    });
});
var ajax_URL="../aa/admin/?go=ajax&files="
function ajax(obj, url){

    var ther = $(obj);
    $(obj).unbind("click");
    $(obj+" a").bind("click", function(){$(obj+" div").toggle(1000);});
    $.getJSON(ajax_URL+url)
        .done(function( data ) {
            $.each( data.katalog, function( i, item ) {
                $( "<div>" ).attr( "id","cat_"+item )
                    .css("display","none")
                    .html(
                    function(){ $("<a>").attr("href", "#").addClass( "katalog" ).text(item).appendTo( this ); })
                    .bind("click",function(){ajax(obj+" #cat_"+item, url+"/"+item); })
                    .appendTo( ther );
            })
            $.each( data.pliki, function( i, item ) {
                $( "<div>" )
                    .addClass( "plik" )
                    .css("display","none")
                    .text(item)
                    .appendTo( ther );
            });
            $(obj+" div").toggle(1000);
        });

}