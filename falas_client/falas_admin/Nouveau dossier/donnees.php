<?php
   $con=mysqli_connect("localhost","root","","map");

   $query = mysqli_query($con,"select * from markers");
   $data = mysqli_fetch_all($query);
   
   //var_dump($data);
   echo json_encode($data);
                   

                
 
 ?>