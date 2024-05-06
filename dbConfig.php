<?php
//DB details
$dbHost = 'localhost'; // Link
$dbUsername = 'root'; // Link
$dbPassword = ''; // Ist Leer weil es im LocalHost ist
$dbName = 'shop'; // Name der Datenbank

// Erschaffe eine Verbindung zur Datenbank
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Error Nachricht wenn keine verbindung mit der Datenbank möglich war
if ($db->connect_error) {
    die('Verbindung fehlgeschlagen: ' . $db->connect_error);
}
?>
