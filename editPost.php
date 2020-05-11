<?php
    require('./lib.inc.php');

    // var_dump($_POST["delete"]);
    // var_dump($_POST["edit"]);
    $isDeleted = (!empty($_POST['delete']));
    $isEdited = (!empty($_POST['edit']));

    $idPost = (empty($_POST['idPost'])) ? "" : filter_input(INPUT_POST, 'idPost', FILTER_SANITIZE_NUMBER_INT);

    $titre = (empty($_POST['titre'])) ? "" : filter_input(INPUT_POST, 'titre', FILTER_SANITIZE_STRING);
    $description = (empty($_POST['description'])) ? "" : filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $idUser = $_SESSION["uid"];

    $postInfo = getPostInfo($idPost);
    
    if ($postInfo["auteur"] != $_SESSION["username"]) {
        header("Location: index.php");
        exit;
    }

    if(!empty($titre) && !empty($description)){
            if(editPost($idPost, $titre, $description)){
                echo "POST MODIFIE";
                //Redirige sur index quand post ajouté
                header("Location: index.php");
                exit;
            }
    }


    if(!empty($_POST["cancel"])){
        header("Location: index.php");
        exit;
    }
    elseif (!empty($_POST["confirm"])) {
        if(deletePost($idPost)){
            echo "POST Supprimé";
            //Redirige sur index quand post ajouté
            header("Location: index.php");
            exit;
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
        <?php if(!empty($_SESSION["ERR"])){
                showDanger($_SESSION["ERR"]);
              }
              
              if($isDeleted) :
        ?>
        <div class="alert alert-danger row" role="alert" >
            <p class="col-xs-6 col-sm-6 col-lg-6 col-md-6">Voulez vous vraiment supprimer ce post ?</p>
            <form action="#" method="post" class="col-xs-6 col-sm-6 col-lg-6 col-md-6 text-right">
            <input type="hidden" name="idPost"  value="<?php echo $idPost ?>" /> 
                <button class="btn btn-outline-success " name="confirm" value="confirm" type="submit">Oui</button>
                <button class="btn btn-outline-danger" name="cancel" value="cancel" type="submit">Annuler</button>
            </form>
        </div>
        <?php elseif($isEdited):?>  
            
        <div class="card formPublication">
            <article class="card-body ">
                <h4 class="card-title text-center">Nouvelle publication</h4>
                <hr>
                <form action="#" method="post">
                <div class="form-group">
                    <input class="form-control" name="titre" value="<?php echo $postInfo["titrePost"]?>" required >
                </div> 
                <div class="form-group">
                    <textarea class="form-control" type="text" name="description" rows="8" required><?php echo $postInfo["descriptionPost"]?></textarea>
                </div>

                <input type="hidden" name="idPost"  value="<?php echo $idPost ?>" /> 

                <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block"> Valider </button>
                </div> 
                </form>
            </article>
        </div> 
        <?php endif;?>  
      </div>
    </div>

    
</body>
</html>