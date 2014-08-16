<?php
$_table['html'] = $this->file('table.html')->data();
$_table['js'] = $this->file('table.js')->data().
    $this->file('dymki.js')->data().
    $this->file('colResizable.js')->data().
    $this->file('edyt.js')->data();

$_table['css'] = $this->file('table.css')->data().
    $this->file('dymki.css')->data().
    $this->file('sort.css')->data();

function td($array){  if(!is_array($array)) return '';  $str=null;
    foreach($array as $el => $val){
    $str.='<td></td>';
    }
    return $str;
}

$table = $this->load('cache/table.html' )->data();

   $ef= $this->loadInclude("module/sql/sql.php");

$thead_sql = sql('thead_all');
$thead_list = listEl($thead_sql,'Field');
$ef = explode(',',$ef);
foreach($ef as $key => $val){
    unset( $thead_list[ array_search( $val, $thead_list ) ] );
}
$class_hidde='';
foreach($thead_list as $key => $val){
    $class_hidde.='td'.($key+1).'h ';
}
if($class_hidde!=''){
    $_table['js'] .='$().ready(function(){$("#content").addClass("'.$class_hidde.'");});';

}

$fixed_thead = swap( '<div class="CRG">{{Field}}</div>'."\n" , $thead_sql  ,'class_dla_thead');
$thead = swap( '<td class="{{Type}}">{{Field}}</td>'."\n" ,  $thead_sql ,'class_dla_thead');
$tbody = swap( '<td >{{{{Field}}}}</td>'."\n" ,  $thead_sql );
$table = swap(
    $_table['html'],
    array(
        'fixed_thead' => $fixed_thead,
        'thead' => $thead,
        'tbody' => swap('<tr id="{{id}}" dir="{{kod_ceneo}}">'.$tbody.'</tr>', sql('tbody') ),
        'tfoot'=> '<tr id="tfoot"><th class="ui-widget ui-state-default ui-corner-all ui-button-text-only">Dodaj Nowy</th>'.td( sql('thead') ).'</tr>'
    )
);



if(true)
{
    $table_a= array(
        'content'=>$table,
        'title'=> 'tabela produktów',
       // 'js'=> $_table['js'],
        'css'=> '
        /* colResizable in Table.php */
.dotted, .CRG{
    background-image: url("j/colResizable/dotted.png");
    background-repeat: repeat-y;
}

.grip {
    cursor: e-resize;
    height: 30px;
    margin-left: -5px;
    margin-top: -3px;
    position: relative;
    width: 20px;
    z-index: 88;
}
#content {

    left: 0;
    padding-left: 10px;
    padding-right: 10px;
    position: absolute;
}
@import url("i/jquery_ui.css");
        ',
        'head'=>''.
            '<script src="j/colResizable/jquery.js" ></script>'.
            '<script src="i/jquery-ui.min.js" ></script>'.
            '<script src="j/colResizable/colResizable-1.3.min.js" ></script>'.
//            '<script src="j/colResizable/colResizable-1.3.source.js" ></script>'.
           // '<script type="application/javascript" src="j/jquery-1.10.2.min.js" ></script>'.
           // '<script type="application/javascript" src="j/sort/jquery-latest.js" ></script>'.
            '<script type="application/javascript" src="j/sort/jquery.tablesorter.min.js" ></script>'
    );
    $_table['js'] = '  var data = '.json_encode( sql('tbody')).';';
   //     json( );
   arrayConect($_table , $table_a );

//$this->load('cache/table.html')->save($table_a['content']);


    arrayConect($_table , $this->loadInclude("module/zasysanie/zasysanie.php") );
    return  $_table;
}

