<?php
session_start();
include("includes.php");

include("../includes/class.newsmanager.php");
VerifConnection($bdd,$_SESSION,2);

$breadcrumbs='<a href="index.php">Index de l\'administration</a> <div class="breadcrumb_divider"></div> 
	<a class="current">Gestion des contenus</a> <div class="breadcrumb_divider"></div> 
	<a href="menus.php">Gestion des menus</a> 
	';

include("includes/header.php");
$affmessage="";
if(isset($_POST["envoi"]))
{
	$verif_titre=Verif($_POST["titre"],"Titre",3,64);
	if($verif_titre=="1")
	{
		include("../includes/class.form.php");
		$pur=valideChaine($_POST["titre"]);
		$message= '<h4 class="alert_success">Réussite - Le menu a été créé</h4>';
		$req = $bdd->prepare('INSERT INTO menus(titre)
		 VALUES(:titre)');
		$req->execute(array(
			'titre'=> $_POST["titre"]
		));
	}
	else
	{
		$message="";
		$message.=($verif_titre!="1")?$verif_titre:"";
		$affmessage= '<h4 class="alert_error"><b>Erreur</b> - '.$message . '</h4> <br />' ;
	}
}
if(isset($_POST["add_link"]))
{
		$erreur=false;
	$verif_titre=Verif($_POST["titre"],"Titre",3,64);
	$verif_lien=Verif($_POST["externe"],"Lien externe",0,128,"","(http|ftp|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&amp;:/~\+#]*[\w\-\@?^=%&amp;/~\+#])?","","optionnel");
	$verif_position=Verif($_POST["position"],"Position",1,10,"int");
	//Si le lien interne est sur null et que la vérification du externe est correcte + != de "" alors c'est un lien externe
	if($_POST["interne"]=="null" && $verif_lien =="1" && $_POST["externe"]!="")
	{
		$url==$_POST["externe"];
	}
	//Sinon si le lien interne est != de null, c'est un lien interne
	elseif($_POST["interne"]!="null")
	{
	  //Si le lien externe est vide
		if($_POST["externe"]=="")
		{
			$url=$_POST["interne"];
		}
		elseif($_POST["externe"]!="")
		{
			$verif_lien="Attention, vous avez choisi une page ET un lien externe.";
			$erreur=true;
		}
	}
	if($verif_titre=="1" && $verif_lien=="1" && $verif_position=="1" && $erreur==false)
	{
		include("../includes/class.form.php");
		$pur=valideChaine($_POST["titre"]);
		$affmessage='<h4 class="alert_success">Réussite - Le lien a été ajouté</h4>';
		$req = $bdd->prepare('INSERT INTO menus_liens(position,titre,url,menu_id)
			VALUES(:position,:titre,:url,:menu_id)');
		$req->execute(array(
		'position'=>$_POST["position"],
		'titre'=> $_POST["titre"],
		'url'=> $url,
		'menu_id' => $_GET["edit"]
		));
	}
	else
	{
		$message="";
		$message.=($verif_titre!="1")?$verif_titre:"";
		$message.=($verif_lien!="1")?$verif_lien:"";
		$message.=($verif_position!="1")?$verif_position:"";
		$affmessage= '<h4 class="alert_error"><b>Erreur</b> - '.$message . '</h4> <br />' ;
	}	
}
if(isset($_GET["del"]))
{
	$req = $bdd->prepare('DELETE FROM menus WHERE id_menu = :id');
		$req->execute(array(
		'id'=> $_GET["del"]
		));
}

if(isset($_GET["edit"]))
{ ?>
	<section id="main" class="column">		
		<article class="module width_full">
			<header><h3>Gestion d'un menu</h3></header>
			<div class="module_content">
            	Dans cette page vous pouvez ajouter des liens, modifier l'endroit où se trouvera le menu, ...
			</div>
		</article><!-- end of stats article -->
		
		
		<div class="clear"></div>
		
		
		<article class="module width_half">
		<header><h3 class="tabs_involved">Ajouter un lien</h3>
		</header>
        
        <div class="module_content">
        	<?php
			if($affmessage!="") echo $affmessage;
			?>
			
            <p>Pour ajouter un lien dans un menu, vous devez lui donner un titre :</p>
			<form name="lien" id="lien" action="menus.php?edit=<?=$_GET["edit"]?>" method="post">
			<fieldset>
				<label for="titre">Titre du lien</label>
				<input type="text" name="titre" id="titre" placeholder="Titre du lien. Ex: Accueil" />
			</fieldset>
            <p>Ensuite, vous devez dire vers quelle page il va cibler:</p>
            <fieldset>
            	<label for="interne">SOIT une page du site</label>
                <select title="interne" name="interne" id="interne" style="width:92%;">
					<option value="null">Aucun</option>
                    <?php
					$stmt = $bdd->prepare('SELECT * FROM pages ORDER BY id_page DESC');
				$stmt->execute();
				while($donnees=$stmt->fetch())
				{
					echo '<option value="'.$donnees["id_page"].'">'.$donnees["titre"].'</option>';
				}
				$stmt->closeCursor();
					?>
				</select>
                <p>&nbsp;</p>
                
				<label for="externe">SOIT une page extérieure</label>
				<input type="text" name="externe" id="externe" placeholder="Page extérieure. Ex: http://google.com - (Indiquez bien le http://)" />
			</fieldset>
            <p>Et pour finir, la position que le lien aura dans ce menu</p>
            <fieldset>
				<label for="position">Position du lien</label>
				<input type="text" name="position" id="position" placeholder="Position du lien. Ex: 5 (5ème)" />
			</fieldset>
        	<div class="clear"></div>
		</div>
			<footer>
				<div class="submit_link">
					<input type="submit" name="add_link" id="add_link" value="Ajouter" class="alt_btn">
					<input type="submit" value="Reset">
                    </form>
				</div>
			</footer>

			</div>
		
		</article><!-- end of content manager article -->
        
        
        <article class="module width_half">
            <header><h3 class="tabs_involved">Content Manager</h3>
            <ul class="tabs">
                <li><a href="#tab1">Posts</a></li>
            </ul>
            </header>
    
            <div class="tab_container">
                <div id="tab1" class="tab_content">
                <table class="tablesorter" cellspacing="0"> 
                <thead> 
                    <tr> 
                        <th></th> 
                        <th>Titre</th> 
                        <th>Lien</th> 
                        <th>Position</th> 
                        <th>Actions</th> 
                    </tr> 
                </thead> 
                <tbody> 
                    <?php
                    $stmt = $bdd->prepare('SELECT * FROM menus_liens WHERE menu_id='.$_GET["edit"].' ORDER BY id_lien DESC');
                    $stmt->execute();
                    while($donnees=$stmt->fetch())
                    {
                        echo "<tr>
                            <td><input type='checkbox' name='news' id='".$donnees["id_lien"]."'></td>
                            <td>".$donnees["titre"]."</td>
                            <td>".$donnees["url"]."</td>
                            <td>".$donnees["position"]."</td>
                            <td><input type='image' src='images/icn_edit.png' title='Edit'><input type='image' src='images/icn_trash.png' title='Trash'></td>
                        
                        <tr>";
                    }
                    $stmt->closeCursor();
                    ?>
                </tbody> 
                </table>
                </div><!-- end of #tab1 -->
                
            </div><!-- end of .tab_container -->
		</article><!-- end of content manager article -->
        
        <div class="clear"></div>

		
		<div class="spacer"></div>
        	<article class="module width_full">
			<header><h3>Créer un nouveau menu</h3></header>
				<div class="module_content">
                <?php
				if($affmessage!="") echo $affmessage;
				?>                
		
		
						<form name="news" id="news" action="<?=$_SERVER['PHP_SELF']?>" method="post">
						<fieldset>
							<label for="titre">Titre</label>
							<input type="text" name="titre" id="titre" placeholder="Titre du menu" />
						</fieldset>
                   <div class="clear"></div>
				</div>
			<footer>
				<div class="submit_link">
					<input type="submit" name="envoi" id="envoi" value="Publier" class="alt_btn">
					<input type="submit" value="Reset">
                    </form>
				</div>
			</footer>
		</article><!-- end of post new article -->
	</section>
<?php
}
else
{
?>
	<section id="main" class="column">		
		<article class="module width_full">
			<header><h3>Gestion des menus</h3></header>
			<div class="module_content">
            	Dans cette page vous pouvez gérer les menus de votre site. En ajouter, supprimer, modifier, ...
			</div>
		</article><!-- end of stats article -->
		
		
		<div class="clear"></div>
		
		
		<article class="module width_half">
		<header><h3 class="tabs_involved">Liste des news</h3>
		</header>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th></th> 
    				<th>Titre</th> 
    				<th>Actions</th> 
				</tr> 
			</thead> 
			<tbody> 
            	<?php
				$stmt = $bdd->prepare('SELECT * FROM menus');
				$stmt->execute();
				while($donnees=$stmt->fetch())
				{
					echo "<tr>
						<td><input type='checkbox' name='news' id='".$donnees["id_menu"]."'></td>
						<td>".$donnees["titre"]."</td>
						<td><a href='menus.php?edit=".$donnees["id_menu"]."' title='menu'>Gérer le menu</a>
						<a href='menus.php?del=".$donnees["id_menu"]."' title='menu'><input type='image' src='images/icn_trash.png' title='Trash'></a></td>
					
					<tr>";
				}
				$stmt->closeCursor();
				?>
			</tbody> 
			</table>
			</div><!-- end of #tab1 -->
			
			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->
        
   		<div class="clear"></div>
		
		<div class="spacer"></div>
        	<article class="module width_full">
			<header><h3>Créer un nouveau menu</h3></header>
				<div class="module_content">
                <?php
				if($affmessage!="") echo $affmessage;
				?>                
		
		
						<form name="news" id="news" action="<?=$_SERVER['PHP_SELF']?>" method="post">
						<fieldset>
							<label for="titre">Titre</label>
							<input type="text" name="titre" id="titre" placeholder="Titre du menu" />
						</fieldset>
                   <div class="clear"></div>
				</div>
			<footer>
				<div class="submit_link">
					<input type="submit" name="envoi" id="envoi" value="Publier" class="alt_btn">
					<input type="submit" value="Reset">
                    </form>
				</div>
			</footer>
		</article><!-- end of post new article -->
	</section>

<?php 
}
include("includes/footer.php");?>