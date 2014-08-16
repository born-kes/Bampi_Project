<?php
/**
 * Edytowanie ustawien
 */
$Option = $this->file('db.php')->data();
if(isset($_POST['clik']) ){
    $Option = $_POST;
    unset($Option['clik']);
foreach($Option as $key => $val){
    $Option[$key]=trim($val);
}
    $this->load('db.php',false)->save($Option);
}


if( is_array( $Option ) && isset($Option['database_name'] ) )
{
    $_table['content'] = $this->file('sql.html')->data();
    preg_match_all( '/{{([\w-_:]+)?}}/' , $_table['content'] , $tagi );
    $tagi = array_map(function(){return '';} , array_flip($tagi[1]) );

    arrayConect($tagi, $Option );

//print_r($Option);
    $db = new medoo($Option);
 /*   // Nie używane
    $TABLE_BD = $db->query("SHOW TABLE STATUS IN ".$Option['database_name'])->fetchAll();
    $TABLE_BD = listEl($TABLE_BD,'Name');*/


    $Tabel = $db->query("SHOW COLUMNS FROM produkty")->fetchAll();
    $Tabel = listEl($Tabel,'Field');
    unset( $Tabel[ array_search( 'id', $Tabel ) ] );
    unset( $Tabel[ array_search( 'kod_ceneo', $Tabel ) ] );
    $Tabel = listEl($Tabel,SORT_REGULAR);
//print_r($Tabel);

    $tagi['Column_list'] =
    $tagi['Tabel_list'] =
        swap('<li class="ui-widget-content">{{nr0}}</li>', $Tabel );

if(isset($Option['Tabel']) && $Option['Tabel']!='')
{
    $Table_arra = explode(',',$Option['Tabel']);
    foreach($Table_arra as $key => $val)
    {
        $Table_index[]='li:eq('.array_search( $val, $Tabel ).')';
    }
    $Table_index = implode(',',$Table_index);

    $tagi['JS'].=' $("#Tabel_list").find("'.$Table_index.'").addClass("ui-selected");';
}
if(isset($Option['Exel']) && $Option['Exel']!='')
{
    $Exel_arra = explode(',',$Option['Exel']);
    foreach($Exel_arra as $key => $val)
    {
        $Exel_index[]='li:eq('.array_search( $val, $Tabel ).')';
    }
    $Exel_index = implode(',',$Exel_index);

    $tagi['JS'].=' $("#Exel_list").find("'.$Exel_index.'").addClass("ui-selected");';
}


   // echo BR;
  //  print_r($tagi['Column_list']);

    $_table['content'] = swap($_table['content'], $tagi );


}
else
{
    $_table['content'] = $this->file('sql.html')->data();

    preg_match_all( '/{{([\w-_:]+)?}}/' , $_table['content'] , $tagi );
    $tagi = array_flip($tagi[1]);

    $_table['content'] = swap($_table['content'], $tagi , function(){return '';} );
}


return $_table;