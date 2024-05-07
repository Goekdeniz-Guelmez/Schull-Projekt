<?php
session_start(); // Startet eine neue Session oder setzt eine vorhandene fort

// Hier wird die Datenbankverknüpfung eingebunden
include "dbConfig.php";

if (!isset($_SESSION['warenkorb'])) {
    $_SESSION['warenkorb'] = array();
    $_SESSION['gesamtsumme'] = 0;
}

$productNotFound = false; // Flag für nicht gefundenes Produkt

// Überprüfen, ob das Formular abgeschickt wurde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['speichern'])) {
        $produktId = $_POST["produktId"];
        $menge = $_POST["menge"];
        // Artikelinformationen aus der Datenbank abrufen
        $sql = "SELECT Bezeichnung, Preis FROM Artikel WHERE ANr = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $produktId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $gesamtPreis = $row['Preis'] * $menge;
            // Artikel und Menge in der Session speichern
            $_SESSION['warenkorb'][] = array('produktId' => $produktId, 'Bezeichnung' => $row['Bezeichnung'], 'menge' => $menge, 'gesamt' => $gesamtPreis);
            $_SESSION['gesamtsumme'] += $gesamtPreis;
        } else {
            $productNotFound = true;
        }
    } elseif (isset($_POST['entfernen'])) {
        // Produkt aus dem Warenkorb entfernen
        $indexToRemove = $_POST['index'];
        if (isset($_SESSION['warenkorb'][$indexToRemove])) {
            $_SESSION['gesamtsumme'] -= $_SESSION['warenkorb'][$indexToRemove]['gesamt'];
            array_splice($_SESSION['warenkorb'], $indexToRemove, 1);
        }
    } elseif (isset($_POST['bestellen']) && !empty($_SESSION['warenkorb'])) {
        $kundennummer = $_POST["kundennummer"];  // Kundennummer aus dem separaten Formular
        // Bestellung in die Datenbank einfügen
        $db->begin_transaction();
        try {
            $sqlBestellung = "INSERT INTO Bestellung (KNr, Datum) VALUES (?, NOW())";
            $stmtBestellung = $db->prepare($sqlBestellung);
            $stmtBestellung->bind_param("i", $kundennummer);
            $stmtBestellung->execute();
            $bestellId = $stmtBestellung->insert_id;

            foreach ($_SESSION['warenkorb'] as $item) {
                $sqlPosition = "INSERT INTO Position (ANr, BesNr, Anzahl) VALUES (?, ?, ?)";
                $stmtPosition = $db->prepare($sqlPosition);
                $stmtPosition->bind_param("iii", $item['produktId'], $bestellId, $item['menge']);
                $stmtPosition->execute();
            }

            $db->commit();
            displayWarenkorb(); // Zeige den Warenkorb mit der Bestellung an
            $_SESSION['warenkorb'] = array();
            $_SESSION['gesamtsumme'] = 0;
            echo "<script>alert('Bestellung erfolgreich aufgegeben!');</script>";
        } catch (Exception $e) {
            $db->rollback();
            echo "<script>alert('Fehler beim Bestellen: " . $e->getMessage() . "');</script>";
        }
    }
}

function displayWarenkorb() {
    if (!empty($_SESSION['warenkorb'])) {
        echo "<h3>Warenkorb:</h3>";
        echo "<ul>";
        foreach ($_SESSION['warenkorb'] as $index => $item) {
            echo "<li>Produkt ID: " . $item['produktId'] . ", Produktname: " . $item['Bezeichnung'] . ", Menge: " . $item['menge'] . ", Gesamt: " . $item['gesamt'] . "€ ";
            echo "<form method='POST' style='display: inline;'><input type='hidden' name='index' value='$index'><input type='submit' name='entfernen' value='Entfernen'></form></li>";
        }
        echo "</ul>";
        echo "<h3>Gesamtsumme: " . $_SESSION['gesamtsumme'] . "€</h3>";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Produkt bestellen</title>
    <script>
        window.onload = function() {
            <?php if ($productNotFound) { ?>
            alert("Produkt nicht gefunden.");
            <?php } ?>
        };
    </script>
</head>
<body>
    <h1>Produkt bestellen</h1>
    <form method="POST">
        <label for="produktId">Produkt ID:</label>
        <input type="number" id="produktId" name="produktId" required><br>

        <label for="menge">Menge:</label>
        <input type="number" id="menge" name="menge" required><br>

        <input type="submit" name="speichern" value="Speichern">
    </form>
    <form method="POST">
        <label for="kundennummer">Kundennummer:</label>
        <input type="number" id="kundennummer" name="kundennummer" required><br>

        <input type="submit" name="bestellen" value="Bestellen" onclick="return confirm('Sind Sie sicher, dass Sie die Bestellung aufgeben möchten?');">
    </form>

    <?php displayWarenkorb(); ?>
</body>
</html>
