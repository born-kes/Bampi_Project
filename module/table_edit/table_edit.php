<?php
/**
 * Created by PhpStorm.
 * User: Monika Lukasz
 * Date: 15.08.14
 * Time: 01:52
 */
$table=array('head'=>''.
    '<script src="j/jquery-1.10.2.min.js" ></script>'.
    '<script src="i/jquery-ui.min.js" ></script>'.
//  '<script src="j/colResizable/colResizable-1.3.min.js" ></script>'.
//  '<script src="j/colResizable/colResizable-1.3.source.js" ></script>'.
//  '<script type="application/javascript" src="j/jquery-1.10.2.min.js" ></script>'.
//  '<script type="application/javascript" src="j/sort/jquery-latest.js" ></script>'.
 '<link rel="stylesheet" media="screen" href="i/jquery-ui.min.css">'
//'<link rel="stylesheet" media="screen" href="http://handsontable.com/demo/css/samples.css">'
);
$table['js'] = $this->file('table_edit.js')->data();
$table['css'] =  $this->file('table_edit.css')->data().
    $this->load('module/table/table.css')->data().
    $this->file('dymki.css')->data().
    $this->file('sort.css')->data();
//var_dump($table['css'] );
$table['content'] = $this->load('cache/table.html')->data();
return $table;