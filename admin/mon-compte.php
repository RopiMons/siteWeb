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
    echo "A";
    $stmt = $bdd->prepare('SELECT passwordpersonnes FROM personnes WHERE idpersonnes=:id');
    $stmt->execute(array("id" => $_SESSION["id"]));
    $donnees = $stmt->fetch();
    $password = $donnees["passwordpersonnes"];
    $stmt->closeCursor();
    if ($password != md5($_POST["premierpass"])) {
        if ($_POST["premierpass"] == "") {
            $msg1 = '<h4 class="alert_error">Erreur : Vous n\'avez pas entré votre mot de passe.</h4>';
        } else {
            $msg1 = '<h4 class="alert_error">Erreur : Le mot de passe entré ne correspond pas à votre mot de passe.</h4>';
        }
    } else {
        $verif_nom = Verif($_POST["nom"], "Nom", 2, 32);
        $verif_prenom = Verif($_POST["prenom"], "Prénom", 2, 32);
        $verif_mail = Verif($_POST["mail"], "adresse E-mail", 8, 48, "email","","","", $bdd, array(":val"=>$_POST["mail"],":id"=>$_SESSION["id"]));
        $verif_pseudo = Verif($_POST["pseudo"], "Pseudo", 2, 32, "pseudo", "", "", "", $bdd, array(":val" => $_POST["pseudo"], ":id" => $_SESSION["id"]));

        if ($verif_nom === true && $verif_prenom === true && $verif_mail === true && $verif_pseudo === true) {
            $req = $bdd->prepare('UPDATE personnes SET nompersonnes = :nom, prenompersonnes = :prenom, mailpersonnes = :mail, personnespseudo = :pseudo WHERE idpersonnes = :id');
            $req->execute(array(
                'nom' => $_POST["nom"],
                'prenom' => $_POST["prenom"],
                'mail' => $_POST["mail"],
                'pseudo' => $_POST["pseudo"],
                'id' => $_SESSION["id"]
            ));
            $msg1 = '<h4 class="alert_success">Réussite - Votre profil a été modifié.</h4>';
        } else {
            $message = "";
            $message.=($verif_nom != "1") ? $verif_nom : "";
            $message.=($verif_prenom != "1") ? $verif_prenom : "";
            $message.=($verif_mail != "1") ? $verif_mail : "";
            $message.=($verif_pseudo != "1") ? $verif_pseudo : "";
            $msg1 = '<h4 class="alert_error"><b>Erreur</b> - ' . $message . '</h4> <br />';
        }
        if ($_POST["pass"] != "") {
            if ($_POST["confirm"] != "") {
                if ($_POST["pass"] != $_POST["confirm"]) {
                    $msg2 = '<h4 class="alert_error">Erreur : Les deux mots de passe ne sont pas identiques.</h4>';
                } else {
                    $req = $bdd->prepare('UPDATE personnes SET passwordpersonnes = :password WHERE idpersonnes = :id');
                    $req->execute(array(
                        'password' => md5($_POST["pass"]),
                        'id' => $_SESSION["id"]
                    ));
                    $msg2 = '<h4 class="alert_success">Réussite - Votre mot de passe a été modifié.</h4>';
                    header("location:erreur.php?msg=2");
                }
            } else {
                $msg2 = '<h4 class="alert_error">Erreur : Vous devez aussi entrer la confirmation du mot de passe.</h4>';
            }
        }
    }
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
<?php
echo $msg1 . "<br />" . $msg2;
$stmt = $bdd->prepare('SELECT * FROM personnes WHERE idpersonnes=:id');
$stmt->execute(array("id" => $_SESSION["id"]));
$donnees = $stmt->fetch();

$prenom = $donnees["prenompersonnes"];
$nom = $donnees["nompersonnes"];
$pseudo = $donnees["personnespseudo"];
$mail = $donnees["mailpersonnes"];

$stmt->closeCursor();
?>
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
    </article><!-- end of post new article -->






    <div class="clear"></div>



    <div class="spacer"></div>
</section>

<?php include("includes/footer.php"); ?>