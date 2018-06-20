<?php
require 'pdo/Access.php';

session_start();

if (empty($_SESSION) || $_SESSION['Rang'] != 2){
    header('Location: http://localhost:8888/ApplicationFrais/');
}
unset($_POST);
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>GSB - Application Frais - Consultation des frais</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="shortcut icon" href="img/favicon.ico">
		<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
	</head>

	<body> 

        <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
            <a class="navbar-brand" href="comptable_accueil.php">
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
                        <a class="nav-link" href="comptable_accueil.php">Accueil</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="comptable_consultation_frais.php">Consultation des frais <span class="sr-only">(current)</span></a>
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
                    <h1 class="title-page">Consultation des frais</h1>
                        <div class="table-responsive">
                            <form id="test" action="comptable_validation_frais.php" method="post">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nom</th>
                                            <th scope="col">Prénom</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $TabUser = getUserByType(3);
                                    foreach ($TabUser as $data){
                                    ?>
                                        <tr>
                                            <td><a href="comptable_validation_frais.php?mois=<?php echo (int)date('m')-1; ?>&param1=<?php echo $data['Id'] ?>"><?php echo $data['Nom'] ?></a></td>
                                            <td><a href="comptable_validation_frais.php?mois=<?php echo (int)date('m')-1; ?>&param1=<?php echo $data['Id'] ?>"><?php echo $data['Prenom'] ?></a></td>
                                            <td>
                                                <a class="icon1" href="comptable_validation_frais.php?mois=<?php echo (int)date('m')-1; ?>&param1=<?php echo $data['Id'] ?>" title="Consulter"><i class="fas fa-eye"></i></a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                </div>
            </div>
        </div>

        <script defer src="js/fontawesome/svg-with-js/js/fontawesome-all.js"></script>
		<script type="text/javascript" src="plugins/jquery.min.js"></script>
		<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

	</body>
</html>