<?php
/**
 * FUNKCJE PODSTAWOWE
 * SŁOWNICZEK
 * cat  - Katalog
 * path - Ścierzka
 * file - Plik
 * page - Strona / Kartka
 * swap - podmiana
 *
 * list - Array
 * Count- Count(array) - suma
 */
define('BR', "\n<br>\n");
$inc;

/**** MENU  ********************************************************************/
/**
 * Pobiera liste elementow menu
 *
 * @return array tablica elementow menu
 */
function menuLoad() {
    $listaMenu = fileLoadData('../inc/.menu.php');
    $listaMenu = array_flip($listaMenu);
    $lista = autPhp(fileList('../pages/') ) ;
    $lista = array_flip($lista);

    foreach ($listaMenu as $menu => $val) {
        if (array_key_exists($menu, $lista) ) {
            unset($lista[$menu]);
        } else {
            unset($listaMenu[$menu]);
        }
    }
    $i=0;

    foreach ($listaMenu as $n => $val) {
        $listaMenu[$n]=$i++;
    }
    $listaMenu = array_flip($listaMenu);
    ksort($listaMenu);

    if (count($lista)>0) {
        foreach($lista as $l => $val) $listaMenu[]=$l;
        menuSave($listaMenu);
    } else {
        $listaMenu= array_flip($listaMenu );
    }

    return $listaMenu;
}

function menuSave($list) {
    filesaveData('../inc/', '.menu', $list);
}

function menuSystem($nr, $str) {
    $list_str = menuLoad();
    if (isset($nr) && isset($str) && isset($list_str[$nr]) ) {

        $list_nr = array_flip($list_str);

        if ($str == 'up') {
            if($list_str[$nr]<=0) echo '<h1>FALSE</h1>';
            if($list_str[$nr]<=0) return false;

            $list_str[$list_nr[$list_str[$nr]-1]]=$list_str[$nr];
            $list_str[$nr]= $list_str[$nr]-1;
            $list_nr = array_flip($list_str);

            ksort($list_nr);
            menuSave($list_nr);
        } elseif ($str=='down') {
            if($list_str[$nr]+1>=count($list_str)) echo '<h1>FALSE</h1>';
            if($list_str[$nr]+1>=count($list_str)) return false;

            $list_str[$list_nr[$list_str[$nr]+1]]=$list_str[$nr];
            $list_str[$nr]= $list_str[$nr]+1;
            $list_nr = array_flip($list_str);

            ksort($list_nr);
            menuSave($list_nr);
        }
    }
}

/*** FUNKCJE KOMENTOWANIA *******************************************************/

/**
 * Zapisuje $str do sesji[error]
 *
 * @class getMsg();
 * @param string $str komunikat o błędzie
 */
function error($str) {
    if (isset($_SESSION['error']) ) {
        $_SESSION['error'].='<br>'.$str;
    } else {
        $_SESSION['error']=$str;
    }
}

/**
 * Zapisuje $str do sesji[success]
 *
 * @class getMsg();
 * @param string $str komunikat o powodzeniu
 */
function success($str){
    if (isset($_SESSION['success']) ) {
        $_SESSION['success'].='<br>'.$str;
    } else {
        $_SESSION['success']=$str;
    }
}

/**
 * Wyswietlenie wiadomosci systemowych <br>
 * informacja o zmiania / aktualizacji
 *
 * @see admin/pages.php
 * @class
 * @param $string $_SESSION[success|error]
 * @internal
 * @return {none}
 */
function getMsg() {
    $types = array('success', 'error');

    foreach ($types as $type) {
        if (isset($_SESSION[$type])) {
            echo '<div class="msg '.$type.'">'.$_SESSION[$type].'</div>';
            unset($_SESSION[$type]);
        }
    }
}

/*** LOGOWANIE **************************************************************/

/**
 * Weryfikacja uzytkownika
 *
 * @link  admin/index.php
 * @class
 * @param $_SESSION['user']
 * @internal
 * @return 1) session_destroy(); header('Location: login.php'); <br> 2) brak reakcji
 */
function verifyLogin() {

    if (! isset($_SESSION['user']) ){
        $_SESSION['user'] = 0;
    }

    if (! $_SESSION['user'] > 0) {
        include('login.php');exit();
    }

    if(isset($_GET['go']) && $_GET['go']=='logout') {
        session_destroy();
        header('Location: index.php');
        /* problemy z htaccess
        session_destroy();
        header('Location: login.php');
    */}
}
/*function verifyLogin() {
    if (! isset($_SESSION['user']) ){
        $_SESSION['user'] = 0;
    }

    if (! $_SESSION['user'] > 0) {
        header('Location: login.php');
    }

    if(isset($_GET['go']) && $_GET['go']=='logout') {
        session_destroy();
        header('Location: login.php');
    }
}*/

/*** SZABLON *****************************************************************/
/******* GENEROWANIE FORMULARZY *************/

/**
 *  Do Tworzenia Inputów dla Form <br>
 * <b>$prefix <br>&lt;input type="" name="" value=""
 * placeholder="" maxlength=""
 * id="" class="" style=""
 * spec &gt;<br> $sufix </b>
 *
 * @author KES
 * @param array $array<b>[type|value|name| placeholder|maxlength|  id|class|style|prefix|sufix]</b>
 * @param _
 * @param string $prefix $array<b>[$prefix]</b>=  przedrostek &lt;div&gt;
 * @param string $sufix $array<b>[$sufix]</b>= zarostek|domknięcie &lt;/div&gt;
 * @param _
 *
 * @param string $type $array<b>[type]</b>= [button|checkbox|file|hidden|image|password|radio|reset|submit|text]
 * @param string|array $value $array<b>[type]</b>= vartość pola, tablica tworzy kilka inputów
 * @param string $name $array<b>[$name]</b>= nazwa pola łapana przez $_POST,
 * @param _
 *
 * @param string $id $array<b>[$id]</b>= identyfikator dla JavaSkryptu|jQuery
 * @param string $class $array<b>[$class]</b>= NameClass stylów CSS
 * @param string $style $array<b>[$style]</b>= wyjątkowe cechy wyglądu
 * @param string $spec  $array<b>[$spec]</b>= zaznaczenie chenchet|selected|
 * @param _
 *
 * @param string $placeholder' $array<b>[$placeholder]</b>= tekst widoczny w pustym polu tekstowym
 * @param int $maxlength $array<b>[$maxlength]</b>= maxymalna ilosc znaków
 * @param string $onclick =
 */
function input($array) {
    if ($array['type']=='select') {
        $array['value']=input(array('type'=> 'option', 'value'=> $array['value'], 'spec'=> @$array['spec']) );
    }

//  Jeśli value jest Array => stwórz kilka inputów
    if (is_array($array['value'])&& count($array['value'])>1 ) {
        $str='';        $new_array=$array;
        foreach($array['value'] as $key => $val){
            if ($array['type']=='option') {
                $new_array['value']= array($key=>$val);
            } else {
                $new_array['value']=$val;
            }

            if (count($array['value'])>1) {
                $str.= input($new_array);
            }
        }
        return $str;
    }
    $SPEC='';

    if ($array['type']=='textarea') {
        $STR_ARRAY = "<".$array['type']." {{STR_ARRAY}}>"
            .htmlspecialchars($array['value'])."</".$array['type'].">";
        unset($array['value']);

    } elseif ($array['type']=='select') {
        $STR_ARRAY = "<".$array['type']." {{STR_ARRAY}}>".$array['value']."</".$array['type'].">";
        unset($array['value']);

    } elseif ($array['type']=='option') {   // print_r($array);
        list($array['value'], $val) = each($array['value']);
        $STR_ARRAY = "<".$array['type']." {{STR_ARRAY}}{{SPEC}}>$val</".$array['type'].">";

    } else {
        $STR_ARRAY= '<input {{STR_ARRAY}}{{SPEC}}/>';
    }

    if ( isset($array['spec']) && isset($array['value']) && $array['spec']===$array['value']) {
        switch ( $array['type'] ) {
            case 'radio':
            case 'checkbox': $SPEC=' checked';  break;
            case 'option': $SPEC=' selected'; break;
        }
    }
    if ($array['type']=='option'){unset($array['type']); }
        unset($array['spec']);

    $prefix = isset($array['legend'])?'<legend>'.$array['legend'].'</legend>':'';   unset($array['legend']);
    $prefix .= isset($array['prefix'])?$array['prefix']:'';   unset($array['prefix']);
    $sufix  = isset($array['sufix'])?$array['sufix']:'';    unset($array['sufix']);

    return $prefix. stringSwap($STR_ARRAY, array('STR_ARRAY' => element($array),'SPEC'=>$SPEC ) ). $sufix;

}

function stringSwapArray($string, $separator , $array) {
    $end_String='';
    if(is_array($array) ){
        if(is_array($separator)) {
            foreach($array as $element => $val){
                if(is_array($val)&& count($val)>1 ){
                    $end_String .= stringSwapArray($string, $separator , $val);
                }else{
                    $end_String .= str_replace($separator[1], $val,
                        str_replace($separator[0], $element, $string)
                    );
                }
            }
        } else {
            foreach($array as $element){
                $end_String .= str_replace($separator, $element, $string);
            }
        }

        return $end_String;
    }

}

/**
 * Podmiana fragmentów tekstu,
 * wstawian wartości z tablicy do stringu <i>{{$config}}</i>
 *
 * @param string $string = ciąg znaków - kod strony
 * @param array $array = tablica asocjacyjna
 * @param string $separator = [opcionalny] przedrostek np.<b>{{page:config}}</b>
 * @return string Przetworzony z Tagami
 */
function stringSwap($string, $array, $separator = '') {
    if(is_array($array)) {
        foreach ($array as $name => $value) {
            if (is_array($value) ) {
                $string = stringSwap($string, $value, $name.':');
            }
            if (strpos($string, "{{".$separator.$name."}}") ) {
                $string = str_replace("{{".$separator.$name."}}", trim($value), trim($string) );
            }
        }
    }
    return $string;
}
/*
/**
 * Spłaszcza Array. Tworzy ciąg znaków z tablicy.
 *
 * @param array $array - tablica $key=>$value
 * @param string $prefix -[opcjonalny] łącznik między  $key=>$value
 * @param string $sufix -[opcjonalny] zamyka|domyka ciąg
 * @return string zwraca spłaszczoną tablicę (pomija pola null|puste)
 */
function element($array, $prefix='="', $sufix='"') {
    $str='';

    foreach ($array as $name => $value) {
        if (! is_null($value) || $value='' ){
            $str.= " $name$prefix$value$sufix";
        }
    }
    return $str;
}

/**
 * Do tworzenia FORMULARZY
 *
 * @param array $input_array[type|value|name|  placeholder|maxlength|id|class|style|     prefix|sufix]
 * @param string $ramka - tekst w obramuwce do form
 */
function form($input_array, $ramka=null) {
    $inputs['FORM']='';
    if (isset($input_array[0]) ){
        foreach($input_array as $input){
            $inputs['FORM'].=input( $input );
        }
    } elseif (isset($input_array['type']) ){
        $inputs['FORM'].=input($input_array);
    } else {
        error('Nie stworzony input, $array nie zawiara danych');
    }

    if ($inputs['FORM']!=''){
        return stringSwap(
            '<form action="" method="post">'.
            (!is_null($ramka)?'
        <fieldset>
            <legend>'.$ramka.'</legend>
                {{FORM}}
        </fieldset>'
                :
                '{{FORM}}').
            '</form>',
            $inputs);
    }
}

/**
 * @param array $array =
 * [ <b>thead</b> :array | <b>tbody</b> :array | <b>id</b> :string | <b>style</b> :string | <b>class</b> :string ]
 * @return string tablica;
 */
function table($array) {
    if(is_array($array) ) {
        if(isset($array['thead'])){
          $thead =  '<thead>'.tableGenerator($array['thead']).'</thead>';
        }
        if(isset($array['tbody'])) {
            $tbody = '<tbody>'.tableGenerator($array['tbody']).'</tbody>';
        }
        if(isset($array['id']) || isset($array['style']) || isset($array['class']) ){

            return '<table'.
            (isset($array['id'])?' id="'.$array['id'].'"':'').
            (isset($array['style'])?' style="'.$array['style'].'"':'').
            (isset($array['class'])?' class="'.$array['id'].'"':'') .   '>'.
            $thead.
            $tbody.
            '</table>';
        }

    }
}

/**
 * @param array $array = (array = tr (td1,[...] tdn), array2 = tr2(tdn), array n => (td) )
 * @param $_
 * @param $array + [ id | style | class ]
 * @return string
 */
function tableGenerator($array) {
    if(is_array($array) ){
        $str='';
        $is_tr = true;
        if(isset($array['id']) || isset($array['style']) || isset($array['class'])){
          $id=  (isset($array['id'])?' id="'.$array['id'].'"':'').
            (isset($array['style'])?' style="'.$array['style'].'"':'').
            (isset($array['class'])?' class="'.$array['class'].'"':'');
            unset($array['id']);
            unset($array['style']);
            unset($array['class']);
        }

        foreach($array as $element => $val){
            if(is_array($val)){
                $is_tr = false;
                $str .= tableGenerator($val);
            }elseif(!is_numeric($element)){
                $str .= '<td id="'.$element.'">'. $val.'</td>';
            } else {
                $str .= '<td>'. $val.'</td>';
            }
        }
        if($is_tr){
            $str = '<tr'.(isset($id)?$id:'').'>'.$str.'</tr>';
        }
        return $str;
    }
}
/*** USTAWIENIA **************************************************************/

/**
 * Zapisanie ustawien
 *
 * @param $file = nazwa Pliku
 * @param $post = potwierdzenie submit
 */
function postSave($file='config', $post) {
    if(isset($post) ) {
        $save_conf = array();
        foreach ($post as $name => $val) {
            $save_conf[$name]=$val;
        }
        fileSaveData('../inc/'.$file , $save_conf);
    }
}

/*** INNE *****************************************************************/

/**
 * Adres strony wlasciwej
 *
 * @see  nieznaleziono
 * @class
 * @param
 * @internal
 * @return adres URL strony np.http://localhost/lekkicms/
 */
function pageUrl($bac='') {
    if($bac==''){
        return preg_replace('#admin/.*#', '', $_SERVER['HTTP_REFERER']);
    } else {
        return preg_replace('#&.*#', '', $_SERVER['REQUEST_URI']);
    }
}

/********************  KES  **************************************/
/**
 * Do Usuwania końcówki .php
 *
 * @User: KES
 * @param string $str np.plik.php
 * @return string nazwa plik
 */
function autPhp($str) {
    if (is_array($str) ) {
        foreach ($str as $key => $val) {
            $str[$key]= autPhp($val);
        }
    } else {
        $str=str_replace('.php','',$str);
    }
    return $str;
}

/**
 * Zamienia Array na ciąg JSON
 *
 * @param $arra tablica dane do zamiany na ciag json
 * @return string jaon wydrukowany ciag w postaci json
 */
function json($arra ) {
    if(! is_array($arra) ){
        return;
    }

    return json_encode($arra);
}

/**
 * prasowanie tablic ( PRINT_R )
 *
 * @param $array array tablica asocjacyjna
 * @return string zamias Print_r zwraca strong
 */
function echo_r($array) {
    $str_array='';
    if (is_array($array) ) {
        foreach ($array as $key => $val) {
            if (is_array($val)) {
                $str_array.=$key.'=>'.echo_r($val).'<br>';
            } else {
                $str_array.= $key.'=>'.$val.',<br>';
            }
        }
    }
    return $str_array;
}

/**
 * Łączy tablice ze sobą
 *
 * @param array $arrayGlobal - do tego dodawany jest ten drugi
 * @param array $arrayConect - ten jest dodawany do arrayGlobal
 */
function arrayConect(&$arrayGlobal, $arrayConect=null){
    if(is_array($arrayGlobal) && is_array($arrayConect)){
        foreach($arrayConect as $key => $value){
            @$arrayGlobal[$key].= ' '.$value;
        }
    }
    // return $arrayGlobal;
}
/**
SORT_REGULAR - porównuj elementy normalnie (nie zmienia typów)  0
SORT_NUMERIC - porównuj elementy jako liczby                    1
SORT_STRING - porównuj elementy jako ciągi tekstowe             2
 * Function                                                     3
 * @param $effect TYPE_NAME
 */
function listEl($arra, $oper = SORT_NUMERIC, $effect=0)
{
    switch($oper)
    {
        case '0':
            foreach($arra as $key => $val){
            $new[]=$val;
            }
            return $new;
            break;
        case 1:
            $new = array();
            foreach($arra as $key => $val){
                if(is_array($val[$effect]))
                    $val[$effect] = listEl($val[$effect], $oper , $effect);
                $new[]=$val[$effect];
            }
            return $new;
            break;
        case 2:
            return 2;
            break;

        default:

            if( is_string($oper) ){
                $new = array();
                foreach($arra as $key => $val){
                    if(is_array($val[$oper]))
                        $val[$oper] = listEl($val[$oper], $oper , $effect);
                    $new[]=$val[$oper];
                }
                return $new;
            }else
            if(!is_numeric($effect) ){
                if(function_exists($effect)){

                    /** @var $effect TYPE_NAME nazwaFunkcji  */

                   $new = @$effect($arra);
                }
            }
            break;

    }
}
function aut($str, $el = '.php'){
    return str_replace($el,' ', $str );
}

function swap($strings, $array, $function=null)
{
    if( is_null($function) ){ // TODO dobry pomysł function do podmiany
        $f_cja = function($a){return $a;};
    }else if( is_array($function) ){
        $f_cja = function($a , $function){
            foreach($function as $name_function){
                $a=$name_function(a);
            }
            return $a;
        };
    }else{
        $f_cja = $function;
    }
    if( is_array($strings) )
        $string = $strings[0];
    else
        $string = $strings;

    if(is_array($array))
    { $str=null;

        foreach ($array as $key => $value)
        {
            if( is_array($strings) ){
                $value = swap($strings[1], $value,$function);
            }
            if( is_array($value) ){
                $str .= swap($string, $value,$function);
            }


            if ( strpos( $string , "{{".$key."}}" )==true )
            {
                $string = str_replace( "{{".$key."}}" , $f_cja($value,$function) , $string );
            }
            else if ( strpos( $string , "{{nr0}}" )==true &&  strpos( $string , "{{nr1}}" )==true )
            {
                $str1 = str_replace( "{{nr0}}" , $f_cja($value,$function) , $string );
                $str .= str_replace( "{{nr1}}" ,  $f_cja($key,$function) , $str1 );
            }
            elseif ( strpos( $string , "{{nr0}}" )==true )
            {
                $str .= str_replace( "{{nr0}}" ,  $f_cja($value, $function) , $string );

            }
        }
    }
    if( !is_null($str) )
        return $str;
    return $string;
}
