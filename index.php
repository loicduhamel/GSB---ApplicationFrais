<?php
require 'pdo/Access.php';

$message = '';

if (isset($_POST['Connexion']) && $_POST['Connexion'] == 'Connexion'){
    $Name = $_POST['login'];
    $Mdp = $_POST['mdp'];
    $options = [
        'cost' => 12,
    ];
    $_mdpHash = password_hash($Mdp, PASSWORD_BCRYPT, $options);
    $verif = array();
    $verif = connectUser($Name, $_mdpHash);
    if ($verif['Bool'] == true) {

        session_start(); 

        $_SESSION['login'] = $_POST['login'];
        $_SESSION['Nom'] = $verif['Nom'];
        $_SESSION['Prenom'] = $verif['Prenom'];
        $_SESSION['Id'] = $verif['Id'];
        $_SESSION['Rang'] = $verif['Rang'];

        if ($verif['Rang'] == 3) {
            header('Location: http://localhost:8888/ApplicationFrais/visiteur_accueil.php');
            exit();
        }
        elseif ($verif['Rang'] == 2){
            header('Location: http://localhost:8888/ApplicationFrais/comptable_accueil.php');
            exit();
        }
        elseif ($verif['Rang'] == 1){
            header('Location: http://localhost:8888/ApplicationFrais/administrateur_accueil.php');
            exit();
        }
    }
    else {

        $message = '<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog">
                      <div class="modal-dialog " role="document">
                        <div class="modal-body alert alert-danger">
                          <button type="button" class="close" data-dismiss="modal" rel="modal:close" aria-label="Close">
                            <span aria-hidden="true" onclick="">&times;</span>
                          </button>
                          <p>L\'identifiant ou le mot de passe est incorrect !<br/> Veuillez réessayer.</p>
                        </div>
                      </div>
                     </div>';
    }
}
elseif (isset($_POST['Inscription']) && $_POST['Request'] == 'Inscription'){
    $user = array();
    $user['Nom'] = $_POST['Nom'];
    $user['Prenom'] = $_POST['Prenom'];
    $user['Adresse'] = $_POST['Adresse'];
    $user['Ville'] = $_POST['Ville'];
    $user['dateembauche'] = $_POST['dateembauche'];
    $user['CP'] = $_POST['CP'];
    $user['Tel'] = $_POST['Tel'];
    $user['Email'] = $_POST['Email'];
    if($_POST['Mdp'] == $_POST['Mdp_conf'] && sizeof($_POST['Mdp']) == sizeof($_POST['Mdp_conf'] && !empty($_POST['Mdp']) && !empty($_POST['Mdp_conf']) )){
        $user['Mdp'] = $_POST['Mdp'];
    }
    else{
        ?>
        <script>
            alert("Les mots de passe ne correspondent pas !");
        </script>
        <?php
    }

    if (true){
        insertUser($user);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>GSB - Application Frais</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="shortcut icon" href="img/favicon.ico">
		<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
		<link href="css/style.css" rel="stylesheet" type="text/css"/>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#exampleModal').modal('show');
                $(".close").modal('hide');
            });
        </script>
	</head>

	<body>
		
		<div class="container">
			<div class="wrapper">
                <div class="error">
                    <?php echo $message; ?>
                </div>
				<form name="Connexion" action="index.php" method="post" class="login-form">
					<a href="index.php"><img class="logo" src="img/logo.png" alt="GSB"/></a>
					<h3 class="title">Application Frais</h3>
					<div class="form-group">
						<input type="text" name="login" class="form-control" id="form-connect" placeholder="Utilisateur" required="">
						<input type="password" name="mdp" class="form-control" id="form-connect" placeholder="Mot de passe" required="">
					</div>
					<a class="mdp-missing" data-toggle="modal" data-target="#Modalmdp" href="">Mot de passe oublié ?</a></br>
					<button type="submit" class="btn btn-primary" name="Connexion" value="Connexion" style="margin-top:3%; position:absolute; left:20%;">Connexion</button>
					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#Modalinscription" style="margin-top:3%; position:absolute; left:52%;">Inscription</button>
				</form>
			</div>
		</div>

		<div class="modal fade" id="Modalinscription" tabindex="-1" role="dialog" aria-labelledby="Modalinscription" aria-hidden="true">
            <form action="" method="post" name="Inscription">
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
                                <input type="text" name="Nom" class="form-control" aria-describedby="Nom" placeholder="Nom" required="">
                                <input type="text" name="Request" class="form-control" aria-describedby="Inscription" value="Inscription" style="display: none;">
                                <input type="text" name="Prenom" class="form-control" aria-describedby="Prénom" placeholder="Prénom" required="">
                                <input type="text" name="Adresse" class="form-control" aria-describedby="Adresse" placeholder="Adresse" required="">
                                <input type="text" name="Ville" class="form-control" aria-describedby="Ville" placeholder="Ville" required="" style="width: 65%; display: inline-block;">
                                <input type="text" name="CP" class="form-control" aria-describedby="CP" placeholder="CP" required="" style="width: 30%; display: inline-block; float: right;">
                                <input type="text" name="Tel" class="form-control" aria-describedby="N° de téléphone" placeholder="N° de téléphone">
                                <input type="text" name="Email" class="form-control" aria-describedby="Adresse e-mail" placeholder="Adresse e-mail" required="">
                                <input type="password" name="Mdp" class="form-control" aria-describedby="Mot de passe" placeholder="Mot de passe" required="">
                                <input type="password" name="Mdp_conf" class="form-control" aria-describedby="Confirmation du mot de passe" placeholder="Confirmation du mot de passe" required="">
                                <input type="text" onfocus="(this.type='date')" class="form-control" name="dateembauche" placeholder="Date d'embauche" required=""/>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" name="Inscription" class="btn btn-primary">Je m'incris</button>
                        </div>
                    </div>
                </div>
            </form>
		</div>

		<div class="modal fade" id="Modalmdp" tabindex="-1" role="dialog" aria-labelledby="Modalmdp" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title" style="margin-left:22%;">Mot de passe oublié</h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
				<form>
					<div class="form-group">
						<p>Veuillez entrer une adresse e-mail valide pour recevoir votre nouveau mot de passe.</p>
						<input type="text" class="form-control" aria-describedby="Adresse e-mail" placeholder="Adresse e-mail" required="">
					</div>
				</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Valider</button>
					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript" src="plugins/jquery.min.js"></script>
		<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

	</body>
</html>