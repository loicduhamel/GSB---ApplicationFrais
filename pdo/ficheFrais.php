<?php
/**
 * User: ryujjin
 * Type_Etat_Frais : {
 * * ID => Description
 * * CL => Saisie clôturée /
 * * CR => Fiche créée, saisie en cours /
 * * RB => Remboursée /
 * * VA => Validée et mise en paiement }
 * Frais_Forfait : {
 * * ID => Description : Prix en €
 * * ETP => Forfait Etape : 110.00 /
 * * KM => Frais Kilometrique : 0.62 /
 * * NUI => Nuitée Hôtel : 80.00 /
 * * REP => Repas Restaurant : 25 }
 */

require "AccessBdd.php";

function donneepdf($id){
    $connexion = openDb();
    $sqlRequest ="SELECT * FROM utilisateur WHERE id = '".$id."';";
    $result = mysqli_fetch_row(mysqli_query($connexion, $sqlRequest));
    $res = array();
    $res['Id'] = $result[0];
    $res['Nom'] = $result[1];
    $res['Prenom'] = $result[2];
    $res['Adresse'] = $result[5];
    $res['CodePostale'] = $result[6];
    $res['Ville'] = $result[7];
    $res['Telephone'] = $result[11];
    $res['Email'] = $result[12];
    closeDb($connexion);
    return $res;
}

function calculMontant($id, $Qte){
    $connexion = openDb();
    $sqlRequest = "SELECT `montant` FROM `frais_forfait` WHERE `id`= '".$id."';";
    $result = mysqli_query($connexion,$sqlRequest);
    $data = mysqli_fetch_row($result);
    $res = array();
    closeDb($connexion);
    mysqli_free_result($result);
    $res['Montant'] = $data[0];
    return $res['Montant']*$Qte;
}

function deleteFicheF_HF($idUser,$id,$bool){
    /**
     * if bool == true Then delete fiche_hors_forfait
     * if bool == false then delete fiche_forfait
     * else ... there is no else
    */
    if ($bool){
       $connexion = openDb();
       $sqlRequest = mysqli_prepare($connexion,"DELETE FROM `ligne_frais_hors_forfait` WHERE `id_visiteur` = ".$idUser." AND `id` = ".$id.";");
       mysqli_stmt_execute($sqlRequest);
       mysqli_stmt_close($sqlRequest);
       closeDb($connexion);
    }
    if ($bool == false){
        $connexion = openDb();
        $sqlRequest = mysqli_prepare($connexion,"DELETE FROM `ligne_frais_forfait` WHERE `id_visiteur` = ".$idUser." AND `id` = ".$id.";");
        mysqli_stmt_execute($sqlRequest);
        mysqli_stmt_close($sqlRequest);
        closeDb($connexion);
    }
}

function selectFiche($idUser,$bool, $mois){
    /**
     * If bool == true Then Select Fiche_Hors_Forfait
     * If bool == false Then Select Fiche_Forfait
    */
    $connexion = openDb();
    $sqlRequest = "SELECT * FROM `fiche_frais` WHERE mois = ". $mois ." and id_visiteur = ". $idUser ." and id_etat = 'CR' ";
    $result = mysqli_query($connexion, $sqlRequest);
    $data = mysqli_fetch_row($result);
    $res = 0;
    if(isset($data)){
        if ($bool) {
            $connexion = openDb();
            $sqlRequest = "SELECT `libelle`, `date_frais_hors_forfait`, `montant`,`mois`, `id` FROM `ligne_frais_hors_forfait` WHERE id_visiteur=" . $idUser . " AND mois = " . $mois . ";";
            $result = mysqli_query($connexion, $sqlRequest);
            $res = array();
            $i = 0;
            while ($data = mysqli_fetch_row($result)) {
                $res[$i]['Desc'] = $data[0];
                $res[$i]['Date'] = $data[1];
                $res[$i]['Montant'] = $data[2];
                $res[$i]['Mois'] = $data[3];
                $res[$i]['Id'] = $data[4];
                $i++;
            }

            mysqli_free_result($result);
            closeDb($connexion);
            return $res;
        }
        if ($bool == false){
            $connexion = openDb();
            $sqlRequest = "SELECT `id_frais_forfait`, `quantite`, `description`, `date_depense`, `id` FROM `ligne_frais_forfait` WHERE `id_visiteur` = ".$idUser." AND `mois` = ".$mois.";";
            $result = mysqli_query($connexion, $sqlRequest);
            $res = array();
            $i = 0;
            while ($data = mysqli_fetch_row($result)) {
                $res[$i]['Id_Type'] = $data[0];
                $res[$i]['Qte'] = $data[1];
                $res[$i]['Desc'] = $data[2];
                $res[$i]['Date_Dep'] = $data[3];
                $res[$i]['Id'] = $data[4];
                $i++;
            }
            mysqli_free_result($result);
            closeDb($connexion);
            return $res;
        }
    }
}

function selectFicheByType($idUser, $etat, $mois){
    $connexion = openDb();
    $sqlRequest = "SELECT * FROM `fiche_frais` WHERE mois = ". $mois ." and id_visiteur = ". $idUser ." and id_etat = 'CR' ";
    $result = mysqli_query($connexion, $sqlRequest);
    $data = mysqli_fetch_row($result);
    $res = 0;
    if(isset($data)){
        $connexion = openDb();
        $sqlRequest = "SELECT `quantite` FROM `ligne_frais_forfait` WHERE `id_visiteur` = ".$idUser." AND `mois` = ".$mois." AND id_frais_forfait='".$etat."';";
        $result = mysqli_query($connexion, $sqlRequest);
        $res = array();
        $res['Qte'] = 0;
        $res['Montant'] = 0;
        while ($data = mysqli_fetch_row($result)) {
            $res['Qte'] += $data[0];
            $res['Montant'] += calculMontant($etat,$data[0]);
        }
        mysqli_free_result($result);
        closeDb($connexion);
    }
    return $res;
}

function obtenirDernierMoisSaisi() {
    $connexion = openDb();
    $requete = "select max(mois) as dernierMois from fiche_frais where id_visiteur='" . $_SESSION['Id'] . "'";
    $idJeuRes = mysqli_query($connexion,$requete);
    $dernierMois = false ;
    if ( $idJeuRes ) {
        $ligne = mysqli_fetch_assoc($idJeuRes);
        $dernierMois = $ligne["dernierMois"];
        mysqli_free_result($idJeuRes);
    }
    return $dernierMois;
}

function obtenirDetailFicheFrais($unMois) {

    $connexion = openDb();
    $ligne = false;
    $requete="select IFNULL(nb_justificatifs,0) as nb_justificatifs, etat.id as id_etat, libelle as libelle_etat, date_modif, montant_valide  from fiche_frais inner join etat on id_etat = etat.id where id_visiteur='" . $_SESSION['Id'] . "' and mois='" . $unMois . "'";
    $idJeuRes = mysqli_query($connexion, $requete);
    if ( $idJeuRes ) {
        $ligne = mysqli_fetch_assoc($idJeuRes);
    }
    mysqli_free_result($idJeuRes);

    return $ligne ;
}

function modifierEtatFicheFrais($unMois, $unIdVisiteur, $unEtat) {
    $connexion = openDb();
    $requete = "update fiche_frais set id_etat = '" . $unEtat . "', date_modif = now() where id_visiteur ='" . $unIdVisiteur . "' and mois = '". $unMois . "'";
    $stmt = mysqli_prepare($connexion,$requete);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    closeDb($connexion);
} 

function FicheFrais($fiche2, $bool){
    if ($bool) {
        $connexion = openDb();
        $mois = obtenirDernierMoisSaisi();
        $Mois = (int)date('m');
        if($mois == $Mois-1){
            $sqlRequest = "UPDATE `fiche_frais` SET `nb_justificatifs`=" . $fiche2['nb_justificatifs'] . ",`montant_valide`+=" . calculMontant($fiche2["Type_Frais"], $fiche2['Qte']) . ",`date_modif`=" . date("Y-m-d") . " WHERE 1";
        }
        if($mois == ($Mois-2)){
            $sqlRequest = "INSERT INTO `fiche_frais`(`id_visiteur`, `mois`, `nb_justificatifs`, `montant_valide`, `date_modif`, `id_etat`) VALUES (".$_SESSION['Id'].",".$Mois.",". 0 .",". calculMontant($fiche2["Type_Frais"], $fiche2['Qte']) .",". date("Y-m-d") .",'CR')";
        }
        mysqli_query($connexion, $sqlRequest);
        closeDb($connexion);
    }
    if ($bool == false){
        $connexion = openDb();
        $Mois = (int)date('m');
        $mois = obtenirDernierMoisSaisi();
        if($mois == $Mois-1){
            $sqlRequest = "UPDATE `fiche_frais` SET `nb_justificatifs`=" . $fiche2['nb_justificatifs'] . ",`montant_valide`+=" . $fiche2['Montant'] . ",`date_modif`=" . date("Y-m-d") . " WHERE 1";
        }
        if($mois == ($Mois-2)){
            $sqlRequest = "INSERT INTO `fiche_frais`(`id_visiteur`, `mois`, `nb_justificatifs`, `montant_valide`, `date_modif`, `id_etat`) VALUES (".$_SESSION['Id'].",".$Mois.",". 0 .",". $fiche2['Montant'] .",". date("Y-m-d") .",'CR')";
        }
        mysqli_query($connexion, $sqlRequest);
        closeDb($connexion);
    }
}

function ajouterFicheFrais($fiche2) {

    $connexion = openDb();
    $mois = (int)date('m')-1;
    // modification de la dernière fiche de frais du visiteur
    $dernierMois = obtenirDernierMoisSaisi();
    $laDerniereFiche = obtenirDetailFicheFrais($dernierMois);
    if ( is_array($laDerniereFiche) && $laDerniereFiche['id_etat']=='CR'){
        modifierEtatFicheFrais($dernierMois,'CL', $_SESSION['Id']);
    }

    $sqlRequest = "INSERT INTO ligne_frais_forfait(id_visiteur, mois, id_frais_forfait, quantite, description, date_depense) VALUES (". $_SESSION['Id'] .",". $mois .",'". $fiche2['Type_Frais'] ."',". $fiche2['Qte'] .",'". $fiche2['Desc'] ."','". $fiche2['Date_Depense'] ."' )";
    $stmt = mysqli_prepare($connexion,$sqlRequest);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    closeDb($connexion);
    FicheFrais($fiche2, true);
}

function existeFicheFrais() {
    $connexion = openDb();
    $mois = (int)date('m')-1;
    $sqlRequest = "select id_visiteur from fiche_frais where id_visiteur='" . $_SESSION['Id'] ."' and mois='" . $mois . "'";
    $idJeuRes = mysqli_query($connexion, $sqlRequest);
    $ligne = false ;
    if ( $idJeuRes ) {
        $ligne = mysqli_fetch_assoc($idJeuRes);
        mysqli_free_result($idJeuRes);
    }

    // si $ligne est un tableau, la fiche de frais existe, sinon elle n'exsite pas
    return is_array($ligne) ;
}

function insertFiche($fiche, $fiche2, $isForfait){
    /**
     * Insert une fiche de frais puis une fiche de frais {Forfait / Hors Forfait}
     * $fiche => Fiche frais
     * $fiche2 => Fiche frais forfait / Fiche frais hors-frofait
     * $isForfait => Booleen qui indique si Fiche_Forfait { True } / Fiche_Hors_Forfait { False }
    */
    $verif = isInYear($fiche['Date']);
    $mois = (int)date('m')-1;

    if ($verif){
        /**
         * Insertion dans fiche_frais soit la fiche de frais principale
         */
        $connexion = openDb();
        $sqlRequest = "INSERT INTO fiche_frais(`id_visiteur`, mois, montant_valide, date_modif, id_etat) VALUES (". $_SESSION['Id'] .", ". $mois .", ". $fiche['MontantValide'] .", '". $fiche['Date'] ."', 'CR') ";
        $stmt = mysqli_prepare($connexion,$sqlRequest);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        closeDb($connexion);
    }

    if ($verif && $isForfait){
        /**
         * Insertion dans frais forfaitisée
         * Dépends du booléen !
         * */
        ajouterFicheFrais($fiche2);
    }
    if ($verif && $isForfait == false){
        /**
         * Insertion dans frais hors forfait
         * Dépends du booléen !
         */
        $connexion = openDb();
        $sqlRequest = "INSERT INTO `ligne_frais_hors_forfait`(`id_visiteur`, `mois`, `libelle`, `date_frais_hors_forfait`, `montant`) VALUES ( ". $_SESSION['Id'] .",". $mois .",'". $fiche2['Desc'] ."','". $fiche2['Date_Depense'] ."',". $fiche2['Montant'] ." );";
        
        $stmt = mysqli_prepare($connexion,$sqlRequest);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        closeDb($connexion);
        FicheFrais($fiche2, false);
    }
}

function isInYear($date) {
    /**
     * La doc est vraiment nécessaire ici ?
     * Check si $date est dans l'année en cours
     * ps: on sait jamais hein, Jocelyn :)
    */
    $today = date("Y-m-d");
    $dateYearBefore = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y") - 1));
    return ($date >= $dateYearBefore) && ($date <= $today);
}

function DisplayEtape(){
    $connexion = openDb();
    $sqlRequest = "SELECT * FROM frais_forfait";
    $result = mysqli_query($connexion, $sqlRequest);
    $res = array();
    $i = 0;
    while ($data = mysqli_fetch_row($result)) {
        $res[$i]['Id'] = $data[0];
        $res[$i]['Libelle'] = $data[1];
        $res[$i]['Qte'] = $data[2];
        $i++;
    }
    mysqli_free_result($result);
    closeDb($connexion);
    return $res;
}
?>