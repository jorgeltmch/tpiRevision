<nav class="navbar navbar-expand navbar-dark bg-dark">
  <a class="navbar-brand" href="#">TPI</a>
    <ul class="nav navbar-nav navbar-left mr-auto">
      <li class="nav-item active"> <!-- le active pour page active  -->
         <a class="nav-link" href="index.php">Home  </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="newPost.php">Nouveau post</a>
      </li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li>
        <!--  formulaire pour buton comme <a>  -->
        <?php if(empty($_SESSION["username"])):?>

        <form action="./login.php">
          <input class="btn btn-primary" type="submit" value="Login" />
        </form>

        <?php else: ?>

          <form action="./logout.php">
          <input class="btn btn-primary" type="submit" value="Logout" />
        </form>

        <?php endif; ?>

      </li>
    </ul>
</nav>