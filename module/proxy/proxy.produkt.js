var tabela_firm = "table.product-offers tr:even",
	komorka_z_cenom = 	 "td:eq(4) a span.value:eq(0)",
	komorka_z_cenom_gr = "td:eq(4) a span.penny:eq(0)",
	komorka_z_img = 	 "td:eq(0) a:eq(0)",
	komorka_z_c_info 	="td:eq(3) span:eq(0)",
	komorka_z_name 		="td:eq(2) .product-name a";


$().ready(function(){ 
    $('img').error(function(){$(this).remove();});
	
    var max =$(tabela_firm).length;
	
console.log('tabela_firm ile jest firm (max)', $(tabela_firm).length);	
									  
    $(tabela_firm).each(function(top, el){
	
console.log('firma:', top);
console.log('komorka_z_cenom:', $(komorka_z_cenom, $(this)).text() );

	
        if($(komorka_z_cenom, $(this)).length==0 || max == top ){
            var odp={next:true , nr:nr_, info:function(){return "next\n max= "+max;} };
        }
        else
        {
            if($(komorka_z_img +" img", $(this)).length>0){
            var firma_ = $(komorka_z_img +" img", $(this)).attr('alt');
            }else if($(komorka_z_img +" span",$(this)).length>0){
                var firma_ = $(komorka_z_img +" span:eq(0)",$(this)).text();
            }
			else
			{ 
			alert('Nieznany wyj¹tek, zapamiêtaj ten produkt i zg³osiæ do administratora'+"\n"+ id_ + ":" + $(komorka_z_name ,$(this)).text() );
			}
			
            var odp={
                nr    : nr_,
                id    : id_,
                top   : top+1,
                cena  : $(komorka_z_cenom ,$(this)).text() +'.'+ $(komorka_z_cenom_gr ,$(this)).text(),
                c_info: $(komorka_z_c_info ,$(this)).text(),
                name  : $(komorka_z_name ,$(this)).text(),
                img   : $(komorka_z_img ,$(this)).html(),
                firma : firma_ ,
                info  : function(){
                    return ''+
                        'nr ='    +this.nr+     "\n"+
                        'id ='    +this.id+     "\n"+
                        'top ='   +this.top+    "\n"+
                        'cena ='  +this.cena+   "\n"+
                        'c_info ='+this.c_info+ "\n"+
                        'name ='  +this.name+   "\n"+
                        'img ='   +this.img+    "\n"+
                        'firma =' +this.firma+  "\n"+
                        'max ='   +max+         "\n"+
                        'next ='  +(max == top+1)
                },
                next: (max == top + 1)

            };
			console.log(odp);
        }

        if(window.top.document.wstawiacz){
            window.top.document.wstawiacz(odp);
        }
    });
    if(max===0 && window.top.document.wstawiacz){
        var odp = {
            next:true,
            nr:nr_,
            info:function(){return "next\n max= "+max;}
        };
        window.top.document.wstawiacz( odp );
    }

    if(window.parent.document.zasysacz && nr_ > -1)
       setTimeout(function(){ window.parent.document.zasysacz(nr_); },0);
});