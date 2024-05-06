<?php
// Hier wird die Datenbankverknüpfung eingebunden
include "dbConfig.php";

// Überprüfen, ob das Formular abgeschickt wurde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Daten aus dem Formular extrahieren und vorbereiten
    $produktId = $_POST["produktId"];
    $menge = $_POST["menge"];
    $kundennummer = $_POST["kundennummer"];

    try {
        // Preis des Produkts abrufen
        $sqlPreis = "SELECT Preis FROM Artikel WHERE ANr = ?";
        $stmtPreis = $db->prepare($sqlPreis);
        $stmtPreis->bind_param("i", $produktId);
        $stmtPreis->execute();
        $resultPreis = $stmtPreis->get_result();

        // Überprüfen, ob das Produkt existiert und den Preis abrufen
        if ($resultPreis->num_rows > 0) {
            $row = $resultPreis->fetch_assoc();
            $preis = $row["Preis"];

            // Gesamtpreis berechnen
            $gesamtPreis = $preis * $menge;

            // Bestellung einfügen
            $sqlBestellung = "INSERT INTO Bestellung (KNr, Datum) VALUES (?, NOW())";
            $stmtBestellung = $db->prepare($sqlBestellung);
            $stmtBestellung->bind_param("i", $kundennummer);
            $stmtBestellung->execute();
            $bestellId = $stmtBestellung->insert_id;

            // Position einfügen
            $sqlPosition = "INSERT INTO Position (ANr, BesNr, Anzahl) VALUES (?, ?, ?)";
            $stmtPosition = $db->prepare($sqlPosition);
            $stmtPosition->bind_param("iii", $produktId, $bestellId, $menge);
            $stmtPosition->execute();

            // Erfolgsmeldung mit der Gesamtsumme
            echo "Bestellung erfolgreich! Gesamtsumme: $gesamtPreis";
        } else {
            // Fehlermeldung, wenn das Produkt nicht gefunden wurde
            echo "Produkt nicht gefunden.";
        }
    } catch (Exception $e) {
        // Fehlermeldung bei einem Fehler
        echo "Fehler beim Bestellen: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Produkt bestellen</title>
</head>
<body>
    <h1>Produkt bestellen</h1>
    <form method="POST">
        <label for="produktId">Produkt ID:</label>
        <input type="number" id="produktId" name="produktId" required><br>

        <label for="menge">Menge:</label>
        <input type="number" id="menge" name="menge" required><br>

        <label for="kundennummer">Kundennummer:</label>
        <input type="number" id="kundennummer" name="kundennummer" required><br>

        <input type="submit" value="Bestellen">
    </form>
</body>
</html>
