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
  
    <div class="p-2 bd-highlight"><a class="btn btn-outline-primary" href="connexion.php" role="button">Connexion</a> </div>

  </div>


  <main class="flex-shrink-0">
  <div class="container homeContainer">
    <h1>Inscription</h1>
    <hr>    
  </div>

	<div class="container">	
		<form method="POST" action=''>
			<div class="row">	
				<div class="col-md-6 ">			
					
					<div class="form-group">
						<label class="col-form-label">Identifiant (adresse mail) *</label>
						<input type="text" class="form-control" value='<?php echo @$_POST['email'];?>' minlength=5 Maxlength="50"  name="email"  required />
					</div>
					<div class="form-group">
						<label class="col-form-label">Mot de passe *</label>
						<input type="password" class="form-control"  Maxlength="50" name="pass1" required />
					</div>
					<div class="form-group">
						<label class="col-form-label">Confirmation du mot de passe *</label>
						<input type="password" class="form-control"  Maxlength="50" name="pass2" required />
					</div>
					
					
				</div>
				<div class="col-md-6 ">
					<div class="form-group">
						<label class="col-form-label">Nom *</label>
						<input type="text" class="form-control" value='<?php echo @$_POST['nom'];?>' Maxlength="50" name="nom" required />
					</div>
					<div class="form-group">
						<label class="col-form-label">Prenom *</label>
						<input type="text" class="form-control" value='<?php echo @$_POST['prenom'];?>' Maxlength="50" name="prenom" required />
					</div>
					<div class="form-group">
						<label class="col-form-label">Téléphone *</label>
						<input type="tel" class="form-control" value='<?php echo @$_POST['telephone'];?>' minlength=8 required Maxlength="15"  name="telephone"  />
					</div> 						
				</div>

			</div>				
			

			<br>
			<div class="g-recaptcha" name="g-recaptcha-response" data-sitekey="6Le_q-EcAAAAAPkjKZ3v23P2sxa3dli5fS2vUNNF"></div>
			<br>
			<div class="form-group">
				<button type="submit" class="btn col-md-12 col-sm-12 col-xs-12 btn-primary" name="valider" id="btn-login">
					ENREGISTRER
				</button> 
			</div>
			<br>
				<?php
				//
				if(isset($_POST['valider'])){  

					
					$nom =htmlspecialchars($_POST['nom']);
					$prenom =htmlspecialchars($_POST['prenom']);
					$telephone =htmlspecialchars($_POST['telephone']); 
					$email =htmlspecialchars($_POST['email']);
					$pass1 =md5(htmlspecialchars($_POST['pass1']));
					$pass2 =md5(htmlspecialchars($_POST['pass2']));

					
					if ( strlen($nom)>0 && strlen($prenom)>0 && strlen($telephone)>0 && strlen($email)>0 && strlen($_POST['pass1'])>0 && strlen($_POST['pass2'])>0){					

					}else{
						$errors[]="Veuillez renseigner tous les champs.";
					}
					
					if(!empty($email)){
						if(!filter_var($email,FILTER_VALIDATE_EMAIL))
						{
							$errors[]="Veuillez utiliser un bon format d'adresse mail.";			
						}												
					}

					if(!empty($telephone)){		
						if (!is_numeric($telephone))
						{
							$errors[] = 'Format du numero de téléphone incorrect';
						}

					}

					if ($pass1<>$pass2)
					{
						$errors[] = 'Les deux mots de passe ne correspondent pas.';
					}

					/*$secret = '6Le_q-EcAAAAADGihi-VvyGZEn7tTouhWANb6T5b';
					$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);
					$responseData = json_decode($verifyResponse);
					if ($responseData->success) {

					}else{
						$errors[] = 'Vous etes un robot visiblement.';
					}*/

					
					if(!empty($errors)){
								
						foreach($errors as $error){
							echo"<div class='alert alert-danger alert-dismissable'>".$error;								
							echo"</div>";
						}
					}
					else{

						$requete=$bdd->prepare("SELECT * FROM utilisateur WHERE mail_utilisateur = ? and statut_utilisateur<>2 ");
						$requete->execute(array($email));
						$rows=$requete->rowCount();


						if ($rows==0){

						$token_mot_de_passe_oublier=md5(token(25));
						$code_confirmation_utilisateur=code(5);
						$date = date("Y-m-d H:i:s");
						$date= date('Y-m-d H:i:s', strtotime($date. ' + 3 days'));
						
						//Insertion de l'utilisateur

						$requete=$bdd->prepare("INSERT INTO utilisateur(nom_utilisateur, prenom_utilisateur, telephone_utilisateur, email_utilisateur, code_confirmation_utilisateur, date_ajout_utilisateur, token_mot_de_passe_oublier, statut_utilisateur, date_expiration_code_confirmation, mot_de_passe_utilisateur, type_utilisateur)VALUES(?,?,?,?,?,Now(),?,0,?,?,'Utilisateur') ");
					

						if( $requete->execute(array($nom,$prenom,$telephone,$email,$code_confirmation_utilisateur,$token_mot_de_passe_oublier,$date,$pass1)) ){
							
							//envoie de l'email de confirmation

							$to=$email;
							$subject  = 'Création de compte';
							$headers = "From: torkent163@gmail.com";
							$lien="www.tp_securite.com/confirm_account.php";
							$message="Bonjour monsieur ".$nom." ".$prenom."\nVotre compte à bien eté créer.\nveuillez cliquer sur ce lien pour activer votre compte.\n".$lien."\nVotre identifiant est: ".$email."\nVotre code de confirmation est: ".$code_confirmation_utilisateur;

							mail($to,$subject,$message, $headers);

							//renitialisation de varible
							

							?>
							<script > 
							swal({
								title: "Création de compte.",
								text: "Utilisateur enregistré avec succès!Un mail avec les informations de connexion vient d'être envoyer au concerné. " ,
								icon: "success",
								button: "OK",
								}).then(function(){
									window.location="confirm_account.php";
									});
							</script>

							<?php
							
						}else{

							print_r($requete->errorInfo());
							echo"<div class='alert alert-danger'>Opération non réussie.Veuillez réessayer</div>";
							}
						}else{
							echo"<div class='alert alert-danger'><b>ERREUR </b>: Le mail saisie est déjà utilisé par un autre utilisateur.</div>";
						}
						
					}

				}

				?>
			
		</form>
	</div>

	<?php require 'footer.php'; ?>
