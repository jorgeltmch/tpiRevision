<?php
    require('./lib.inc.php');

    $_SESSION["ERR"] = ""; //Vider le cache des erreurs quand on arrive sur la page

    $idPost = $_GET["post"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">

    <title>Home</title>
</head>
<body class="bg-light">
    
    <?php include("navbar.php") ?>
    <div class="container-fluid">
    <div class="container grille-articles">
         <div class="row">
            <?php echo displayOnePost($idPost) ?>
         </div>
      </div>
    </div>    
</body>
<script src="./js/jquery.min.js" type="text/javascript"></script>
<script src="./js/bootstrap.min.js" type="text/javascript"></script>
</html>