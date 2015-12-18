<?php
include 'database.php';
?>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UFT-8">
</head>
<?php

  $stmt = $sql->prepare("SELECT `PseudoIG` FROM `ListeSpe` WHERE `UUID` = ''");
  $stmt->execute();


$number = 1;
while ($info = $stmt->fetch()) {

    $pseudoIG = $info[0];

    $content = file_get_contents('https://api.mojang.com/users/profiles/minecraft/' . urlencode($pseudoIG));
    $json = json_decode($content);
    $UUID = $json->id;

    //Envois de la requete pour la modification
        $update = $sql->prepare("UPDATE ListeSpe SET `UUID` = :UUID WHERE `PseudoIG` = :PseudoIG");

        $update->execute(array(':UUID' => $UUID, ':PseudoIG' => $PseudoIG));

    //Logs
        if (php_sapi_name() == "cli") {
            echo "\n\033[0;32m$number\033[0;36m [Modification] \033[0;33mUUID:\033[0;0m $UUID | \033[0;33mIG:\033[0;0m $PseudoIG\n";
        }
        else {
            echo "$number [Modification] UUID: $UUID | IG: $PseudoIG <br>";
        }

    sleep(1);
    $number++;
}

 ?>