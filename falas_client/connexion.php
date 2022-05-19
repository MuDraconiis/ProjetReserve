<?php require 'bdd.php'; ?>
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
  <link rel='stylesheet' href='bootstrap/css/bootstrap.min.css' > 
  <link rel="stylesheet" href="bootstrap/css/style.css">
  <script src='bootstrap/js/bootstrap.min.js' ></script>

</head>

<body class='d-flex flex-column h-100'>

  <header>
    <nav class='navbar navbar-expand-md navbar-dark fixed-top bg-dark'>
      <div class='container-fluid'>
        <a class='navbar-brand' href='index.php'>FENESTRA CORSA</a>        
      </div>       
    </nav>
  </header><br><br>

  <div class="d-flex flex-row-reverse bd-highlight loginEtLogout" style="margin-top: 20px;">
  
  <div class="p-2 bd-highlight"><a class="btn btn-outline-primary" href="inscription.php" role="button">Inscription</a> </div>

</div>



  <main class="flex-shrink-0">
  <div class="container homeContainer">
    <h1>Connexion</h1>
    <hr>    
  </div>

  <div class="container">

    <form method="POST" class="col col-md-6" class="form-group " action="" id="login-form"  autocomplete="off">

          <div class="form-group">
              <label class=" col-form-label" for="login_user" >Nom d'utilisateur *</label><br>
              <input type="mail" class="form-control" value="<?php echo @$_POST['mail_utilisateur']; ?>" placeholder="Entrez votre nom d'utilisateur" maxlength="50" name="login" required />
          </div>
      
          <div class="form-group">
              <label class=" col-form-label" for="pass_user" >Mot de passe *</label><br>
              <input type="password" class="form-control" placeholder="Entrez votre mot de passe" name="motdepasse" required /> 
          </div>
      <br>	
      <button  name="valider" class="btn col-md-12 col-sm-12 col-xs-12 btn-primary" id="btn-login">&nbsp; Se connecter </button> <br><br>

      <a href="forget_password.php">Mot de passe oublié?</a>
          <br><br>
          
          <!--Début traitement -->
          <?php
          if(isset($_POST["valider"])){
              $login=strip_tags($_POST["login"]);
              $motdepasse=htmlspecialchars($_POST["motdepasse"]);

              if(strlen($login)>0 && strlen($motdepasse)>0){

                  $motdepasse=md5($motdepasse);
                  $requete=$bdd->prepare("SELECT * FROM utilisateur WHERE email_utilisateur=? AND mot_de_passe_utilisateur=? ");
                  $requete->execute(array($login, $motdepasse));
                  $rows=$requete->fetch();

                  if ($requete->rowCount()>0){

                      if ($rows['statut_utilisateur']==0){
                          //le compte n'est pas encore activé
                          header('location:confirm_account.php');
                      }else if($rows['statut_utilisateur'] == 1) {
                          //le compte est actif
                          
                          $_SESSION['id_utilisateur']=$rows["id_utilisateur"];     
                          $_SESSION['type_utilisateur']=$rows["type_utilisateur"];                   

                          header('location:index.php');

                      }else if ($rows['statut_utilisateur'] == 3 ){
                          //le compte a eté desactivé  (Par un administrateur)
                          echo "<div class='alert alert-danger'><b>Erreur </b>:Votre compte a été désactiver.</div>";

                      }else if ($rows['statut_utilisateur']== 2){
                          //le compte a eté supprimé (de façon logique)
                          echo "<div class='alert alert-danger'><b>Erreur </b>:Votre compte a été supprimé.</div>";

                      }else{

                          echo "<div class='alert alert-danger'><b>Erreur </b>:Identifiant ou mot de passe incorrect.</div>";
                      }

                  }else{
                      echo $motdepasse;
                      echo "<div class='alert alert-danger'><b>Erreur </b>:Identifiant ou mot de passe incorrect.</div>";
                  }
              }else{

                  echo"<div class='alert alert-danger'><b>Erreur </b>:Veuillez saisir tous les champs.</div>";
              }

          }


          ?>
          <!--Fin traitement -->

    </form>
  </div>

  <?php require_once 'footer.php'; ?>
