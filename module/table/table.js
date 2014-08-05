/* table/table.js */

$().ready(function(){

    $('#content,.table_fi')
        .css('width',$(window).width()-20)
    $('#body_s tr')
        .prepend(function(a){ return '<th>'+(a+1)+'</th>'; })
        .append('<th />');
    $('#table_s thead tr')
        .prepend('<td />')
        .append('<td />');
    $('.table_fi')
        .append('<div />')
        .prepend('<div />');

    $('#table_s tr:first td , #table_s tr:eq(1) td,.table_fi div').each(function(){
        $(this).css('width',$(this).width());
    });


 /*   var nav = $('#table_s thead'); // Zastąpione do testów*/
    var nav = $('.table_fi');
    var t_s = $('#table_s');

    $('tr,td').mouseenter(function(){ var el = $(this);
        el.addClass("ui-state-default");
        $('*',el).click(function(){$('*').removeClass("activ"); el.addClass("activ");})
    })
        .mouseleave(function(){$(this).removeClass("ui-state-default");});

// TODO Pływające Menu
    $(window).scroll(function () {
        if ($(this).scrollTop() > 150) {
            if(! nav.hasClass('f-nav') ){

                $('div', nav).each(function(a){
                    $(this).width( $('#table_s thead td:eq('+a+')').width() );
                });
                nav.addClass("f-nav");
            }

        /*     t_s.addClass("f-nav");*/
        } else {
            nav.removeClass("f-nav");
         //   t_s.removeClass("f-nav");
        }
    });




        {
            $("#table_s").tablesorter( {selectorHeaders:"thead td"} );
        }
});

/* END table/table.js */