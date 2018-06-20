<?php
require 'pdo/ficheFrais.php';

session_start();
setlocale (LC_TIME, 'fr_FR.utf8','fra');

$id_vis = $_SESSION['Id'];
$cumul = 0;
$mois = (int)date('m') - 1;

if (empty($_SESSION) || $_SESSION['Rang'] != 3){
    header('Location: http://localhost:8888/ApplicationFrais/');
}
if (isset($_POST['AjoutFraisF'])){

    $orderdate = explode("-",$_POST['Date_Depense']);
    $month = $orderdate[1];
    if((int)$month == (int)date("m")-1 && $_POST['Type_Frais'] !== "NULL") {
        // $fiche2 contient la fiche de frais forfaitisé
        $fiche2 = array();
        $fiche2['Type_Frais'] = $_POST['Type_Frais'];
        $fiche2['Qte'] = (int)$_POST['Qte'];
        $fiche2['Desc'] = $_POST['Description'];
        $fiche2['Date_Depense'] = $_POST['Date_Depense'];
        $fiche2['nb_justificatifs'] = 0;

        // $fiche contient la fiche de frais
        $fiche = array();
        $fiche['MontantValide'] = calculMontant($fiche2['Type_Frais'], $fiche2['Qte']);
        $fiche['Date'] = $_POST['Date_Depense'];

        insertFiche($fiche, $fiche2, true);
    }
    if((int)$month !== (int)date("m")-1)
    {
        $message='Merci de renseigner les frais du mois précédent.';
        echo '<script type="text/javascript">window.alert("'.$message.'");</script>';
    }
    if ($_POST['Type_Frais'] == "NULL"){
        $message='Veuillez sélectionner un type de frais.';
        echo '<script type="text/javascript">window.alert("'.$message.'");</script>';
    }
}

if (isset($_POST['idToDel'])){
    deleteFicheF_HF($_SESSION['Id'],$_POST['idToDel'],false);

}

unset($_POST);
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>GSB - Application Frais - Frais forfaitisés</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="shortcut icon" href="img/favicon.ico">
		<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
    </head>

	<body>
        <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
            <a class="navbar-brand" href="visiteur_accueil.php">
                <img class="logo-nav" src="img/logo.png" alt="GSB">
            </a>
            <p class="app-frais">Application Frais</p>
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#collapsingNavbar">
                <span> </span>
                <span> </span>
                <span> </span>
            </button>
            <div class="collapse navbar-collapse" id="collapsingNavbar">
            <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="visiteur_accueil.php">Accueil</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="visiteur_frais_forfaitises.php">Frais forfaitisés <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="visiteur_frais_hors_forfait.php">Frais hors forfait</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo $_SESSION['Nom'].' '.$_SESSION['Prenom'] ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="pdo/Disconnect.php">Déconnexion</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container">
            <div class="row">
                <div class="white-zone2">
                <form action="fpdf/pdfphpvisite.php" name="valider" >
                        <h1 class="title-page">Frais forfaitisés</h1>

                        <?php
                        $tabUser = donneepdf($_SESSION['Id']);

                        ?>
                        <a class="icon2" href="http://localhost:8888/ApplicationFrais/fpdf/pdfphpvisite.php?mois=<?php echo $mois; ?>&id=<?php echo $_SESSION['Id'] ?>" title="Télécharger au format PDF"><i class="far fa-file-pdf"></i></a>
                        <a class="link-modal" href="" data-toggle="modal" data-target="#Modalfraif"><p>Ajouter un frai forfaitisé</p></a>

                    </form>
                    <p style="margin-left: 20px">Veuillez saisir les fiches de frais forfaitisé du mois de <?php echo (strftime("%B %Y", strtotime('-1 month')));  ?></p>
                        <div class="table-responsive">
                            <h2 class="title-page3">Synthèse du mois</h2>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Forfait étape</th>
                                        <th scope="col">Frais kilométrique</th>
                                        <th scope="col">Nuitée hôtel</th>
                                        <th scope="col">Repas restaurant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <tr>
                                            <td>Quantité totale</td>
                                            <td>
                                                <?php
                                                    $data = selectFicheByType($_SESSION['Id'],'ETP', $mois);
                                                    echo $data['Qte'];
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $data = selectFicheByType($_SESSION['Id'],'KM', $mois);
                                                    echo $data['Qte'];
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $data = selectFicheByType($_SESSION['Id'],'NUI', $mois);
                                                    echo $data['Qte'];
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $data = selectFicheByType($_SESSION['Id'],'REP', $mois);
                                                    echo $data['Qte'];
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Montant total</td>
                                            <td>
                                                <?php
                                                $data = selectFicheByType($_SESSION['Id'],'ETP', $mois);
                                                $cumul += $data['Montant'];
                                                echo $data['Montant'];
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $data = selectFicheByType($_SESSION['Id'],'KM', $mois);
                                                $cumul += $data['Montant'];
                                                echo $data['Montant'];
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $data = selectFicheByType($_SESSION['Id'],'NUI', $mois);
                                                $cumul += $data['Montant'];
                                                echo $data['Montant'];
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $data = selectFicheByType($_SESSION['Id'],'REP', $mois);
                                                $cumul += $data['Montant'];
                                                echo $data['Montant'];
                                                ?>
                                            </td>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive">
                            <p>Total des frais forfaitisés engagés pour le mois : <?php echo $cumul ?> € </p>
                        </div>
                        <div class="table-responsive">
                            <h2 class="title-page3">Détails du mois</h2>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Date</th>
                                        <th scope="col">Type de frais</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Quantité</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $data = selectFiche($_SESSION['Id'],false ,$mois);
                                if(isset($data)){
                                    foreach ($data as $tab){
                                    ?>
                                    <tr>
                                        <td><?php echo $tab['Date_Dep'] ?></td>
                                        <td><?php echo $tab['Id_Type'] ?></td>
                                        <td><?php echo $tab['Desc'] ?></td>
                                        <td><?php echo $tab['Qte'] ?></td>
                                        <td>
                                            <form method="post" action="">
                                                <button class="icon2" href="" type="submit" title="Supprimer" name="idToDel" value="<?php echo $tab['Id'] ?>"><i class="far fa-trash-alt"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php }
                                }else{ ?>
                                    <td>vide</td>
                                    <td>vide</td>
                                    <td>vide</td>
                                    <td>vide</td>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
        </div>

		<div class="modal fade" id="Modalfraif" tabindex="-1" role="dialog" aria-labelledby="Modalinscription" aria-hidden="true">
            <form action="" method="post" name="AjoutFraisF">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 id="modal-title" class="modal-title">Ajout frai forfaitisé</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <select class="select-frais" name="Type_Frais" size="1">
                                    <option value="NULL" selected>Type de Frais
                                    <?php 
                                        $data = DisplayEtape();
                                        foreach($data as $tab){
                                            ?>
                                            <option value="<?php echo $tab["Id"] ?>"><?php echo $tab["Libelle"] ?>
                                            <?php
                                        }
                                    ?>
                                    
                                </select>
                                <input type="date" class="form-control" name="Date_Depense" placeholder="Date d'embauche" required=""/>
                                <input type="text" class="form-control" name="Description" aria-describedby="Description" placeholder="Description">
                                <input type="number" min="0" step="1" class="form-control" name="Qte" aria-describedby="Quantité" placeholder="Quantité">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="AjoutFraisF" class="btn btn-primary">Valider</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <script defer src="js/fontawesome/svg-with-js/js/fontawesome-all.js"></script>
		<script type="text/javascript" src="plugins/jquery.min.js"></script>
		<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

	</body>
</html>