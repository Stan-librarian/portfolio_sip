<?php
	include('sip2.class.php') ; // source : https://cap60552.github.io/php-sip2/
	include('functions.php') ; 
	// Récupération des valeurs envoyées par pret.php :
	if (isset($_GET['item']) && $_GET['item'] != '') {
		$item = $_GET['item'] ;
	}
	if (isset($_GET['patron']) && $_GET['patron'] != '') {
		$patron = $_GET['patron'] ;
	}
	$loanDate = date('Ymd    His');
?>
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
		<?php
			$myIniFile = parse_ini_file ("config.ini", TRUE);
			$url = $myIniFile["host"]["url"] ;
			$port = $myIniFile["host"]["port"] ;
			
			echo 'Usager : ' . $patron . '<br />' ;
			echo 'Document : ' . $item . '<br />' ;
			echo 'Serveur : ' . $url  . '<br /><hr />' ;

			$mysip = new sip2;
			$mysip->hostname = $url ;
			$mysip->port = $port ;
			//
			$mysip->patron = $patron ;
			$mysip->patronpwd = '';
			//
			$result = $mysip->connect();
			// sip2.class.php > function msgCheckout($item, $nbDateDue ='', $scRenewal='N', $itmProp ='', $fee='N', $noBlock='N', $cancel='N') 
			// nb due date 	: vide
			// SC renewal policy : Y
			// itmprop : vide
			// fee : Y 
			// no block : Y
			// cancel : N
			$query = $mysip->msgRenew($item, '', '', '', 'Y', 'Y', 'Y');
			
			// sip2.class.php > function msgRenew($item = '', $title = '', $nbDateDue = '', $itmProp = '', $fee= 'N', $noBlock = 'N', $thirdParty = 'N') 
			
			echo '<b>Requête :</b> '. $query . '<br />' ;
			
			$result = $mysip->get_message($query) ;
			echo '<b>Résultat :</b> ' . $result ;
			
			if(substr($result, 0, 3) == '301'){
				$dateRetour = explode('|AH', $result)[1];
				$dateRetour = explode('|', $dateRetour)[0];
				echo '<br /><b>→ Prolongation enregistrée</b><br />→ Date de retour : <i>' . $dateRetour . '</i>' ;
			}
			else{
				$refus = explode('|AF', $result)[1];
				$refus = explode('|', $refus)[0] ;
				echo '<br /><b>→ Prolongation refusée</b><br />→ Message du serveur : <i>' . $refus . '</i>' ;
			}
			//
			$mysip->disconnect();
			unset($url, $port, $patron, $item, $location, $_POST, $result) ;
			
			// 11NN20171123    103806                  AOJL|AATEST03|AB429765|AC|BOY|BIN|AY0AZEDDC
			// 120NNN20171123    103806AOJL    |AATEST03|AB429765|AJHumanité provisoire : Nouvelles / Walter M. Miller Jr ; Trad. de l'américain par Daniel Riche|AH04/01/2018|AFVous avez atteint le maximum de prolongations (type de groupes documentaires).|AGRSF MIL|

		?> 
		<?php
			include_once 'footer.txt' ;
		?>
	</body>
</html>

	