<?php
session_start();
include "dbConfig.php";

// Fetch the admin customer number only once per session if not already fetched
if (!isset($_SESSION['adminKNr'])) {
    $sqlAdmin = "SELECT KNr FROM Kunde WHERE Vorname = 'admin'";
    $result = $db->query($sqlAdmin);
    if ($result) {
        $adminKNr = $result->fetch_assoc()['KNr'];
        $_SESSION['adminKNr'] = $adminKNr; // Store admin KNr in session for later use
    } else {
        echo "<script>alert('Administratorzugriff konnte nicht verifiziert werden.');</script>";
        exit; // Stop execution if admin KNr cannot be fetched
    }
} else {
    $adminKNr = $_SESSION['adminKNr'];
}

// Handle product deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_product'])) {
    $kundennummer = $_POST['kundennummer'];
    $product_id = $_POST['product_id'];

    if ($kundennummer == $adminKNr) {
        $db->autocommit(FALSE);

        try {
            $sqlDeleteProduct = "DELETE FROM Artikel WHERE ANr = ?";
            $stmtDeleteProduct = $db->prepare($sqlDeleteProduct);
            $stmtDeleteProduct->bind_param("i", $product_id);
            $stmtDeleteProduct->execute();

            if ($stmtDeleteProduct->affected_rows > 0) {
                $db->commit();
                echo "<script>alert('Produkt erfolgreich gelöscht.'); window.location.reload();</script>";
            } else {
                throw new Exception("Produkt nicht gefunden oder bereits gelöscht.");
            }
        } catch (Exception $e) {
            $db->rollback();
            echo "<script>alert('Fehler beim Löschen des Produkts: " . $e->getMessage() . "'); window.location.reload();</script>";
        }
    } else {
        echo "<script>alert('Nur der Administrator kann Produkte löschen.');</script>";
    }
}

// Handle product addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bezeichnung'])) {
    $kundennummer = $_POST['kundennummer'];

    if ($kundennummer == $adminKNr) {
        $bezeichnung = $db->real_escape_string($_POST['bezeichnung']);
        $beschreibung = $db->real_escape_string($_POST['beschreibung']);
        $preis = $db->real_escape_string($_POST['preis']);
        $bildDateiname = $db->real_escape_string($_POST['bild']);
        $bild = "../bilder/" . $bildDateiname;

        $sql = "INSERT INTO Artikel (Bezeichnung, Beschreibung, Preis, Bild) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssds", $bezeichnung, $beschreibung, $preis, $bild);
        if ($stmt->execute()) {
            echo "<script>alert('Produkt erfolgreich hinzugefügt.'); window.location.reload();</script>";
        } else {
            echo "<script>alert('Fehler beim Hinzufügen des Produkts: " . $stmt->error . "'); window.location.reload();</script>";
        }
        $stmt->close();
    } else {
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
                <a class="navbar-brand" href="#">E-Shop</a>
            </div>

            <ul class="nav navbar-nav">
                <li class="active">
                    <a href="home.php">Home (Produkte)</a>
                </li>
                <li>
                    <a href="registrieren.php">Registrierung</a>
                </li>
                <li>
                    <a href="bestellen.php">bestellungs formular</a>
                </li>
            </ul>
        </div>
    </nav>

    <h2>Produkt hinzufügen</h2>

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
