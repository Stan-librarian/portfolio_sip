<html Content-Type: text/html; charset=UTF-8>
	<head>
		<title>SIP en PHP - Retour</title>
		<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
		<link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
	</head>
	<body>
		<?php
			include_once 'header.txt' ;
			include_once 'menu.txt' ;
		?>
		<hr />
		<h2>Retour d'un document</h2>
		<form method="post" action="checkin.php">
			<label for="item">CB document : </label>
			<input type="text" name="item" placeholder="XXXXXXX" autofocus />  <input type="submit" value="Enregistrer le retour">
			<br />
			<label for="location">Localisation de retour : </label>
			<select name="location"  size="1" required >
				<?php
					$myIniFile = parse_ini_file ("config.ini", TRUE);
					$locations = $myIniFile["host"]["location"] ;
					foreach($locations as $value){
						if($value == 'JL'){
							echo '<option value="'.$value.'" selected>'.$value.'</option>' ;
						}
						else{
							echo '<option value="'.$value.'">'.$value.'</option>' ;
						}
					}
				?>
			</select>
			<br />
		</form>
		<?php
			include_once 'footer.txt' ;
		?>
	</body>
</html>