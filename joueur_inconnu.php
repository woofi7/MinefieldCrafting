<?php
include 'database.php';
include('DOMParser/simple_html_dom.php');

$stmt = $sql->prepare("SELECT * FROM `ListeSpe` WHERE `Specialite` = 'autre'");
$stmt->execute();

$number = 1;
foreach ($stmt as $key => $value) {
	if ($value['Rang'] !== 'Inconnu') {
		
		if (!empty($value['LienProfil'])) {
			$pseudo = $value['pseudo'];

			$profil = file_get_html("http://www.minefield.fr/profil/$pseudo");

			$test = $profil->find('div[id=profil_inconnu]');
			if (empty($test)) {
				foreach($profil->find('li[class=li5] div[class=content]') as $specialite) {$specialite = $specialite->plaintext;}

				if (!empty($specialite)) {
				//Envois de la requete pour la modification
					$update = $sql->prepare("UPDATE ListeSpe SET `Specialite` = :specialite WHERE `pseudo` = :pseudo");

					$update->execute(array(':specialite' => $specialite, ':pseudo' => $pseudo));

				//Logs
					if (php_sapi_name() == "cli") {
						echo "\033[0;32m$number\033[0;36m [Spe] \033[0;33mForum:\033[0;0m $pseudo | \033[0;33mSpecialite:\033[0;0m $specialite\n";
					}
					else {
						echo "$number [Spe] Forum: $pseudo | Specialite: $specialite <br>";
					}
					$number++;
				}
			}
		}
	}

}

?>