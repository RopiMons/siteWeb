<?php
session_start();
include("includes.php");
include("../includes/class.newsmanager.php");
VerifConnection($bdd, $_SESSION, 3);

$breadcrumbs = '<a href="index.php">Index de l\'administration</a> <div class="breadcrumb_divider"></div> 
<a href="commerce.php">Mon commerce</a> <div class="breadcrumb_divider"></div> 
<a class="current">Ajouter un commerce</a>';

$message = "";
$titre = "";
$description = "";
$rue = "";
$num = "";
$localite = "";
$cp = "";
$pays = "";

include("includes/header.php");

if ($_SESSION["niveau"] == 3) {
    if (isset($_POST["ajouter_commerce"])) {
        $verif_titre = Verif($_POST["titre"], "Titre du commerce", 3, 128);
        $verif_description = Verif($_POST["contenu"], "Description du commerce", 3);
        $verif_rue = Verif($_POST["rue"], "Rue", 3, 128);
        $verif_num = Verif($_POST["numero"], "Numéro", 1, 12);
        $verif_cp = Verif($_POST["cp"], "Code postal", 4, 8, "int");
        $verif_localite = Verif($_POST["localite"], "Localité", 2, 32);
        $verif_pays = Verif($_POST["pays"], "Pays", 2, 32);

        $titre = $_POST["titre"];
        $description = $_POST["contenu"];
        $rue = $_POST["rue"];
        $num = $_POST["numero"];
        $cp = $_POST["cp"];
        $localite = $_POST["localite"];
        $pays = $_POST["pays"];

        if ($verif_titre == 1) {
            if ($verif_description == 1) {
                if ($verif_rue == 1) {
                    if ($verif_num == 1) {
                        if ($verif_cp == 1) {
                            if ($verif_localite == 1) {
                                if ($verif_pays == 1) {
                                    $uniqid = uniqid();
                                    $id_adresse = "ad" . $uniqid;
                                    $id_commerce = "comm" . $uniqid;

                                    $req = $bdd->prepare('INSERT INTO adresses(idadresses,adresses_personnesID,adresses_catalogueadressesID,adressesrue,adressesnumero,adressescodepostal,adresseslocalite,adressespays,adressescommerceid)
				                        VALUES(:id_adresse,:id_user,:id_catalogue,:rue,:numero,:cp,:localite,:pays,:commerce)');
                                    $req->execute(array(
                                        'id_adresse' => $id_adresse,
                                        'id_user' => $_SESSION["id"],
                                        'id_catalogue' => $_POST['type'],
                                        'rue' => $_POST["rue"],
                                        'numero' => $_POST["numero"],
                                        'cp' => $_POST["cp"],
                                        'localite' => $_POST["localite"],
                                        'pays' => $_POST["pays"],
                                        'commerce' => $id_commerce
                                    ));

                                    $req = $bdd->prepare('INSERT INTO commerce(idcommerce,commercenom,commercecontenu,commercestatus)
				                        VALUES(:id_commerce,:nom,:contenu,:status)');
                                    $req->execute(array(
                                        'id_commerce' => $id_commerce,
                                        'nom' => $_POST["titre"],
                                        'contenu' => $_POST["contenu"],
                                        'status' => 0
                                    ));

                                    $req = $bdd->prepare('INSERT INTO compers(compers_personnesID,compers_commerceID)
				                        VALUES(:id_user,:id_commerce)');
                                    $req->execute(array(
                                        'id_user' => $_SESSION["id"],
                                        'id_commerce' => $id_commerce
                                    ));
                                    $req = $bdd->prepare('INSERT INTO telephones(telephone_idcommerce)
				                        VALUES(:id_commerce)');
                                    $req->execute(array(
                                        'id_commerce' => $id_commerce
                                    ));

                                    $req = $bdd->prepare('INSERT INTO typecommerce(typecommerce_commerceID)
				                        VALUES(:id_commerce)');
                                    $req->execute(array(
                                        'id_commerce' => $id_commerce
                                    ));


                                    $message = '<h4 class="alert_success">Réussite - Votre commerce a été proposé.</h4>';
                                } else
                                    $message = '<h4 class="alert_error">' . $verif_pays . '</h4>';
                            } else
                                $message = '<h4 class="alert_error">' . $verif_localite . '</h4>';
                        } else
                            $message = '<h4 class="alert_error">' . $verif_cp . '</h4>';
                    } else
                        $message = '<h4 class="alert_error">' . $verif_num . '</h4>';
                } else
                    $message = '<h4 class="alert_error">' . $verif_rue . '</h4>';
            } else
                $message = '<h4 class="alert_error">' . $verif_description . '</h4>';
        } else
            $message = '<h4 class="alert_error">' . $verif_titre . '</h4>';
    }
    ?>
    <section id="main" class="column">		
        <article class="module width_full">
            <header><h3>Ajouter un commerce</h3></header>
            <div class="module_content">
                <p>Dans cette page vous pouvez demander la création d'un nouveau commerce. Un administrateur devra le valider avant qu'il ne soit visible en ligne. </p>
            </div>
        </article><!-- end of stats article -->


        <div class="clear"></div>


        <article class="module width_full">
            <header><h3>Formulaire de demande d'ajout</h3></header>
            <div class="module_content">
                <?php echo $message; ?>
                <form name="ajouter" id="ajouter" action="#" method="post">
                    <fieldset>
                        <label for="titre">Titre du commerce</label>
                        <input type="text" name="titre" id="titre" value="<?php echo $titre ?>" />
                    </fieldset>
                    <fieldset>
                        <label for="contenu">Description</label>
                        <textarea id="contenu" name="contenu" rows="10" style="height:300px;"><?php echo $description ?></textarea>
                    </fieldset><div class="clear"></div>
                    <p>&nbsp;</p>
                    <h3>Votre adresse</h3>

                    <fieldset style="width:73%; float:left; margin-right: 3%;">
                        <label for="rue">Rue</label>
                        <input type="text" name="rue" id="rue" value="<?php echo $rue ?>" style="width:92%;"/>
                    </fieldset>
                    <fieldset style="width:23%; float:left;">
                        <label for="numero">Numéro</label>
                        <input type="text" name="numero" id="numero" value="<?php echo $num ?>" style="width:92%;"/>
                    </fieldset><div class="clear"></div>

                    <fieldset style="width:73%; float:left;margin-right: 3%;">
                        <label for="localite">Localité</label>
                        <input type="text" name="localite" id="localite" value="<?php echo $localite ?>" style="width:92%;"/>
                    </fieldset>
                    <fieldset style="width:23%; float:left; ">
                        <label for="cp">Code postal</label>
                        <input type="text" name="cp" id="cp" value="<?php echo $cp ?>" style="width:92%;"/>
                    </fieldset><div class="clear"></div>

                    <fieldset style="width:48%; float:left; margin-right: 3%;">
                        <label for="pays">Pays</label>
                        <input type="text" name="pays" id="pays" value="<?php echo $pays ?>" />
                    </fieldset>
                    <fieldset style="width:48%; float:left; "> <!-- to make two field float next to one another, adjust values accordingly -->
                        <label>Type</label>
                        <select title="type" name="type" id="type" style="width:92%;">
                            <?php
                            $stmt = $bdd->prepare('SELECT * FROM catalogueadresses');
                            $stmt->execute(array(
                            ));
                            while ($donnees = $stmt->fetch()) {
                                echo "<option value='" . $donnees["idcatalogueadresses"] . "'>" . $donnees["catalogueadresselabel"] . "</option>";
                            }
                            $stmt->closeCursor();
                            ?>
                        </select>
                    </fieldset>

            </div>
            <div class="clear"></div>
            <footer>
                <div class="submit_link">
                    <input type="submit" name="ajouter_commerce" id="ajouter_commerce" value="Ajouter le commerce" class="alt_btn">
                    <input type="submit" value="Reset">
                    </form>
                </div>
            </footer>

        </article><!-- end of messages article -->

        <div class="clear"></div>





        <div class="spacer"></div>
    </section>

    <?php
}
if ($_SESSION["niveau"] >= 9) {
    ?>
    <section id="main" class="column">	
        <?php
        if (isset($_GET["id"])) {
            ?>
            <article class="module width_full">
                <header><h3 class="tabs_involved">Détails sur un commerce</h3>
                </header>
                <div class="module_content">
                    <?php
                    $req = new RequetteSelect("commerce", array("commerce.commercenom", "adresses.adressesnumero", "adresses.adressesrue", "adresses.adressescodepostal", "adresses.adresseslocalite", "adresses.adressespays", "personnes.nompersonnes", "personnes.prenompersonnes"));
                    $req->where("idcommerce", ":id")
                            ->leftJoin("adressescommerceid", "adresses", "idcommerce")
                            ->leftJoin("compers_commerceID", "compers", "idcommerce")
                            ->leftJoin("idpersonnes", "personnes", "compers_personnesID", "compers");

                    $stm = $bdd->prepare($req);
                    $stm->execute(array(
                        ":id" => $_GET["id"],
                    ));
                    $donnees = $stm->fetchAll();
                    $stm->closeCursor();

                    echo "<p>Nom : " . $donnees[0]["commercenom"] . "</p>";

                    $identites = Array();

                    foreach ($donnees as $donnee) {
                        echo " <p>Adresse : <br \>" . $donnee["adressesnumero"] . ", " . $donnee["adressesrue"] . "<br/>" . $donnee["adressescodepostal"] . " " . $donnee["adresseslocalite"] . " <br /> " . $donnee["adressespays"] . "</p>";
                        $identites[] = " <p>Nom : " . $donnee["nompersonnes"] . ", prénom : " . $donnee["prenompersonnes"] . "</p>";
                    }
                    foreach ($identites as $identite){
                        echo $identite;
                    }
                }
                ?>
            </div><!-- end of .tab_container -->

        </article><!-- end of content manager article -->

    <?php
    if (isset($_GET["valid"])) {
        $req = $bdd->prepare('UPDATE commerce SET commercestatus = 1 WHERE idcommerce = :id');
        $req->execute(array(
            'id' => $_GET["valid"]
        ));
        $message = '<h4 class="alert_success">Le commerce a été validé.</h4>';
    }
    if (isset($_GET["refus"])) {
        $req = $bdd->prepare("DELETE FROM commerce WHERE idcommerce = :id");
        $req->execute(array(
            'id' => $_GET["refus"]
        ));
        $message = '<h4 class="alert_warning">Le commerce a été refusé et supprimé.</h4>';
    }
    echo $message;
    ?>
        <article class="module width_full">
            <header><h3 class="tabs_involved">Liste des commerces en attente de validation</h3>
            </header>
            <div class="tab_container">
                <div id="tab1" class="tab_content">
                    <table class="tablesorter" cellspacing="0"> 
                        <thead> 
                            <tr> 
                                <th>Nom</th> 
                                <th>Adresse</th> 
                                <th>CP</th> 
                                <th>Actions</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
    <?php
    $stmt = $bdd->prepare('SELECT * FROM commerce WHERE commercestatus=0');
    $stmt->execute();
    while ($donnees = $stmt->fetch()) {
        echo "<tr>
						<td>" . $donnees["commercenom"] . "</td>";
        $stmt2 = $bdd->prepare('SELECT * FROM adresses WHERE adressescommerceid=:id');
        $stmt2->execute(array("id" => $donnees["idcommerce"]));
        $donnees2 = $stmt2->fetch();
        echo"<td>" . $donnees2["adressesnumero"] . ", " . $donnees2["adressesrue"] . "</td>
						<td>" . $donnees2["adressescodepostal"] . "</td>
						
						<td>
						<a href='commerce-ajouter.php?id=" . $donnees["idcommerce"] . "' title='Chercher'><input type='image' src='images/icn_photo.png' title='Trash'></a>
						<a href='commerce-ajouter.php?valid=" . $donnees["idcommerce"] . "' title='Valider'><input type='image' src='images/icn_alert_success.png' title='Edit'></a>
						<a href='commerce-ajouter.php?refus=" . $donnees["idcommerce"] . "' title='Refuser'><input type='image' src='images/icn_alert_error.png' title='Trash'></a>
						
						</td>
					
					<tr>";
        $stmt2->closeCursor();
    }
    $stmt->closeCursor();
    ?>
                        </tbody> 
                    </table>
                </div><!-- end of #tab1 -->			
            </div><!-- end of .tab_container -->

        </article><!-- end of content manager article -->
    </section>
    <?php
}
?>

<?php include("includes/footer.php"); ?>