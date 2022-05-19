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


  
  <main class="flex-shrink-0">
  <div class="container homeContainer">
    <h1>Vérification de compte</h1>
    <hr>    
  </div>
  <div class="container">
   <form method="POST" action="" id="login-form" autocomplete="off">
        <div class="form-group">
            <label for="login_user" class="col-form-label">Adresse mail *</label>
            <input type="mail" value="<?php echo @$_POST['email']; ?>" placeholder="Entrez votre adresse mail" maxlength="100"  <?php if(isset($_POST['email']) and strlen($_POST['email'])>0){ echo '';}{ echo "autofocus"; }?> class="form-control" name="email" required />
        </div>
        <div class="form-group">
            <label for="pass_user" class="col-form-label">Code de confirmation *</label>
            <input type="text"  class="form-control"  placeholder="Entrez votre code de confirmation" name="code_confirmation" required />
        </div>
        <div class="row">
            <div  class="col-md-12 col-sm-12 col-xs-12"><br>
                <div class="form-group">
                    <button class="btn col-md-12 col-sm-12 col-xs-12 btn-primary" name="verifier" id="btn-login">
                         Activer
                    </button>
                </div>
            </div>
        </div>
        <?php 

            if (isset($_POST['verifier'])){
                $mail_utilisateur =htmlspecialchars($_POST['email']);
                $code_confirmation_utilisateur=htmlspecialchars( $_POST['code_confirmation']);
                
                
                $requete=$bdd->prepare("SELECT * from utilisateur where email_utilisateur=? and code_confirmation_utilisateur =? ");
                $requete->execute(array($mail_utilisateur, $code_confirmation_utilisateur));
                $reponse=$requete->fetch();

                $id_utilisateur=$reponse['id_utilisateur'];

                
                if($requete->rowCount()>0){


                    if($reponse['statut_utilisateur'] == 0 ){

                        if (date("Y-m-d H:i:s")<$reponse['date_expiration_code_confirmation']){

                            $requete=$bdd->prepare("UPDATE utilisateur SET statut_utilisateur = 1 where id_utilisateur =? ");
                            
                            
                            if( $requete->execute(array($id_utilisateur)) ){

                                ?>
                                <script > 
                                swal({
                                    title: "Vérification de compte.",
                                    text: "Votre compte à été validé avec succès.cliquez sur OK pour continuer. ",
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
                                    title: "Vérification  de compte.",
                                    text: "Erreur lors de la vérification du compte.Veuillez rééssayer. " ,
                                    icon: "error",
                                    button: "OK",
                                    }).then(function(){
                                        window.location="confirm_account.php";
                                        });
                                </script>
                                <?php
                                }//ici

                        }else{

                            //date expiré
                            // mise a jour du code de confirmation et de la date d'expiration
                            $code_confirmation_utilisateur=code(5);
                            $date = date("Y-m-d H:i:s");
                            $date=date('Y-m-d H:i:s', strtotime($date. ' + 3 days'));

                            $requete=$bdd->prepare("UPDATE utilisateur SET code_confirmation_utilisateur =?, date_expiration_code_confirmation=? where id_utilisateur =? ");
                            $requete->execute(array($code_confirmation_utilisateur, $date, $id_utilisateur));

                            //envoi du nouveau code de confirmation à l'utilisateur
                            $to=$reponse['email_utilisateur'];
                            $subject  = 'Validation de compte';
                            $headers = "From: torkent163@gmail.com";

                            
                            $lien="site/confirm_account.php";
                            //echo $message;
                            $message="Bonjour monsieur ".$reponse['nom_utilisateur']." ".$reponse['prenom_utilisateur']." \n Nous venons de vous renvoyer un nouveau code de confirmation.\nveuillez cliquer sur ce lien pour confirmer votre compte.\n".$lien."\nVotre LOGIN est: ".$reponse['login_utilisateur']."\nVotre code de confirmation est: ".$code_confirmation_utilisateur;
                            mail($to,$subject,$message, $headers);

                            ?>
                                <script > 
                                swal({
                                    title: "Vérification  de compte.",
                                    text: "La date de vérification est expirée! nous vous avons envoyer un nouveau code de confirmation mail.Veuillez l'utiliser avant trois jours. " ,
                                    icon: "warning",
                                    button: "OK",
                                    }).then(function(){
                                        window.location="confirm_account.php";
                                        });
                                </script>
                                <?php 
                        }
                    


                    }else{
                    ?>
                        <script> 
                        swal({
                            title: "Vérification de compte.",
                            text: "Ce compte à déjà été activé. " ,
                            icon: "warning",
                            button: "OK",
                            }).then(function(){
                                window.location="connexion.php";
                                });
                        </script>
                        <?php

                    }

                }else{
                    ?>
                    <script> 
                    swal({
                        title: "Vérification de compte.",
                        text: "Code de vérification ou adresse mail invalide. ",
                        icon: "error",
                        button: "OK",
                        });
                    </script>
                    <?php
                }


            }
                
            ?>
 </form>
 </div>
 
<?php require 'footer.php'; ?>
 

