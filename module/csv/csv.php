<?php

if(true || isset($_POST['file']))
{
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="noweceny'.( date("d_m") ).'.csv"');

$this->loadInclude("module/sql/sql.php");
/*$h = sql('thead');
foreach($h as $e){
    $he[] = $e[0];
}*/
//listEl( sql('thead') )
echo implode(';', array('kod_produktu','nazwa','cena') ).
    "\n";

$b = sql('csv');
foreach($b as $e){
    echo implode(';', aut($e,';') )."\n";
}
//print_r( $b );
exit;
}
else
{
 $_table['html'] = $this->file('csv.html')->data();

    $this->loadInclude("module/sql/sql.php");
  $fixed_thead = swap( '<li><input type="hidden" name="{{Field}}">{{Field}}</li>'."\n" ,  sql('thead') ,'class_dla_thead');

   $body['content'] = swap( $_table['html'] , array('form'=>$fixed_thead) );
    return $body;
}