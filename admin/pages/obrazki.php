<?php

if(!empty($_FILES["file"]) ){
    $rapo="<br>";
    $allowedExts = array("gif", "jpeg", "jpg", "png");
    $temp = explode(".", $_FILES["file"]["name"]);
    $extension = end($temp);

    if ((($_FILES["file"]["type"] == "image/gif")
            || ($_FILES["file"]["type"] == "image/jpeg")
            || ($_FILES["file"]["type"] == "image/jpg")
            || ($_FILES["file"]["type"] == "image/pjpeg")
            || ($_FILES["file"]["type"] == "image/x-png")
            || ($_FILES["file"]["type"] == "image/png"))
        && ($_FILES["file"]["size"] < 2000000) // wielkosc w bajtach
        && in_array($extension, $allowedExts)) {
        if ($_FILES["file"]["error"] > 0) {
            $rapo.=  "Return Code: " . $_FILES["file"]["error"] . "<br>";
        } else {
            $rapo.=  "Upload:Nazwa: " . $_FILES["file"]["name"] . "<br>";
            $rapo.=  "Type: " . $_FILES["file"]["type"] . "<br>";
            $rapo.=  "Size:Wielkosc: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
            $rapo.=  "Typczasowo plik na serwerze " . $_FILES["file"]["tmp_name"] . "<br>";
            if (file_exists("upload/" . $_FILES["file"]["name"])) {
                $rapo.=  $_FILES["file"]["name"] . " already exists. ";
            } else {
                move_uploaded_file($_FILES["file"]["tmp_name"],
                    "../i/" . $_FILES["file"]["name"]);
                $rapo.=  "Stored in: " . "i/" . $_FILES["file"]["name"];
            }
        }
        success('obrazek Zapisany');
    } else {
        if ((($_FILES["file"]["type"] == "image/gif")
            || ($_FILES["file"]["type"] == "image/jpeg")
            || ($_FILES["file"]["type"] == "image/jpg")
            || ($_FILES["file"]["type"] == "image/pjpeg")
            || ($_FILES["file"]["type"] == "image/x-png")
            || ($_FILES["file"]["type"] == "image/png"))
        ){}else
            error('Obrazek nie pobrany: błąd typu.');
        if ($_FILES["file"]["size"] < 20000){}else
            error('Obrazek nie pobrany: błąd obrazek za Duży.');
        if( in_array($extension, $allowedExts)){}else
            error('Obrazek nie pobrany: błąd Nie dopuszczalne rozszeżenie.');

    }
}
getMsg();
?>
<div class="box">
    <h2>Import Ograzków</h2>
    <div class="content">
        <form action="<?php echo pageUrl(@$_GET['go']); ?>" method="post" enctype="multipart/form-data">
            <label for="file">Plik do pobrania:</label>
            <input type="file" name="file" id="file">
            <input type="submit" value="ok">
        </form>
        <h3>Lista obrazków</h3>
        <table class="settings">
            <tbody>
                <tr>
                    <td width="16%">Nazwa</td>
                    <td>Frazy</td>
                </tr>
                <?php
                $listaIMG=fileList('../i/');
for( $x = 0; $x < count($listaIMG); $x++ ) {
           echo '<tr>
                    <td><a href="../i/'.autPhp($listaIMG[$x]).'">'.autPhp($listaIMG[$x]).'</a></td>
                    <td>{{img:'.autPhp($listaIMG[$x]).'}}</td>
                </tr>';
}
            ?>
            </tbody>
        </table>
        <?php echo $rapo; ?>
    </div>
</div>