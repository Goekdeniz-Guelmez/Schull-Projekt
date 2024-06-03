# Schul Projekt

### Projektübersicht
Für mein Schulprojekt habe ich eine fiktive Shopping-Seite erstellt, die es Nutzern ermöglicht, Produkte anzusehen, sich zu registrieren und Bestellungen aufzugeben. Die Webseite wurde mithilfe von HTML, CSS und PHP entwickelt und nutzt eine MySQL-Datenbank zur Verwaltung der Daten. Im Folgenden beschreibe ich die Implementierung der verschiedenen Komponenten der Webseite.

### Projekt Strucktur:
| _ Datenbank.sql
| _ dbConfig.php
| _ index.html
| _ home.php
| _ registrieren.php
| _ bestellen.php
| _ neues_produkt_einfügen.php
| _ README.md
| _ Dokumentation.md
| _ bilder
      | _ lamp.jpg
      | _ lock.jpg
      | _ plug.png
      | _ speaker.jpg
      | _ thermostat.jpg




## Datenbank.sql

### Datenbankstruktur
Tabellenstruktur:

- Ort: Speichert Postleitzahlen und Ortsnamen.
- Anschrift: Enthält Adressen mit Straße, Hausnummer und Verweis auf die Tabelle Ort.
- Kunde: Speichert Kundendaten inklusive Vorname, Nachname, E-Mail und Verweis auf die Anschrift.
- Artikel: Beinhaltet Produktinformationen wie Bezeichnung, Beschreibung, Preis und Bild.
- Bestellung: Verzeichnet Bestellungen mit Kunde und Datum.
- Position: Speichert die einzelnen Positionen einer Bestellung mit Verweis auf Artikel und Bestellung.


Die Datenbank für das Projekt enthält mehrere Tabellen zur Verwaltung von Kunden, Produkten, Bestellungen und Adressen. Die Datenbank wird mit dem folgenden Skript erstellt:

### Schritt 1: Erstellen der Datenbank

Zunächst habe ich sichergestellt, dass keine alte Version der Datenbank existiert, indem ich sie gegebenenfalls gelöscht habe. Anschließend habe ich eine neue Datenbank mit dem Namen "shop" erstellt und aktiviert.

```sql
DROP DATABASE IF EXISTS shop;
CREATE DATABASE shop;
USE shop;
```

### Schritt 2: Erstellen der Tabelle "Ort"

Die erste Tabelle, die ich erstellt habe, ist die Tabelle "Ort". Diese enthält die Postleitzahl (PLZ) und den Namen des Ortes. Die PLZ und der Name dienen gemeinsam als Primärschlüssel, um sicherzustellen, dass jede Kombination einzigartig ist.

```sql
CREATE TABLE IF NOT EXISTS Ort (
    PLZ int(11) NOT NULL,
    Name varchar(100) NOT NULL,
    PRIMARY KEY (PLZ, Name)
);
```

### Schritt 3: Erstellen der Tabelle "Anschrift"

Als nächstes habe ich die Tabelle "Anschrift" erstellt, die eine eindeutige ID (AnsID), die Postleitzahl (PLZ), die Straße und die Hausnummer enthält. Die PLZ ist ein Fremdschlüssel, der auf die Tabelle "Ort" verweist.

```sql
CREATE TABLE IF NOT EXISTS Anschrift (
    AnsID int(11) NOT NULL AUTO_INCREMENT,
    PLZ int(11),
    Straße varchar(100) NOT NULL,
    Hausnummer int(11) NOT NULL,
    PRIMARY KEY (AnsID),
    FOREIGN KEY (PLZ) REFERENCES Ort(PLZ)
);
```

### Schritt 4: Erstellen der Tabelle "Kunde"

Die Tabelle "Kunde" enthält die Kundennummer (KNr), den Vor- und Nachnamen, die Anschriften-ID (AnsID) und die E-Mail-Adresse. Die AnsID ist ein Fremdschlüssel, der auf die Tabelle "Anschrift" verweist.

```sql
CREATE TABLE IF NOT EXISTS Kunde (
    KNr int(11) NOT NULL AUTO_INCREMENT,
    Vorname varchar(100) NOT NULL,
    Nachname varchar(100) NOT NULL,
    AnsID int(11),
    Email varchar(100) NOT NULL,
    PRIMARY KEY (KNr),
    FOREIGN KEY (AnsID) REFERENCES Anschrift(AnsID)
);
```

### Schritt 5: Erstellen der Tabelle "Artikel"

Die Tabelle "Artikel" enthält die Artikelnummer (ANr), die Bezeichnung, eine Beschreibung, den Preis und einen Pfad zum Bild. Diese Tabelle dient zur Speicherung der Produkte, die im Online-Shop angeboten werden.

```sql
CREATE TABLE IF NOT EXISTS Artikel (
    ANr int(11) NOT NULL AUTO_INCREMENT,
    Bezeichnung varchar(100) NOT NULL,
    Beschreibung varchar(1000),
    Preis double(10,2) NOT NULL,
    Bild varchar(255),
    PRIMARY KEY (ANr)
);
```

### Schritt 6: Erstellen der Tabelle "Bestellung"

In der Tabelle "Bestellung" werden die Bestellnummer (BesNr), die Kundennummer (KNr) und das Datum der Bestellung gespeichert. Die KNr ist ein Fremdschlüssel, der auf die Tabelle "Kunde" verweist.

```sql
CREATE TABLE IF NOT EXISTS Bestellung (
    BesNr int(11) NOT NULL AUTO_INCREMENT,
    KNr int(11),
    Datum datetime NOT NULL,
    PRIMARY KEY (BesNr),
    FOREIGN KEY (KNr) REFERENCES Kunde(KNr)
);
```

### Schritt 7: Erstellen der Tabelle "Position"

Die letzte Tabelle, die ich erstellt habe, ist die Tabelle "Position". Diese enthält die Bestellpositionsnummer (BesPos), die Artikelnummer (ANr), die Bestellnummer (BesNr) und die Anzahl der Artikel. Die ANr und BesNr sind Fremdschlüssel, die auf die Tabellen "Artikel" bzw. "Bestellung" verweisen.

```sql
CREATE TABLE IF NOT EXISTS Position (
    BesPos int(11) NOT NULL AUTO_INCREMENT,
    ANr int(11),
    BesNr int(11),
    Anzahl int(11) NOT NULL,
    PRIMARY KEY (BesPos),
    FOREIGN KEY (ANr) REFERENCES Artikel(ANr),
    FOREIGN KEY (BesNr) REFERENCES Bestellung(BesNr)
);
```

### Schritt 8: Einfügen von Testdaten

Um die Funktionsweise der Datenbank zu überprüfen, habe ich einige Testdaten in die Tabelle "Artikel" und die Tabelle "Kunde" eingefügt.

```sql
INSERT INTO Artikel (ANr, Bezeichnung, Beschreibung, Preis, Bild)
VALUES 
(001, "Smart Speaker", "Der Echo Studio kombiniert High-Fidelity-Klang mit den Funktionen von verschiedenen Sprachsteuerungen. Mit seinem 3D-Klangsystem füllt er den Raum mit beeindruckendem Sound. Dank der Sprachsteuerung können Benutzer per Sprachbefehl Musik abspielen, Fragen stellen und Smart-Home-Geräte steuern. Der Echo Studio passt sich automatisch an die Raumakustik an und bietet so stets optimalen Klang. Ideal für Audiophile und Smart-Home-Enthusiasten.", 49.99, "../bilder/speaker.jpg"),
(002, "Smart Plug", "Der Mini Smart Plug ermöglicht die Fernsteuerung angeschlossener Geräte über das Internet. Mit der zugehörigen App können Sie Geräte ein- und ausschalten, Zeitpläne erstellen und den Energieverbrauch überwachen.",33.13, "../bilder/plug.png"),
(003, "Smart Lock", "Der Wi-Fi Smart Lock ermöglicht die sichere Steuerung Ihrer Tür von überall aus über das Internet. Mit der zugehörigen App können Sie die Tür verriegeln und entriegeln, virtuelle Schlüssel verwalten und Aktivitätsprotokolle überprüfen.", 159.99, "../bilder/lock.jpg"),
(004, "Smart Lamp", "Die Smart Lamp bietet eine einfache Möglichkeit, Ihre Beleuchtung zu automatisieren und zu steuern. Über die Philips Hue-App können Sie die Lampe ein- und ausschalten, Helligkeit und Farbtemperatur anpassen sowie Zeitpläne festlegen.", 14.99, "../bilder/lamp.jpg"),
(005, "Smart Thermostat", "Das Smart Thermostat ermöglicht die intelligente Steuerung Ihrer Heizung und Kühlung von überall aus über das Internet. Mit der zugehörigen App können Sie die Temperatur einstellen, Zeitpläne programmieren und Energieverbrauchsberichte anzeigen.", 39.99, "../bilder/thermostat.jpg");

INSERT INTO Kunde (KNr, Vorname, Nachname, Email)
VALUES (0, "admin", "admin", "admin@gmail.com");
```

Diese Schritte dokumentieren, wie ich die Datenbank für den Online-Shop erstellt habe. Jede Tabelle wurde sorgfältig geplant und erstellt, um eine konsistente und effiziente Datenverwaltung zu gewährleisten. Die Verwendung von Fremdschlüsseln stellt sicher, dass die Datenintegrität über die verschiedenen Tabellen hinweg gewahrt bleibt.

### Datenbankkonfiguration
Die Verbindung zur Datenbank wird in einer separaten Konfigurationsdatei eingerichtet. Diese Datei enthält die Zugangsdaten und stellt sicher, dass die Webseite auf die Datenbank zugreifen kann.

## dbConfig.php:
Eine Konfigurationsdatei (`dbConfig.php`) wurde erstellt, um die Verbindung zur MySQL-Datenbank für die "Smart GmbH" Website zu verwalten. Diese Datei enthält wichtige Informationen zur Datenbankverbindung und stellt sicher, dass die Website mit der Datenbank kommunizieren kann.

### PHP-Tag und Datenbankdetails
Die Datei beginnt mit dem Öffnen des PHP-Tags. Danach werden die Details der Datenbankverbindung definiert, einschließlich des Hostnamens, des Benutzernamens, des Passworts und des Datenbanknamens. Da es sich um eine lokale Entwicklung handelt, bleibt das Passwortfeld leer.

```php
<?php
//DB details
$dbHost = 'localhost'; // Link
$dbUsername = 'root'; // Link
$dbPassword = ''; // Ist Leer weil es im LocalHost ist
$dbName = 'shop'; // Name der Datenbank
```

### Erstellen der Datenbankverbindung

Im nächsten Schritt erstelle ich eine neue Verbindung zur MySQL-Datenbank mithilfe der `mysqli` Klasse und den zuvor definierten Verbindungsdetails.

```php
// Erschaffe eine Verbindung zur Datenbank
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
```

### Fehlerbehandlung

Falls die Verbindung zur Datenbank fehlschlägt, gebe ich eine Fehlermeldung aus und beende das Skript. Dies wird durch die `connect_error` Eigenschaft der `mysqli` Klasse ermöglicht.

```php
// Error Nachricht wenn keine verbindung mit der Datenbank möglich war
if ($db->connect_error) {
    die('Verbindung fehlgeschlagen: ' . $db->connect_error);
}
?>
```

## index.html
Dies ist die Startseite der Webseite. Sie enthält eine Navigation und eine Willkommensnachricht.

```html
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Smart GmbH</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="form">
    <nav class="navbar navbar-inverse" style="border-radius: 0px;">
      <div class="container-fluid">
          <div class="navbar-header">
              <a class="navbar-brand" class="active" href="index.html">Smart GmbH</a>
          </div>
          <ul class="nav navbar-nav">
              <li>
                  <a href="home.php">Home (Produkte)</a>
              </li>
              <li>
                  <a href="registrieren.php">Registrierung</a>
              </li>
              <li>
                  <a href="bestellen.php">Bestellformular</a>
              </li>
          </ul>
      </div>
    </nav>
    <h1 style="margin-top: 30vh;">Wilkommen</h1>
    <br>
    <p style="margin-bottom: 50vh;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptates cupiditate ab eaque! Praesentium ducimus exercitationem non ratione, doloremque optio ipsa porro aut provident ut dolore fuga dolorem aliquid amet modi. Assumenda, illo. Blanditiis iusto excepturi deleniti minima. Obcaecati dicta recusandae commodi quos rerum hic, magnam ipsum adipisci quae ut voluptatum tempore culpa reiciendis. Quo commodi blanditiis assumenda animi facere perferendis laboriosam sit sequi quaerat porro. Corporis assumenda, ullam illo veniam facere, ratione nihil animi quisquam nisi ut autem soluta blanditiis dolores deserunt. In sit illo ullam dolor magnam sint sequi voluptatem, quos beatae aperiam iusto. Sequi optio tempora rem non!</p>
  </div>
</body>
</html>
```

#### home.php
Diese Datei zeigt die verfügbaren Produkte an. Sie ruft die Produktdaten aus der Datenbank ab und zeigt sie in einer strukturierten Liste an.

```php
<?php
include "dbConfig.php"; // Einbinden der Datenbankkonfigurationsdatei
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf8">
    <title>Smarthome Produkte - Smart GmBH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.html">Smart GmbH</a>
            </div>
            <ul class="nav navbar-nav">
                <li class="active">
                    <a href="home.php">Home (Produkte)</a>
                </li>
                <li>
                    <a href="registrieren.php">Registrierung</a>
                </li>
                <li>
                    <a href="bestellen.php">Bestellformular</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h1>Unsere Produkte</h1>
        <br>
        <div id="products" class="row list-group">
        <?php
        $query = $db->query("SELECT * FROM Artikel ORDER BY ANr LIMIT 10");
        if ($query->num_rows > 0) {
            while ($row = $query->fetch_assoc()) { ?>
        <div class="item col-lg-4">
            <div class="thumbnail">
                <img src="<?php echo $row["Bild"]; ?>" alt="<?php echo htmlspecialchars($row["Bild"]); ?>" class="product-image">
                <div class="caption">
                    <h4 class="list-group-item-heading"><?php echo htmlspecialchars($row["Bezeichnung"]); ?></h4>
                    <p class="list-group-item-text" style="padding-bottom:10px"><?php echo htmlspecialchars($row["Beschreibung"]); ?></p>
                    <h3 class="list-group-item-heading">