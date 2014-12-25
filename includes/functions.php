<?php

function Statistiques($bdd, $valeur = 1) {
    $dateJ = date("d/m/y");
    $stmt = $bdd->prepare('SELECT * FROM statistiques WHERE id_date=:id');
    $stmt->execute(array(
        "id" => $dateJ
    ));
    if (FALSE != $stmt->fetch()) {
        $req = $bdd->prepare('UPDATE statistiques SET vues = vues + :valeur WHERE id_date = :id');
        $req->execute(array("id" => $dateJ, "valeur" => $valeur));
    } else {
        $req = $bdd->prepare('INSERT INTO statistiques(id_date) VALUES(:id)');
        $req->execute(array('id' => $dateJ));
    }
    $stmt->closeCursor();
}

function MoisReduit($mois) {
    switch ($mois) {
        case 01: return"Jan";
            break;
        case 02: return"Fév";
            break;
        case 03: return"Mar";
            break;
        case 04: return"Avr";
            break;
        case 05: return"Mai";
            break;
        case 06: return"Jui";
            break;
        case 07: return"Jul";
            break;
        case 08: return"Aoû";
            break;
        case 09: return"Sep";
            break;
        case 10: return"Oct";
            break;
        case 11: return"Nov";
            break;
        case 12: return"Déc";
            break;
    }
}

function MoisComplet($mois) {
    switch ($mois) {
        case 01: return"Janvier";
            break;
        case 02: return"Février";
            break;
        case 03: return"Mars";
            break;
        case 04: return"Avril";
            break;
        case 05: return"Mai";
            break;
        case 06: return"Juin";
            break;
        case 07: return"Juillet";
            break;
        case 08: return"Août";
            break;
        case 09: return"Septembre";
            break;
        case 10: return"Octobre";
            break;
        case 11: return"Novembre";
            break;
        case 12: return"Décembre";
            break;
    }
}

function TypeVisible() {
    return array("1" => "Supprimé", "0" => "En ligne");
}

function AffPage($bdd, $id) {

    $stmt = $bdd->prepare('SELECT * FROM pages WHERE id_page =:id');
    $stmt->execute(array("id" => $id));
    $donnees = $stmt->fetch();
    $titre = $donnees["titre"];
    $contenu = $donnees["text"];
    $visible = $donnees["visible"];
    $type = $donnees["type"];
    $stmt->closeCursor();
    if ($visible == 0) {
        $retour = '<h2 class="page-heading"><span>' . $titre . '</span></h2>' . $contenu;
        return $retour;
    } elseif ($visible == 1) {
        $retour = '<h2 class="page-heading"><span>Erreur</span></h2>La page que vous recherchez n\'pas pas pu être trouvée, veuillez ré-essayer ou contacter un administrateur pour résoudre le problème.';
        $titre_page = "";
    }
}

function TitrePage($bdd, $page = "0", $titre = "", $news = "0") {
    if ($page != "0") {
        $stmt = $bdd->prepare('SELECT titre FROM pages WHERE id_page =:id');
        $stmt->execute(array("id" => $page));
        $donnees = $stmt->fetch();

        $titre = $donnees["titre"];

        $stmt->closeCursor();
        return $titre;
    } elseif ($titre != "") {
        return $titre;
    } elseif ($news != "0") {
        $stmt = $bdd->prepare('SELECT titre FROM news WHERE id_news =:id');
        $stmt->execute(array("id" => $news));
        $donnees = $stmt->fetch();

        $titre = $donnees["titre"];

        $stmt->closeCursor();
        return $titre;
    }
}

function Visible($bdd, $nom, $id) {
    //Si visible = 1 on supprime
    //Si visible != 1 on met visible à 1
    $stmt = $bdd->prepare('SELECT * FROM ' . $nom . ' WHERE id_' . $nom . ' = :id');
    $stmt->execute(array(
        'id' => $id
    ));
    $donnees = $stmt->fetch();

    $visible = $donnees["visible"];

    $stmt->closeCursor();
    if ($visible != "1") {
        $stmt = $bdd->prepare('UPDATE ' . $nom . ' SET visible = "1" WHERE id_' . $nom . ' = :id');
        $stmt->execute(array(
            "id" => $id
        ));
        $stmt->closeCursor();
    } else {
        $count = $bdd->exec("DELETE FROM " . $nom . " WHERE id_" . $nom . " = " . $id . "");
    }
}

function VerifAgenda($bdd) {
    $stmt = $bdd->prepare('SELECT * FROM calendrier');
    $stmt->execute();
    while ($donnees = $stmt->fetch()) {
        $event_id = $donnees["id_calendrier"];

        $date_event = $donnees["date_calendrier"];
        $jour_event = substr($date_event, 0, 2);
        $mois_event = substr($date_event, 3, 2);
        $annee_event = substr($date_event, 6, 4);
        $date_event = $annee_event . $mois_event . $jour_event;

        $date = date("Ymd");
        if ($date_event < $date) {
            $bdd->exec('DELETE FROM calendrier WHERE id_calendrier =' . $event_id . ' ');
        }
    }
}

function Adresse($bdd, $id_user) {
    //include("../includes/class.verif.php");

    if (isset($_POST["ajouter_adresse"])) {
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
                            echo "ok !";
                        } else
                            return $verif_pays;
                    } else
                        return $verif_localite;
                } else
                    return $verif_cp;
            } else
                return $verif_num;
        } else
            return $verif_rue;
    }
    ?>
    <article class="module width_full">
        <header><h3>Formulaire de demande d'ajout</h3></header>
        <div class="module_content">
            <form name="ajouter_adresse" id="ajouter_adresse" action="#" method="post">
                <fieldset style="width:73%; float:left; margin-right: 3%;">
                    <label for="rue">Rue</label>
                    <input type="text" name="rue" id="rue" value="" style="width:92%;"/>
                </fieldset>
                <fieldset style="width:23%; float:left;">
                    <label for="numero">Numéro</label>
                    <input type="text" name="numero" id="numero" value="" style="width:92%;"/>
                </fieldset><div class="clear"></div>

                <fieldset style="width:73%; float:left;margin-right: 3%;">
                    <label for="localite">Localité</label>
                    <input type="text" name="localite" id="localite" value="" style="width:92%;"/>
                </fieldset>
                <fieldset style="width:23%; float:left; ">
                    <label for="cp">Code postal</label>
                    <input type="text" name="cp" id="cp" value="" style="width:92%;"/>
                </fieldset><div class="clear"></div>

                <fieldset style="width:48%; float:left; margin-right: 3%;">
                    <label for="pays">Pays</label>
                    <input type="text" name="pays" id="pays" value="" />
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
                <div class="clear"></div>    
        </div>
        <div class="clear"></div>
        <footer>
            <div class="submit_link">
                <input type="submit" name="ajouter_adresse" id="ajouter_adresse" value="Ajouter l'adresse" class="alt_btn">
                <input type="reset" value="Reset">
                </form>
            </div>
        </footer>
    </article><!-- end of messages article -->
    <?php
}

function tronque($chaine, $longueur = 300) {

    if (empty($chaine)) {
        return "";
    } elseif (strlen($chaine) < $longueur) {
        return $chaine;
    } elseif (preg_match("/(.{1,$longueur})\s/ms", $chaine, $match)) {
        return $match [1] . "...";
    } else {
        return substr($chaine, 0, $longueur) . "...";
    }
}
?>