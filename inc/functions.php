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
 * @param array $array_global - do tego dodawany jest ten drugi
 * @param array $array_conect - ten jest dodawany do array_global
 */
function array_conect($array_global, $array_conect){
    if(is_array($array_global) && is_array($array_conect)){
        foreach($array_conect as $key => $value){
            $array_global[$key].= ' '.$value;
        }
    }
    return $array_global;
}

function temple($get){
    global $config, $TEMPLE;
    if(!isset($config))
    $config = fileLoad('inc/config.php');
    $Temple = fileLoad('themes/'.$config['theme'].'/template.html', false);

    if(isset($get) && !is_null($get) ) {
        $page =  fileLoad("pages/$get.php");
    }
    if(is_null($page) ) {
        $page = fileLoad('pages/home.php');
    }

    $TempleBlok = fileLoad('themes/'.$config['theme'].'/block.php');
    $TempleBlok = array_conect($TempleBlok, $page);

    $TEMPLE = array('theme'=>$Temple,'bloki'=> $TempleBlok);
}
/*
 * $Hook = array(
 *   'loading'=>'',
 *   'loading_go'=>'',
 *   'loading_validacja'=>'',
 *   'loadink_fin'=>'',
 *
 *   'weryfikation'=>'',
 *   'weryfikation_go'=>'',
 *   'weryfikation_validacja'=>'',
 *   'weryfikation_fin'=>'',
 *
 *   'conection'=>'',
 *   'conection_go'=>'',
 *   'conection_validacja'=>'',
 *   'conection_fin'=>'',
 *
 *   'final'=>'',
 *   'final_go'=>'',
 *   'final_validacja'=>'',
 *   'final_fin'=>'',
);*/
$Hook = fileLoad('inc/.hook.php');
//$Hook['loading'][]=
$TEMPLE = temple(@$_GET['page']);

$TEMPLE['bloki'] = array_conect($Temple['bloki'], $config);

$STRONA =  stringSwap($TEMPLE['theme'], $TEMPLE['bloki']);

function Loading(){
    global $Hook,$TEMPLE;
    foreach($Hook as $hak => $function){
        $function($TEMPLE['bloki']);
    }
}
