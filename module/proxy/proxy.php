<?php

function get_web_page( $url, $post='category-id=&search-query=' ){
    $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => true,     //return headers in addition to content
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        CURLINFO_HEADER_OUT    => true,
        CURLOPT_SSL_VERIFYPEER => false,     // Disabled SSL Cert checks
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_COOKIE         => 'rc=igdamb4ThORF5UzypVHYFfZFDA5ZKaNGK1uVgq1QCemB03sbz6lxNEs/N7/eTpEUjcCoUAMtjYx7Tw5Izj0pAEjNYUZPLZ6xIYPJ+svlstc02RliKmqtDw==; domain=.ceneo.pl; expires=Tue, 28-Oct-2014 21:15:10 GMT; path=/ rc=igdamb4ThOQPy+qMidFn6X7jlbO5MX6uo58bz0nU8FMnILPtxD57aQH4Ra9MSjiSC4rQsiQxknkI4Enxw3Umoutip583XNQiAKBsaaURGjwuQX84wjxx6opRKRvev8978KtfGQ7rcJE0qmdTd4vVoyra6AzX7hVN; domain=.ceneo.pl; expires=Tue, 28-Oct-2014 21:15:10 GMT; path=/ pagination=30; expires=Fri, 31-Dec-9999 23:59:59 GMT; path=/ fs=et=635421831107771900&sg=8f9c598c-bced-48aa-9338-2ba0bf742d47&st=BenQ MW851UST; domain=.ceneo.pl; expires=Wed, 27-Aug-2014 20:15:10 GMT; path=/ sv2=83f1f022-b90c-4523-adcc-9da8e5bf9643; domain=.ceneo.pl; expires=Fri, 31-Dec-9999 23:59:59 GMT; path=/ userCeneo=ID=de976e60-afe3-45a1-8078-d103d69d060e&sp=7L0HYBxJliUmL23Ke39K9UrX4HShCIBgEyTYkEAQ7MGIzeaS7B1pRyMpqyqBymVWZV1mFkDM7Z28995777333nvvvfe6O51OJ/ff/z9cZmQBbPbOStrJniGAqsgfP358Hz8ifo1f89f4NX6N/5se/MTz6+GXX/t3+91+t9/w/wkAAP//&lvp=0,&lvpe=1&al=$$$&sc=1&mvv=0&nv=0; domain=.ceneo.pl; expires=Fri, 31-Dec-9999 23:59:59 GMT; path=/ cnicaf=; domain=.ceneo.pl; expires=Fri, 31-Dec-9999 23:59:59 GMT; path=/ st2=; domain=.ceneo.pl; expires=Sun, 27-Jul-2014 20:15:10 GMT; path=/'
    ,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS=>$post //'category-id=&search-query=BenQ+MW851UST'
    );

    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $rough_content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header_content = substr($rough_content, 0, $header['header_size']);
    $body_content = trim(str_replace($header_content, '', $rough_content));
    $pattern = "#Set-Cookie:\\s+(?<cookie>[^=]+=[^;]+)#m";
    preg_match_all($pattern, $header_content, $matches);
    $cookiesOut = implode("; ", $matches['cookie']);

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['headers']  = $header_content;
    $header['content'] = $body_content;
    $header['cookies'] = $cookiesOut;
    return $header;
}
//var_dump( get_web_page('http://www.ceneo.pl/search') );

function filtr_proxy($str){

    foreach($str as $wiersz){
        if(!$body && strpos($wiersz, '<body')!==false){
            $body = true;
        }elseif($body){
            if(strpos($wiersz, '</body')!==false){
                $body = false;
            }else{
                if(strpos($wiersz, '</script>')!==false){
                    $wiersz = '<!-- '.str_replace("</script>", '-->', $wiersz);
                    $war = true;
                }
                if(strpos($wiersz, '//')!==false){
                    $wiersz = str_replace("//", '/', $wiersz);
                }
                if(strpos($wiersz, '/image.ceneo.pl')!==false){
                    $wiersz = str_replace("/image.ceneo.pl/", 'http://image.ceneo.pl/', $wiersz);
                }
                if(strpos($wiersz, 'data-original')!==false){

                    $wiersz = str_replace("src", 'data-photourl', $wiersz);
                    $wiersz = str_replace("data-original", 'src', $wiersz);
                }

                if(
                    strpos($wiersz, '<script')!==false ||
                    strpos($wiersz, '</html') !==false ||
                    strpos($wiersz, '<iframe')!==false
                ) $war = false;

                // if($war) echo ($wiersz);
                if($war)
                    $page['content'] .= ($wiersz);
            }
        }
    }
    return $page['content'];
}

if(!empty($_GET['ceneo']) )
{
    $page['title'].=$_GET['ceneo'];
    $page['menu'].='<h2>zaciągane z ceneo </h2>';
    $page['content']='';
    if(is_numeric($_GET['ceneo']))
    {
        $url="http://www.ceneo.pl/".$_GET['ceneo'].";0280-0.htm";
        $page['css']=$this->file('proxy.produkt.css')  ->data();
        $page['js'] =$this->file('proxy.produkt.js')  ->data().
            // nr ? co to było ? ilosc produktów ?
            '    var nr = '.(isset($_GET['nr'])?$_GET['nr']:0).';';
    }
    else
    {
        $url = "http://www.ceneo.pl/Projektory_i_sprzet_prezentacyjny;szukaj-".$_GET['ceneo'];
        $page['css']=$this->file('proxy.szukaj.css')  ->data();
    $page['js'] =$this->file('proxy.szukaj.js')  ->data();
    }


    $page['head']='<script type="application/javascript" src="j/jquery-1.10.2.min.js" ></script>';

    $str = gzfile($url);

    if(is_array($str) && count($str)>80){
        $page['content'] = filtr_proxy($str);
    }else{
    //$url
        $ef =get_web_page('http://www.ceneo.pl/search','category-id=&search-query='.$_GET['ceneo']);

    if( isset($ef['content']) ){
        $str = explode("\n" , $ef['content'] );
        $page['content'] = filtr_proxy($str);
    }
    else
    {
        $slowa = explode(" " , $_GET['ceneo'] );
        if(count($slowa)>2){
            $nowe_slowa[]=$slowa[0];
            $nowe_slowa[]=$slowa[1];
            $slowa = implode(' ', $nowe_slowa);

            $ef =get_web_page('http://www.ceneo.pl/search','category-id=&search-query='.$slowa);

            if( isset($ef['content']) ){
                $str = explode("\n" , $ef['content'] );
                $page['content'] = filtr_proxy($str);
                if( !isset($page['content']) || is_null($page['content']) ){
                    $page['content']=''.
                    '<h2>Nic nie znaleziono! Wyniki dla '.$slowa.' </h2>'.
                    $page['content'];
                }
            }
        }
    }

    }

    if( !isset($page['content']) || is_null($page['content']) ){
        $page['js']='';
        $page['content']='<h2>Nic nie znaleziono</h2>'.
            '<h1><a href="'.$url.'" target="_blank" id="podglada">Sprawdz na CENEO</a></h1><br><br>';
        $page['css'].=' #content{padding-left: 20px;} ';
    }
    $page['content']='<h1><a href="'.$url.'" target="_blank" id="podglada">Sprawdz na CENEO</a></h1>'.$page['content'];

    return $page;

}
else
    return array('content'=>'<a href="?ceneo=25200763;0280-0.htm">produkt</a><br>
    <a href="?ceneo=;szukaj-'. urlencode($_GET['szukaj']).'">szukaj </a>');