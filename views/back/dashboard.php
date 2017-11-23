<?php ob_start() ; ?>
<div class="page">
	<header>
		<img src="https://d30y9cdsu7xlg0.cloudfront.net/png/90442-200.png" class="logo">
		<p class="logo">SHEEP</p>
		<nav>
			<a href="/logout" class="href_nav">Déconexion</a>
			<a href="#his" class="href_nav">Historique</a>
			<a href="#" class="href_nav ajouForm" onclick="divVisible()">Ajouter une Dépense </a>
		</nav>
	</header>
    <div class="graphic">
    	<svg xml:lang="fr" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    		<?php foreach ($depenses as $depense) :
        		foreach ($total as $to) {$p = ($depense["SUM(price)"]/$to["SUM(price)"])*1000;}
        		$i++;
    		?>
    		<rect x="<?php echo $width; ?>" y="-300" width="50" height="<?php echo $p; ?>" transform="scale(1-1)" fill="<?php echo '#'.$color[$i] ?>">
    			<animate attributeName='height' attributeType='XML' fill='freeze' from='0' to='<?php echo $p; ?>' begin="0s" dur='0.8s'/>
    		</rect>
    		<?php $width += 55; ?>
    		<?php endforeach; ?>
		</svg>
    </div>
    <div class="tableau">
    	<table>
    		<tr>
    			<td class="tb">NOM</td>
    			<td class="tb">PRIX</td>
    			<td class="tb">DATE</td>
    			<td class="tb">PART</td>
    		</tr>
        	<tr>
        		<td class="tb"><?php foreach ($datas as $data) {echo htmlentities($data['GROUP_CONCAT(u.name)'])."<br>";} ?></td>
        		<td class="tb"><?php foreach ($datas as $data) {echo htmlentities($data['price'])."€"."<br>";} ?></td>
        		<td class="tb"><?php foreach ($datas as $data) {echo htmlentities($data['pay_date'])."<br>";} ?></td>
        		<td class="tb"><?php foreach ($datas as $data) {echo htmlentities($data['part'])."€"."<br>";} ?></td>
        	</tr>
        	<tr>
        		<td>
        			<?php 
	        			for ($i=1; $i <= $nbPage; $i++) { 
	        				if ($i==$cPage) {
	        					echo $i;
	        				}else{
	        					echo "<a href=\"admin?page=$i#his\">$i</a>";
	        				}
	        			}
	        		 ?>   
        		</td>
        	</tr>
    	</table>
    </div>
	<div class="formulaire" id="formVisibility">
		<h2>Ajouter une dépense</h2>
		<form action="/form" method="post">
			<input type="text" class="input_spend" name="title" value="" id="title" placeholder="Title" />
			<input type="number" class="input_spend" name="price" value="" id="price" min="0" placeholder="Price" />
			<input type="date" class="input_spend" name="pay_date" value="<?php echo date('Y-m-d') ?>" id="pay_date"/>
			<input type="button" class="input_spend" name="UserVisibility" value="SHOW USERS" id="UserVisibility" onclick="usersVisible();">
			<br><br>
			<input class="input_spend" type="submit" name="submit" value=":: Ajouter ::" />
			<div class="formUsers" id="formUsers">
				<h2>Utilisateur</h2>
				<div class="input_check" name="name" value="" id="name">
					<?php foreach ($userName as $name) :  ?>
				        <input type="checkbox" > <?php echo $name['name']."<br>" ;?>
					<?php endforeach; ?>
				</div>
				
			</div>
		</form>
	</div>
    <footer>
    	<a id="his"></a>
    	<br><br><br><br><br><br><br><br>
    	<br><br><br><br><br><br><br><br>
    	<br><br><br><br><br><br><br><br>
    	<br><br><br><br><br><br><br><br>
    	<br><br>    <br><br>    <br><br>
    </footer>
</div>
<?php $content = ob_get_clean() ; ?>

<?php include __DIR__ . '/../layouts/master.php' ?>