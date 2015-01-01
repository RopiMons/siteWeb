<?php
session_start();
include("includes.php");

VerifConnection($bdd, $_SESSION, 3);
$niveau = saveDB::getUserLevelBySession($bdd, $_SESSION);

$breadcrumbs = '<a href="index.php">Index de l\'administration</a> <div class="breadcrumb_divider"></div> 
	<a class="current">Panneau de contrôle</a>';
include("includes/header.php");
?>
<section id="main" class="column">	
    <article class="module width_full">
        <header><h3>Manuel d'utilisation</h3></header>
        <div class="module_content">
            <p>N'hésitez pas à consulter notre manuel d'utilisation en cas de problème. <a href="../manuel-ropi.pdf" target="_blank" title="Manuel d'utilisateur">Consulter le manuel</a></p>

            <?php
            if ($niveau == 9) {
                echo '<p>Notre manuel d\'administration est également à votre disposition. <a href="../manuel-ropi-admin.pdf" target="_blank" title="Manuel d\'administration">Consulter le manuel</a></p>';
            }
            ?>
        </div>		</article><!-- end of stats article -->
    <div class="spacer"></div>
</section>

<?php include("includes/footer.php"); ?>