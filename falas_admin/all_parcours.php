<?php require_once 'header.php'; ?>


  <br>
  <main class="flex-shrink-0">
  <div class="container homeContainer"  >

  <?php
  $id_foret= $_SESSION['id_foret'];
  $requete=$bdd->prepare("SELECT * from foret where id_foret=?");
  $requete->execute(array($id_foret));
  $reponse = $requete->fetch();

  ?>
    
    <a href="index.php">Foret -></a>&nbsp;<a href="foret.php?id_foret=<?=$id_foret?>"><?=$reponse['nom_foret']?> -></a>&nbsp;<label class="active">Tous les parcours</label>

    <div class="card">
      <h1>Les parcours réalisés dans cette foret</h1>
      <hr class="hr">

      <center>
      <div class="row col-md-12">
          <?php           
            $requete=$bdd->prepare("SELECT * from parcours p, utilisateur u where u.id_utilisateur = p.id_utilisateur and id_foret=?  ");
            $requete->execute(array($id_foret));
            while ($reponse = $requete->fetch()){
            
          ?>
          <div class=" card  col-md-4 col-sm-12  " >
              <center>
                <label ><?=$reponse['nom_parcours']; ?></label>
                <?php 
                if ($reponse['statut_parcours']==1){
                  echo "<span class='badge badge-pill badge-success'>Validé</span>";
                }
                ?>
                
              </center>
              
              <hr>
              <label >Ajouté par: <?=$reponse['nom_utilisateur'].' '.$reponse['prenom_utilisateur']?></label>              
              <label >publié le: <?=$reponse['date_creation_parcours']?></label><br>
              <a class="btn btn-primary" href="explore.php?id_parcours=<?=$reponse['id_parcours']; ?>">Explorer</a>
              <br/>
              <?php           
                $requete2=$bdd->prepare("SELECT count(*) as nbr from images where statut_img = 0 and id_parcours= ?  ");
                $requete2->execute(array($reponse['id_parcours']));
                $reponse2 = $requete2->fetch();
 
                if ($reponse2['nbr']>0){
                  echo "<span class='badge badge-pill badge-warning'>".$reponse2['nbr']." Images à valider</span>";
                }
                ?>



              <br>
          </div> 
          <br>
                  
          <?php }; ?>
          
        </center>

          
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