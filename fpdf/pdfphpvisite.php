<?php
require('fpdf.php');
require '../pdo/Access.php';
session_start();

$cumul = 0;

$tabUser = getUserById("".$_SESSION['Id']."");
$dataETP = selectFicheByType("".$_SESSION['Id']."",'ETP',$_GET['mois']);
$dataKM = selectFicheByType("".$_SESSION['Id']."",'KM',$_GET['mois']);
$dataNUI = selectFicheByType("".$_SESSION['Id']."",'NUI',$_GET['mois']);
$dataREP = selectFicheByType("".$_SESSION['Id']."",'REP',$_GET['mois']);

$cumul += $dataETP['Montant'];
$cumul += $dataKM['Montant'];
$cumul += $dataNUI['Montant'];
$cumul += $dataREP['Montant'];

class PDF extends FPDF
{
// En-tête
    function Header()
    {
        // Logo si tu le met la page se charge tt seul mais ne le fichier
        // pdf lui ne se télécharge pas a par si t'enlève l'image
        $this->Image('../img/logo.png',10,6,30);

        // Police Arial gras 15
        $this->SetFont('Arial','B',15);
        // Décalage à droite
        $this->Cell(80);
        // Titre
        $this->Cell(30,10,'GSB',1,0,'C');
        // Saut de ligne
        $this->Ln(20);
    }

// Pied de page
    function Footer()
    {
        // Positionnement à 1,5 cm du bas
        $this->SetY(-15);
        // Police Arial italique 8
        $this->SetFont('Arial','I',8);
        // Numéro de page
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }

}

// Instanciation de la classe dérivée
$pdf = new PDF();


$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
$pdf->Cell(40,10,utf8_decode('Nom : '.$tabUser['Nom']),0,1);
$pdf->Cell(40,10,utf8_decode('Prénom : '.$tabUser['Prenom']),0,1);
$pdf->Cell(40,10,utf8_decode('Adresse : '.$tabUser['Adresse'].', '.$tabUser['CodePostale'].' '.$tabUser['Ville']),0,1);
$pdf->Cell(40,10,utf8_decode('Téléphone : '.$tabUser['Telephone']),0,1);
$pdf->Cell(40,10,utf8_decode('Email : '.$tabUser['Email']),0,1);

//synthèse du mois
$pdf->SetFont("Arial","B",16);
$pdf->Text(10,100,utf8_decode("Synthèse du mois"),0,1);
$pdf->SetFont('Times','',13);
$pdf->Text(50,120,utf8_decode("Forfait étape"),0,1);
$pdf->Text(80,120,utf8_decode("Frais kilométrique"),0,1);
$pdf->Text(120,120,utf8_decode("Nuitée hôtel"),0,1);
$pdf->Text(150,120,utf8_decode("Repas resautant"),0,1);
$pdf->Text(10,140,utf8_decode("Quantité totale"),0,1);
$pdf->Text(10,155,utf8_decode("Montant total"),0,1);

$pdf->SetFont('Times','',12);
$pdf->Text(55,140,utf8_decode($dataETP['Qte']),0,1);
$pdf->Text(90,140,utf8_decode($dataKM['Qte']),0,1);
$pdf->Text(130,140,utf8_decode($dataNUI['Qte']),0,1);
$pdf->Text(160,140,utf8_decode($dataREP['Qte']),0,1);

$pdf->Text(55,155,utf8_decode($dataETP['Montant']),0,1);
$pdf->Text(90,155,utf8_decode($dataKM['Montant']),0,1);
$pdf->Text(130,155,utf8_decode($dataNUI['Montant']),0,1);
$pdf->Text(160,155,utf8_decode($dataREP['Montant']),0,1);

$pdf->Text(10,175,utf8_decode("Total des frais forfaitisé engagés pour le mois :"),0,1);
$pdf->Text(92,175,utf8_decode($cumul),0,1);

//Détails du mois
$pdf->SetFont("Arial", "B", 16);
$pdf->Text(10, 200, utf8_decode("Détails du mois"), 0, 1);
$pdf->SetFont('Times', '', 13);
$pdf->Text(10, 220, utf8_decode("Date"), 0, 1);
$pdf->Text(40, 220, utf8_decode("Type de frais"), 0, 1);
$pdf->Text(70, 220, utf8_decode("Description"), 0, 1);
$pdf->Text(160, 220, utf8_decode("Quantité"), 0, 1);

$data = selectFiche($_SESSION['Id'],false,$_GET['mois']);
$a = 240;

foreach ($data as $tab) {

    $pdf->SetFont('Times', '', 12);
    $pdf->Text(10, $a, utf8_decode($tab['Date_Dep']), 0, 1);
    $pdf->Text(45, $a, utf8_decode($tab['Id_Type']), 0, 1);
    $pdf->Text(70, $a, utf8_decode($tab['Desc']), 0, 1);
    $pdf->Text(165, $a, utf8_decode($tab['Qte']), 0, 1);
$a+=10;
}


$pdf->Output();

$pdf = 'ton_fichier.pdf';

// Création des headers, pour indiquer au navigateur qu'il s'agit d'un fichier à télécharger
header('Content-Transfer-Encoding: binary'); //Transfert en binaire (fichier)
header('Content-Disposition: attachment; filename="document.pdf"'); //Nom du fichier
header('Content-Length: ' . filesize($fichier)); //Taille du fichier

//Envoi du fichier dont le chemin est passé en paramètre
readfile($fichier);


