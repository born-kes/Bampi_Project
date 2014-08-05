$().ready(function(){
    $("a").not('#podglada').click(function(){
        var href = $(this).attr("href");
        href = href.match(/([0-9]+)/i)[0];

        if(window.top.document.id_ceneo){ window.top.document.id_ceneo(href);}
        return false;
    });
});