<?php
require('./lib.inc.php');

if (!empty($_SESSION["username"])) {
  header("Location: index.php"); 
  exit();
}

$_SESSION["ERR"] = ""; //Vider le cache des erreurs quand on arrive sur la page

$username = (empty($_POST['username'])) ? "" : filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);;
$sha1pwd = (empty($_POST['password'])) ? "" : sha1( filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));

if (!empty($username) && !empty($sha1pwd)) {
    if (connexion($username, $sha1pwd)){ //lorsqu'un utilisateur a crée un compte il est logué
      echo "CONNECTE";
      $_SESSION["username"] = $username;
      $_SESSION["uid"] = getIdUser($username);
      //Quand user connecté redirect sur index
      header("Location: index.php");
      exit;
    }
    else{
      $_SESSION["ERR"] = ERR05;
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
                <h4 class="card-title text-center">Login</h4>
                <hr>
                <form action="#" method="post">
                <div class="form-group">
                    <input class="form-control" placeholder="Username" name="username" required >
                </div> 
                <div class="form-group">
                    <input class="form-control" placeholder="******" type="password" name="password" required>
                </div> 

                <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block"> Login </button>
                </div> 

                <p class="text-center"><a href="./inscription.php" class="link">Pas encore inscrit ?</a></p>
                </form>
            </article>
        </div> 
      </div>
    </div>

    
</body>
</html>