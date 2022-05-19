
<?php require_once 'header.php'; ?>
<script src="./sentier.js" ></script>

  <br>
  <main class="flex-shrink-0">
  <div class="container homeContainer"  >

  <?php
  $id_sentier= strip_tags($_GET['id_sentier']);
  $requete=$bdd->prepare("SELECT * from sentier where id_sentier=? and statut_sentier=1");
  $requete->execute(array($id_sentier));
  $reponse = $requete->fetch();

  ?>
    
    <div class="card">
    <div class="card-header"><h1>Le sentier <?=$reponse['nom_sentier']; ?> </h1>  </div>
        <div class="card-body">    
            
            <?php /*if(empty($reponse['photo_sentier'])): ?>  
              <img src="images/sentier_par_defaut.jpg" class="">
            <?php else: ?>
              <img src="images/<?=$reponse['photo_sentier']; ?>" class="">
            <?php endif;*/ ?>

            <p><?=$reponse['description_sentier']; ?></p>            
        </div>
    </div>   
    <br>

    <div class="card">
        <div class="card-header"><h1>Localisation du sentier</h1>  </div>
        <div class="card-body">
            <blockquote class="blockquote mb-0">
                </blockquote>
                <div id="map" style="height: 300px; width: 1050px;"> </div>             
        </div>
    </div> 
    <br>

    <div class="card">
        <div class="card-header">
            <?php
            $requete=$bdd->prepare("SELECT * from photo where id_sentier=? and statut_photo=1");
            $requete->execute(array($id_sentier));
            $nb=$requete->rowCount();
            ?>
            
            <h1>Les photos (<?=$nb; ?>) </h1>
        </div>
        <div class="card-body">
            <blockquote class="blockquote mb-0">
                <div class="row">
                    <?php
                     $requete=$bdd->prepare("SELECT * from photo where id_sentier=? and statut_photo=1");
                     $requete->execute(array($id_sentier));
                     while ($reponse = $requete->fetch()){
                    ?>

                    <a href=""><img src="images/<?=$reponse['chemin_photo']; ?>" class=" col visualisation_photo"></a> 

                    <?php } ?>                   
                </div>
            </blockquote>
        </div>
    </div> 
    <br>

    <div class="card">
    <div class="card-header">
            <?php
            $requete=$bdd->prepare("SELECT * from check_point where id_sentier=? and statut_ck_pt=1");
            $requete->execute(array($id_sentier));
            $nb=$requete->rowCount();
            ?>
            
            <h1>Les check-points (<?=$nb; ?>) </h1>
        </div>
        <div class="card-body">
            <blockquote class="blockquote mb-0">
                <div class="row"> 
                    
                    <?php
                     $requete=$bdd->prepare("SELECT * from check_point c, utilisateur u where u.id_utilisateur = c.id_utilisateur and id_sentier=? and statut_ck_pt=1");
                     $requete->execute(array($id_sentier));
                     while ($reponse = $requete->fetch()){
                    ?>

                    <div class="container col foret_conteneur" >
                        <a class="" href="">
                            <label><?= $reponse['nom_utilisateur'].' '.$reponse['prenom_utilisateur']; ?></label><br>
                            <label>Publié le <?= $reponse['date_creation_ck_pt']; ?></label><br>
                            <label><?= $reponse['type_ck_pt']; ?></label><br>
                            <img src="images/<?= $reponse['photo_ck_pt']; ?>" class="check_point_img">                       
                        </a>
                    </div>

                    <?php } ?>  

                    
            </blockquote>
        </div>
    </div>
    <br> 

    
       
  </div>

    <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
    <script
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyClyugYZlyjzeunU6tQrLdA3sz-7bICPgA&callback=initMap&v=weekly"
  async
  ></script>

 
<?php require_once 'footer.php'; ?>
