<?php
$config = fileLoadData('../inc/config.php');

$form = fileLoadData('../pages/.wytyczne.php');

if(isset($_POST['file']) ){
    fileSave('../pages/'.$_POST['file'].'.php', serialize($_POST) );
}

foreach($form as $input){
    $legend=$input['legend'];
    unset($input['legend']);

    $inputs[$legend]= input( $input );
}
//'Nazwa' 'URL'
$Thead='
    <tr>
        <td>{a}</td>
        <td>{b}</td>
    </tr>
';
$Thead = stringSwapArray($Thead, array('{a}','{b}'), array(''=>''));
$Body='
<tr>
    <td>{a}</td>
    <td>{b}</td>
</tr>
';
$Body = stringSwapArray($Body, array('{a}','{b}'), $inputs);

getMsg(); ?>
<style>
    #node tr:nth-child(1) td:nth-child(1){width:16%;}
    #node td {text-align:left;}
    #node .content textarea { margin-top: 0px; }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        $('input[type="text"]').keyup(function(){
            $(this).attr({width: 'auto', size: ($(this).val().length+parseInt($(this).val().length *0.13) )});
        });

        $('input[type="text"]').attr('size','1')
    });
</script>
<div class="box">
    <h2>Rodzaje Treści</h2>
    <?php echo stringSwapArray('<div class="content">{b}</div>', array('{a}','{b}'), catList('../pages') ); ?>
    <h2>Podstrony</h2>
    <div class="content">
        <form action="" method="post">
            <table id="node">
                <thead>
                <?php echo $Thead; ?>
                </thead>
                <tbody>
                <?php echo $Body; ?>
                </tbody>
            </table>
        </form>
    </div>
</div>