<?php
        session_start();

        $db_host = "mysql-theroot163.alwaysdata.net";
        $db_name = "theroot163_tpsecurite";
        $db_user = "241507";
        $db_pass = "falasorma";

        /*$db_host = "localhost";
        $db_name = "falas";
        $db_user = "root";
        $db_pass = "";*/
        try {
            //code...
            $bdd=$bdd = new PDO ("mysql:host=$db_host;dbname=$db_name","$db_user","$db_pass",array(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION));    

        } catch (\Throwable $th) {
            echo $th;
        }
        /*$lati=[];
        $lngi=[];
        
        $d=['42.439718,8.860345', '42.439839,8.860329', '42.439961,8.860307', '42.440068,8.860401', '42.440192,8.860396', '42.440309,8.86035', '42.440419,8.860286', '42.440546,8.860189', '42.440663,8.860082', '42.44071,8.859953', '42.440754,8.859814', '42.440776,8.859652', '42.440762,8.859553', '42.440793,8.859424', '42.440859,8.859336', '42.440924,8.859258', '42.441027,8.859202'];
        //list($lat, $lng) = explode(",", $d[1]);
        //echo $lng;

        for ($i = 0; $i <=count($d)-1; $i++) {
            list($lat, $lng) = explode(",", $d[$i]);
            array_push($lati,$lat);
            array_push($lngi,$lng);
        }

        //var_dump($lati);

        
        for ($i = 0; $i <= count($d)-1; $i++) {
            //echo $lati[$i];
            $req=$bdd->prepare("INSERT into marker (id_parcours,lat,lng,date_heure) values(9,?,?,now()) ");
            $req->execute(array($lati[$i],$lngi[$i]));
        }*/
?>