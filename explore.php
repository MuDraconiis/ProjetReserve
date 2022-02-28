<?php 

require_once 'header.php'; 

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
                   
            </blockquote>
        </div>
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