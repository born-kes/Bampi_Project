/**
 * Created by Monika Lukasz on 14.06.14.
 */
$(function(){
    // Manu boczne left|right wysuwaj� si�

    $("#panel_left").css("left","-220px");
    $("#panel_right").css("right","-220px");
    $( "#panel_left .slide_button" ).click(function(){
        if($("#panel_left").css("left")=="-220px"){
            $("#panel_left").animate({left: "0px"}, 500 );
        }else{
            $("#panel_left").animate({left: "-220px"}, 500 );
        }
    });
    $( "#panel_right .slide_button" ).click(function(){
        if( $("#panel_right").css("right")=="-220px" ){
            $("#panel_right").animate({right: "0px"}, 500 );
        } else {
            $("#panel_right").animate({right: "-220px"}, 500 );
        }

    });
});