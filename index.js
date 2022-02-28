

function initMap(){

    var options ={ center: {lat: 42.039604, lng: 9.012893}, zoom:9 }

    //afficher un point de maniere statique
    map = new google.maps.Map(document.getElementById("map"),options)

    //afficher un point en cliquant
    //google.maps.event.addListener(map, 'click', function(event) { placeMarker(event.latLng); });
     
     var gmarkers = [];

    //affichage des markers provenant de la base de données
     $.ajax({
        type : 'GET',
        url : 'get_markers.php',
        dataType : "json", 
        success : function(data){       

            $.each(data, function(i, obj) {
                //console.log(data[i][3], data[i][4]);
                addMarker(new google.maps.LatLng(data[i][3], data[i][4]));
                gmarkers.push(data[i][3].concat(',',data[i][4]));
                //google.maps.event.addListener(data[i][4], "rightclick", function (point) {delMarker(data[i][4])});  
            })
        }
    });


    // fin de l'affichage des markers provenant de la bdd

    //console.log(gmarkers);


    //affichage d'un gpx
    
    $.ajax({ type: "GET", url: "gpx_foret/gp.gpx", dataType: "xml",
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
             poly.setMap(map);
             // fit bounds to track
             map.fitBounds(bounds);
        }
   });
   
   //fin de l'affichage du gpx

   //Ajout d'un marker sur la carte
   function addMarker(location) {
        marker = new google.maps.Marker({
            position: location,            
            map: map,

        });

    }
    // fin de l'ajout d'un marker sur la carte



    



   




























}


    

   
  

    







        



   







  
  

 
    





