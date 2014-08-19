<?php

/**
 * Class fileMenager zarzadzanie plikami
 */
class fileMenager
{
    /**
     * @class
     * @var true|false $znacznik czy ścieżka zawiera plik
     */
    protected $znacznik;

    /**
     * @var string scieszka katalogow
     */
    protected $where;

    /**
     * @var string nazwa pliku
     */
    protected $whot;

    protected $count;

    protected $path;

    /**
     * @var array lista pobranych danych
     */
    protected $tree = array();



    /**
     * zwraca zebrane dane
     *
     * @param string $val nazwa zmiennej
     *
     * @return array|string zebrane dane
     */
    public function info($val=null)
    {
        if( is_null($val) || !isset($this->$val) )
        {
            return array(
                'where'=>$this->where,
                'whot'=>$this->whot,
                'tree'=>$this->tree,
                'count'=>$this->count,
                'znacznik'=>$this->znacznik

            );
        }
        else if( isset($this->$val) )
        {
            return $this->$val;
        }
        return '';
    }

    /**
     * Filtruje scieszke katalogi i pliki
     *
     * @param string $path scieszka drzewa katalogów
     * @return $this
     */
    public function load($path, $local = true)
    {
        $this->path = $path;

        if($local)
            $this->catalog();
        $this->file_name();

        if($this->znacznik)
            $this->path = $this->where.$this->whot;
        return $this;
    }

    /**
     * include + test istnienia
     * @param string $path scieszka drzewa katalogów
     */
    public function loadInclude($path=null, $local = true)
    {
        if ( ! is_null($path) )
            $this->load($path, $local);

        if ( isset($this->path) && is_file( $this->path ) )
        {
            return include( $this->path );
          //  return true;
        } else {
//            error("<b>Taka podstrona nie istnieje</b>");
//            getMsg();
            return false;
        }
    }

    /**
     * Izoluje katalog
     *
     * @return string|array
     */
    protected function catalog(){
        preg_match('/^((.|..)?[\w_\-]*\/)+/i', $this->path, $cat);

        $this->where = isset($cat[0])?$cat[0]:'';
        $this->znacznik = false;
      //  $cat=explode('/' , $cat[0] );
      //  $this->tree = array($cat[0]=>array($cat[1]=>array($cat[2])));
        return $this;
    }

    /**
     * Izoluje nazwę pliku
     * @return $this
     */
    protected function file_name()
    {
        $this->znacznik = false;
        preg_match('/([a-zA-Z0-9_\-]+\.+[\w]{3,4})$/i', $this->path, $file);
        if(!is_null($file[0]))
            $this->znacznik = true;

        $this->whot = $file[0];
        return $this;
    }

    /**
     * katalog domyślny ostatnia lokalizacja
     * @param $file nazwa pliku
     *
     * @return $this
     */
    public function file($file)
    {
        if ( is_file($this->where.$file) )
        {
            $this->whot = $file;
        }
        return $this;
    }

    /**
     * Tworzy liste katalogow i plikow
     *
     * @param string|null $path scieszka dostempu
     * @param int $nr numer porządkowy
     * @return array liste plikow i katalogów
     */
    public function lista($path = null , $nr = true)
	{
        if(! is_null($path) )
        {
            $this->load($path);
        }
        if(! $dir = @opendir($this->where) )
            return false;

        while ($file = readdir($dir)) {
            if ( is_file($this->where.$file) )
            {
                if( $file[0] != ".")
                {
                    if($nr)
                    {
                        $array[$file] = 'plik';
                    }
                    else
                    {
                        $array[] = $file;
                    }
                }

            }
            else if( $file[0] != ".")
                $array[$file] = array();
          //  }
        }
        natsort ($array);
        $this->tree[$this->where] =$array;
        return $array;
	}

    /**
     * Pobiera zawartosc pliku
     *
     * @return string|array zawartość pliku
     */
    public function data()
	{
        if(!isset($this->whot) || ! is_file( $this->where . $this->whot) ) return null;
        $path = $this->where . $this->whot;

        if ( (!isset($this->tree[$path]) || is_null( $this->tree[$path] ) )
            && file_exists($path) )
        {
            $this->tree[$path] = file_get_contents($path);

              if( @unserialize($this->tree[$path]) )
              {
                $this->tree[$path] = unserialize($this->tree[$path]);
              }
        }
        return $this->tree[$path];
	}

    /**
     * zlicza ilosc plikow|katalogow
     *
     * @param bool $plik wybur pliki lub katalogi
     *
     * @return $this->count ilosc w katalogu
     */
    public function count( $plik = true )
	{ $this->count = 0;

        if(! isset($this->tree[$this->where]) )
        {
            $this->lista();
        }

        if(isset($this->tree[$this->where]))
        {
            foreach($this->tree[$this->where] as $file)
            {

                if( (is_array($file) && !$plik) ||
                   (!is_array($file) &&  $plik) )
                {
                    $this->count++;
                }
            }
        }
        return $this;

	}

    public function rename($new_name)
    {
        if (is_file($this->path) )
        {
            if (file_exists($this->where . $new_name) )
            {
                error('Nie mogę zmienić nazwy. Taki Plik już istnieje.');
            }
            else
            {
                if(rename($this->path , $this->where . $new_name))
                {
                    success('Nazwa została zmieniona.');
                    return true;
                }
                else
                {
                    error('Nazwa nie została zmieniona.');
                }
            }
        }
        return false;
    }

    public function save($save_content)
    {
        $new = file_exists($this->path);
        if (isset($save_content) )
        {
            if( is_array($save_content) )
            {
                $save_content = serialize($save_content);
            }
            if(file_put_contents($this->path, $save_content) )
            {
                if(!$new)
                {
                    chmod($this->path,0777);
                }
             //   success('Plik został pomyślnie zaktualizowany.');
                unset($this->tree[$this->path]);
                return true;
            }
            else
            {
                error('Nie udało się zapisać zmian. Sprawdź czy plik posiada odpowiednie uprawnienia.');
            }
        }
        return false;
    }

    public function delete()
    {
        if( is_file( $this->path ) ) {
            if( unlink( $this->path ) )
                success( 'Wybrana podstrona została usunięta.' );
            else
                error('Nie udało się usunąć podstrony. Sprawdź uprawnienia pliku.');
        }
    }

    public function index($nr)
    {
       $lista = $this->lista();
        return $lista[$nr];
    }

    /**
     * przechwytuje wywolanie nie istniejacej metody i uruchamia jako function
     *
     * @param string $name - nazwa metody
     * @param array $pars argumenty function
     * @desc test
     *
     * @return mixed
     */
    public function __call( $name , $pars ){
        if (function_exists($name))
        {
                call_user_func_array( $name , $pars);
        }
    }


    /**
     * Dekoduje znaki na HTML
     *
     * @param $str - zakodowany ciąg
     * @return string Ciąg HTML
     */
    public function deHtml($str)
    {
        return htmlspecialchars_decode($str);
    }

    /**
     * Koduje znaki HTML
     *
     * @param $str  -   ciąg HTML
     * @return string - Zakodowany ciąg np do educji
     */
    public function enHtml($str)
    {
        return htmlspecialchars($str);
    }

}
class Class_Null
{
    public function __call( $name , $pars ){
        return $this;
    }
}