<?php
include "dbConfig.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vorname = $_POST["vorname"];
    $nachname = $_POST["nachname"];
    $email = $_POST["email"];
    $strasse = $_POST["strasse"];
    $hausnummer = $_POST["hausnummer"];
    $plz = $_POST["plz"];

    $db->autocommit(FALSE);

    try {
        // Vorhandene PLZ überprüfen und ggf. hinzufügen
        $sqlCheckPLZ = "SELECT PLZ FROM Ort WHERE PLZ = ?";
        $stmtCheckPLZ = $db->prepare($sqlCheckPLZ);
        $stmtCheckPLZ->bind_param("i", $plz);
        $stmtCheckPLZ->execute();
        $resultCheckPLZ = $stmtCheckPLZ->get_result();

        if ($resultCheckPLZ->num_rows === 0) {
            $sqlInsertPLZ = "INSERT INTO Ort (PLZ, Name) VALUES (?, '')";
            $stmtInsertPLZ = $db->prepare($sqlInsertPLZ);
            $stmtInsertPLZ->bind_param("i", $plz);
            $stmtInsertPLZ->execute();
        }

        // Anschrift einfügen
        $sqlAnschrift = "INSERT INTO Anschrift (PLZ, Straße, Hausnummer) VALUES (?, ?, ?)";
        $stmtAnschrift = $db->prepare($sqlAnschrift);
        $stmtAnschrift->bind_param("iss", $plz, $strasse, $hausnummer);
        $stmtAnschrift->execute();
        $anschriftId = $db->insert_id;

        // Kunde einfügen
        $sqlKunde = "INSERT INTO Kunde (Vorname, Nachname, AnsID, Email) VALUES (?, ?, ?, ?)";
        $stmtKunde = $db->prepare($sqlKunde);
        $stmtKunde->bind_param("ssis", $vorname, $nachname, $anschriftId, $email);
        $stmtKunde->execute();
        $kundennummer = $db->insert_id;

        $db->commit();

        // E-Mail senden
        $subject = 'Willkommen bei uns!';
        $message = "Hallo $vorname,\n\nVielen Dank für deine Registrierung. Deine Kundennummer lautet: $kundennummer";
        $headers = 'From: noreply@example.com' . "\r\n" .
                   'Reply-To: noreply@example.com' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();

        mail($email, $subject, $message, $headers);

        $successMessage = "Registrierung erfolgreich! Ihre Kundennummer lautet: $kundennummer. Wir haben Ihnen auch eine Mail gesended.";
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
</body>
</html>
