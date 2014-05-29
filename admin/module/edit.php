<div id="left">
<script src="module/edit/page-write-php.js" type="text/javascript"></script>
<script src="module/edit/codemirror.js"></script>
<script src="module/edit/xml.js"></script>
<script src="module/edit/javascript.js"></script>
<script src="module/edit/css.js"></script>
<script src="module/edit/clike.js"></script>
<script src="module/edit/php.js"></script>
<style type="text/css"  media="all" >
    @import url("module/edit/page-write-php.css");
    @import url("module/edit/codemirror.css");
    .CodeMirror { border: 1px solid #dfd; }
    body .CodeMirror-scroll { background:#efe !important; }
    /* .activeline {background: #e8f2ff !important; margin: -1px -7px !important; padding: 1px 7px !important;} */
    .CodeMirror { font-size: 14px;border-color:#dfd;width:752px;margin-left:-32px; }
    .CodeMirror-scroll { background:#fff !important;height:260px; }
    .CodeMirror-lines { padding:0.4em 0.6em !important; }
    .CodeMirror-gutter { border-right:1px solid #dfd !important; background:white;}
    .CodeMirror .CodeMirror-lines span.cm-comment { color:#999 !important; }
    textarea.textarea-for-code { background:#efe !important;border-color:#999 !important; }
</style><?php
 
 $url_plik='pages/kes.php';
$plik = fopen($url_plik, "r");
$zaw = fread($plik, 9999);
fclose($plik);
 
?>
<form action="<?php echo $url_plik; ?>" method="post">
<textarea style="width:600px; height:300px;" wrap="off" name="code"  class="textarea-for-code" id="textarea-for-code" ><? echo htmlspecialchars($zaw); ?></textarea><br>
 </div>
<div id="overlay-container"></div>
<div id="phpv-submit">phpv-submit</div>
<div id="new-feedback">new-feedback</div>
<script>
    window.enabled = true;
    function enableEditor() {
        window.editor = CodeMirror.fromTextArea(document.getElementById("textarea-for-code"), {
            lineNumbers: true,
            mode: 'application/x-httpd-php-open',
            indentUnit: 4,
            indentWithTabs: true,
            enterMode: "keep",
            tabMode: "shift",
            smartIndent: false,
            fixedGutter : true,
            onCursorActivity: function() {
                editor.setLineClass(hlLine, null);
                if (!editor.somethingSelected()) hlLine = editor.setLineClass(editor.getCursor().line, "activeline");
            }
        });
        CodeMirror.defineMIME("application/x-httpd-php-open", {name: "php", phpOpen: true});
        var hlLine = editor.setLineClass(0, "activeline");
    }
   // enableEditor();
</script>
<b onclick="enableEditor();">aktywator</b>
<script>
    function toggleHighlight() {
        if (window.enabled) {
            window.editor.toTextArea();
            window.enabled = false;
        } else {
            enableEditor();
            window.enabled = true;
        }
    }
    function overlay_open() {
        var overlay = document.getElementById("overlay-container");
        var html = document.getElementsByTagName("html")[0];
        html.className = "overlay-open";
        overlay.style.display = "block";
    }
    function overlay_close() {
        var overlay = document.getElementById("overlay-container");
        var html = document.getElementsByTagName("html")[0];
        html.className = "";
        overlay.style.display = "none";
    }
    var phpvsubmit = document.getElementById('phpv-submit');
    var codeform = document.getElementById('code-form');
    var feedback = document.getElementById('feedback-overlay');
    var new_feedback = document.getElementById('new-feedback');
    codeform_onsubmit = function() {
        editor.save();
    }
    phpvsubmit.onclick = function() {
        codeform.action = phpvsubmit.href;
        codeform_onsubmit();
        codeform.submit();
        return false;

    }
    new_feedback.onclick = feedback.onclick = function() {
        overlay_open();
        return false;
    }
</script>