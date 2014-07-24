<?php
/**
 * Generowanie Menu głównego
 *
 * Wyświetla całą treść i daje możliwość dodania jej do Menu głównego
 *
 */
 $fin['css']=$this->file('menu.css')->data();
 $fin['sc'] =$this->file('menu.js' )->data();
$fin['content']=$this->file('menu.html' )->data();

 $ListaPlikow =  ( autPhp($this->lista('../pages/', false ) ) );

function menu_activ( $formaUL, &$ListaPlikow)
{

    if( ! empty($_POST)
        && isset($_POST['plik'])   && is_array($_POST['plik'])
        && isset($_POST['name'])
        && isset($_POST['przecinek'])
    )
    {
       /* foreach($_POST['name'] as $key => $el)
        {echo $el;
            $activ_menu[]=$el;
            unset($ListaPlikow[$el]);

        }*/
       // $activ_menu = array_flip($activ_menu);
//        return $GLOBALS['tpl']->swap($formaUL, $activ_menu);

        for($i=0, $j= count($_POST['plik']);$i<$j; $i++)
        {

            $activ_menu_nev[ $_POST['plik'][$i] ]= $_POST['name'][$i];
            unset($ListaPlikow[ $_POST['plik'][$i] ] );
        }


        $GLOBALS['file']->load('../cache/menu.html')->save(
            '<ul>'.
                $GLOBALS['tpl']->swap('<li><a href="{{nr0}}.html">{{nr0}}</a></li>' , $activ_menu_nev)
            .'</ul>'
        );
    }
    else
    {   $activ_menu = null;
        $activ_menu = $GLOBALS['file']->file('../cache/menu.html' )->data();
        $activ_menu = $GLOBALS['tpl']->tagsGet($activ_menu,'/>([\w-_]+)?<\/a>/');

    foreach($activ_menu as $key => $name)
    {
        if( in_array($name , $ListaPlikow) )
        {
            $plik = array_search( $name , $ListaPlikow );
            $activ_menu_nev[ $plik ]=$name;
            unset($ListaPlikow[ $plik ] );
        }
    }
    }
    return $GLOBALS['tpl']->swap($formaUL, $activ_menu_nev);
}

$formaUL = '
            <li class="a{{nr1}}">
            <div class="przenies">&nbsp;&xoplus;&nbsp;</div>
            <div class="edit">&nbsp;&cularr;&nbsp;</div>
            <div class="el"><em>{{nr0}}</em>
               <input type="hidden" value="{{nr1}}" name="plik[]">
               <input type="hidden" value="{{nr0}}" name="name[]">
               <ul class="m-ul re"><input type="hidden" value="{{nr1}}" name="przecinek[]">
               </ul>
               </div>

            </li>';

/*
if($efekt = menu_activ('<li><a href="{b}.html">{b}</a></li>', $ListaPlikow) )
{
    fileSave(',
        '<ul>'.$efekt.'</ul>'
    );
}*/


//getMsg();



$fin['content'] = $GLOBALS['tpl']->swap($fin['content'] , array(
    'menu_activ'=> menu_activ($formaUL, $ListaPlikow),
    'menu_hiden'=> $GLOBALS['tpl']->swap($formaUL, $ListaPlikow)
    ));
$GLOBALS['tpl']->html($fin);