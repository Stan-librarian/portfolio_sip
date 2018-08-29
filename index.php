<html Content-Type: text/html; charset=UTF-8>
	<head>
		<title>SIP en PHP</title>
		<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
		<link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
		<!--<link rel="stylesheet" type="text/css" href="../tableau_bord/css/tableau_bord.css" media="screen" />-->
	</head>
	<body>
		<!--<script type="text/javascript"> 
			if(localStorage.getItem("isRefresh" === undefined){
			  localStorage.setItem("isRefresh",true);
			  location.href = location.href;
			/* et normalement ça devrait marcher sauf erreur de ma part qui est plus que plausible */
			}

		</script>-->
		<?php
			include_once 'header.txt' ;
		?>
		<div class="container">
			<section class="container">
				<a href="info_usager.php">
				<i class="fa fa-user fa-3x" aria-hidden="true"></i><i class="fa fa-question fa-3x" aria-hidden="true"></i><br />Info usager<br />(63→64)
				</a>
			</section>
			<section class="container">
				<a href="info_document.php">
				<i class="fa fa-book fa-3x" aria-hidden="true"></i><i class="fa fa-question fa-3x" aria-hidden="true"></i><br />Info document<br />(17→18)
				</a>
			</section>
			<section class="container">
				<a href="pret.php">
				<i class="fa fa-upload fa-3x" aria-hidden="true"></i><br />Prêt<br />(11→12)
				</a>
			</section>
			<section class="container">
				<a href="prolongation.php">
				<i class="fa fa-upload fa-3x" aria-hidden="true"></i><i class="fa fa-plus fa-3x" aria-hidden="true"></i><br />Prolongation<br />(29→30)
				</a>
			</section>
			<section class="container">
				<a href="retour.php">
				<i class="fa fa-download fa-3x" aria-hidden="true"></i><br />Retour<br />(09→10)
				</a>
			</section>
		</div>
		<hr />
		<p>
			Base sélectionnée :
			<?php
				$myIniFile = parse_ini_file ("config.ini", TRUE);
				$host = $myIniFile["host"]["url"] ;
				echo $host ;
				echo ' <a href="change_host.php?host='.$host.'"><button>Changer</button></a>' ;
			?>
			
		</p>
		<?php
			include_once 'footer.txt' ;
		?>
	</body>
</html>

	