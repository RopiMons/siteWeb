<?php
session_start();
$menu = '<a href="?add">Ajouter un membre</a>';
include("../includes/db_connect.php");
include("../includes/class.user.php");
include("../includes/functions.php");
include("../includes/class.verif.php");
include("../includes/class.newsmanager.php");
VerifConnection($bdd, $_SESSION["id"], $_SESSION["password"], $_SESSION["niveau"], 9);
$breadcrumbs = '<a href="index.php">Index de l\'administration</a> <div class="breadcrumb_divider"></div> 
				<a href="news.php">Gestion des membres</a> <div class="breadcrumb_divider"></div>
				<a class="current">Toutes les news</a>';
include("includes/header.php");
$message = "";

function Niveau($bdd, $id, $niveaux) {
    
    // On supprime les anciennes autorisations de la base de donnée
    $req = $bdd->prepare('DELETE FROM typepersonnepersonne WHERE typeperonne_idpersonnes= :id');
    $req->execute(array(
        'id' => $id
    ));
    $req->closeCursor();
    
    // On ajoute les niveaux autorisés à la table de liaison
    
    foreach ($niveaux as $niveau) {
        $req = $bdd->prepare('INSERT INTO typepersonnepersonne(typeperonne_idpersonnes,typepersonnepersonne_cataloguetypepersonne)VALUES(:id,:niveau)');
        $req->execute(array(
            'id' => $id,
            'niveau' => $niveau
        ));
        $req->closeCursor();
    }
}

if (isset($_POST["adduser"])) {
    $message = CheckFormulaire($_POST["prenom"], $_POST["nom"], $_POST["pseudo"], $_POST["mail"], $_POST["pass"], $_POST["confirm"]);
    if ($message == 1) {
        $id = uniqid();
        $req = $bdd->prepare('INSERT INTO personnes(idpersonnes,nompersonnes,prenompersonnes,mailpersonnes,passwordpersonnes,personnespseudo)
			VALUES(:id,:nom,:prenom,:mail,:password,:pseudo)');
        $req->execute(array(
            'id' => $id,
            'nom' => $_POST["nom"],
            'prenom' => $_POST["prenom"],
            'mail' => $_POST['mail'],
            'password' => md5($_POST["pass"]),
            'pseudo' => $_POST["pseudo"]
        ));
        Niveau($bdd, $id, $_POST["options"]);
        $message = '<h4 class="alert_success">Réussite, l\'utilisateur a été créé.</h4>';

        //AJOUTE L'UTILISATEUR
    }
} elseif (isset($_POST["envoimail"])) {
    $verif_mail = Verif($_POST["email"], "adresse E-mail", 8, 48, "email");
    if ($verif_mail == 1) {
        $id = uniquid();
        $message = '<h4 class="alert_success">Réussite, le mail a été envoyé.</h4>';
        $to = $_POST["email"];
        $subject = 'ROPI - Invitation à créer votre compte';
        $contenu = 'Bonjour !\r\n
					Vous recevez cet e-mail car un administrateur du Ropi.be vous invite à créer votre compte.\r\n
					Pour créer votre compte, il vous suffit de cliquer sur le lien ci-dessous et de remplir le formulaire.\r\n
					Voici le lien : <a href="http://ropi.be/inscription.php?id=' . $id . '>Cliquez ici</a>.\r\n
					Cordialement, \r\n
					';
        $headers = 'From: info@' . $domaine . "\r\n" .
                'Reply-To: info@' . $domaine . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $contenu, $headers);
    } else {
        $message = '<h4 class="alert_error">Erreur :' . $verif_mail . '</h4>';
    }
}
?>


<section id="main" class="column">		


<?php
if (isset($_GET["add"])) {
    ?>
        <article class="module width_full">
            <header><h3>Ajouter un utilisateur</h3></header>
            <div class="module_content">
                Dans cette page, vous pouvez ajouter un utilisateur. Vous entrez vous-même le nom d'utilisateur, adresse E-mail, et mot de passe.
            </div>
        </article><!-- end of stats article -->
        <?php echo $message; ?>
        <article class="module width_half">
            <header><h3>1) Ajouter un utilisateur</h3>
            </header>



            <div class="module_content">
        <?php
        $prenom = (isset($_POST["prenom"]) != "") ? $_POST["prenom"] : "";
        $nom = (isset($_POST["nom"]) != "") ? $_POST["nom"] : "";
        $pseudo = (isset($_POST["pseudo"]) != "") ? $_POST["pseudo"] : "";
        $password = (isset($_POST["password"]) != "") ? $_POST["password"] : "";
        $confirmation = (isset($_POST["confirmation"]) != "") ? $_POST["confirmation"] : "";
        $mail = (isset($_POST["mail"]) != "") ? $_POST["mail"] : "";
        ?>
                <form name="adduser" id="adduser" action="users.php?add" method="post">
                    <fieldset style="width:48%; float:left; margin-right: 3%;">
                        <label for="prenom">Prénom</label>
                        <input type="text" name="prenom" id="prenom" value="<?php echo $prenom; ?>" style="width:92%;"/>
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
                    <fieldset style="width:48%; float:left; margin-right: 3%;">
                        <label for="pass">Mot de passe</label>
                        <input type="password" name="pass" id="pass"  style="width:92%;"  />
                    </fieldset>
                    <fieldset style="width:48%; float:left;">
                        <label for="confirm">Confirmation</label>
                        <input type="password" name="confirm" id="confirm"  style="width:92%;" />
                    </fieldset>
                    <fieldset> 
                        <label>Type d'utilisateur</label>
    <?php
    $stmt = $bdd->prepare('SELECT * FROM cataloguetypersonne ORDER BY idcataloguetypersonne');
    $stmt->execute();
    while ($donnees = $stmt->fetch()) {
        
        echo '<br /><p><label><input type="checkbox" name="options[]" value="' . $donnees["idcataloguetypersonne"] . '" >' . $donnees["cataloguetypersonnelabel"] . '</label></p>';
    }
    $stmt->closeCursor();
    ?>
                    </fieldset>

                    <div class="clear"></div>
            </div>
            <footer>
                <div class="submit_link">
                    <input type="submit" name="adduser" id="adduser" value="Ajouter le membre" class="alt_btn">
                    <input type="submit" value="Reset">
                    </form>
                </div>
            </footer>
        </article><!-- end of post new article -->
        <!--<article class="module width_half">
                <header><h3>2) Envoyer un mail d'inscription (recommandé)</h3>
                </header>
                        <div class="module_content">
                                <form name="envoimail" id="envoimail" action="users.php?add" method="post">
                                        <fieldset>
                                                <label for="mail">Email</label>
                                                <input type="text" name="email" id="email"/>
                                        </fieldset>
           
           <div class="clear"></div>
                        </div>
                <footer>
                        <div class="submit_link">
                <input type="submit" name="envoimail" id="envoimail" value="Envoyer le mail" class="alt_btn">
                                <input type="submit" value="Reset">
            </form>
                        </div>
                </footer>
        </article><!-- end of post new article -->
    <?php
} elseif (isset($_GET["mod"])) {
    ?>
        <article class="module width_full">
            <header><h3>Ajouter un utilisateur</h3></header>
            <div class="module_content">
                Dans cette page, vous pouvez modifier un utilisateur. N'oubliez pas de cocher le niveau de l'utilisateur après chaque modification.
            </div>
        </article><!-- end of stats article -->
        <?php echo $message; ?>
        <article class="module width_half">
            <header><h3>Modifier l'utilisateur</h3>
            </header>
        <?php
        if (isset($_POST["moduser"]) && isNiveau("9", $_SESSION["id"], $bdd)) {
            if ($_POST["pass"] != $_POST["premierpass"])
                $pass = md5($_POST["pass"]);
            else
                $pass = $_POST["premierpass"];

            $req = $bdd->prepare('UPDATE personnes SET nompersonnes = :nom, prenompersonnes = :prenom, mailpersonnes = :mail, passwordpersonnes = :password, personnespseudo = :pseudo WHERE idpersonnes = :id');
            $req->execute(array(
                'nom' => $_POST["nom"],
                'prenom' => $_POST["prenom"],
                'mail' => $_POST["mail"],
                'password' => $pass,
                'pseudo' => $_POST["pseudo"],
                'id' => $_GET["mod"]
            ));
            Niveau($bdd, $_GET["mod"], $_POST["options"]);
            $message = '<h4 class="alert_success">Réussite - Le membre a été mis à jour.</h4>';
            // Si L'utilisateur courant modifie ses informations, on force la reconnexion afin que les nouvelles informations soient prisent en compte
            if($_GET["mod"] == $_SESSION["id"])
            {
                Connexion($bdd, $_SESSION["username"], $mdp["pasword"]);
            }
            
        }
        ?>



            <div class="module_content">
            <?php
            echo $message;
            $stmt = $bdd->prepare('SELECT * FROM personnes WHERE idpersonnes=:id');
            $stmt->execute(array("id" => $_GET["mod"]));
            $donnees = $stmt->fetch();

            $prenom = $donnees["prenompersonnes"];
            $nom = $donnees["nompersonnes"];
            $pseudo = $donnees["personnespseudo"];
            $password = $donnees["passwordpersonnes"];
            $mail = $donnees["mailpersonnes"];

            $stmt->closeCursor();
            ?>
                <form name="adduser" id="adduser" action="users.php?mod=<?php echo $_GET["mod"] ?>" method="post">
                    <input type="hidden" name="idpersonnes" id="idpersonnes" value="<?php echo $_GET["mod"]; ?>"/>
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
                        <input type="password" name="pass" id="pass" value="<?php echo $password ?>"/>
                        <input type="hidden" name="premierpass" id="premierpass" value="<?php echo $password ?>"/>
                    </fieldset>
                    <fieldset> 
                        <label>Type d'utilisateur</label>
    <?php
    $stmt = $bdd->prepare('SELECT * FROM cataloguetypersonne ORDER BY idcataloguetypersonne');
    $stmt->execute();
    while ($donnees = $stmt->fetch()) {
        $checked = "";
        if(isNiveau($donnees["idcataloguetypersonne"], $_GET["mod"], $bdd)){
            $checked = "checked";
        }
        echo '<br /><p><label><input type="checkbox" name="options[]" value="' . $donnees["idcataloguetypersonne"] . '" '.$checked.'>' . $donnees["cataloguetypersonnelabel"] . '</label></p>';
    }
    $stmt->closeCursor();
    ?>
                    </fieldset>

                    <div class="clear"></div>
            </div>
            <footer>
                <div class="submit_link">
                    <input type="submit" name="moduser" id="moduser" value="Modifier le membre" class="alt_btn">
                    <input type="submit" value="Reset">
                    </form>
                </div>
            </footer>
        </article><!-- end of post new article -->

    <?php
}
?>





    <div class="clear"></div>


    <article class="module width_3_quarter">
        <header><h3 class="tabs_involved">Liste des utilisateurs</h3>
        </header>
        <div class="tab_container">
            <div id="tab1" class="tab_content">
                <table class="tablesorter" cellspacing="0"> 
                    <thead> 
                        <tr> 
                            <th>Nom</th> 
                            <th>Pseudo</th> 
                            <th>Mail</th> 
                            <th>Actions</th> 
                        </tr> 
                    </thead> 
                    <tbody> 
<?php
$stmt = $bdd->prepare('SELECT * FROM personnes');
$stmt->execute();
while ($donnees = $stmt->fetch()) {
    echo "<tr>
						<td>" . $donnees["nompersonnes"] . "</td>
						<td>" . $donnees["personnespseudo"] . "</td>
						<td>" . $donnees["mailpersonnes"] . "</td>
						<td><a href='users.php?mod=" . $donnees["idpersonnes"] . "' title='news'><input type='image' src='images/icn_edit.png' title='Edit'></a>";
    //echo "<a href='users.php?del=".$donnees["idpersonnes"]."' title='news'><input type='image' src='images/icn_trash.png' title='Trash'></a>"
    echo "</td>";

    echo "<tr>";
}
$stmt->closeCursor();
?>
                    </tbody> 
                </table>
            </div><!-- end of #tab1 -->
        </div><!-- end of .tab_container -->

    </article><!-- end of content manager article -->
    <article class="module width_quarter">
        <header><h3>Options</h3></header>
        <div class="module_content">
                        <?php echo $menu; ?>

        </div>
    </article><!-- end of messages article -->

    <div class="clear"></div>

    <div class="spacer"></div>
</section>

<?php include("includes/footer.php"); ?>