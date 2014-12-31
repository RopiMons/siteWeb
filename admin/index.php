<?php
session_start();
include("includes.php");

VerifConnection($bdd,$_SESSION,2);
$niveau = saveDB::getUserLevelBySession($bdd, $_SESSION);

$breadcrumbs='<a href="index.php">Index de l\'administration</a> <div class="breadcrumb_divider"></div> 
	<a class="current">Panneau de contrôle</a>';
include("includes/header.php");

$jour=array(); $vues=array(); $i=0; $auj=false; $hier=false;
$stmt = $bdd->prepare('SELECT * FROM statistiques ORDER BY id DESC LIMIT 0,7');
$stmt->execute();
while($donnees=$stmt->fetch())
{
	if($i==0) $auj=$donnees["vues"];
	elseif($i==1) $hier=$donnees["vues"];
	$jour[$i]=$donnees["id_date"];
	$vues[$i]=intval($donnees["vues"]);
	$i++;
}
$calcul=0; $i=0;
$stmt = $bdd->prepare('SELECT * FROM statistiques');
$stmt->execute();
while($donnees=$stmt->fetch())
{
	$calcul=$donnees["vues"]+$calcul;
	$i++;
}
$moyenne=$calcul/$i;
?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Jour', 'Vues'],  
          ['<?php echo $jour[6]?>',  <?php echo $vues[6]?>],
          ['<?php echo $jour[5]?>',  <?php echo $vues[5]?>],
          ['<?php echo $jour[4]?>',  <?php echo $vues[4]?>],
		  ['<?php echo $jour[3]?>',  <?php echo $vues[3]?>],
		  ['<?php echo $jour[2]?>',  <?php echo $vues[2]?>],
		  ['<?php echo $jour[1]?>',  <?php echo $vues[1]?>],
          ['<?php echo $jour[0]?>',  <?php echo $vues[0]?>]
        ]);

        var options = {
          title: 'Vues des 7 derniers jours',
          hAxis: {title: 'Jours', titleTextStyle: {color: 'red'}}
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>	
	
	
	<section id="main" class="column">	
        <?php
		if($niveau==9)
		{ 
		?>	
		<article class="module width_full">
			<header><h3>Stats</h3></header>
			<div class="module_content">
				<article class="stats_graph">
					<div id="chart_div" style="width: 900px; height: 200px;"></div>
				</article>
				
				<article class="stats_overview">
					<div class="overview_today">
						<p class="overview_day">Aujourd'hui</p>
						<p class="overview_count"><?php echo $auj?></p>
						<p class="overview_type">Vues</p>
					</div>
					<div class="overview_previous">
						<p class="overview_day">Hier</p>
						<p class="overview_count"><?php echo $hier?></p>
						<p class="overview_type">Vues</p>
						<p class="overview_count"><?php echo round($moyenne,2)?></p>
						<p class="overview_type">Moyenne globale</p>
					</div>
				</article>
				<div class="clear"></div>
			</div>
		</article><!-- end of stats article -->
        <?php
		}
		else
		{
			?>
        <article class="module width_full">
			<header><h3>Bienvenue</h3></header>
			<div class="module_content">
				<p>Bienvenue sur l'espace d'administration du Ropi.</p>
			</div>
		</article><!-- end of stats article -->
            <?php
		}
		?>
		
		
		<div class="clear"></div>
		
		
		<article class="module width_half">
			<header><h3>Macdeb.net : les news</h3></header>
				<div class="module_content">
					<h1>Les dernières news du <a href="http://www.macdeb.net/blog" target="_blank" title="blog">Blog</a></h1>
                    <p>Voici les derniers posts du blog de Macdeb.net. N'hésitez pas à consulter de occasionnellement nos articles.</p>

                    <?php require_once("includes/rsslib.php"); 
		echo RSS_Links("http://www.macdeb.net/blog/feed/", 5);?>
				</div>
		</article><!-- end of styles article -->
        <article class="module width_half">
			<header><h3>Autres</h3></header>
				<div class="module_content">
					<?php
					if($niveau==9)
	{
		echo "Modifier le nombre de Ropis en circulation";
		$verif_autorisation_mod=true;
		
		//Mise à jour des points ropi
		if(isset($_POST["mod_ropi"]))
		{
			$req = $bdd->prepare('UPDATE ropi SET 
				ropi_circulation = :nb_ropi 
				WHERE id=1');
			$req->execute(array(
				'nb_ropi' => $_POST["nb_ropi"]
			));
			$message='<h4 class="alert_success">Réussite - Le nombre de ropi en circulation a été modifié.</h4>';
			
			
		}
		$stmt = $bdd->prepare('SELECT ropi_circulation FROM ropi WHERE id=1');
				$stmt->execute();
				$donnees=$stmt->fetch();
				$ropi_circulation=$donnees["ropi_circulation"];
		?>
        <form name="mod_ropi" id="mod_ropi" action="#" method="post">   
                <fieldset>
					<label for="points">Ropis en circulation</label>
					<input type="text" id="nb_ropi" name="nb_ropi" value="<?php echo $ropi_circulation?>" />
				</fieldset>            
					    <input type="submit" name="mod_ropi" id="mod_ropi" value="Mettre à jour les ropis en circulation" class="alt_btn">
                        
				</form>
        <?php
		
	}
					?>
				</div>
		</article><!-- end of styles article -->
		<div class="spacer"></div>
	</section>

<?php include("includes/footer.php");?>