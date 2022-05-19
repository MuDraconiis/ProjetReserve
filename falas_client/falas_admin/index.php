<?php 
require 'bdd.php';
if (empty($_SESSION['id_utilisateur']) || @$_SESSION['type_utilisateur'] <> "Admin" ){
  header('location:connexion.php');
}

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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <link rel='stylesheet' href='bootstrap/css/bootstrap.min.css' > 
  <link rel="stylesheet" href="bootstrap/css/style.css">
  <script src='bootstrap/js/bootstrap.min.js' ></script>
  

  <script src="https://requirejs.org/docs/release/2.3.5/minified/require.js"></script>
  <script type="text/javascript"   src="http://geoxml3.googlecode.com/svn/branches/polys/geoxml3.js"></script>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">
  
  <script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>
  

  <script>
    $(document).ready(function(){
      $('.slider').bxSlider();
    });
  </script>

<script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/classic/ckeditor.js"></script>


  

</head>

<body class='d-flex flex-column h-100' <?php if(isset($_GET['id_foret'])){ echo 'onload="show_modal2()"'; } ?>   <?php if(isset($_POST['enregistrer'])){ echo 'onload="show_modal()"'; } ?> >

  <!--
    <header>
    <nav class='navbar navbar-expand-md navbar-dark fixed-top bg-dark'>
      <div class='container-fluid'>
        <a class='navbar-brand' href='index.php'>FENESTRA CORSA</a>        
      </div>             
    </nav>
  </header><br><br>
 -->

  <nav class="navbar navbar-expand-lg navbar-dark bg-primary rounded">
  
  <ul class="navbar-nav mr-auto">
 
  

    <li class="nav-item active">
      <a href="inex.php" class="nav-link">FENESTRA CORSA</a>
    </li>
    <li class="nav-item ">
      <a href="" class="nav-link">Compte</a>
    </li>    

    <li class="nav-item ">
      <a href="users.php" class="nav-link">Gestion des utilisateurs</a>
    </li>
    
  </ul>

  <ul class="navbar-nav ml-auto">

  <?php if (empty($_SESSION['id_utilisateur'])){ ?>

  <li class="nav-item active">
    <a href="connexion.php" class="nav-link">Connexion</a>
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

  <div class="container">
    
  <div class="row">
    <div class="col col-md-10">
      <h2 class="h2"><u>Gestion des forêts</u></h2>  
    </div> 
    <div class="col" >
      <button class="btn btn-primary col-md-12" data-toggle="modal" data-target="#exampleModalCenter"> <i class="fa fa-"></i> Ajouter</button>
    </div>           
    
  </div>

    <div class="row">   

        <?php
        $requete=$bdd->query("SELECT * from foret");
        while($reponse = $requete->fetch()){
        
        ?>
          
          <div class="col-sm-12 card col-md-4 " >

            <a class="btn" href="foret.php?id_foret=<?php echo $reponse['id_foret']; ?>">

              <?php 
              if ($reponse['photo_foret']==null){
                ?>
              <img src="../images/1.jpg" class="foret_img">
              <?php
              }else{          
              ?>
              <img src="../images/<?php echo $reponse['photo_foret']; ?>" class="foret_img">
              <?php } ?>
              <br>
              <label ><?php echo $reponse['nom_foret']; ?></label>
              
            </a>
            <div class="row">
              <div class="col col-md-12">
                <a class="btn btn-primary col-md-12" href="index.php?id_foret=<?=$reponse['id_foret']?>">Modifier</a>
              </div>
              
            </div>
            
            

          </div>  
                

        <?php } ?>      
        
    </div>

    

    
<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <center><h5 class="modal-title" id="exampleModalLongTitle">Ajout d'une forêt</h5></center>                    
        
      </div>
      <div class="modal-body">
        <form action="" method="POST" enctype="multipart/form-data">

            Nom:
            <input type="text" name="nom" class="form-control" value="<?=@$_POST['nom']?>" required>
            <br/>

            Description de la forêt:
            <textarea class="form-control"  value="" id="editor" rows="5" name="desc" required ><?=@$_POST['desc']?></textarea>
            <br/>

            Trace GPX de la forêt:
            <input type="file" class="form-control" name="gpx" required>
            <br/>

            Photo de la forêt:
            <input type="file" class="form-control" name="img" required>
            <br/>

            <input class="btn btn-primary col-md-12" type="submit" name="enregistrer" value="Enregistrer">
        </form>
        <br/>
        <h3>        
            <?php            
            if(isset($_POST['enregistrer'])) { 
            
              //image
              $extensions = array('png', 'jpg', 'jpeg','PNG', 'JPG', 'JPEG');
              // File upload path 
              $img = time().basename($_FILES['img']['name']); 
              $img_path = "../images/" . $img;              
              // Check whether file type is valid 
              $fileType = pathinfo($img_path, PATHINFO_EXTENSION); 
              if(in_array($fileType, $extensions)){ 
                  // Upload file to server 
                  if(move_uploaded_file($_FILES["img"]["tmp_name"], $img_path)){ 
                      
                  }else{ 
                      $errors[]= "IMG download failed";
                  } 
              }else{ 
                $errors[]= "IMG File type error, must be 'png', 'jpg', 'jpeg','PNG', 'JPG', 'JPEG' ";
              }

              //gpx
              $ext_gpx = array('gpx', 'GPX');
              // File upload path 
              $gpx = time().basename($_FILES['gpx']['name']); 
              $gpx_path = "../gpx_foret/" . $gpx;              
              // Check whether file type is valid 
              $fileType = pathinfo($gpx_path, PATHINFO_EXTENSION); 
              if(in_array($fileType, $ext_gpx)){ 
                  // Upload file to server 
                  if(move_uploaded_file($_FILES["gpx"]["tmp_name"], $gpx_path)){ 
                      
                  }else{ 
                      $errors[]= "GPX download failed";
                  } 
              }else{ 
                $errors[]= "GPX File type error, must be 'gpx', 'GPX' ";
              }


              $nom=htmlspecialchars($_POST['nom']);                
              $desc=htmlspecialchars($_POST['desc']);                
              $id_user=$_SESSION['id_utilisateur'];

              if (empty($errors)){

                $requete = $bdd->prepare("INSERT into foret(nom_foret, description_foret, delimitation_foret, photo_foret, id_utilisateur) values (?, ?, ?, ?, ?) ");

                if ($requete->execute(array($nom, $desc, $gpx, $img, $id_user))){ ?>

                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Enregistrement effectuée.</strong> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                <?php }else{ ?>

                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Erreur lors de l'enregistrement.</strong> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                <?php 
                  } 

              }else{
                foreach($errors as $error){ ?>
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><?=$error?></strong> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                <?php 
                }
              }          
            
            } ?> 
        </h3> 

      </div>
      <div class="modal-footer">
        <a href="index.php" class="btn btn-secondary" >Fermer</a>
        
      </div>
    </div>
  </div>
</div>
<!-- Modal -->

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <center><h5 class="modal-title" id="exampleModalLongTitle">Modification d'une forêt</h5></center>        
      </div>
      <div class="modal-body">
        <?php
          $id_foret=htmlspecialchars($_GET['id_foret']);
          $requete2=$bdd->prepare("SELECT * from foret where id_foret = ?");
          $requete2->execute(array($id_foret));
          $reponse2 = $requete2->fetch();
        
        ?>
        <form action="" method="POST" enctype="multipart/form-data">

            Nom:
            <input type="text" name="nom" class="form-control" value="<?=$reponse2['nom_foret']?>" required>
            <br/>

            Description de la forêt:
            <textarea class="form-control"  value="" id="editor" rows="5" name="desc" required ><?=$reponse2['description_foret']?></textarea>
            <br/>

            Trace GPX de la forêt:&nbsp;<span class='badge badge-pill badge-secondary'><?=$reponse2['delimitation_foret']?></span>
            <input type="file" class="form-control" name="gpx" required value="<?=$reponse2['delimitation_foret']?>" >
            
            <br/>

            Photo de la forêt:&nbsp;<span class='badge badge-pill badge-secondary'><?=$reponse2['photo_foret']?></span>
            <input type="file" class="form-control" name="img" required value="<?=$reponse2['photo_foret']?>">
            
            <br/>

            <input class="btn btn-primary col-md-12" type="submit" name="modifier" value="Modifier">
        </form>
        <br/>
        <h3>        
            <?php            
            if(isset($_POST['modifier'])) { 
            
              //image
              $extensions = array('png', 'jpg', 'jpeg','PNG', 'JPG', 'JPEG');
              // File upload path 
              $img = time().basename($_FILES['img']['name']); 
              $img_path = "../images/" . $img;              
              // Check whether file type is valid 
              $fileType = pathinfo($img_path, PATHINFO_EXTENSION); 
              if(in_array($fileType, $extensions)){ 
                  // Upload file to server 
                  if(move_uploaded_file($_FILES["img"]["tmp_name"], $img_path)){ 
                      
                  }else{ 
                      $errors[]= "IMG download failed";
                  } 
              }else{ 
                $errors[]= "IMG File type error, must be 'png', 'jpg', 'jpeg','PNG', 'JPG', 'JPEG' ";
              }

              //gpx
              $ext_gpx = array('gpx', 'GPX');
              // File upload path 
              $gpx = time().basename($_FILES['gpx']['name']); 
              $gpx_path = "../gpx_foret/" . $gpx;              
              // Check whether file type is valid 
              $fileType = pathinfo($gpx_path, PATHINFO_EXTENSION); 
              if(in_array($fileType, $ext_gpx)){ 
                  // Upload file to server 
                  if(move_uploaded_file($_FILES["gpx"]["tmp_name"], $gpx_path)){ 
                      
                  }else{ 
                      $errors[]= "GPX download failed";
                  } 
              }else{ 
                $errors[]= "GPX File type error, must be 'gpx', 'GPX' ";
              }


              $nom=htmlspecialchars($_POST['nom']);                
              $desc=htmlspecialchars($_POST['desc']);                
              $id_user=$_SESSION['id_utilisateur'];

              if (empty($errors)){

                $requete = $bdd->prepare("UPDATE foret set nom_foret=?, description_foret=?, delimitation_foret=?, photo_foret=? where id_foret =? ");

                if ($requete->execute(array($nom, $desc, $gpx, $img, $id_foret))){ ?>

                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Modification effectuée.</strong> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                <?php }else{ ?>

                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Erreur lors de la modification.</strong> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                <?php 
                  } 

              }else{
                foreach($errors as $error){ ?>
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><?=$error?></strong> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                <?php 
                }
              }          
            
            } ?> 
        </h3> 

      </div>
      <div class="modal-footer">
        <a href="index.php" class="btn btn-secondary" >Fermer</a>
        
      </div>
    </div>
  </div>
</div>
<!-- Modal -->

  </div>

 
  <br>

<footer class='footer mt-auto py-3 bg-light'>
  <center><label >&copy;20212228</label></center>
</footer>

</body>
<script src='bootstrap/js/bootstrap.min.js' ></script>
<script>
    ClassicEditor.create( document.querySelector( '#editor' ) ).catch( error => {
            console.error( error );
        } );
</script>

<script>

  function show_modal(){ $("#exampleModalCenter").modal("show"); };
  function show_modal2(){ $("#exampleModalCenter2").modal("show"); };
 

  $('#myModal').on('shown.bs.modal', function () {
  $('#myInput').trigger('focus')
})

</script>
</html>
