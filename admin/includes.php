<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$includes = array(
    "db_connect.php",
    "class.user.php",
    "class.verif.php",
    "functions.php",
    "class.editeurRequette.php",
    "class.parametres.php",
    "class.requetteSelect.php",
    "class.requetteUpdate.php",
    "class.requetteInsert.php",
    "class.ressource.php",
    "class.saveDB.php",
);

foreach($includes as $include){
    include("../includes/".$include);
}