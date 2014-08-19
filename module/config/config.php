<?php
/**
 * Edytowanie ustawien
 */
global $config,$db;
if(!isset($config) || is_null($config) )
$config = $this->file('db.php',false)->data();
$_table['html']=$this->file('config.html',false)->data();

if(isset($_POST['clik']) ){
    $config = $_POST;
    unset($config['clik']);
    foreach($config as $key => $val){
       $config[$key]=trim($val);
    }
    $this->load('db.php',false)->save($config);
}

$db_in = $this->loadInclude("module/sql/sql.php");


if( is_array( $config ) && isset($config['database_name'] ) && $db->conect )
{// Jest połączenie -
    $_table['content'] = $_table['html'];

    /**
     * Tworzy listę Tagów ze ścieszki
     */
    preg_match_all( '/{{([\w-_:]+)?}}/' , $_table['content'] , $tagi );
    $tagi = array_map(function(){return '';} , array_flip($tagi[1]) );

    arrayConect($tagi, $config );

    $Tabel_sql = $db->query("SHOW COLUMNS FROM produkty");

 if($Tabel_sql){

     $Tabel_sql = $Tabel_sql->fetchAll();
     $Tabel = listEl($Tabel_sql,'Field');

     // id i kod_cenneo zawsze ukryte
    unset( $Tabel[ array_search( 'id', $Tabel ) ] );
    unset( $Tabel[ array_search( 'kod_ceneo', $Tabel ) ] );
    $Tabel = listEl($Tabel,SORT_REGULAR);


    $tagi['Column_list'] =
    $tagi['Tabel_list'] =
        swap('<li class="ui-widget-content">{{nr0}}</li>', $Tabel );

    if(isset($config['Tabel']) && $config['Tabel']!='')
    {
        $Table_arra = explode(',',$config['Tabel']);
        foreach($Table_arra as $key => $val)
        {
            $Table_index[]='li:eq('.array_search( $val, $Tabel ).')';
        }
        $Table_index = implode(',',$Table_index);

        $tagi['JS'].=' $("#Tabel_list").find("'.$Table_index.'").addClass("ui-selected");';
    }

    if(isset($config['Exel']) && $config['Exel']!='')
    {
        $Exel_arra = explode(',',$config['Exel']);
        foreach($Exel_arra as $key => $val)
        {
            $Exel_index[]='li:eq('.array_search( $val, $Tabel ).')';
        }
        $Exel_index = implode(',',$Exel_index);

        $tagi['JS'].=' $("#Exel_list").find("'.$Exel_index.'").addClass("ui-selected");';
    }
//
//    // echo BR;
//    //  print_r($tagi['Column_list']);
 }else{

     $tagi['komunikat'].=' tablica w bazie danych nie istnieje ';
     $tagi['JS'].=' $("#kolumny").hide() ';
 }

    $_table['content'] = swap($_table['content'], $tagi );


}
else
{

    preg_match_all( '/{{([\w-_:]+)?}}/' , $_table['html'] , $tagi );
    $tagi = array_map(function(){return '';} , array_flip($tagi[1]) );
    $tagi['JS']=" $('#kolumny').hide(); ";
   // var_dump($tagi);
    arrayConect($tagi, $config );

    $tagi['komunikat'].=' Brak połączenia z Bazą - nieznana nazwa ';
    $_table['content'] = swap( $_table['html'], $tagi);


/*/
    preg_match_all( '/{{([\w-_:]+)?}}/' , $_table['content'] , $tagi );
    $tagi = array_flip($tagi[1]);

    $_table['content'] = swap($_table['content'], $tagi , function(){return '';} );*/

}


return $_table;