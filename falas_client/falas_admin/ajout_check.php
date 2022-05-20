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
  

</head>

<body class='d-flex flex-column h-100' <?php if(isset($_GET['id_sentier'])){ echo 'onload="show_modal()"'; } ?> >

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
      <a href="index.php" class="nav-link">FENESTRA CORSA</a>
    </li>
 
  
    
    <li class="nav-item ">
      <a href="" class="nav-link">Compte</a>
    </li>    
    <li class="nav-item ">
      <a href="" class="nav-link">Gestion des forets</a>
    </li>
    <li class="nav-item ">
      <a href="" class="nav-link">Gestion des parcours</a>
    </li>
    <li class="nav-item ">
      <a href="" class="nav-link">Gestion des utilisateurs</a>
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


<?php 

$id_foret= strip_tags($_SESSION['id_foret']) ;

//recuperation du fichier gpx pour afficher les delimitation de la foret
$requete=$bdd->prepare("SELECT * from foret where id_foret=?");
$requete->execute(array($id_foret));
$reponse = $requete->fetch();

//recuperation de tous les markers du parcours pour les afficher sur la carte
$requete2=$bdd->prepare("SELECT * from check_point where id_foret=?");
$requete2->execute(array($id_foret));

?>

<script >    

function initMap(){    

  //definition des options pour afficher la corse par defaut sur la carte
  var options ={ center: {lat: 42.039604, lng: 9.012893}, zoom:9,mapTypeId: "hybrid" }

//pour les check points
map2 = new google.maps.Map(document.getElementById("map2"),options)

//afficher un point en cliquant
google.maps.event.addListener(map2, 'click', function(event) { placeMarker(event.latLng); });
 
 gmarkers = [];


//console.log(gmarkers);
 
function placeMarker(location) {

    if (gmarkers.length <= 0) {
        var marker = new google.maps.Marker({ position: location, map: map2 });
        gmarkers.push(marker.position.toUrlValue()); 
        google.maps.event.addListener(marker, "rightclick", function (point) {delMarker(marker); });  
        //var inputF = document.getElementById("lat_lng");
        //inputF.value=gmarkers[0];
        //inputF.setAttribute('value', gmarkers);
        document.getElementById('lat_lng').value = gmarkers[0];
    }     

    //google.maps.event.addListener(marker, "click", function (point) {alert(marker.position.toUrlValue())}); 
    
    console.log(gmarkers[0]);
}    

//affichage d'un gpx

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
              strokeColor: "#FF00AA",
              strokeOpacity: .7,
              strokeWeight: 4
         });
         poly.setMap(map2);
         // fit bounds to track
         map2.fitBounds(bounds);
    }
});

//fin de l'affichage du gpx

//Ajout d'un marker sur la carte
function addMarker(location) {
    marker = new google.maps.Marker({
        position: location,
        map: map2
    });
}
// fin de l'ajout d'un marker sur la carte

//Suppression d'un marker 
var delMarker = function (markerPar) { 
    markerPar.setMap(null); 
    gmarkers.pop();
    var inputF = document.getElementById("lat_lng");
    inputF.innerHTML="";
}
//fin de la suppression d'un marker



}

function ping(){
    return gmarkers[0];
    
    
}

</script>

  <br>
  <main class="flex-shrink-0">

  <div class="container homeContainer"  >

  <?php

  $requete=$bdd->prepare("SELECT * from foret where id_foret=?");
  $requete->execute(array($id_foret));
  $reponse = $requete->fetch();

  ?>
    <a href="index.php">Foret -></a>&nbsp;<a href="foret.php?id_foret=<?=$id_foret?>"><label class=""><?=$reponse['nom_foret']?>-></a>&nbsp;<label class="active">Ajout de check point</label>
    <div class="card">
        <div class="card-header">Position géographique</div>
        <div class="card-body">
            <blockquote class="blockquote mb-0"> 
              <div id="map2" class= "col-md-12" style="height: 500px;"> </div>                  
            </blockquote>
        </div>
    </div>   
    <br/>

    <div class="row">      
      <div class="col col-md-12">

      <form action="" method="POST" enctype="multipart/form-data">
          <div class="col col-md-4">
              Coordonnées du check point:             
              <input type="text" name="lat_lng" id="lat_lng"  class="form-control"    required value="" >
          </div>
          <br>
          <div>
              Description:              
              <textarea name="desc_ck"  class="form-control" cols="30" rows="10" required><?=@htmlspecialchars($_POST['desc_ck'])?></textarea>
          </div>
          <br>
          <div>
              Fichier audio pour le guide vocal:
              <input type="file" class="form-control" name="audio" required>
          </div>
          <br>
          <input type="submit" class="btn btn-primary col-md-12" name="ajout" value="Ajouter">
      </form>

      </div>
    </div>
    <br>
    <h3>        
        <?php            
        if(isset($_POST['ajout'])) { 
        
            //image
            $extensions = array('mp3');
            // File upload path 
            $img = time().basename($_FILES['audio']['name']); 
            $img_path = "audio/" . $img;              
            // Check whether file type is valid 
            $fileType = pathinfo($img_path, PATHINFO_EXTENSION); 
            if(in_array($fileType, $extensions)){ 
                // Upload file to server 
                if(move_uploaded_file($_FILES["audio"]["tmp_name"], $img_path)){ 
                    
                }else{ 
                    $errors[]= "Audio download failed";
                } 
            }else{ 
            $errors[]= "Audio File type error, must be mp3";
            }


            $lat_lng=$_POST['lat_lng'];

            var_dump($img);
            $coord=explode(",",$lat_lng);                
            $desc=htmlspecialchars($_POST['desc_ck']);
            var_dump($coord);
            

            $id_user=$_SESSION['id_utilisateur'];

            if (empty($errors)){

            $requete = $bdd->prepare("INSERT into check_point(lat_ck, lng_ck, desc_ck, id_foret, statut_ck, audio, id_parcours) values (?, ?, ?, ?, ?, ?, ?) ");

            //$requete->execute(array($coord[0], $coord[1], $desc, $id_foret, 1, "aa", 3));
            //print_r($requete->errorInfo());
            echo "ping";echo "ping";echo "ping";echo "ping";echo "ping";echo "ping";echo "ping";echo "ping";echo "ping";echo "ping";echo "ping";echo "ping";echo "ping";
            if ($requete->execute(array($coord[0], $coord[1], $desc, $id_foret, 1, "aa", 3))){ ?>

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

  <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
  <script
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyClyugYZlyjzeunU6tQrLdA3sz-7bICPgA&callback=initMap&v=weekly"
  async
  ></script>
  

 
  <?php require_once 'footer.php'; ?>

