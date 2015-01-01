<?php

$includes = array(
    "db_connect.php",
    "class.newsmanager.php",
    "class.user.php",
    "class.verif.php",
    "functions.php",
    "class.editeurRequette.php",
    "class.parametres.php",
    "class.requetteSelect.php",
    "class.ressource.php",
    "class.saveDB.php",
);

foreach($includes as $include){
    include("includes/".$include);
}
