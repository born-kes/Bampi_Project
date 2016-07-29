var AV_MEDIA  = 'avworld.pl';
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
var max_top = 5, cenoManiak=[];

window.top.document.zasysacz = function(next) {

    if(next < $('#body_s').find('tr').length && next>-1){



        var nr = (next+1),
            tr = $('#body_s').find('tr:eq('+nr+')')
            dir = tr.attr('dir'),
            id = tr.attr('id')

        ajax('ajax.html?sql=update_ceny',
            '&id=' + id +
            '&top1=&top1_cena=&top2=&top2_cena=&top3=&top3_cena=&top4=&top4_cena=' +
            '&dedykowana=&dedykowana_top=&dedykowana_cena='

        );
        $('#zasysanie_f').attr('src',
            'proxy.html?ceneo='+ dir
                +'&nr='+nr );
    }else{
        $('#zasysanie_f').attr('src','');
    }
}
//var szablon_top = <div></div>;
window.top.document.wstawiacz = function(odp){
    var tr
    if(odp.nr != -1){
        tr = $('#body_s').find('tr:eq(' + odp.nr +')');
    }
    else
    {
        tr = $('#body_s').find('tr.activ');
    }
    var string;

    if(alternatiw==odp.firma){
        if(!tr.find('th:last .alternatiw').length)
            tr.find('th:last').append('<div class="alternatiw"><ol class="zasysanie"></ol></div> ');
        tr.find('th:last .alternatiw ol').html( wstawTekst(odp) );
        ajax('ajax.html?sql=update_ceny',
            '&id=' + tr.attr('id') +
            '&dedykowana=' + odp.firma+
            '&dedykowana_cena=' + odp.cena+
            '&dedykowana_top=' + odp.top
        );
    }

    if( odp.top <= max_top){

        if( odp.top ==1 && tr.find('th:last li').length > 0 ){
            tr.find('th:last li').remove();
        }

        if(odp.top < 5)
        ajax('ajax.html?sql=update_ceny',
                '&id='+tr.attr('id')+
                '&top'+odp.top+'='+odp.firma+
                '&top'+odp.top+'_cena='+odp.cena

        );
        if(cenoManiak[parseInt(tr.attr('id'))] === undefined)
            cenoManiak[parseInt(tr.attr('id'))]=[];
        cenoManiak[parseInt(tr.attr('id'))][odp.top]=parseFloat(odp.cena);

        tr.find('th:last ol:first').append( wstawTekst(odp) );

        if(AV_MEDIA == odp.firma){
            tr.addClass('Av Av'+odp.top);
            cenoManiak[parseInt(tr.attr('id'))][0]= odp.top;

        }

        if(!odp.next) return;
        if(!tr.hasClass('Av')) tr.addClass('Av Av0');

        tr.find('th:last ol:first').css('min-width',function(){
           var min = 0;
            $(this).find('li:first div').each(function(){ min +=$(this).width();});
            return min+'px';
        })

    }


    if(odp.nr != "undefined" && odp.next ) {

        // Test CenoManiaka

        var id = parseInt(tr.attr('id'));
        if(cenoManiak[id] === undefined)
            cenoManiak[id]=[];

        if(cenoManiak[id][0]==1 && tr.find('.min i').length==0 ){
var min = Math.ceil(tr.find('.min').text()*100),
    ruznica = Math.ceil(cenoManiak[id][2]*100) - Math.ceil(cenoManiak[id][1]*100);

            if( min  < ruznica )
                tr.find('.min').append('<i class="fa fa-arrow-up fa-fw" title="'+( Math.ceil(min + ruznica )/100)+'"></i>');
            else if( min > ruznica )
                tr.find('.min').append('<i class="fa fa-arrow-down fa-fw" title="'+(Math.ceil(min - ruznica)/100)+'"></i>');

        } else if(tr.find('.min i').length==0 ) {

            var min = Math.ceil(tr.find('.min').text()*100),
                ruznica = Math.ceil(cenoManiak[id][1]*100) - Math.ceil(cenoManiak[id][ cenoManiak[id][0] ]*100);

                tr.find('.min').append('<i class="fa fa-arrow-down fa-fw" title="'+( Math.ceil(min + ruznica)/100)+'"></i>');


        }
    }

    if(odp.nr != -1 && odp.nr != "undefined" && odp.next) {
//alert( (odp.nr != -1) + "\n" + (odp.nr != "undefined") +"\n" + "\n\n" + odp.info());
        /*
        if(odp.nr < $('#body_s').find('tr').length && odp.nr>-1){

            var nr = (odp.nr+1);
            $('#zasysanie_f').attr('src',
                'proxy.html?ceneo='+$('#body_s').find('tr:eq('+nr+')').attr('dir')
                    +'&nr='+nr );
        }else{
            $('#zasysanie_f').attr('src','');
        }
        */
    }else{
      //  alert( (odp.nr != -1) + "\n" + (odp.nr != "undefined") +"\n" + "\n\n" + odp.info());


    }

    window.top.document.licznik = function(odp){
    idProduktu[nr]

    }

    function wstawTekst(odp){
        var string = ''+
            '<li style="overflow: hidden;">' +
            //'<div class="row">' +
            '<div style="width: 8px" class="top_top">{{top}}</div>' +
            '<div class="top_firmName">{{firma}}</div>' +
            '<div class="top_cena">{{cena}}</div>' +
            '<div class="top_cenaInfo">{{c_info}}</div>' +
            '<div class="top_opis">{{name}}</div>' +
            // '</div>' +
            '</li>';


        string = string.replace('{{nr}}', odp.nr);
        string = string.replace('{{id}}', odp.id);
        string = string.replace('{{top}}', odp.top);
        string = string.replace('{{cena}}', odp.cena);
        string = string.replace('{{c_info}}', odp.c_info);
        string = string.replace('{{name}}', odp.name);
        string = string.replace('{{img}}', odp.img);
        string = string.replace('{{firma}}', odp.firma);
        return string;
    }
};
