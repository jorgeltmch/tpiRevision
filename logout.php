<?php
session_start();

if (isset($_SESSION["username"])) {
    // vider le tableau de la session
$_SESSION = array();

// supprimer le cookie de session du côté du navigateur
// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

header("Location: index.php");
exit();
}
else{
  header("Location: login.php"); //TODO: CHANGER QUAND PAGE LOGIN
  exit();
}