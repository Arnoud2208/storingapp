<?php
session_start(); // nodig voor het opslaan van errors

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Controleer of het formulier is ingediend
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Variabelen veilig vullen
    $attractie  = trim($_POST['attractie']  ?? '');
    $type       = trim($_POST['type']       ?? '');
    $capaciteit = trim($_POST['capaciteit'] ?? '');
    $melder     = trim($_POST['melder']     ?? '');

    // Validatie
    $errors = [];

    if ($attractie === '') {
        $errors[] = "Attractie mag niet leeg zijn.";
    }

    if ($type === '') {
        $errors[] = "Type mag niet leeg zijn.";
    }

    if ($capaciteit === '' || !is_numeric($capaciteit)) {
        $errors[] = "Capaciteit moet een geldig getal zijn.";
    }

    if ($melder === '') {
        $errors[] = "Melder mag niet leeg zijn.";
    }

    // Als er fouten zijn, sla ze op en ga terug
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = [
            'attractie'  => $attractie,
            'type'       => $type,
            'capaciteit' => $capaciteit,
            'melder'     => $melder
        ];

        // Terug naar formulierpagina
        header("Location: /pad/naar/formulier.php");
        exit;
    }

    // Verbinding
    require_once __DIR__ . '/../../../config/conn.php';

    // Query
    $sql = "INSERT INTO meldingen (attractie, type, capaciteit, melder)
            VALUES (:attractie, :type, :capaciteit, :melder)";

    $stmt = $conn->prepare($sql);

    try {
        $stmt->execute([
            ':attractie'  => $attractie,
            ':type'       => $type,
            ':capaciteit' => $capaciteit,
            ':melder'     => $melder
        ]);

        $_SESSION['success'] = "Gegevens succesvol opgeslagen!";
        header("Location: /pad/naar/formulier.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION['errors'] = ["Fout bij opslaan: " . $e->getMessage()];
        header("Location: /pad/naar/formulier.php");
        exit;
    }

} else {
    echo "Formulier niet correct ingediend.";
}
