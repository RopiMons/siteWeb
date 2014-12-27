<?php

/**
 * La fonction VerifConnection permet de vérifier si la connection à une page est autorisée.
 * @authors Macdeb & Laurent Cardon <laurent.cardon@ropi.be>
 * @param PDO $bdd Objet PDO utilisé pour la connection à la base de donnée
 * @param String $id Id de la personne demandant la connection
 * @param String $password Mot de passe de la personne demandant la connection (Mot de passe lié à $id de la personne)
 * @param Int $niveau Niveau d'accès de la personne liée à $id et $password 
 * @param Int $requis Niveau recquis pour l'accès à la page
 * @param Objet $ressource Paramètre optionnel qui de part sa présence autorise une personne à accèder à ses propres ressources 
 * @return null Redirection vers la page de connection en cas de non-autorisation.
 * 
 */
function VerifConnection(PDO $bdd, $id, $password, $niveau, $requis, $ressource = null) {
    $auth = $niveau - $requis;
    if ($auth < 0) {
        session_destroy;
        header("location:../connexion.php");
    }
    $stmt = $bdd->prepare('SELECT idpersonnes,personnespseudo,passwordpersonnes FROM personnes WHERE idpersonnes= :id AND passwordpersonnes= :pass');
    $stmt->execute(array(
        'id' => $_SESSION["id"],
        'pass' => $password
    ));
    $donnees = $stmt->fetch();

    if (!$donnees && isset($donnees["id"])) {
        header("location:erreur.php?msg=1");
    }
    $stmt->closeCursor();
}

function FormulaireInscription($pseudo = "") {
    $form = '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">
        Veuillez remplir ce formulaire pour vous inscrire:<br />
        <div class="center">
            <label for="pseudo">Nom d\'utilisateur</label><input type="text" name="pseudo" ' . ValueFormulaire($pseudo) . '/><br />
            <label for="password">Mot de passe</label><input type="password" name="password" /><br />
			<label for="password2">Confirmation</label><input type="passwor" name="password2" /><br />
            <label for="email">Email</label><input type="text" name="email"/><br />
            <input type="submit" value="Envoyer" name="envoi" />
                </div>
    </form>';
    return $form;
}

function Connexion($bdd, $pseudo, $mdp) {
    // Si une session existe déjà, on la détrui
    if (isset($_SESSION['id'])) {
        session_destroy();
        session_start();
    }
    $message = "";
    $stmt = $bdd->prepare('SELECT * FROM personnes WHERE personnespseudo= :username OR  mailpersonnes = :username');
    $stmt->execute(array(
        'username' => $pseudo
    ));
    $donnees = $stmt->fetch();
    if (md5($mdp) == $donnees["passwordpersonnes"]) {
        $_SESSION['username'] = $donnees['personnespseudo'];
        $_SESSION['password'] = $donnees["passwordpersonnes"];
        $_SESSION['id'] = $donnees['idpersonnes'];

        $niveau = getNiveau($donnees['idpersonnes'], $bdd);
        $_SESSION['niveau'] = $niveau;

        if ($niveau != 0) {
            header("location:admin/index.php");
        } else {
            $message = "Erreur : Votre compte n'a pas encore été activé par un administrateur.";
        }
    } else {
        $message = "Erreur : Le mot de passe entré n'est pas correct.";
    }
    $stmt->closeCursor();
    return($message == "") ? true : $message;
}

function CheckFormulaire($prenom, $nom, $pseudo, $mail, $password, $pwd2) {
    $verif_prenom = Verif($prenom, "prenom", 3, 16);
    $verif_nom = Verif($nom, "nom", 3, 32);
    $verif_pseudo = Verif($pseudo, "pseudo", 2, 32);
    $verif_mdp = Verif($password, "mot de passe", 5, 32, "", "", $pwd2);
    $verif_mail = Verif($mail, "adresse E-mail", 8, 48, "email");
    if ($verif_prenom == 1 && $verif_nom == 1 && $verif_pseudo == 1 && $verif_mdp == 1 && $verif_mail == 1) {
        return 1;
    } else {
        $retour = "";
        $retour.=($verif_prenom != 1) ? $verif_prenom : "";
        $retour.=($verif_nom != 1) ? $verif_nom : "";
        $retour.=($verif_pseudo != 1) ? $verif_pseudo : "";
        $retour.=($verif_mdp != 1) ? $verif_mdp : "";
        $retour.=($verif_mail != 1) ? $verif_mail : "";
        return '<div class="alert alert-error">Erreur :' . $retour . '</div>';
    }
}

/*
 * Fonctionnalitées ajoutée par Laurent C - laurent.cardon@ropi.be
 */

function getNom($bdd, $id) {
    $stmt = $bdd->prepare('SELECT prenompersonnes FROM personnes WHERE idpersonnes= :idpers');
    $stmt->execute(array(
        'idpers' => $id
    ));
    $donnees = $stmt->fetch();
    if ($donnees) {
        $retour = $donnees["prenompersonnes"];
    } else {
        $retour = false;
    }

    return $retour;
}

function getNiveau($idpersonne, $bdd) {
    $niveau = 0;
    $stmt2 = $bdd->prepare('SELECT * FROM typepersonnepersonne WHERE typeperonne_idpersonnes = :idpersonne');
    $stmt2->execute(array(
        'idpersonne' => $idpersonne
    ));
    while ($donnees2 = $stmt2->fetch()) {
        if ($donnees2["typepersonnepersonne_cataloguetypepersonne"] > $niveau) {
            $niveau = $donnees2["typepersonnepersonne_cataloguetypepersonne"];
        }
    }
    return $niveau;
}

function isNiveau($num, $idpersonne, $bdd) {
    $retour = false;
    $stmt2 = $bdd->prepare('SELECT * FROM typepersonnepersonne WHERE typeperonne_idpersonnes = :idpersonne');
    $stmt2->execute(array(
        'idpersonne' => $idpersonne
    ));
    while ($donnees2 = $stmt2->fetch()) {
        if ($donnees2["typepersonnepersonne_cataloguetypepersonne"] == $num) {
            $retour = true;
        }
    }
    return $retour;
}

/*
 * Fin des fonctionnalitées ajoutées par Laurent C - laurent.cardon@ropi.be
 */
?>