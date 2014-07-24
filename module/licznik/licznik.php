<?php
/**
 * Na początku kodu umieść $microTimeStart = microtime(true);
 */
global $functionEnd, $functionModule;

$functionEnd[]=function(){
    global $microTimeStart;
$time = sprintf("<n>%d ,</n>%f s", 1, microtime(true) - $microTimeStart);
    echo stringSwap(fileLoad('module/licznik/licznik.html',false), array('time'=>$time) );
};

$functionModule[]=function(){
    global $microTimeStart;
    templeBloki('js', stringSwap( fileLoad('module/licznik/licznik.js', false), array('time'=>$microTimeStart) ) );
    templeBloki('css', "\n n{display:none;}\n");
};