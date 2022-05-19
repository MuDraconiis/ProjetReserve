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

<body class='d-flex flex-column h-100' <?php if(isset($_POST['modifier'])){ echo 'onload="show_modal2()"'; } ?>  <?php if(isset($_POST['ajouter'])){ echo 'onload="show_modal()"'; } ?> >

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
 
  <?php if (!empty($_SESSION['id_utilisateur'])){ ?>

    <li class="nav-item active">
      <a href="inex.php" class="nav-link">FENESTRA CORSA</a>
    </li>
    <li class="nav-item ">
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


<?php 



$id_foret= $_SESSION['id_foret'];
$requete=$bdd->prepare("SELECT nom_foret from foret where id_foret=?");
$requete->execute(array($id_foret));
$nom_foret=$requete->fetch();

//recuperation du fichier gpx pour afficher les delimitation de la foret
$requete=$bdd->prepare("SELECT * from foret where id_foret=?");
$requete->execute(array($id_foret));
$reponse = $requete->fetch();

//recuperation de tous les markers du parcours pour les afficher sur la carte
$id_parcours= strip_tags($_GET['id_parcours']);  
$requete2=$bdd->prepare("SELECT * from marker where id_parcours=?");
$requete2->execute(array($id_parcours));

?>

<script >
    

function initMap(){    

//definition des options pour afficher la corse par defaut sur la carte
var options ={ center: {lat: 42.039604, lng: 9.012893}, zoom:9 }

//création de la carte
map = new google.maps.Map(document.getElementById("map"),options) 
var gmarkers = [];




//affichage des delimitation de la foret
$.ajax({ type: "GET", url: "gpx_foret/<?php echo $reponse['delimitation_foret']; ?>", dataType: "xml",
    success: function (xml) { var points = []; var bounds = new google.maps.LatLngBounds();
    $(xml).find("trkpt").each(function () {
        var lat = $(this).attr("lat");
        var lon = $(this).attr("lon");
        var p = new google.maps.LatLng(lat, lon);
        points.push(p);
        bounds.extend(p);
    });

    var poly = new google.maps.Polyline({
        // use your own style here
        path: points,
        strokeColor: "#3AF30B",
        strokeOpacity: .7,
        strokeWeight: 4
    });

    poly.setMap(map);
    // fit bounds to track
    map.fitBounds(bounds);
    }
});


//fonction d'ajout des markers
function addMarker(location) {
    marker = new google.maps.Marker({
        position: location,            
        map: map
    });

}

const flightPlanCoordinates = [];

    <?php while($marker = $requete2->fetch()){ ?>

    var lat=<?php echo $marker['lat']; ?>;
    var lng=<?php echo $marker['lng']; ?>;

    flightPlanCoordinates.push({ lat: lat, lng: lng })

<?php }; ?>




  const flightPath = new google.maps.Polyline({
    path: flightPlanCoordinates,
    geodesic: true,
    strokeColor: "#3E5CEA",
    strokeOpacity: 1.0,
    strokeWeight: 2,
  });

  flightPath.setMap(map);


}

</script>

  <br>
  <main class="flex-shrink-0">
  <div class="container homeContainer"  >

  <?php
  $id_parcours= strip_tags($_GET['id_parcours']);

  
  $requete=$bdd->prepare("SELECT * from parcours where id_parcours=?");
  $requete->execute(array($id_parcours));
  $reponse = $requete->fetch();

  ?>
    <a href="index.php">Foret -></a>&nbsp;
    <a href="foret.php?id_foret=<?=$id_foret?>"><?=$nom_foret['nom_foret'] ?> -></a>&nbsp;
    <a href="all_parcours.php">Tous les parcours -></a>&nbsp;
    <label class="active"><?=$reponse['nom_parcours'] ?></label>

    <div class="card">
        <div class="card-header"><?= $reponse['nom_parcours']; ?></div>
        <div class="card-body">
            <blockquote class="blockquote mb-0">
            <p><?= $reponse['date_creation_parcours']; ?></p>   
            <hr>
            <div id="map" class= "col-md-12" style="height: 500px;" > </div> 
            <br/>

            <h3>
            <div class="row">
              <div class="col col-md-10">
                <h2 class="h2"><u>Description </u></h2>  
              </div>

              <?php 
              if( @$_SESSION['id_utilisateur'] == $reponse['id_utilisateur'] || @$_SESSION['type_utilisateur'] == "Admin" ){ ?>

                <div class="col" >
                  <button class="btn btn-primary col-md-12" data-toggle="modal" data-target="#exampleModalCenter2"> <i class="fa fa-"></i> Modifier</button>
                </div>

              <?php } ?>  
            <!-- Modal -->
            <div class="modal fade" id="exampleModalCenter2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content ">
                  <div class="modal-header">
                    <center><h5 class="modal-title" id="exampleModalLongTitle">Modification de la description du parcours</h5></center>                    
                    
                  </div>
                  <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        <textarea class="form-control"  value="" id="editor" rows="10" name="desc"  ><?=$reponse['description_parcours']?></textarea>
                        
                        <br/>
                        <input class="btn btn-primary col-md-12" type="submit" name="modifier" value="Modifier">
                    </form>
                    <br/>
                    <h3>        
                        <?php            
                        if(isset($_POST['modifier'])) {  

                            $desc=$_POST['desc'];
                            $requete = $bdd->prepare("UPDATE parcours set description_parcours = ? where id_parcours = ?");
                            if ($requete->execute(array($desc, $id_parcours))){ ?>

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

                            <?php }                            
                        
                        } ?> 
                    </h3> 

                  </div>
                  <div class="modal-footer">
                    <a href="explore.php?id_parcours=<?=$id_parcours?>" class="btn btn-secondary" >Fermer</a>
                    
                  </div>
                </div>
              </div>
            </div>
            <!-- Modal -->
              


            </div>
            </h3>
            
            <p><?=$reponse['description_parcours']?> </p>
            

            <hr> 

            <h2 class="h2"><u>Point de controle</u></h2>   
            <div>
            <?php
                $requete = $bdd->prepare("SELECT * from check_point where id_parcours = ? and statut_ck = 1");
                $requete->execute(array($id_parcours));
                $i=0;
                while( $rep=$requete->fetch() ){   
                  $i++;               
              ?>
                <div class="card">
                  <label for=""><?=$i?>: </label>
                <p><?=$rep['desc_ck']?></p>
                </div>
                <br/>          
                
              <?php } ?>              

            </div>  
            <?php
            $requete = $bdd->prepare("SELECT count(*) as nbr from images where id_parcours = ? and statut_img = 1");
            $requete->execute(array($id_parcours));
            $rep=$requete->fetch();            
            ?>
            <div class="row">
              <div class="col col-md-10">
                <h2 class="h2"><u>Images (<?=$rep['nbr']?>)</u></h2>  
              </div>
              
              <?php 
              if( !empty($_SESSION['id_utilisateur']) ){ ?>

              <div class="col" >
                <button class="btn btn-primary col-md-12" data-toggle="modal" data-target="#exampleModalCenter"> <i class="fa fa-"></i> Ajouter</button>
              </div>

              <?php } ?>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Ajout d'images</h5>
                    
                  </div>
                  <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        Select Image Files to Upload: <br/>
                        <input class="form-control" type="file" name="files[]" multiple >
                        <br/>
                        <input class="btn btn-primary col-md-12" type="submit" name="ajouter" value="UPLOAD">
                    </form>
                    <br/>
                    <h3>        
                        <?php            
                        if(isset($_POST['ajouter'])) { 

                          // File upload configuration 
                          $targetDir = "images/"; 
                          $allowTypes = array('png', 'jpg', 'jpeg','PNG', 'JPG', 'JPEG'); 
                          
                          $statusMsg = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = ''; 
                          $fileNames = array_filter($_FILES['files']['name']); 

                          if(!empty($fileNames)){ 

                              foreach($_FILES['files']['name'] as $key=>$val){ 
                                  // File upload path 
                                  $fileName = time().basename($_FILES['files']['name'][$key]); 
                                  $id_user=@$_SESSION['id_utilisateur'];

                                  $targetFilePath = $targetDir . $fileName; 
                                  
                                  // Check whether file type is valid 
                                  $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                                  if(in_array($fileType, $allowTypes)){ 
                                      // Upload file to server 
                                      if(move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)){ 
                                          // Image db insert sql 
                                          $insertValuesSQL .= "('".$fileName."', NOW(), 0, $id_parcours, $id_user ),"; 
                                      }else{ 
                                          $errorUpload .= $_FILES['files']['name'][$key].' | '; 
                                      } 
                                  }else{ 
                                      $errorUploadType .= $_FILES['files']['name'][$key].' | '; 
                                  } 
                              } 
                              
                              // Error message 
                              $errorUpload = !empty($errorUpload)?'Upload Error: '.trim($errorUpload, ' | '):''; 
                              $errorUploadType = !empty($errorUploadType)?'File Type Error: '.trim($errorUploadType, ' | '):''; 
                              $errorMsg = !empty($errorUpload)?'<br/>'.$errorUpload.'<br/>'.$errorUploadType:'<br/>'.$errorUploadType; 
                              
                              if(!empty($insertValuesSQL)){ 
                                  $insertValuesSQL = trim($insertValuesSQL, ','); 
                                  // Insert image file name into database 
                                  $insert = $bdd->query("INSERT INTO images (nom_img, date_ajout_img, statut_img, id_parcours, id_utilisateur) VALUES $insertValuesSQL"); 
                                  if($insert){ 
                                      $statusMsg = "Files are uploaded successfully, your files will appear after admin validation".$errorMsg; 
                                  }else{ 
                                      $statusMsg = "Sorry, there was an error uploading your file."; 
                                  } 
                              }else{ 
                                  $statusMsg = "Upload failed! ".$errorMsg; 
                              } 
                          }else{ 
                              $statusMsg = 'Please select a file to upload.'; 
                          }
                          
                          echo $statusMsg;
                            
                            
                        
                        } ?> 
                    </h3> 

                  </div>
                  <div class="modal-footer">
                  <a href="explore.php?id_parcours=<?=$id_parcours?>" class="btn btn-secondary" >Fermer</a>
                    
                  </div>
                </div>
              </div>
            </div>
            <!-- Modal -->
            
            
            <div>
              <div class="slider">
              <?php
                $requete = $bdd->prepare("SELECT * from images where id_parcours = ? and statut_img = 1");
                $requete->execute(array($id_parcours));
                
                while( $rep=$requete->fetch() ){                  
              ?>
                <img src="images/<?=$rep['nom_img']?>" style="height:400px">
                
              <?php } ?>
              </div>
            </div>

            <hr>
            <center>
              <a class="btn btn-primary" href="fiche_parcours.php?id_parcours=<?=$id_parcours?>" target="_blank">Imprimer la fiche de ce parcours</a>
            </center>
            
            



            </blockquote>
        </div>
    </div>   
    <br>
  


        
    </div>

  </div>

  <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyClyugYZlyjzeunU6tQrLdA3sz-7bICPgA&callback=initMap&v=weekly" async > </script>
  
  

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