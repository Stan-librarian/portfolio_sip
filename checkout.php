<?php
	// Récupère les valeurs envoyées par pret.php et exécute la requête SIP checkout (11)
	//
	// Protocole SIP : 
	// 11<SC renewal policy><no block><transaction date><nb due date><institution id><patron identifier><item identifier><terminal password><patron password><item properties><fee acknowledged><cancel> 
	// Field          					ID                  Format         
	// SC renewal policy							1-char, fixed-length required field:  Y or N. 
	// --> If this field contains a 'Y ' then the SC has been configured  by the library staff to do renewals.  ‘N’ means the SC has been configured to not do renewals.  
	// --> NB : SC = 3M SelfCheck system
	// --> Y pour nous
	// no block											1-char, fixed-length required field:  Y or N. 
	// --> This field notifies the ACS that the article was already checked in or out while the ACS was not on-line.  When this field is Y, the ACS should not block this transaction because it has already been executed. The SC can perform transactions while the ACS is off-line. These transactions are stored and will be sent to the ACS when it comes back on-line.  
	// --> N pour nous
	// transaction date  								18-char, fixed-length required field:  YYYYMMDDZZZZHHMMSS.  The date and time that the patron checked out the item at the SC unit.
	// nb due date 										18-char, fixed-length required field:  YYYYMMDDZZZZHHMMSS 
	// --> This is the no block due date that articles were given during off-line (store and forward) operation. 
	// --> vide pour nous
	// institution id 					AO				variable-length required field
	// patron identifier			AA				variable-length required field 
	// item identifier				AB				variable-length required field 
	// terminal password		AC				variable-length required field 
	// item properties				CH				variable-length optional field 
	// --> vide pour nous
	// patron password			AD				variable-length optional field 
	// --> If this feature is not used by the ACS in the library then the field should be zero length if it is required in the command, and can be omitted entirely if the field is optional in the command
	// fee acknowledged		BO				1-char, optional field: Y or N 
	// --> Y pour nous
	// cancel							BI					1-char, optional field: Y or N 
	// --> N pour nous
	//
	// sip2.class.php > function msgCheckout($item, $nbDateDue ='', $scRenewal='N', $itmProp ='', $fee='N', $noBlock='N', $cancel='N') 
	// 
	// Exemple de requête 11 envoyée par l'automate :
	// 11YN20140408    105635                  AOSM|AAE221976|AB0697478|AC|CH|AD|BOY|BIN|AY6AZEBA0
	//
	// Exemple de requête générée par la classe :
	// 11YN20171118 170557 AOJL|AATEST03|AB00000000429765|AC|AD0000|BOY|BIN|AY2AZEA83 
	//
	include('sip2.class.php') ; // source : https://cap60552.github.io/php-sip2/
	include('functions.php') ; 
	$db = new SQLite3('sip.sqlite');		
	$today = date("Y-m-d");	
	$now = date("H-i-s") ;
	// Récupération des valeurs envoyées par pret.php :
	if (isset($_POST['item']) && $_POST['item'] != '') {
		$item = $_POST['item'] ;
	}
	if (isset($_POST['patron']) && $_POST['patron'] != '') {
		$patron = $_POST['patron'] ;
	}
	if (isset($_POST['location']) && $_POST['location'] != '') {
		$location = $_POST['location'] ;
	}
	$loanDate = date('Ymd    His');
	
	// Vérification que le prêt n'a pas déjà été enregistré :
	$query = 'SELECT count(*) FROM prets where date = \'' . $today . '\' and patron = \'' . str_pad($patron, 14, '0', STR_PAD_LEFT) . '\' and item = \'' . str_pad($item, 14, '0', STR_PAD_LEFT) . '\' ' ;
	echo $query . '<br />' ;
	$results = $db->query($query);
	while ($row = $results->fetchArray()) {
		$nb = $row[0]  ;
	}
	if ($nb != 0){
		echo '<script>alert("Ce document a déjà été prêté à cet usager aujourd\'hui !")</script>';
		echo '<script type="text/javascript"> 
			history.back(-1);
		</script>' ;
	}
?>
<html Content-Type: text/html; charset=UTF-8>
	<head>
		<title>SIP en PHP - Prêt</title>
		<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
		<link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
	</head>
	<body>
		<?php
			include_once 'header.txt' ;
			include_once 'menu.txt' ;
		?>
		<hr />
		<h2>Prêt d'un document à un usager</h2>
		<?php
			$myIniFile = parse_ini_file ("config.ini", TRUE);
			$url = $myIniFile["host"]["url"] ;
			$port = $myIniFile["host"]["port"] ;
			
			echo 'Usager : ' . $patron . '<br />' ;
			echo 'Document : ' . $item . '<br />' ;
			echo 'Localisation : ' . $location . '<br />' ;
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
			$query = $mysip->msgCheckout($item, '', 'Y', '', 'Y', 'Y', 'N');
			
			echo '<b>Requête :</b> '. $query . '<br />' ;
			
			$result = $mysip->get_message($query) ;
			echo '<b>Résultat :</b> ' . $result ;
			
			if(substr($result, 0, 3) == '121'){
				$dateRetour = explode('|AH', $result)[1];
				$dateRetour = explode('|', $dateRetour)[0];
				echo '<br /><b>→ Prêt enregistré</b><br />→ Date de retour : <i>' . $dateRetour . '</i>' ;
				$query = 'insert into prets values (\'' . $today . '\' , \'' . $now . '\' , \'' . str_pad($patron, 14, '0', STR_PAD_LEFT) . '\' , \'' . str_pad($item, 14, '0', STR_PAD_LEFT) . '\', \'' . $location . '\')' ;
				// echo $query . '<br />' ;
				$results = $db->exec($query);
			}
			else{
				$refus = explode('|AF', $result)[1];
				$refus = explode('|', $refus)[0] ;
				echo '<br /><b>→ Prêt refusé</b><br />→ Message du serveur : <i>' . $refus . '</i>' ;
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

	