DROP DATABASE IF EXISTS shop;
CREATE DATABASE shop;

USE shop;

CREATE TABLE IF NOT EXISTS Ort (
    PLZ int(11) NOT NULL,
    Name varchar(100) NOT NULL,
    PRIMARY KEY (PLZ, Name)
);

CREATE TABLE IF NOT EXISTS Anschrift (
    AnsID int(11) NOT NULL AUTO_INCREMENT,
    PLZ int(11),
    Name varchar(100) NOT NULL,
    Straße varchar(100) NOT NULL,
    Hausnummer int(11) NOT NULL,
    PRIMARY KEY (AnsID),
    FOREIGN KEY (PLZ, Name) REFERENCES Ort(PLZ, Name)
);

CREATE TABLE IF NOT EXISTS Kunde (
    KNr int(11) NOT NULL AUTO_INCREMENT,
    Vorname varchar(100) NOT NULL,
    Nachname varchar(100) NOT NULL,
    AnsID int(11),
    Email varchar(100) NOT NULL,
    PRIMARY KEY (KNr),
    FOREIGN KEY (AnsID) REFERENCES Anschrift(AnsID)
);

CREATE TABLE IF NOT EXISTS Artikel (
    ANr int(11) NOT NULL AUTO_INCREMENT,
    Bezeichnung varchar(100) NOT NULL,
    Beschreibung varchar(1000),
    Preis double(10,2) NOT NULL,
    Bild varchar(255),
    PRIMARY KEY (ANr)
);

CREATE TABLE IF NOT EXISTS Bestellung (
    BesNr int(11) NOT NULL AUTO_INCREMENT,
    KNr int(11),
    Datum datetime NOT NULL,
    PRIMARY KEY (BesNr),
    FOREIGN KEY (KNr) REFERENCES Kunde(KNr)
);

CREATE TABLE IF NOT EXISTS Position (
    BesPos int(11) NOT NULL AUTO_INCREMENT,
    ANr int(11),
    BesNr int(11),
    Anzahl int(11) NOT NULL,
    PRIMARY KEY (BesPos),
    FOREIGN KEY (ANr) REFERENCES Artikel(ANr),
    FOREIGN KEY (BesNr) REFERENCES Bestellung(BesNr)
);

INSERT INTO artikel (ANr, Bezeichnung, Beschreibung, Preis, Bild)
values (001, "Smart Speaker", "Der Echo Studio kombiniert High-Fidelity-Klang mit den Funktionen von verschiedenen Sprachsteuerungen. Mit seinem 3D-Klangsystem füllt er den Raum mit beeindruckendem Sound. Dank der Sprachsteuerung können Benutzer per Sprachbefehl Musik abspielen, Fragen stellen und Smart-Home-Geräte steuern. Der Echo Studio passt sich automatisch an die Raumakustik an und bietet so stets optimalen Klang. Ideal für Audiophile und Smart-Home-Enthusiasten.", 49.99, "../bilder/speaker.jpg"),
(002, "Smart Plug", "Der Mini Smart Plug ermöglicht die Fernsteuerung angeschlossener Geräte über das Internet. Mit der zugehörigen App können Sie Geräte ein- und ausschalten, Zeitpläne erstellen und den Energieverbrauch überwachen.",33.13, "../bilder/plug.png"),
(003, "Smart Lock", "Der Wi-Fi Smart Lock ermöglicht die sichere Steuerung Ihrer Tür von überall aus über das Internet. Mit der zugehörigen App können Sie die Tür verriegeln und entriegeln, virtuelle Schlüssel verwalten und Aktivitätsprotokolle überprüfen.", 159.99, "../bilder/lock.jpg"),
(004, "Smart Lamp", "Die Smart Lamp bietet eine einfache Möglichkeit, Ihre Beleuchtung zu automatisieren und zu steuern. Über die Philips Hue-App können Sie die Lampe ein- und ausschalten, Helligkeit und Farbtemperatur anpassen sowie Zeitpläne festlegen.", 14.99, "../bilder/lamp.jpg"),
(005, "Smart Thermostat", "Das Smart Thermostat ermöglicht die intelligente Steuerung Ihrer Heizung und Kühlung von überall aus über das Internet. Mit der zugehörigen App können Sie die Temperatur einstellen, Zeitpläne programmieren und Energieverbrauchsberichte anzeigen.", 39.99, "../bilder/thermostat.jpg");