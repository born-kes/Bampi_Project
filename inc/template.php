<?php

/**
 * Class fileMenager zarzadzanie plikami
 */
class templateMenager
{
    /**
     * themple string
     * @var string
     */
    protected $szkielet;

    /**
     * list tags in $szkielet
     * @var array
     */
    protected $tags=array();

    /**
     * tags box in $szkielet
     * @var array
     */
    protected $box=array();

    /**
     * Active className
     * @var string
     */
    public $active_class;

    /**
     * active tags box in $szkielet
     * @var string
     */
    public $active_box='body';

    public function info($var = null)
    {
        if( ! is_null($var) )
            return $this->$var;
        return array(
        'tags'=>$this->tags,
        'box'=>$this->box,
        'active_class'=>$this->active_class,
        'active_box'=>$this->active_box,
        'szkielet'=>//htmlspecialchars
        ($this->szkielet)
        );
    }

    /**
     * Loading generator this Class $szkielet and tags list
     * @param string $tpl
     */
    public function __construct($tpl = null)
    {
        try
        {
            if( is_array($tpl) )
            {
                $option = $tpl;
                $tpl = @$option['temple'];
                unset($option['temple']);
            }

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
            $this->tag = $this->tagsFlip( $this->tagsGet( $this->szkielet , '/^{{([\w-_:]+)?}}/m') );

            $this->box = $this->tagsGet( $this->szkielet );
            $this->box = $this->tagsFlip( $this->box );

            if( isset($option) )
            {
                foreach($option as $ignor_key => $array_element)
                $this->who($ignor_key)->html($array_element);

            }
        }
        catch (PDOException $e)
        {
         //   throw new Exception($e->getMessage());
        }
    }

    /**
     * return list tags $szkielet
     * @return array
     */
    public function tags(){
        return $this->tags;
    }

    /**
     * generator list tags from reg
     * @param string $string
     * @param string $reg
     *
     * @return mixed
     */
    public function tagsGet( $string , $reg = '/{{([\w-_:]+)?}}/' )
    {
        preg_match_all( $reg , $string , $match );
        return $match[1];
    }

    /** array flip
     * @param array $array
     *
     * @return array
     */
    protected function tagsFlip($array )
    {
        $array = array_flip($array);
        return   array_map(function(){return array();} , $array );
    }

    /**
     * install box in tags
     * @param string $string
     * @param array $array
     *
     * @return string
     */
    public function swap($string, $array)
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
                else if ( strpos( $string , "{{nr0}}" )==true &&  strpos( $string , "{{nr1}}" )==true )
                {
                        $str1 = str_replace( "{{nr0}}" , $this->praser( $value, $key ) , $string );
                        $str .= str_replace( "{{nr1}}" , $this->praser( $key, $key ) , $str1 );
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
        if( ! is_null( $str ) ){ return $str;}
        return $string;
    }

    public function li($array , $li='<li><a href="?go={{nr0}}">{{nr0}}</a></li>')
    {
        $str=null;

        foreach($array as $key => $value){
            $str.=$this-> swap( $li , array($key) );
        }
          return $str;
    }

    /**
     * flattens array
     * @param array|string $value
     * @param string|null $key
     *
     * @return string
     */
    protected function praser( $value, $key=null )
    {
        if( is_array( $value ) )
        {
            if(isset($this->tags[$key]) && !is_numeric($key) )
            {
                $str = '<div'.
                    (is_null($key)?'':" class=\"$key\"").
                        '>{{nr0}}</div>';
            }
            else
            {
                $str = "{{nr0}}";
            }

            return $this->swap( $str , $value );
        }
      //  if(isset($this->tag[$key]) && !is_numeric($key) )
      //      return '<div class="'.$key.'">'.trim($value).'</div>';
        return @trim($value);
    }
    public function who($name){
        $this->active_class = $name;
        return $this;
    }
    /**
     * install code html in box
     * @param string|array $html
     * @param string|int|null $box or $nr
     * @param int $nr
     */
    public function html($html , $box=null , $nr=0)
    {
        // nr moze byc drugim argumentem
        if( is_numeric($box) )
        {
            $nr=$box;
            $box=null;
        }

        // generowanie tresci HTML
        if( is_object($html) )
        {
            $html = $html(); // TODO error dla array ?$this_db['html']=''; $this->load('cache/table.html' )->save($this_db);
        }
        elseif( is_array($html) && !is_null($box) )
        {
            foreach( $html as $new_nr => $element ){
                $this->html($element , $box, $new_nr);
            }
            return $this;
        }
        elseif( is_array($html) && is_null($box) )
        {
            foreach( $html as $new_box => $element ){
                $this->html($element , $new_box);
            }
            return $this;
        }

        if( is_null($box) )
        {
            $box = $this->active_box;
        }

        $box_element = array();

        $box_element['html'][]= $html;
        $box_element['who']=$this->active_class;
        $this->cssAut();


        $this->box[$box][$nr][]=$box_element;
        return $this;
    }

    /**
     * install code html from array in box
     * @param array $array=array( 'tags'=>'value' )
     */
    public function box_multi($array=null)
    {
        if( is_array($array) )
        {
            foreach($array as $key => $value)
            {
                $this->html($value , $key);
            }
        }
    }

    /**
     * filtr tags active box
     * @param string $tags
     *
     * @return $this
     */
    public function box($tags)
    {
        if( isset( $this->box[$tags]) )
           $this->active_box=$tags;
        else $this->active_box='body';
        return $this;
    }

    /**
     * cline active class from next css
     */
    private function cssAut()
    {
        $this->active_class = explode(' ',$this->active_class);
        $this->active_class = $this->active_class[0];
    }

    /**
     * add class
     * @param string $name
     *
     * @return $this
     */
    public function css($name)
    {

//       todo problem dla (meta_classa , meta)
//        nie - to wogule działa??
//        if( strpos($this->active_class , $name )!==true )
        $this->active_class .= ' '.$name;
        return $this;
    }

    /**
     *sortuje i zwraca zawartość box
     * @param int $nr
     *
     * @return array
     */
    protected function install($nr)
    {
        ksort($this->box[ $this->tags[$nr] ]);
        $str='';
        foreach($this->box[ $this->tags[$nr] ] as $key)
        {
            foreach($key as $value){
                $str .=  $this->praser( $value['html'] , $value['who']);
            }
        }
        return array($this->tags[$nr] => $str);
    }

    /**
     * the final $szkielet generator, installed box contents in the tags $szkielet
     */
    protected function drukowanie()
    {
        for($i=0,$c=count($this->tags); $i<$c; $i++){
            $this->szkielet = $this->swap( $this->szkielet , $this->install($i) );
        }

    }

    /**
     *  echo final $szkielet
     */
    public function __destruct()
    {
        $this->drukowanie();
        echo $this->szkielet;
        // echo $this->stringSwap( $this->szkielet , $this->box );
    }

}
/*
$tpl = new templateMenager();

$tpl->box('content');
$tpl->active_class = 'meta_classa';
$tpl->css('rom')->html('test1',-4);
$tpl->html('test2','content',-1);
$tpl->css('test3')->html('test3','content');
$tpl->html('moze by tak jak','content');
*/
