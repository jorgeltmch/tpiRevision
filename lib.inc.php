<?php

require('./db/dbconstants.inc.php');

session_start();

//Mode debug, developpeur TODO: remplacer 1 par 0 pour prod
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function dbTPI()
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

function userExists($username){
    $sql = "SELECT idusers FROM users WHERE username=:username ";

    static $ps = null;
    try {
        if ($ps == null) {
            $ps = dbTPI()->prepare($sql);
        }
        
        $ps->bindParam(':username', $username, PDO::PARAM_STR); //force le param a etre un str
        
        if($ps->execute()){
            $answer = $ps->fetch(PDO::FETCH_ASSOC);
        } else {
            echo "Erreur : Impossible d'exécuter le ps<br />";
        }

        
    } catch (\Throwable $th) {
        die("il y a eu un soucis avec la requête");
    }

    return (isset($answer["idusers"]));
}

function addUser($username, $sha1pwd){
    $sql = "INSERT INTO users(username, pwd) VALUES(:username, :pwd)";

    static $ps = null;

    try {
        if ($ps == null) {
            $ps = dbTPI()->prepare($sql);
        }
        
        $ps->execute(
            array(
                'username' => $username,
                'pwd' => $sha1pwd,
            )
        );
        return true;
    } catch (\Throwable $th) {
        //die("L'utilisateur n'a pas pu être créé");
        return false;
    }

}

function getIdUser($username){
    $sql = "SELECT idusers FROM users WHERE username=:username LIMIT 1";
    $answer = array();

    static $ps = null;

    if ($ps == null) {
        // Préparer mon prepare statement
        try {
            $ps = dbTPI()->prepare($sql);
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
    return $answer["idusers"];
}

function connexion($username, $sha1pwd){
    $sql = "SELECT idusers FROM users WHERE username=:username AND pwd=:passwd LIMIT 1";
    $answer = array();

    static $ps = null;

    if ($ps == null) {
        // Préparer mon prepare statement
        try {
            $ps = dbTPI()->prepare($sql);
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
    return (isset($answer["idusers"]));
}


function getPosts(){
    $sql = "SELECT titrePost, descriptionPost, auteur FROM posts";
    $answer = array();

    static $ps = null;

    if ($ps == null) {
        // Préparer mon prepare statement
        try {
            $ps = dbTPI()->prepare($sql);
        } catch (Exception $e) {
            //TODO: Prinntlog
            echo 'Erreur : ' . $e->getMessage() . '<br />';
            echo 'N° : ' . $e->getCode();
            // @TODO: Gérer les erreurs
            die('Unable to create prepare statement');
        }
    };
    // Exécuter le prepare statement;
    try {
        if ($ps->execute()) {
            $answer = $ps->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "Erreur : Impossible d'exécuter le ps<br />";
        }
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage() . '<br />';
        echo 'N° : ' . $e->getCode();
        die("Même pas foutu de vérifier si un utilisateur existe ...");
    }
    return $answer;
}

function addPost($titre, $description, $idUser){
    $sql = "INSERT INTO posts(titrePost, descriptionPost, idUsers, auteur) VALUES(:t, :d, :id, :a)";

    static $ps = null;

    try {
        if ($ps == null) {
            $ps = dbTPI()->prepare($sql);
        }
        
        $ps->bindParam(':t', $titre, PDO::PARAM_STR); //force le param a etre un str
        $ps->bindParam(':d', $description, PDO::PARAM_STR); 
        $ps->bindParam(':id', $idUser, PDO::PARAM_STR); 
        $ps->bindParam(':a', $_SESSION["username"], PDO::PARAM_STR); //A ajouter ou pas ?

        return $ps->execute();

    } catch (\Throwable $th) {
        //die("L'utilisateur n'a pas pu être créé");
        return false;
    }

}


function displayPosts($posts){
    $html = "<div class=\"container-fluid\">". //Container principal
            "  <div class=\"container grille-articles\">". //Grille des articles
            "      <div class=\"row\">";
    foreach ($posts as $key => $value) {
    $html .= "          <article class=\"col-xs-12 col-sm-12 col-lg-3 col-md-3\">". //Article
             "              <h2>". $value["titrePost"] .  "</h2>".
             "              <p><i>par ". $value["auteur"] .  "</i></p>".
             "              <p>". $value["descriptionPost"] .  "</p>".
             "          </article>";
    }
    $html .= "      </div>".
             "  </div>".
             "</div>";
             
    return $html;
}