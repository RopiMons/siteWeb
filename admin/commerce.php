<?php
session_start();
include("../includes/class.ressource.php");
include("../includes/class.parametres.php");
include("../includes/db_connect.php");
include("../includes/class.user.php");
include("../includes/functions.php");
include("../includes/class.verif.php");
include("../includes/class.newsmanager.php");
VerifConnection($bdd, $_SESSION["id"], $_SESSION["password"], $_SESSION["niveau"], 3);

$breadcrumbs = '<a href="index.php">Index de l\'administration</a> <div class="breadcrumb_divider"></div> 
<a class="current">Gestion de votre(vos) commerce(s)</a>';

function Status($status) {
    switch ($status) {
        case 0: return "En cours de validation";
        case 1: return "Actif";
    }
}

include("includes/header.php");
?>
<section id="main" class="column">		
    <article class="module width_full">
        <header><h3>Gestion des commerces</h3></header>
        <div class="module_content">
            Dans cette page vous pouvez gérer votre/vos commerce(s). 
        </div>
    </article><!-- end of stats article -->


    <div class="clear"></div>


    <article class="module width_3_quarter">
        <header><h3 class="tabs_involved">Commerce(s)</h3>
        </header>
        <div class="tab_container">
            <div id="tab1" class="tab_content">
                <table class="tablesorter" cellspacing="0"> 
                    <thead> 
                        <tr> 
                            <th>Nom du commerce</th> 
                            <th>Status</th>
                            <th>Actions</th> 
                        </tr> 
                    </thead> 
                    <tbody> 
<?php
$i = 0;
$stmt = $bdd->prepare('SELECT * FROM compers WHERE compers_personnesID = :id');
$stmt->execute(array("id" => $_SESSION["id"]));
while ($donnees = $stmt->fetch()) {
    $i++;
    $comm = $bdd->prepare('SELECT * FROM commerce WHERE idcommerce = :id_comm');
    $comm->execute(array("id_comm" => $donnees["compers_commerceID"]));
    $donnees2 = $comm->fetch();

    echo "<tr>
	  <td>" . $donnees2["commercenom"] . "</td>
	  <td>" . Status($donnees2["commercestatus"]) . "</td>";
    
    if($donnees2["commercestatus"]>0)
    {
        echo "<td><a href='commerce-gerer.php?edit=" . $donnees2["idcommerce"] . "' title='pages'><input type='image' src='images/icn_edit.png' title='Edit'></a>
              </tr>";//"<a href='commerce-gerer.php?del=" . $donnees2["idcommerce"] . "' title='pages'><input type='image' src='images/icn_trash.png' title='Trash'></a></td>";
    }else{
        echo "<td>Pas d'actions possible pour l'instant</td>";
    }
	echo "<tr>";
    $comm->closeCursor();
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
        <div class="message_list">
            <div class="module_content">
                <div class="message"><p>Vous pouvez gérer <?= $i ?> commerces. </p></div>
                <div class="message"><p><a href="commerce-ajouter.php">Ajouter un nouveau commerce</a></p></div>
            </div>
        </div>
        <footer>
        </footer>
    </article><!-- end of messages article -->

    <div class="clear"></div>





    <div class="spacer"></div>
</section>

<?php include("includes/footer.php"); ?>