<?php

session_start();

require_once 'pdo/AdminCRUD.php';

if (empty($_SESSION) || $_SESSION['Rang'] != 1){
    header('Location: http://localhost:8888/ApplicationFrais/');
}
if (isset($_POST['del'])){   
    Delete($_POST['del']);
}
if (isset($_POST['edit'])){
    Edit($_POST['identifiant'],$_POST['nom'],$_POST['valeur']);
}
if (isset($_POST['Ajout'])){
    Create($_POST['IDa'],$_POST['Noma'],$_POST['Valeura']);
}
unset($_POST);

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
            <a class="navbar-brand" href="administrateur-crud.php">
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
                    <form methods="post" action="" name="Crud-Value">
                        <div class="table-responsive">
                            <h2 class="title-page3">Tableau des valeurs de frais forfaitisés</h2>
                            <a class="icon2" data-toggle="modal" data-target="#ModalAjout" style="padding-left:40%"><i class="fas fa-plus-circle"></i></a>
                            <table class="table table-striped" style="margin-top: 2%">
                                <thead>
                                    <tr>
                                        <th scope="col">Identifiant</th>
                                        <th scope="col">Nom</th>
                                        <th scope="col">Valeur</th>
                                        <th scope="col">Éditer</th>
                                        <th scope="col">Supprimer</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $data = Read();
                                        if(isset($data)){
                                            foreach($data as $tab){
                                    ?>
                                    <form method="post" action="">
                                        <tr>
                                            <td><input type="text" name="identifiant" readonly="readonly" value="<?php echo $tab['Identifiant']; ?>"></td>
                                            <td><input type="text" name="nom" value="<?php echo $tab['Nom']; ?>"></td>
                                            <td><input type="text" name="valeur" value="<?php echo $tab['Valeur']; ?>"></td>
                                            <td>
                                                <button class="icon2" type="submit" formmethod="post" name="edit" value="<?php echo $tab['Identifiant'] ?>"><i class="far fa-edit"></i></button>                                            
                                            </td>
                                            <td>
                                                <button class="icon2" type="submit" formmethod="post" name="del" value="<?php echo $tab['Identifiant'] ?>"><i class="far fa-trash-alt"></i></button>
                                            </td>
                                            
                                        </tr>
                                    </form>
                                    <?php
                                            }
                                        }else{ 
                                    ?>
                                    <tr>
                                        <td>vide</td>
                                        <td>vide</td>
                                        <td>vide</td>
                                        <td>
                                            <button class="icon2" href="#" title="Supprimer" name="del"><i class="far fa-trash-alt"></i></button>
                                        </td>
                                        <td>
                                            <button class="icon1" href="" title="Éditer"><i class="far fa-edit"></i></button>
                                        </td>
                                    </tr>
                                    <?php 
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="ModalAjout" tabindex="-1" role="dialog" aria-labelledby="Modalinscription" aria-hidden="true">
            <form action="" method="post" name="Ajout">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" style="margin-left:35%;">Inscription</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" name="IDa" class="form-control" aria-describedby="ID" placeholder="Identifiant" required="">
                                <input type="text" name="Noma" class="form-control" aria-describedby="Nom" placeholder="Libellé" required="">
                                <input type="number" name="Valeura" class="form-control" aria-describedby="Valeur" placeholder="Valeur" required="">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" name="Ajout" class="btn btn-primary">Ajouter</button>
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