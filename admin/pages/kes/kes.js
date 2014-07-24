
function drop(node){
    var str='';
    if(node.get(0).nodeName==undefined)
        return;
    str+=' '+node.get(0).nodeName;
    if(node.attr('id')!=undefined)
        str+='#'+node.attr('id');
    if(node.attr('class')!=undefined)
        str+='.'+node.attr('class');
    if(node.attr('type')!=undefined)
        str+='['+node.attr('type')+']';
    if(	str!='')
        return str+' ';
    return str;
}

$(document).ready(function() {
    var i=0;

    $('#dodaj').click(function() {
        var new_div = $('<div>');
        new_div.attr('class', 'div');
        var new_label = $('<label>');
        var new_input = $('<input>');
        new_input.attr('type', extra_type);
        new_input.attr('name', 'img[]');
        var new_link = $('<a>');
        new_link.attr('href', '#');
        new_link.html('usuń');
        new_link.click(function() {
            $(this).parent('label').remove();
        });
        new_label.append(new_input);
        new_label.append(new_link);

        new_div.append(new_label);


        $(this).parents('fieldset').append(new_div);
        return false;

    });
    $('input').attr('style','width:60px;');
    $('input').autoGrowInput({
        comfortZone: 5,
        minWidth: 80,
        maxWidth: 2000
    });


    $( '*', document.body ).click(function( event ) {
        event.stopPropagation();
        var str='';
        if($( this ).parent().attr('id')!=undefined)
            str+=' #'+$( this ).parent(2).attr('id');
        else
            str+=$( this ).parent().get( 0 ).nodeName;

        str+=' '+$( this ).get( 0 ).nodeName;


//  var domElement = $( this ).get( 0 ).nodeName;
      //  alert( str +multiclick);
    });

    $( '*', document.body ).bind({
        dblclick: function(event) {
            event.stopPropagation();
            var str=	drop($( this ).parent().parent().parent().parent() )+
                drop($( this ).parent().parent().parent() )+
                drop($( this ).parent().parent() )+
                drop($( this ).parent() )+
                drop($( this ) );
            alert('str:'+str);
        },
        mouseenter: function() {
            var str=	drop($( this ).parent().parent().parent().parent() )+
                drop($( this ).parent().parent().parent() )+
                drop($( this ).parent().parent() )+
                drop($( this ).parent() )+
                drop($( this ) );

            $('#where').text(str);
        }
    });
});
(function($){
// jQuery autoGrowInput plugin by James Padolsey
// See related thread: http://stackoverflow.com/questions/931207/is-there-a-jquery-autogrow-plugin-for-text-fields

    $.fn.autoGrowInput = function(o) {
        o = $.extend({
            maxWidth: 1000,
            minWidth: 0,
            comfortZone: 70
        }, o);
        this.filter('input:text').each(function(){
            var minWidth = o.minWidth || $(this).width(),
                val = '',
                input = $(this),
                testSubject = $('<tester/>').css({
                    position: 'absolute',
                    top: -9999,
                    left: -9999,
                    width: 'auto',
                    fontSize: input.css('fontSize'),
                    fontFamily: input.css('fontFamily'),
                    fontWeight: input.css('fontWeight'),
                    letterSpacing: input.css('letterSpacing'),
                    whiteSpace: 'nowrap'
                }),
                check = function() {
                    if (val === (val = input.val())) {return;}
// Enter new content into testSubject
                    var escaped = val.replace(/&/g, '&amp;').replace(/\s/g,'&nbsp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                    testSubject.html(escaped);
// Calculate new width + whether to change
                    var testerWidth = testSubject.width(),
                        newWidth = (testerWidth + o.comfortZone) >= minWidth ? testerWidth + o.comfortZone : minWidth,
                        currentWidth = input.width(),
                        isValidWidthChange = (newWidth < currentWidth && newWidth >= minWidth)
                            || (newWidth > minWidth && newWidth < o.maxWidth);
// Animate width
                    if (isValidWidthChange) {
                        input.width(newWidth);
                    }
                };
            testSubject.insertAfter(input);
            $(this).bind('keyup keydown blur update', check);
        });
        return this;
    };
})(jQuery);
