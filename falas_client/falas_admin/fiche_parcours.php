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
  

</head>

<body class='d-flex flex-column h-100' onload="window.print();setTimeout(() => { window.location='explore.php?id_parcours=<?=$_GET['id_parcours']?>';   }, 100);"  >


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
    

    
        
        <div class="card-body">
            <div class="card-header alert alert-primary"><?= $reponse['nom_parcours']; ?></div>
            <br/>
            <p><u>Date de création:</u> <?= $reponse['date_creation_parcours']; ?></p>   
            
            <div id="map" class= " card col-md-12" style="height: 500px;" > </div> 
            <br/>

            <h3>
              <u>Description</u>
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
            
            <div class="row">
              <div class="col col-md-10">
                <h2 class="h2"><u>Images</u></h2>  
              </div>
              <div class="col" >
                
              </div>
            </div>


            
            
            <div>
              <div class="row">
              <?php
                $requete = $bdd->prepare("SELECT * from images where id_parcours = ? and statut_img = 1 limit 4");
                $requete->execute(array($id_parcours));
                
                while( $rep=$requete->fetch() ){                  
              ?>
                <img src="images/<?=$rep['nom_img']?>" class=" col col-md-6" style="height:400px;  ">
                
              <?php } ?>
              </div>
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

 
<br>
<footer class='footer mt-auto py-3 bg-light'>
 
</footer>
</body>
<script src='bootstrap/js/bootstrap.min.js' ></script>

<script>

  function show_modal(){ $("#exampleModalCenter").modal("show"); };

  $('#myModal').on('shown.bs.modal', function () {
  $('#myInput').trigger('focus')
})

</script>
</html>