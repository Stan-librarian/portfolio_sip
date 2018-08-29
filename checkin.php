<?php
	$myIniFile = parse_ini_file ("config.ini", TRUE);
	include('sip2.class.php') ; // source : https://cap60552.github.io/php-sip2/
	include('functions.php') ; 
	if (isset($_POST['item']) && $_POST['item'] != '') {
		$item = $_POST['item'] ;
	}
	$db = new SQLite3('sip.sqlite');			
	$today = date("Y-m-d");	
	$now = date("H-i-s") ;
	if (isset($_POST['location']) && $_POST['location'] != '') {
		$location = $_POST['location'] ;
	}
	else{
		$location = $myIniFile["host"]["sip_location"] ;
	}
?>
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
		<h2>Enregistrement du retour d'un document</h2>
		<?php
			
			$url = $myIniFile["host"]["url"] ;
			$port = $myIniFile["host"]["port"] ;
			$returnDate = date('Ymd    His');
			
			
			echo 'Document : ' . $item . '<br />' ;
			echo 'Localisation : ' . $location . '<br />' ;
			echo 'Serveur : ' . $url . '<br />' ;
			
			$mysip = new sip2;
			// Set host name
			$mysip->hostname = $url ;
			$mysip->port = $port ;
			
			// Identify a patron
			// $mysip->patron = 'TEST03';
			// $mysip->patronpwd = '';
			
			// connect to SIP server 
			$result = $mysip->connect();
			

			// function msgCheckin($item, $itmReturnDate, $itmLocation = '', $itmProp = '', $noBlock='N', $cancel = '') 
			$query = $mysip->msgCheckin($item, $returnDate, $location, '', 'N', '');
			echo '<b>Requête :</b> '. $query . '<br />' ;
			
			$result = $mysip->get_message($query) ;
			echo '<b>Résultat :</b> ' . $result ;
			
			if(substr($result, 0, 3) == '101'){
				echo '<br /><b>→ Retour enregistré</b>' ;
				$query = 'insert into retours values (\'' . $today . '\' , \'' . $now . '\' , \'' . str_pad($item, 14, '0', STR_PAD_LEFT) . '\', \'' . $location . '\')' ;
				// echo $query . '<br />' ;
				$results = $db->exec($query);
			}
			else{
				$refus = explode('|AF', $result)[1];
				$refus = explode('|', $refus)[0] ;
				echo '<br /><b>→ Retour refusé</b><br />→ Message du serveur : <i>' . $refus . '</i>' ;
			}
			
			$mysip->disconnect();
		?> 
		<?php
			include_once 'footer.txt' ;
		?>
	</body>
</html>

	