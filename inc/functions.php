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

/*** FUNKCJE NA Katalogach *******************************************************/

/**
 * Do Pobierania listy Katalogów
 *
 * @param $path - Katalogo do przeszukania
 * @return array array lista katalogow
 */
function cat_list($path) {$array='';
    $dir = opendir($path);
    while ($file = readdir($dir)) {
        if (is_dir($path.'/'.$file) && $file[0] != ".") {
            $array[] = $file;
        }
    }
    return $array;
} //END cat_list();

/**
 * Do sprawdzania indexów katalogów
 *
 * @param $path - Katalogo do przeszukania
 * @param string|int $nr_str - albo numer indexu albo nazwa
 * @return string|int zwraca : nr indexu albo nazwa pliku
 */
function cat_index($path, $nr_str){
    $list = cat_list($path);
    if(is_numeric($nr_str))

        return isset($list[$nr_str])? $list[$nr_str]:'';

    foreach($list as $key => $val)
        if($val == $nr_str) return $key;

}

/*** FUNKCJE NA PLIKACH *******************************************************/
/*admin
 page_delete($plik, $path = '../pages')
 */
function file_list($path) {
    $dir = opendir($path);
    while ($file = readdir($dir)) {
        if (is_file($path.'/'.$file) && $file[0] != ".") {
            $array[] = $file;
        }
    }
    return $array;
} //END files_list();

/**
 * Do sprawdzenia ile jest plików w katalogu
 *
 * @param file $path - Katalog
 * @return intiger int $count
 */
function file_count($path = '..\pages') {
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
function file_load($path, $file) {
    if(is_file($path.$file) )
        return htmlspecialchars(file_get_contents($path.$file));
    else
        error('Podany plik nie istnieje.');
} //END load_file();

/**
 * Zapisanie edytowanego pliku
 *
 * @param $path - scieszka dostępu
 * @param $file - nazwa pliku {<b>rozszerzenie wymagane</b>} *.php,*.html,*.css
 * @param $save_content - tresc html
 */
function file_save($path, $file , $save_content ) {
    if(isset($path) && isset($file) && isset($save_content) ) {
        if(file_put_contents($path.$file.'.php', htmlspecialchars_decode($save_content)))
            success('Plik został pomyślnie zaktualizowany.');
        else
            error('Nie udało się zapisać zmian. Sprawdź czy plik posiada odpowiednie uprawnienia.');
    }
} //END save_file()

/**
 * Podłączenie wybranego Pliku
 * <br><br>
 * include("$path/$go.php"||"$path/pages.php");
 *
 * @param $path - Katalog
 * @param $plik = _GET['go'] -[opcjonalny] nazwa pliku {bez <b>PHP</b> na końcu}
 */
function file_include($path, $plik=NULL) {
    if(pagesCount($path)>0){
        if(!is_null($plik) || isset($_GET['go'])){

            if(is_null($plik) && isset($_GET['go']))
                $plik = $_GET['go'];

            if(is_file("$path/$plik.php"))
                include("$path/$plik.php");
            else{
                error("<b>Taka podstrona nie istnieje</b>"); getMsg();
            }
        }else
            include("$path/pages.php");
    }else{
        error("<b>Ten Katalog jest pusty</b>"); getMsg();
    }
} //END getPage();

/**
 * Pobranie Array z pliku ,
 * zapisanego w postaci tabeli asocjacyjnej
 *
 * @param $path - pełna nazwa pliku np{'../pages/'.$_GET['file'].'.php'}
 * @return array|void - unserialize($plik)<br> - header('Location: index.php');
 */
function file_get_data($path) {
    if(file_exists($path))
        return unserialize(file_get_contents($path));
    else
        header('Location: index.php');
} //END getData();

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
function file_save_data($path, $file , $save_array ){

    if(isset($_GET['file']) || file_exists($path.'/'.$file.'.php')) {
        // zmiana nazwy
        if(isset($_GET['file']) && $_GET['file'] != $file && '.menu' != $file) {
            if(file_exists($path.'/'.$file.'.php')){
                error('Plik o nowej nazwie już istnieje.');
                $file = $_GET['file'];
            }else{
                rename($path.'/'.$_GET['file'].'.php', $path.'/'.$file.'.php');
                success('Nazwa podstrony została pomyślnie zaktualizowana.');
            }
        }
        if(file_put_contents($path.'/'.$file.'.php', serialize($save_array)))
            success('Podstrona została pomyślnie zaktualizowana.');
        else
            error('Nie udało się zapisać podstrony. Sprawdź uprawnienia pliku.');
    }elseif(!file_exists($path.'/'.$file.'.php')) {
        // Tworze nowy plik
        if(file_put_contents($path.'/'.$file.'.php', serialize($save_array))) {
            chmod($path.'/'.$file.'.php',0777);
            header('Location: index.php?go='.$file);
        }
        else
            error('Nie udało się utworzyć nowej podstrony.');
    }
    else
        error('Podstrona o takim adresie już istnieje! i nie moge jej stworzyć ani zaktualizować.');
}

/**** MENU  ********************************************************************/
/**
 * Pobiera liste meu
 *
 * @param $path
 */
function menu_load(){
    $listaMenu= array_flip(getData('../inc/.menu.php') );
    $lista = array_flip(autPhp(files_list('../pages/') ) );
    foreach($listaMenu as $menu => $val){
        if(array_key_exists($menu, $lista)){
            unset($lista[$menu]);
        }else{
            unset($listaMenu[$menu]);
        }
    }
    $i=0;
    foreach($listaMenu as $n => $val)   $listaMenu[$n]=$i++;
    $listaMenu = array_flip($listaMenu );
    ksort($listaMenu);

    if(count($lista)>0){
        foreach($lista as $l => $val) $listaMenu[]=$l;
        save_Menu( $listaMenu);
    }else
        $listaMenu= array_flip($listaMenu );

    return $listaMenu;
}

function menu_save($list){
    saveData('../inc/', '.menu', $list);
}

function menu_system($nr,$str){
    $list_str = get_Menu();
    if(isset($nr) && isset($str) && isset($list_str[$nr]) ){

        $list_nr = array_flip($list_str);

        if($str == 'up'){
            if($list_str[$nr]<=0) echo '<h1>FALSE</h1>';
            if($list_str[$nr]<=0) return false;

            $list_str[$list_nr[$list_str[$nr]-1]]=$list_str[$nr];
            $list_str[$nr]= $list_str[$nr]-1;
            $list_nr = array_flip($list_str);

            ksort($list_nr);
            save_Menu($list_nr );
        }else if($str=='down'){
            if($list_str[$nr]+1>=count($list_str)) echo '<h1>FALSE</h1>';
            if($list_str[$nr]+1>=count($list_str)) return false;

            $list_str[$list_nr[$list_str[$nr]+1]]=$list_str[$nr];
            $list_str[$nr]= $list_str[$nr]+1;
            $list_nr = array_flip($list_str);

            ksort($list_nr);
            save_Menu($list_nr );
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
function error($str){
    if(isset($_SESSION['error'])) { $_SESSION['error'].='<br>'.$str; }
    else $_SESSION['error']=$str;
}
/**
 * Zapisuje $str do sesji[success]
 *
 * @class getMsg();
 * @param string $str komunikat o powodzeniu
 */
function success($str){
    if(isset($_SESSION['success'])) { $_SESSION['success'].='<br>'.$str; }
    else $_SESSION['success']=$str;
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
    foreach($types as $type) {
        if(isset($_SESSION[$type])) {
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
function verify_login() {
    if(!isset($_SESSION['user'])) $_SESSION['user'] = 0;

    if(!$_SESSION['user'] > 0) header('Location: login.php');

    if(isset($_GET['go']) && $_GET['go']=='logout') {
        session_destroy();
        header('Location: login.php');
    }
} //END verify_login();

/*** PODSTRONY **************************************************************/

/**
 * Podmiana fragmentów tekstu,
 * wstawian wartości z tablicy do stringu <i>{{$config}}</i>
 *
 * @param string $string = ciąg znaków
 * @param array $array = tablica asocjacyjna
 * @param string $separator = [opcionalny] przedrostek np.<b>{{page:config}}</b>
 * @return string Przetworzony z Tagami
 */
function swap_string($string, $array, $separator = ''){

    foreach($array as $name => $value){
        if(is_array($value) )
            $string = swap_string($string, $value, $name.':');
        if(strpos($string,"{{".$separator.$name."}}") )
            $string = str_replace("{{".$separator.$name."}}", $value, $string);
    }
    return $string;
}

/**
 * Lista stron z katalogu pages <br>
 * pobiera ciąg znaków i wstawia w tagi słowa pobrane z plików w pętli
 *
 * @param string $pattern - kod html z " {{page:url}} "
 * @param string $path - scieszka katalogu
 * @return -tablica wszystkich plików {z ../pages} nałożona na html
 */
function pages_list($pattern, $path = '../pages') {
    $config = getData('../inc/config.php');
    $menu =  get_Menu();

    $dir = opendir($path);

    $i = 0;
    while ($file = readdir($dir)) {
        if (is_file($path.'/'.$file) && $file != "." && $file != "..") {
            $page = getData($path.'/'.$file);
            $ex = autPhp($file);
            $page['url']    =(@$config['url']?$ex.'.html':'?page='.$ex);
            $page['name']   = @$ex;
            $page['order']  = @$menu[$ex];

            $new_pattern[@$menu[@$page['name']]] = swap_string($pattern, $page, 'page:');
            unset($page);
            $i++;
        }
    }
    if($i>0) {
        ksort($new_pattern);
        $result=implode($new_pattern);
    }else $result = 'Brak podstron';
    echo $result;
} //END page_list();

/* Kolejnosc stron - max, min lub szukana<br>
 * pobiera liste plików pages
 *
 */
/**
 * Przeglada katalog PAGES i zwraca nr|nazwe pliku
 *
 * @link function_admin.php
 * @class
 * @param $type - 'MIN_MAX'|'SEARCH'
 * @param $whot - 'MAX'|'MIN'
 * @internal
 * @return a intiger 1) MIN_MAX - nr Pierwszy|Ostatni  plik<br>
 *         string 2) SEARCH - nazwa pliku nr $whot
 */
function page_order($type, $what) {
    $path = '../pages';
    $dir = opendir($path);
    $page_order = NULL;

    if($type == 'MIN_MAX') {
        while ($file = readdir($dir)) {
            if (is_file($path.'/'.$file) && $file[0] != ".") {
                $page = getData($path.'/'.$file);
                $order[] = $page['order'];
            }
        }
        if($what == 'MAX') $page_order = max($order);
        else if($what == 'MIN') $page_order = min($order);
    } //end of 'MIN_MAX' type

    else if($type == 'SEARCH') {
        while (($file = readdir($dir)) && (!$page_order)) {
            if (is_file($path.'/'.$file) && $file[0] != ".") {
                $page = getData($path.'/'.$file);

                if($what == $page['order']) $page_order = $file;
                else $page_order = false;
            }
        }
    } //end of 'SEARCH' type

    return $page_order;
} //END page_order();

/**
 * Zmiana kolejnosci podstrony
 *
 * @link  admin/pages/page.php
 * @class page_order('MIN_MAX','MAX');
 * @param string $get_a - 'up'|'down'
 * @param string $get_b - nazwa pliku
 * @internal
 * @return NULL działa na plikach
 */
function page_change_order($get_a, $get_b) {
    $path = '../pages';
    if(isset($get_a) && isset($get_b)) {
        $get_b = $get_b.'.php';
        if(is_file($path.'/'.$get_b)) {
            $page = getData($path.'/'.$get_b);

            $page['title'] = $page['title'];
            $page['content'] = $page['content'];

            if($get_a=='up') {
                if($page['order'] != 1) {
                    if(page_order('SEARCH',$page['order']-1)) {
                        $search_page_dir = $path.'/'.page_order('SEARCH',$page['order']-1);
                        $search_page = getData($search_page_dir);
                        $search_page['title'] = $search_page['title'];
                        $search_page['content'] = $search_page['content'];
                        $search_page['order'] = $page['order'];
                        file_put_contents($search_page_dir, serialize($search_page));
                    }
                    $page['order'] = $page['order'] - 1;
                    file_put_contents($path.'/'.$get_b, serialize($page));
                }
            }
            else if($get_a=='down') {
                if($page['order'] != page_order('MIN_MAX','MAX')) {
                    if(page_order('SEARCH',$page['order']+1)) {
                        $search_page_dir = $path.'/'.page_order('SEARCH',$page['order']+1);

                        $search_page = getData($search_page_dir);
                        $search_page['title'] = $search_page['title'];
                        $search_page['content'] = $search_page['content'];
                        $search_page['order'] = $page['order'];
                        file_put_contents($search_page_dir, serialize($search_page));
                    }
                    $page['order'] = $page['order'] + 1;
                    file_put_contents($path.'/'.$get_b, serialize($page));
                }
            }
        }
    }
} //END page_change_order();

/**
 * Dodanie nowej podstrony<br> nowy plik w PAGES
 *
 * @param $post - 'add_page'|null - nie istotne
 * @param array $_POST[title|file|content]
 * @return chmod($path.'/'.$file.'.php',0777);<br>header('Location: index.php?go=pages');
 */
function page_add($post) {
    $path = '../pages';
    if(isset($post)) {
        if(empty($_POST['title']) || empty($_POST['file']) || empty($_POST['content'])) error('Wypełnij wszystkie pola!');
        else {
            $save_array = array();
            $save_array['title'] = $_POST['title'];
            $save_array['content'] = addslashes($_POST['content']);
            $save_array['f-cja']= is_numeric(@$_POST['option'])?$_POST['option']:null;
            $save_array['order'] = page_order('MIN_MAX', 'MAX') + 1;
            $file = preg_replace('#[^a-zA-Z0-9_-]#', '_', strip_tags(strtolower($_POST['file'])));

            saveData($path, $file , $save_array );
        }
    }
} //END page_add();

/**
 * Edycja podstrony
 *
 * @link admin/pages/pages.php
 * @class
 * @param   $post = edit_page|null  - nie istotne
 * @internal
 * @return adres URL strony np.http://localhost/lekkicms/
 */
function page_edit($post) {
    $path = '../pages';
    if(isset($post)) {
        if(empty($_POST['title']) || empty($_POST['file']) || empty($_POST['content']))
            error('Nie możesz zostawić pustych pól!');
        else {
            $file = str_replace(" ",'_',$_POST['file']);
            if(file_exists($path.'/'.$_GET['file'].'.php')) {
                $page = getData($path.'/'.$_GET['file'].'.php');
                $page['title'] = $_POST['title'];
                $page['content'] = htmlspecialchars_decode(stripslashes($_POST['content']));
                $page['f-cja'] = intval($_POST['f-cja']);
                $page['order'] = $page['order'];

                saveData($path, $file , $page );
            }
            else
                error('Taka podstrona nie istnieje.');
        }
    }
} //END page_edit();

/**
 * Linki zalezne od operacji w Panel Administratora - Pages<br>
 * nowy|powrut
 *
 * @param $get => @$_GET['feature']
 * @return echo string link
 */
function page_links($get) {
    if(!isset($get)) echo '<a href="?go=pages&feature=new">Utwórz nową</a>';
    else echo '<a href="?go=pages">&laquo; Powrót</a>';
} //END page_links();

/*** SZABLON ***************************************************************** /
function dropUnset(&$array,$value){
$efect = $array[$value];
unset($array[$value]);
return $efect;
}
 */
/******* GENEROWANIE FORMULARZY *************/

/**
 * Do tworzenia FORMULARZY
 *
 * @param array $input_array[type|value|name|  placeholder|maxlength|id|class|style|     prefix|sufix]
 * @param string $ramka - tekst w obramuwce do form
 */
function form($input_array, $ramka=null){
    $inputs['FORM']='';
    if(isset($input_array[0]) )
        foreach($input_array as $input){
            $inputs['FORM'].=input( $input );
        }
    else if(isset($input_array['type']) )
        $inputs['FORM'].=input($input_array);
    else
        error('Nie stworzony input, $array nie zawiara danych');

    if($inputs['FORM']!='')
        return swap_string(
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
/**
 *  Do Tworzenia Inputów dla Form
 * @author KES
 * @param array $array [type|value|name| placeholder|maxlength|  id|class|style|prefix|sufix]
 * @param string $type [button|checkbox|file|hidden|image|password|radio|reset|submit|text]
 * @param string|array $value vartość pola, tablica tworzy kilka inputów
 * @param string $name nazwa pola łapana przez $_POST,
 *
 *  @param string $placeholder' tekst widoczny w pustym polu tekstowym
 *  @param int $maxlength maxymalna ilosc znaków
 *
 *  @param string $id identyfikator dla JavaSkryptu|jQuery
 *  @param string $class NameClass stylów CSS
 *  @param string $style wyjątkowe cechy wyglądu
 *  @param string $prefix przedrostek &lt;div&gt;
 *  @param string $sufix zarostek|domknięcie &lt;/div&gt;
 * @param string $spec - zaznaczenie chenchet|selected|
 */
function input($array){
    if($array['type']=='select'){
        $array['value']=input(array('type'=> 'option', 'value'=> $array['value'], 'spec'=> @$array['spec']) );
    }

//  Jeśli value jest Array => stwórz kilka inputów
    if(is_array($array['value'])&& count($array['value'])>1 ){
        $str='';        $new_array=$array;
        foreach($array['value'] as $key => $val){
            if($array['type']=='option')
                $new_array['value']= array($key=>$val);
            else
                $new_array['value']=$val;

            if(count($array['value'])>1)
                $str.= input($new_array);
        }
        return $str;
    }
    $SPEC='';

    if($array['type']=='textarea'){
        $STR_ARRAY = "<".$array['type']."{{STR_ARRAY}}>"
            .htmlspecialchars($array['value'])."</".$array['type'].">";
        unset($array['value']);

    }elseif($array['type']=='select'){
        $STR_ARRAY = "<".$array['type']."{{STR_ARRAY}}>".$array['value']."</".$array['type'].">";
        unset($array['value']);

    }elseif($array['type']=='option'){   // print_r($array);
        list($array['value'], $val)=each($array['value']);
        $STR_ARRAY = "<".$array['type']."{{STR_ARRAY}}{{SPEC}}>$val</".$array['type'].">";

    }else
        $STR_ARRAY= '<input{{STR_ARRAY}}{{SPEC}}/>';

    if( isset($array['spec']) && isset($array['value']) && $array['spec']===$array['value']){
        switch( $array['type'] ){
            case 'radio':
            case 'checkbox': $SPEC=' checked';  break;
            case 'option': $SPEC=' selected';  break;
        }

    }
    unset($array['spec']);

    $prefix = isset($array['prefix'])?$array['prefix']:'';   unset($array['prefix']);
    $sufix  = isset($array['sufix'])?$array['sufix']:'';    unset($array['sufix']);

    return $prefix. swap_string($STR_ARRAY, array('STR_ARRAY' => element($array),'SPEC'=>$SPEC ) ). $sufix;

}
/**
 * Spłaszcza Array. Tworzy ciąg znaków z tablicy.
 *
 * @param array $array - tablica $key=>$value
 * @param string $prefix -[opcjonalny] łącznik między  $key=>$value
 * @param string $sufix -[opcjonalny] zamyka|domyka ciąg
 * @return string zwraca spłaszczoną tablicę (pomija pola null|puste)
 */
function element($array,$prefix='="',$sufix='"'){
    $str='';
    foreach($array as $name => $value){
        if(!is_null($value) || $value='' )
            $str.= " $name$prefix$value$sufix";
    }
    return $str;
}

/**
 * Ladowanie pliku do edycji
 *
 * @link  admin/pages/theme.php
 * @class files_list(katalog)
 * @param $get - nazwa pliku
 * @internal
 * @return echo echo <textarea + <input
 * /
function load_file($get) {
$config = getData('../inc/config.php');
if(isset($get) && is_file('../themes/'.$config['theme'].'/'.$get)) {
echo '<textarea name="content">'.htmlspecialchars(file_get_contents('../themes/'.$config['theme'].'/'.$get)).'</textarea>';
echo '<input type="hidden" name="file" value="'.$get.'">';
} else {
$array = files_list('../themes/'.$config['theme']);
echo '<textarea name="content">'.htmlspecialchars(file_get_contents('../themes/'.$config['theme'].'/'.$array[0])).'</textarea>';
echo '<input type="hidden" name="file" value="'.$array[0].'">';
}
} //END load_file();

/**
 * Zapisanie edytowanego pliku
 *
 * @link  admin/pages/theme.php
 * @class
 * @param $post - POST sygnał zapisania
 * @internal
 * @return plik działania na plikach
 * /
function save_file($post) {
$config = getData('../inc/config.php');
if(isset($post)) {
if(file_put_contents('../themes/'.$config['theme'].'/'.$_POST['file'], htmlspecialchars_decode($_POST['content'])))
success('Plik został pomyślnie zaktualizowany.');
else
error('Nie udało się zapisać zmian. Sprawdź czy plik posiada odpowiednie uprawnienia.');
}
} //END save_file()

/*** USTAWIENIA **************************************************************/

/**
 * Zapisanie ustawien
 *
 * @param $post = potwierdzenie submit
 * @param $file = nazwa Pliku
 */
function save_Post($post,$file='config') {
    if(isset($post)) {
        //$config = getData('../inc/config.php');
        $save_conf = array();
        foreach($_POST as $name => $val){
            $save_conf[$name]=$val;
        }
        saveData('../inc/', $file, $save_conf);
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
    if($bac=='')
        return preg_replace('#admin/.*#', '', $_SERVER['HTTP_REFERER']);
    else
        return preg_replace('#&.*#', '', $_SERVER['REQUEST_URI']);
} //END pageUrl();

/********************  KES  **************************************/
/**
 * Do Usuwania końcówki .php
 *
 * @User: KES
 * @param string $str np.plik.php
 * @return string nazwa plik
 */
function autPhp($str){
    if(is_array($str) ){
        foreach($str as $key => $val){
            $str[$key]= autPhp($val);
        }
    }else{
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
function json($arra ){ if( !is_array($arra) )return;

    return json_encode($arra);
}

/**
 * prasowanie tablic ( PRINT_R )
 *
 * @param $array array tablica asocjacyjna
 * @return string zamias Print_r zwraca strong
 */
function echo_r($array){
    $str_array='';
    if(is_array($array) )
        foreach($array as $key => $val){
            if(is_array($val))
                $str_array.=$key.'=>'.echo_r($val).'<br>';
            else
                $str_array.= $key.'=>'.$val.',<br>';
        }
    return $str_array;
}