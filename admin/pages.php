<?php
session_start();
include("../includes/db_connect.php");
include("../includes/class.user.php");
include("../includes/functions.php");
include("../includes/class.verif.php");
include("../includes/class.newsmanager.php");
VerifConnection($bdd,$_SESSION["id"],$_SESSION["password"],$_SESSION["niveau"],9);
if(!isset($_GET["all"]))
{
	$breadcrumbs='<a href="index.php">Index de l\'administration</a> <div class="breadcrumb_divider"></div> 
	<a class="current">Gestion des news</a>';
}
else
{$breadcrumbs='<a href="index.php">Index de l\'administration</a> <div class="breadcrumb_divider"></div> 
				<a href="pages.php">Gestion des pages</a> <div class="breadcrumb_divider"></div>';
	
}
include("includes/header.php");

$envoye=false;
$message="";
		if(isset($_POST["envoi"]))
		{
			$verif_titre=Verif($_POST["titre"],"Titre",3,64);
			$verif_contenu=Verif($_POST["contenu"],"Contenu",3,64);
			if($verif_titre=="1" && $verif_contenu=="1")
			{
				include("../includes/class.form.php");
				$pur=valideChaine($_POST["titre"]);
				$message= '<h4 class="alert_success">Réussite - La page a été ajoutée</h4>';
				$req = $bdd->prepare('INSERT INTO pages(text,titre,auteur,ip_auteur,visible)
				 VALUES(:text,:titre,:auteur,:ip_auteur,:visible)');
				$req->execute(array(
					'text' => $_POST["contenu"],
					'titre'=> $_POST["titre"],
					'auteur' => $_SESSION['id'],
					'ip_auteur' =>  $_SERVER["REMOTE_ADDR"],
					'visible'=>$_POST["visible"]
				));
				$envoye=true;
			}
			else
			{
				$message="";
				$message.=($verif_titre!="1")?$verif_titre:"";
				$message.=($verif_contenu!="1")?$verif_contenu:"";
				$message= '<h4 class="alert_error"><b>Erreur</b> - '.$message . '</h4> <br />' ;
			}
		}
		//On vérifie l'existance des champs
		$titre=(isset($_POST["titre"])!="") ? $_POST["titre"] : "";
		$contenu=(isset($_POST["contenu"])!="") ? $_POST["contenu"] : "";
		$visible=(isset($_POST["visible"])!="") ? $_POST["visible"] : "";
		$type=(isset($_POST["type"])!="") ? $_POST["type"] : "";
		if(isset($_POST["update"]))
		{
			$verif_titre=Verif($_POST["titre"],"Titre",3,64);
			$verif_contenu=Verif($_POST["contenu"],"Contenu",3,64);
			if($verif_titre=="1" && $verif_contenu=="1")
			{
				include("../includes/class.form.php");
				$pur=valideChaine($_POST["titre"]);
				$message= '<h4 class="alert_success">Réussite - La page a été modifiée.</h4>';
				$req = $bdd->prepare('UPDATE pages SET text = :text, titre = :titre, visible = :visible, last_modif = :last_modif WHERE id_page = :id');
				$req->execute(array(
					'text' => $_POST["contenu"],
					'titre'=> $_POST["titre"],
					'id' => $_POST["id"],
					'visible' => $_POST["visible"],
					'last_modif' => date("Y-m-d H:i:s")
					));
			}
			else
			{
				$message="";
				$message.=($verif_titre!="1")?$verif_titre:"";
				$message.=($verif_contenu!="1")?$verif_contenu:"";
				$message='<h4 class="alert_error"><b>Erreur</b> - '.$message . '</h4> <br />' ;
			}
		}
		if(isset($_GET["del"]))
		{
			$stmt = $bdd->prepare('SELECT * FROM pages WHERE id_page = :del');
			$stmt->execute(array("del"=>$_GET["del"]));
			$donnees=$stmt->fetch();
			
				$visible=$donnees["visible"];
				
			$stmt->closeCursor();
			if($visible!="1")
			{
				$stmt = $bdd->prepare('UPDATE pages SET visible = "1" WHERE id_page = :id');
				$stmt->execute(array(
					'id' => $_GET["del"]
				));
				$stmt->closeCursor();
			}
			else
			{
				$count = $bdd->exec("DELETE FROM pages WHERE id_page = ".$_GET["del"] . "");
			}
			
		}
		if(isset($_GET["edit"]))
		{
			$stmt = $bdd->prepare('SELECT * FROM pages WHERE id_page = "'.$_GET["edit"].'"');
			$stmt->execute();
			$donnees=$stmt->fetch();
				$id=$donnees["id_page"];
				$titre=$donnees["titre"];
				$contenu = $donnees["text"];
				$visible=$donnees["visible"];
				$type=$donnees["type"];
			$stmt->closeCursor();
		}

$statut=TypeVisible();
?>
<SCRIPT LANGUAGE="JavaScript">
function confirmation() {
	var msg = "Êtes-vous sur de vouloir supprimer?";
	if (confirm(msg))
	{
		location.replace(supprimer.php);
	}
}
</SCRIPT>
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
echo $message;
$menu = '<ul>
                    	<li><a href="news.php?all" title="all">Afficher la liste complète des news</a></li>
                        <li><a href="news.php?cat" title="all">Gérer les catégories</a></li>
                    </ul>';
?>
	<section id="main" class="column">		
		<article class="module width_full">
			<header><h3>Gestion des news</h3></header>
			<div class="module_content">
            	Dans cette page vous pouvez gérer les news de votre site. En ajouter, supprimer, modifier, ...
			</div>
		</article><!-- end of stats article -->
		
		
		<div class="clear"></div>
		
		
		<article class="module width_full">
		<header><h3 class="tabs_involved">Liste des pages</h3>
		</header>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th></th> 
    				<th>Titre</th> 
    				<th>Créée le</th> 
                    <th>Dernière modification</th> 
                    <th>Statut</th>
    				<th>Actions</th> 
				</tr> 
			</thead> 
			<tbody> 
            	<?php
				$stmt = $bdd->prepare('SELECT * FROM pages ORDER BY id_page DESC');
				$stmt->execute();
				while($donnees=$stmt->fetch())
				{
					echo "<tr>
						<td><input type='checkbox' name='news' id='".$donnees["id_page"]."'></td>
						<td>".$donnees["titre"]."</td>
						<td>".$donnees["date_post"]."</td>
						<td>".$donnees["last_modif"]."</td>
						<td>".$statut[$donnees["visible"]]."</td>
						<td><a href='pages.php?edit=".$donnees["id_page"]."' title='pages'><input type='image' src='images/icn_edit.png' title='Edit'></a>
						<a href='pages.php?del=".$donnees["id_page"]."' title='pages'><input type='image' src='images/icn_trash.png' title='Trash'></a></td>
					
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
		
		<article class="module width_full">
			
                
                
		
		
					<?php
					if($message!="") echo $message;
					if(isset($_GET["edit"]))
					{
						echo '
						<header><h3>Modifier la page</h3></header>
						<div class="module_content">
						<form name="news" id="news" action="pages.php?edit='.$_GET["edit"].'" method="post">';
					}
					else
					{
						echo '
						<header><h3>Poster une nouvelle page</h3></header>
						<div class="module_content">
						<form name="news" id="news" action="'.$_SERVER['PHP_SELF'].'" method="post">';
					}
					
					if($envoye==true)
					{
						$titre="";
						$contenu="";
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
							<label>État</label>
							<select title="visible" name="visible" id="visible" style="width:92%;">
                            
                                <?php
								$i=0;
								foreach($statut as $statu)
								{
									$selected="";
									if($visible==$i) $selected='selected="selected"';
									echo "<option value='".$i."' ".$selected.">".$statut[$i]."</option>";
									$i++;
								}
								?>
							</select>
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
		<div class="spacer"></div>
	</section>

<?php include("includes/footer.php");?>