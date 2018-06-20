<?php
/**
 * Created by PhpStorm.
 * User: ryujjin
 * Date: 26/02/18
 * Time: 19:23
 */

include 'ficheFrais.php';

function randomId() {
    //fonction qui génére un id aléatoire
    $string = rand(0,9);
    $string = strval($string);
    $chaine = "abcdefghijklmnpqrstuvwxy";
    srand((double)microtime()*1000000);
    for($i=0; $i<2; $i++) {
        $string .= $chaine[rand()%strlen($chaine)];
    }
    return $string;
}

function insertUser($user){
    //generation des donnes manquantes
    $user['id'] = randomId();
    $user['id_role'] = 3;
    $user['version'] = 1;
    $user['login'] = "".strtolower(substr($user['Prenom'],0,1-strlen($user['Prenom'])))."".strtolower(substr($user['Nom'],0, strlen($user['Nom'])))."";

    $connexion = openDb();
    $sqlRequest = 'INSERT INTO utilisateur(`nom`,`prenom`,`login`,`password`,`adresse`,`code_postal`,`ville`,`date_embauche`,`id_role`,`version`,`telephone`, `email`) 
                    VALUE ("'.$user["Nom"].'","'.$user["Prenom"].'","'.$user["login"].'","'.$user["Mdp"].'","'.$user["Adresse"].'",
                        "'.$user["CP"].'","'.$user["Ville"].'","'.$user["dateembauche"].'",'.$user["id_role"].','.$user["version"].',"'.$user["Tel"].'","'.$user["Email"].'");';
    $stmt = mysqli_prepare($connexion,$sqlRequest);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    closeDb($connexion);
}

function connectUser($login,$mdp){
    //Ouverture + recuperation des donnees
    $connexion = openDb();
    $sqlRequest = "SELECT * FROM `utilisateur` WHERE `login` = '".$login."'";
    $result = mysqli_query($connexion, $sqlRequest);
    $data = mysqli_fetch_row($result);
    $_mdp = $data[4];

    // Les donnees renvoyer
    $dataR = array();
    $dataR['Bool'] = password_verify($_mdp, $mdp);
    $dataR['Id'] = $data[0];
    $dataR['Nom'] = $data[1];
    $dataR['Prenom'] = $data[2];
    $dataR['Rang'] = $data[13];

    //fermeture + envois des donnees
    mysqli_free_result($result);
    closeDb($connexion);
    return $dataR;
}

function getUserByType($type){
    //ouverture de db
    $connexion = openDb();
    $sqlRequest ="SELECT * FROM utilisateur WHERE id_role = '".$type."';";
    $result = mysqli_query($connexion, $sqlRequest);
    $res = array();
    $i = 0;
    while($data = mysqli_fetch_row($result)){
        $res[$i]['Id'] = $data[0];
        $res[$i]['Nom'] = $data[1];
        $res[$i]['Prenom'] = $data[2];
        $res[$i]['Adresse'] = $data[5];
        $res[$i]['CodePostale'] = $data[6];
        $res[$i]['Ville'] = $data[7];
        $res[$i]['Telephone'] = $data[11];
        $res[$i]['Email'] = $data[12];
        $i++;
    }

    mysqli_free_result($result);
    closeDb($connexion);
    return $res;
}

function getUserById($id){
    $connexion = openDb();
    $sqlRequest ="SELECT * FROM utilisateur WHERE id = '".$id."';";
    $result = mysqli_fetch_row(mysqli_query($connexion, $sqlRequest));
    $res = array();
    $res['Id'] = $result[0];
    $res['Nom'] = $result[1];
    $res['Prenom'] = $result[2];
    $res['Adresse'] = $result[9];
    $res['CodePostale'] = $result[6];
    $res['Ville'] = $result[7];
    $res['Telephone'] = $result[10];
    $res['Email'] = $result[11];
    closeDb($connexion);
    return $res;
}
/*
$login = 'comp';
$options = [
    'cost' => 12,
];
$mdp = password_hash('1234', PASSWORD_BCRYPT, $options);
$v = connectUser($login,$mdp);
print_r($v);*/

?>