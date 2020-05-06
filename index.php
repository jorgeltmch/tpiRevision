<?php
require('./lib.inc.php');

$posts = getPosts();

//var_dump($posts);

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
    <?php echo displayPosts($posts) ?>
    
    <!------------------------------------------------------------------------------- Grille d'articles --------------------------------------------------------------------------------->
    <!-- center -->
    <!-- <div class="container-fluid">
        <div class="container grille-articles">
            <div class="row"> 
                <article class="col-xs-12 col-sm-12 col-lg-3 col-md-3">
                    <h2> Mon article </h2>
                    <p><i>auteur</i></p>
                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Excepturi veritatis quia labore corporis, quis doloribus expedita, eaque perferendis consequatur nulla animi doloremque deleniti voluptas itaque quo ab voluptatum reiciendis atque.</p>
                </article>
                <article class="col-xs-12 col-sm-12 col-lg-3 col-md-3">
                    <h2> Mon article </h2>
                    <p><i>auteur</i></p>
                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Excepturi veritatis quia labore corporis, quis doloribus expedita, eaque perferendis consequatur nulla animi doloremque deleniti voluptas itaque quo ab voluptatum reiciendis atque.</p>
                </article>
                <article class="col-xs-12 col-sm-12 col-lg-3 col-md-3">
                    <h2> Mon article </h2>
                    <p><i>auteur</i></p>
                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Excepturi veritatis quia labore corporis, quis doloribus expedita, eaque perferendis consequatur nulla animi doloremque deleniti voluptas itaque quo ab voluptatum reiciendis atque.</p>
                </article>
                <article class="col-xs-12 col-sm-12 col-lg-3 col-md-3">
                    <h2> Mon article </h2>
                    <p><i>auteur</i></p>
                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Excepturi veritatis quia labore corporis, quis doloribus expedita, eaque perferendis consequatur nulla animi doloremque deleniti voluptas itaque quo ab voluptatum reiciendis atque.</p>
                </article>
            </div>
        </div>
    </div> -->

</body>
