<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('phpqrcode/qrlib.php');  // Assurez-vous d'utiliser le chemin correct pour la bibliothèque PHP QR Code

// Initialisation de la variable pour le chemin du fichier QR code
$qrCodePath = null;

if (isset($_POST['generate'])) {
    $data = $_POST['data'];

    if (!empty($data)) {
        // Nom du fichier et chemin de stockage
        $fileName = 'qrcode.png';
        $filePath = 'temp/' . $fileName;

        // Créer le répertoire temporaire si nécessaire
        if (!is_dir('temp')) {
            mkdir('temp', 0755);  // Créer le dossier si nécessaire, et avec les bonnes permissions
            echo "Dossier 'temp' créé.<br>";
        }

        // Générer le QR Code avec une taille plus grande (par exemple, taille 10)
        $result = QRcode::png($data, $filePath, QR_ECLEVEL_L, 10);  // Taille du QR Code

        // Vérifier si le fichier a bien été créé
        if (file_exists($filePath)) {
            $qrCodePath = $filePath;  // Définir le chemin du fichier pour l'affichage
            echo "QR Code généré avec succès!<br>";
        } else {
            echo "Erreur : Le fichier QR Code n'a pas été généré.<br>";
        }
    } else {
        echo "Erreur : Le champ de texte ou URL est vide.<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Générateur de Code QR</title>
    <link rel="stylesheet" href="style.css"> <!-- Si vous utilisez un fichier CSS externe -->
    <style>
        /* Optionnel : Pour ajuster l'affichage du QR code */
        img {
            max-width: 100%;  /* Pour s'assurer que l'image ne dépasse pas la largeur du conteneur */
            width: 500px;     /* Taille définie pour l'affichage (vous pouvez ajuster) */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Générateur de Code QR</h1>
        <form action="index.php" method="POST">
            <label for="data">Entrez un texte ou une URL :</label>
            <input type="text" name="data" id="data" placeholder="Texte ou URL" required>
            <button type="submit" name="generate">Générer le Code QR</button>
        </form>

        <!-- Affichage du code QR après génération -->
        <?php if ($qrCodePath && file_exists($qrCodePath)): ?>
            <div>
                <img src="<?= $qrCodePath ?>" alt="Code QR">
                <br>
                <a href="<?= $qrCodePath ?>" download="qr_code.png">Télécharger le Code QR</a>
            </div>
        <?php elseif (isset($_POST['generate'])): ?>
            <p>Erreur : Le QR code n'a pas pu être généré.</p>
        <?php endif; ?>
    </div>
</body>
</html>
