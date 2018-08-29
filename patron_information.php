<?php
	include('sip2.class.php') ; // source : https://cap60552.github.io/php-sip2/
	include('functions.php') ; 
	if (isset($_POST['patron']) && $_POST['patron'] != '') {
		$patron = $_POST['patron'] ;
	}
?>
<html Content-Type: text/html; charset=UTF-8>
	<head>
		<title>SIP en PHP - Info Usager</title>
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
		<h2>Informations sur un usager</h2>
		<?php
			$myIniFile = parse_ini_file ("config.ini", TRUE);
			$url = $myIniFile["host"]["url"] ;
			$port = $myIniFile["host"]["port"] ;
			$mysip = new sip2;
			$mysip->hostname = $url ;
			$mysip->port = $port ;
			//
			// Identify a patron
			$mysip->patron = $patron ;
			$mysip->patronpwd = '';
			//
			// connect to SIP server 
			$result = $mysip->connect();
			//
			// login
			// $result = $mysip->msgLogin('', '') ;
			//
			echo '<p>Usager : ' . $mysip->patron . '<br />' ;
			echo 'Serveur : ' . $url . '</p>' ;
			// echo 'Type d\'information : ' . $type . '</p>' ;
			// valeurs possibles : none, hold, overdue, charged, fine, recall, unavail
			 /* 
			* According to the specification:
			* Only one category of items should be  requested at a time, i.e. it would take 6 of these messages, 
			* each with a different position set to Y, to get all the detailed information about a patron's items.
			*/
			// $array = array() ;
			// $total = '' ;
			// $values = array('charged', 'overdue', 'hold', 'none');
			// $values = array('charged');
			// unset($result) ;	
			// foreach ($values as &$value) {
				// echo $value . '<br />' ;
				// $query = $mysip->msgPatronInformation($value);
				// echo '<b>Requête :</b> ' . $query . '<br />' ;
				// $result = $mysip->get_message($query) ;
				// $debug =  $mysip->_debugmsg($query) ;
				// $array[] = $result ;
				// $total  .= $result ;
				// echo '<b>Résultat :</b> ' . $result . '<hr />' ;
				// echo $debug ;
				// sleep(5) ;
				// unset($result) ;	
			// }
			set_time_limit($myIniFile["host"]["timeout"]);
			$query = $mysip->msgPatronInformation('charged', 1, 99);
			$result = $mysip->get_message($query) ;
			$infos = getPatronInfo($patron);
			
			echo '<b>Requête :</b> ' . $query . '<br />' ;
			echo '<b>Résultat :</b> ' . $result . '<br />' ;	
			echo '<hr />' ;
			echo '<b>Nom :</b> ' . $infos['name'] . '<br />' ;	
			echo '<b>Catégorie :</b> ' . $infos['category'] . '<br />' ;	
			echo '<b>Adresse :</b> ' . $infos['address'] . '<br />' ;	
			echo '<b>Email :</b> ' . $infos['email'] . '<br />' ;	
			echo '<b>Téléphone :</b> ' . $infos['phone'] . '<br />' ;	
			echo '<b>Date de naissance :</b> ' . $infos['birthDate'] . '<br />' ;	
			echo '<b>Note :</b> ' . $infos['alert'] . '<br />' ;	
			$loans_number = (int)substr($result, 45, 4) ;
			$lates_number = intval(substr($result, 41, 4)) ;
			$holds_number = intval(substr($result, 37, 4)) ;
			echo '<b>Nombre de prêts en cours :</b> ' . $loans_number . '<br />' ;	
			echo '<b>Dont Nombre de prêts en retard :</b> ' . $lates_number . '<br />' ;	
			echo '<b>Nombre de réservations en cours :</b> ' . $holds_number . '<br />' ;	
			// NB : je ne récupère pas les titres réservés dans les champs AS → pb du champ summary : 			6300220180526    175022  Y       AOJL|AATEST03|BP1|BQ99|AY0AZF1DB → je n'ai qu'un Y, en 3ème position donc je récupère les prêts ; essayer 6300220180526    175022YYY       AOJL|AATEST03|BP1|BQ99|AY0AZF1DB
			// 
		/*  cf function msgPatronInformation dans sip2.class.php :
        * According to the specification:
        * Only one category of items should be  requested at a time, i.e. it would take 6 of these messages, 
        * each with a different position set to Y, to get all the detailed information about a patron's items.
		*/
			$loans = explode ('|AU', $result);
			// $lates = explode ('|AT', $total);
			// $holds = explode ('|AS', $total);
			echo '<hr />' ;
			echo '<h2>Liste des prêts</h2>' ;
			echo '<table class="sortable">' ;
			echo '<thead>' ;
			echo '<tr><th>N°</th><th>CB</th><th>Titre</th><th>Cote</th><th>Date de retour</th><th>Prolonger</th>' ;
			echo '</thead>' ;
			$data = array() ;
			for ($i = 1; $i < count($loans); $i++) {
				$loans[$i] = rtrim($loans[$i]);
				$loans[$i] = rtrim($loans[$i], '|');
				$data[]  = getItemInfo($loans[$i]) ;
				$title = getItemInfo($loans[$i])['title'] ;
				$call = getItemInfo($loans[$i])['call'] ;
				$returnDate = getItemInfo($loans[$i])['returnDate'] ;
				echo '<tr><td>'.$i . '</td><td>' .  $loans[$i] . '</td><td>' . $title . '</td><td>' . $call . '</td><td>' . $returnDate . '</td><td><a href="renew.php?item='.$loans[$i].'&patron='.$patron.'"><i class="fa fa-plus" aria-hidden="true"></i></a></td></tr>' ;
			}
			// echo '<tr><td colspan=5></td><td><a href="renewAll.php?patron='.$patron.'"><button>Prolonger tout</button></a></td></tr>' ;
			echo '</table>' ;
			print_r($data);
			$mysip->disconnect();
		?> 
		<hr />
		<a href="info_usager.php">Autre usager</a>
		<?php
			include_once 'footer.txt' ;
		?>
	</body>
</html>

	