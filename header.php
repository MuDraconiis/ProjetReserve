<?php 
require 'bdd.php';

 ?>
<!doctype html>
<html lang='en'>
<head>
  <meta charset='utf-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <meta name='description' content=''>
  <meta name='author' content='Mark Otto, Jacob Thornton, and Bootstrap contributors'>
  <meta name='generator' content='Hugo 0.87.0'>
  <title>FENESTRA CORSA</title>  
  <!-- Bootstrap CSS -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <link rel='stylesheet' href='bootstrap/css/bootstrap.min.css' > 
  <link rel="stylesheet" href="bootstrap/css/style.css">
  <script src='bootstrap/js/bootstrap.min.js' ></script>

  <script src="https://requirejs.org/docs/release/2.3.5/minified/require.js"></script>
  <script type="text/javascript"   src="http://geoxml3.googlecode.com/svn/branches/polys/geoxml3.js"></script>
  

</head>

<body class='d-flex flex-column h-100' <?php if(isset($_GET['id_sentier'])){ echo 'onload="show_modal()"'; } ?> >

  <header>
    <nav class='navbar navbar-expand-md navbar-dark fixed-top bg-dark'>
      <div class='container-fluid'>
        <a class='navbar-brand' href='index.php'>FENESTRA CORSA</a>        
      </div>             
    </nav>
  </header><br><br>

  <nav class="navbar navbar-expand-lg navbar-dark bg-primary rounded">
  
  <ul class="navbar-nav mr-auto">
 
  <?php if (!empty($_SESSION['id_utilisateur'])){ ?>

    <li class="nav-item active">
      <a href="" class="nav-link">Compte</a>
    </li>


    <?php } ?>

  </ul>

  <ul class="navbar-nav ml-auto">

    <?php if (empty($_SESSION['id_utilisateur'])){ ?>

    <li class="nav-item active">
      <a href="connexion.php" class="nav-link">Connexion</a>
    </li>
    
    <li class="nav-item active">
      <a href="inscription.php" class="nav-link">Inscription</a>
    </li>

    <?php }else{ ?>

    <li class="nav-item active">
      <a href="logout.php" class="nav-link">Deconnexion</a>
    </li>

    <?php } ?>
    
  </ul>

</nav>

