
$().ready(function(){
    var max =$("div.site-full-width table.product-offers tr:even").length;
    $("div.site-full-width table.product-offers tr:even").each(function(top, el){
        if($("td:eq(4) a strong:eq(0)", $(this)).length==0 || max == top ){
            var odp={next:true , nr:nr_, info:function(){return "next\n max= "+max;} };
        }
        else
        {
            var odp={
                nr    : nr_,
                id    : id_,
                top   : top+1,
                cena  : $("td:eq(4) a strong:eq(0)",$(this)).html(),
                c_info: $("td:eq(4) span:eq(0)",$(this)).text(),
                name  : $("td:eq(3) .product-name a",$(this)).html(),
                img   : $("td:eq(0) a:eq(0)",$(this)).html(),
                firma : $("td:eq(0) a:eq(0) img:eq(0)",$(this)).attr('alt'),
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


    if(window.parent.document.zasysacz)
        window.parent.document.zasysacz(nr+1);
});