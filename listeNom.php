<?php
include 'database.php';
?>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UFT-8">
</head>
<?php

$stmt = $sql->prepare("SELECT `UUID` FROM `ListeSpe`");
$stmt->execute();

$number = 1;
while ($info = $stmt->fetch()) {

	$UUID = $info[0];

    $result = file_get_contents("https://api.mojang.com/user/profiles/$UUID/names", false);

    //Envois de la requete pour la modification
        $update = $sql->prepare("UPDATE ListeSpe SET `ListeNom` = :result WHERE `UUID` = :UUID");

        $update->execute(array(':result' => $result, ':UUID' => $UUID));

    //Logs
        if (php_sapi_name() == "cli") {
            echo "\n\033[0;32m$number\033[0;36m [Modification] \033[0;33mListeNom:\033[0;0m $result | \033[0;33mUUID:\033[0;0m $UUID\n";
        }
        else {
            echo "$number [Modification] ListeNom: $result | UUID: $UUID <br>";
        }

    $number++;
    sleep(2);
}

 ?>