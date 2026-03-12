<?php

// Variabelen vullen
$attractie = $_POST['attractie'];
$type = $_POST['type'];
$capaciteit = $_POST['capaciteit'];
$melder = $_POST['melder'];
$prioriteit = isset($_POST['prioriteit']) ? 1 : 0;
$overig = $_POST['overige_info'];

$errors = [];

// 1. attractie mag niet leeg zijn
if (empty($attractie)) {
    $errors[] = "Vul de naam van de attractie in.";
}

// 2. type mag niet leeg zijn
if (empty($type)) {
    $errors[] = "Kies een type attractie.";
}

// 3. capaciteit moet een getal zijn
if (!is_numeric($capaciteit)) {
    $errors[] = "Capaciteit moet een getal zijn.";
}

// 4. melder mag niet leeg zijn
if (empty($melder)) {
    $errors[] = "Vul de naam van de melder in.";
}

if (!empty($errors)) {
    $errorString = implode("&", array_map(fn($e) => "error[]=" . urlencode($e), $errors));
    header("Location: ../meldingen/create.php?$errorString");
    exit;
}

require_once '../../../config/conn.php';

$query = "INSERT INTO meldingen (attractie, type, capaciteit, melder, prioriteit, overige_info)
          VALUES (:attractie, :type, :capaciteit, :melder, :prioriteit, :overige_info)";

$statement = $conn->prepare($query);

$statement->execute([
    ":attractie" => $attractie,
    ":type" => $type,
    ":capaciteit" => $capaciteit,
    ":melder" => $melder,
    ":prioriteit" => $prioriteit,
    ":overige_info" => $overig
]);

header("Location: ../meldingen/index.php?msg=Melding opgeslagen");
exit;