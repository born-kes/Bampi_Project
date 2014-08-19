<?php
/**
 * Created by PhpStorm.
 * User: Monika Lukasz
 * Date: 15.08.14
 * Time: 01:52
 */
$table=array('head'=>'
    <script src="j/jquery-1.10.2.min.js" ></script>
    <script src="i/jquery-ui.min.js" ></script>
    <script src="j/SlickGrid/lib/jquery.event.drag-2.2.js"></script>
<script src="j/SlickGrid/slick.core.js"></script>
<script src="j/SlickGrid/slick.formatters.js"></script>
<script src="j/SlickGrid/slick.editors.js"></script>
<script src="j/SlickGrid/slick.grid.js"></script>
<script src="j/SlickGrid/plugins/slick.headermenu.js"></script>
<link rel="stylesheet" media="screen" href="i/jquery-ui.min.css" />
<link rel="stylesheet" media="screen" href="j/SlickGrid/slick.grid.css" />
<link rel="stylesheet" media="screen" href="j/SlickGrid/plugins/slick.headermenu.css" />'
);
$table['js'] =   $this->file('table_edit.js')->data();
$table['css'] =  $this->file('table_edit.css')->data();

$ef= $this->loadInclude("module/sql/sql.php");

$table['js'] =''.
 //   '  var data = '.json_encode( sql('tbody')).';'.
'var columns = [
    {id: "symbol", name: "Symbol", field: "kod_produktu", sortable: true, editor: Slick.Editors.Text },
    {id: "nazwa", name: "Nazwa Produktu", field: "nazwa", sortable: true, editor: Slick.Editors.Text },
    {id: "producent", name: "Producent", field: "producent", sortable: true, editor: Slick.Editors.Text },
    {id: "cena_k", name: "Cena Kupna", field: "cena_kupna", sortable: true },
    {id: "cena_stara", name: "Stara Cena", field: "cena_stara", sortable: true },
    {id: "notatka", name: "Notatka", field: "notatka"}
];'. $table['js'] ;
$table['content'] = '<div id="myGrid" style="width:100%;height:500px;"></div>';
return $table;