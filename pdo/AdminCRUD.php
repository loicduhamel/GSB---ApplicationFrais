<?php 

require "AccessBdd.php";

function Create($id,$nom,$valeur){
    $connexion = openDb();
    $sqlRequest = "INSERT INTO `frais_forfait`(`id`, `libelle`, `montant`) VALUES ('".$id."','".$nom."',".$valeur.")";
    $stmt = mysqli_prepare($connexion,$sqlRequest);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    closeDb($connexion);
};

function Read(){ 
    $connexion = openDb();
    $sqlRequest = "SELECT * FROM `frais_forfait`";
    $result = mysqli_query($connexion, $sqlRequest);
    $res = array();
    $i = 0;
    while ($data = mysqli_fetch_row($result)) {
        $res[$i]['Identifiant'] = $data[0];
        $res[$i]['Nom'] = $data[1];
        $res[$i]['Valeur'] = $data[2];
        $i++;
    }
    mysqli_free_result($result);
    closeDb($connexion);
    return $res;
};

function Edit($id,$nom,$valeur){
    $connexion = openDb();
    $sqlRequest = "UPDATE `frais_forfait` SET `libelle`='".$nom."',`montant`=".$valeur." WHERE id='".$id."'";
    $stmt = mysqli_prepare($connexion,$sqlRequest);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    closeDb($connexion);
};

function Delete($id){
    $connexion = openDb();
    $sqlRequest = "DELETE FROM `frais_forfait` WHERE `id` = '".$id."'";
    $stmt = mysqli_prepare($connexion,$sqlRequest);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    closeDb($connexion);
};