<?php

/** * FUNKCJE PODSTAWOWE ******************************************************
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
$inc;
/*** FUNKCJE NA Katalogach *******************************************************/

/**
 * Do Pobierania listy Katalogów
 *
 * @param $path - Katalogo do przeszukania
 * @return array array lista katalogow
 */
function catList($path) {
    $array = '';
    $dir = opendir($path);

    while ($file = readdir($dir)) {
        if (is_dir($path.'/'.$file) && $file[0] != ".") {
            $array[] = $file;
        }
    }
    return $array;
}

/**
 * Do sprawdzania indexów katalogów
 *
 * @param $path - Katalogo do przeszukania
 * @param string|int $nr_str - albo numer indexu albo nazwa
 * @return string|int zwraca : nr indexu albo nazwa pliku
 */
function cat_Index($path, $nr_str){
    $list = catList($path);

    if (is_numeric($nr_str)) {
        return isset ($list[$nr_str]) ? $list[$nr_str] : '' ;
    }

    foreach ($list as $key => $val) {
        if ($val == $nr_str)
            return $key;
    }
}

/*** FUNKCJE NA PLIKACH *******************************************************/
/*admin
 page_delete($plik, $path = '../pages')
 */

/**
 * Do Pobierania listy Plikow
 *
 * @param $path - scieszka do katalogu
 * @return array array lista katalogow
 */
function fileList($path) {
    $dir = opendir($path);

    while ($file = readdir($dir)) {
        if (is_file($path.'/'.$file) && $file[0] != ".") {
            $array[] = $file;
        }
    }
    return $array;
}

/**
 * Do sprawdzenia ile jest plików w katalogu
 *
 * @param string $path - Katalog
 * @return int int $count
 */
function fileCount($path = '..\pages') {
    $dir = @opendir($path);
    $count = 0;

    while ($file = @readdir($dir)) {
        if (is_file($path.'/'.$file) && $file[0] != ".") {
            $count = $count + 1;
        }
    }
    return $count;
}

/**
 * Pobieranie pliku do Edytowania
 * znaki zakodowane htmlspecialchars
 *
 * @param $path - scieszka katalogu
 * @param $file - nazwa pliku {<b>rozszerzenie wymagane</b>} *.php,*.html,*.css
 * @return string htmlspecialchars($string)
 */
function fileLoad($path, $file) {
    if (is_file($path.$file) ) {
        return file_get_contents($path.$file);
    } else {
        error('Podany plik nie istnieje.');
    }
}

/**
 * Pobranie Array z pliku ,
 * zapisanego w postaci tabeli asocjacyjnej
 *
 * @param $path - pełna nazwa pliku np{'../pages/'.$_GET['file'].'.php'}
 * @return array|void - unserialize($plik)<br> - header('Location: index.php');
 */
function fileLoadData($path) {
    global  $inc;
    if (! is_null($inc[$path]) ) {
        return $inc[$path];
    } elseif (file_exists($path)) {
        if( file_exists($path) ) {
            $inc[$path] = unserialize(file_get_contents($path));
            return $inc[$path];
        }
    }
}

/**
 * Zmienia nazwe pliku
 *
 * @param $path - pełna scieszka do pliku
 * @param $file - $_GET[file].'php'
 * @param $nev_file - nowa nazwa pliku
 * @return bool
 */
function fileReName($path, $file, $nev_file) {
    if($file === $nev_file) {
        return FALSE;
    }
    if (is_file($path.$file) ) {
        if (file_exists($path.$nev_file) ) {
            error('Nie mogę zmienić nazwy. Taki Plik już istnieje.');
            return FALSE;
        }
        if(rename($path.$file, $path.$nev_file)) {
            success('Nazwa została zmieniona.');
        } else {
            error('Nazwa nie została zmieniona.');
        }
    }
}

/**
 * Zapisywanie pliku
 *
 * @param $path - scieszka dostępu
 * @param $file - nazwa pliku {<b>rozszerzenie wymagane</b>} *.php,*.html,*.css
 * @param $path_file - scieszka + nazwa pliku + rozszerzenie *.php,*.html,*.css
 * @param $save_content - deHtml(), serialize()- tresc html deHtml przed zapisem
 */
function fileSave($path_file, $save_content) {
    $new = file_exists($path_file);
    if (isset($path_file) && isset($save_content) ) {
        if(file_put_contents($path_file, $save_content) ) {
            if(!$new) {
                chmod($path_file,0777);
            }
            success('Plik został pomyślnie zaktualizowany.');
        } else {
            error('Nie udało się zapisać zmian. Sprawdź czy plik posiada odpowiednie uprawnienia.');
        }
    }
}

/**
 * Zapisuje informacji Array do pliku
 * w postaci serializowanej tabeli asocjacyjnej
 *
 * @param $path - scieszka dostępu
 * @param $file - nazwa pliku {bez <b>PHP</b> na końcu}
 * @param $save_array - tabela do zapisania
 * @param $_GET $_GET['file'] - istnieje jesli plik jest edytowany
 * @return void
 */
function fileSaveData($path, $file, $save_array ,$new_file=null) {

    if (isset($file) || file_exists($path. $file)) {
        // zmiana nazwy
        if (isset($_GET['file']) && $_GET['file'].'php' != $file) {
            fileReName($path, $_GET['file'].'php', $file);
        }
        fileSave($path. $file, serialize($save_array) );

    } elseif (!file_exists($path.$file) ) {
        // Tworze nowy plik
        fileSave($path. $file, serialize($save_array) );
            header('Location: index.php?go='.autPhp($file) );
    } else {
        error('Podstrona o takim adresie już istnieje! i nie moge jej stworzyć ani zaktualizować.');
    }
}

/**
 * Do usuwania Plików
 *
 * @param string $plik - nazwa pliku {bez <b>PHP</b> na końcu}
 * @param string $path - nazwa katalogu
 */
function fileDelete($plik, $path = '../pages') {

    if(isset($plik)) {
        $plik .='.php';
        if(is_file($path.'/'.$plik)) {
            if(unlink($path.'/'.$plik))
                success('Wybrana podstrona została usunięta.');
            else
                error('Nie udało się usunąć podstrony. Sprawdź uprawnienia pliku.');
        }
    }
} //END page_delete();

/**
 * Podłączenie wybranego Pliku
 * <br><br>
 * include("$path/$go.php"||"$path/pages.php");
 *
 * @param $path - Katalog
 * @param $plik = _GET['go'] -[opcjonalny] nazwa pliku {bez <b>PHP</b> na końcu}
 */
function fileInclude($path, $plik=NULL) {
    if (fileCount($path)>0) {
        if (!is_null($plik) || isset($_GET['go']) ) {

            if(is_null($plik) && isset($_GET['go']) )
                $plik = $_GET['go'];

            if (is_file("$path/$plik.php") ) {
                include("$path/$plik.php");
            } else {
                error("<b>Taka podstrona nie istnieje</b>");
                getMsg();
            }
        } else {
            include("$path/pages.php");
        }
    } else {
        error('<b>Ten Katalog jest pusty</b>');
        getMsg();
    }
}

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

/*** PODSTRONY **************************************************************/

function stringSwapArray($string, $separator , $array) {
    $end_String='';
    if(is_array($array) ){
        if(is_array($separator)) {
            foreach($array as $element => $val){
                if(is_array($val)&& count($val)>1 ){
                    $end_String .=stringSwapArray($string, $separator , $val);
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

    foreach ($array as $name => $value) {
        if (is_array($value) ) {
            $string = stringSwap($string, $value, $name.':');
        }
        if (strpos($string, "{{".$separator.$name."}}") ) {
            $string = str_replace("{{".$separator.$name."}}", $value, $string);
        }
    }
    return $string;
}

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
        $STR_ARRAY = "<".$array['type']."{{STR_ARRAY}}>"
            .htmlspecialchars($array['value'])."</".$array['type'].">";
        unset($array['value']);

    } elseif ($array['type']=='select') {
        $STR_ARRAY = "<".$array['type']."{{STR_ARRAY}}>".$array['value']."</".$array['type'].">";
        unset($array['value']);

    } elseif ($array['type']=='option') {   // print_r($array);
        list($array['value'], $val)=each($array['value']);
        $STR_ARRAY = "<".$array['type']."{{STR_ARRAY}}{{SPEC}}>$val</".$array['type'].">";

    } else {
        $STR_ARRAY= '<input{{STR_ARRAY}}{{SPEC}}/>';
    }

    if ( isset($array['spec']) && isset($array['value']) && $array['spec']===$array['value']) {
        switch ( $array['type'] ) {
            case 'radio':
            case 'checkbox': $SPEC=' checked';  break;
            case 'option': $SPEC=' selected';  break;
        }

    }
    unset($array['spec']);

    $prefix = isset($array['legend'])?'<legend>'.$array['legend'].'</legend>':'';   unset($array['legend']);
    $prefix .= isset($array['prefix'])?$array['prefix']:'';   unset($array['prefix']);
    $sufix  = isset($array['sufix'])?$array['sufix']:'';    unset($array['sufix']);

    return $prefix. stringSwap($STR_ARRAY, array('STR_ARRAY' => element($array),'SPEC'=>$SPEC ) ). $sufix;

}

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
 * Dekoduje znaki na HTML
 *
 * @param $str - zakodowany ciąg
 * @return string Ciąg HTML
 */
function deHtml($str) {
    return htmlspecialchars_decode($str);
}

/**
 * Koduje znaki HTML
 *
 * @param $str  -   ciąg HTML
 * @return string - Zakodowany ciąg np do educji
 */
function enHtml($str) {
    return htmlspecialchars($str);
}