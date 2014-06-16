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

// !!!! Zastępuje 2 function admin
/**
 * Pobieranie Dane z pliku<br>
 *
 * znaki zakodowane htmlspecialchars
 *
 * @param string $path_file - scieszka katalogu + nazwa pliku {<b>rozszerzenie wymagane</b>} *.php,*.html,*.css
 * @param boolean $unserialize [true|false] = true <b>array</b>
 * @return array|string htmlspecialchars($string)
 */
function fileLoad($path_file, $unserialize = true) {
    global  $inc;
    if (! is_null($inc[$path_file]) ) {
        return $inc[$path_file];
    } elseif (file_exists($path_file)) {
        if( file_exists($path_file) ) {
            if($unserialize){
                $inc[$path_file] = unserialize(file_get_contents($path_file));
            } else {
                $inc[$path_file] = file_get_contents($path_file);
            }
            return $inc[$path_file];
        }
    }
    //  echo " plik '$path_file' nie istnieje";
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
                $string = str_replace("{{".$separator.$name."}}", $value, $string);
            }
        }
    }
    return $string;
}

/**
 * Łączy tablice ze sobą
 *
 * @param array $arrayGlobal - do tego dodawany jest ten drugi
 * @param array $arrayConect - ten jest dodawany do arrayGlobal
 */
function arrayConect(&$arrayGlobal, $arrayConect){
    if(is_array($arrayGlobal) && is_array($arrayConect)){
        foreach($arrayConect as $key => $value){
            $arrayGlobal[$key].= ' '.$value;
        }
    }
    // return $arrayGlobal;
}

/**
 * @param $get = nazwa pliku pobierana z $_GET[page]
 */
function temple($get){
    global $config, $TempleBloki, $Temple;
    if(!isset($config))
        $config = fileLoad('inc/config.php');
    $Temple = fileLoad('themes/'.$config['theme'].'/template.html', false);

    if(isset($get['page']) && !is_null($get['page']) ) {
        $page =  fileLoad("pages/".$get['page'].".php");
    }
    if(is_null($page) ) {
        $page = fileLoad('pages/home.php');
    }

    $TempleBloki = fileLoad('themes/'.$config['theme'].'/block.php');
    arrayConect($TempleBloki, $page);
}

function moduleInclude($name){
    if(file_exists("module/$name")){
        global $TempleBloki;
        if(file_exists("module/$name/$name.php")){
            include("module/$name/$name.php");
        }else {
            $TempleBloki['body'].=fileLoad("module/$name/$name.html", false);

            $TempleBloki['js'].=fileLoad("module/$name/$name.js", false);

            $TempleBloki['css'].=fileLoad("module/$name/$name.css", false);
        }
    }
    if(isset($_POST) && count($_POST)>0){
        // print_r($_POST);echo '<br>';
        moduleInclude('logowanie');
    }
}
    $Hook = array( // pobieranie danyc
        'temple'=>'_GET',
        'arrayConect'=>array('TempleBloki', 'config'),
    );
//$Hook = fileLoad('inc/.hook.php');
//print_r($Hook);


//$TEMPLE['bloki'] =


//$STRONA =  stringSwap($TEMPLE['theme'], $TEMPLE['bloki']);
    /*
    if(is_array($Hook) )
        foreach($Hook as $hak => $value){
            if(!is_null($value) )
                if( function_exists($hak) ){
                    if(is_array($value)){
                        $hak($$value[0], $$value[1]);
                    } else {
                        $hak($$value);
                    }
                }
        }*/
