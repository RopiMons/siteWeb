<?php
session_start();
include("../includes/db_connect.php");
include("../includes/class.user.php");
include("../includes/functions.php");
include("../includes/class.verif.php");
include("../includes/class.newsmanager.php");
VerifConnection($bdd, $_SESSION["id"], $_SESSION["password"], $_SESSION["niveau"], 3);

$breadcrumbs = '<a href="index.php">Index de l\'administration</a> <div class="breadcrumb_divider"></div> 
	<a class="current">Parrainer un commerce</a>';
include("includes/header.php");
?>

<section id="main" class="column">		
    <article class="module width_full">
        <header><h3>Un commerce filleul</h3></header>
        <div class="module_content">
            <p>Dans cette page vous pouvez proposer l'affiliation d'un nouveau commerce.</p>
        </div>
    </article><!-- end of stats article -->

    <div class="clear"></div>

    <article class="module width_full">
        <header><h3>Formulaire de parrainage d'un commerce</h3></header>
        <div class="module_content">                
            <form name="ajouter" id="ajouter" action="parrainer.php" enctype="multipart/form-data">
                <fieldset>
                    <label for="nom">Nom du commerce</label>
                    <input type="text" name="nom" id="nom" value="<?php echo  $titre ?>" />
                </fieldset>

                <fieldset style="width:73%; float:left; margin-right: 3%;">
                    <label for="rue">Rue</label>
                    <input type="text" name="rue" id="rue" value="<?php echo  $rue ?>" style="width:92%;"/>
                </fieldset>
                <fieldset style="width:23%; float:left;">
                    <label for="numero">Numéro</label>
                    <input type="text" name="numero" id="numero" value="<?php echo  $num ?>" style="width:92%;"/>
                </fieldset><div class="clear"></div>

                <fieldset style="width:73%; float:left;margin-right: 3%;">
                    <label for="localite">Localité</label>
                    <input type="text" name="localite" id="localite" value="<?php echo  $localite ?>" style="width:92%;"/>
                </fieldset>
                <fieldset style="width:23%; float:left; ">
                    <label for="cp">Code postal</label>
                    <input type="text" name="cp" id="cp" value="<?php echo  $cp ?>" style="width:92%;"/>
                </fieldset><div class="clear"></div>

                <fieldset style="width:40%; float:left; margin-right: 3%;">
                    <label for="telephone">Téléphone</label>
                    <input type="text" name="telephone" id="telephone" value="<?php echo  $pays ?>" />
                </fieldset>                        
        </div>
        <div class="clear"></div>
        <footer>
            <div class="submit_link">
                <input type="submit" name="envoyer" id="envoyer" value="Ajouter le filleul" class="alt_btn">                              
            </div>
        </footer>				
        </form>
        </div>

    </article><!-- end of messages article -->

    <div class="clear"></div>

    <div class="spacer"></div>
</section>

<?php
if (isset($_REQUEST["envoyer"])) {

    $req = $bdd->prepare('INSERT INTO filleul (nom,rue,numero,localite,cp,telephone,parrain_id,dates) VALUES(:nom,:rue,:numero,:localite,:cp,:telephone,:parrain_id,CURDATE())');
    $req->execute(array(
        'nom' => $_REQUEST["nom"],
        'rue' => $_REQUEST["rue"],
        'numero' => $_REQUEST["numero"],
        'localite' => $_REQUEST["localite"],
        'cp' => $_REQUEST["cp"],
        'telephone' => $_REQUEST["telephone"],
        'parrain_id' => $_SESSION['id']
    ));

    $stmt = $bdd->prepare('SELECT * FROM personnes WHERE idpersonnes = :id');
    $stmt->execute(array("id" => $_SESSION["id"]));

    while ($donnees = $stmt->fetch()) {
        $headers = 'From: ' . $donnees["nompersonnes"] . ' <' . $donnees["prenompersonnes"] . '>' . "\r\n";

        $destinataire = '';
        $message = 'NOUVEAU PARRAINAGE DE : ' . $donnees["nompersonnes"] . ' ' . $donnees["prenompersonnes"] . ' LE COMMERCE : ' . $_REQUEST["nom"] . '    RUE '
                . $_REQUEST["rue"] . '   NUM : ' . $_REQUEST["numero"] . ' A;  ' . $_REQUEST["cp"] . '  ' . $_REQUEST["localite"] . ' A ETE PROPOSE.';
        $tel = '';
        if (mail($destinataire, $tel, $message, $headers))
            ;
    }
}
?>




<?php include("includes/footer.php"); ?>



