<?php 

require_once 'header.php'; 

$id_foret= $_SESSION['id_foret'];


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

