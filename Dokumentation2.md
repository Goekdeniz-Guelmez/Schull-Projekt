# Schul Projekt

## Projektstruktur

Die Projektstruktur umfasst verschiedene Dateien und Ordner:

- Datenbank.sql: Enthält das Skript zum Erstellen der Datenbank und der Tabellen.
- dbConfig.php: Konfigurationsdatei zur Verbindung mit der MySQL-Datenbank.
- index.html: Die Startseite der Webseite.
- home.php: Seite zur Anzeige der verfügbaren Produkte.
- registrieren.php: Seite zur Registrierung von Nutzern.
- bestellen.php: Seite zur Bestellung von Produkten.
- neues_produkt_einfügen.php: Seite zum Hinzufügen neuer Produkte.
- README.md: Eine README-Datei mit allgemeinen Informationen zum Projekt.
- Dokumentation.md: Die vorliegende Dokumentation des Projekts.
- bilder: Ein Ordner mit Bilddateien für die Produkte (lamp.jpg, lock.jpg, plug.png, speaker.jpg, thermostat.jpg).

## Datenbankstruktur

Die Datenbank für das Projekt enthält mehrere Tabellen zur Verwaltung von Kunden, Produkten, Bestellungen und Adressen.

### Schritt 1: Erstellen der Datenbank

Zunächst wurde sichergestellt, dass keine alte Version der Datenbank existiert, indem sie gegebenenfalls gelöscht und anschließend eine neue Datenbank mit dem Namen "shop" erstellt wurde.

### Schritt 2: Erstellen der Tabelle "Ort"

Die erste Tabelle, "Ort", enthält die Postleitzahl (PLZ) und den Namen des Ortes. Die PLZ und der Name dienen gemeinsam als Primärschlüssel, um sicherzustellen, dass jede Kombination einzigartig ist.

### Schritt 3: Erstellen der Tabelle "Anschrift"

Die Tabelle "Anschrift" enthält eine eindeutige ID (AnsID), die Postleitzahl (PLZ), die Straße und die Hausnummer. Die PLZ ist ein Fremdschlüssel, der auf die Tabelle "Ort" verweist.

### Schritt 4: Erstellen der Tabelle "Kunde"

Die Tabelle "Kunde" enthält die Kundennummer (KNr), den Vor- und Nachnamen, die Anschriften-ID (AnsID) und die E-Mail-Adresse. Die AnsID ist ein Fremdschlüssel, der auf die Tabelle "Anschrift" verweist.

### Schritt 5: Erstellen der Tabelle "Artikel"

Die Tabelle "Artikel" enthält die Artikelnummer (ANr), die Bezeichnung, eine Beschreibung, den Preis und einen Pfad zum Bild. Diese Tabelle dient zur Speicherung der Produkte, die im Online-Shop angeboten werden.

### Schritt 6: Erstellen der Tabelle "Bestellung"

In der Tabelle "Bestellung" werden die Bestellnummer (BesNr), die Kundennummer (KNr) und das Datum der Bestellung gespeichert. Die KNr ist ein Fremdschlüssel, der auf die Tabelle "Kunde" verweist.

### Schritt 7: Erstellen der Tabelle "Position"

Die letzte Tabelle, "Position", enthält die Bestellpositionsnummer (BesPos), die Artikelnummer (ANr), die Bestellnummer (BesNr) und die Anzahl der Artikel. Die ANr und BesNr sind Fremdschlüssel, die auf die Tabellen "Artikel" bzw. "Bestellung" verweisen.

### Schritt 8: Einfügen von Testdaten

Um die Funktionsweise der Datenbank zu überprüfen, wurden einige Testdaten in die Tabellen "Artikel" und "Kunde" eingefügt.

## Datenbankkonfiguration

Die Verbindung zur Datenbank wird in einer separaten Konfigurationsdatei (`dbConfig.php`) eingerichtet. Diese Datei enthält die Zugangsdaten und stellt sicher, dass die Webseite auf die Datenbank zugreifen kann.

## Seitenübersicht

### index.html

**Beschreibung:**
Die Startseite der Webseite enthält grundlegende Navigationselemente und eine Willkommensnachricht für die Benutzer.

**Funktionalität:**
- Die Seite beginnt mit grundlegenden HTML-Elementen wie dem DOCTYPE, Kopf- und Körperbereich.
- Im Kopfbereich wird die Zeichencodierung festgelegt und das Stylesheet `style.css` eingebunden.
- Der Körper enthält eine Navigationsleiste (`<nav>`), die Links zu anderen Seiten der Webseite wie "Home (Produkte)", "Registrierung" und "Bestellformular" enthält.
- Unterhalb der Navigationsleiste befindet sich eine Willkommensnachricht, die mittig auf der Seite platziert ist.

### home.php

**Beschreibung:**
Diese Seite zeigt die verfügbaren Produkte an, indem sie die Produktdaten aus der Datenbank abruft und sie in einer strukturierten Liste anzeigt.

**Funktionalität:**
- Zu Beginn wird die Konfigurationsdatei `dbConfig.php` eingebunden, um die Verbindung zur MySQL-Datenbank herzustellen.
- Die HTML-Struktur der Seite umfasst einen Kopfbereich mit Meta-Tags und einem Link zum Stylesheet.
- Im Körperbereich wird eine Navigationsleiste ähnlich der auf der Startseite angezeigt.
- Ein Hauptbereich (`<div class="container">`) enthält eine Überschrift und eine Liste der Produkte.
- PHP-Code innerhalb des HTML-Dokuments führt eine SQL-Abfrage aus, um die Produktinformationen aus der Datenbank zu holen.
- Die Produktinformationen (Bild, Bezeichnung, Beschreibung und Preis) werden dynamisch generiert und in HTML-Elementen angezeigt.

### registrieren.php

**Beschreibung:**
Diese Seite ermöglicht es Nutzern, sich auf der Webseite zu registrieren. Sie enthält ein Formular zur Eingabe von Benutzerdaten und speichert diese in der Datenbank.

**Funktionalität:**
- Die Datei beginnt mit der Einbindung der `dbConfig.php` zur Herstellung der Datenbankverbindung.
- Ein Formular erlaubt es Nutzern, ihren Vor- und Nachnamen, E-Mail-Adresse, Straße, Hausnummer, PLZ und Ort einzugeben.
- Beim Absenden des Formulars wird überprüft, ob die E-Mail oder der Benutzername bereits existieren.
- Wenn die eingegebenen Daten gültig sind, werden sie in die entsprechenden Tabellen der Datenbank eingefügt.
- Erfolgs- oder Fehlermeldungen werden nach der Registrierung angezeigt.

### bestellen.php

**Beschreibung:**
Diese Seite ermöglicht es Nutzern, Produkte zu bestellen, indem sie eine Datenbankverbindung nutzt und Sessions verwendet, um den Warenkorb zu verwalten.

**Funktionalität:**
- Zu Beginn wird die Session gestartet und die `dbConfig.php` eingebunden.
- Der Warenkorb wird initialisiert, falls er noch nicht existiert.
- Benutzer können Produkte zum Warenkorb hinzufügen, die Menge ändern oder Produkte entfernen.
- Beim Abschließen der Bestellung werden die Bestelldaten und die einzelnen Positionen in der Datenbank gespeichert.
- Der aktuelle Inhalt des Warenkorbs wird angezeigt und aktualisiert.

### neues_produkt_einfuegen.php

**Beschreibung:**
Diese Seite ermöglicht es, neue Produkte in die Datenbank einzufügen.

**Funktionalität:**
- Die Seite enthält ein Formular, das Produktinformationen wie Bezeichnung, Beschreibung, Preis und Bilddatei entgegennimmt.
- Beim Absenden des Formulars werden die Daten in die entsprechende Tabelle der Datenbank eingefügt.
- Erfolgs- oder Fehlermeldungen werden nach dem Einfügen des neuen Produkts angezeigt.

### dbConfig.php

**Beschreibung:**
Diese Datei enthält die Konfigurationsdetails zur Verbindung mit der MySQL-Datenbank.

**Funktionalität:**
- Enthält Variablen zur Speicherung der Datenbankverbindungsdetails wie Hostname, Benutzername, Passwort und Datenbankname.
- Stellt die Verbindung zur MySQL-Datenbank her und überprüft, ob die Verbindung erfolgreich ist.
- Bei einem Verbindungsfehler wird eine entsprechende Fehlermeldung ausgegeben.