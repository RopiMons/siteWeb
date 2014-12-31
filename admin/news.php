<?php
session_start();
include("includes.php");
include("../includes/class.newsmanager.php");
VerifConnection($bdd,$_SESSION,9);
if(!isset($_GET["all"]))
{
	$breadcrumbs='<a href="index.php">Index de l\'administration</a> <div class="breadcrumb_divider"></div> 
	<a class="current">Gestion des news</a>';
}
else
{$breadcrumbs='<a href="index.php">Index de l\'administration</a> <div class="breadcrumb_divider"></div> 
				<a href="news.php">Gestion des news</a> <div class="breadcrumb_divider"></div>
				<a class="current">Toutes les news</a>';
	
}
include("includes/header.php");

$jour=array(); $vues=array(); $i=0; $auj=false; $hier=false;
$stmt = $bdd->prepare('SELECT * FROM statistiques ORDER BY id DESC LIMIT 0,7');
$stmt->execute();
while($donnees=$stmt->fetch())
{
	if($i==0) $auj=$donnees["vues"];
	elseif($i==1) $hier=$donnees["vues"];
	$jour[$i]=$donnees["id_date"];
	$vues[$i]=intval($donnees["vues"]);
	$i++;
}
$calcul=0; $i=0;
$stmt = $bdd->prepare('SELECT * FROM statistiques');
$stmt->execute();
while($donnees=$stmt->fetch())
{
	$calcul=$donnees["vues"]+$calcul;
	$i++;
}
$moyenne=$calcul/$i;
?>
<script type="text/javascript" src="includes/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		language : "fr",
		mode : "textareas",
		theme : "advanced",
		plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>
<!-- /TinyMCE -->
<?php
$menu = '<ul>
                    	<li><a href="news.php?all" title="all">Afficher la liste complète des news</a></li>
                        <li><a href="news.php?cat" title="all">Gérer les catégories</a></li>
                    </ul>';

//Si on affiche toute les news
if(isset($_GET["all"]))
{
?>
    	<section id="main" class="column">		
		<article class="module width_full">
			<header><h3>Gestion des news</h3></header>
			<div class="module_content">
            	Voici l'ensemble des news de votre site, classée de la plus récente à la plus ancienne.
			</div>
		</article><!-- end of stats article -->
		
		
		<div class="clear"></div>
		
		
		<article class="module width_3_quarter">
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
    				<th>Category</th> 
    				<th>Postée le</th> 
    				<th>Actions</th> 
				</tr> 
			</thead> 
			<tbody> 
            	<?php
				$stmt = $bdd->prepare('SELECT * FROM news ORDER BY id_news DESC');
				$stmt->execute();
				while($donnees=$stmt->fetch())
				{
					echo "<tr>
						<td><input type='checkbox' name='news' id='".$donnees["id_news"]."'></td>
						<td>".$donnees["titre"]."</td>
						<td>".$donnees["titre"]."</td>
						<td>".$donnees["date_post"]."</td>
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
        		<article class="module width_quarter">
			<header><h3>Messages</h3></header>
				<div class="module_content">
					<?php echo $menu?>
				</div>
		</article><!-- end of messages article -->
        
        		<div class="clear"></div>
		

<?php
}
elseif(isset($_GET["cat"]))
{ 
?>
	    	<section id="main" class="column">		
		<article class="module width_full">
			<header><h3>Gestion des news</h3></header>
			<div class="module_content">
            	Voici l'ensemble des catégories de votre site.
			</div>
		</article><!-- end of stats article -->
		
		
		<div class="clear"></div>
		
		
		<article class="module width_3_quarter">
		<header><h3 class="tabs_involved">Liste des catégories</h3>
		<ul class="tabs">
   			<li><a href="#tab1">Liste</a></li>
		</ul>
		</header>

		<div class="tab_container">
        <?php
			if(isset($_POST["cat"]))
			{
				$verif_titre=Verif($_POST["titre"],"Titre",3,64);
				if($verif_titre=="1")
				{
					echo '<h4 class="alert_success">Réussite - La catégorie a été ajoutée</h4>';
					$req = $bdd->prepare('INSERT INTO news_cat(titre)
					 VALUES(:titre)');
					$req->execute(array(
						'titre'=> $_POST["titre"]
						));
				}
				else
				{
					$message=($verif_titre!="1")?$verif_titre:"";
					echo '<h4 class="alert_error"><b>Erreur</b> - '.$message . '</h4> <br />' ;
				}
			}
			//On vérifie l'existance des champs
			$titre=(isset($_POST["titre"])!="") ? $_POST["titre"] : "";
			if(isset($_GET["edit_cat"]))
			{
				if(isset($_POST["updatecat"]))
				{
					$verif_titre=Verif($_POST["titre"],"Titre",3,64);
					if($verif_titre=="1")
					{
						echo '<h4 class="alert_success">Réussite - La catégorie a été modifiée</h4>';
						$req = $bdd->prepare('UPDATE news_cat SET titre = :titre WHERE id_cat = :id');
						$req->execute(array(
							'titre'=> $_POST["titre"],
							'id' => $_POST["id"]
							));
					}
					else
					{
						$message=($verif_titre!="1")?$verif_titre:"";
						echo '<h4 class="alert_error"><b>Erreur</b> - '.$message . '</h4> <br />' ;
					}
				}
				$stmt = $bdd->prepare('SELECT * FROM news_cat WHERE id_cat = "'.$_GET["edit_cat"].'"');
				$stmt->execute();
				$donnees=$stmt->fetch();
					$titre=$donnees["titre"];
				$stmt->closeCursor();
			}
			if(isset($_GET["del_cat"]))
			{
				$stmt = $bdd->prepare('UPDATE news_cat SET visible = "0" WHERE id_cat = :id');
				$stmt->execute(array(
					'id' => $_GET["del_cat"]
				));
				$stmt->execute();	
				$stmt->closeCursor();
			}
			?>

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
				$stmt = $bdd->prepare('SELECT * FROM news_cat WHERE visible="1" ORDER BY id_cat DESC ');
				$stmt->execute();
				while($donnees=$stmt->fetch())
				{
					echo "<tr>
						<td>".$donnees["titre"]."</td>
						<td><a href='news.php?cat&amp;edit_cat=".$donnees["id_cat"]."'><input type='image' src='images/icn_edit.png' title='Edit'></a>
						<a href='news.php?cat&amp;del_cat=".$donnees["id_cat"]."'><input type='image' src='images/icn_trash.png' title='Trash'></a></td>
					
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
			<header><h3>Messages</h3></header>
				<div class="module_content">
					<?php echo $menu?>
				</div>
		</article><!-- end of messages article -->
        
        <div class="clear"></div>
		
		<article class="module width_full">
			<header><h3>Poster une nouvelle catégorie</h3></header>
				<div class="module_content">
					<?php
					if(isset($_GET["edit_cat"]))
					{
						echo '<form name="cat" id="cat" action="news.php?cat&amp;edit_cat='.$_GET["edit_cat"].'" method="post">';
					}
					else
					{
						echo '<form name="cat" id="cat" action="news.php?cat" method="post">';
					}
					?>
		
                	<form name="cat" id="cat" action="news.php?cat" method="post">
						<fieldset>
							<label for="titre">Titre</label>
							<input type="text" name="titre" id="titre" value="<?=$titre?>" />
						</fieldset>
                   
                   <div class="clear"></div>
				</div>
			<footer>
				<div class="submit_link">
					<?php
					if(isset($_GET["edit_cat"]))
					{
						echo '<input type="hidden" name="id" id="id" value="'.$_GET["edit_cat"].'" />';
						echo '<input type="submit" name="updatecat" id="updatecat" value="Modifier" class="alt_btn">';
					}
					else
					{
						echo '<input type="submit" name="cat" id="cat" value="Publier" class="alt_btn">';
					}
					?>
					<input type="submit" value="Reset">
                    </form>
				</div>
			</footer>
		</article><!-- end of post new article -->

<?php
}
//Si on affiche la page de base
else
{
?>
	<section id="main" class="column">		
		<article class="module width_full">
			<header><h3>Gestion des news</h3></header>
			<div class="module_content">
            	Dans cette page vous pouvez gérer les news de votre site. En ajouter, supprimer, modifier, ...
			</div>
		</article><!-- end of stats article -->
		
		
		<div class="clear"></div>
		
		
		<article class="module width_3_quarter">
		<header><h3 class="tabs_involved">Liste des news</h3>
		<ul class="tabs">
   			<li><a href="#tab1">En ligne</a></li>
    		<li><a href="#tab2">Supprimées</a></li>
		</ul>
		</header>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th></th> 
    				<th>Titre</th> 
    				<th>Categorie</th> 
    				<th>Postée le</th> 
    				<th>Actions</th> 
				</tr> 
			</thead> 
			<tbody> 
            	<?php
				$stmt = $bdd->prepare('SELECT * FROM news WHERE visible="1" ORDER BY id_news DESC LIMIT 0,5');
				$stmt->execute();
				while($donnees=$stmt->fetch())
				{
					echo "<tr>
						<td><input type='checkbox' name='news' id='".$donnees["id_news"]."'></td>
						<td>".$donnees["titre"]."</td>
						<td>".$donnees["categorie"]."</td>
						<td>".$donnees["date_post"]."</td>
						<td><a href='news.php?edit=".$donnees["id_news"]."' title='news'><input type='image' src='images/icn_edit.png' title='Edit'></a>
						<a href='news.php?del=".$donnees["id_news"]."' title='news'><input type='image' src='images/icn_trash.png' title='Trash'></a></td>
					
					<tr>";
				}
				$stmt->closeCursor();
				?>
			</tbody> 
			</table>
			</div><!-- end of #tab1 -->
			
			<div id="tab2" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
					<th></th> 
    				<th>Titre</th> 
    				<th>Categorie</th> 
    				<th>Postée le</th> 
    				<th>Actions</th>  
				</tr> 
			</thead> 
			<tbody> 
            	<?php
				$stmt = $bdd->prepare('SELECT * FROM news WHERE visible="0" ORDER BY id_news DESC LIMIT 0,5');
				$stmt->execute();
				while($donnees=$stmt->fetch())
				{
					echo "<tr>
						<td><input type='checkbox' name='news' id='".$donnees["id_news"]."'></td>
						<td>".$donnees["titre"]."</td>
						<td>".$donnees["categorie"]."</td>
						<td>".$donnees["date_post"]."</td>
						<td><a href='news.php?edit=".$donnees["id_news"]."' title='news'><input type='image' src='images/icn_edit.png' title='Edit'></a>
						<a href='news.php?up=".$donnees["id_news"]."' title='news'>Publier</a></td>
					
					<tr>";
				}
				$stmt->closeCursor();
				?>
			</tbody> 
			</table>

			</div><!-- end of #tab2 -->
			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->
        		<article class="module width_quarter">
			<header><h3>Options</h3></header>
				<div class="module_content">
                	<?php echo $menu;?>
                    
				</div>
		</article><!-- end of messages article -->
        
        		<div class="clear"></div>
		
		<article class="module width_full">
			<header><h3>Poster une nouvelle news</h3></header>
				<div class="module_content">
                <?php
		if(isset($_POST["envoi"]))
		{
			$verif_titre=Verif($_POST["titre"],"Titre",3,64);
			$verif_contenu=Verif($_POST["contenu"],"Contenu",3,64);
			$verif_tag=Verif($_POST["tag"],"Tag",0,64);
			if($verif_titre=="1" && $verif_contenu=="1" && $verif_tag=="1")
			{
				include("../includes/class.form.php");
				$pur=valideChaine($_POST["titre"]);
				echo '<h4 class="alert_success">Réussite - La news a été ajoutée</h4>';
				$req = $bdd->prepare('INSERT INTO news(text,titre,auteur,ip_auteur,premier_titre,tags,categorie)
				 VALUES(:text,:titre,:auteur,:ip_auteur,:premier_titre,:tags,:categorie)');
				$req->execute(array(
					'text' => $_POST["contenu"],
					'titre'=> $_POST["titre"],
					'auteur' => $_SESSION['id'],
					'ip_auteur' =>  $_SERVER["REMOTE_ADDR"],
					'premier_titre' => $pur,
					'tags' => $_POST["tag"],
					'categorie' =>$_POST["categorie"]
					));
			}
			else
			{
				$message="";
				$message.=($verif_titre!="1")?$verif_titre:"";
				$message.=($verif_contenu!="1")?$verif_contenu:"";
				$message.=($verif_tag!="1")?$verif_tag:"";
				echo '<h4 class="alert_error"><b>Erreur</b> - '.$message . '</h4> <br />' ;
			}
		}
		//On vérifie l'existance des champs
		$titre=(isset($_POST["titre"])!="") ? $_POST["titre"] : "";
		$contenu=(isset($_POST["contenu"])!="") ? $_POST["contenu"] : "";
		$categorie=(isset($_POST["categorie"])!="") ? $_POST["categorie"] : "";
		$tag=(isset($_POST["tag"])!="") ? $_POST["tag"] : "";	
		
		if(isset($_GET["del"]))
		{
			$stmt = $bdd->prepare('UPDATE news SET visible = "0" WHERE id_news = :id');
			$stmt->execute(array(
				'id' => $_GET["del"]
			));
			$stmt->execute();	
			$stmt->closeCursor();
		}
		if(isset($_GET["up"]))
		{
			$stmt = $bdd->prepare('UPDATE news SET visible = "1" WHERE id_news = :id');
			$stmt->execute(array(
				'id' => $_GET["up"]
			));
			$stmt->execute();	
			$stmt->closeCursor();
		}
		if(isset($_POST["update"]))
		{
			$verif_titre=Verif($_POST["titre"],"Titre",3,64);
			$verif_contenu=Verif($_POST["contenu"],"Contenu",3,64);
			$verif_tag=Verif($_POST["tag"],"Tag",0,64);
			if($verif_titre=="1" && $verif_contenu=="1" && $verif_tag=="1")
			{
				include("../includes/class.form.php");
				$pur=valideChaine($_POST["titre"]);
				echo '<h4 class="alert_success">Réussite - La news a été modifiée.</h4>';
				$req = $bdd->prepare('UPDATE news SET text = :text, titre = :titre, tags = :tags, categorie = :categorie WHERE id_news = :id');
				$req->execute(array(
					'text' => $_POST["contenu"],
					'titre'=> $_POST["titre"],
					'tags' => $_POST["tag"],
					'categorie' =>$_POST["categorie"],
					'id' => $_POST["id"]
					));
			}
			else
			{
				$message="";
				$message.=($verif_titre!="1")?$verif_titre:"";
				$message.=($verif_contenu!="1")?$verif_contenu:"";
				$message.=($verif_tag!="1")?$verif_tag:"";
				echo '<h4 class="alert_error"><b>Erreur</b> - '.$message . '</h4> <br />' ;
			}
		}
		if(isset($_GET["edit"]))
		{
			$stmt = $bdd->prepare('SELECT * FROM news WHERE id_news = "'.$_GET["edit"].'"');
			$stmt->execute();
			$donnees=$stmt->fetch();
				$id=$donnees["id_news"];
				$titre=$donnees["titre"];
				$contenu = $donnees["text"];
				$categorie = $donnees["categorie"];
				$tag = $donnees["tags"];
			$stmt->closeCursor();
		}

		?>
                
		
		
					<?php
					if(isset($_GET["edit"]))
					{
						echo '<form name="news" id="news" action="news.php?edit='.$_GET["edit"].'" method="post">';
					}
					else
					{
						echo '<form name="news" id="news" action="'.$_SERVER['PHP_SELF'].'" method="post">';
					}
					?>
                	
						<fieldset>
							<label for="titre">Titre</label>
							<input type="text" name="titre" id="titre" value="<?=$titre?>" />
						</fieldset>
						<fieldset>
							<label for="contenu">Contentu</label>
							<textarea id="contenu" name="contenu" rows="12" style="height:300px;"><?=$contenu?></textarea>
						</fieldset>
						<fieldset style="width:48%; float:left; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
							<label>Categorie</label>
							<select title="categorie" name="categorie" id="categorie" style="width:92%;">
                                 <?php
								$stmt = $bdd->prepare('SELECT * FROM news_cat');
								$stmt->execute(array(
								));
								while($donnees=$stmt->fetch())
								{
									echo "<option value='".$donnees["titre"]."'>".$donnees["titre"]."</option>";
								}
								$stmt->closeCursor(); 
								?>
							</select>
						</fieldset>
						<fieldset style="width:48%; float:left;"> <!-- to make two field float next to one another, adjust values accordingly -->
							<label for="tag">Tags</label>
							<input type="text" name="tag" id="tag" style="width:92%;" value="<?=$tag?>"/>
						</fieldset>
                   
                   <div class="clear"></div>
				</div>
			<footer>
				<div class="submit_link">
                	<?php
					if(isset($_GET["edit"]))
					{
						echo '<input type="hidden" name="id" id="id" value="'.$_GET["edit"].'" />';
						echo '<input type="submit" name="update" id="update" value="Modifier" class="alt_btn">';
					}
					else
					{
						echo '<input type="submit" name="envoi" id="envoi" value="Publier" class="alt_btn">';
					}
					?>
					
					<input type="submit" value="Reset">
                    </form>
				</div>
			</footer>
		</article><!-- end of post new article -->

<?php
}
?>

		<div class="spacer"></div>
	</section>

<?php include("includes/footer.php");?>