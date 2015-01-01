<?php
session_start();
include("includes.php");
include("includes/class.form.php");

if (isset($_POST["Connexion"])) {
    $verif_pseudo = Verif($_POST["pseudo"], "pseudo", 2, 32);
    $verif_mdp = Verif($_POST["password"], "mot de passe", 5, 32);
    $champs = array($verif_pseudo, $verif_mdp);
    $test = ValideForm($champs);
    if ($test == true) {
        //echo "test == true";
        $message = Connexion($bdd, $_POST["pseudo"], $_POST["password"]);
    }
}

include("includes/functions.php");
$titre_page = TitrePage($bdd, "", "Connexion");
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        <title>Le ropi - <?php echo $titre_page ?></title>

        <!--[if lte IE 8]>
          <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]--> 


<?php
$message = "";
$test = "";


include("includes/menu.php");
?>
    <div class="gris_clair">
        <div class="row-fluid corps">
            <div class="span12">
        <?php
        if (isset($test) != true) {
            echo $test;
        }
        echo $message;
        //On vérifie l'existance des champs
        $pseudo = (isset($_POST["pseudo"]) != "") ? $_POST["pseudo"] : "";
        $password = (isset($_POST["password"]) != "") ? $_POST["password"] : "";

        if (!isset($_GET["inscription"])) {
            ?>
                    <form class="form-horizontal" name="connexion" action="#" method="post">
                        <h2 class="heading">Vous connecter </h2>
                        <p>Utilisez ce formulaire afin de vous connecter à votre compte.</p>

                        <div class="control-group">
                            <label class="control-label" for="pseudo">Pseudo</label>
                            <div class="controls">
                                <input type="text" id="pseudo" placeholder="votre pseudo" name="pseudo" >
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="password">Mot de passe</label>
                            <div class="controls">
                                <input type="password" id="password" placeholder="Password" name="password" >
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <button type="submit" class="btn btn-info" value="Me connecter" name="Connexion" id="Connexion">Connexion</button> - <a href="?inscription">Je n'ai pas encore de compte, je souhaite m'inscrire</a>
                            </div>
                        </div>

                    </form>
    <?php
} else {
    $prenom = (isset($_POST["prenom"]) != "") ? $_POST["prenom"] : "";
    $nom = (isset($_POST["nom"]) != "") ? $_POST["nom"] : "";
    $pseudo = (isset($_POST["pseudo"]) != "") ? $_POST["pseudo"] : "";
    $password = (isset($_POST["password"]) != "") ? $_POST["password"] : "";
    $confirmation = (isset($_POST["confirmation"]) != "") ? $_POST["confirmation"] : "";
    $mail = (isset($_POST["mail"]) != "") ? $_POST["mail"] : "";

    if (isset($_POST["adduser"])) {
        $message = CheckFormulaire($_POST["prenom"], $_POST["nom"], $_POST["pseudo"], $_POST["mail"], $_POST["pass"], $_POST["confirm"],array("pseudo"=>array(":val"=>$_POST["pseudo"],":id"=>0),"email"=>array(":val"=>$_POST["mail"],":id"=>0)),$bdd);
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
            //Niveau($bdd,$id,$_POST["options"]);
            $message = '<div class="alert alert-success"><b>Réussite</b>, Votre compte a été créé.</div>';

            //AJOUTE L'UTILISATEUR
        }
    }


    echo $message;
    ?>
                    <form class="form-horizontal" name="adduser" id="adduser" action="#" method="post">

                        <div class="control-group">
                            <label class="control-label" for="prenom">Prénom</label>
                            <div class="controls">
                                <input type="text" id="prenom" placeholder="Votre prénom" name="prenom" value="<?php echo $prenom ?>" >
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="nom">Nom</label>
                            <div class="controls">
                                <input type="text" id="nom" placeholder="Votre nom" name="nom" value="<?php echo $nom ?>" >
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="pseudo">Pseudo</label>
                            <div class="controls">
                                <input type="text" id="pseudo" placeholder="Votre pseudo" name="pseudo" value="<?php echo $pseudo ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="mail">Adresse E-mail</label>
                            <div class="controls">
                                <input type="text" id="mail" placeholder="Votre adresse E-mail" name="mail" value="<?php echo $mail ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="pass">Mot de passe</label>
                            <div class="controls">
                                <input type="password" id="pass" placeholder="Votre mot de passe" name="pass" >
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="confirm">Confirmation du mot de passe</label>
                            <div class="controls">
                                <input type="password" id="confirm" placeholder="Identique au mot de passe" name="confirm" >
                            </div>
                        </div>                  

                        <br />
                        <div class="form-actions">
                            <button type="submit" name="adduser" id="adduser" class="btn btn-info">Créer un compte</button>
                            - <a href="connexion.php">Connexion</a>

                        </div></form>

    <?php
}
?>
            </div>
        </div>
    </div>

<?php include("includes/footer.php"); ?>
    <script src="js/jquery.js"></script>
<?php include("includes/pied.php");
?>