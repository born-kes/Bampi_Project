<?php
$_table['html'] = $this->file('table.html')->data();
$_table['js'] = $this->file('table.js')->data().
    $this->file('dymki.js')->data().
    $this->file('colResizable.js')->data().
    $this->file('edyt.js')->data();

$_table['css'] = $this->file('table.css')->data().
    $this->file('dymki.css')->data().
    $this->file('sort.css')->data();

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
                $str .= str_replace( "{{nr0}}" ,  $f_cja($value,$function) , $string );

            }
        }
    }
    if( !is_null($str) )
        return $str;
    return $string;
}

function td($array){  if(!is_array($array)) return '';  $str=null;
    foreach($array as $el => $val){
    $str.='<td></td>';
    }
    return $str;
}

$table = $this->load('cache/table.html' )->data();

    $this->loadInclude("module/sql/sql.php");

$thead = swap( '<td class="{{Type}}">{{Field}}</td>'."\n" ,  sql('thead') ,'class_dla_thead');
$tbody = swap( '<td >{{{{Field}}}}</td>'."\n" ,  sql('thead'));
$table = swap(
    $_table['html'],
    array(
        'thead' => $thead,
        'tbody' => swap('<tr id="{{id}}" dir="{{kod_ceneo}}">'.$tbody.'</tr>', sql('tbody') ),
        'tfoot'=> '<tr id="tfoot"><th class="ui-widget ui-state-default ui-corner-all ui-button-text-only">Dodaj Nowy</th>'.td( sql('thead') ).'</tr>'
    )
);


if(is_null($table))
{
 //$this->load('cache/table.html' )->save($_table['html']);
}
else
{
    return array(
        'content'=>$table,
        'title'=> 'tabela produktów',
        'js'=> $_table['js'],
        'css'=> $_table['css'].'
        /* colResizable */
.dotted, .CRG{
    background-image: url("j/colResizable/dotted.png");
    background-repeat: repeat-y;
}

.grip {
    cursor: e-resize;
    height: 30px;
    margin-left: -5px;
    margin-top: -3px;
    position: relative;
    width: 20px;
    z-index: 88;
}
#content {

    left: 0;
    padding-left: 10px;
    padding-right: 10px;
    position: absolute;
}
@import url("i/jquery_ui.css");
        ',
        'head'=>''.
            '<script src="j/colResizable/jquery.js" ></script>'.
            '<script src="i/jquery-ui.min.js" ></script>'.
            '<script src="j/colResizable/colResizable-1.3.min.js" ></script>'.
//            '<script src="j/colResizable/colResizable-1.3.source.js" ></script>'.
           // '<script type="application/javascript" src="j/jquery-1.10.2.min.js" ></script>'.
           // '<script type="application/javascript" src="j/sort/jquery-latest.js" ></script>'.
            '<script type="application/javascript" src="j/sort/jquery.tablesorter.min.js" ></script>'
    );
}

