<?php
// ini_get('max_input_time'); maxymalny czas działania skryptu
ini_set('include_path', ini_get('include_path').';../');

 $meddo = $this->loadInclude('medoo.php',false);

global $db,$config;

$db = new medoo($config);

function foreachAut($array, $nr=null){ //print_r($array);
    $new_Araay = array();
    foreach($array as $ar){
        if(is_array($ar)){
            if( is_array( $nr ) ){
                $new_Araay[ $ar[ $nr[ 1 ] ] ] = $ar[ $nr[ 0 ] ];
            } else {
                $new_Araay[] = $ar[ $nr ];
            }
        } else {
            $new_Araay[] = $ar;
        }
    }
    return $new_Araay;
}
function sql($get = null ){
    global $db,$config;
    if( is_null($get) && isset($_GET['sql']) )
        $get = $_GET['sql'];

    if(isset($get) && !is_null($get)){

        switch($get)
        {

            case 'medoo':
                return $db;
                break;

            case 'csv':
                return $sql_thead = $db->select('produkty',array('kod_produktu','nazwa','cena'),'ORDER BY `id` ASC');
//                    return ( foreachAut($sql_thead, array(1,0) ) );

                break;
            case 'thead_all':
                return $sql_thead = $db->query("SHOW COLUMNS FROM produkty;")->fetchAll();
                break;
            case 'thead':
                return explode(',', $config['Tabel'] );
               /* Edytowalny w ustawieniach
                *  return $sql_thead = $db->query("SHOW COLUMNS FROM produkty;")->fetchAll();*/

                break;
            case 'tbody':
                return $db->select('produkty','*','ORDER BY `id` ASC');

                break;
            case 'tbody_all':/* EXEL */
                $list = explode(',', $config['Exel'] );
                $list = array_map(function($a){return 'produkty.'.$a;}, $list );
                $list[]='sprzedaz.*';
//print_r($list);
                return $db->select('produkty',
                array("[>]sprzedaz" => array("id" => "id") ),
                    $list
                    ,
                    'ORDER BY `produkty`.`id` ASC');
                //$db->last_query();
                break;
            case 'dodaj':

                break;
            case 'update':
                if(isset($_POST['id']) && is_numeric($_POST['id']))
                {
                    return $db->update('produkty', $_POST , array('id'=>$_POST['id']) );
                }
                else
                {
                    if(!isset($_POST['nazwa']) || trim($_POST['nazwa'])=='' )
                        return '';

                    unset($_POST['id']);
                    $max =  array_flip(
                        listEl( sql('thead_all'), 'Field' )
                    );

                    $max = array_map(function(){return '';},$max);
                    arrayConect( $max, $_POST  );
                     $ef = $db->insert('produkty', $max );
                    if($ef){

                        $ef = $db->select('produkty', 'id',array('AND'=> $_POST ));
                        echo($ef[0]); return;
                    }else{
                        echo 'Wszystkie pola wymaganie';
                    }

                   // return '';
                }
                break;
            case 'update_ceny':
                /*return*/ $db->insert('sprzedaz', $_POST );
                var_dump($db->error());
                //echo $db->last_query();
                    /*return*/ $db->update('sprzedaz', $_POST , array('id'=>$_POST['id']) );
                var_dump($db->error());


                break;
            case 'usun':var_dump($_REQUEST);
                if(isset($_POST['id']) && is_numeric($_POST['id']))
                    //return
                        $db->delete('produkty', array('id'=>$_POST['id']) );
            default:
                break;
        }
        echo $db->last_query();
    }
}
function class_dla_thead($val) {
    switch($val) {
        case 'varchar(255)':
            return 'text';
        case 'int(10) unsigned':
            return 'number';
        case 'int(11) unsigned':
            return 'kod';
        case 'float(8,2) unsigned':
            return 'cena';
    }
    return $val;
}
function sql_option($name){
    global $config;
    if ( strpos( $config['Tabel'] , $name )==true )
    {
        $config['Tabel'] = str_replace( $name , '' , $config['Tabel'] );
        $config['Tabel'] = str_replace(",," , ',' , $config['Tabel'] );
    }
    else{
        if($config['Tabel']=='')
            $config['Tabel'] = $name;
        else
            $config['Tabel'] .=','.$name;
    }
    fileMenager()->load('module/config/db.php')->save($config);
   // $config['Tabel'];

}

$_table=array();

if(isset($_GET['page']) && $_GET['page']== 'sql' ){
    if(isset($_GET['import']) )
    {
            $_table = $this->loadInclude('import.php',false);
    }
    elseif(isset($_GET['export']) )
    {
        header('Content-Type: application/json');
        header('Content-Disposition: attachment;filename="Export_BD_'.( date("d_m") ).'.json"');
        header('Cache-Control: max-age=0');
      echo  json_encode( sql('tbody') );
        exit;
    }
    else{
     //   $_table = $this->loadInclude('module/config/config.php');
    }

}elseif(isset($_GET['page']) && $_GET['page']== 'table' ){
    $_table =$config['Tabel'];
}elseif(isset($_GET['sql']) && $_GET['sql']=='hide'){
    sql_option($_POST['id']);

}
//exit;
return $_table;