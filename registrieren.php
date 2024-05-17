<?php
include "dbConfig.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vorname = $_POST["vorname"];
    $nachname = $_POST["nachname"];
    $email = $_POST["email"];
    $strasse = $_POST["strasse"];
    $hausnummer = $_POST["hausnummer"];
    $plz = $_POST["plz"];
    $ort = $_POST["ort"]; // Capture 'ort' from POST data

    $db->autocommit(FALSE);

    try {
        // Check if PLZ exists and optionally insert it
        $sqlCheckPLZ = "SELECT PLZ FROM Ort WHERE PLZ = ?";
        $stmtCheckPLZ = $db->prepare($sqlCheckPLZ);
        $stmtCheckPLZ->bind_param("i", $plz);
        $stmtCheckPLZ->execute();
        $resultCheckPLZ = $stmtCheckPLZ->get_result();

        if ($resultCheckPLZ->num_rows === 0) {
            $sqlInsertPLZ = "INSERT INTO Ort (PLZ, Name) VALUES (?, ?)";
            $stmtInsertPLZ = $db->prepare($sqlInsertPLZ);
            $stmtInsertPLZ->bind_param("is", $plz, $ort); // Include 'ort' in the insertion
            $stmtInsertPLZ->execute();
        }

        // Insert address
        $sqlAnschrift = "INSERT INTO Anschrift (PLZ, Straße, Hausnummer) VALUES (?, ?, ?)";
        $stmtAnschrift = $db->prepare($sqlAnschrift);
        $stmtAnschrift->bind_param("iss", $plz, $strasse, $hausnummer);
        $stmtAnschrift->execute();
        $anschriftId = $db->insert_id;

        // Insert customer
        $sqlKunde = "INSERT INTO Kunde (Vorname, Nachname, AnsID, Email) VALUES (?, ?, ?, ?)";
        $stmtKunde = $db->prepare($sqlKunde);
        $stmtKunde->bind_param("ssis", $vorname, $nachname, $anschriftId, $email);
        $stmtKunde->execute();
        $kundennummer = $db->insert_id;

        $db->commit();

        $successMessage = "Registrierung erfolgreich! Ihre Kundennummer lautet: $kundennummer. Bitte notieren sie sich diese ID nummer.";
    } catch (Exception $e) {
        $db->rollback();
        $errorMessage = "Fehler beim Registrieren: " . $e->getMessage();
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
