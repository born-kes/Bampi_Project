<?php
if(isset($_POST)){
if(isset($_POST['img']) && is_array($_POST['img']) )
foreach($_POST['img'] as $key => $val){
    echo $key.' $key => $val '. $val.'<br>';
}
}

$fin['sc']      =  $this->file('kes.js')  ->data(). 'var extra_type="text"';
$fin['content'] =   $this->file('kes.html')->data();
$fin['css'] =   'legend{float:left;}';



$select['type']='select';
$select['value']= array(1,'page'=>'page','trzy'=>3);
$select['name']='select';
$select['id']='id_select';
$select['spec']='page';
$select['sufix']='<br>';
$form[]=$select;


$form[] = array(
    'type'=>'radio',
    'value'=> array('page','trzy',3),
    'name'=>'radio',
    'spec'=>'trzy',
    'sufix'=>'<br>',
        'prefix'=>' <legend> radio </legend>'
);
$form[] = array(
    'type'=>'checkbox',
    'value'=> array('page','trzy',3),
    'name'=>'checkbox',
    'id'=>'id_checkbox',
    'spec'=>3,
    'sufix'=>'<br>'

);

$fin['content'].= '
<div class="box">
  <div class="content">
  '.form($form,'Pokazowy Form').'
  </div>
</div>';

$GLOBALS['tpl']->html($fin);
return $fin;