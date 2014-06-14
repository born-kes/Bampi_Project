/**
 * Created by Monika Lukasz on 14.06.14.
 */
$(function(){
    $( "#div_Global_Menu" ).tabs();
    $( "#div_Global_Menu .hidde").click(function(){
        $( "#div_Global_Menu li:gt(0)").toggle();
    });
    $( "#div_Global_Menu li:gt(0)").toggle();
});
