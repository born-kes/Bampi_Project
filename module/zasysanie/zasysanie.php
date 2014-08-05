<?php  //  $this->file('edyt.js')->data();
$_table['content'] = $this->file('zasysanie.html')->data();
$_table['js'] = $this->file('zasysanie.js')->data();
$_table['css'] = '#zasysanie_b{position:fixed; top:0;z-index: 4}';

return $_table;
    array(
    'content'=>$_table['html'],
    'title'=> '',
    'js'=> '',
    'css'=> '',
    'head'=>''
);
