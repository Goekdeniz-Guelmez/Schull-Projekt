<?php
// Datenbankkonfiguration einbinden
include "dbConfig.php";

// Überprüfen, ob das Formular mit der POST-Methode abgeschickt wurde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // POST-Daten in Variablen speichern
    $vorname = $_POST["vorname"];
    $nachname = $_POST["nachname"];
    $email = $_POST["email"];
    $strasse = $_POST["strasse"];
    $hausnummer = $_POST["hausnummer"];
    $plz = $_POST["plz"];
    $ort = $_POST["ort"];

    // Überprüfen, ob der Benutzername oder die E-Mail bereits existieren
    $sqlCheckUser = "SELECT * FROM Kunde WHERE Vorname = ? OR Email = ?";
    $stmtCheckUser = $db->prepare($sqlCheckUser);
    $stmtCheckUser->bind_param("ss", $vorname, $email);
    $stmtCheckUser->execute();
    $resultCheckUser = $stmtCheckUser->get_result();

    // Wenn der Benutzername oder die E-Mail bereits existieren, Fehlermeldung anzeigen
    if ($resultCheckUser->num_rows > 0) {
        $errorMessage = "Der Benutzername oder die E-Mail-Adresse sind bereits vergeben. Bitte wählen Sie einen anderen Namen oder eine andere E-Mail.";
    } else {
        // Automatische Commit-Funktion der Datenbank deaktivieren
        $db->autocommit(FALSE);

        try {
            // Überprüfen, ob die eingegebene PLZ bereits in der Datenbank existiert
            $sqlCheckPLZ = "SELECT PLZ FROM Ort WHERE PLZ = ?";
            // Diese Methode bereitet eine SQL-Anweisung zur Ausführung vor. Sie schützt die Anwendung vor SQL-Injection-Angriffen, indem sie Platzhalter (?) in der SQL-Abfrage verwendet, die später mit den tatsächlichen Werten ersetzt werden.
            $stmtCheckPLZ = $db->prepare($sqlCheckPLZ);
            // Diese Methode bindet die tatsächlichen Werte an die Platzhalter in der vorbereiteten SQL-Anweisung. Sie nimmt die Datentypen und die entsprechenden Variablen als Parameter und sorgt dafür, dass die Werte richtig in die SQL-Anweisung eingefügt werden. Die Datentypen sind:
            // i für Integer
            // d für Double
            // s für String
            // b für Blob
            $stmtCheckPLZ->bind_param("i", $plz); 
            $stmtCheckPLZ->execute(); # Diese Methode führt die vorbereitete und gebundene SQL-Anweisung aus. Nach dem Binden der Parameter wird die Abfrage an die Datenbank gesendet und ausgeführt.
            $resultCheckPLZ = $stmtCheckPLZ->get_result(); // Holt das Ergebnis der Abfrage, falls diese Daten zurückgibt.

            // Wenn die PLZ nicht existiert, einen neuen Eintrag für PLZ und Ort hinzufügen
            if ($resultCheckPLZ->num_rows === 0) {
                $sqlInsertPLZ = "INSERT INTO Ort (PLZ, Name) VALUES (?, ?)";
                $stmtInsertPLZ = $db->prepare($sqlInsertPLZ);
                $stmtInsertPLZ->bind_param("is", $plz, $ort); // 'Ort' und 'PLZ' in die Einfügeoperation einbinden
                $stmtInsertPLZ->execute();
            }

            // Anschrift in die Datenbank einfügen
            $sqlAnschrift = "INSERT INTO Anschrift (PLZ, Straße, Hausnummer) VALUES (?, ?, ?)";
            $stmtAnschrift = $db->prepare($sqlAnschrift);
            $stmtAnschrift->bind_param("iss", $plz, $strasse, $hausnummer);
            $stmtAnschrift->execute();
            $anschriftId = $db->insert_id; // Die ID der eingefügten Anschrift speichern

            // Kundendaten in die Datenbank einfügen
            $sqlKunde = "INSERT INTO Kunde (Vorname, Nachname, AnsID, Email) VALUES (?, ?, ?, ?)";
            $stmtKunde = $db->prepare($sqlKunde);
            $stmtKunde->bind_param("ssis", $vorname, $nachname, $anschriftId, $email);
            $stmtKunde->execute();
            $kundennummer = $db->insert_id; // Die Kundennummer speichern

            // Alle Änderungen in der Datenbank festschreiben
            $db->commit();

            // Erfolgsnachricht festlegen
            $successMessage = "Registrierung erfolgreich! Ihre Kundennummer lautet: $kundennummer. Bitte notieren sie sich diese ID nummer.";
        } catch (Exception $e) {
            // Bei einem Fehler alle Änderungen rückgängig machen
            $db->rollback();
            // Fehlermeldung festlegen
            $errorMessage = "Fehler beim Registrieren: " . $e->getMessage();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Registrieren</title>
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

    <h1>Kundenregistrierung</h1>

    <!-- Formular für die Kundenregistrierung -->
    <form method="POST">
        <label for="vorname">Vorname:</label>
        <input type="text" id="vorname" name="vorname" required><br>

        <label for="nachname">Nachname:</label>
        <input type="text" id="nachname" name="nachname" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <h2>Anschrift</h2>
        <label for="strasse">Straße:</label>
        <input type="text" id="strasse" name="strasse" required><br>

        <label for="hausnummer">Hausnummer:</label>
        <input type="number" id="hausnummer" name="hausnummer" required><br>

        <label for="plz">PLZ:</label>
        <input type="number" id="plz" name="plz" required><br>

        <label for="plz">Ort:</label>
        <input type="text" id="ort" name="ort" required><br>

        <input type="submit" value="Registrieren">
    </form>

    <!-- Anzeige der Erfolgs- oder Fehlermeldung -->
    <?php if (isset($successMessage)): ?>
    <script>
        alert('<?php echo $successMessage; ?>');
    </script>
    <?php elseif (isset($errorMessage)): ?>
    <script>
        alert('<?php echo $errorMessage; ?>');
    </script>
    <?php endif; ?>

    <footer>
        <p>Developed by Gökdeniz and Ralf. Databank modelling by Adrian and Elias, Project Management by Natalie</p>
    </footer>
</body>
</html>