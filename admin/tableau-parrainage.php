<?php
session_start();
include("../includes/db_connect.php");
include("../includes/class.user.php");
include("../includes/functions.php");
include("../includes/class.verif.php");
include("../includes/class.newsmanager.php");
VerifConnection($bdd,$_SESSION["id"],$_SESSION["password"],$_SESSION["niveau"],3);


$breadcrumbs='<a href="index.php">Index de l\'administration</a> <div class="breadcrumb_divider"></div> 
<a class="current">tableau des parraianges</a>';

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
			<header><h3>Tableau des parrainages</h3></header>
			<div class="module_content">
            	Dans cette page vous trouvez le nom des personnes/commerces ayant étés parrainés.               
			</div>
		</article><!-- end of stats article -->
		
		
		<div class="clear"></div>
		
		
		<article class="module width_full">
		<header><h3 class="tabs_involved">Tableau des parrainages</h3>
		</header>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
			
			<thead> 
				
			</thead> 
			<tbody> 
            <form name="maforme" action="tableau-parrainage.php" enctype="multipart/form-data">
            		
                                    
            	
				<?php
				
				
				
					
						$stmt = $bdd->prepare("select *   from filleul order by dates desc");
											   
						$stmt->execute();
						$rowCount = $stmt->rowCount();
						
						if($rowCount > 0){
							echo '<table class="tablesorter" cellspacing="0"> 
									<thead> 
										<tr> 
											<th>Le commerce</th> 
											<th>Rue</th>
											<th>Numéro</th>									
											<th>Cp</th>      
											<th>Localité</th>  
											<th>Date</th>        
										</tr> 
									</thead> '; 
							
							
							
							while($donnees=$stmt->fetch())
							{
								echo "<tr>
										<td>".$donnees["nom"]."</td>
										<td>".$donnees["rue"]."</td>
										<td>".$donnees["numero"]."</td>
										<td>".$donnees["cp"]."</td>	
										<td>".$donnees["localite"]."</td>	
										<td>".$donnees["dates"]."</td>											
									  <tr>";
							}
							
							}$stmt->closeCursor();
							echo '</table> <br><br><br>';
					
					
					
					
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