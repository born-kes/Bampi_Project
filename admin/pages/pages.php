<?php
menuLoad(@$_GET['file'],@$_GET['order']);
fileDelete(@$_GET['delete']);
pageAdd(@$_POST['add_page']);
pageEdit(@$_POST['edit_page']);

$config = fileGetData('../inc/config.php');
getMsg();
?>
<script type="text/javascript">
    $(document).ready(function() {
        $('input[type="text"]').keyup(function(){
            $(this).attr({width: 'auto', size: ($(this).val().length+parseInt($(this).val().length *0.15) )});
        });
        // $('input').attr('style','width:60px;')
    });
</script>
<div class="box">
    <h2>Podstrony <div class="right"><?php pageLinks(@$_GET['feature']); ?></div></h2>
    <div class="content">
        <?php
        if(!isset($_GET['feature'])) {
            echo '
					<table>
						<thead>
							<tr>
								<td>Nazwa</td> <td>Kolejność</td> <td>URL</td> <td width="20%">Akcje</td>
							</tr>
						</thead>
						<tbody>';
            pagesList('<tr>
									<td><a href="?go=pages&feature=edit&file={{page:name}}">{{page:title}}</a></td> <td>{{page:order}}</td>
									<td><a href="../{{page:url}}" target="_blank" title="Zobacz">{{page:url}}</a></td>
									<td><a href="?go=pages&order=up&file={{page:name}}" title="Przesuń w górę"><img src="img/icon_arrow_up.gif" align="middle" /></a> <a href="?go=pages&order=down&file={{page:name}}" title="Przesuń w dół"><img src="img/icon_arrow_down.gif" align="middle" /></a> <a href="?go=pages&delete={{page:name}}" title="Usuń"><img src="img/icon_delete.gif" align="middle" /></a></td>
								</tr>');
            echo '</tbody>
					</table>';
        } else {
        if($_GET['feature']=='new') {
            echo '<form method="POST" action="?go=pages&feature=new">';
            $add_page='Dodaj';

        }else if($_GET['feature']=='edit' && isset($_GET['file'])) {
            $page = fileGetData('../pages/'.$_GET['file'].'.php');
            echo '<form method="POST" action="?go=pages&feature=edit&file='. $_GET['file'].'">';
                $add_page='Aktualizuj';
        } ?>
                <table class="new_page">
                    <tbody>
                    <tr>
                        <td width="16%">Tytuł Podstrony:</td> <td><input type="text" name="title" value="<?php
                            echo (isset($_POST['title'])?$_POST['title']:(isset($page['title'])?$page['title']:'') );
                            ?>" /></td>
                    </tr>
                    <tr>
                        <td width="16%">Nazwa w adresie:</td> <td><input type="text" name="file" value="<?php
                            echo (isset($_POST['file'])?$_POST['file']:(isset($_GET['file'])?$_GET['file']:'') );
                            ?>" /></td>
                    </tr>
                    <tr>
                        <td width="16%">Treść:</td> <td><textarea name="content"><?php
                                echo (isset($_POST['content'])?$_POST['content']:(isset($page['content'])?$page['content']:'') );
                                ?></textarea></td>
                    </tr>
                    <tr>
                        <td width="16%">ckedytor</td><td><a href="" onclick="$('textarea').cleditor();return false;">włącz</a></td>
                    </tr>
                    <tr>
                        <td>
                            <em onclick="$('#page_function').show(1000);"> dodatkowe fukcje </em>
                        </td>
                        <td id="page_function" style="display:none;">
                                funcion(& $page ,& $config){
                                <textarea name="page_function" placeholder="<?php

                    echo '$page[ title | url | content | page_function ]">'.
                                     htmlspecialchars_decode(
                                        isset($_POST['page_function'])?$_POST['page_function']:(isset($page['page_function'])?$page['page_function']:'')
                                    );
                                    ?></textarea>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <input type="submit" name="add_page" value="<?php echo $add_page; ?>" />

            </form>
            <?php   } ?>
    </div>
</div>