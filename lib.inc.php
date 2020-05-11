<?php

require('./db/dbconstants.inc.php');
require('./errors.inc.php');

session_start();

//Mode debug, developpeur TODO: remplacer 1 par 0 pour prod
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function dbTPI(){
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

function showDanger($error){
    echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
}

function showWarning($warning){
    echo "<div class=\"alert alert-warning\" role=\"alert\">" . $warning . "</div>";
}

function showSucces($success){
    echo "<div class=\"alert alert-success\" role=\"alert\">" . $success . "</div>";
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

function addUser($username, $sha1pwd, $nom, $prenom, $bio, $typeFichier, $nomFichier,  $sizeFichier, $tmpNameFichier){
    $sql = "INSERT INTO users(username, pwd, nom, prenom, bio, imgProfil) VALUES(:username, :pwd, :nom, :prenom, :bio, :nomF)";

    static $ps = null;

    try {
        if ($ps == null) {
            $ps = dbTPI()->prepare($sql);
        }
        
        $ps->execute(
            array(
                'username' => $username,
                'pwd' => $sha1pwd,
                'nom' => $nom,
                'prenom' => $prenom,
                'bio' => $bio,
                'nomF' => $nomFichier
            )
        );
        $tmp = changeFileNameIfExists($nomFichier);
        addMediaToServer($tmp, $tmpNameFichier);
        return true;
    } catch (\Throwable $th) {
        return false;
    }

}

function changeFileNameIfExists($fileName , $cpt = 0){

    if (file_exists("./img/" . $fileName)) {
        while(file_exists("./img/" . $cpt . $fileName)){
            $cpt++;
        }
    
        return $cpt . $fileName; 
    }
    else{
        return $fileName; 
    }
   
   
}

function addMediaToServer($nomFichier, $tmpName){
    $typesAcceptes = array("image/gif", "image/png", "image/jpeg", "video/mp4", "audio/mpeg"); 

    try {
        //$test = mime_content_type($nomFichier);
        if (in_array(mime_content_type($tmpName), $typesAcceptes)) {

                //$nomFichier = $cpt .= $nomFichier;
                if (!move_uploaded_file($tmpName, "./img/" .  changeFileNameIfExists($nomFichier))){
                    throw new Exception();    //si il retourne false, throw, si il throw deja il va dans le catch
            }
    
              
            
        }
    } catch (\Throwable $th) {
        throw new Exception();
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

function getUserProfilePicture($uid){
    $sql = "SELECT imgProfil FROM users WHERE idusers=:idU LIMIT 1";
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
        $ps->bindParam(':idU', $uid, PDO::PARAM_STR); //force le param a etre un str
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

    return (empty($answer["imgProfil"])) ? "profil.png" : $answer["imgProfil"];
}

function getPosts(){
    $sql = "SELECT idposts, titrePost, descriptionPost, auteur FROM posts";
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

function editPost($idPost, $titre, $description){
    $sql = " UPDATE posts SET titrePost = :t, descriptionPost = :d WHERE idposts = :id";

    static $ps = null;

    try {
        if ($ps == null) {
            $ps = dbTPI()->prepare($sql);
        }
        
        $ps->bindParam(':t', $titre, PDO::PARAM_STR); //force le param a etre un str
        $ps->bindParam(':d', $description, PDO::PARAM_STR); 
        $ps->bindParam(':id', $idPost, PDO::PARAM_STR); 

        return $ps->execute();

    } catch (\Throwable $th) {
        //die("L'utilisateur n'a pas pu être créé");
        return false;
    }
}

function deletePost($idPost){
    $sql = " DELETE FROM posts WHERE idposts = :id";

    static $ps = null;

    try {
        if ($ps == null) {
            $ps = dbTPI()->prepare($sql);
        }
        
        $ps->bindParam(':id', $idPost, PDO::PARAM_STR); //force le param a etre un str

        return $ps->execute();

    } catch (\Throwable $th) {
        //die("L'utilisateur n'a pas pu être créé");
        return false;
    }
}

function getIdByUsername($username){
    $sql = "SELECT idusers FROM users WHERE username=:u LIMIT 1";
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
        $ps->bindParam(':u', $username, PDO::PARAM_STR); //force le param a etre un str
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

function getPostsFromUser($idUser){
    $sql = "SELECT * FROM posts WHERE idUsers=:idU";
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
        $ps->bindParam(':idU', $idUser, PDO::PARAM_STR); //force le param a etre un str
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

function getUserInfo($uid){
    $sql = "SELECT bio, nom, prenom FROM users WHERE idusers=:idU LIMIT 1";
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
        $ps->bindParam(':idU', $uid, PDO::PARAM_STR); //force le param a etre un str
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

    return $answer;
}

function getPostInfo($idPost){
    $sql = "SELECT titrePost, descriptionPost, auteur FROM posts WHERE idposts=:id LIMIT 1";
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
        $ps->bindParam(':id', $idPost, PDO::PARAM_STR); //force le param a etre un str
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

    return $answer;
}

function displayPosts($posts){
    $html = "";

    
    foreach ($posts as $key => $value) {
    $auteur = $value["auteur"];
    $html .= "          <article class=\"col-xs-12 col-sm-12 col-lg-3 col-md-3 post\">". //Article
             "              <h2><a class=\"postlink\" href=\"post.php?post=" . $value["idposts"] .  "\">". $value["titrePost"] .  "</a></h2>".
             "              <p><i>par <a href=\"user.php?username=$auteur\">" . $value["auteur"]  . "</a></i></p>".
             "              <p>". $value["descriptionPost"] .  "</p>";

             if (!empty($_SESSION["username"])) 
             {
                if ($value["auteur"] == $_SESSION["username"]) 
                {
                    $html .= "<form method=\"post\" action=\"./editPost.php\">
                                <input type=\"hidden\" name=\"idPost\"  value=\"" . $value["idposts"] . "\" /> 
                                <button class=\"btn btn-outline-secondary\" name=\"edit\" type=\"submit\" value=\"" . $value["idposts"] ."\" />Edit</button>
                                <button class=\"btn btn-outline-danger\" name=\"delete\" type=\"submit\" value=\"" . $value["idposts"] ."\" />Delete</button>
                                </form>";
                }
             }

    $html .= "          </article>";
             
             
    }

             
    return $html;
}

function displayOnePost($idPost){

    $infos = getPostInfo($idPost);

    $html = "         <article class=\"col-xs-12 col-sm-12 col-lg-12 col-md-12 text-center\">
                        <h2>". $infos["titrePost"] .  "</h2>
                        <p><i>par " . $infos["auteur"] .  "</i></p>         
                      </article>";
    $html .= "         <article class=\"col-xs-12 col-sm-12 col-lg-12 col-md-12 text-center \">
                        <p>". $infos["descriptionPost"] .  "</p>     
                      </article>";

        if (!empty($_SESSION["username"])) 
        {
            if ($infos["auteur"] == $_SESSION["username"]) 
            {
                $html .= "<article class=\"col-xs-12 col-sm-12 col-lg-12 col-md-12 text-center\">
                            <form method=\"post\" action=\"./editPost.php\">
                            <input type=\"hidden\" name=\"idPost\"  value=\"" . $idPost . "\" /> 
                            <button class=\"btn btn-outline-secondary\" name=\"edit\" type=\"submit\" value=\"" . $idPost ."\" />Edit</button>
                            <button class=\"btn btn-outline-danger\" name=\"delete\" type=\"submit\" value=\"" . $idPost ."\" />Delete</button>
                            </form>
                          </article>";
            }
        }

    return $html;
}