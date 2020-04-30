<?php
require('./lib.inc.php');

$username = (empty($_POST['username'])) ? "" : $_POST['username'];
$sha1pwd = (empty($_POST['password'])) ? "" : sha1($_POST['password']);
$sha1pwd2 = (empty($_POST['password2'])) ? "" : sha1($_POST['password2']);

if (!empty($username) && !empty($sha1pwd)) {
  if ($sha1pwd == $sha1pwd2) {
    if (!userExists($username)){
      if (addUser($username, $sha1pwd)) {
        //Si l'user est inscrit, il est automatiquement connecté.
        echo "CONNECTE";
        $_SESSION["username"] = $username;   
      }
      else{
        echo "ERREUR AVEC LA CREATION DE L'USER";
      }
    }
    else{
      echo "L'USER N'EXISTE DEJA";
    }
  } else {
    echo "LES MDP NE CORRESPONDENT PAS";
  }
  
    
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TPI</title>
</head>
<body>
<?php if(empty($_SESSION["username"])) :?>

<form action="#" method="post">
  <div class="container">
    <label for="username"><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="username" required>

    <label for="password"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="password" required>

    <label for="password"><b>Reentrez votre mdp</b></label>
    <input type="password" placeholder="Enter Password" name="password2" required>

    <button type="submit">Inscription</button>
    <p>Déjà inscrit ? <a href="./inscription.php">Connectez vous ici</a></p>

  </div>
</form>

<?php else:?>

    <p>Connecté à <b><?php echo($_SESSION["username"]) ?></b> <a href="./logout.php">Logout ici</a></p>

<?php endif;?>
</body>
</html>