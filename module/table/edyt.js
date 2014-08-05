/* table/edit.js */

$().ready(function(){



    $('#body_s tr').each(function(){
        $('th:last',this).prepend('<div style="padding-right: 20px">TOP</div>');
    });

});
function edit_tab(nr){

    if( $('#content #edit_div').length == 0 ){
        var div = $('<form id="edit_form"><table id="edit_div" style="width: 100%;" class="ui-widget-content"></table></form>');
        if(nr!==undefined){
            var body = $('#body_s tr:eq(' + nr + ')')
                .clone(false);
        }else{
            var body = $('tfoot tr:eq(0)')
                .clone(false);
        }

        body.attr('valign','top')
        $('td' , body ).each(function(a){

            var thead = $('#table_s thead td:eq('+(a+1)+')');

            if(a==0)$(this).addClass('hide');


            thead.children().remove();
            if( thead.hasClass('text') ){
                $(this).html(
                    '<textarea ' +
                        'name="' + thead.text()+'" '+
                        'class="ui-state-hover" ' +
                        'style="width: 200px;height:90px;padding-left:4px">'+
                        $(this).text()+
                        '</textarea>'
                )
            }else{
                $(this).html(
                    '<input ' +
                        'type="text" ' +
                        'name="' + thead.text()+ '" '+
                        'value="' + $(this).text()+ '" ' +
                        'class="ui-state-hover" ' +
                        'style="width: 90px;padding-left:4px" />'
                )
            }

        });
        $("textarea[name='nazwa']", body).change(function(){


            var val= encodeURI( $(this).val() );
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
        });

        window.top.document.id_ceneo = function(a){
            $("#edit_div input[name='kod_ceneo']").val(a);
            $('iframe').dialog("close");
            // $('#content iframe');
        }

        div.children('table').html( body );
        div.children('table').prepend(
            $('#table_s thead')
                .clone(false)
                .each(function(a){
                    td = $('td',this).attr("style", "");

                    var hide = new Array(0,1);
                    for(var i in hide)$('td:eq('+i+')',this).addClass('hide');

                })
        );

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
            "Zapisz produkt": function(){

                var odp = ajax('ajax.html?sql=update', form.serialize() );
                $('tr:odd td' , form).text(function(){
                    return $(this).children().val();
                });
                if( $('tr:odd', form ).attr('id') != 'tfoot')
                {
                    $('#table_s #'+ $('tr:odd', form ).attr('id')+' td' )
                        .html(function(a){
                            return $('tr:odd td:eq('+a+')', form).html();
                        });
                }else{
                    $('tr:odd th', form).removeClass().text('Nowy');
                    $('#table_s').append( $('tr:odd', form ) );
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
function ajax(url, val ){
    $.post( url , val )
        .done(function(data) {
            return (data);
        });
}
/* END table/edit.js */
