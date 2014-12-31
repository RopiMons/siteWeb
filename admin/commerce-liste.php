<?php
session_start();
include("includes.php");
include("../includes/class.newsmanager.php");
VerifConnection($bdd,$_SESSION,9);

$breadcrumbs='<a href="index.php">Index de l\'administration</a> <div class="breadcrumb_divider"></div> 
<a href="commerce.php">Mon commerce</a> <div class="breadcrumb_divider"></div> 
<a class="current">Ajouter un commerce</a>';

$message="";

include("includes/header.php");	
?>
<section id="main" class="column">	
    <article class="module width_full">
		<header><h3 class="tabs_involved">Liste des commerces validés</h3>
		</header>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th>Nom</th> 
    				<th>Adresse</th> 
                    <th>CP</th> 
    				<th>Actions</th> 
				</tr> 
			</thead> 
			<tbody> 
            	<?php
				$stmt = $bdd->prepare('SELECT * FROM commerce WHERE commercestatus<>0');
				$stmt->execute();
				while($donnees=$stmt->fetch())
				{
					echo "<tr>
						<td>".$donnees["commercenom"]."</td>";
					$stmt2 = $bdd->prepare('SELECT * FROM adresses WHERE adressescommerceid=:id');
				    $stmt2->execute(array("id"=>$donnees["idcommerce"]));
				    $donnees2=$stmt2->fetch();
					echo"<td>".$donnees2["adressesnumero"]. ", ".$donnees2["adressesrue"]."</td>
						<td>".$donnees2["adressescodepostal"]."</td>
						
						<td>
						<a href='commerce-ajouter.php?id=".$donnees["idcommerce"]."' title='Chercher'><input type='image' src='images/icn_photo.png' title='Trash'></a>
						<a href='commerce-ajouter.php?valid=".$donnees["idcommerce"]."' title='Valider'><input type='image' src='images/icn_alert_success.png' title='Edit'></a>
						<a href='commerce-ajouter.php?refus=".$donnees["idcommerce"]."' title='Refuser'><input type='image' src='images/icn_alert_error.png' title='Trash'></a>
						
						</td>
					
					<tr>";
					$stmt2->closeCursor();
					
					
					
				}
				$stmt->closeCursor();
				?>
			</tbody> 
			</table>
			</div><!-- end of #tab1 -->			
		</div><!-- end of .tab_container -->
		
	</article><!-- end of content manager article -->
</section>


<?php include("includes/footer.php");?>