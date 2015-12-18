<?php
include 'database.php';
include('DOMParser/simple_html_dom.php');

$i = 0;
$e = 10000000;
$number = 1;
$date = strftime("%m-%d-%Y", mktime(0, 0, 0, date('m'), date('d')-2, date('Y')));
$pseudoLast = "";
$break = false;

for ($i=$i; $i < $e; $i+=20) {

	if ($break == true) {
		break;
	}

	$page = file_get_html("https://www.minefield.fr/forum/members/?lastvisit=$date&lastvisit_ltmt=mt&st=$i");

	//Récuperer pseudoLast
	foreach ($page->find('strong a[title=Voir le profil]') as $value) {
		//Si c'est le dernier pseudo Partie 2
		if ($pseudoLast == $value->plaintext) {
			echo "Fin\n";
			$break = true;
			break;
		}

		$pseudo = $value->plaintext;
		$pseudo = htmlentities($pseudo);

		$pseudo = str_replace("%", "", $pseudo);
		$pseudo = str_replace(" ", "%20", $pseudo);
		$pseudo = str_replace("..", "", $pseudo);
		$pseudo = str_replace("(", "%28", $pseudo);
		$pseudo = str_replace(")", "%29", $pseudo);

			//Lien profil
			foreach($page->find('strong a[title=Voir le profil]') as $lienProfil) {$lienProfil = $lienProfil->href;}

			//Récuperer profil du joueur
			$profil = file_get_html("http://www.minefield.fr/profil/$pseudo");

			$test = $profil->find('div[id=profil_inconnu]');

			//Si Le profil existe
			if (empty($test)) {

				//Récupérer messages forum
				foreach(file_get_html($lienProfil)->find('li[class=row_title] div[class=content]') as $connexion) {$connexion = $connexion->plaintext;}

				//Récuperer variable dans la page
				foreach($profil->find('li[class=li5] div[class=content]') as $connexion) {$connexion = $connexion->plaintext;}
				foreach($profil->find('li[class=li4] div[class=content]') as $inscription) {$inscription = $inscription->plaintext;}
				foreach($profil->find('li[class=li3] div[class=content]') as $rang) {$rang = $rang->plaintext;}
				foreach($profil->find('li[class=li6] div[class=content]') as $specialite) {$specialite = $specialite->plaintext;}

				//Trie des joueurs
				if ($connexion == "Jamais") {
					if (php_sapi_name() == "cli") {
						echo "\033[0;32m$number\033[0;31m [Erreur] \033[0;0mConnexion est jamais \"$pseudo\"\n";
					}

					else {
						echo "$number [Info] Connexion est jamais \"$pseudo\"<br>";
					}
				}

				else {
					foreach($profil->find('li[class=li1] div[class=content]') as $pseudoIG) {$pseudoIG = $pseudoIG->plaintext;}

					foreach($profil->find('div[class=achievementStepDetails]') as $achievement) {

						//Fat boots
						if(preg_match('#Fat Boots#', $achievement->outertext)) {
							$regexp = preg_match('#\(([0-9]{1,})/10000 kc\)#', $achievement->outertext, $result);
							$fatboots = $result[1];
						}

						//Carpette Diem
						if(preg_match('#Carpette Diem#', $achievement->outertext)) {
							$regexp = preg_match('#\(([0-9]{1,})/666\)#', $achievement->outertext, $result);
							$death = $result[1];
						}

						//Ancêtre et en Os
						if(preg_match('#Ancêtre et en Os#', $achievement->outertext)) {
							$regexp = preg_match('#\(([0-9]{1,})/12\)#', $achievement->outertext, $result);
							$ancetre = $result[1];
						}

						//Je chatte, donc je suis
						if(preg_match('#Je chatte, donc je suis#', $achievement->outertext)) {
							$regexp = preg_match('#\(([0-9]{1,})/10000\)#', $achievement->outertext, $result);
							$msg = $result[1];
						}

						//Insomniaque
						if(preg_match('#Insomniaque#', $achievement->outertext)) {
							$regexp = preg_match('#\(([0-9]{1,})/604800\)#', $achievement->outertext, $result);
							$insomniaque = $result[1];
						}

						//Taupe
						if(preg_match('#Au début était la bouse#', $achievement->outertext)) {
							$regexp = preg_match('#\(([0-9]{1,})/100000\)#', $achievement->outertext, $result);
							$taupe = $result[1];
						}

						//Cobbel comme le jour
						if(preg_match('#Cobbel comme le jour#', $achievement->outertext)) {
							$regexp = preg_match('#\(([0-9]{1,})/100000\)#', $achievement->outertext, $result);
							$cobble = $result[1];
						}

						//Déforestation
						if(preg_match('#Déforestation#', $achievement->outertext)) {
							$regexp = preg_match('#\(([0-9]{1,})/1234\)#', $achievement->outertext, $result);
							$wood = $result[1];
						}

						//Musclor
						if(preg_match('#Musclor#', $achievement->outertext)) {
							$regexp = preg_match('#\(([0-9]{1,})/1000\)#', $achievement->outertext, $result);
							$obsi = $result[1];
						}

						//Moooon préééciieeeuuuuuux
						if(preg_match('#Mooon préééciieeeuuuuux#', $achievement->outertext)) {
							$regexp = preg_match('#\(([0-9]{1,})/100\)#', $achievement->outertext, $result);
							$diamond = $result[1];
						}

						//FLÄRDFULL
						if(preg_match('#FLÄRDFULL#', $achievement->outertext)) {
							$regexp = preg_match('#\(([0-9]{1,})/123\)#', $achievement->outertext, $result);
							$bookshelf = $result[1];
						}

						//Reforestation
						if(preg_match('#Reforestation#', $achievement->outertext)) {
							$regexp = preg_match('#\(([0-9]{1,})/1234\)#', $achievement->outertext, $result);
							$reforestation = $result[1];
						}

						//CobbelBob Lennon
						if(preg_match('#CobbelBob Lennon#', $achievement->outertext)) {
							$regexp = preg_match('#\(([0-9]{1,})/10000\)#', $achievement->outertext, $result);
							$cobblebob = $result[1];
						}

						//Illuminati
						if(preg_match('#Illuminati#', $achievement->outertext)) {
							$regexp = preg_match('#\(([0-9]{1,})/7890\)#', $achievement->outertext, $result);
							$illuminati = $result[1];
						}

						//Naincroyable
						if(preg_match('#naincroyable#', $achievement->outertext)) {
							$regexp = preg_match('#\(([0-9]{1,})/500000\)#', $achievement->outertext, $result);
							$naincroyable = $result[1];
						}

						//Un gros pigeon
						if(preg_match('#Un gros pigeon !#', $achievement->outertext)) {
							$regexp = preg_match('#\(([0-9]{1,})/4000\)#', $achievement->outertext, $result);
							$riannon = $result[1];
						}

						//Je suis à bout
						if(preg_match('#Je suis à bout#', $achievement->outertext)) {
							$regexp = preg_match('#\(([0-9]{1,})/4000\)#', $achievement->outertext, $result);
							$boudumonde = $result[1];
						}

						//Baston
						if(preg_match('#Baston !#', $achievement->outertext)) {
							$regexp = preg_match('#\(([0-9]{1,})/4000\)#', $achievement->outertext, $result);
							$balhaiz = $result[1];
						}

						//Castle Crasher
						if(preg_match('#Castle Crusher#', $achievement->outertext)) {
							$regexp = preg_match('#\(([0-9]{1,})/500\)#', $achievement->outertext, $result);
							$cubo = $result[1];
						}
					}

					$connexion = explode('/', $connexion);
					$connexion2 = $connexion['2'] * 365 + $connexion['1'] * 31 + $connexion['0'];
					$connexion = "".$connexion['2']."-".$connexion['1']."-".$connexion['0']."";

					$inscription = explode('/', $inscription);
					$inscription = "".$inscription['2']."-".$inscription['1']."-".$inscription['0']."";

					//Variable actif
					if (date("Y") * 365 + date("m") * 31 + date("d") - $connexion2 >= 60) {
						$actif = false;
					}

					else {
						$actif = true;
					}

					//Specialite des paysan et vagabond
					if ($rang == "Paysan") {
						$specialite = "paysan";
					}

					elseif ($rang == "Vagabond") {
						$specialite = "Vagabond";
					}

					//Enlever joueurs cheaté
					if ($pseudoIG == "Kasane_") {
						$fatboots = "0";
					}

					if ($pseudoIG == "Pcote") {
						$reforestation = "0";
						$illuminati = "0";
					}

					if ($pseudoIG == "Thorgrin") {
						$riannon = "0";
						$boudumonde = "0";
						$balhaiz = "0";
					} 

					if ($pseudoIG == "Super_Pingouin" || $pseudoIG == "Lettow") {
						$riannon = "0";
					}

					//UUID
					$content = file_get_contents('https://api.mojang.com/users/profiles/minecraft/' . urlencode($pseudoIG));
					$json = json_decode($content);

					if ($json) {
						$UUID = $json->id;

						//Tester si le UUID est sur la bdd
						$test2 = $sql->prepare("SELECT `UUID` FROM `ListeSpe` WHERE `UUID` = :UUID");
						$test2->bindValue('UUID', $UUID);
						$test2->execute();
						$test2 = $test2->fetch();

						if ($test2) {
							$update = $sql->prepare("UPDATE ListeSpe SET `pseudo` = :pseudo, `PseudoIG` = :pseudoIG, `Rang` = :rang, `Specialite` = :specialite, `Connection` = :connexion, `Actif` = :actif, `AchievementDistanceMarche` = :fatboots, `AchievementMort` = :death, `AchievementAnciennete` = :ancetre, `AchievementChat` = :msg, `AchievementTempsConnexion` = :insomniaque, `AchievementDirt` = :taupe, `AchievementCobble` = :cobble, `AchievementWood` = :wood, `AchievementObsi` = :obsi, `AchievementDiamond` = :diamond, `AchievementBookshelf` = :bookshelf,  `AchievementReforestation` = :reforestation, `AchievementCobbleBobLennon` = :cobblebob, `AchievementNaincroyable` = :naincroyable, `AchievementIlluminati` = :illuminati, `AchievementRiannon` = :riannon, `AchievementBoudumonde` = :boudumonde, `AchievementBalhaiz` = :balhaiz, `AchievementCubo` = :cubo WHERE `UUID` = :UUID");

							$update->execute(array(':pseudo' => $pseudo, ':pseudoIG' => $pseudoIG, ':rang' => $rang, ':specialite' => $specialite,':connexion' => $connexion, ':actif' => $actif, ':fatboots' => $fatboots, ':death' => $death, ':ancetre' => $ancetre, ':msg' => $msg, ':insomniaque' => $insomniaque, ':taupe' => $taupe, ':cobble' => $cobble, ':wood' => $wood, ':obsi' => $obsi, ':diamond' => $diamond, ':bookshelf' => $bookshelf, ':UUID' => $UUID, ':reforestation' => $reforestation, ':cobblebob' => $cobblebob, ':naincroyable' => $naincroyable, ':illuminati' => $illuminati, ':riannon' => $riannon, ':boudumonde' => $boudumonde, ':balhaiz' => $balhaiz, ':cubo' => $cubo));

							if (php_sapi_name() == "cli") {
								echo "\n\033[0;32m$number\033[0;36m [Modification] \033[0;33mForum:\033[0;0m $pseudo | \033[0;33mIG:\033[0;0m $pseudoIG| \033[0;33mSpecialite:\033[0;0m $specialite | \033[0;33mRang:\033[0;0m $rang | \033[0;33mConnexion:\033[0;0m $connexion | \033[0;33mActif:\033[0;0m $actif | \033[0;33mFat Boots:\033[0;0m $fatboots | \033[0;33mCarpette Diem:\033[0;0m $death | \033[0;33mAncetre en Os:\033[0;0m $ancetre | \033[0;33mMessages:\033[0;0m $msg | \033[0;33mInsomniaque:\033[0;0m $insomniaque | \033[0;33mTaupe:\033[0;0m $taupe | \033[0;33mCobble:\033[0;0m $cobble | \033[0;33mWood:\033[0;0m $wood | \033[0;33mObsi:\033[0;0m $obsi | \033[0;33mDiamant:\033[0;0m $diamond | \033[0;33mBookshelf:\033[0;0m $bookshelf | \033[0;33mReforestation:\033[0;0m $reforestation | \033[0;33mCobbelBob Lennon:\033[0;0m $cobblebob | \033[0;33mnaincroyable:\033[0;0m $naincroyable | \033[0;33mIlluminati:\033[0;0m $illuminati | \033[0;33mRiannon:\033[0;0m $riannon | \033[0;33mBoudumonde:\033[0;0m $boudumonde | \033[0;33mBalhaiz:\033[0;0m $balhaiz | \033[0;33mCubo:\033[0;0m $cubo\n";
							}

							else {
								echo "$number [Modification] Forum: $pseudo | IG: $pseudoIG | Specialite: $specialite | Rang: $rang | Connexion: $connexion | Actif: $actif | Fat Boots: $fatboots | Carpette Diem: $death | Ancetre en Os: $ancetre | Messages: $msg | Insomniaque: $insomniaque | Taupe: $taupe | Cobble: $cobble | Wood: $wood | Obsi: $obsi | Diamant: $diamond | Bookshelf: $bookshelf | Reforestation: $reforestation | CobbelBob Lennon: $cobblebob | naincroyable : $naincroyable | Illuminati : $illuminati | Riannon : $riannon | Boudumonde : $boudumonde | Balhaiz : $balhaiz | Cubo : $cubo <br>";
							}
						}

						else {
							$listeNom = file_get_contents("https://api.mojang.com/user/profiles/$UUID/names", false);

							$insert = $sql->prepare("INSERT INTO ListeSpe(UUID, listeNom, pseudo, PseudoIG, Specialite, Rang, LienProfil, Connection, inscription, Actif, AchievementDistanceMarche, AchievementMort, AchievementAnciennete, AchievementChat, AchievementTempsConnexion, AchievementDirt, AchievementCobble, AchievementWood, AchievementObsi, AchievementDiamond, AchievementBookshelf, AchievementNaincroyable, AchievementIlluminati, AchievementRiannon, AchievementBoudumonde, AchievementBalhaiz, AchievementCubo) VALUES(:UUID, :listeNom, :pseudo, :pseudoIG, :specialite, :rang, :lienProfil, :connexion, :inscription, :actif, :fatboots, :death, :ancetre, :msg, :insomniaque, :taupe, :cobble, :wood, :obsi, :diamond, :bookshelf, :naincroyable, :illuminati, :riannon, :boudumonde, :balhaiz, :cubo)");
							$insert->execute(array(':pseudoIG' => $pseudoIG, ':UUID' => $UUID, ':listeNom' => $listeNom, ':specialite' => $specialite, ':rang' => $rang, ':lienProfil' => $lienProfil, ':connexion' => $connexion, ':inscription' => $inscription, ':actif' => $actif, ':fatboots' => $fatboots, ':death' => $death, ':ancetre' => $ancetre, ':msg' => $msg, ':insomniaque' => $insomniaque, ':taupe' => $taupe, ':cobble' => $cobble, ':wood' => $wood, ':obsi' => $obsi, ':diamond' => $diamond, ':bookshelf' => $bookshelf, ':naincroyable' => $naincroyable, ':illuminati' => $illuminati, ':riannon' => $riannon, ':boudumonde' => $boudumonde, ':balhaiz' => $balhaiz, 'pseudo' => $pseudo, 'cubo' => $cubo));

							if (php_sapi_name() == "cli") {
								echo "\033[0;32m$number\033[0;36m [Addition] \033[0;33mForum:\033[0;0m $pseudo | \033[0;33mUUID:\033[0;0m $UUID | \033[0;33mListeNom:\033[0;0m $listeNom | \033[0;33mIG:\033[0;0m $pseudoIG | \033[0;33mSpecialite:\033[0;0m $specialite | \033[0;33mRang:\033[0;0m $rang | \033[0;33mLienProfil:\033[0;0m $lienProfil | \033[0;33mConnexion:\033[0;0m $connexion | \033[0;33mInscription:\033[0;0m $inscription  | \033[0;33mActif:\033[0;0m $actif | \033[0;33mFat Boots:\033[0;0m $fatboots | \033[0;33mCarpette Diem:\033[0;0m $death | \033[0;33mAncetre en Os:\033[0;0m $ancetre | \033[0;33mMessages:\033[0;0m $msg | \033[0;33mInsomniaque:\033[0;0m $insomniaque | \033[0;33mTaupe:\033[0;0m $taupe | \033[0;33mCobble:\033[0;0m $cobble | \033[0;33mWood:\033[0;0m $wood | \033[0;33mObsi:\033[0;0m $obsi | \033[0;33mDiamant:\033[0;0m $diamond | \033[0;33mBookshelf:\033[0;0m $bookshelf | \033[0;33mReforestation:\033[0;0m $reforestation | \033[0;33mCobbelBob Lennon:\033[0;0m $cobblebob | \033[0;33mnaincroyable:\033[0;0m $naincroyable | \033[0;33mIlluminati:\033[0;0m $illuminati | \033[0;33mRiannon:\033[0;0m $riannon | \033[0;33mBoudumonde:\033[0;0m $boudumonde | \033[0;33mBalhaiz:\033[0;0m $balhaiz | \033[0;33mCubo:\033[0;0m $cubo\n";
							}

							else {
								echo "$number [Addition] UUID: $UUID | listeNom : $listeNom | Forum: $pseudo | IG: $pseudoIG | Specialite: $specialite | Rang: $rang | LienProfil : $lienProfil | Connexion: $connexion | Inscription: $inscription | Actif: $actif | Fat Boots: $fatboots | Carpette Diem: $death | Ancetre en Os: $ancetre | Messages: $msg | Insomniaque: $insomniaque | Taupe: $taupe | Cobble: $cobble | Wood: $wood | Obsi: $obsi | Diamant: $diamond | Bookshelf: $bookshelf | Reforestation: $reforestation | CobbelBob Lennon: $cobblebob | naincroyable : $naincroyable | Illuminati : $illuminati | Riannon : $riannon | Boudumonde : $boudumonde | Balhaiz : $balhaiz | Cubo : $cubo <br>";
							}
						}
					}

					else {
						if (php_sapi_name() == "cli") {
							echo "\033[0;32m$number\033[0;31m [Erreur] \033[0;0mUUID \"$pseudo\"\n";
						}

						else {
							echo "$number [Info] UUID \"$pseudo\"<br>";
						}
					}
				}
			}

			//Si le profil n'existe pas
			else {
				if (php_sapi_name() == "cli") {
					echo "\033[0;32m$number\033[0;36m [Info] \033[0;0mProfil inconnu \"$pseudo\"\n";
				}
				else {
					echo "$number [Info] Profil inconnu \"$pseudo\"<br>";
				}
			}

			$number++;
		}

		foreach ($page->find('strong a[title=Voir le profil]') as $value) {
			if ($i % 20 == 0) {
				$pseudoLast = $value->plaintext;
			}
			break;
		}
	}

	//Gestion des inactifs sur la base de donnée
	$stmt = $sql->prepare("SELECT * FROM `ListeSpe`");
	$stmt->execute();

	$number = 1;

	while($value = $stmt->fetch()) {
		$connexion = $value['Connection'];
		$pseudo = $value['pseudo'];
		$actif = $value['Actif'];

		$connexion = explode('-', $connexion);
		$connexion = $connexion['0'] * 365 + $connexion['1'] * 31 + $connexion['2'];

		if (date("Y") * 365 + date("m") * 31 + date("d") - $connexion >= 60 & $actif == true) {
			$update = $sql->prepare("UPDATE ListeSpe SET `Actif` = false WHERE `pseudo` = :pseudo");
			$update->bindValue(':pseudo', $pseudo);
			$update->execute();

			if (php_sapi_name() == "cli") {
				echo "\033[0;32m$number\033[0;36m [Activite] \033[0;33mPseudo:\033[0;0m $pseudo | \033[0;33mActif:\033[0;0m false\n";
			}
			else {
				echo "$number [Activite] Pseudo : $pseudo | Actif: false<br>";
			}
		}

		elseif (date("Y") * 365 + date("m") * 31 + date("d") - $connexion < 60 & $actif == false) {
			$update = $sql->prepare("UPDATE ListeSpe SET `Actif` = true WHERE `pseudo` = :pseudo");
			$update->bindValue(':pseudo', $pseudo);
			$update->execute();

			if (php_sapi_name() == "cli") {
				echo "\033[0;32m$number\033[0;36m [Activite] \033[0;33mPseudo:\033[0;0m $pseudo | \033[0;33mActif:\033[0;0m true\n";
			}

			else {
				echo "$number [Activite] Pseudo : $pseudo | Actif: true<br>";
			}
		}

		$number++;
	}

	//Gestion des rangs
	$stmt = $sql->prepare("SELECT * FROM `ListeSpe`");
	$stmt->execute();

	$number = 1;

	while($value = $stmt->fetch()) {
		$specialite = $value['Specialite'];
		$rang = $value['Rang'];
		$pseudo = $value['pseudo'];

		if ($rang !== 'Vagabond' & $specialite == 'Vagabond') {

			if ($rang == 'Paysan') {
				//Envois de la requete pour la modification
				$update = $sql->prepare("UPDATE ListeSpe SET `Specialite` = 'Paysan' WHERE `pseudo` = :pseudo");
				$update->bindValue(':pseudo', $pseudo);
				$update->execute();

				//Logs
				if (php_sapi_name() == "cli") {
					echo "\033[0;32m$number\033[0;36m [Specialite] \033[0;33mPseudo:\033[0;0m $pseudo | \033[0;33mSpecialite:\033[0;0m paysan\n";
				}
				else {
					echo "$number [Specialite] Pseudo : $pseudo | Specialite: Paysan<br>";
				}
			}

			if ($rang !== 'Paysan') {
				//Envois de la requete pour la modification
				$update = $sql->prepare("UPDATE ListeSpe SET `Specialite` = 'autre' WHERE `pseudo` = :pseudo");
				$update->bindValue(':pseudo', $pseudo);
				$update->execute();

				//Logs
				if (php_sapi_name() == "cli") {
					echo "\033[0;32m$number\033[0;36m [Specialite] \033[0;33mPseudo:\033[0;0m $pseudo | \033[0;33mSpecialite:\033[0;0m autre\n";
				}
				else {
					echo "$number [Specialite] Pseudo : $pseudo | Specialite: autre<br>";
				}
			}
		}

		elseif ($rang == 'Villageois' & $specialite == 'paysan') {

			//Envois de la requete pour la modification
			$update = $sql->prepare("UPDATE ListeSpe SET `Specialite` = 'autre' WHERE `pseudo` = :pseudo");
			$update->bindValue(':pseudo', $pseudo);
			$update->execute();

			//Logs
			if (php_sapi_name() == "cli") {
				echo "\033[0;32m$number\033[0;36m [Specialite] \033[0;33mPseudo:\033[0;0m $pseudo | \033[0;33mSpecialite:\033[0;0m autre\n";
			}
			else {
				echo "$number [Specialite] Pseudo : $pseudo | Specialite: autre<br>";
			}
		}

		$number++;
	}
?>