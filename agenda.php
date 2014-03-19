<?php
include("includes/db_connect.php");
include("includes/functions.php");
$titre_page=TitrePage($bdd,9);
$page = AffPage($bdd,9); // + Titre de la page
include("includes/head.php");
include("includes/menu.php");
?>
<div class="gris_clair">
     <div class="row-fluid corps">
         <div class="span12">
               <?php echo $page; ?>
                    <br/>
                    <link rel='stylesheet' type='text/css' href='js/fullcalendar/fullcalendar.css' />
<link rel='stylesheet' type='text/css' href='js/fullcalendar/fullcalendar.print.css' media='print' />
<script type='text/javascript' src='js/jquery-1.8.1.min.js'></script>
<script type='text/javascript' src='js/jquery-ui-1.8.23.custom.min.js'></script>
<script type='text/javascript' src='js/fullcalendar/fullcalendar.min.js'></script>
                	

                
                <div id="calendar"></div>

          </div>
      </div>
  </div>
  
  <?php 
  include("includes/footer.php");?>
  
<?php
function ChangeDate($date){
	$jour=substr($date,0,2);
	$mois=(substr($date,3,2))-1;
	$annee=substr($date,6,4);
	
	return $annee.", ".$mois.", ".$jour;
}
function ChangeHeure($heure){
	$h=substr($heure,0,2);
	$m=substr($heure,3,2);
	return $h.", ".$m;
}
$list_event="";
$stmt = $bdd->prepare('SELECT * FROM calendrier');
$stmt->execute();
while($donnees=$stmt->fetch())
{
    if($list_event!="")
	    $list_event.=",";
    $list_event.= '{
    title: \''.$donnees["titre_calendrier"].'\',
    start: new Date('.ChangeDate($donnees["date_calendrier"]).')
    }';
}
$stmt->closeCursor();
?>
  <script type='text/javascript'>

	$(document).ready(function() {
	
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		$('#calendar').fullCalendar({
			editable: false,
			events: [
				<?=$list_event?>
			]
		});
		
	});
</script>
  <?php include("includes/pied.php");
   ?>