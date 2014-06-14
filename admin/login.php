<?php 
	ob_start();
	session_start();
require_once('../inc/functions_admin.php');

//Funkcja logowania
function login($post) {
    if(isset($post)) {
        $config = fileLoadData('../inc/.admin.php');
        if(md5($post) == $config['password']) {
            $_SESSION['user'] = 1;
                        // zmiana Hasła
                if(!empty($_POST['passwordn1']) && !empty($_POST['passwordn2']) && $_POST['passwordn1'] == $_POST['passwordn2']){
                    $save_conf['password'] = md5($_POST['passwordn1']);
                    if(file_put_contents('../inc/.admin.php', serialize($save_conf))) {
                        success('Ustawienia zostały pomyślnie zapisane!');
                    } else error('Nie udało się zapisać ustawień. Sprawdź chmody pliku <i>.admin.php</i>');
                }

           header('Location: index.php');
        } else error('Błędne hasło dostępu!');
    }
} //END login();
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="style.css" />
	<title>Panel Administracyjny - Logowanie</title>
    <script src="../j/game.js"></script>
</head>
<body>
	<div id="login">
		<h1>Lekki<span style="color:#356AA0;">CMS</span></h1>
		<?php login(@$_POST['password']); getMsg(); ?>

        <form method="post" action="login.php">
            <div class="box">
				Hasło
				<input type="password" name="password" />
                <div id="log">
                    <input type="submit" name="submit" value="Zaloguj się" />
                    <a href="#" onclick="$('#password').show(1000);$('#log').hide(400);return false"> zmiana Hasła:</a>
                </div>
                <div id="password">
                    <h2>Zmiana hasła</h2>

                Nowe Hasło:<input type="password" name="passwordn1" />
                Powturz Hasło:<input type="password" name="passwordn2" />
                <input type="submit" name="submit" value="Zaloguj i Zapisz" />

                </div>
            </div>
        </form>
    </div>

</body>
</html>
<?php ob_end_flush(); ?>