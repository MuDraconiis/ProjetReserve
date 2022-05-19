<?php 
require 'bdd.php'; 
require 'function.php';
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
  <link rel='stylesheet' href='bootstrap/css/bootstrap.min.css' > 
  <link rel="stylesheet" href="bootstrap/css/style.css">
  <script src='bootstrap/js/bootstrap.min.js' ></script>
  <script src='bootstrap/js/sweetalert.min.js' ></script>


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
  
    <div class="p-2 bd-highlight"><a class="btn btn-outline-primary" href="index.php" role="button">Connexion</a> </div>

  </div>
  <main class="flex-shrink-0">
  <div class="container homeContainer">
    <h1>Récupération de mot de passe</h1>
    <hr>    
  </div>
            
    <div class="container">
    <form method="POST" action="">
        <div class="form-group">
            <label class="col-form-label">Saisir votre adresse Mail *</label>
            <input type="text" class="form-control" Maxlength="250" value="<?php echo @$_POST['mail'];?>"  name="mail" required />
        </div>

        <br/>
        <div class="form-group">
            <button type="submit" class="btn col-md-12 col-sm-12 col-xs-12 btn-primary" name="connect" id="btn-login">
                <i class="glyphicon glyphicon-edit"></i> &nbsp; Envoyer le code de récupération
            </button>
        </div>
        <br>
        <?php
            if(isset($_POST['connect'])){
              echo 'ping';

                if (strlen($_POST['mail'])>0){  
                  
                  $recup_mail=htmlspecialchars($_POST['mail']);
                  $requete=$bdd->prepare("SELECT * from utilisateur where mail_utilisateur = ?");
                  $requete->execute(array($recup_mail));
                  $rep=$requete->fetch();

                  

                  
                  if($requete->rowCount()>0){

                      $to=$recup_mail;
                      $subject  = 'Récupération de mot de passe.';
                      $headers = "From: leonroot@gmail.com";
                      $token=md5(token(30));
                      $id_utilisateur=$rep['id_utilisateur'];
                      $lien="site/reset_password.php?confirm=".$token."";
                      //echo $message;
                      $message="Bonjour monsieur ".$rep['nom_utilisateur']." ".$rep['prenom_utilisateur']." \n Vous avez fait une requete de changement de mot de passe.\nVeuillez cliquer sur ce lien pour changer votre mot de passe.\n ".$lien."\nSi cette requete ne vient pas de vous, veuillez ignorer ce mail.";
                      
                      if (mail($to,$subject,$message, $headers)){

                        $requete=$bdd->prepare("UPDATE utilisateur set token_mot_de_passe_oublier=? where id_utilisateur =?");
                        $requete->execute(array($token, $id_utilisateur));
                     
                          ?>

                          <script >
                              swal({
                                  title: "Récupération de mot de passe.",
                                  text: "Si votre mail existe, Nous venons de vous envoyé des instructions par mail  pour reinitialiser votre mot de passe. " ,
                                  icon: "success",
                                  button: "OK",
                              }).then(function(){
                                  window.location="index.php";
                              });
                          </script>
                      <?php

                      }else{
                      
                      ?>
                          <script >
                              swal({
                                  title: "Récupération de mot de passe.",
                                  text: "Erreur lors de l'envoi du mail.Veuillez rééssayer. " ,
                                  icon: "error",
                                  button: "OK",
                              }).then(function(){
                                  window.location="forget_password.php";
                              });
                          </script>
                          <?php

                      }


                  }else{
                    ?>

                    <script >
                        swal({
                            title: "Récupération de mot de passe.",
                            text: "Si votre mail existe, Nous venons de vous envoyé des instructions par mail  pour reinitialiser votre mot de passe. " ,
                            icon: "success",
                            button: "OK",
                        }).then(function(){
                            window.location="index.php";
                        });
                    </script>
                <?php
                  }
                  //
                }else{
                  echo"<div class='alert alert-danger'><b>Erreur </b>:Veuillez saisir une adresse mail.</div>";
                }
            }
            ?>
        
        
    </form>
    </div>
    <?php require 'footer.php'; ?>


