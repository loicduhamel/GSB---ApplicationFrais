<?php
require './pdo/ficheFrais.php';

session_start();

setlocale (LC_TIME, 'fr_FR.utf8','fra');

if (empty($_SESSION) || $_SESSION['Rang'] != 3){
    header('Location: http://localhost:8888/ApplicationFrais/');
}
if(isset($_POST['AjoutFraisHF']))
{
    $orderdate = explode("-",$_POST['date_depense_fraisf']);
    $month = $orderdate[1];

    if((int)$month == (int)date("m")-1)
    {
        $fiche = array();
        $fiche['MontantValide'] = (int)$_POST['montant_fraishf'];
        $fiche['Date'] = $_POST['date_depense_fraisf'];

        $fraisf = array();
        $fraisf['Desc'] = $_POST['description_fraishf'];
        $fraisf['Date_Depense'] = $_POST['date_depense_fraisf'];
        $fraisf['Montant'] = (int)$_POST['montant_fraishf'];
        $fraisf['nb_justificatifs'] = 0;
        insertFiche($fiche, $fraisf, false);

        $message='Fiche frais hors forfait ajouté !';
        echo '<script type="text/javascript">window.alert("'.$message.'");</script>';
    }
    if((int)$month !== (int)date("m")-1)
    {
        $message='Merci de renseigner les frais du mois précédent.';
        echo '<script type="text/javascript">window.alert("'.$message.'");</script>';
    }
}if (isset($_POST['idToDel'])){
    deleteFicheF_HF($_SESSION['Id'],$_POST['idToDel'],true);

}

$mois = (int)date('m') - 1;
unset($_POST);
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>GSB - Application Frais - Frais hors forfait</title>
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
                    <li class="nav-item">
                        <a class="nav-link" href="visiteur_frais_forfaitises.php">Frais forfaitisés</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="visiteur_frais_hors_forfait.php">Frais hors forfait <span class="sr-only">(current)</span></a>
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
                    <form action="fpdf/pdfphpvisitehorsforfait.php" name="valider" >
                        <h1 class="title-page">Frais hors forfait</h1>
                        <?php
                        $tabUser = donneepdf($_SESSION['Id']);
                        ?>
                        <a class="icon2" href="http://localhost:8888/ApplicationFrais/fpdf/pdfphpvisitehorsforfait.php?mois=<?php echo $mois; ?>&id=<?php echo $_SESSION['Id'] ?>" title="Télécharger au format PDF"><i class="far fa-file-pdf"></i></a>
                        <a class="link-modal" href="" data-toggle="modal" data-target="#Modalfraihf"><p>Ajouter un frai hors forfait</p></a>

                    </form>
                    <p style="margin-left: 20px">Veuillez saisir les fiches de frais hors forfait du mois de <?php echo (strftime("%B %Y", strtotime('-1 month')));  ?></p>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Libellé</th>
                                    <th scope="col">Montant</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $data = selectFiche($_SESSION['Id'],true,$mois);
                                if(isset($data)){
                                    foreach ($data as $tab){
                            ?> 
                                <tr>
                                    <td><?php echo $tab['Date'] ?></td>
                                    <td><?php echo $tab['Desc'] ?></td>
                                    <td><?php echo $tab['Montant'] ?></td>
                                    <td>
                                        <form method="post" action="">
                                            <button class="icon2" href="" type="submit" title="Supprimer" name="idToDel" value="<?php echo $tab['Id'] ?>"><i class="far fa-trash-alt"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php }}else{
                                ?>
                                    <td>vide</td>
                                    <td>vide</td>
                                    <td>vide</td>
                                <?php 
                            } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="Modalfraihf" tabindex="-1" role="dialog" aria-labelledby="Modalinscription" aria-hidden="true">
            <form action="" method="post" name="AjoutFraisHF">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 id="modal-title" class="modal-title">Ajout frai hors forfait</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                                <div class="form-group">
                                    <input type="date" class="form-control" name="date_depense_fraisf" placeholder="Date d'embauche" required=""/>
                                    <input type="text" class="form-control" name="description_fraishf" aria-describedby="Libellé" placeholder="Libellé">
                                    <input type="text" class="form-control" name="montant_fraishf" aria-describedby="Montant" placeholder="Montant">
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="AjoutFraisHF" class="btn btn-primary">Valider</button>
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