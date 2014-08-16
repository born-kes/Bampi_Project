$().ready(function(){

    $('#body_s').find('th:odd').prepend('<div style="padding: 5px" class="ol"><ol class="zasysanie"></ol></div>');

$('#zasysanie_b')
    .addClass("ui-widget ui-state-default ui-corner-all ui-button-text-only")
    .click(function(){
        /*       var odp={
            nr : 0,
            id : $('#body_s tr:eq(0)').attr('dir'),
            top : 1,
            cena : '00.00',
            c_info: 'opis:bleblelble',
            name : 'nazwa',
            img : '<img>',
            firma : 'oknoplast ;P ',
            info : function(){
                return 'top='+this.nr+
                    "\n cena="+this.cena+
                    "\n c_info="+this.c_info+
                    "\n name="+this.name+
                    "\n img="+this.img+
                    "\n firma="+this.firma;
            }
        };
        if(window.top.document.wstawiacz)
            window.top.document.wstawiacz(odp);

 To już działa  */// return;
        var nr = (prompt("Od Którego zacząć", "1"))-1;
    $('#zasysanie_f').attr('src',
            'proxy.html?ceneo='+$('#body_s').find('tr:eq('+nr+')').attr('dir')
                +'&nr='+nr );
        return true;
  });

});
var max_top = 5;
//var szablon_top = <div></div>;
window.top.document.wstawiacz = function(odp){


    if( odp.top <= max_top){
        var string = ''+
            '<li style="overflow: hidden;">' +
            //'<div class="row">' +
            '<div style="width: 8px">{{top}}</div>' +
            '<div>{{firma}}</div>' +
            '<div>{{cena}}</div>' +
            '<div>{{c_info}}</div>' +
            '<div>{{name}}</div>' +
           // '</div>' +
            '</li>';
        if(odp.nr != -1){
            var tr = $('#body_s').find('tr:eq(' + odp.nr +')');
        }
        else
        {
            var tr = $('#body_s').find('tr.activ');
        }
        if( odp.top ==1 && tr.find('th:last li').length > 0 ){
            $('#body_s').find('tr.activ th:last li').remove();
        }

        if(odp.top < 5)
        ajax('ajax.html?sql=update_ceny',
                '&id='+tr.attr('id')+
                '&top'+odp.top+'='+odp.firma+
                '&top'+odp.top+'_cena='+odp.cena

        );

//alert(odp.info());
        string = string.replace('{{nr}}', odp.nr);
        string = string.replace('{{id}}', odp.id);
        string = string.replace('{{top}}', odp.top);
        string = string.replace('{{cena}}', odp.cena);
        string = string.replace('{{c_info}}', odp.c_info);
        string = string.replace('{{name}}', odp.name);
        string = string.replace('{{img}}', odp.img);
        string = string.replace('{{firma}}', odp.firma);

        tr.find('th:last ol:first').append(string);

        if(odp.firma=='Av World'){ tr.addClass('Av Av'+odp.top) }

        if(!odp.next) return;
        if(!tr.hasClass('Av')) tr.addClass('Av Av0');

        tr.find('th:last ol:first').css('min-width',function(){
           var min = 0;
            $(this).find('li:first div').each(function(){ min +=$(this).width();});
            return min+'px';
        })

    }

    if(odp.nr != -1 && odp.nr != "undefined" && odp.next) {
//alert( (odp.nr != -1) + "\n" + (odp.nr != "undefined") +"\n" + "\n\n" + odp.info());
        if(odp.nr < $('#body_s').find('tr').length && odp.nr>-1){

            var nr = (odp.nr+1);
            $('#zasysanie_f').attr('src',
                'proxy.html?ceneo='+$('#body_s').find('tr:eq('+nr+')').attr('dir')
                    +'&nr='+nr );
        }else{
            $('#zasysanie_f').attr('src','');
        }
    }else{
      //  alert( (odp.nr != -1) + "\n" + (odp.nr != "undefined") +"\n" + "\n\n" + odp.info());


    }
};
