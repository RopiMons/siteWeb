<!doctype html>
<html lang="fr">

<head>
	<meta charset="utf-8"/>
	<title>Ropi Administration</title>
	
	<link rel="stylesheet" href="css/layout.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/opa-icons.css" type="text/css" media="screen" />
	<!--[if lt IE 9]>
	<link rel="stylesheet" href="css/ie.css" type="text/css" media="screen" />
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<script src="js/jquery-1.5.2.min.js" type="text/javascript"></script>
	<script src="js/hideshow.js" type="text/javascript"></script>
	<script src="js/jquery.tablesorter.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/jquery.equalHeight.js"></script>
	<script type="text/javascript">
	$(document).ready(function() 
    	{ 
      	  $(".tablesorter").tablesorter(); 
   	 } 
	);
	$(document).ready(function() {

	//When page loads...
	$(".tab_content").hide(); //Hide all content
	$("ul.tabs li:first").addClass("active").show(); //Activate first tab
	$(".tab_content:first").show(); //Show first tab content

	//On Click Event
	$("ul.tabs li").click(function() {

		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content

		var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active ID content
		return false;
	});

});
    </script>
    <script type="text/javascript">
    $(function(){
        $('.column').equalHeight();
    });
</script>
                              
    <?php
	$page_actuelle = $_SERVER['SCRIPT_NAME'];
$rooth="/ropi_ok/admin/";
    if($page_actuelle==$rooth."commerce-gerer.php")
	{
	 ?>
    <script src="js/jquery-1.7.min.js"></script>
	<script src="js/upload.packed.js"></script>
    <style>
.uploadifyQueue
{
	width: 450px;
}
.uploadifyQueueItem a
{
	font-size: 12px;
	text-decoration: none;
	color: #fff !important;
}
.uploadifyQueueItem a:hover
{
	text-decoration: underline;
}
.uploadifyQueueItem:first-child
{
	margin-top: 0px;
}
.uploadifyQueueItem
{
	margin-top: 5px;
	padding: 5px;
	border: 1px solid #D6D6D6;
	background-color: #45515F;
}
.uploadedImage
{
	border: none;
	max-width: 438px;
}
.uploadedThumbnail
{
	border: none;
	max-width: 200px;
}
.afterUploadThumbnail
{
	display: block;
}
.cancel
{
	float: right;
	margin-left: 5px;
}
.uploadifyProgress
{
	background-color: #45515F;
	border-color: #808080 #C5C5C5 #C5C5C5 #808080;
	border-style: solid;
	border-width: 1px;
	margin-top: 10px;
	width: 100%;
}
.uploadifyProgressBar
{
	background-color: #869FB7;
	height: 3px;
	width: 1px;
}
.uploadButton
{
	width: 110px;
	margin-top: 10px;
}
.button_cancel
{
	width: 10px;
	height: 10px;
	background: transparent url("close.png") no-repeat scroll 0 0;
	border: none;
	cursor: pointer;
	padding: 0px;
	margin-bottom: 0px !important;
	margin-top: 4px !important;
	line-height: 1 !important;
}
/*--- misc ---*/
.uploadifyQueue:after
{
    font-size: 0px;
    content: ".";
    display: block;
    height: 0px;
    visibility: hidden;
    clear: both;
}
.imu_info
{
	display: none;
	clear: both;
	border: 1px solid #c8c8c8;
	background-color: #e2e2e2;
	-moz-border-radius: 10px;
	-moz-box-shadow: 3px 3px 20px #e2e2e2;
	-webkit-border-radius: 10px;
	-webkit-box-shadow: 3px 3px 20px #e2e2e2;
	border-radius: 10px;
	box-shadow: 3px 3px 20px #e2e2e2;
	padding: 10px;
	margin-bottom: 15px;
	font-size: 12px;
	text-align: center;
	line-height: 150%;
}
.imu_loader
{
	display: none;
	margin-left: 15px;
}
</style>
     <?php
	}

	?> 

</head>


<body>
<header id="header">
	<hgroup>
		<h1 class="site_title"><a href="index.php">Administration du site</a></h1>
		<h2 class="section_title">Panneau d'administration</h2><div class="btn_view_site"><a href="../index.php">Voir le site</a></div>
	</hgroup>
</header> <!-- end of header bar -->
<?php
if(isset($_SESSION["niveau"]))
{?>
	<section id="secondary_bar">
		<div class="user">
			<p><?=$_SESSION["username"]?></p>
			<a class="logout_user" href="deconnexion.php" title="Déconnexion">Déconnexion</a>
		</div>
		<div class="breadcrumbs_container">
			<article class="breadcrumbs"><?=$breadcrumbs?></article>
		</div>
	</section><!-- end of secondary bar -->
	
	<aside id="sidebar" class="column">

		<hr/>
        <h3>Menu</h3>
        <ul>
             <li class="icon-home icon-blue"> <i class="icon-home"></i><a href="index.php">Index </a></li>
             <li class="icn_profile"><a href="mon-compte.php">Mon profil</a></li>
             <li class="icn_settings"><a href="manuels.php">Manuels d'utilisation</a></li>
             <li class="icn_jump_back"><a href="deconnexion.php">Déconnexion</a></li>
        </ul>
        <?php if($_SESSION["niveau"]==9)
		{
			$c=0;
			$stmt = $bdd->prepare('SELECT * FROM commerce WHERE commercestatus=0');
			$stmt->execute();
			while($donnees=$stmt->fetch())
			{
				$c=$c+1;
			}
			$stmt->closeCursor(); 
			?>
		<h3>Gestion du contenu</h3>
		<ul>
       		
			<li class="icn_new_article"><a href="pages.php">Pages</a></li>
			<li class="icn_edit_article"><a href="news.php" title="News">News</a></li>
			<!--<li class="icn_tags"><a href="menus.php">Menus</a></li>-->
            <!--<li class="icn_photo"><a href="galerie.php">Galerie</a></li>-->
			<li class="icn_categories"><a href="calendrier.php">Calendrier</a></li>
		</ul>
		<h3>Utilisateurs</h3>
		<ul class="toggle">
			<li class="icn_add_user"><a href="users.php?add">Ajouter un utilisateur</a></li>
			<li class="icn_view_users"><a href="users.php">Gérer les utilisateurs</a></li>
            <li class="icn_edit_article"><a href="rediger-mail.php">Envoyer un mail</a></li>
		</ul>
         <h3>Gestion des commerces </h3>
		<ul class="toggle">
			<li class="icn_new_article"><a href="commerce-ajouter.php">Gérer les commerces en attente <strong>(<?=$c?>)</strong></a></li>
			<li class="icn_folder"><a href="commerce-gerer.php">Gérer les commerces</a></li>
			<li class="icn_tags"><a href="commerce-type.php">Gérer les types de commerce</a></li>
           
		</ul>
        <hr/>
        <?php
		}
		if($_SESSION["niveau"]>=3)
		{
		?>
        <h3>Commerce</h3>
		<ul class="toggle">
			<li class="icn_add_user"><a href="commerce.php">Gérer mon commerce</a></li>	
            <li class="icn_add_user"><a href="demander-commerce.php">demander un commerce ou article </a></li>
            <li class="icn_add_user"><a href="statistique-demandes.php">Statistique des demandes</a></li>	
            <li class="icn_add_user"><a href="parrainer.php">Parrainer un commerce</a></li>
            <li class="icn_add_user"><a href="tableau-parrainage.php">Le tableau des parrainages</a></li>		
		</ul>
        <?php
		}
		?>
		<!--<h3>Media</h3>
		<ul class="toggle">
			
			
			<li class="icn_audio"><a href="#">Audio</a></li>
			<li class="icn_video"><a href="#">Video</a></li>
		</ul>-->

<?php } ?>		
		<footer>
			<hr />
			<p><strong>Copyright &copy; 2012 Ropi</strong></p>
			<p>Interface d'administration créée par <a href="http://www.macdeb.net">Macdeb.net</a></p>
		</footer>
	</aside><!-- end of sidebar -->