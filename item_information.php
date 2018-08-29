<?php
	include('sip2.class.php') ; // source : https://cap60552.github.io/php-sip2/
	include('functions.php') ; 
	if (isset($_POST['item']) && $_POST['item'] != '') {
		$item = $_POST['item'] ;
	}
?>
<html Content-Type: text/html; charset=UTF-8>
	<head>
		<title>SIP en PHP</title>
		<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
		<link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
		<script src="../scripts/sorttable.js"></script>
	</head>
	<body>
		<?php
			include_once 'header.txt' ;
			include_once 'menu.txt' ;
		?>
		<hr />
		<h2>Informations sur un document</h2>
		<?php
			$itemInfo = getItemInfo($item);
			echo '<table class="sortable">' ;
			echo '<thead>' ;
			echo '<tr><th>CB</th><th>Titre</th><th>Cote</th><th>Date de retour</th>' ;
			echo '</thead>' ;
			echo '<tbody>' ;
			echo '<tr><td>' .  $item . '</td><td>' . $itemInfo['title'] . '</td><td>' . $itemInfo['call'] . '</td><td>' . $itemInfo['returnDate'] . '</td></tr>' ;
			echo '</tbody>' ;
			echo '</table>' ;
			unset($item, $itemInfo) ;
		?> 
		<a href="info_document.php">Autre document</a>
		<?php
			include_once 'footer.txt' ;
		?>
	</body>
</html>

	