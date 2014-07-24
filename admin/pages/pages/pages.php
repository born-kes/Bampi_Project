<?php
/*menuLoad(@$_GET['file'],@$_GET['order']);
fileDelete(@$_GET['delete']);
pageAdd(@$_POST['add_page']);
pageEdit(@$_POST['edit_page']);*/
$fin['sc']=$this->file('pages.js')->data();
$fin['content']=$this->file('pages.html')->data();
$tags = $GLOBALS['tpl']->tagsGet($this->load('../themes/default/template.html')->data());
$bloki = array_flip($GLOBALS['tpl']->tagsGet($fin['content']));

// SAVE
if(isset($_POST['title']) && !is_null($_POST['title']) && isset($_GET['file']) ){
    $this->load('../pages/'.$_GET['file'].'.php')-> save($_POST);
}
//getMsg();

$config = $this->load('../inc/config.php');

$ListaPlikow =  ( autPhp($this->lista('../pages/', false ) ) );


 if(!isset($_GET['feature'])) {

     $bloki=array(
         'name'=>'',
         'body'=> ''.
         $GLOBALS['tpl']->swap(
         "\n<tr>\n<td><a href=\"?go=pages&feature=edit&file={{nr0}}\">{{nr0}}</a></td>\n</tr>",
         $ListaPlikow),
         'input'=> '" style="display:none;"',
         'form'=>''
     );

}else if($_GET['feature']=='edit' && isset($_GET['file'])) {
     $page = $this->load('../pages/'.$_GET['file'].'.php')->data();
     if(!is_array($page))$page=array($page);
     $page = array_merge(array_map(function(){return '';} , array_flip($tags)), $page );

     $bloki=array(
         'name'=>@$_GET['file'],
         'body'=> ''.
             $GLOBALS['tpl']->swap(
                 '<tr><td><label>{{nr1}}</label></td><td>'.
                 //<input type="text" value="{{nr0}}" name="{{nr1}}" >
                 '<textarea name="{{nr1}}" placeholder="" style="width: 650px; height: 23px;margin-top: 0px;">{{nr0}}</textarea>'.
//       ckedytor <a href="" onclick="$('textarea').cleditor();return false;">włącz</a>
                 '</td></tr>',
                 $page),
         'input'=> 'Aktualizuj',
         'form'=>'edit&file='. $_GET['file']
     );
 }

$fin['content'] = $GLOBALS['tpl']->swap( $fin['content'] , $bloki );
$GLOBALS['tpl']->html($fin);