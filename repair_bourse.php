<?php
	include 'database.php';
	date_default_timezone_set('America/Montreal');

//Déclaration des données
	$start = '2014-09-10';
	$end = '2014-10-08';

//Nombre de jours
  $nbSecondes= 60*60*24;

  $debut_ts = strtotime($start);
  $fin_ts = strtotime($end);
  $diff = $fin_ts - $debut_ts;
  $nbJours = round($diff / $nbSecondes);

//Requete pour sélectionner les colones
  $colone = $sql->prepare("SELECT * FROM `Bourse`");
	$colone->execute();

	$colone = $colone->fetch();

	foreach ($colone as $key => $value) {
		if ($key !== 'ID' & $key !== 0 & $key !== 'Date') {
			
		//Récupération des prix
			$prixStart = $sql->prepare("SELECT `$key` FROM `Bourse` WHERE `Date` = '$start'");
			$prixStart->execute();

			$prixEnd = $sql->prepare("SELECT `$key` FROM `Bourse` WHERE `Date` = '$end'");
			$prixEnd->execute();

			$prixStart = $prixStart->fetch();
			$prixStart = $prixStart[$key];
			$prixEnd = $prixEnd->fetch();
			$prixEnd = $prixEnd[$key];

		//Calcul de la moyenne
			$moyenne = $prixEnd - $prixStart;
			$moyenne /= $nbJours;

			for ($number = 1; $number < $nbJours; $number++) {
				$prixStart += $moyenne;
				$prix = round($prixStart, 2);

			//Préparation des données pour la requete
				$dateNb = $nbJours - $number;
				$date = strftime("%Y-%m-%d", mktime(0, 0, 0, date('m'), date('d')-$dateNb, date('Y')));

		//Tester si la date est sur la bdd
			$test = $sql->prepare("SELECT `Date` FROM `Bourse` WHERE `Date` = '$date'");
			$test->execute();

			$test = $test->fetch();

		//Envois des requetes
			if (!empty($test)) {
			//Envois de la requete pour la modification
				$update = $sql->prepare("UPDATE Bourse SET `$key` = '$prix' WHERE `Date` = '$date'");
				$update->execute();

			//Logs
				if (php_sapi_name() == "cli") {
					echo "\033[0;36m [Bourse] \033[0;33mObjet:\033[0;0m $key | \033[0;33mDate:\033[0;0m $date | \033[0;33mPrix:\033[0;0m $prix\n";
				}
				else {
					echo "[Bourse] Objet: $key | Date: $date | Prix: $prix<br>";
				}
			}

			else {
			//Envois de la requete pour l'addition
				$insert = $sql->prepare("INSERT INTO Bourse(`Date`, `$key`) VALUES('$date', '$prix')");
				$insert->execute();

			//Logs
				if (php_sapi_name() == "cli") {
					echo "\033[0;36m [Bourse] \033[0;33mObjet:\033[0;0m $key | \033[0;33mDate:\033[0;0m $date | \033[0;33mPrix:\033[0;0m $prix\n";
				}
				else {
					echo "[Bourse] Objet: $key | Date: $date | Prix: $prix<br>";
				}
			}

			}

		}
	}

?>