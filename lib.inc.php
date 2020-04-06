<?php

require('./db/dbconstants.inc.php');


function DB()
{
    static $dbc = null;

    if ($dbc == null) {
        try {
            $dbc = new PDO(
                'mysql:host=' . HOST . ';dbname=' . DBNAME,
                DBUSER,
                DBPWD,
                array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                    // PDO::ATTR_PERSISTENT => true
                )
            );
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage() . '<br />';
            echo 'N° : ' . $e->getCode();
            die('Could not connect to MySQL');
        }
    }
    return $dbc;
}


function connexion($username, $sha1pwd){
    $sql = "SELECT id FROM users WHERE username=:username AND pwd=:passwd LIMIT 1";
    $answer = array();

    static $ps = null;

    if ($ps == null) {
        // Préparer mon prepare statement
        try {
            $ps = DB()->prepare($sql);
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage() . '<br />';
            echo 'N° : ' . $e->getCode();
            // @TODO: Gérer les erreurs
            die('Unable to create prepare statement');
        }
    };
    // Exécuter le prepare statement;
    try {
        $ps->bindParam(':username', $username, PDO::PARAM_STR); //force le param a etre un str
        $ps->bindParam(':passwd', $sha1pwd, PDO::PARAM_STR);
        if ($ps->execute()) {
            $answer = $ps->fetch(PDO::FETCH_ASSOC);
        } else {
            echo "Erreur : Impossible d'exécuter le ps<br />";
        }
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage() . '<br />';
        echo 'N° : ' . $e->getCode();
        die("Même pas foutu de vérifier si un utilisateur existe ...");
    }
    return (isset($answer["id"]));
}