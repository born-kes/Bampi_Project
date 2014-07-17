<?php

/**
 * Class fileMenager zarzadzanie plikami
 */
class templateMenager
{
    protected $szkielet;

    protected $tags=array();

    protected $box=array();

    public $active_class;
    public $active_box='body';

	public function __construct($tpl = null)
    {
        try
        {
            if( isset($tpl) && ! is_null($tpl) )
            {
                $url = $tpl;
            }
            else
            {
                $url = 'themes/default/template.html';
            }

            if( file_exists($url) )
            {
                $this->szkielet = file_get_contents($url);
            }
            else
            {
                $this->szkielet = '<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <script type="application/javascript">{{js}}</script>
 {{head}}
 </head>
    <body>
{{body}}
    <header>
{{header}}
    </header>
    <nav>{{menu}}</nav>
    <details>
{{details}}
    </details>
    <article>
{{article}}
{{content}}
    </article>
    <footer>
{{footer}}
    </footer>
</body>
</html>';

            }
            $this->tags = $this->tagsGet( $this->szkielet );
          //  $this->tags = $this->tagsFlip( $this->tags );, '/^{{([\w-_:]+)?}}/m'

            $this->box = $this->tagsGet( $this->szkielet );
            $this->box = $this->tagsFlip( $this->box );

        }
        catch (PDOException $e)
        {
         //   throw new Exception($e->getMessage());
        }
    }

    public function tags(){
        return $this->tags;
    }

    public function tagsGet( $string , $reg = '/{{([\w-_:]+)?}}/' )
    {
        preg_match_all( $reg , $string , $match );
        return $match[1];
    }

    protected function tagsFlip($array )
    {
        $array = array_flip($array);
        return   array_map(function(){return array();} , $array );
    }

    protected function stringSwap($string, $array)
    {
        $str=null;
        if(is_array($array))
        {
            foreach ($array as $key => $value)
            {
                if ( strpos( $string , "{{".$key."}}" )==true )
                {
                    $string = str_replace( "{{".$key."}}" , $this->praser( $value, $key ) , $string );
                }
                else if ( strpos( $string , "{{nr0}}" )==true ||  strpos( $string , "{{nr1}}" )==true )
                {
                        $str .= str_replace( "{{nr0}}" , $this->praser( $value, $key ) , $string );
                }
                else {
                    $str .= str_replace( "{{nr0}}" , $this->praser( $value, $key ) , $string );

                }
            }
        }
        if( ! is_null( $str ) ) return $str;
        return $string;
    }

    public function praser( $value, $key=null )
    {
        if( is_array( $value ) )
        {
            if( isset($this->tags[$key]) || !is_numeric($key) )
            {
                $str = '<div'.
                    (is_null($key)?'':" class=\"$key\"").
                        '>{{nr0}}</div>';
            }
            else
            {
                $str = "{{nr0}}";
            }

            return $this->stringSwap( $str , $value );
        }
        return trim($value);
    }

    public function html($html , $box=null , $nr=0)
    {
        if( is_numeric($box) )
        {
            if($nr==0)
                $nr=$box;
            $box=null;
        }

        $box_element = array();
        $box_element['html'][]= $html;
        $box_element['who']=$this->active_class;
        $this->cssAut();

       if( is_null($box) )
           $box=$this->active_box;

        $this->box[$box][$nr][]=$box_element;
    }

    public function box($tags)
    {
        if( isset( $this->box[$tags]) )
           $this->active_box=$tags;
        else $this->active_box='body';
        return $this;
    }

    private function cssAut()
    {
        $this->active_class = explode(' ',$this->active_class)[0];
    }

    public function css($name)
    {
        if( strpos($this->active_class , $name )!==true )
        $this->active_class .= ' '.$name;
        return $this;
    }

    protected function install($nr)
    {ksort($this->box[ $this->tags[$nr] ]);
        $str='';
        foreach($this->box[ $this->tags[$nr] ] as $key)
        {
            foreach($key as $value){
                $str .=  $this->praser( $value['html'] , $value['who']);
            }
        }
        return array($this->tags[$nr] => $str);
    }

    protected function drukowanie()
    {
        for($i=0,$c=count($this->tags); $i<$c; $i++){
            $this->szkielet = $this->stringSwap( $this->szkielet , $this->install($i) );
        }

    }

    public function __destruct()
    {
        $this->drukowanie();
        echo $this->szkielet;
        // echo $this->stringSwap( $this->szkielet , $this->box );
    }

}

$tpl = new templateMenager();
//$tpl->body['body'][0][]='nowa tresc';

$tpl->box('content');
$tpl->active_class = 'merida';
$tpl->css('atom')->html('test1',-4);
$tpl->html('test2','content',-1);
$tpl->css('test3')->html('test3','content');
$tpl->html('moze by tak jak','content');


//print_r( $tpl->box );

/*
$tpl->html('test2','content',-1);
$tpl->html('test3','content');
$tpl->css('atom')->html('test1','content',3);*/