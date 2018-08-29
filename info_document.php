<html Content-Type: text/html; charset=UTF-8>
	<head>
		<title>SIP en PHP</title>
		<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
		<link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
	</head>
	<body
		<?php
			include_once 'header.txt' ;
			include_once 'menu.txt' ;
		?>
		<hr />
		<h2>Informations sur un document</h2>
		<form method="post" action="item_information.php">
			<label for="item">CB document : </label>
			<input type="text" name="item" placeholder="XXXXXXX" autofocus />
			<br />
			<input type="submit" value="Récupérer les informations">
		</form>
		<?php
			include_once 'footer.txt' ;
		?>
	</body>
</html>

	