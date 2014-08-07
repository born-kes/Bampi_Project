<?php  //  $this->file('edyt.js')->data();
$_table['content'] = $this->file('zasysanie.html')->data();
$_table['js'] = $this->file('zasysanie.js')->data();
$_table['css'] = '/* zasysanie */
#zasysanie_b{
position:fixed;
top:0;
z-index: 4
-moz-max-content;
-webkit-margin-start;
}
.zasysanie{
display: block;
min-width: 21em;
background-color: #fff;
margin: 0;
padding-right: 2em;
}
th div.ol {
text-align: left;
}
';

return $_table;
    array(
    'content'=>$_table['html'],
    'title'=> '',
    'js'=> '',
    'css'=> ' ',
    'head'=>''
);
