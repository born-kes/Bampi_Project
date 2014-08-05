/* table/table.js */

$().ready(function(){

    $('#content')
        .css('width',$(window).width()-20)
    $('#body_s tr')
        .prepend(function(a){ return '<th>'+(a+1)+'</th>'; })
        .append('<th />');
    $('#table_s thead tr')
        .prepend('<td />')
        .append('<td />');

    $('#table_s tr:first td , #table_s tr:eq(1) td').each(function(){
        $(this).css('width',$(this).width());
    });


    var nav = $('#table_s thead');
    var t_s = $('#table_s')

    $('tr,td').mouseenter(function(){ var el = $(this);
        el.addClass("ui-state-default");
        $('*',el).click(function(){$('*').removeClass("activ"); el.addClass("activ");})
    })
        .mouseleave(function(){$(this).removeClass("ui-state-default");});

// TODO Pływające Menu
    $(window).scroll(function () {
        if ($(this).scrollTop() > 150) {

            nav.addClass("f-nav");
            t_s.addClass("f-nav");
        } else {
            nav.removeClass("f-nav");
            t_s.removeClass("f-nav");
        }
    });




        {
            $("#table_s").tablesorter( {selectorHeaders:"thead td"} );
        }
});

/* END table/table.js */