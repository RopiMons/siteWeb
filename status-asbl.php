<?php
include("includes/db_connect.php");
include("includes/functions.php");
$titre_page=TitrePage($bdd,16);
$page = AffPage($bdd,16); // + Titre de la page
include("includes/head.php");
include("includes/menu.php");
?>
<div class="gris_clair">
     <div class="row-fluid corps">
         <div class="span12">
               <?php echo $page; ?>
          </div>
      </div>
  </div>
  
  <?php 
  include("includes/footer.php");?>
  <script src="js/jquery.js"></script>
  <?php include("includes/pied.php");
   ?>