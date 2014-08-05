/*  table/colResizable.js */

var colResiza = true;
function colResizable(){
     var onSampleResized = function(e){
        var table = $(e.currentTarget); //reference to the resized table
        var columns = $(e.currentTarget).find("tbody td");
     };

   var colResizable =  $("table").colResizable({
        liveDrag:true,
        gripInnerHtml:"<div class='grip'></div>",
        draggingClass:"dragging",
       onDrag:onSampleResized,
        onResize:onSampleResized
    });
    colResiza=false;
}
/* END table/colResizable.js */
