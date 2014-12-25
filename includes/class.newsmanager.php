<?php

//Utilisé pour aller chercher les paramètres des news dans la db
function Initialisateur_news($bdd) {
    $stmt = $bdd->prepare('SELECT * FROM news_parametres WHERE id=1');
    $stmt->execute();
    $donnees = $stmt->fetch();
    $retour = $donnees["nb_news_par_page"] . "." . $donnees["nb_news_aff_complet"];
    $stmt->closeCursor();
    return $retour;  // Exploitation : $params= explode(".",$params); echo $params[0] . " " . $params[1];
}

function Lister_news($complet = 0, $total, $bdd, $type = "") {
    //On récupère les paramètres des news
    $aff_total = "";
    if ($complet == 0 && $total == 0) {
        $params = Initialisateur_news($bdd);
        $params = explode(".", $params);
        $nb_aff_complet = $params[1];
        $aff_total = $params[0];
    } else {
        $nb_aff_complet = $complet;
        $aff_total = $total;
    }
    //On fait la query
    $stmt = $bdd->prepare('SELECT * FROM news WHERE visible = "1" ORDER BY id_news DESC LIMIT 0,' . $aff_total);
    $stmt->execute();
    while ($donnees = $stmt->fetch()) {
        $news = array(
            "titre" => $donnees["titre"], "text" => $donnees["text"], "auteur" => $donnees["auteur"], "date_post" => $donnees["date_post"],
            "premier_titre" => $donnees["premier_titre"], "nb_vues" => $donnees["nb_vues"], "categorie" => $donnees["categorie"], "id" => $donnees["id_news"]
        );
        if ($nb_aff_complet > 0)
            AffichageComplet($news, $bdd);
        else
            AffichageReduit($news, $type);
        $nb_aff_complet--;
    }
    $stmt->closeCursor();
}

function AffichageComplet($news, $bdd) {
    $date_news = explode("-", $news["date_post"]);
    $mois = MoisReduit($date_news[1]);
    echo'
	<article class="format-standard">
		<div class="entry-date"><div class="number">' . substr($date_news[2], 0, 2) . '</div> <div class="year">' . $mois . ' ' . $date_news[0] . '</div></div>
		
		<h2  class="post-heading"><a href="single.php?id=' . $news["id"] . '">' . $news["titre"] . '</a></h2>
		<div class="excerpt">
			' . $news["text"] . '
		</div>
		<div class="meta">
			<div class="categories">Dans <a href="news.php?cat=' . $news["categorie"] . '">' . $news["categorie"] . '</a></div>
			<div class="user">Par ' . $news["auteur"] . '</div>
		</div>
	</article>';
}

function AffichageReduit($news) {

    $date_news = explode("-", $news["date_post"]);
    $mois = MoisReduit($date_news[1]);
    
    $texte = str_replace("<p>", "", $news["text"]);
    $texte = str_replace("</p>", "", $texte);
    echo '<li><div class="date">
                         <span class="day">' . substr($date_news[2], 0, 2) . '</span>
				         <span class="month">' . $mois . '</span>
			         </div>
			         <h4><a href="single.php?id=' . $news["id"] . '">' . $news["titre"] . '</a></h4>
			         <p>' . tronque(strip_tags($texte), 100) . ' <a href="single.php?id=' . $news["id"] . '" class="read-more">En savoir + <i class="icon-angle-right"></p></i></a></li>';
}

?>