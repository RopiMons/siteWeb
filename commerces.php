<?php
include("includes/db_connect.php");
include("includes/class.recherche.php");
include("includes/functions.php");
$titre_page = TitrePage($bdd, "", "Les commerces adhérants au Ropi");
include("includes/head.php");
include("includes/menu.php");
$recherche = false;
if (isset($_POST["search"])) {
    $recherche = true;
    $type = $_POST["type"];
    $codepostal = $_POST["codepostal"];
    $commerce = $_POST["commerce"];
    $produit = $_POST["produit"];
    $rue = $_POST["rue"];
    
    $rechercheObj = new Recherche($type,$codepostal,$rue,$commerce,$produit);
    $commerces = $rechercheObj->getArray($bdd);
}
?>
<div class="gris_clair">
    <div class="row-fluid corps">
        <div class="span4">
            <h2>Recherche</h2>			

            <form name="search" action="#" method="post">
                <fieldset>
                    <label for="type">Type de commerce : </label> 
                    <select title="type" name="type" id="type">
                        <option value="0">Aucun</option>
<?php
$stmt = $bdd->prepare('SELECT * FROM cataloguetypecommerce');
$stmt->execute(array());
while ($donnees = $stmt->fetch()) {
    if (isset($_POST["type"])) {
        if ($_POST["type"] == $donnees["idcataloguetypecommerce"])
            echo "<option value='" . $donnees["idcataloguetypecommerce"] . "' selected>" . $donnees["cataloguetypecommercelabel"] . "</option>";
        else
            echo "<option value='" . $donnees["idcataloguetypecommerce"] . "'>" . $donnees["cataloguetypecommercelabel"] . "</option>";
    } else
        echo "<option value='" . $donnees["idcataloguetypecommerce"] . "'>" . $donnees["cataloguetypecommercelabel"] . "</option>";
}
$stmt->closeCursor();
?>
                    </select>
                    <br />
<?php
$produit = "";
if (isset($_POST["produit"]) != "")
    $produit = $_POST["produit"];
$commerce = "";
if (isset($_POST["commerce"]) != "")
    $commerce = $_POST["commerce"];
$rue = "";
if (isset($_POST["rue"]) != "")
    $rue = $_POST["rue"];

$liste_commerces = "";
$stmt = $bdd->prepare('SELECT commercenom FROM commerce WHERE commercestatus<>0');
$stmt->execute();
while ($donnees = $stmt->fetch()) {
    if ($liste_commerces != "")
        $liste_commerces.= ",";

    $liste_commerces.= '"' . $donnees["commercenom"] . '"';
}
$stmt->closeCursor();

$liste_articles = "";
$stmt = $bdd->prepare('SELECT produitnom  FROM commerceproduits');
$stmt->execute();
while ($donnees = $stmt->fetch()) {
    if ($liste_articles != "")
        $liste_articles.= ",";

    $liste_articles.= '"' . $donnees["produitnom"] . '"';
}
$stmt->closeCursor();

$liste_rues = "";
$stmt = $bdd->prepare('SELECT adressesrue FROM adresses WHERE adresses_catalogueadressesID = 3');
$stmt->execute();
while ($donnees = $stmt->fetch()) {
    if ($liste_rues != "")
        $liste_rues.= ",";

    $liste_rues.= '"' . str_replace("'", " ", $donnees["adressesrue"]) . '"';
}
$stmt->closeCursor();
?>
                    <label for="commerce">Nom du commerce : </label><input type="text" name="commerce" id="commerce" placeholder="Nom du commerce" value="<?php echo $commerce ?>" data-provide="typeahead" data-items="4" data-source='[<?php echo $liste_commerces ?>]' autocomplete="off"/>

                    <br />

                    <label for="produit">Nom d'un produit : </label><input type="text" name="produit" id="produit" placeholder="Nom d'un produit" value="<?php echo $produit ?>" data-provide="typeahead" data-items="4" data-source='[<?php echo $liste_articles ?>]' autocomplete="off"/>

                    <br />



                    <label for="produit">Nom de la rue : </label><input type="text" name="rue" id="rue" placeholder="rue" value="<?php echo $rue ?>" data-provide="typeahead" data-items="4" data-source='[<?php echo $liste_rues ?>]' autocomplete="off"/>

                    <br />
                    <label for="codepostal">Code postal : </label>
                    <select title="codepostal" name="codepostal" id="codepostal">
                        <option value="0">Aucun</option>
                    <?php
                    $cps = array();
                    $stmt = $bdd->prepare('SELECT adressescodepostal FROM adresses WHERE adresses_catalogueadressesID = 3');
                    $stmt->execute(array());
                    while ($donnees = $stmt->fetch()) {
                        $verif = true;
                        foreach ($cps as &$cp) {
                            if ($cp == $donnees["adressescodepostal"]) {
                                $verif = false;
                            }
                        }
                        if ($verif == true) {
                            array_push($cps, $donnees["adressescodepostal"]);
                            if (isset($_POST["codepostal"])) {
                                if ($_POST["codepostal"] == $donnees["adressescodepostal"])
                                    echo "<option value='" . $donnees["adressescodepostal"] . "' selected>" . $donnees["adressescodepostal"] . "</option>";
                                else
                                    echo "<option value='" . $donnees["adressescodepostal"] . "'>" . $donnees["adressescodepostal"] . "</option>";
                            } else
                                echo "<option value='" . $donnees["adressescodepostal"] . "'>" . $donnees["adressescodepostal"] . "</option>";
                        }
                    }
                    $stmt->closeCursor();
                    ?>
                    </select>

                    <input type="submit" class="btn btn-large btn-info btn-info" value="Chercher" name="search" id="search" />
                </fieldset>

            </form>  
        </div>

        <div class="span8">
                        <?php
                        if (isset($_GET["commerce"])) {
                            $stmt = $bdd->prepare('SELECT * FROM commerce WHERE idcommerce = :id_comm AND commercestatus<>0');
                            $stmt->execute(array("id_comm" => $_GET["commerce"]));
                            $donnees = $stmt->fetch();

                            //echo '<img src="'.$donnees["commerceimage"].'"/><br />';
                            echo "<br /><h1>" . $donnees["commercenom"] . "</h1><br /><br />";
                            echo tronque($donnees["commercecontenu"]);
                            echo '<br /><p><a href="commerces-afficher.php?id=' . $_GET["commerce"] . '">Voir la page du commerce</a></p>';
                            $stmt->closeCursor();
                            echo '<div class="clear"></div>';
                        } elseif ($recherche) {
                            ?>
                <div id="txtHint"></div>

                <h2>Résultat de votre recherche</h2>
                <table width="100%" class="table table-hover table-striped table-bordered">
                    <tr>
                    <thead><td>Nom du commerce</td><td>Type de commerce</td><td></td></thead>
                    <tbody>
                        </tr>
                            <?php
                            $i = 0;
                            foreach ($commerces as $commerce) {
                                $i++;
                                echo "<tr>";
                                echo '<td>' . $commerce["nom"]. '</td>';
                                echo '<td>' . $commerce["type"] . '</td>';
                                echo '<td><button class="btn btn-info" onClick="showUser(this.value)" value="' . $commerce["id"]. '">Voir le commerce</button></td>';
                                echo "</tr>";
                            }
                            ?>
                    </tbody>
                </table>
                <?php
                if ($i == 0) {
                    echo "<br /><strong>Aucun commerce ne correspond à vos critères de recherche</strong>";
                } else{
                    if($i>1){
                        $s = "s";
                        $ent = "ent";
                    }else{
                        $s = "";
                        $ent = "";
                    }
                    echo $i . " commerce$s correspond$ent à vos critères";
                }
            }
            else {
                echo "<h2>Aucune recherche effectuée</h2>";
                echo "<p>Effectuez une recherche en utilisant le formulaire.</p>";
            }
            ?>
        </div>
    </div>
</div>

                    <?php include("includes/footer.php"); ?>
<script src="js/jquery.js"></script>

<script type="text/javascript" src="ajax.js"></script>

                    <?php include("includes/pied.php");
                    ?>