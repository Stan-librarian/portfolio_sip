<html Content-Type: text/html; charset=UTF-8>
	<head>
		<title>SIP en PHP - Prolongation</title>
		<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
		<link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
	</head>
	<body>
		<?php
			include_once 'header.txt' ;
			include_once 'menu.txt' ;
		?>
		<hr />
		<h2>Prolongation</h2>
		<form method="post" action="patron_information.php">
			<label for="patron">CB usager : </label>
			<input type="text" name="patron" placeholder="EXXXXXX" autofocus />
			<input type="submit" value="Voir les prêts" />
		</form>
		<?php
			include_once 'footer.txt' ;
		?>
	</body>
</html>