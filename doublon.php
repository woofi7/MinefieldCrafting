<?php
include 'database.php';
include('DOMParser/simple_html_dom.php');

$stmt = $sql->prepare("SELECT * FROM `ListeSpe`");
$stmt->execute();

$number = 1;
while ($request = $stmt->fetch()) {
    if ($request['LienProfil'] == "") {
        $UUID = $request['UUID'];
        $pseudo = $request['pseudo'];

        $page = file_get_html("https://www.minefield.fr/forum/index.php?app=members&name=$pseudo");

        foreach($page->find('strong a[title=Voir le profil]') as $lienProfil) {$lienProfil = $lienProfil->href;}
        $id = $request['ID'];

        echo "\033[0;32m[$number] \033[0;33mForum:\033[0;0m $pseudo | \033[0;33mLienProfil:\033[0;0m $lienProfil\n";
        $number++;

        $update = $sql->prepare("UPDATE ListeSpe SET `LienProfil` = :lienProfil WHERE `ID` = :id");
        $update->execute(array(':lienProfil' => $lienProfil, ':id' => $id));
    }
}

 ?>