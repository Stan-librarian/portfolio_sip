<?php
	function read_array($array){
		foreach($array as $key => $value){
			if(gettype($value)== 'array'){
				read_array($value);
			}
			else {
				echo $key . ' : ' . $value . '<br />' ;
			}	
		}
	}
	//
	function startsWith($haystack, $needle)
		{
			 $length = strlen($needle);
			 return (substr($haystack, 0, $length) === $needle);
		}
	//
	function endsWith($haystack, $needle)
		{
			$length = strlen($needle);
			if ($length == 0) {
				return true;
			}

			return (substr($haystack, -$length) === $needle);
		}
	
	//
	function getItemInfo($item)
	{
		$info = array() ;
		$title = '' ;
		$call = '' ;
		$returnDate = '' ;
		$myIniFile = parse_ini_file ("config.ini", TRUE, INI_SCANNER_RAW);
		$url = $myIniFile["host"]["url"] ;
		$port = $myIniFile["host"]["port"] ;
		$mysip = new sip2;
		$mysip->hostname = $url ;
		$mysip->port = $port ;
		$result = $mysip->connect();
		$query = $mysip->msgItemInformation($item);
		$result = $mysip->get_message($query) ;
		$title = explode('|AJ', $result)[1];
		// $title = explode('|', $title)[0] . ' ('. $result . ')' ;
		$title = explode('|', $title)[0]  ;
		$call = explode('|BG', $result)[1];
		$call = explode('|', $call)[0]  ;
		if(strpos($result, '|AH') !== false){
			$returnDate = explode('|AH', $result)[1];
			$returnDate = explode('|', $returnDate)[0]  ;
		}
		$mysip->disconnect();
		$info['title'] = $title ;
		$info['call'] = $call ;
		$info['returnDate'] = $returnDate ;
		unset($title, $call, $returnDate) ;
		return $info ;
	}
	//
	function getPatronInfo($patron)
	{
		$info = array() ;
		$nom = '' ;
		$adresse = '' ;
		$telephone = '' ;
		$email = '' ;
		$category = '' ;
		$birthDate = '' ;
		$alert = '' ;
		$myIniFile = parse_ini_file ("config.ini", TRUE, INI_SCANNER_RAW);
		$url = $myIniFile["host"]["url"] ;
		$port = $myIniFile["host"]["port"] ;
		$mysip = new sip2;
		$mysip->hostname = $url ;
		$mysip->port = $port ;
		$mysip->patron = $patron ;
		$mysip->patronpwd = '';
		$result = $mysip->connect();
		$query = $mysip->msgPatronInformation('charged');
		$result = $mysip->get_message($query) ;
		// ex 1 :
		// Requête : 6300220171124 112916 Y AOJL|AATEST03|BP1|BQ5|AY0AZF21B
		// Résultat : 64 00220171124 112916000100000002000000000001AOJL |AATEST03|AETEST, Mineur - Fin d'abonnement le 01/07/2018|BZ5|CA30|CB20|BLY|CQN|BHEUR|BV0.0|CC0.0|BDAdresse Ligne 1 Adresse Ligne 3, Adresse Ligne 2, Adresse Ligne 3|BE|BF03 12 34 56 78|DG|DH|DI|DJ|DK|DL|PD11/15/2005|PCMineur LLH|AU00000000429765|AU00000000691835| 
		// ex 2 :
		// Requête : 6300220171124 114728 Y AOJL|AAE023145|BP1|BQ5|AY0AZF247
		// Résultat : 64Y 00220171124 114728000000050005000000000000AOJL |AAE023145|AELELEU, Claudie - Fin d'abonnement le 03/10/2018|BZ5|CA30|CB20|BLY|CQN|BHEUR|BV0.0|CC0.0|BD5/44 rue de Trévise, LILLE, |BE|BF|DG|DH|DI|DJ|DK|DL|AFAttention : l'abonné a des prêts en retard.|PD12/01/1945|PCMajeur LLH|AU00000000604599|AU00000000740626|AU00000000511719|AU00000000740619|AU00000000717553| 
		//
		$nom = explode('|AE', $result)[1];
		$nom = explode('|', $nom)[0] ;
		$nom = explode(' - ', $nom)[0] ;
		$adresse = explode('|BD', $result)[1];
		$adresse = explode('|', $adresse)[0] ;
		$telephone = explode('|BF', $result)[1];
		$telephone = explode('|', $telephone)[0] ;
		$email = explode('|BE', $result)[1];
		$email = explode('|', $email)[0] ;
		$category = explode('|PC', $result)[1];
		$category = explode('|', $category)[0] ;
		$birthDate = explode('|PD', $result)[1];
		$birthDate = explode('|', $birthDate)[0] ;	
		if(strpos($result, '|AF') !== false){
			$alert = explode('|AF', $result)[1];
			$alert = explode('|', $alert)[0] ;
		}
		$mysip->disconnect();
		$info['name'] = $nom ;
		$info['address'] = $adresse ;
		$info['phone'] = $telephone ;
		$info['email'] = $email ;
		$info['category'] = $category ;
		$info['birthDate'] = $birthDate ;
		$info['alert'] = $alert ;
		unset($nom, $adresse, $telephone, $email, $category, $birthDate, $alert) ;
		return $info ;
	}
?>