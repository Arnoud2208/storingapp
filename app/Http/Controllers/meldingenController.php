<?php

// Variabelen vullen
$attractie  = $_POST['attractie'];
$capaciteit = $_POST['capaciteit'];
$melder     = $_POST['melder'];

echo $attractie . " / " . $capaciteit . " / " . $melder;

// 1. Verbinding
require_once '../../../config/conn.php';

// 2. Query
$sql = "INSERT INTO meldingen (attractie, capaciteit, melder)
        VALUES (:attractie, :capaciteit, :melder)";

// 3. Prepare
$stmt = $conn->prepare($sql);

// 4. Execute
$stmt->execute([
    ':attractie'  => $attractie,
    ':capaciteit' => $capaciteit,
    ':melder'     => $melder
]);

echo "Gegevens succesvol opgeslagen!";
?>

