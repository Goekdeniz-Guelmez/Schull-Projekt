### Dokumentation zur Kundenregistrierung (6.5 - Montag)

#### Überblick
Der PHP-Code ermöglicht die Registrierung neuer Kunden in einer Datenbank. Der Prozess umfasst das Ausfüllen eines Webformulars, das Senden dieser Daten an einen Server, das Einfügen von Daten in die Datenbank und das optional Senden einer Bestätigungsmail mit der Kundennummer an den registrierten Kunden.

#### Dateistruktur
- `index.php` - Hauptdatei, enthält sowohl das HTML-Formular als auch den PHP-Code zur Verarbeitung der Registrierung.
- `dbConfig.php` - Konfigurationsdatei für die Datenbankverbindung (nicht im Code inkludiert, muss separat vorhanden sein).

#### Hauptkomponenten
1. **HTML-Formular**: Sammelt Vorname, Nachname, E-Mail, Straße, Hausnummer und PLZ des Benutzers.
2. **PHP Backend-Logik**: Verarbeitet die Formulardaten und interagiert mit der Datenbank.
3. **Datenbankinteraktion**:
   - Überprüfung, ob die PLZ bereits existiert.
   - Einfügung neuer Adressen und Kunden in die Datenbank.
   - Transaktionsmanagement für konsistente Datenintegrität.

#### Ablauf der Registrierung
1. **Formularübermittlung**:
   - Der Benutzer füllt das Formular aus und sendet es ab.
   - Der Server prüft, ob die Anfrage vom Typ `POST` ist.

2. **Datenverarbeitung**:
   - Daten werden aus dem POST-Array extrahiert.
   - Die Datenbankverbindung wird eingeleitet (`$db->autocommit(FALSE);`).

3. **PLZ-Überprüfung und -Einfügung**:
   - Es wird geprüft, ob die PLZ bereits in der Tabelle `Ort` vorhanden ist.
   - Falls nicht, wird die neue PLZ hinzugefügt.

4. **Adressdaten einfügen**:
   - Eine neue Adresse mit Straße, Hausnummer und PLZ wird in die Tabelle `Anschrift` eingefügt.
   - Die ID der neuen Adresse wird für den nächsten Schritt gespeichert.

5. **Kundendaten einfügen**:
   - Kundendaten zusammen mit der Adresse-ID werden in die Tabelle `Kunde` eingefügt.
   - Die Kunden-ID wird aus dem letzten Insert-Vorgang abgerufen.

6. **Transaktionsabschluss**:
   - Bei erfolgreicher Eingabe wird die Transaktion bestätigt (`$db->commit();`).
   - Bei Fehlern wird ein Rollback durchgeführt und eine Fehlermeldung angezeigt.

7. **E-Mail-Versand (optional)**:
   - Bei erfolgreicher Registrierung wird eine Bestätigungsemail an die angegebene E-Mail-Adresse gesendet.

#### Fehlerbehandlung
- Der Code enthält Try-Catch-Blöcke zur Fehlerbehandlung bei Datenbankeingaben.
- Rollbacks garantieren, dass keine inkonsistenten Daten bei einem Fehler gespeichert werden.

#### Sicherheitshinweise
- Der Code verwendet Prepared Statements, um SQL-Injection zu verhindern.
- Es ist wichtig, dass auch die `dbConfig.php` sichere Methoden zur Verwaltung der Datenbankverbindung verwendet.

### Weiterentwicklung
Für eine Weiterentwicklung des Codes könnten folgende Aspekte verbessert oder ergänzt werden:
- Validierung der Eingaben auf Serverseite zur Verbesserung der Datensicherheit und -integrität.
- Erweiterte Fehlermeldungen und Nutzerfeedback auf der Website.
- Verwendung einer externen Bibliothek wie PHPMailer für den E-Mail-Versand für zuverlässigere Funktionalität und bessere Handhabung.

Diese Dokumentation sollte als Ausgangspunkt dienen, um die Funktionalität und Struktur des Codes zu verstehen und zukünftige Anpassungen oder Erweiterungen vorzunehmen.



# bestellen seite



# neues produkt einfügen seite gemacht



# sql code umändern so das double von 10,4 zu 10,2


TODO:
 - das active class im index.html link packen im nav
 - index.html muss bestellung formular zu bestellformular
 - PLZ mit Name als Primär schlüssel machen?