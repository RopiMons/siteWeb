<?php

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
					
					
					if(isset($_GET["cat"]))
					{
						echo '<h2>Archives pour ' . $_GET["cat"] . '</h2>';
						$statement = $bdd->prepare('SELECT * FROM news WHERE visible = "1" AND categorie=:cat');
						$statement->execute(array("cat" => $_GET["cat"]));
						$count = $statement->rowCount();
						if($count!=0)
						{
							$stmt = $bdd->prepare('SELECT * FROM news WHERE visible = "1" AND categorie=:cat ORDER BY id_news DESC LIMIT 0,10');	
							$stmt->execute(array("cat" => $_GET["cat"]));
							while($donnees=$stmt->fetch())
							{
								echo '<ul class="recent-blog">';
								$news=array(
									"titre"=>$donnees["titre"],"text"=>$donnees["text"],"auteur"=>$donnees["auteur"],"date_post"=>$donnees["date_post"],
									"premier_titre"=>$donnees["premier_titre"],	"nb_vues"=>$donnees["nb_vues"],"categorie"=>$donnees["categorie"],"id"=>$donnees["id_news"]
								);
								AffichageReduit($news);
								echo '</ul>';
							}
							$stmt->closeCursor();
						}
						else echo "<h4>Aucune news n'a été postée dans cette catégorie.</h4>";
					}
					elseif(isset($_GET["mois"]))
					{
						echo '<h2>Archives pour ' . $_GET["mois"] . '</h2>';
						$mois_precedent="";
                        $stmt = $bdd->prepare('SELECT * FROM news WHERE visible = "1" ORDER BY id_news DESC LIMIT 0,10');
                        $stmt->execute();
                        while($donnees=$stmt->fetch())
                        {
							$date_news=explode("-",$donnees["date_post"]);
							$mois=MoisComplet($date_news[1]);
							if($mois==$_GET["mois"])
							{
								echo '<ul class="recent-blog">';
                              	$news=array(
									"titre"=>$donnees["titre"],"text"=>$donnees["text"],"auteur"=>$donnees["auteur"],"date_post"=>$donnees["date_post"],
									"premier_titre"=>$donnees["premier_titre"],	"nb_vues"=>$donnees["nb_vues"],"categorie"=>$donnees["categorie"],"id"=>$donnees["id_news"]
								);
								AffichageReduit($news);
								echo '</ul>';
							}
							$mois_precedent=$mois;
                        }
                        $stmt->closeCursor();
					}

					?>					
					
          </div>
          <div class="span4">
	        		<ul>
		        		<li>
			        		<h4>CATEGORIES</h4>
							<ul>
								<?php
                                $stmt = $bdd->prepare('SELECT * FROM news_cat WHERE visible = "1"');
                                $stmt->execute();
                                while($donnees=$stmt->fetch())
                                {
                                	echo '<li class="cat-item"><a href="news.php?cat='.$donnees["titre"].'" title="Voir tous les posts">'.$donnees["titre"].'</a></li>';
                                }
                                $stmt->closeCursor();
                                ?>
							</ul>
		        		</li>
		        		<li>
			        		<h4>ARCHIVES</h4>
							<ul>
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
							</ul>
		        		</li>
	        		
	        		</ul>
          </div>
      </div>
  </div>
  
  <?php 
  include("includes/footer.php");?>
  <script src="js/jquery.js"></script>
  <?php include("includes/pied.php");
   ?>