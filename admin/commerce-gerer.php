<?php
$url_image = "http://macdeb.eu/ropi_ok/img";
session_start();
include("../includes/class.ressource.php");
include("../includes/class.parametres.php");
include("../includes/db_connect.php");
include("../includes/class.user.php");
include("../includes/functions.php");
include("../includes/class.verif.php");
include("../includes/class.newsmanager.php");


if(isset($_GET["edit"]))
{
    // On  vérifie si le propriétaire de la ressource peut acceder à cette page.
    VerifConnection($bdd, $_SESSION["id"], $_SESSION["password"], $_SESSION["niveau"], 9, new Ressource("Commerce",$_GET["edit"],$bdd),"Edit");
}
else
{
    VerifConnection($bdd, $_SESSION["id"], $_SESSION["password"], $_SESSION["niveau"], 9);
}


$breadcrumbs = '<a href="index.php">Index de l\'administration</a> <div class="breadcrumb_divider"></div> 
<a href="commerce.php">Mon commerce</a> <div class="breadcrumb_divider"></div> 
<a class="current">Ajouter un commerce</a>';

$message = "";

include("includes/header.php");
?>
<section id="main" class="column">	
    <?php
    if (!isset($_GET["edit"])) {

        if (isset($_GET["id"])) {
            ?>
            <article class="module width_full">
                <header><h3 class="tabs_involved">Détails sur un commerce</h3>
                </header>
                <div class="module_content">
        <?php
        $stmt = $bdd->prepare('SELECT * FROM commerce WHERE idcommerce=:id');
        $stmt->execute(array("id" => $_GET["id"]));
        $donnees = $stmt->fetch();

        echo "<p>Nom :" . $donnees["commercenom"] . "</p>";

        $stmt2 = $bdd->prepare('SELECT * FROM adresses WHERE adressescommerceid=:id');
        $stmt2->execute(array("id" => $donnees["idcommerce"]));
        while ($donnees2 = $stmt2->fetch()) {
            echo" <p>Adresse : " . $donnees2["adressesnumero"] . ", " . $donnees2["adressesrue"] . "<br/>" . $donnees2["adressescodepostal"] . " " . $donnees2["adresseslocalite"] . " <br /> " . $donnees2["adressespays"] . "</p>";
        }
        $stmt2->closeCursor();

        $stmt2 = $bdd->prepare('SELECT * FROM compers WHERE compers_commerceID=:id');
        $stmt2->execute(array("id" => $donnees["idcommerce"]));
        while ($donnees2 = $stmt2->fetch()) {
            $stmt3 = $bdd->prepare('SELECT * FROM personnes WHERE idpersonnes=:id_personne');
            $stmt3->execute(array("id_personne" => $donnees2["compers_personnesID"]));
            $donnees3 = $stmt3->fetch();
            echo" <p>Nom : " . $donnees3["nompersonnes"] . ", prénom : " . $donnees3["prenompersonnes"] . "</p>";
            $stmt3->closeCursor();
        }
        $stmt2->closeCursor();


        $stmt->closeCursor();
        ?>
                </div><!-- end of .tab_container -->

            </article><!-- end of content manager article -->

                    <?php
                }
                ?>
        <article class="module width_full">
            <header><h3 class="tabs_involved">Liste des commerces</h3>
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
    $stmt = $bdd->prepare('SELECT * FROM commerce WHERE commercestatus<>0');
    $stmt->execute();
    while ($donnees = $stmt->fetch()) {
        $id_compers = false;
        $stmt2 = $bdd->prepare('SELECT * FROM compers WHERE compers_commerceID=:id');
        $stmt2->execute(array("id" => $donnees["idcommerce"]));
        while ($donnees2 = $stmt2->fetch()) {
            if ($_SESSION["id"] == $donnees2["compers_personnesID"])
                $id_compers = true;
        }
        $stmt2->closeCursor();
        if ($_SESSION["niveau"] == 9 || $id_compers == true) {

            echo "<tr>
						    <td>" . $donnees["commercenom"] . "</td>";
            $stmt2 = $bdd->prepare('SELECT * FROM adresses WHERE adressescommerceid=:id');
            $stmt2->execute(array("id" => $donnees["idcommerce"]));
            $donnees2 = $stmt2->fetch();
            echo"<td>" . $donnees2["adressesnumero"] . ", " . $donnees2["adressesrue"] . "</td>
						    <td>" . $donnees2["adressescodepostal"] . "</td>
						
						    <td>
						    <a href='commerce-gerer.php?edit=" . $donnees["idcommerce"] . "' title='pages'><input type='image' src='images/icn_edit.png' title='Edit'></a>
						    <a href='commerce-gerer.php?refus=" . $donnees["idcommerce"] . "' title='Refuser'><input type='image' src='images/icn_alert_error.png' title='Trash'></a>
						
						    </td>
					
					    <tr>";
        }
        $stmt2->closeCursor();
    }
    $stmt->closeCursor();
    ?>
                        </tbody> 
                    </table>
                </div><!-- end of #tab1 -->			
            </div><!-- end of .tab_container -->

        </article><!-- end of content manager article -->
                            <?php
                        } elseif (isset($_GET["edit"])) {
                            if (isset($_POST["IMUFiles"]) && count($_POST["IMUFiles"])) {
                                echo "<br />Uploaded files: ";
                                for ($i = 0; $i < count($_POST["IMUFiles"]); $i++) {
                                    $url_send = $url_image . "/" . $_POST["IMUFiles"][$i];
                                    echo "<br />" . $url_image . "/" . $_POST["IMUFiles"][$i];
                                    if ($_POST["type"] == "logo") {
                                        $req = $bdd->prepare('UPDATE commerce SET commercelogo = :url_image WHERE idcommerce = :id');
                                        $req->execute(array(
                                            'url_image' => $url_send,
                                            'id' => $_GET["edit"]
                                        ));
                                    } else {
                                        $req = $bdd->prepare('UPDATE commerce SET commerceimage = :url_image WHERE idcommerce = :id');
                                        $req->execute(array(
                                            'url_image' => $url_send,
                                            'id' => $_GET["edit"]
                                        ));
                                    }
                                }
                            }
                            $verif_autorisation_mod = false;
                            $stmt = $bdd->prepare('SELECT * FROM compers WHERE compers_personnesID = :id_comm');
                            $stmt->execute(array("id_comm" => $_SESSION["id"]));
                            while ($donnees = $stmt->fetch()) {
                                if ($_GET["edit"] == $donnees["compers_commerceID"]) {
                                    $verif_autorisation_mod = true;
                                }
                            }
                            $stmt->closeCursor();
                            if ($_SESSION["niveau"] == 9) {
                                $verif_autorisation_mod = true;

                                //Mise à jour des points ropi
                                if (isset($_POST["mod_points"])) {
                                    $req = $bdd->prepare('UPDATE commerce SET 
									    commercevaleurpublicitaire = :points 
										WHERE idcommerce = :id');
                                    $req->execute(array(
                                        'points' => $_POST["points"],
                                        'id' => $_GET["edit"]
                                    ));
                                    $message = '<h4 class="alert_success">Réussite - Les points ROPI ont été modifiés.</h4>';
                                }
                            }

                            if ($verif_autorisation_mod == false) {
                                echo '<h4 class="alert_error">Vous n\'avez pas l\'autorisation pour modifier ce commerce.</h4>';
                            } else {

                                if (isset($_GET["adresse"])) {
                                    if (isset($_GET["supp"])) {
                                        $req = $bdd->prepare("DELETE FROM adresses WHERE idadresses = :id");
                                        $req->execute(array(
                                            'id' => $_GET["adresse"]
                                        ));
                                        $message = '<h4 class="alert_warning">L\'adresse a été supprimée.</h4>';
                                    }
                                    if (isset($_POST["ajouter_commerce"])) {
                                        $verif_rue = Verif($_POST["rue"], "Rue", 3, 128);
                                        $verif_num = Verif($_POST["numero"], "Numéro", 1, 12);
                                        $verif_cp = Verif($_POST["cp"], "Code postal", 4, 8, "int");
                                        $verif_localite = Verif($_POST["localite"], "Localité", 2, 32);
                                        $verif_pays = Verif($_POST["pays"], "Pays", 2, 32);
                                        if ($verif_rue == 1) {
                                            if ($verif_num == 1) {
                                                if ($verif_cp == 1) {
                                                    if ($verif_localite == 1) {
                                                        if ($verif_pays == 1) {
                                                            $uniqid = uniqid();
                                                            $id_adresse = "ad" . $uniqid;


                                                            if ($_POST["id"] == "") {
                                                                $test_adresse_commerce_unique = true;

                                                                if ($_POST["type"] == 3) {
                                                                    $prepare = $bdd->prepare('SELECT * FROM adresses WHERE adressescommerceid= :id_comm AND adresses_catalogueadressesID=3');
                                                                    $prepare->execute(array('id_comm' => $_GET["edit"]));
                                                                    $result = $prepare->fetchAll(PDO::FETCH_ASSOC);
                                                                    if (count($result) != 0) {
                                                                        $test_adresse_commerce_unique = false;
                                                                    }
                                                                }
                                                                if ($test_adresse_commerce_unique == true) {
                                                                    $req = $bdd->prepare('INSERT INTO adresses(
							                idadresses,adresses_personnesID,adresses_catalogueadressesID,
								            adressesrue,adressesnumero,adressescodepostal,adresseslocalite,adressespays,adressescommerceid)
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
                                                                        'commerce' => $_GET["edit"]
                                                                    ));
                                                                    $message = '<h4 class="alert_success">Réussite - Votre adresse a été ajoutée.</h4>';
                                                                } else {
                                                                    $message = '<h4 class="alert_error">L\'adresse n\'a pas pu être ajoutée : vous avez déjà entré une adresse pour votre commerce.</h4>';
                                                                }
                                                            } else {
                                                                $req = $bdd->prepare('UPDATE adresses SET 
									    adressesrue = :rue, 
										adressesnumero = :num, 
										adressescodepostal = :cp,
										adresseslocalite = :localite,
										adressespays = :pays,
										adresses_catalogueadressesID = :type
										WHERE idadresses = :id');
                                                                $req->execute(array(
                                                                    'rue' => $_POST["rue"],
                                                                    'num' => $_POST["numero"],
                                                                    'cp' => $_POST["cp"],
                                                                    'localite' => $_POST["localite"],
                                                                    'pays' => $_POST["pays"],
                                                                    'type' => $_POST["type"],
                                                                    'id' => $_GET["adresse"]
                                                                ));
                                                                $message = '<h4 class="alert_success">Réussite - Votre adresse a été modifiée.</h4>';
                                                            }
                                                        } else
                                                            return $message = $verif_pays;
                                                    } else
                                                        return $message = $verif_localite;
                                                } else
                                                    return $message = $verif_cp;
                                            } else
                                                return $message = $verif_num;
                                        } else
                                            return $message = $verif_rue;
                                    }
                                }
                                if (isset($_GET["textes"])) {
                                    if (isset($_POST["modifier_texte"])) {
                                        $req = $bdd->prepare('UPDATE commerce SET commercecontenu = :text WHERE idcommerce = :id');
                                        $req->execute(array(
                                            'text' => $_POST["contenu"],
                                            'id' => $_GET["edit"]
                                        ));
                                        $message = '<h4 class="alert_success">Votre texte a été modifié.</h4>';
                                    }
                                }
                                if (isset($_GET["tel"])) {
                                    if (isset($_POST["num_tel"])) {
                                        $req = $bdd->prepare('UPDATE telephones SET telephone_gsm = :gsm, telephone_commerce = :tel WHERE telephone_idcommerce = :id');
                                        $req->execute(array(
                                            'gsm' => $_POST["gsm"],
                                            'tel' => $_POST["tel"],
                                            'id' => $_GET["edit"]
                                        ));
                                        $message = '<h4 class="alert_success">Vos numéros de téléphone ont été modifiés avec succès.</h4>';
                                    }
                                }
                                if (isset($_GET["image"])) {
                                    echo "get image";
                                    if (isset($_POST["IMUFiles"]) && count($_POST["IMUFiles"])) {
                                        echo "COUCOUUUUUUUUUUUUUUUUUUUU";
                                        echo "<br />Uploaded files: ";
                                        for ($i = 0; $i < count($_POST["IMUFiles"]); $i++) {
                                            echo "<br />" . $_POST["IMUFiles"][$i];
                                            $req = $bdd->prepare('UPDATE commerce ( commercelogo)
	                    VALUES(:url_image) WHERE icommerce = :id');
                                            $req->execute(array(
                                                'url_image' => $_POST["IMUFiles"][$i],
                                                'id' => $_GET["edit"]
                                            ));
                                        }
                                    }
                                }

                                if (isset($_GET["produit"])) {
                                    $nom = "";
                                    $contenu = "";
                                    if ($_GET["produit"] != "") {
                                        $stmt = $bdd->prepare('SELECT * FROM commerceproduits WHERE idproduit = :id_prod');
                                        $stmt->execute(array("id_prod" => $_GET["produit"]));
                                        $donnees = $stmt->fetch();
                                        if ($donnees["produitidcommerce"] == $_GET["edit"]) {
                                            $id = $donnees["idproduit"];
                                            $nom = $donnees["produitnom"];
                                            $contenu = $donnees["produitdescription"];
                                        } else {
                                            $message = '<h4 class="alert_error"><b>Erreur</b> Ce produit n\'appartient pas à votre commerce ! </h4>';
                                        }
                                        $stmt->closeCursor();
                                        if (isset($_GET["supp"])) {
                                            $nom = "";
                                            $contenu = "";
                                            $req = $bdd->prepare("DELETE FROM commerceproduits WHERE idproduit = :id");
                                            $req->execute(array(
                                                'id' => $_GET["produit"]
                                            ));
                                            $message = '<h4 class="alert_warning">Le produit a été supprimé.</h4>';
                                        }
                                    }

                                    if (isset($_POST["gerer_produit"])) {
                                        if ($_POST["id"] != "") {
                                            $req = $bdd->prepare('UPDATE commerceproduits SET produitnom = :titre, produitdescription = :contenu WHERE idproduit = :id');
                                            $req->execute(array(
                                                'titre' => $_POST["titre"],
                                                'contenu' => $_POST["contenu"],
                                                'id' => $_GET["produit"]
                                            ));
                                            $message = '<h4 class="alert_success">Votre produit a été modifié.</h4>';
                                        } else {
                                            $req = $bdd->prepare('INSERT INTO commerceproduits(produitnom, produitdescription, produitidcommerce)
				    VALUES(:nom,:contenu,:id_comm)');
                                            $req->execute(array(
                                                'nom' => $_POST["titre"],
                                                'contenu' => $_POST["contenu"],
                                                'id_comm' => $_GET["edit"]
                                            ));
                                            $message = '<h4 class="alert_success">Réussite - Votre produit a été ajouté.</h4>';
                                        }
                                    }
                                }
                                if (isset($_GET["type"])) {
                                    if (isset($_POST["modifier_type"])) {
                                        $req = $bdd->prepare('UPDATE typecommerce SET typecommerce_cataloguetypecommerceID = :type WHERE typecommerce_commerceID = :id');
                                        $req->execute(array(
                                            'type' => $_POST["type"],
                                            'id' => $_GET["edit"]
                                        ));
                                    }
                                }

                                $nbproduits = 0;
                                $stmt = $bdd->prepare('SELECT * FROM commerceproduits WHERE produitidcommerce = :id_comm');
                                $stmt->execute(array("id_comm" => $_GET["edit"]));
                                while ($donnees = $stmt->fetch()) {
                                    $nbproduits++;
                                }
                                $stmt->closeCursor();



                                echo $message;
                                ?>
            <article class="module width_3_quarter">
                <header><h3 class="tabs_involved">Gérer mon commerce</h3>
                </header>
                <div class="module_content">
                    Dans cette page, vous pouvez gérer votre commerce : ajouter une adresse, modifier l'image d'accueil, les textes,... 
                </div>
            </article>
            <article class="module width_quarter">
                <header><h3 class="tabs_involved">Menu</h3>
                </header>
                <div class="module_content">
                    <ul>
                        <li><a href="<?php echo"commerce-gerer.php?edit=" . $_GET["edit"] ?>">Retour à la gestion du commerce</a></li>
                        <li><a href="<?php echo"commerce-gerer.php?edit=" . $_GET["edit"] . "&amp;adresse" ?>">Ajouter une adresse</a></li>
            <?php
            if ($nbproduits < 10) {
                echo '<li><a href="commerce-gerer.php?edit=' . $_GET["edit"] . '&amp;produit">Ajouter un produit</a>' . " (" . (10 - $nbproduits) . " produits restants)</li>";
            } else {
                echo "<li>Vous avez déjà entré vos 10 produits</li>";
            }
            ?>
                        <li><a href="<?php echo"commerce-gerer.php?edit=" . $_GET["edit"] . "&amp;textes" ?>">Modifier les textes</a></li>
                        <li><a href="<?php echo"commerce-gerer.php?edit=" . $_GET["edit"] . "&amp;image" ?>">Modifier les images</a></li>
                        <li><a href="<?php echo"commerce-gerer.php?edit=" . $_GET["edit"] . "&amp;tel" ?>">Modifier les numéros de téléphone</a></li>
                        <li><a href="<?php echo"commerce-gerer.php?edit=" . $_GET["edit"] . "&amp;type" ?>">Modifier le type de commerce</a></li>
                        <li><a href="<?php echo'../commerce.php?q=' . $_GET["edit"] . '' ?>" target="_blank">Aperçu</a></li>
                    </ul>
            <?php
            if ($_SESSION["niveau"] == 9) {
                $stmt = $bdd->prepare('SELECT commercevaleurpublicitaire FROM commerce WHERE idcommerce=:id');
                $stmt->execute(array("id" => $_GET["edit"]));
                $donnees = $stmt->fetch();
                $points = $donnees["commercevaleurpublicitaire"];
                ?>
                        <form name="ajouter_adresse" id="ajouter_adresse" action="#" method="post">   
                            <fieldset>
                                <label for="points">Points ROPI</label>
                                <input type="text" id="points" name="points" value="<?php echo$points ?>" />
                            </fieldset>            
                            <input type="submit" name="mod_points" id="mod_points" value="Mettre à jour les points" class="alt_btn">

                        </form>
                <?php
            }
            ?>
                </div>
            </article>

            <?php
            if (isset($_GET["textes"])) {
                $stmt = $bdd->prepare('SELECT * FROM commerce WHERE idcommerce = :id_comm');
                $stmt->execute(array("id_comm" => $_GET["edit"]));
                $donnees = $stmt->fetch();
                $contenu = $donnees["commercecontenu"];
                $stmt->closeCursor();
                ?>
                <div class="clear"></div>
                <article class="module width_full">
                    <header><h3 class="tabs_involved">Modifier le texte de votre commerce</h3>
                    </header>
                    <div class="tab_container">
                        <form name="ajouter_adresse" id="ajouter_adresse" action="#" method="post">   
                            <fieldset>
                                <label for="contenu">Contentu</label>
                                <textarea id="contenu" name="contenu" rows="12" style="height:500px;"><?php echo$contenu ?></textarea>
                            </fieldset>            

                    </div>
                    <div class="clear"></div>
                    <footer>
                        <div class="submit_link">
                            <input type="submit" name="modifier_texte" id="modifier_texte" value="Modifier" class="alt_btn">
                            <input type="submit" value="Reset">

                        </div>
                    </footer>
                    </form>

                    </div>
                </article>

            <?php
        } elseif (isset($_GET["tel"])) {
            $stmt = $bdd->prepare('SELECT * FROM telephones WHERE telephone_idcommerce = :id_comm');
            $stmt->execute(array("id_comm" => $_GET["edit"]));
            $donnees = $stmt->fetch();
            $tel = $donnees["telephone_commerce"];
            $gsm = $donnees["telephone_gsm"];
            $stmt->closeCursor();
            ?>
                <div class="clear"></div>
                <article class="module width_full">
                    <header><h3 class="tabs_involved">Modifier les numéros de téléphone de votre commerce</h3>
                    </header>
                    <div class="tab_container">
                        <form name="num_tel" id="num_tel" action="#" method="post">   
                            <fieldset>
                                <label for="gsm">Numéro de gsm</label>
                                <input type="text" id="gsm" name="gsm" value="<?php echo$gsm ?>" />
                                <p><br /></p><br /><br />
                                <label for="tel">Numéro de téléphone pour votre commerce</label>
                                <input type="text" id="tel" name="tel" value="<?php echo$tel ?>" />
                            </fieldset>            

                    </div>
                    <div class="clear"></div>
                    <footer>
                        <div class="submit_link">
                            <input type="submit" name="num_tel" id="num_tel" value="Modifier" class="alt_btn">

                        </div>
                    </footer>
                    </form>

                    </div>
                </article>

                        <?php
                    } elseif (isset($_GET["image"])) {
                        $stmt = $bdd->prepare('SELECT * FROM commerce WHERE idcommerce = :id_comm');
                        $stmt->execute(array("id_comm" => $_GET["edit"]));
                        $donnees = $stmt->fetch();
                        $image = $donnees["commerceimage"];
                        $stmt->closeCursor();
                        ?>

                <div class="clear"></div>
                <article class="module width_half">
                    <header><h3 class="tabs_involved">Modifier le logo de votre commerce</h3>
                    </header>
                    <div class="module_content">
                        <p>Sélectionnez le logo dans vos fichiers pour l'envoyer sur le site.</p>

                        <form id="form" method="post" action="commerce-gerer.php?edit=<?php echo$_GET["edit"] ?>">
                            <input type="hidden" name="type" value="logo"/>
                            <input class="IMU" type="file" path="../img/" multi="false" afterUpload="image" startOn="onSubmit:form" maxSize="204800" thumbnails="200x" thumbnailsFolders="../img/partenaires/" thumbnailsAfterUpload="link,image,Thumbnail created!" />
                            <input type="submit" value="Submit" />
                        </form>




                    </div>
                </article>

                <article class="module width_half">
                    <header><h3 class="tabs_involved">Modifier la photo de votre commerce</h3>
                    </header>
                    <div class="module_content">
                        <p>Sélectionnez la photo dans vos fichiers pour l'envoyer sur le site. Préférez une photo de 940 pixels de large sur 350 pixels de haut pour avoir un affichage optimisé.</p>
                        <form id="form2" method="post" action="commerce-gerer.php?edit=<?php echo$_GET["edit"] ?>">
                            <input type="hidden" name="type" value="image"/>
                            <input class="IMU" type="file" path="../img/" multi="false" afterUpload="image" startOn="onSubmit:form2" maxSize="204800" thumbnails="200x" thumbnailsFolders="../img/partenaires/" thumbnailsAfterUpload="link,image,Thumbnail created!" />
                            <input type="submit" value="Submit" />
                        </form>


                    </div>

                </article><!-- end of content manager article -->


                <?php
            } elseif (isset($_GET["type"])) {
                $stmt = $bdd->prepare('SELECT * FROM typecommerce WHERE typecommerce_commerceID = :id_comm');
                $stmt->execute(array("id_comm" => $_GET["edit"]));
                $donnees = $stmt->fetch();
                $type = $donnees["typecommerce_cataloguetypecommerceID"];
                $stmt->closeCursor();
                ?>
                <div class="clear"></div>
                <article class="module width_full">
                    <header><h3 class="tabs_involved">Modifier le type de votre commerce</h3>
                    </header>
                    <div class="tab_container">
                        <form name="modifier_type" id="modifier_type" action="#" method="post">   
                            <fieldset style="width:48%; float:left; "> <!-- to make two field float next to one another, adjust values accordingly -->
                                <label>Type</label>
                                <select title="type" name="type" id="type" style="width:92%;">
                                    <option value="0">Aucun</option>
            <?php
            $stmt = $bdd->prepare('SELECT * FROM cataloguetypecommerce');
            $stmt->execute();
            while ($donnees = $stmt->fetch()) {
                if ($donnees["idcataloguetypecommerce"] == $type)
                    echo "<option value='" . $donnees["idcataloguetypecommerce"] . "' selected='selected'>" . $donnees["cataloguetypecommercelabel"] . "</option>";
                else
                    echo "<option value='" . $donnees["idcataloguetypecommerce"] . "'>" . $donnees["cataloguetypecommercelabel"] . "</option>";
            }
            $stmt->closeCursor();
            ?>
                                </select>
                            </fieldset>         

                    </div>
                    <div class="clear"></div>
                    <footer>
                        <div class="submit_link">
                            <input type="submit" name="modifier_type" id="modifier_type" value="Modifier" class="alt_btn">
                            <input type="submit" value="Reset">

                        </div>
                    </footer>
                    </form>

                    </div>
                </article>

            <?php
        }
        elseif (isset($_GET["adresse"])) {
            $id = "";
            $rue = "";
            $numero = "";
            $localite = "";
            $cp = "";
            $pays = "";
            $type = "";

            if ($_GET["adresse"] != "") {
                $stmt = $bdd->prepare('SELECT * FROM adresses WHERE idadresses = :id_ad');
                $stmt->execute(array("id_ad" => $_GET["adresse"]));
                $donnees = $stmt->fetch();
                $id = $donnees["idadresses"];
                $numero = $donnees["adressesnumero"];
                $rue = $donnees["adressesrue"];
                $localite = $donnees["adresseslocalite"];
                $cp = $donnees["adressescodepostal"];
                $pays = $donnees["adressespays"];
                $type = $donnees["adresses_catalogueadressesID"];
                $stmt->closeCursor();
            }
            ?>
                <div class="clear"></div>
                <article class="module width_full">
                    <header><h3 class="tabs_involved">Ajouter une adresse</h3>
                    </header>
                    <div class="tab_container">
                        <h3>Votre adresse</h3>
                        <form name="ajouter_adresse" id="ajouter_adresse" action="#" method="post">   
                            <input type="hidden" name="id" value="<?php echo$id ?>"/>     
                            <fieldset style="width:73%; float:left; margin-right: 3%;">
                                <label for="rue">Rue</label>
                                <input type="text" name="rue" id="rue" value="<?php echo$rue ?>" style="width:92%;"/>
                            </fieldset>
                            <fieldset style="width:23%; float:left;">
                                <label for="numero">Numéro</label>
                                <input type="text" name="numero" id="numero" value="<?php echo$numero ?>" style="width:92%;"/>
                            </fieldset><div class="clear"></div>

                            <fieldset style="width:73%; float:left;margin-right: 3%;">
                                <label for="localite">Localité</label>
                                <input type="text" name="localite" id="localite" value="<?php echo$localite ?>" style="width:92%;"/>
                            </fieldset>
                            <fieldset style="width:23%; float:left; ">
                                <label for="cp">Code postal</label>
                                <input type="text" name="cp" id="cp" value="<?php echo$cp ?>" style="width:92%;"/>
                            </fieldset><div class="clear"></div>

                            <fieldset style="width:48%; float:left; margin-right: 3%;">
                                <label for="pays">Pays</label>
                                <input type="text" name="pays" id="pays" value="<?php echo$pays ?>" />
                            </fieldset>
                            <fieldset style="width:48%; float:left; "> <!-- to make two field float next to one another, adjust values accordingly -->
                                <label>Type</label>
                                <select title="type" name="type" id="type" style="width:92%;">
                                    <?php
                                    $stmt = $bdd->prepare('SELECT * FROM catalogueadresses');
                                    $stmt->execute(array(
                                    ));
                                    while ($donnees = $stmt->fetch()) {
                                        if ($donnees["idcatalogueadresses"] == $type)
                                            echo "<option value='" . $donnees["idcatalogueadresses"] . "' selected='selected'>" . $donnees["catalogueadresselabel"] . "</option>";
                                        else
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


                    </div>
                </article>
                <?php
            }
            elseif (isset($_GET["produit"])) {
                if ($nbproduits < 10) {
                    if ($_GET["produit"] == "")
                        $text_button = "Ajouter un produit";
                    else
                        $text_button = "Modifier un produit";
                    ?>

                    <div class="clear"></div>
                    <article class="module width_full">
                        <header><h3 class="tabs_involved">Modifier le texte de votre commerce</h3>
                        </header>
                        <div class="module_content">
                            <form name="gerer_produit" id="gerer_produit" action="#" method="post">  
                                <input type="hidden" id="id" name="id" value="<?php echo$_GET["produit"] ?>"/>
                                <fieldset>
                                    <label for="titre">Nom du produit</label>
                                    <input type="text" id="titre" name="titre" value="<?php echo$nom ?>" />
                                </fieldset>            

                                <fieldset>
                                    <label for="contenu">Contentu</label>
                                    <textarea id="contenu" name="contenu" rows="12" style="height:500px;"><?php echo$contenu ?></textarea>
                                </fieldset>            

                        </div>
                        <div class="clear"></div>
                        <footer>
                            <div class="submit_link">
                                <input type="submit" name="gerer_produit" id="gerer_produit" value="<?php echo$text_button ?>" class="alt_btn">
                                <input type="submit" value="Reset">
                                </form>
                            </div>
                        </footer>


                        </div>
                    </article>
                <?php
            }
            else {
                ?>
                    <div class="clear"></div>
                    <article class="module width_full">
                        <header><h3 class="tabs_involved">Modifier le texte de votre commerce</h3>
                        </header>
                        <div class="module_content">
                            <p>Vous avez déjà entré vos 10 produits.</p>		
                        </div>
                    </article>

                <?php
            }
            ?>

                                    <?php
                                } else {
                                    ?>
                <div class="clear"></div>

                <article class="module width_half">
                    <header><h3 class="tabs_involved">Liste des adresses</h3>
                    </header>
                    <div class="tab_container">
                        <div id="tab1" class="tab_content">
                            <table class="tablesorter" cellspacing="0"> 
                                <thead> 
                                    <tr> 
                                        <th>Adresse</th> 
                                        <th>Type</th> 
                                        <th>Actions</th> 
                                    </tr> 
                                </thead> 
                                <tbody> 
            <?php
            $stmt = $bdd->prepare('SELECT * FROM adresses WHERE adressescommerceid = :id_comm');
            $stmt->execute(array("id_comm" => $_GET["edit"]));
            while ($donnees = $stmt->fetch()) {
                $stmt2 = $bdd->prepare('SELECT * FROM catalogueadresses WHERE idcatalogueadresses = :id');
                $stmt2->execute(array("id" => $donnees["adresses_catalogueadressesID"]));
                $donnees2 = $stmt2->fetch();

                $stmt2->closeCursor();

                echo "<tr>
						    <td>" . $donnees["adressesnumero"] . ", " . $donnees["adressesrue"] . "</td>";

                echo"<td>" . $donnees2["catalogueadresselabel"] . "</td>
						
						    <td>
						    <a href='commerce-gerer.php?edit=" . $_GET["edit"] . "&amp;adresse=" . $donnees["idadresses"] . "' title='pages'><input type='image' src='images/icn_edit.png' title='Edit'></a>
						    <a href='commerce-gerer.php?edit=" . $_GET["edit"] . "&amp;adresse=" . $donnees["idadresses"] . "&amp;supp' title='Refuser'><input type='image' src='images/icn_trash.png' title='Trash'></a>
						
						    </td>
					
					    </tr>";
            }
            $stmt->closeCursor();
            ?>
                                </tbody> 
                            </table>
                        </div><!-- end of #tab1 -->			
                    </div><!-- end of .tab_container -->

                </article><!-- end of content manager article -->


                <article class="module width_half">
                    <header><h3 class="tabs_involved">Liste des produits</h3>
                    </header>
                    <div class="tab_container">
                        <div>
                            <table class="tablesorter" cellspacing="0"> 
                                <thead> 
                                    <tr> 
                                        <th>Nom du produit</th> 
                                        <th>Actions</th> 
                                    </tr> 
                                </thead> 
                                <tbody> 
            <?php
            $stmt = $bdd->prepare('SELECT * FROM commerceproduits WHERE produitidcommerce = :id_comm');
            $stmt->execute(array("id_comm" => $_GET["edit"]));
            while ($donnees = $stmt->fetch()) {
                echo "<tr>
						    <td>" . $donnees["produitnom"] . "</td>";

                echo"<td>
						    <a href='commerce-gerer.php?edit=" . $_GET["edit"] . "&amp;produit=" . $donnees["idproduit"] . "' title='pages'><input type='image' src='images/icn_edit.png' title='Edit'></a>
						    <a href='commerce-gerer.php?edit=" . $_GET["edit"] . "&amp;produit=" . $donnees["idproduit"] . "&amp;supp' title='Refuser'><input type='image' src='images/icn_trash.png' title='Trash'></a>
						
						    </td>
					
					    </tr>";
            }
            $stmt->closeCursor();
            ?>
                                </tbody> 
                            </table>
                        </div><!-- end of #tab1 -->			
                    </div><!-- end of .tab_container -->

                </article><!-- end of content manager article -->







            <?php
        }
    }
}
?>
</section>
<!--TINYMCE-->
<script type="text/javascript" src="includes/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
    tinyMCE.init({
        // General options
        language: "fr",
        mode: "textareas",
        theme: "advanced",
        plugins: "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks",
        // Theme options
        theme_advanced_buttons1: "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
        theme_advanced_buttons4: "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks",
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_statusbar_location: "bottom",
        theme_advanced_resizing: true,
        // Example content CSS (should be your site CSS)
        content_css: "css/content.css",
        // Drop lists for link/image/media/template dialogs
        template_external_list_url: "lists/template_list.js",
        external_link_list_url: "lists/link_list.js",
        external_image_list_url: "lists/image_list.js",
        media_external_list_url: "lists/media_list.js",
        // Style formats
        style_formats: [
            {title: 'Bold text', inline: 'b'},
            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            {title: 'Example 1', inline: 'span', classes: 'example1'},
            {title: 'Example 2', inline: 'span', classes: 'example2'},
            {title: 'Table styles'},
            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
        ],
        // Replace values for the template plugin
        template_replace_values: {
            username: "Some User",
            staffid: "991234"
        }
    });
</script>
<!-- /TinyMCE -->


<?php include("includes/footer.php"); ?>