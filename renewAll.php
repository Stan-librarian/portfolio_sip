
<?php
	// ATTENTION : renew all n'est pas géré par Portoflio pour l'instant (cf ticket https://intime.bibliomondo.com/issues/88477)
	include('sip2.class.php') ; // source : https://cap60552.github.io/php-sip2/
	include('functions.php') ; 
	// Récupération des valeurs envoyées par pret.php :
	if (isset($_GET['patron']) && $_GET['patron'] != '') {
		$patron = $_GET['patron'] ;
	}
?>
<html Content-Type: text/html; charset=UTF-8>
	<head>
		<title>SIP en PHP - Prolongation globale</title>
		<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
		<link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
	</head>
	<body>
		<?php
			include_once 'header.txt' ;
			include_once 'menu.txt' ;
		?>
		<hr />
		<h2>Prolongation globale</h2>
		<?php
			$myIniFile = parse_ini_file ("config.ini", TRUE);
			$url = $myIniFile["host"]["url"] ;
			$port = $myIniFile["host"]["port"] ;
			
			echo 'Usager : ' . $patron . '<br />' ;
			echo 'Serveur : ' . $url  . '<br /><hr />' ;

			$mysip = new sip2;
			$mysip->hostname = $url ;
			$mysip->port = $port ;
			//
			$mysip->patron = $patron ;
			$mysip->patronpwd = '';
			//
			$result = $mysip->connect();
			
			$query = $mysip->msgRenewAll('Y') ;
			// sip2.class.php -> function msgRenewAll($fee = 'N') 
			
			echo '<b>Requête :</b> '. $query . '<br />' ;
			// -> Requête : 65AOJL|AATEST04|BOY|AY0AZF886 
			// après modification de sip2.class.php (ajout de $this->_addFixedOption($this->_datestamp(), 18); dans la fonction msgRenewAll) :
			// -> Requête : 6520171201   134902AOJL|AATEST04|BOY|AY0AZF545 
			
			$result = $mysip->get_message($query) ;
			echo '<b>Résultat :</b> ' . $result ;
			/*
			if(substr($result, 0, 3) == '661'){
				$nb_prolongations = substr($result, 1, 4);
				$nb_refus = substr($result, 5, 4);
				echo $nb_prolongations . ' documents prolongés et ' . $nb_refus . ' documents non prolongés' ;
				$prolongations = explode('|BM', $result);
				$refus = explode('|BN', $result);
				unset($prolongations[0]) ;
				unset($refus[0]) ;
				if(count($prolongations) != 0){
					echo 'Titres prolongés :<br />' ;
					foreach($prolongations as $value){
						echo $value . '<br />' ;
					}
				}
				if(count($refus) != 0){
					echo 'Titres non prolongés :<br />' ;
					foreach($refus as $value){
						echo $value . '<br />' ;
					}
				}
			}
			else{
				$refus = explode('|AF', $result)[1];
				$refus = explode('|', $refus)[0] ;
				echo '<br /><b>→ Prolongation refusée</b><br />→ Message du serveur : <i>' . $refus . '</i>' ;
			}
			//
			*/
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

	