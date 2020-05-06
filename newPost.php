<?php 

require('./lib.inc.php');

if (empty($_SESSION["username"])) {
    header("Location: login.php"); 
    exit();
}

$titre = (empty($_POST['tire'])) ? "" : $_POST['tire'];
$description = (empty($_POST['description'])) ? "" : $_POST['description'];
$idUser = getIdUser($_SESSION["username"]);

if (!empty($titre) && !empty($description) && !empty($idUser)) {
    if (addPost($titre, $description, $idUser)) {
        echo "POST AJOUTE";
    }
    else{
        echo "ERREUR AVEC LA PUBLICATION";
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
    <title>Nouvelle publication</title>
</head>
<body class="bg-light">
<?php include("navbar.php") ?>

    <div class="container">

    <div class="row">
      <div class="col-xs-12 col-sm-12 col-lg-12 col-md-12 mx-auto">
        <div class="card formPublication">
            <article class="card-body ">
                <h4 class="card-title text-center">Nouvelle publication</h4>
                <hr>
                <form action="#" method="post">
                <div class="form-group">
                    <input class="form-control" placeholder="Titre ..." name="tire" required >
                </div> 
                <div class="form-group">
                    <textarea class="form-control" type="text" name="description" rows="8" placeholder="Votre article ..." required></textarea>
                </div>

                <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block"> Valider </button>
                </div> 
                </form>
            </article>
        </div> 
      </div>
    </div>

    
</body>
</html>