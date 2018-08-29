<html Content-Type: text/html; charset=UTF-8>
	<head>
		<title>SIP en PHP</title>
		<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
		<link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
	</head>
	<body>
		<?php
			include_once 'header.txt' ;
			include_once 'menu.txt' ;
		?>
		<hr />
		<h2>Info Usager</h2>
		<form method="post" action="patron_information.php">
			<label for="patron">CB usager : </label>
			<input type="text" name="patron" placeholder="EXXXXXX" autofocus />
			<br />
			<!--<label for="type">Type d'information : </label>
			<select name="type"  size="1" required >
				<option value="none">none</option>
				<option value="hold">réservations en cours</option>
				<option value="overdue">overdue</option>
				<option value="charged">prêts en cours</option>
				<option value="fine">fine</option>
				<option value="recall">recall</option>
				<option value="unavail">unavail</option>
			</select>
			<br />
			<!-- -->
			<input type="submit" value="Récupérer les informations">
		</form>
		<?php
			include_once 'footer.txt' ;
		?>
	</body>
</html>