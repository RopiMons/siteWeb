<?php
include("includes/db_connect.php");
include("includes/functions.php");
$titre_page = TitrePage($bdd, "0", "");
include("includes/head.php");
include("includes/class.parametres.php");
?>
<style>
    .inner-item {
        text-align: center;
        img {
            margin: 0 auto;}}
        </style>
        <?php
        include("includes/menu.php");
        $logo = array();
        $id = array();
        $stmt1 = $bdd->prepare('SELECT * FROM commerce WHERE commercestatus<>0 ORDER BY RAND() LIMIT 4');
        $stmt1->execute();
        while ($donnees = $stmt1->fetch()) {
            if ($donnees["commercelogo"]) {
                array_push($logo, Parametres::getUploadLogoFolder() . $donnees['commercelogo']);
                array_push($id, $donnees['idcommerce']);
            }
        }
        $stmt1->closeCursor();

        function Image($url) {
            
            return "<img src='$url' style='max-height:130px' />";
        }

        $target = "";
        $slide = "";
        foreach($logo as $i => $log) {
            if ($i == 0) {
                $target.='<li data-target="#myCarousel" data-slide-to="' . $i . '" class="active"></li>';
                $slide.='<div class="active item text-center"><div class="inner-item"><a href=commerce.php?q=' . $id[$i] . ' title="voir le commerce">' . Image($log) . '</a></div></div>';
            } else {
                $target.='<li data-target="#myCarousel" data-slide-to="' . $i . '"></li>';
                $slide.='<div class="item text-center"><div class="inner-item"><a href=commerce.php?q=' . $id[$i] . ' title="voir le commerce">' . Image($log) . '</a></div></div>';
            }
        }
        ?>
        <div class="gris_clair">
    <br/>
    <div class="row-fluid">

        <div class="span4 slider " style="max-height:100px;">
            <h4>Quelques adhérants au ROPI</h4>
            <div id="myCarousel" class="carousel slide">
                <ol class="carousel-indicators">
                    <?= $target ?>
                </ol>
                <!-- Carousel items -->
                <div class="carousel-inner">
                    <?= $slide ?>
                </div>
                <!-- Carousel nav -->
                <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
                <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
            </div><!-- FIN CAROUSEL -->
        </div>
        <div class="span4 embed-box">
            <?php
            $stmt = $bdd->prepare('SELECT * FROM pages WHERE id_page =:id');
            $stmt->execute(array("id" => "17"));
            $donnees = $stmt->fetch();
            echo $donnees["text"];
            $stmt->closeCursor();
            ?>
        </div>
        <div class="span4"><br /><br />
            <p class=""><a href="carte.php" title="Voir la carte" class="btn btn-info btn-large">Voir la carte des commerçants <i class="icon-map-marker icon-white"></i></a></p>
        </div>
    </div>

    <div class="row-fluid corps">
        <div class="span6">
            <ul class="recent-blog">

                <?php Lister_news(0, 3, $bdd) ?>
            </ul>
        </div>
        <div class="span6 well">
            <?php
            $stmt = $bdd->prepare('SELECT * FROM pages WHERE id_page =:id');
            $stmt->execute(array("id" => "8"));
            $donnees = $stmt->fetch();
            echo $donnees["text"];
            $stmt->closeCursor();
            ?>

        </div>
    </div>
</div>
<?php
include("includes/footer.php");
?><script src="js/jquery.js"></script>
<script type="text/javascript">
    $('#myCarousel').carousel({
        interval: 7000
    })
</script><?php
include("includes/pied.php");
?>