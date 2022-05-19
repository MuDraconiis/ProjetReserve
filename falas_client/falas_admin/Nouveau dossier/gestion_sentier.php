<?php 
/*
require_once 'header.php'; 
if (empty($_SESSION['id_utilisateur'])){ header('location:connexion.php');exit; };*/
?>
<?php 
require 'bdd.php';
if (empty($_SESSION['id_utilisateur'])){ header('location:connexion.php');exit; };


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
  <script src='bootstrap/jquery/jquery.min.js' ></script>
  <script src='bootstrap/js/sweetalert.min.js' ></script>

</head>

<body class='d-flex flex-column h-100' <?php if(isset($_GET['id_sentier'])){ echo 'onload="show_modal()"'; };if(isset($_GET['id_modification'])){ echo 'onload="show_modal2()"'; }; ?> >

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

    <li class="nav-item active">
      <a href="gestion_sentier.php" class="nav-link">Sentier</a>
    </li>

    <li class="nav-item active">
      <a href="" class="nav-link">Check point</a>
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



  <br>
  <main class="flex-shrink-0">
  <div class="container homeContainer"  >


 
    <h1>Gestion de sentiers</h1>

    <div class="row">

    <table class="table">
    <thead>
        <tr>
        
        <th scope="col">Nom</th>
        <th scope="col">Date de création</th>
        <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php 
          
        $requete=$bdd->prepare("SELECT * from sentier where id_utilisateur=? and statut_sentier in (0,1) ");
        $requete->execute(array($_SESSION['id_utilisateur']));
        
        while ($reponse = $requete->fetch()){
          
     ?>
        <tr>
        
        <td><a class="btn btn-outline-primary" href="gestion_sentier.php?id_sentier=<?=$reponse['id_sentier']; ?>"><?=$reponse['nom_sentier']; ?></a></td>
        <td><?=$reponse['date_creation_sentier']; ?></td>
        <td>
            <a class="btn btn-success" href="gestion_sentier?id_modification=<?= $reponse['id_sentier']; ?>">Modifier</a>
            <a class="btn btn-danger" href="gestion_sentier?id_suppression=<?= $reponse['id_sentier']; ?>" onclick="return confirm('Etes vous sûr de vouloir supprimer ce sentier?');">Suprimer</a>
        </td>
        </tr>
    <?php }; ?>

    </tbody>
    </table>

    
    <!-- modal pour l'ajout -->
    <button type="button" class="btn btn-primary col-md-12" data-toggle="modal" data-target="#exampleModal">
    Ajouter
    </button>
    <br>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ajout  de sentier</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="" method="POST" enctype="multipart/form-data" >
                <div class="form-group">
                    <label >Nom du sentier:</label>
                    <input type="text" class="form-control" name="nom_sentier" required>
                </div>

                <div class="form-group">
                    <label >Choisir la forêt qui contient le sentier</label>
                    
                    <select class="form-control" name="id_foret" required>
                    <option value="" disabled selected>----------</option>
                    <?php 
                        $requete=$bdd->query("SELECT * from foret");                                               
                        while ($reponse = $requete->fetch()){
                    ?>
                    <option value="<?= $reponse['id_foret']; ?>"><?= $reponse['nom_foret']; ?></option>
                    <?php } ?>

                    </select>
                </div>

                <div class="form-group">
                    <label >Description du sentier</label>
                    <textarea class="form-control" name="description_sentier" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label >Photo illustration du sentier</label>
                    <input type="file" name="image_sentier" required>
                </div>

                <button type="submit" name="valider" class="btn btn-primary mb-2 col-md-12">Ajouter</button>
                
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            
        </div>
        </div>
    </div>
    </div>

    <!--fin du modal pour l'ajout -->

    <!-- modal pour la modification  -->


    <!-- Modal -->
    <div id="update" class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modification de sentier</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">

            <form action="" method="POST" enctype="multipart/form-data" >

            <?php 
          
                $requete=$bdd->prepare("SELECT * from sentier where id_utilisateur=? and id_sentier = ? ");
                $requete->execute(array($_SESSION['id_utilisateur'],htmlspecialchars($_GET['id_modification'])));
                
                $rep = $requete->fetch();
                    
            ?>
                <div class="form-group">
                    <label >Nom du sentier:</label>
                    <input type="text" class="form-control" name="nom_sentier" value="<?= $rep['nom_sentier']; ?>" required>
                </div>
                

                <div class="form-group">
                    <label >Choisir la forêt qui contient le sentier</label>
                    
                    <select class="form-control" name="id_foret" required>
                    <option value="" disabled selected>----------</option>
                    <?php 
                        $requete=$bdd->query("SELECT * from foret");                                               
                        while ($reponse = $requete->fetch()){
                    ?>
                    <option <?php if ($rep['id_foret']==$reponse['id_foret']){echo "SELECTED"; } ?> value="<?= $reponse['id_foret']; ?>"><?= $reponse['nom_foret']; ?></option>
                    <?php } ?>

                    </select>
                </div>

                <div class="form-group">
                    <label >Description du sentier</label>
                    <textarea class="form-control" name="description_sentier" rows="3" required><?= $rep['description_sentier']; ?></textarea>
                </div>

                <div class="form-group">
                    <label >Photo illustration du sentier</label>
                    <input type="file" name="image_sentier" value="<?= $rep['photo_sentier']; ?>" required>
                </div>

                <button type="submit" name="modifier" class="btn btn-primary mb-2 col-md-12">Modifier</button>
                
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            
        </div>
        </div>
    </div>
    </div>

    <!--fin du modal pour la modification -->

    <!-- modal pour voir les infos du sentier -->
    <div id="view" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLiveLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl "  role="document" >
        
            <div class="modal-content"  >

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLiveLabel">Details du sentier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body" >

                    <?php
                    if(isset($_GET['id_sentier'])){
                        $requete_msg=$bdd->prepare("SELECT * from sentier s, foret f where f.id_foret = s.id_foret and id_sentier = ? and id_utilisateur=? ");
                        $requete_msg->execute(array(htmlspecialchars($_GET['id_sentier']),$_SESSION['id_utilisateur'] ));
                        $reponse_msg = $requete_msg->fetch(); ?>
                        
                        <img src="images/<?=$reponse_msg['photo_sentier'] ;  ?>" style="width: 450px;"><br>
                        <label ><u>Nom de la foret contenant le sentier:</u>&nbsp;<?=$reponse_msg['nom_foret'] ; ?></label><br>
                        <label ><u>Nom du sentier:</u>&nbsp;<?=$reponse_msg['nom_sentier'] ; ?></label><br>
                        <label ><u>Date de création:</u>&nbsp;<?=$reponse_msg['date_creation_sentier'] ; ?></label><br>
                        <label ><u>Description du sentier:</u></label><br>
                        <p><?=$reponse_msg['description_sentier'] ;  ?></p>
                    
                    <?php } ?>
                   
           
                   
                </div>

                <div class="modal-footer">                
                    <button type="button" class="btn  btn-secondary" data-dismiss="modal">Fermer</button>                    
                </div>
            </div>
        
        </div>
        </div>
    <!--fin du modal pour voir les infos du sentier -->

    <!-- traitements -->

    <?php
        //ajout
        if(isset($_POST['valider'])){           
                 

            
             if ( strlen($_POST['nom_sentier'])>0 && strlen($_POST['id_foret'])>0 && strlen($_POST['description_sentier'])>0 ){	
            
            $nom_sentier =htmlspecialchars($_POST['nom_sentier']);
            $id_foret =htmlspecialchars($_POST['id_foret']);
            $description_sentier =htmlspecialchars($_POST['description_sentier']);        

            }else{
                $errors[]="Veuillez renseigner tous les champs.";
            }
            
            $dossier = 'images/';

            $fichier=$fichier="metaimage.jpg";
            if(!empty(basename($_FILES['image_sentier']['name']))){

                $fichier = basename($_FILES['image_sentier']['name']);
                $taille_maxi = 1048576;
                $taille=$_FILES['image_sentier']['size'];
                $extensions = array('.png', '.gif', '.jpg', '.jpeg','.PNG', '.GIF', '.JPG', '.JPEG');
                $extension = strrchr($_FILES['image_sentier']['name'], '.');
                if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
                {
                $errors[]='Votre image doit etre de type png, gif, jpg, jpeg';
                }
                if($taille>$taille_maxi){
                $errors[]="Le fichier est trop gros..(maxinum $taille_maxi ko) ";
                }

                $fichier = strtr($fichier,'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                $fichier = preg_replace('/([^.a-z0-9]+)/i', '_', $fichier);
                $target = $_SESSION['id_utilisateur'];
                $target .="_".time();
                $target .= $fichier;
                $fichier = preg_replace ("' 'i","",$target);
            }



            if(move_uploaded_file($_FILES['image_sentier']['tmp_name'], $dossier.$fichier))
            {
                
            }else{
                $errors[]="Votre image n'a pas puis être télécharger !";                
            }
            
            if(!empty($errors)){
                        
                foreach($errors as $error){ ?>
                    
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><?=$error; ?></strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                <?php }
            }
            else{

                $date = date("Y-m-d H:i:s");
                $requete=$bdd->prepare("INSERT INTO sentier(nom_sentier, id_foret, description_sentier, id_utilisateur, photo_sentier, date_creation_sentier, statut_sentier)VALUES(?,?,?,?,?,now(),0) ");            

                if( $requete->execute(array($nom_sentier,$id_foret, $description_sentier, $_SESSION['id_utilisateur'], $fichier  )) ){
                    ?>
                    <script > 
                    swal({
                        title: "Création de sentier.",
                        text: "Le sentier à été créé avec succès. il sera visible pour tous le monde lorsqu'il sera validé par un administrateur. " ,
                        icon: "success",
                        button: "OK",
                        }).then(function(){
                            window.location="gestion_sentier.php";
                            });
                    </script>

                    <?php
                            
                }else{

                    print_r($requete->errorInfo());
                    echo"<div class='alert alert-danger'>Opération non réussie.Veuillez réessayer</div>";
                }                
            }

        }

        //fin Ajout

        //Modification
        if(isset($_POST['modifier'])){           
                 

            
            if ( strlen($_POST['nom_sentier'])>0 && strlen($_POST['id_foret'])>0 && strlen($_POST['description_sentier'])>0 ){	
             
           $nom_sentier =htmlspecialchars($_POST['nom_sentier']);
           $id_foret =htmlspecialchars($_POST['id_foret']);
           $description_sentier =htmlspecialchars($_POST['description_sentier']);        

           }else{
               $errors[]="Veuillez renseigner tous les champs.";
           }
           
           $dossier = 'images/';

           $fichier=$fichier="metaimage.jpg";
           if(!empty(basename($_FILES['image_sentier']['name']))){

               $fichier = basename($_FILES['image_sentier']['name']);
               $taille_maxi = 1048576;
               $taille=$_FILES['image_sentier']['size'];
               $extensions = array('.png', '.gif', '.jpg', '.jpeg','.PNG', '.GIF', '.JPG', '.JPEG');
               $extension = strrchr($_FILES['image_sentier']['name'], '.');
               if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
               {
               $errors[]='Votre image doit etre de type png, gif, jpg, jpeg';
               }
               if($taille>$taille_maxi){
               $errors[]="Le fichier est trop gros..(maxinum $taille_maxi ko) ";
               }

               $fichier = strtr($fichier,'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
               $fichier = preg_replace('/([^.a-z0-9]+)/i', '_', $fichier);
               $target = $_SESSION['id_utilisateur'];
               $target .="_".time();
               $target .= $fichier;
               $fichier = preg_replace ("' 'i","",$target);
           }



           if(move_uploaded_file($_FILES['image_sentier']['tmp_name'], $dossier.$fichier))
           {
               
           }else{
               $errors[]="Votre image n'a pas puis être télécharger !";                
           }
           
           if(!empty($errors)){
                       
               foreach($errors as $error){ ?>
                   
                   <div class="alert alert-danger alert-dismissible fade show" role="alert">
                   <strong><?=$error; ?></strong>
                   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>
                   </div>
               <?php }
           }
           else{

               $date = date("Y-m-d H:i:s");
               $requete=$bdd->prepare("UPDATE  sentier set nom_sentier=?, id_foret=?, description_sentier=?,  photo_sentier=?,  statut_sentier=0 where id_sentier = ? ");            

               if( $requete->execute(array($nom_sentier,$id_foret, $description_sentier,  $fichier, $_GET['id_modification'] )) ){
                   ?>
                   <script > 
                   swal({
                       title: "Création de sentier.",
                       text: "Le sentier à été modifié avec succès. il sera visible pour tous le monde lorsqu'il sera validé par un administrateur. " ,
                       icon: "success",
                       button: "OK",
                       }).then(function(){
                           window.location="gestion_sentier.php";
                           });
                   </script>

                   <?php
                           
               }else{

                   print_r($requete->errorInfo());
                   echo"<div class='alert alert-danger'>Opération non réussie.Veuillez réessayer</div>";
               }                
           }

       }
        //fin modification

        //suppression
        if (isset($_GET['id_suppression'])){

            $requete=$bdd->prepare("UPDATE  sentier set   statut_sentier=2 where id_sentier = ? ");            

            if( $requete->execute(array($_GET['id_suppression'] )) ){
                ?>
                <script > 
                swal({
                    title: "Création de sentier.",
                    text: "Le sentier à été supprimé avec succès. il sera completement supprimé lorsqu'un administrateur aura validé la suppression. " ,
                    icon: "success",
                    button: "OK",
                    }).then(function(){
                        window.location="gestion_sentier.php";
                        });
                </script>

                <?php
                        
            }else{

                print_r($requete->errorInfo());
                echo"<div class='alert alert-danger'>Opération non réussie.Veuillez réessayer</div>";
            }
        }
        //fin suppression

        ?>

    <!-- fin des traitements-->

        
    </div>
       
  </div>

 
<footer class='footer mt-auto py-3 bg-light'>
  <center><label >&copy;20212228</label></center>
</footer>
</body>
<script src='bootstrap/js/bootstrap.min.js' ></script>

<script>

  function show_modal(){ $("#view").modal("show"); };
  function show_modal2(){ $("#update").modal("show"); };

  $('#myModal').on('shown.bs.modal', function () {
  $('#myInput').trigger('focus')
})

</script>
</html>