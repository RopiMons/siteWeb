<?php
if(!isset($_GET["id"]))
	header("location:index.php");
include("includes/db_connect.php");
include("includes/functions.php");
$titre_page=TitrePage($bdd,"0","",$_GET["id"]);

include("includes/head.php");
include("includes/menu.php");

?>
<div class="gris_clair">
     <div class="row-fluid corps">
         <div class="span8 well">
               <?php
					$statement = $bdd->prepare('SELECT * FROM news WHERE visible = "1" AND id_news=:id');
						$statement->execute(array("id" => $_GET["id"]));
						$count = $statement->rowCount();
						if($count!=0)
						{
							$stmt = $bdd->prepare('SELECT * FROM news WHERE visible = "1" AND id_news=:id');	
							$stmt->execute(array("id" => $_GET["id"]));
							$donnees=$stmt->fetch();
							$news=array(
								"titre"=>$donnees["titre"],"text"=>$donnees["text"],"auteur"=>$donnees["auteur"],"date_post"=>$donnees["date_post"],
								"premier_titre"=>$donnees["premier_titre"],	"nb_vues"=>$donnees["nb_vues"],"categorie"=>$donnees["categorie"],"id"=>$donnees["id_news"]
							);
							$stmt->closeCursor();
						}
						else echo "<h4>Aucune news ne correspond.</h4>";
						
						
						if($count!=0)
						{
							$date_news=explode("-",$news["date_post"]);
							$mois=MoisReduit($date_news[1]);
							
				        }
				        echo '<a href="single.php?id='.$news["id"].'"><h2>'.$news["titre"].'</h2></a>';
						echo '<hr/>';
                        echo $news["text"];
						echo '<hr/>';
						echo '<i class="icon-time"></i> '.$mois. " " . $date_news[0] . ' ';
						if($news["categorie"]!="")
                        echo '<i class="icon-tag"></i> Dans <a href="news.php?cat='.$news["categorie"].'">'.$news["categorie"].'</a> ';
						echo '<i class="icon-user"></i> Par '.getNom($bdd,$news["auteur"]).'</a>'?>
          </div>
          <div class="span4">
              <h3>Catégorie de news</h3>
              <?php
              $stmt = $bdd->prepare('SELECT * FROM news_cat WHERE visible = "1"');
              $stmt->execute();
              while($donnees=$stmt->fetch())
              {
                  echo '<li><a href="news.php?cat='.$donnees["titre"].'" title="Voir tous les posts">'.$donnees["titre"].'</a></li>';
              }
              $stmt->closeCursor();
			  ?>
			  <h3>Archives</h3>
			  <?php
			  $mois_precedent="";
              $stmt = $bdd->prepare('SELECT * FROM news WHERE visible = "1" ORDER BY id_news');
              $stmt->execute();
              while($donnees=$stmt->fetch())
              {
			      $date_news=explode("-",$donnees["date_post"]);
				  $mois=MoisComplet($date_news[1]);
				  if($mois!=$mois_precedent)
				  {
                      echo '<li class="cat-item"><a href="news.php?mois='.$mois.'" title="Voir tous les posts">'.$mois.'</a></li>';
				  }
				  $mois_precedent=$mois;
              }
              $stmt->closeCursor();
              ?>
          </div>
      </div>
  </div>
  
  <?php 
  include("includes/footer.php");?>
  <script src="js/jquery.js"></script>
  <?php include("includes/pied.php");
   ?>