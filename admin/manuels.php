<?php
session_start();
include("includes.php");

VerifConnection($bdd,$_SESSION,2);

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
          ['<?=$jour[6]?>',  <?=$vues[6]?>],
          ['<?=$jour[5]?>',  <?=$vues[5]?>],
          ['<?=$jour[4]?>',  <?=$vues[4]?>],
		  ['<?=$jour[3]?>',  <?=$vues[3]?>],
		  ['<?=$jour[2]?>',  <?=$vues[2]?>],
		  ['<?=$jour[1]?>',  <?=$vues[1]?>],
          ['<?=$jour[0]?>',  <?=$vues[0]?>]
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
		<article class="module width_full">
            <header><h3>Manuel d'utilisation</h3></header>
			<div class="module_content">
				<p>N'hésitez pas à consulter notre manuel d'utilisation en cas de problème. <a href="../manuel-ropi.pdf" target="_blank" title="Manuel d'utilisateur">Consulter le manuel</a></p>
                
                <?php
				if($_SESSION["niveau"]==9)
	            {
					echo '<p>Notre manuel d\'administration est également à votre disposition. <a href="../manuel-ropi-admin.pdf" target="_blank" title="Manuel d\'administration">Consulter le manuel</a></p>';
				}
				?>
			</div>		</article><!-- end of stats article -->
		<div class="spacer"></div>
	</section>

<?php include("includes/footer.php");?>