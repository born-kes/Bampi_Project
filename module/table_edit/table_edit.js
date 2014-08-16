/**
 * Created by Monika Lukasz on 15.08.14.
 */
$().ready(function(){
/*    $( document ).tooltip({
        position: {
            my: "center bottom-20",
            at: "center top",
            using: function( position, feedback ) {
                $( this ).css( position );
                $( "<div>" )
                    .addClass( "arrow" )
                    .addClass( feedback.vertical )
                    .addClass( feedback.horizontal )
                    .appendTo( this );
            }
        }
    });*/
    var data = [];
   /*     ["", "Kia", "Nissan", "Toyota", "Honda"],
        ["2008", 10, 11, 12, 13],
        ["2009", 20, 11, 14, 13],
        ["2010", 30, 15, 12, 13]
    ];
    $('table tr').each(function(nr){
        data[nr]=[]
        $(this).find('td').each(function(v){
            data[nr][v]=$(this).text();
        });
    });
    alert(data);
    $("#footer").handsontable({
        data: data,
        startRows: 6,
        startCols: 8
    });

    function bindDumpButton() {
        $('body').on('click', 'button[name=dump]', function () {
            var dump = $(this).data('dump');
            var $container = $(dump);
            console.log('data of ' + dump, $container.handsontable('getData'));
        });
    }
    bindDumpButton();*/
        function changeToTable(that) {
            var tbl = $("table.fortune500");
            tbl.css("display", "");
            $("#grid_table").pqGrid("destroy");
            $(that).val("Change Table to Grid");
        }
        function changeToGrid(that) {
            var tbl = $("#table_s");
            var obj = $.paramquery.tableToArray(tbl);
            var newObj = {  width: 900, height: 460, sortIndx: 0,
                hoverMode: 'cell' ,
                selectionModel: { type: 'cell' },
                editModel: { saveKey: '13' },
                resizable: true,
                columnBorders: true,
                freezeCols: 2
            };
            newObj.dataModel = { data: obj.data, rPP: 20, paging: "local" };
            newObj.colModel = obj.colModel;
            $("#content").pqGrid(newObj);
            $(that).val("Change Grid back to Table");
            tbl.css("display", "none");
        }
        //toggle removed from $ 1.9
  //      $("#pq-grid-table-btn").button().click(function () {
            if ($("#table_s").hasClass('pq-grid')) {
                changeToTable(this);
            }
            else{
                changeToGrid(this);
            }
 //       });
       // var $grid = $("#footer").pqGrid(obj);
});