<?php
session_start();
include("includes.php");
include("../includes/class.newsmanager.php");
VerifConnection($bdd,$_SESSION,3);


$breadcrumbs='<a href="index.php">Index de l\'administration</a> <div class="breadcrumb_divider"></div> 
<a class="current">statistique des demandes</a>';

function Status($status)
{
	switch ($status)
	{
		case 1: return "Actif";
		    break;
	}
}


include("includes/header.php");

?>
	<section id="main" class="column">		
		<article class="module width_full">
			<header><h3>Demande de recherche de commerce ou de produits</h3></header>
			<div class="module_content">
            	Dans cette page vous trouvez les statistiques sur les demandes  de recherche d'un type de commerces adhérants au ropi ou de produits.               
			</div>
		</article><!-- end of stats article -->
		
		
		<div class="clear"></div>
		
		
		<article class="module width_full">
		<header><h3 class="tabs_involved">Les statistiques de demandes de commerce et de produits</h3>
		</header>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
			
			<thead> 
				
			</thead> 
			<tbody> 
            <form name="maforme" action="statistique-demandes.php" enctype="multipart/form-data" method="post">
            		<?php 
						$today = date("Y-m-d");  
						
						$date = new \DateTime($today);
        				//$today2->add( new \DateInterval('P1Y'));
        				$today2=date("Y-m-d", strtotime("-1 year"));
						
					?>
                    de  : 
					<input type="text" name="datedebut" id="datedebut" value="<?php echo $today2 ;?>" /> 
            
            		à : 
					<input type="text" name="datefin" id="datefin"  value="<?php echo $today ;?>" />
                    <br><br>
                    
                    <input type="submit" value="envoyer" name="envoyer" id="envoyer">
           			<br><br>
           
                  
            	
				<?php
				
				
					if(isset($_POST["datedebut"]) &&  isset($_POST["datefin"])  ) {
					
						$stmt = $bdd->prepare("select * , COUNT(*) as qt from proposition inner join personnes on personne_id = idpersonnes 
											   where dates between'" .$_POST["datedebut"]. "' and '". $_POST["datefin"]."' and produit <> ''
											   group by  produit 
											   order by  produit");
						$stmt->execute();
						$rowCount = $stmt->rowCount();
						
						if($rowCount > 0){
							echo '<table class="tablesorter" cellspacing="0"> 
									<thead> 
										<tr> 
											<th>nom</th> 
											<th>Prénom</th>
											<th>produit</th>									
											<th>qt</th>                
										</tr> 
									</thead> '; 
							
							
							
							while($donnees=$stmt->fetch())
							{
								echo "<tr>
										<td>".$donnees["nompersonnes"]."</td>
										<td>".$donnees["prenompersonnes"]."</td>
										<td>".$donnees["produit"]."</td>
										<td>".$donnees["qt"]."</td>												
									  <tr>";
							}
							
							}$stmt->closeCursor();
							echo '</table> <br><br><br>';
					};
					
					echo'<br><br><br><br>';
					
					if(isset($_POST["datedebut"]) &&  isset($_POST["datefin"])  ) {
					
						$stmt2 = $bdd->prepare("select * , COUNT(*) as qt from proposition inner join personnes on personne_id = idpersonnes 
											   where dates between'" .$_POST["datedebut"]. "' and '". $_POST["datefin"]."' and commerce <> '' 
											   group by commerce
											   order by commerce");
						$stmt2->execute();
						
						$rowCount = $stmt2->rowCount();
						
						if($rowCount > 0){														
							echo '<table class="tablesorter" cellspacing="0"> 
									<thead> 
										<tr> 
											<th>Nom</th>
											<th>Prénom</th>
											<th>Commerce</th> 
											<th>qt</th>                
										</tr> 
									</thead> '; 
							
							
							
							while($donnees=$stmt2->fetch())
							{
								echo "<tr>
										<td>".$donnees["nompersonnes"]."</td>
										<td>".$donnees["prenompersonnes"]."</td>
										<td>".$donnees["commerce"]."</td>
										<td>".$donnees["qt"]."</td>												
									  <tr>";
							}
							
						}$stmt2->closeCursor();
						echo '</table>';
					}
					
					
				?>
                
                
                
                
                
                
                </form>
			</tbody> 
			
          
            
			</div><!-- end of #tab1 -->			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->
        		
			<footer>
			</footer>
		</article><!-- end of messages article -->

   		<div class="clear"></div>
		
			
                
                
		
		<div class="spacer"></div>
	</section>

<?php include("includes/footer.php");?>