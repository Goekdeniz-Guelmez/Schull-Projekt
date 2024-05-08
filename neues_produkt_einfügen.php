<?php
    session_start();
    
    include "dbConfig.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $kundennummer = $_POST['kundennummer'];

        // Holt die KNr des Benutzers 'admin'
        $sqlAdmin = "SELECT KNr FROM Kunde WHERE Vorname = 'admin'";
        $result = $db->query($sqlAdmin);
        $adminKNr = $result->fetch_assoc()['KNr'];

        // Überprüfung, ob die eingegebene KNr mit der KNr des 'admin' übereinstimmt
        if ($kundennummer == $adminKNr) {
            $bezeichnung = $db->real_escape_string($_POST['bezeichnung']);
            $beschreibung = $db->real_escape_string($_POST['beschreibung']);
            $preis = $db->real_escape_string($_POST['preis']);
            $bildDateiname = $db->real_escape_string($_POST['bild']);
            $bild = "../bilder/" . $bildDateiname; // Hinzufügen des Pfades vor dem Dateinamen

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
        $result->free();
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

    <form method="post" action="">
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
</body>
</html>
