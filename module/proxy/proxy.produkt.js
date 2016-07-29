
$().ready(function(){
    $('img').error(function(){$(this).remove();});
    var max =$("table.product-offers tr:even").length;
										  
    $("table.product-offers tr:even").each(function(top, el){
	
        if($("td:eq(3) a span.value:eq(0)", $(this)).length==0 || max == top ){
            var odp={next:true , nr:nr_, info:function(){return "next\n max= "+max;} };
        }
        else
        {
            if($("td:eq(0) a:eq(0) img",$(this)).length>0){
            var firma_ = $("td:eq(0) a:eq(0) img:eq(0)",$(this)).attr('alt');
            }else if($("td:eq(0) a:eq(0) span",$(this)).length>0){
                var firma_ = $("td:eq(0) a:eq(0) span:eq(0)",$(this)).text();
            }
			else
			{ 
			alert('Nieznany wyj¹tek, zapamiêtaj ten produkt i zg³oœæ do administratora');
			}
			
            var odp={
                nr    : nr_,
                id    : id_,
                top   : top+1,
                cena  : $("td:eq(4) a span.value:eq(0)",$(this)).text()+'.'+$("td:eq(4) a span.penny:eq(0)",$(this)).text(),
                c_info: $("td:eq(4) span:eq(0)",$(this)).text(),
                name  : $("td:eq(3) .product-name a",$(this)).text(),
                img   : $("td:eq(0) a:eq(0)",$(this)).html(),
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