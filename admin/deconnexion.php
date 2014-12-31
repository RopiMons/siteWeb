<?php
session_start();
include("includes.php");

$breadcrumbs='<a href="index.php">Index de l\'administration</a> <div class="breadcrumb_divider"></div> 
	<a class="current">Déconnexion</a>';

unset($_SESSION['username'],$_SESSION["password"], $_SESSION['userid']);

include("includes/header.php");
?>

	
	
	<section id="main" class="column">
		<article class="module width_full">
			<header><h3>Déconnexion</h3></header>
				<div class="module_content">
               		
					<p>Vous avez été correctement déconnecté.</p>

<p>Vérifiez que vous n'avez pas coché une case à votre connexion pour "se souvenir du mot de passe". Si vous n'êtes plus tout à fait sûr, effacez vos traces du navigateur. Si vous utilisez un ordinateur public (cybercafé, lieu de travail,...) pour une brève connexion, n'hésitez pas à utiliser la navigation privée de votre navigateur. </p>

<p><a href="../index.php">Retour à l'index du site</a></p>
				</div>
		</article><!-- end of styles article -->
		<div class="spacer"></div>
	</section>

<?php include("includes/footer.php");?>