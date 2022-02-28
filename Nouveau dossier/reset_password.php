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
    <h1>Réinitialisation de mot de passe</h1>
    <hr>    
  </div>
    <?php
    $token_recup=htmlspecialchars($_GET['confirm']);

    $requete=$bdd->prepare("SELECT * from utilisateur where token_mot_de_passe_oublier=?");
    $requete->execute(array($token_recup));
    $rep=$requete->fetch();

    if(!empty($rep)){

    ?>
    <div class="container">
        <form method="POST" action="">
            <input type="hidden" value="<?php echo $rep['id_utilisateur']; ?>" name="id_utilisateur">
            <div class="form-group">
                <label >Saisir votre nouveau mot de passe   *</label>
                <input type="password" class="form-control"  value="<?php echo @$_POST['pass1'];?>"  name="pass1" required />
            </div>
            <div class="form-group">
                <label >Confirmer votre nouveau mot de passe   *</label>
                <input type="password" class="form-control"  value="<?php echo @$_POST['pass2'];?>"  name="pass2" required />
            </div>

            <br/>
            <div class="form-group">
                <button type="submit" class="btn col-md-12 col-sm-12 col-xs-12 btn-primary" name="update" id="btn-login">
                    <i class="glyphicon glyphicon-edit"></i> &nbsp; Réinitialiser le mot de passe
                </button>
            </div>
        </form>
        <br>    
        
    <?php

    }else{
        ?>
        <script >
            swal({
                title: "Récupération de mot de passe.",
                text: "Erreur le lien de récupération est incorrect ou à déja eté utiliser. " ,
                icon: "error",
                button: "OK",
            }).then(function(){
                window.location="index.php";
            });
        </script>
        <?php
    }


    if (isset($_POST['update'])){

        $mot_passe_1=md5($_POST['pass1']);
        $mot_passe_2=md5($_POST['pass2']);

        if ($mot_passe_1 == $mot_passe_2){

            $id_utilisateur=$_POST['id_utilisateur'];

            $requete=$bdd->prepare("UPDATE utilisateur set mot_de_passe_utilisateur=? WHERE id_utilisateur=? ");
            

            if ($requete->execute(array($mot_passe_1, $id_utilisateur))){

               
                $requete=$bdd->prepare("UPDATE utilisateur set token_mot_de_passe_oublier='null' where id_utilisateur =?");
                $requete->execute(array($id_utilisateur));

                ?>
                <script >
                    swal({
                        title: "Réinitialisation de mot de passe.",
                        text: "Mot de passe réinitialisé avec succès.Veuillez vous connecter pour continuer. " ,
                        icon: "success",
                        button: "OK",
                    }).then(function(){
                        window.location="connexion.php";
                    });
                </script>
            <?php


            }else{

            ?>
                <script >
                    swal({
                        title: "Céation de mot de passe.",
                        text: "Erreur lors de la réinitialisation du mot de passe.Veuillez rééssayer.",
                        icon: "error",
                        button: "OK",
                    });
                </script>
                <?php

            }

        }else{
            echo "<div class='alert alert-danger'><b>Erreur </b>:Les deux mot de passe ne correspondent pas.</div>";
        }

    }
    ?>
    </div>

<?php require 'footer.php'; ?>	


