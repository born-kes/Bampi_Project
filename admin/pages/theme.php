<?php

	$config = fileLoadData('../inc/config.php');
	//save_file(@$_POST['submit']);

$get = (isset($_GET['file'])?$_GET['file']:fileList('../themes/'.$config['theme']) ) ;
if(is_array($get))$get=$get[0];
$form[]=array(
    'type'=>'textarea',
    'name'=>'content',
    'value'=>htmlspecialchars_decode( fileLoad('../themes/'.$config['theme'].'/'.$get, false) )
);
$form[]= array(
    'type'=>'hidden',
    'name'=>'file',
    'value'=>$get
);
$form[]= array(
    'type'=>'submit',
    'name'=>'submit',
    'value'=>'Zapisz'
);

getMsg();
?>
<div class="box">
    <h2>Szablon <em><?php echo $config['theme'] ?></em></h2>
    <div class="content">
        Wybierz plik do edycji
        <?php echo input(array(
            'type'=>'select',
            'value'=> fileList('../themes/'.$config['theme']),
            'name'=>"file",
            'spec'=>$get,
           'onchange'=>'location = \''.pageUrl('f').'&file=\'+this.options[this.selectedIndex].innerHTML;'
        )  );

         echo form($form,'');
        ?>
</div>
