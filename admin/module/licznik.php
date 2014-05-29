<?php
/**
 * Na początku kodu umieść $time_licznik = microtime(true);
 */

?><div style="right:0;position: fixed;bottom: -5px;background: none repeat scroll 0 0 #E6E6E6; padding:5px; width: 210px;">
    <span style="display:none"><?php  printf("%d </span>
    Wygenerowane w: <span style=\"right: 3px; position: absolute;\">%f s</span>", 1, microtime(true) - $time_licznik); ?><br>
    Pobrane z serwera w:<span id="licznik_end" style="right: 3px; position: absolute;"></span></div>
<script type="text/javascript">
    $(function(){
        $.ajax('?go=ajax').done(function(a,b){
            $('#licznik_end').text(
                 (a-<?php echo $time_licznik ?>).toFixed(6) + ' s'
            )
        });
    });
</script>