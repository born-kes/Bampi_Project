<?php
/**
 * Generowanie Menu głównego
 *
 * Wyświetla całą treść i daje możliwość dodania jej do Menu głównego
 *
 */

/**
 * Wczytanie listy stron
 */
$lista_plikow = fileList('../pages/');


$formaUL = '
            <li class="a{a}">
            {b}
               <input type="hidden" value="{a}" name="plik[]">
               <input type="hidden" value="{b}" name="name[]">
               <ul class="m-ul re"><input type="hidden" value="{a}" name="przecinek[]">
               </ul>
            </li>';
?>
<style>
    .m-ul { min-height: 10px; }
    ul.re{ background-color: #fffdec; }
    #menu .m-ul ul.re li{ display: inline-block; }
    #menu li {
        display: -moz-stack;
        padding: 10px;
        -moz-box-shadow: 10;
    }
    #menu ul ul:nth-child(){
        display: -moz-stack;
        padding: 10px;
    }

</style>
<script>
    $(function() {
        $( ".m-ul" ).sortable({
            connectWith: ".m-ul"
        }).disableSelection();
    });
</script>
<div class="box">
    <form method="post" action="">
        <h2>Menu Urzytkownika <button>Zapisz zmiany</button></h2>
        <ul id="menu" class="m-ul">
            <?php
            if(
                !is_null($_POST)
                && isset($_POST['plik'])
                && is_array($_POST['plik'])
                && isset($_POST['name'])
                && isset($_POST['przecinek'])
            ) {

                for($i=0, $j= count($_POST['plik']);$i<$j; $i++) {
                    // Sprawdzanie zagnierzczenia
                    if($_POST['plik'][$i]==$_POST['przecinek'][$i]){
                       echo stringSwapArray($formaUL,
                           array('{a}','{b}'),
                           array($_POST['plik'][$i]=> $_POST['name'][$i])
                       );
                        unset($lista_plikow[$_POST['plik'][$i]]);
                    }
                }
            }
            ?>
        </ul>
    </form>

    <h2>Lista Stron</h2>
    <ul id="menu_list" class="m-ul">
    <?php echo stringSwapArray($formaUL , array('{a}','{b}'), $lista_plikow); ?>
    </ul>
</div>


