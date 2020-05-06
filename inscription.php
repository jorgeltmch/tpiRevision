<?php
require('./lib.inc.php');

$username = (empty($_POST['username'])) ? "" : $_POST['username'];
$sha1pwd = (empty($_POST['password'])) ? "" : sha1($_POST['password']);
$sha1pwd2 = (empty($_POST['password2'])) ? "" : sha1($_POST['password2']);

if (!empty($username) && !empty($sha1pwd)) {
  if ($sha1pwd == $sha1pwd2) {
    if (!userExists($username)){
      if (addUser($username, $sha1pwd)) {
        echo "USER AJOUTE";

        header("Location: login.php");
        exit;

      }
      else{
        echo "ERREUR AVEC LA CREATION DE L'USER";
      }
    }
    else{
      echo "L'USER N'EXISTE DEJA";
    }
  } else {
    echo "LES MDP NE CORRESPONDENT PAS"; //TODO : LIST ERRORS
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
        <div class="card login">
            <article class="card-body ">
                <h4 class="card-title text-center">Inscription</h4>
                <hr>
                <form action="#" method="post">
                <div class="form-group">
                    <input class="form-control" placeholder="Username" name="username" required >
                </div> 
                <div class="form-group">
                    <input class="form-control" placeholder="******" type="password" name="password" required>
                </div> 

                <div class="form-group">
                    <input class="form-control" placeholder="Confirm password" type="password" name="password" required>
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
</html>
