/* table/dymki.js */

$().ready(function(){

    /**
     * Rozwijane Menu Górne
     */
//    :gt(1)
    $('#thead').find('td').append(function(){return ''+
        '<div class="contextual-links-wrapper contextual-links-processed">' +
        '<a href="#" class="contextual-links-trigger">Konfiguruj</a>' +
        '<ul class="contextual-links" style="display: none;">' +
        '<li class="node-colResizable"><a href="">Zmień szerokość</a></li>' +
        '<li class="node-hide first"><a href="">Ukryj</a></li>' +
        '<li class="node-hide last"><a href="">Ukryj na Stałe</a></li>' +
        '</ul></div>';});

    /**
     * Rozwijane Menu Boczne
     */
    $('#body_s th:even').append(function(nr){return ''+
            '<div class="contextual-links-wrapper contextual-links-processed">' +
            '<a href="#" class="contextual-links-trigger">Konfiguruj</a>' +
            '<ul class="contextual-links" style="display: none;">' +
            '<li style="margin-bottom:10px"><a class="conect-links" href="http://www.ceneo.pl/'+
        $('#body_s').find('tr:eq('+nr+')').attr('dir')+
        ';0280-0.htm#tab=click_scroll" target="_blank">Idź na Cenneo</a></li>' +
            '<li class="node-cenneo first"><a href="">Sprawdz Cenneo + Podgląd</a></li>' +
            '<li class="node-cenneo last"><a href="">Sprawdz Cenneo</a></li>' +
            '<li class="node-edit first"><a href="">Edutuj wiersz</a></li>' +
            '<li class="node-delete first" style="margin-top:10px"><a href="">Usuń wiersz</a></li>' +
            '</ul></div>';})
        .each(function(nr){

            /**
             * Sprawdz Cenneo             *
             */
            $('.node-cenneo a' , this).click(function(){

                var id = $('#body_s tr:eq(' + nr + ')').attr('dir');

                if( $('#iframe').length > 0 )
                { $('#body_s tr:eq(' + nr + ') ol li').remove();
                    if($('#iframe').attr('src')!='proxy.html?ceneo='+ id)
                        $('#iframe').attr('src','proxy.html?ceneo='+ id);
                }
                else
                {
                    $('#content')
                        .append('<iframe id="iframe"' +
                            ' src="proxy.html?ceneo='+ id +'"' +
                            ' style="display:none"' +
                            ' title="Podgląd z Ceneo" />');
                }
                return false;
            });

            /**
             * Sprawdz Ceneo + Podgląd
             * to tylko Podgląd
             */
            $('.node-cenneo.first a' , this).click(function(){

                $('#iframe').dialog({ width: 905,height:600,
                    show: {effect: "clip",duration: 1000},
                    hide: {effect:"clip", duration:300}
                })
                    .css({ width: 880, height:600});
                return false;
            });

            /**
             * Edytuj wiersz
             */
            $('.node-edit.first a' , this).click(function(){edit_tab(nr);return false;});

            /**
             * Usuń wiersz
             */
            $('.node-delete.first a' , this).click(function(){
                var nr_ = $('tr.activ th:eq(0)').clone()
                    .children()
                    .remove()
                    .end();
                if( confirm('Usuwam wiersz '+nr_.text() ) ){
                    ajax('ajax.html?sql=usun', 'id='+$('#content').find('tr.activ').attr('id') );
                    $('tr.activ').remove();
                }
                return false;
            });
        });
    $('tfoot .ui-button-text-only').click(function(){edit_tab();return false;});

    /**
     * Blokowanie Kliknięć w menu Górnym i Bocznym
     */
    $('.contextual-links-wrapper a').not('.conect-links').click(function(){return false;});

    $('#thead td,#body_s th:even')
        .each(function(){
            $('a:eq(0)',this).click(function(){
                $(this).next().toggle("slow");
                return false;
            });
           $('a:gt(0)',this).click(function(){
                $(this).next().toggle("slow");
                return false;
            });

        })
        .mouseover(function(){
            $('div',this).addClass("contextual-links-active");
        })
        .mouseout(function(){
            $('div',this).removeClass("contextual-links-active" );
        });



    $('#thead')
        .find('td ul').each(function(nr){

        $('.node-colResizable a', this).click(function(){

            if(colResiza)
                colResizable();
            else{
                $("table").colResizable({ disable : true });
                $('#table_s tfoot tr:eq(0) td').each(function(nr){
                    $(this).css('width',$('#thead').find('tr:eq(0) td:eq('+( nr )+')').width() );
                });

                colResiza=true
            }
        });
       $('.node-hide a', this).click(function(){
                 $("table").colResizable({ disable : true });
            $('#content').addClass('td'+(nr+1)+'h');
        });
        $('.node-hide.last a', this).click(function(){
               var el = $(this).parents('td').clone(false).children().remove().end().text();;


            ajax('ajax.html?sql=hide','id='+el
             //   '&id='+tr.attr('id')+
                   // '&top'+odp.top+'='+odp.firma+
                   // '&top'+odp.top+'_cena='+odp.cena

            );
        });
    })/* chrzani sie
        .mouseover(function(){
          //  $(this).stop().show("slow");
        })
        .mouseout(function(){
          //  $(this).hide("slow");
        })*/
        .find('ul a').click(function(){
           $(this).parents('ul').hide("slow");
        });

});

/* END table/dymki.js */
