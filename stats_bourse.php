<?php
	include 'database.php';

include('DOMParser/simple_html_dom.php');

	$bourse = file_get_html("http://www.minefield.fr/gui/bourse.php");

	foreach($bourse->find('img[alt=img]') as $value) {
    	$id = $value->src;

    	$id2 = array();

    	if(preg_match('#32x32/([0-9]+||[0-9]+-[0-9]+)\.png#', $id, $id2)) { $id = $id2[1];}

	    	$nom = $bourse->find("img[src=32x32/$id.png]", 0)->parent()->parent()->find('td[class=c12]');
	    		if (empty($nom)) { $nom = $bourse->find("img[src=32x32/$id.png]", 0)->parent()->parent()->find('td[class=c22]'); }
	    	$nom = $nom[0];
	    	$nom = preg_replace("#<td class='c[0-9]+'>#", "", $nom);
	    	$nom = preg_replace("#</td>#", "", $nom);

	    	$prix = $bourse->find("img[src=32x32/$id.png]", 0)->parent()->parent()->find('td[class=c13]');
	    		if (empty($prix)) { $prix = $bourse->find("img[src=32x32/$id.png]", 0)->parent()->parent()->find('td[class=c23]'); }
   	    	$prix = $prix[0];
	    	$prix = preg_replace("#<td class='c[0-9]+'>#", "", $prix);
	    	$prix = preg_replace("# Pa</td>#", "", $prix);

//Requete
	//Ajout item
    $recherche = $sql->prepare("SELECT ID_IG FROM ListeItem WHERE ID_IG = :id");
    $recherche->bindValue('id', $id);
		$recherche->execute();

		$row = $recherche->fetch();

    if ($row[0] !== $id) {

    $insert = $sql->prepare("INSERT INTO ListeItem(ID_IG, Nom) VALUES(:id,:nom)");
    $insert->bindValue('id', $id);
    $insert->bindValue('nom', $nom);
		$insert->execute();

		echo "Ajout d'un id: $id | $nom\n";
    }

	//Si il n'y a pas d'entrée, la créer
    $colone = $sql->prepare("SHOW COLUMNS FROM Bourse LIKE :id");
    $colone->bindValue('id', $id);
		$colone->execute();

		$colone = $colone->fetch();

			if ($colone[0] !== $id) {
				$colone = $sql->prepare("ALTER TABLE `Bourse` ADD `:id` text not null");
				$colone->bindValue('id', $id);
				$colone->execute();

				echo "Ajout d'une colonne: $id\n";
			}

	//Ajout prix des items
		$select = $sql->prepare("SELECT * FROM Bourse WHERE `Date`=:date1");
		$select->bindValue('date1', date("Y-m-d"));
		$select->execute();

		$row = $select->fetch();

		$date = date("Y-m-d");

	  if ($row['Date'] == date('Y-m-d')) {
			$insert = $sql->prepare("UPDATE Bourse SET `$id` = '$prix' WHERE `Date` = '$date'");
			$insert->execute();

			echo "Ajout du prix1: $id | $nom| ".date('Y-m-d')." | $prix\n";		
		}
		else {
			$insert = $sql->prepare("INSERT INTO Bourse(`Date`, `".$id."`) VALUES(:date1, :prix)");
			$insert->bindValue('prix', $prix);
			$insert->bindValue('date1', date("Y-m-d"));
			$insert->execute();

			echo "Ajout du prix2: $id | $nom | ".date('Y-m-d')." | $prix\n";	
		}

	}
?>