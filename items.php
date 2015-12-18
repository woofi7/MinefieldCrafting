<?php
include 'database.php';

$item = $sql->prepare("SELECT * FROM `ListeItem`");
$item->execute();

$fichier = fopen('../../js/countries.js', 'a+');
ftruncate($fichier, 0);

fputs($fichier ,"var countries = { \n");
while ($row = $item->fetch()) {
	fputs($fichier, "'".$row['ID_IG']."': '".htmlentities($row['Nom'], ENT_QUOTES)."',\n");
}
fputs($fichier, "}");

fclose($fichier);
?>