<?php
// Starten der Session, um Sitzungsdaten zu speichern
session_start();
include "dbConfig.php"; // Einbinden der Datenbankkonfigurationsdatei

// Abrufen der Administratorkundennummer nur einmal pro Sitzung, falls noch nicht geschehen
if (!isset($_SESSION['adminKNr'])) {
    // SQL-Abfrage, um die Kundennummer des Administrators zu erhalten
    $sqlAdmin = "SELECT KNr FROM Kunde WHERE Vorname = 'admin'";
    $result = $db->query($sqlAdmin); // Ausführen der Abfrage
    if ($result) {
        // Administratorkundennummer aus dem Ergebnis abrufen und in der Sitzung speichern
        $adminKNr = $result->fetch_assoc()['KNr'];
        $_SESSION['adminKNr'] = $adminKNr; // Administratorkundennummer in der Sitzung speichern
    } else {
        // Fehlermeldung anzeigen und Ausführung stoppen, wenn die Administratorkundennummer nicht abgerufen werden kann
        echo "<script>alert('Administratorzugriff konnte nicht verifiziert werden.');</script>";
        exit; // Beenden der Ausführung
    }
} else {
    // Verwenden der gespeicherten Administratorkundennummer aus der Sitzung
    $adminKNr = $_SESSION['adminKNr'];
}

// Behandlung der Produktlöschung
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_product'])) {
    $kundennummer = $_POST['kundennummer'];
    $product_id = $_POST['product_id'];

    // Überprüfen, ob die Kundennummer mit der Administratorkundennummer übereinstimmt
    if ($kundennummer == $adminKNr) {
        $db->autocommit(FALSE); // Deaktivieren der automatischen Commit-Funktion

        try {
            // SQL-Anweisung zum Löschen eines Produkts
            $sqlDeleteProduct = "DELETE FROM Artikel WHERE ANr = ?";
            $stmtDeleteProduct = $db->prepare($sqlDeleteProduct); // Vorbereitung der SQL-Anweisung
            $stmtDeleteProduct->bind_param("i", $product_id); // Binden der Produkt-ID an die SQL-Anweisung
            $stmtDeleteProduct->execute(); // Ausführen der SQL-Anweisung

            // Überprüfen, ob das Produkt erfolgreich gelöscht wurde
            if ($stmtDeleteProduct->affected_rows > 0) {
                $db->commit(); // Änderungen in der Datenbank festschreiben
                echo "<script>alert('Produkt erfolgreich gelöscht.'); window.location.reload();</script>";
            } else {
                // Fehler werfen, wenn das Produkt nicht gefunden wurde oder bereits gelöscht ist
                throw new Exception("Produkt nicht gefunden oder bereits gelöscht.");
            }
        } catch (Exception $e) {
            // Änderungen zurücksetzen bei Fehler
            $db->rollback();
            echo "<script>alert('Fehler beim Löschen des Produkts: " . $e->getMessage() . "'); window.location.reload();</script>";
        }
    } else {
        // Fehlermeldung anzeigen, wenn die Kundennummer nicht die des Administrators ist
        echo "<script>alert('Nur der Administrator kann Produkte löschen.');</script>";
    }
}

// Behandlung der Produkthinzufügung
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bezeichnung'])) {
    $kundennummer = $_POST['kundennummer'];

    // Überprüfen, ob die Kundennummer mit der Administratorkundennummer übereinstimmt
    if ($kundennummer == $adminKNr) {
        // POST-Daten sichern und speichern
        $bezeichnung = $db->real_escape_string($_POST['bezeichnung']);
        $beschreibung = $db->real_escape_string($_POST['beschreibung']);
        $preis = $db->real_escape_string($_POST['preis']);
        $bildDateiname = $db->real_escape_string($_POST['bild']);
        $bild = "../bilder/" . $bildDateiname; // Pfad zum Bild festlegen, somit muss der user nur den namen und die datei art eingeben z.B. bild.png anstelle vom gesammten pfad.

        // SQL-Anweisung zum Hinzufügen eines neuen Produkts
        $sql = "INSERT INTO Artikel (Bezeichnung, Beschreibung, Preis, Bild) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql); // Vorbereitung der SQL-Anweisung
        $stmt->bind_param("ssds", $bezeichnung, $beschreibung, $preis, $bild);
        if ($stmt->execute()) {
            // Erfolgsmeldung anzeigen und Seite neu laden
            echo "<script>alert('Produkt erfolgreich hinzugefügt.'); window.location.reload();</script>";
        } else {
            // Fehlermeldung anzeigen bei Fehler
            echo "<script>alert('Fehler beim Hinzufügen des Produkts: " . $stmt->error . "'); window.location.reload();</script>";
        }
        $stmt->close(); // Schließen der vorbereiteten Anweisung
    } else {
        // Fehlermeldung anzeigen, wenn die Kundennummer nicht die des Administrators ist
        echo "<script>alert('Nur der Administrator kann Produkte hinzufügen.');</script>";
    }
}
?>





<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Produkt hinzufügen</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.html">Smart GmbH</a>
            </div>

            <ul class="nav navbar-nav">
                <li>
                    <a href="home.php">Home (Produkte)</a>
                </li>
                <li class="active">
                    <a href="registrieren.php">Registrierung</a>
                </li>
                <li>
                    <a href="bestellen.php">Bestellformular</a>
                </li>
            </ul>
        </div>
    </nav>

    <h2>Produkt hinzufügen</h2>

    <!-- Formular zum Hinzufügen eines neuen Produkts -->
    <form method="post">
        <label for="kundennummer">Kundennummer:</label><br>
        <input type="number" id="kundennummer" name="kundennummer" required><br>
        <label for="bezeichnung">Bezeichnung:</label><br>
        <input type="text" id="bezeichnung" name="bezeichnung" required><br>
        <label for="beschreibung">Beschreibung:</label><br>
        <textarea id="beschreibung" name="beschreibung" required></textarea><br>
        <label for="preis">Preis:</label><br>
        <input type="number" step="0.01" id="preis" name="preis" required><br>
        <label for="bild">Bild URL:</label><br>
        <input type="text" id="bild" name="bild" required><br>
        <input type="submit" value="Produkt hinzufügen">
    </form>

    <br>
    <br>

    <!-- Formular zum Löschen eines Produkts -->
    <h2>Produkt löschen</h2>
    <form method="POST">
        <label for="kundennummer">Kundennummer:</label><br>
        <input type="number" id="kundennummer" name="kundennummer" required><br>
        <label for="product_id">Produkt ID:</label>
        <input type="number" id="product_id" name="product_id" required><br>
        <input type="submit" name="delete_product" value="DELETE">
    </form>

    <footer>
        <p>Develped by Gökdeniz and Ralf. Databank modelling by Adrian and Elias, Project Management by Natalie</p>
    </footer>

</body>
</html>
