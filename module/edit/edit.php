<?php
 
 $url_plik='pages/kes.php';
$plik = fopen($url_plik, "r");
$zaw = fread($plik, 9999);
fclose($plik);
