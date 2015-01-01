<?php
session_start();
$menu = '<a href="?add">Ajouter un membre</a>';
include("includes.php");
include("../includes/class.newsmanager.php");
VerifConnection($bdd, $_SESSION, 3);
$niveau = saveDB::getUserLevelBySession($bdd, $_SESSION);

$breadcrumbs = '<a href="index.php">Index de l\'administration</a> <div class="breadcrumb_divider"></div> 
				<a href="news.php">Gestion des membres</a> <div class="breadcrumb_divider"></div>
				<a class="current">Toutes les news</a>';
$message = "";
$msg1 = "";
$msg2 = "";
if (isset($_POST["moduser"])) {

    $requette = new RequetteSelect("personnes", "passwordpersonnes AS mdp");
    $requette->where("idpersonnes", ":id");

    $donnees = saveDB::execute($bdd, $requette->getSQL(), array(":id" => $_SESSION["id"]));

    if ($donnees[0]["mdp"] != md5($_POST["premierpass"])) {
        if ($_POST["premierpass"] == "") {
            $msg1 = '<h4 class="alert_error">Erreur : Vous n\'avez pas entré votre mot de passe.</h4>';
        } else {
            $msg1 = '<h4 class="alert_error">Erreur : Le mot de passe entré ne correspond pas à votre mot de passe.</h4>';
        }
    } else {
        $check = CheckFormulaire($_POST["nom"], $_POST["prenom"], $_POST["pseudo"], $_POST["mail"], $_POST["premierpass"], $_POST["premierpass"], array("pseudo" => array(":val" => $_POST["pseudo"], ":id" => $_SESSION["id"]), "email" => array(":val" => $_POST["mail"], ":id" => $_SESSION["id"])), $bdd);

        if ($check === 1) {
            $requette = new RequetteUpdate("personnes");
            $requette->addValue('nompersonnes', ':nom')->addValue('prenompersonnes', ':prenom')->addValue('personnespseudo', ':pseudo')->addValue('mailpersonnes', ':mail')->where("idpersonnes", ':id');

            if (saveDB::executeSecureAdminRequest($bdd, $_SESSION, new Ressource("Personne", $_SESSION["id"], $bdd), $requette, array(':nom' => $_POST["nom"], ':prenom' => $_POST["prenom"], ':pseudo' => $_POST["pseudo"], ':mail' => $_POST["mail"], ':id' => $_SESSION["id"]))) {
                $msg1 = '<h4 class="alert_success">Réussite - Votre profil a été modifié.</h4>';
            } else {
                $msg1 = '<h4 class="alert_error">Vous n\'avez pas les droits suffisant pour modifier cette Ressource.</h4> <br />';
            }
        } else {
            $msg1 = '<h4 class="alert_error">' . $check . '</h4> <br />';
        }
        if ($_POST["pass"] != "") {
            if ($_POST["confirm"] != "") {
                if ($_POST["pass"] != $_POST["confirm"]) {
                    $msg2 = '<h4 class="alert_error">Erreur : Les deux mots de passe ne sont pas identiques.</h4>';
                } else {

                    $requette = new RequetteUpdate('personnes');
                    $requette->addValue('passwordpersonnes', ':mdp')->where("idpersonnes", ":id");

                    if (saveDB::executeSecureAdminRequest($bdd, $_SESSION, new Ressource("Personne", $_SESSION["id"], $bdd), $requette, array(':mdp' => md5($_POST["pass"]), ':id' => $_SESSION["id"]))) {
                        $msg2 = '<h4 class="alert_success">Réussite - Votre mot de passe a été modifié.</h4>';
                        header("location:erreur.php?msg=2");
                    } else {
                        $msg2 = "<h4 class='alert_error'>Vous n'avez pas l'autorisation de modifier cette Ressource</h4>";
                    }
                }
            } else {
                $msg2 = '<h4 class="alert_error">Erreur : Vous devez aussi entrer la confirmation du mot de passe.</h4>';
            }
        }
    }
}


$requette = new RequetteSelect("personnes", array("prenompersonnes AS prenom", "nompersonnes AS nom", "personnespseudo AS pseudo", "mailpersonnes AS mail"));

if ($donnes = saveDB::executeSecureAdminRequest($bdd, $_SESSION, new Ressource("Personne", $_SESSION["id"], $bdd), $requette->where("idpersonnes", ":id"), array(':id' => $_SESSION["id"]))) {
    $donnees = $donnes[0];
    $prenom = $donnees["prenom"];
    $nom = $donnees["nom"];
    $pseudo = $donnees["pseudo"];
    $mail = $donnees["mail"];
} else {
    header("location:deconnexion.php");
}

include("includes/header.php");

?>


<section id="main" class="column">		

    <article class="module width_full">
        <header><h3>Modifier le compte</h3></header>
        <div class="module_content">
            Dans cette page, vous pouvez modifier votre compte. <?php
            if ($niveau == 9) {
                echo "<br />N'oubliez pas de cocher le niveau de l'utilisateur une fois que vous avez terminé.";
            }
            ?>
        </div>
    </article><!-- end of stats article -->
    <?php echo $message; ?>
    <article class="module width_full">
        <header><h3>Modifier l'utilisateur</h3>
        </header>


        <div class="module_content">
            <?php echo $msg1 . "<br \>" . $msg2; ?>
            <form name="moduser" id="moduser" action="mon-compte.php" method="post">
                <fieldset style="width:48%; float:left; margin-right: 3%;">
                    <label for="prenom">Prénom</label>
                    <input type="text" name="prenom" id="prenom" value="<?php echo $prenom ?>" style="width:92%;"/>
                </fieldset>
                <fieldset style="width:48%; float:left;">
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" id="nom" value="<?php echo $nom ?>" style="width:92%;"/>
                </fieldset><div class="clear"></div>
                <fieldset>
                    <label for="pseudo">Nom d'utilisateur</label>
                    <input type="text" name="pseudo" id="pseudo" value="<?php echo $pseudo ?>" />
                </fieldset>
                <fieldset>
                    <label for="mail">Adresse Email</label>
                    <input type="text" name="mail" id="mail" value="<?php echo $mail ?>" />
                </fieldset>
                <fieldset>
                    <label for="pass">Mot de passe</label>
                    <input type="password" name="premierpass" id="premierpass"/>
                </fieldset>
                <br />
                <h3>Si vous désirez changer de mot de passe, complétez les deux champs ci-dessous. Sinon, laissez-les vides.</h3>
                <fieldset style="width:48%; float:left; margin-right: 3%;">
                    <label for="pass">Mot de passe</label>
                    <input type="password" name="pass" id="pass" style="width:92%;"/>
                </fieldset>
                <fieldset style="width:48%; float:left;">
                    <label for="confirm">Confirmation</label>
                    <input type="password" name="confirm" id="confirm" style="width:92%;"/>
                </fieldset>


                <div class="clear"></div>
        </div>
        <footer>
            <div class="submit_link">
                <input type="submit" name="moduser" id="moduser" value="Modifier" class="alt_btn">
                <input type="submit" value="Reset">
                </form>
            </div>
        </footer>
    </article>

    <div class="clear"></div>



    <div class="spacer"></div>
</section>

<?php include("includes/footer.php"); ?>