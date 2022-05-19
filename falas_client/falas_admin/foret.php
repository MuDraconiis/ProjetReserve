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



$id_foret= strip_tags($_GET['id_foret']) ;


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

  //création de la carte
  map = new google.maps.Map(document.getElementById("map"),options) 
  var gmarkers = [];

  const tourStops = [];

  //affichages des markers sur la carte
  <?php while($marker = $requete2->fetch()){ ?>

      var lat=<?php echo $marker['lat_ck']; ?>;
      var lng=<?php echo $marker['lng_ck']; ?>;
      var desc="<?php echo $marker['desc_ck']; ?>";
      
      tourStops.push( [{ lat: lat, lng: lng }, desc ] );
      

  <?php }; ?> 

  // Create an info window to share between markers.
  const infoWindow = new google.maps.InfoWindow();

  // Create the markers.
  tourStops.forEach(([position, title], i) => {
    const marker = new google.maps.Marker({
      position,
      map,
      title: `${i + 1}. ${title}`,
      label: `${i + 1}`,
      optimized: false,
    });

    // Add a click listener for each marker, and set up the info window.
    marker.addListener("click", () => {
      infoWindow.close();
      infoWindow.setContent(marker.getTitle());
      infoWindow.open(marker.getMap(), marker);
    });
  });


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

}

</script>

  <br>
  <main class="flex-shrink-0">

  <div class="container homeContainer"  >

  <?php
  $id_foret= strip_tags($_GET['id_foret']);
  $_SESSION['id_foret']=$id_foret;

  $requete=$bdd->prepare("SELECT * from foret where id_foret=?");
  $requete->execute(array($id_foret));
  $reponse = $requete->fetch();

  ?>
    <a href="index.php">Foret -></a>&nbsp;<label class="active"><?=$reponse['nom_foret']?></label>
    <div class="card">
        <div class="card-header">Position géographique</div>
        <div class="card-body">
            <blockquote class="blockquote mb-0"> 
              <div id="map" class= "col-md-12" style="height: 500px;"> </div>                  
            </blockquote>
        </div>
    </div>   
    <br/>

    <div class="row">      
      <div class="col col-md-6">
      <button class="btn btn-primary col-md-12" data-toggle="modal" data-target="#exampleModalCenter"> <i class="fa fa-"></i> Ajouter un repère</button>
      </div>
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

            <div class="card">
                <div class="card-header">Position géographique</div>
                <div class="card-body">
                    <blockquote class="blockquote mb-0"> 
                      <div id="map" class= "col-md-12" style="height: 500px;"> </div>                  
                    </blockquote>
                </div>
            </div> 

                
                <br/>

                <input class="btn btn-primary col-md-12" type="submit" name="enregistrer" value="Enregistrer">
            </form>
            <br/>
            <h3>        
                <?php            
                if(isset($_POST['enregistrer'])) {               

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
    <br>

    <div class="container card">
      <h1>Description</h1>
      <hr>
    <p><?= $reponse['description_foret']; ?></p>  
    </div>
    <br>

    
      <div class="card" >
        <br>
        <div><a href="all_parcours.php" class="btn btn-primary col ">Tous les parcours</a></div>
        <br>       
      </div>

      <br>
        
    </div>
       
  </div>

  <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
  <script
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyClyugYZlyjzeunU6tQrLdA3sz-7bICPgA&callback=initMap&v=weekly"
  async
  ></script>
  

 
  <?php require_once 'footer.php'; ?>

