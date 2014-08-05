$().ready(function(){

$('#zasysanie_b')
    .addClass("ui-widget ui-state-default ui-corner-all ui-button-text-only")
    .click(function(){
        var odp={
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

/* To już działa  */ return;
    $('#zasysanie_f').attr('src',
            'proxy.html?ceneo='+$('#body_s tr:eq(0)').attr('dir')
                +'&nr=0' );
  });
$('#body_s tr').each(function(){
 //  alert( $(this).attr('dir') );
});

});
var max_top = 5;
//var szablon_top = <div></div>;
window.top.document.wstawiacz = function(odp){
    if( odp.top <= max_top ){
        var tr = $('#body_s tr:eq('+odp.nr+')');
        var string = '<div>{{nr}}</div><div>{{c_info}}</div>';
        
        string = string.replace('{{nr}}', odp.nr);
        string = string.replace('{{id}}', odp.id);
        string = string.replace('{{top}}', odp.top);
        string = string.replace('{{cena}}', odp.cena);
        string = string.replace('{{c_info}}', odp.c_info);
        string = string.replace('{{name}}', odp.name);
        string = string.replace('{{img}}', odp.img);
        string = string.replace('{{firma}}', odp.firma);


        alert(string);

    }
};
