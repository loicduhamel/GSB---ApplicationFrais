<?php

session_start();

if (empty($_SESSION) || $_SESSION['Rang'] != 3){
    header('Location: http://localhost:8888/ApplicationFrais/');
}

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>GSB - Application Frais - Accueil</title>
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
            <button type="button" class="btn btn-primary" onclick="window.location='apk/suiviAA.apk'">Télécharger l'application Android SuiviAA</button>
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#collapsingNavbar">
                <span> </span>
                <span> </span>
                <span> </span>
            </button>
            <div class="collapse navbar-collapse" id="collapsingNavbar">
            <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="visiteur_accueil.php">Accueil <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="visiteur_frais_forfaitises.php">Frais forfaitisés</a>
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


        <div id="demo" class="carousel slide" data-ride="carousel">
            <ul class="carousel-indicators">
                <li data-target="#demo" data-slide-to="0" class="active"></li>
                <li data-target="#demo" data-slide-to="1"></li>
                <li data-target="#demo" data-slide-to="2"></li>
            </ul>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="d-block w-100" src="img/Tuto slide AppliFrais1.jpg">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="img/Tuto slide AppliFrais2.jpg">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="img/Tuto slide AppliFrais3.jpg">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="img/Tuto slide AppliFrais4.jpg">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="img/Tuto slide AppliFrais5.jpg">
                </div>
            </div>
            <a class="carousel-control-prev" href="#demo" data-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </a>
            <a class="carousel-control-next" href="#demo" data-slide="next">
                <span class="carousel-control-next-icon"></span>
            </a>
        </div>

		<script type="text/javascript" src="plugins/jquery.min.js"></script>
		<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

	</body>
</html>