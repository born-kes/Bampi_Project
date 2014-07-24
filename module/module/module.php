<?php
/**
 */
global $functionModule;
$functionModule[]=function(){        $list = catList('module');
    print_r(catList('module'));
    templeBloki('body', '<ul>' .

        form(array('type'=>'checkbox', 'value'=>$list, 'sufix'=>array($list)) )
       // stringSwapArray('<li><input type="checkbox" name="{val}"> {val}</li>' , '{val}', catList('module') )

        . '</ul>' );
  //  templeBloki('js', stringSwap( fileLoad('module/licznik/licznik.js', false), array('time'=>$microTimeStart) ) );
  //  templeBloki('css', "\n n{display:none;}\n" );
};

/**
 * pobiera listę włączonych modułów
 * pobiera listę wszystkich modułów
 * zamiana miejscami nazwa nrIndex
 * połączenie - duplikaty są usunięte ( ale tracę listę
 *
 * lista modułów -> jesli jest włączony daj mu checkbox
 */