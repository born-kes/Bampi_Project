<?php
function json_test($json_string){
    return !preg_match('/[^,:{}\\[\\]0-9.\\-+Eaeflnr-u \\n\\r\\t]/',
        preg_replace('/"(\\.|[^"\\\\])*"/', '', $json_string));
}

$page = array(); global $db;

if( empty($_FILES["file"]) ){
    $page['content']=  $this->load('import.html',false)->data();
}else{

if ($_FILES["file"]["error"] > 0) {
    $page['content']= "Error: " . $_FILES["file"]["error"] . "<br>";
} else {
    $myfile = file_get_contents($_FILES["file"]["tmp_name"]) ;

    if(!json_test($myfile)){
        $json = json_decode($myfile,true);

        foreach($json as $key => $val){
            //  var_dump($val);
                 $db->insert('produkty', $val );
        };
    }
    else
    {
        $db->query($myfile);
    }

    $error = $db->error();

    if( is_null($error[2]) )
        $page['content']=' Dodane ';
    else
        $page['content']= $error[2];
}
  //  var_dump( $db->last_query() );


}

return $page;