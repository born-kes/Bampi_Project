/* table/dymki.js */

$().ready(function(){

    $('#table_s thead td:gt(1)').append(function(){
        '<div class="contextual-links-wrapper contextual-links-processed">' +
        '<a href="#" class="contextual-links-trigger">Konfiguruj</a>' +
        '<ul class="contextual-links" style="display: none;">' +
        '<li class="node-hide-all first"><a href="">Zmień szerokość</a></li>' +
        '<li class="node-hide-all first"><a href="">Ukryj na Stałe</a></li>' +
        '<li class="node-hide last"><a href="">Ukryj</a></li>' +
        '</ul></div>'});


    $('#body_s th:even').append(function(){return ''+
            '<div class="contextual-links-wrapper contextual-links-processed">' +
            '<a href="#" class="contextual-links-trigger">Konfiguruj</a>' +
            '<ul class="contextual-links" style="display: none;">' +
            '<li class="node-cenneo first"><a href="">Sprawdz Cenneo + Podgląd</a></li>' +
            '<li class="node-cenneo last"><a href="">Sprawdz Cenneo</a></li>' +
            '<li class="node-edit first"><a href="">Edutuj wiersz</a></li>' +
            '<li class="node-delete first" style="margin-top:10px"><a href="">Usuń wiersz</a></li>' +
            '</ul></div>'})
        .each(function(nr){ //alert($('.node-cenneo',this).html());

            $('.node-cenneo a' , this).click(function(){

                var id = $('#body_s tr:eq(' + nr + ')').attr('dir');

                if( $('#content iframe').length > 0 )
                {
                    if($('#content iframe').attr('src')!='proxy.html?ceneo='+ id)
                        $('#content iframe').attr('src','proxy.html?ceneo='+ id);
                }
                else
                {
                    $('#content').append('<iframe src="proxy.html?ceneo='+ id +'" style="display:none" title="Podgląd z Ceneo" />');
                }
                return false;
            });

            $('.node-cenneo.first a' , this).click(function(){

                $('#content iframe').dialog({ width: 905,height:600,
                    show: {effect: "clip",duration: 1000},
                    hide: {effect:"clip", duration:300}
                })
                    .css({ width: 880, height:600});
                return false;
            });

            $('.node-edit.first a' , this).click(function(){edit_tab(nr);});

            $('.node-delete.first a' , this).click(function(){
                var nr_ = $('tr.activ th:eq(0)').clone()
                    .children()
                    .remove()
                    .end();
                if( confirm('Usuwam wiersz '+nr_.text() ) ){
                    ajax('ajax.html?sql=usun', 'id='+$('tr.activ').attr('id') );
                    $('tr.activ').remove();
                }

            });

        });
    $('tfoot .ui-button-text-only').click(function(){edit_tab();});

    $('.contextual-links-wrapper a').click(function(){return false;});

    $('#table_s thead td,#body_s th')
        .each(function(){
            $('a:eq(0)',this).click(function(){
                $(this).next().toggle("slow");
            });

        })
        .mouseover(function(){
            $('div',this).addClass("contextual-links-active");

        })
        .mouseout(function(){
            $('div',this).removeClass("contextual-links-active" );
            //  $('ul:eq(0)',this).css('display','none');
        });


    $('#table_s thead td ul').each(function(nr){
        $('a:eq(0)', this).click(function(){

            if(colResiza)
                colResizable();
            else{
                $("table").colResizable({ disable : true });
                $('#table_s tfoot tr:eq(0) td').each(function(nr){
                    $(this).css('width',$('#table_s thead tr:eq(0) td:eq('+( nr )+')').width() );
                });

                colResiza=true
            }
        });
        $('a:eq(2)', this).click(function(){
            $("table").colResizable({ disable : true });
            $('#table_s tr').each(function(){
                var cr = true;
                $('td:eq('+nr+')',this).animate({
                        width :"0px",
                        fontSize:"0em",
                        padding:"0"},
                    3000,
                    function(){
                        $(this).remove();
                    }
                );
            });
        });
    })
        .mouseout(function(){
            $(this).next().hide("slow");
        });

});

/* END table/dymki.js */
