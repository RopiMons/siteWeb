<?php
session_start();
include("../includes/db_connect.php");
include("../includes/class.user.php");
include("../includes/class.verif.php");
include("../includes/functions.php");
VerifConnection($bdd,$_SESSION["id"],$_SESSION["password"],$_SESSION["niveau"],9);

$breadcrumbs='<a href="index.php">Index de l\'administration</a> <div class="breadcrumb_divider"></div> 
	<a class="current">Panneau de contrôle</a>';
include("includes/header.php");
$menu=""; $envoye=false;
$statut=TypeVisible();
?>
	
	
	<section id="main" class="column">		
		<article class="module width_full">
			<header><h3>Gestion des types de commerce</h3></header>
			<div class="module_content">
            	Dans cette page vous pouvez gérer les types de commerce.
			</div>
		</article><!-- end of stats article -->
		
		
		<div class="clear"></div>
		<article class="module width_full">
			<header><h3>Poster un nouveau type</h3></header>
				<div class="module_content">
                <?php
		if(isset($_POST["envoi"]))
		{
			$verif_titre=Verif($_POST["titre"],"Titre",3,64);
			if($verif_titre=="1")
			{
				include("../includes/class.form.php");
				$pur=valideChaine($_POST["titre"]);
				echo '<h4 class="alert_success">Réussite - Le type a été ajouté</h4>';
				$req = $bdd->prepare('INSERT INTO cataloguetypecommerce(cataloguetypecommercelabel)
				 VALUES(:titre)');
				$req->execute(array(
					'titre' => $_POST["titre"],
					));
				$envoye=true;
			}
			else
			{
				$message="";
				$message.=($verif_titre!="1")?$verif_titre:"";
				echo '<h4 class="alert_error"><b>Erreur</b> - '.$message . '</h4> <br />' ;
			}
		}
		//On vérifie l'existance des champs
		$titre=(isset($_POST["titre"])!="") ? $_POST["titre"] : "";
		
		if(isset($_GET["del"]))
		{
			$bdd->exec("DELETE FROM cataloguetypecommerce WHERE idcataloguetypecommerce = ".$_GET["del"]." ");
		      $message = '<h4 class="alert_error"><b>Erreur</b> - Le type de commerce a bien été supprimé.</h4> <br />' ;

		}

		?>
                
		
                	<form name="event" id="event" action="<?=$_SERVER['PHP_SELF']?>" method="post">
						<fieldset>
							<label for="titre">Titre</label>
							<input type="text" name="titre" id="titre" value="<?=$titre?>" />
						</fieldset>
                   <div class="clear"></div>
				</div>
			<footer>
				<div class="submit_link">
					<input type="submit" name="envoi" id="envoi" value="Ajouter" class="alt_btn">
					
					<input type="submit" value="Reset">
                    </form>
				</div>
			</footer>
		</article><!-- end of post new article -->
		
		<article class="module width_3_quarter">
		<header><h3 class="tabs_involved">Liste des news</h3>
		</header>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th>Titre</th> 
    				<th>Actions</th>  
				</tr> 
			</thead> 
			<tbody> 
            	<?php
				$stmt = $bdd->prepare('SELECT * FROM cataloguetypecommerce ORDER BY idcataloguetypecommerce DESC');
				$stmt->execute();
				while($donnees=$stmt->fetch())
				{
					echo "<tr>
						<td>".$donnees["cataloguetypecommercelabel"]."</td>
						<td><a href='calendrier.php?del=".$donnees["idcataloguetypecommerce"]."' title='type'><input type='image' src='images/icn_trash.png' title='Trash'></a></td>
					
					<tr>";
				}
				$stmt->closeCursor();
				?>
			</tbody> 
			</table>
			</div><!-- end of #tab1 -->
			
			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->
        		<article class="module width_quarter">
			<header><h3>Options</h3></header>
				<div class="module_content">
                	<?php echo $menu;?>
                    
				</div>
		</article><!-- end of messages article -->
        
        		<div class="clear"></div>
		
		


		<div class="spacer"></div>
	</section>

<?php include("includes/footer.php");?>