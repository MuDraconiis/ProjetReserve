<?php require_once 'header.php'; ?>

  <br>
  <main class="flex-shrink-0">
  <div class="container homeContainer"  >

  <div class="container">

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
              <img src="images/1.jpg" class="foret_img">
              <?php
              }else{          
              ?>
              <img src="images/<?php echo $reponse['photo_foret']; ?>" class="foret_img">
              <?php } ?>
              <br>
              <label ><?php echo $reponse['nom_foret']; ?></label>
              
            </a>
          </div>  
                

        <?php } ?>

      

        
        
    </div>
       
  </div>

 
  <?php require_once 'footer.php'; ?>
