<?php
/**
 */


/*** LOGOWANIE **************************************************************/

/**
 * Weryfikacja uzytkownika
 *
 * @link  admin/index.php
 * @class
 * @param $_SESSION['user']
 * @internal
 * @return 1) session_destroy(); header('Location: login.php'); <br> 2) brak reakcji
 */
function verifyLogin() {
    $users = fileLoad('inc/.users.php');

    if (isset($_SESSION['user']) ){
        define('UPRAWNIENIA',$users[$_SESSION['user'][1]]);
        return true;
    }
    switch( checkPass(@$_POST["login"], @$_POST["password"]) ){
        case 3:echo '<h1>3</h1>';
            // Brak login i hasła
            break;
        case 2:echo '<h1>2</h1>';
            // Niewłaściwa ilość znaków w Login lub Hasło
            break;
        case 1:echo '<h1>1</h1>';
            // Niewłaściwy Login lub Hasło
            break;
        case 0:echo '<h1>0</h1>';
            // Właściwe Login i Hasło
            $_SESSION['user'] = $_POST["login"];
            define('UPRAWNIENIA', $users[$_SESSION['user'][1]] );
            echo '<h1>OK</h1>';
            break;
        default:
            break;
    }
    //      session_destroy();
}

/**
 * @param $user
 * @param $pass
 * @return int
 */
function checkPass($user, $pass){
    $users = fileLoad('inc/.users.php');
    /*sprawdzenie długołci przekazanych ciągów*/
    if(is_null($user) && is_null($pass) ){
        return 3;
    }

    $userNameLength = strlen($user);
    $userPassLength = strlen($pass);

    if($userNameLength < 3 || $userNameLength > 20 ||
        $userPassLength < 4 || $userPassLength > 40){
        return 2;
    }
    $user =addslashes($user);
    $pass =addslashes($pass);

    /*nawiązanie połączenia serwerem i wybór bazy*/

    if(!isset($users[$user]) ){
        // Niema takiego usera
        return 1;
    }
    if($users[$user][0]!==$pass){
        // Niewłaściwe hasło
        return 1;
    }
    if(isset($users[$user]) && $users[$user][0]===$pass){
        return 0;
    }

}

verifyLogin();