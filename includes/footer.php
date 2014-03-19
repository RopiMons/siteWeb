 <footer class="gris">
            <div class="row-fluid ">
                <div class="span3">
                    <img src="img/logo.png" style="height:70px;"/>
                </div>
                <div class="span4">
                    <h4>Connexion à l'espace membre</h4>
                    <form name="connexion" action="connexion.php" method="post">
                        <div class="control-group">
    <label class="control-label" for="pseudo">Pseudo</label>
    <div class="controls">
      <input type="text" id="pseudo" placeholder="votre pseudo" name="pseudo" >
    </div>
  </div>
  <div class="control-group">
       <label class="control-label" for="password">Mot de passe</label>
       <div class="controls">
           <input type="password" id="password" placeholder="Password" name="password" >
       </div>
  </div>
  <div class="control-group">
    <div class="controls">
      <button type="submit" class="btn btn-info" value="Me connecter" name="Connexion" id="Connexion">Connexion</button> - <a href="connexion.php?inscription">Je n'ai pas encore de compte</a>
    </div>
  </div>
						
					</form>
                </div>
                <div class="span5">
                     <ul class="recent-blog">
                       <?php Lister_news(0,2,$bdd)?>
                    </ul>
                </div>
                <div class="span12">
                    <p>Copyright Ropi.be <span class="pull-right footer">Développé par <a target="_blank" href="http://macdeb.net">Macdeb.net</a></span></p>
                </div>
            </div>
        </footer>
    </div><!-- END CONTAINER-->