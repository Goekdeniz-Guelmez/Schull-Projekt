# Schul Projekt

Projekt Strucktur:

| _Datenbank.sql
| _index.html
| _
| _
| _
| _
| _
| _bilder
      | _
      | _
      | _
      | _
      | _




## Datenbank.sql

Im Rahmen meines Schulprojekts habe ich eine relationale Datenbank für einen fiktiven Online-Shop mit SQL realisiert. Hier ist eine detaillierte Beschreibung des Prozesses und der dabei erstellten Tabellen:

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