/* table/edit.js */

$().ready(function(){

});
function iframCeneo(el) {

    var val= encodeURI( el.val() );
    if( $('#content iframe').length > 0 )
    {
        if( $('#content iframe').attr('src') != 'proxy.html?ceneo='+ val )
            $('#content iframe').attr('src' , 'proxy.html?ceneo='+ val);
    }
    else
    {
        $('#content').append('<iframe src="proxy.html?ceneo='+ val +
            '" style="display:none" title="Podgląd z Ceneo" />');
    }
    var frame = $('#content iframe')

    frame.dialog({
        width: 905,
        height:600,
        show: {effect: "clip",duration: 1000},
        hide: {effect:"clip", duration:300},
        close: function() {
            $('iframe').remove();
        }
    })
        .css({ width: 880, height:600});
}
/**
 * Edytowanie wierszy
 * @param int nr - nr wiersza tbody
 */
function edit_tab(nr){

    if( $('#content #edit_div').length == 0 ){
        var div = $('<form id="edit_form"><table id="edit_div" style="width: 100%;" class="ui-widget-content"></table></form>');


        if(nr!==undefined){
            var body = $('#body_s tr:eq(' + nr + ')')
                .clone(false);



            body.attr('valign','top')
        $('td' , body ).each(function(a){
            var thead_ = $('#thead td:eq('+(a)+')').clone(false);
            if(a==0)
                $(this).addClass('hide');
            thead_.children().remove();

            if( thead_.hasClass('text') && false ){
                $(this).html(
                    '<textarea ' +
                        'name="' + thead_.text()+'" '+
                        'class="ui-state-hover" ' +
                        'style="width: 200px;height:90px;padding-left:4px">'+
                        $(this).text()+
                        '</textarea>'
                )
            }else{
                $(this).html(
                    '<input ' +
                        'type="text" ' +
                        'name="' + thead_.text()+ '" '+
                        'value="' + $(this).text()+ '" ' +
                        'class="ui-state-hover" ' +
                        'style="width: 90px;padding-left:4px" />'
                )
            }


        });
            thead = $('#thead')
                .clone(false).each(function(a){
                td = $('td',this).attr("style", "");
                $('td:eq(0)',this).addClass('hide');

                //    var hide = new Array(0,1);
                //    for(var i in hide)$('td:eq('+i+')',this).addClass('hide');

            })

        }else{
            var thead = $('<tr>'+
                '<td>kod_produktu</td>'+
                '<td>Nazwa</td>'+
                '<td>kod_ceneo</td>'+'</tr>'
                );

            var body = $('<tr>' +
                '<td><input ' +
                'type="text" ' +
                'name="kod_produktu" '+
                'value="" ' +
                'class="ui-state-hover" /></td>'+
                '<td><input ' +
                'type="text" ' +
                'name="nazwa" '+
                'value="" ' +
                'class="ui-state-hover" /></td>'+
                '<td><input type="text" name="kod_ceneo" />' +
                '<input type="hidden" name="id" />' +
                '</td>'+
                '</tr>');

        }


        $("input[name='nazwa']", body).change(function(){iframCeneo( $(this) );});

        window.top.document.id_ceneo = function(a){
            $("#edit_div input[name='kod_ceneo']").val(a);
            $('iframe').dialog("close");
            // $('#content iframe');
        }

        div.children('table').html( body );
        div.children('table').prepend( thead );

        $('#content').append(div);
    }

    var form = $('#edit_form');
    form.dialog({
        width: '90%',
        left:'1%',
        modal: true,
        show: {effect: "clip",duration: 1000},
        hide: {effect:"clip", duration:300},
        buttons: {
            "Sprawdz na Ceneo":function(){iframCeneo( $("input[name='nazwa']",body) );},
            "Zapisz produkt": function(){

                 ajax('ajax.html?sql=update', form.serialize() );
                /** spłaszcza usuwając inputy **/
                $('tr:odd td' , form).text(function(){
                    return $(this).children().val();
                });


                if(
                    $('tr:odd', form ).attr('id')!== null   &&
                    $('tr:odd', form ).attr('id')!== 'undefined'     &&
                    $('tr:odd', form ).attr('id')!== undefined
                    )
                {
                    $('#table_s #'+ $('tr:odd', form ).attr('id')+' td' )
                        .html(function(a){
                            return $('tr:odd td:eq('+a+')', form).html();
                        });
                }
                else
                {

                    var urlClin = location.href.split('?');
                    var nowy = $('#body_s').find('tr:eq(0)').clone();
                    nowy.attr('dir', $('tr:odd td:eq(2)', form).text() )
                        .attr('id',"DodanyNowy")
                        .find('th,td').text('');
                    nowy.find('th:eq(0)').text( $('#body_s').find('tr').length-1 );
                    nowy.find('td:eq(1)').text( $('tr:odd td:eq(0)', form).text() );
                    nowy.find('td:eq(4)').text( $('tr:odd td:eq(1)', form).text() );
                    nowy.find('td:eq(3)').text( $('tr:odd td:eq(2)', form).text() );

                    $('#body_s').append( nowy );
                    setTimeout(function(){
                        location.href = urlClin[0] + '?' +
                            ($('tr:odd td:eq(1)', form).text()) +
                            '#tfoot';
                    },0);

                }
                form.dialog( "close" );
            },
            "Anuluj": function() {
                form.dialog("close");
            }
        },
        close: function() {
            $(this).remove();
        }
    });


}

function ajax(url, val, nowy ){

    return  $.post( url , val )
        .done(function(data) {
            if(nowy!==undefined){
                $('#DodanyNowy').attr('id',data);
                var nr = $('#body_s').find('tr').length-1;
                setTimeout(function(){edit_tab(nr);},400);
            }
        });

}
/* END table/edit.js */
