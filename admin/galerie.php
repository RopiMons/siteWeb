<?php
session_start();
include("includes.php");
include("../includes/class.newsmanager.php");
VerifConnection($bdd,$_SESSION,9);
	
$breadcrumbs='<a href="index.php">Index de l\'administration</a> <div class="breadcrumb_divider"></div> 
<a class="current">Gestion de la galerie</a>';
	
include("includes/header.php");

?>
<?php
$menu = '<ul>
                    	<li></li>
                    </ul>';

//Si on affiche toute les news
?>
	<section id="main" class="column">		
		<article class="module width_full">
			<header><h3>Gestion des images</h3></header>
			<div class="module_content">
            	Dans cette page vous pouvez gérer les images de votre site. En ajouter, supprimer, ...
			</div>
		</article><!-- end of stats article -->
		
		
		<div class="clear"></div>
		
		
		<article class="module width_3_quarter">
		<header><h3 class="tabs_involved">Liste des images</h3>
		</header>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th>Titre</th> 
    				<th>URL</th> 
    				<th>Actions</th> 
				</tr> 
			</thead> 
			<tbody> 
            	<?php
				$stmt = $bdd->prepare('SELECT * FROM galerie ORDER BY id_image DESC');
				$stmt->execute();
				while($donnees=$stmt->fetch())
				{
					echo "<tr>
						<td>".$donnees["titre_image"]."</td>
						<td>".$donnees["url_image"]."</td>
						<td><a href='news.php?edit=".$donnees["id_news"]."' title='news'><input type='image' src='images/icn_edit.png' title='Edit'></a>
						<a href='news.php?del=".$donnees["id_news"]."' title='news'><input type='image' src='images/icn_trash.png' title='Trash'></a></td>
					
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
		
        
 
<?php
if(isset($_FILES['avatar']))
{
	$dossier = 'images/';

	$fichier = basename($_FILES['avatar']['name']);
	$taille_maxi = 100000;
	$taille = filesize($_FILES['avatar']['tmp_name']);
	$extensions = array('.png', '.gif', '.jpg', '.jpeg');
	$extension = strrchr($_FILES['avatar']['name'], '.'); 
	//Début des vérifications de sécurité...
	if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
	{
	     $erreur = 'Vous devez uploader un fichier de type png, gif, jpg, jpeg, txt ou doc...';
	}
	if($taille>$taille_maxi)
	{
	     $erreur = 'Le fichier est trop gros...';
	}
	if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
	{
    	 //On formate le nom du fichier ici...
    	 $fichier = strtr($fichier, 
    	      'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
    	      'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
    	 $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
		  echo "FICHIER = " . $fichier;
    	 if(move_uploaded_file($_FILES['avatar']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
    	 {
    	      echo 'Upload effectué avec succès !';
    	 }
    	 else //Sinon (la fonction renvoie FALSE).
    	 {
       	   echo 'Echec de l\'upload !';
     	}
	}
	else
	{
   	  echo $erreur;
	}
}
?>


     
 
		<article class="module width_full">
			<header><h3>Poster une nouvelle image</h3></header>
				<div class="module_content">
                <form method="POST" action="galerie.php" enctype="multipart/form-data">
               <!-- On limite le fichier à 100Ko -->
               <input type="hidden" name="MAX_FILE_SIZE" value="100000">
               Fichier : <input type="file" name="avatar">


		

                   
                   <div class="clear"></div>
				</div>
			<footer>
				<div class="submit_link">
					<input type="submit" name="envoyer" value="Envoyer le fichier">
</form>
				</div>
			</footer>
		</article><!-- end of post new article -->


		<div class="spacer"></div>
	</section>

<?php include("includes/footer.php");?>