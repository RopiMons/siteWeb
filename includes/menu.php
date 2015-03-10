</head>

<body>
    <div class="container wrapper">
        <header class="gris">
            <div class="row-fluid">
                <div class="span8 logo">
                    <a href="index.php" title="accueil"><img src="img/logo.png" style="width: 125px"></a>
                </div>
                <div class="span4">
                <?php $i=0; $stmt = $bdd->prepare('SELECT idcommerce FROM commerce');
$stmt->execute();
while($donnees=$stmt->fetch())
{
	$i++;
}
$stmt->closeCursor(); 
$stmt = $bdd->prepare('SELECT ropi_circulation FROM ropi');
$stmt->execute();
while($donnees=$stmt->fetch())
{
	$ropi_circulation = $donnees["ropi_circulation"];
}
$stmt->closeCursor();?>
                    <h5>Quelques chiffres</h5>
                    <b class="btn"><?=$i?></b> commerçants adhérants au Ropi.<br/>
                    <b class="btn"><?=$ropi_circulation?></b> Ropis en circulation.
                </div>
            </div>
            <div class="navbar">
                <div class="navbar-inner">
                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <?php $page_actuelle = $_SERVER['SCRIPT_NAME'];$rooth="/ropi_ok/";?>
                  <div class="nav-collapse collapse">
                        <ul class="nav">
                        <?php echo ($page_actuelle==$rooth."index.php" || $page_actuelle==$rooth."")?'<li class="active">' : '<li>';?><a href="index.php" title="accueil">Accueil</a></li>
                        <?php echo ($page_actuelle==$rooth."pourquoi-adherer.php" || 
							$page_actuelle==$rooth."en-pratique.php" ||
							$page_actuelle==$rooth."charte.php" ||
							$page_actuelle==$rooth."l-equipe.php" ||
							$page_actuelle==$rooth."nous-aider.php" 
							)?'<li class="active dropdown">' : '<li class="dropdown">';?>
                            
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="le ropi">Le ropi <b class="caret"></b></a>
                                <ul class="dropdown-menu">                                   
                                    <li><a href="en-pratique.php" title="En pratique">En pratique</a></li>
                                    <li><a href="charte.php" title="La charte">La charte</a></li>
                                    <li><a href="l-equipe.php" title="L'équipe">L'équipe</a></li>
									<li><a href="pourquoi-adherer.php" title="Adhére à l'asbl ?">Adhérer à l'asbl </a></li>
                                    <li><a href="nous-aider.php" title="Nous aider">Nous aider</a></li>
                                </ul>
                            </li>
                            <?php echo ($page_actuelle==$rooth."presse.php")?'<li class="active">' : '<li>';?>
                            <a href="presse.php" title="Coin presse">Coin presse</a></li>
                            <?php echo ($page_actuelle==$rooth."agenda.php")?'<li class="active">' : '<li>';?>
                            <a href="agenda.php" title="Agenda">Agenda</a></li>
                            <?php echo ($page_actuelle==$rooth."contact.php")?'<li class="active">' : '<li>';?>
                            <a href="contact.php" title="Contact">Contact</a></li>
                            <?php echo ($page_actuelle==$rooth."commerces.php")?'<li class="active">' : '<li>';?>
                            <a href="commerces.php" title="Commerces">COMMERCES</a></li>
                            
                            <?php echo ($page_actuelle==$rooth."outils-de-comm.php" || 
							$page_actuelle==$rooth."en-pratique.php" ||
							$page_actuelle==$rooth."charte.php" ||
							$page_actuelle==$rooth."conventions.php" ||
							$page_actuelle==$rooth."comptes-rendus.php" || 
							$page_actuelle==$rooth."comptabilite.php" ||
							$page_actuelle==$rooth."status-asbl.php"
							)?'<li class="active dropdown">' : '<li class="dropdown">';?>
                            
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="le ropi">Doc' <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="outils-de-comm.php" title="Outils de communication">Outils de communication</a></li>
                                    <li><a href="en-pratique.php" title="En pratique">En pratique</a></li>
                                    <li><a href="charte.php" title="La charte">La charte</a></li>
                                    <li><a href="conventions.php" title="Conventions">Conventions</a></li>
                                    <li><a href="comptes-rendus.php" title="Comptes rendus">Comptes rendus</a></li>
                                    <li><a href="comptabilite.php" title="Comptabilité">Comptabilité</a></li>
                                    <li><a href="status-asbl.php" title="Statut de l'ASBL">Statut de l'ASBL</a></li>
                                </ul>
                            </li>
                           
                        </ul>
                        <form class="navbar-form pull-right" method="post" action="commerces.php">
                            <input class="span2" type="search" placeholder="Chercher un commerce" name="commerce" id="commerce">
                            <button type="submit" class="btn"><i class="icon-search"></i></button>
                        </form>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </header>
