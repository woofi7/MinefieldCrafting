<?php
include 'database.php';

$stmt = $sql->prepare("SELECT * FROM `ListeSpe`");
$stmt->execute();

echo date("Y-m-d");

$vagabond = $sql->prepare('SELECT * FROM ListeSpe WHERE Connection = "'.date("Y-m-d").'" AND Rang = "Vagabond"');
$vagabond->execute();
$paysan = $sql->prepare('SELECT * FROM ListeSpe WHERE Connection = "'.date("Y-m-d").'" AND Rang = "Paysan"');
$paysan->execute();
$villageois = $sql->prepare('SELECT * FROM ListeSpe WHERE Connection = "'.date("Y-m-d").'" AND Rang = "Villageois"');
$villageois->execute();
$citoyen = $sql->prepare('SELECT * FROM ListeSpe WHERE Connection = "'.date("Y-m-d").'" AND Rang = "Citoyen"');
$citoyen->execute();
$chevalier = $sql->prepare('SELECT * FROM ListeSpe WHERE Connection = "'.date("Y-m-d").'" AND Rang = "Chevalier"');
$chevalier->execute();
$noble = $sql->prepare('SELECT * FROM ListeSpe WHERE Connection = "'.date("Y-m-d").'" AND Rang = "Noble"');
$noble->execute();
$gouverneur = $sql->prepare('SELECT * FROM ListeSpe WHERE Connection = "'.date("Y-m-d").'" AND Rang = "Gouverneur"');
$gouverneur->execute();
$roi = $sql->prepare('SELECT * FROM ListeSpe WHERE Connection = "'.date("Y-m-d").'" AND Rang = "Roi"');
$roi->execute();

$nbVagabond = 0;
$nbPaysan = 0;
$nbVillageois = 0;
$nbCitoyen = 0;
$nbChevalier = 0;
$nbNoble = 0;
$nbGouverneur = 0;
$nbRoi = 0;

while ($row = $vagabond->fetch()) {$nbVagabond++;}
while ($row = $paysan->fetch()) {$nbPaysan++;}
while ($row = $villageois->fetch()) {$nbVillageois++;}
while ($row = $citoyen->fetch()) {$nbCitoyen++;}
while ($row = $chevalier->fetch()) {$nbChevalier++;}
while ($row = $noble->fetch()) {$nbNoble++;}
while ($row = $gouverneur->fetch()) {$nbGouverneur++;}
while ($row = $roi->fetch()) {$nbRoi++;}

	$update = $sql->prepare("INSERT INTO Stats(Date, Vagabond, Paysan, Villageois, Citoyen, Chevalier, Noble, Gouverneur, Roi) VALUES(:date1, :vagabond, :paysan, :villageois, :citoyen, :chevalier, :noble, :gouverneur, :roi)");

	$update->execute(array(':date1' => date('Y-m-d'), ':vagabond' => $nbVagabond, ':paysan' => $nbPaysan, ':villageois' => $nbVillageois, ':citoyen' => $nbCitoyen, ':chevalier' => $nbChevalier, ':noble' => $nbNoble, ':gouverneur' => $nbGouverneur, ':roi' => $nbRoi));
?>