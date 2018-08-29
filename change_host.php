<?php
	if (isset($_GET['host']) && $_GET['host'] != '') {
		$host = $_GET['host'] ;
	}
	$hostNew = '' ;
	if($host == 'X.X.X.X'){
		$hostNew = 'Y.Y.Y.Y' ;
	}
	elseif($host == 'Y.Y.Y.Y'){
		$hostNew = 'X.X.X.X' ;
	}
	// echo 'Serveur actuel : '. $host . '<br />' ;
	// echo 'Serveur souhaité : '. $hostNew . '<br />' ;
	// echo '<hr />' ;
	$tmp = file("config.ini");
	// print_r($tmp) ;
	// echo '<hr />' ;
	$tmp[1] = 'url = ' . $hostNew . "\r\n" ;
	// print_r($tmp) ;
	// echo '<hr />' ;
	$new = '' ;
	foreach($tmp as $line){
		// echo $line . '<br />' ;
		// $new .= $line . "\r" ;
		$new .= $line ;
	}
	// echo $new ;
	copy("config.ini", "config.ini.old");
	// $ecrire = fopen("config.ini","w");
	// ftruncate($ecrire, 0);
	file_put_contents ('config.ini' , $new);
?>
<script type="text/javascript"> 
	<!--history.back(-1);-->
	window.location.href = "index.php" ;
</script>
