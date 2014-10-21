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
        CURLOPT_COOKIE         => '	userCeneo=ID=de976e60-afe3-45a1-8078-d103d69d060e&sp=7L0HYBxJliUmL23Ke39K9UrX4HShCIBgEyTYkEAQ7MGIzeaS7B1pRyMpqyqBymVWZV1mFkDM7Z28995777333nvvvfe6O51OJ/ff/z9cZmQBbPbOStrJniGAqsgfP358Hz8ifo1f89f4NX6N/5se/MTz6+GXX/t3+91+t9/w/wkAAP//&lvp=14219391,12460056,23322222,9052918,10169841,25200763,30024103,30180459,23517155,27781987,&lvpe=1&al=$$$&sc=1&mvv=0&nv=0; domain=.ceneo.pl; expires=Fri, 31-Dec-9999 23:59:59 GMT; path=/ rc=igdamb4ThOSOTvy0o77PSn7jlbO5MX6uyO5QEAo5klgGyhBJSGF16YRl6dOak95n/LgkXzBkHkERjGdXS3P7b/l/mNUwqMPq3i9+BYyxEmwtdw6t+6SLbbZYHGQ56n+hk7YMBsrJx+hmqc6kSRMF2h6dYmqtMG2J/LgkXzBkHkERjGdXS3P7b28PSUjz/+ppnVo8N7JubJt+45WzuTF+rm7KiZEI4IIr99UflbXwLfapgA+H9uis7vM9Ry+UpzGt4yrGfHORhxgfD0UVlVaLNk/2p3kH2rVfuYpKz1XdRAIgE9ZlDxqyYMhmfaS67DYDmm/mYsfBeKvR6CBnDNyU02NltytSC8HCeRDtCEkCTfY2fEfHGLNqImc0ejuGPlPMgfFSyoZg9dUFrFnwtBvIMnTelT8AvVkjTiKdQtiR1RbtAIgvbWifMJvtPK7sH4lp; domain=.ceneo.pl; expires=Fri, 12-Dec-2014 11:49:45 GMT; path=/ rc=igdamb4ThOSOTvy0o77PSn7jlbO5MX6uyO5QEAo5klgGyhBJSGF16YRl6dOak95n/LgkXzBkHkERjGdXS3P7b/l/mNUwqMPq3i9+BYyxEmwtdw6t+6SLbbZYHGQ56n+hk7YMBsrJx+hmqc6kSRMF2h6dYmqtMG2J/LgkXzBkHkERjGdXS3P7b28PSUjz/+ppnVo8N7JubJt+45WzuTF+rm7KiZEI4IIr99UflbXwLfapgA+H9uis7vM9Ry+UpzGt4yrGfHORhxgfD0UVlVaLNk/2p3kH2rVfuYpKz1XdRAIgE9ZlDxqyYMhmfaS67DYDmm/mYsfBeKvR6CBnDNyU02NltytSC8HCeRDtCEkCTfY2fEfHGLNqImc0ejuGPlPMgfFSyoZg9dUFrFnwtBvIMnTelT8AvVkjTiKdQtiR1RbtAIgvbWifMJvtPK7sH4lp; domain=.ceneo.pl; expires=Fri, 12-Dec-2014 11:49:45 GMT; path=/ svcc=d=2014-08-07 04:05:11&m=2014-09-12 12:49:46&c=1; domain=.ceneo.pl; expires=Fri, 31-Dec-9999 23:59:59 GMT; path=/ st2=; domain=.ceneo.pl; expires=Thu, 11-Sep-2014 10:49:46 GMT; path=/'
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
    $war = $body = false;
    $page= array('content'=>'');
   // print_r($str);echo BR;
    foreach($str as $wiersz){
        if(!$body && strpos($wiersz, '<body')!==false){
            $body = true;
        }elseif($body){
            if(strpos($wiersz, '</body')!==false){
                $body = false;
            }else{
                if(strpos($wiersz, '/script')!==false){
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
                ){
                    $wiersz = '<!-- '.str_replace("<iframe", '-->', $wiersz);
                    $wiersz = '<!-- '.str_replace("<script", '', $wiersz);
                  //  $war = false;
                }

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
    $page = array('title'=>'','menu'=>'','content'=>'');
    $page['title'].=$_GET['ceneo'];
    $page['menu'].='<h2>zaciągane z ceneo </h2>';

    if(is_numeric($_GET['ceneo']))
    {
        $url="http://www.ceneo.pl/".$_GET['ceneo'].";0280-0.htm";
        $page['css']=$this->file('proxy.produkt.css')  ->data();
        $page['js'] =$this->file('proxy.produkt.js')  ->data().
            /* nr - index tr tablicy */
            '    var nr_ = '.( ( isset($_GET['nr']) && is_numeric($_GET['nr']) )?$_GET['nr']:-1).';
    var id_ ='.(is_numeric($_GET['ceneo'])?$_GET['ceneo']:-1).';';
    }
    else
    {
        $url = "http://www.ceneo.pl/Projektory_i_sprzet_prezentacyjny;szukaj-".$_GET['ceneo'];
        $page['css']=$this->file('proxy.szukaj.css')  ->data();
    $page['js'] =$this->file('proxy.szukaj.js')  ->data();
    }


    $page['head']='<script type="application/javascript" src="j/jquery-1.10.2.min.js" ></script>';

    $str = @gzfile($url);

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
else if(isset($_GET['ceneo']) && $_GET['ceneo']==0){
    $page['head']='<script type="application/javascript" src="j/jquery-1.10.2.min.js" ></script>';
    $page['js'] =$this->file('proxy.produkt.js')  ->data().
        '    var nr_ = '.(is_numeric($_GET['nr'])?$_GET['nr']:-1).';
    var id_ =0;';

}
else{


    $page = array('content'=>'    <form method="GET" action="">
    <fieldset name="test">
        <label for="ceneo">Szukaj w Ceneo</label>
        <input type="text" name="ceneo" id="ceneo" value="'.@$_GET['ceneo'].'" /><input type="submit" value="ok" />
    </fieldset></form>');
}
return $page;