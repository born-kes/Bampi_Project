<?php
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
    if( is_null($get) && isset($_GET['sql']) )
        $get = $_GET['sql'];

    if(isset($get) && !is_null($get)){
        $db = new medoo();
        switch($get)
        {
            case 'thead':
                return $sql_thead = $db->query("SHOW COLUMNS FROM produkty;")->fetchAll();
//                    return ( foreachAut($sql_thead, array(1,0) ) );

                break;
            case 'tbody':
                return $db->select('produkty','*','ORDER BY `id` ASC');

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
                    return $db->insert('produkty', $_POST );
                }
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
//exit;
return array('content'=>'jestem');