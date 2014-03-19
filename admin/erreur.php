<?php
session_start();
$breadcrumbs='<a class="current">Erreur</a>';
if(!isset($_GET["msg"]))
{
	header("location:index.php");
}
include("../includes/db_connect.php");
include("../includes/class.user.php");
//VerifConnection($bdd,$_SESSION["id"],$_SESSION["password"],$_SESSION["niveau"],1);
include("includes/header.php");

$message=array(
"1"=>"Vous n'avez pas le niveau nécessaire pour accéder à cette page.",
"2" => "Votre mot de passe a été changé. Par sécurité, nous vous avons déconnecté."
);
?>

	<section id="main" class="column">		
    
		<h4 class="alert_error"><?php echo $message[$_GET["msg"]]; ?></h4>
		<article class="module width_full">
			<header><h3>Erreur</h3></header>
			<div class="module_content">
            	Si vous pensez que cette erreur n'est pas justifiée, veuillez contacter un administrateur du site.
                <?php 
				if($_GET["msg"]==2)
				{
					unset($_SESSION['username'],$_SESSION["password"], $_SESSION['userid'], $_SESSION["niveau"]);
					echo '<br /><p><a href="../connexion.php">Retour au formulaire de connexion</a></p>';
				}?>
			</div>
		</article><!-- end of stats article -->



		
		<div class="clear"></div>
		
		<div class="spacer"></div>
	</section>

<?php include("includes/footer.php");?>