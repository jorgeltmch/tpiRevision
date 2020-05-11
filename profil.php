<?php
require('./lib.inc.php');

$userPosts = getPostsFromUser($_SESSION["uid"]);
//var_dump($_SESSION["uid"]);
$userInfo = getUserInfo($_SESSION["uid"]);
//var_dump($userInfo);

//error_log("entrer dans le fichier index");

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
    
    <!------------------------------------------------------------------------------- Grille d'articles --------------------------------------------------------------------------------->
    <!-- center -->
    <div class="container-fluid">
        <div class="container grille-articles">
            <div class="row"> 
                <div class="col-xs-12 col-sm-12 col-lg-3 col-md-3">
                    <img src="img/<?php echo getUserProfilePicture($_SESSION["uid"])?>" class="img-fluid img-thumbnail" height="500px" width="500px" alt="profil">
                </div>
                <article class="col-xs-12 col-sm-12 col-lg-3 col-md-3">
                    <h2> <?php echo $_SESSION["username"] ?> </h2>
                    <p><?php echo $userInfo["prenom"] . " " . $userInfo["nom"] ?> , <i><?php echo count($userPosts) ?> posts</i></p>  
                    <p><?php echo $userInfo["bio"] ?></p>
                </article>

                <!-- pour separer, a voir si on garde -->
                <article class="col-xs-12 col-sm-12 col-lg-3 col-md-3"></article>
                <article class="col-xs-12 col-sm-12 col-lg-3 col-md-3"></article>


                    <?php echo displayPosts($userPosts); ?>
            </div>
        </div>
    </div>

</body>
<script src="./js/jquery.min.js" type="text/javascript"></script>
<script src="./js/bootstrap.min.js" type="text/javascript"></script>
</html>
