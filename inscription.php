<?php
require('./lib.inc.php');

if (!empty($_SESSION["username"])) {
  header("Location: index.php"); 
  exit();
}

$_SESSION["ERR"] = ""; //Vider le cache des erreurs quand on arrive sur la page

$username = (empty($_POST['username'])) ? "" : filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$sha1pwd = (empty($_POST['password'])) ? "" : sha1(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));
$sha1pwd2 = (empty($_POST['password2'])) ? "" : sha1(filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_STRING));
$nom = (empty($_POST['nom'])) ? "" : filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
$prenom = (empty($_POST['prenom'])) ? "" : filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
$bio = (empty($_POST['bio'])) ? "" : filter_input(INPUT_POST, 'bio', FILTER_SANITIZE_STRING);

$nomFichier = (empty($_FILES['img']['name'])) ? "" : $_FILES['img']['name'];
$typeFichier = (empty($_FILES['img']['type'])) ? "" : $_FILES['img']['type'];
$sizeFichier = (empty($_FILES['img']['size'])) ? "" : $_FILES['img']['size'];
$tmpNameFichier = (empty($_FILES['img']['tmp_name'])) ? "" : $_FILES['img']['tmp_name'];
$errFichier = (empty($_FILES['img']['error'])) ? "" : $_FILES['img']['error'][0] ;


if (!empty($username) && !empty($sha1pwd) && !empty($sha1pwd2) && !empty($nom) && !empty($prenom) && !empty($bio) && !empty($nomFichier) && !empty($typeFichier) && !empty($sizeFichier) && !empty($tmpNameFichier)) {
  if (strlen($bio) <= 220) //TODO : 220 DANS CONSTANTE
  {
    if ($errFichier == 0) {
      if ($sha1pwd == $sha1pwd2) {
        if (!userExists($username)){
          if (addUser($username, $sha1pwd, $nom, $prenom, $bio, $typeFichier, $nomFichier,  $sizeFichier, $tmpNameFichier)) {
            echo "USER AJOUTE";
    
            header("Location: login.php");
            exit;
    
          }
          else{
            $_SESSION["ERR"] = ERR01;
          }
        }
        else{
          $_SESSION["ERR"] = ERR02;
    
        }
      } else {
        $_SESSION["ERR"] = ERR03;
    
      }
    }
    else{
      $_SESSION["ERR"] = ERR06;
    }
  }
  else{
    $_SESSION["ERR"] = ERR07;
  }
    
  
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <title>Login</title>
</head>
<body class="bg-light">
<?php include("navbar.php") ?>


    <div class="container">
    
    
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6 mx-auto">
        <?php if(!empty($_SESSION["ERR"])){
                showDanger($_SESSION["ERR"]);
              } 
        ?>
        <div class="card login">
            <article class="card-body ">
                <h4 class="card-title text-center">Inscription</h4>
                <hr>
                <form action="#" enctype="multipart/form-data" method="post">
                <div class="form-group">
                    <input class="form-control" placeholder="Username" name="username" required >
                </div> 
                <div class="form-group">
                    <input class="form-control" placeholder="******" type="password" name="password" required>
                </div> 

                <div class="form-group">
                    <input class="form-control" placeholder="Confirm password" type="password" name="password2" required>
                </div> 

                <div class="form-group">
                    <input class="form-control" placeholder="Nom" name="nom"  >
                </div> 

                <div class="form-group">
                    <input class="form-control" placeholder="Prenom" name="prenom"  >
                </div> 

                <div class="form-group">
                    <textarea class="form-control" type="text" name="bio" rows="8" placeholder="Votre article ..." maxlength="220" required></textarea>
                </div>

                <div class="input-group mb-3">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" name="img" multiple accept="image/x-png,image/gif,image/jpeg">
                    <label class="custom-file-label" for="inputGroupFile02">Choose file</label>
                  </div>
                  <div class="input-group-append">
                    <span class="input-group-text"  >Upload</span>
                  </div>
                </div>

                <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block"> Sign up </button>
                </div> 

                <p class="text-center"><a href="./login.php" class="link">Déjà inscrit ?</a></p>
                </form>
            </article>
        </div> 
      </div>
    </div>

    
</body>
<script src="./js/jquery.min.js" type="text/javascript"></script>
<script src="./js/bootstrap.min.js" type="text/javascript"></script>
</html>
