<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Schritt 1: Postleitzahl
    $plz = isset($_POST['plz']) ? htmlspecialchars($_POST['plz']) : '';

    // Schritt 2: Standort
    $Ort = isset($_POST['Ort']) ? htmlspecialchars($_POST['Ort']) : '';
    $Strasse = isset($_POST['Strasse']) ? htmlspecialchars($_POST['Strasse']) : '';
    $Hausnummer = isset($_POST['Hausnummer']) ? htmlspecialchars($_POST['Hausnummer']) : '';

    // Schritt 3: Art der Arbeit
    $art_der_arbeit = isset($_POST['art_der_arbeit']) ? htmlspecialchars($_POST['art_der_arbeit']) : '';

    // Schritt 4: Innen oder Außen
    $innen_oder_außen = isset($_POST['Innen oder außen']) ? htmlspecialchars($_POST['Innen oder außen']) : '';

    // Schritt 5: Zu streichende Teile (Checkboxes)
    $waende = isset($_POST['Waende']) ? 'Ja' : 'Nein';
    $zimmerdecken = isset($_POST['Zimmerdecken']) ? 'Ja' : 'Nein';
    $tueren = isset($_POST['Tueren']) ? 'Ja' : 'Nein';
    $fensterrahmen = isset($_POST['Fensterrahmen']) ? 'Ja' : 'Nein';
    $andere = isset($_POST['Andere']) ? 'Ja' : 'Nein';

    // Schritt 6: Quadratmeter
    $quadratmeter = isset($_POST['Quadratmeter']) ? htmlspecialchars($_POST['Quadratmeter']) : '';

    // Schritt 7: Datum der Durchführung
    $datum = isset($_POST['Datum']) ? htmlspecialchars($_POST['Datum']) : '';

    // Schritt 8: Projektbeschreibung
    $projektbeschreibung = isset($_POST['Projektbeschreibung']) ? htmlspecialchars($_POST['Projektbeschreibung']) : '';

    // Schritt 9: Vorname und Nachname
    $vorname = isset($_POST['Vorname']) ? htmlspecialchars($_POST['Vorname']) : '';
    $nachname = isset($_POST['Nachname']) ? htmlspecialchars($_POST['Nachname']) : '';

    // Schritt 10: E-Mail und Telefonnummer
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
    $telefon = isset($_POST['tel']) ? htmlspecialchars($_POST['tel']) : '';

    $gesamtpreis = $quadratmeter * 100;

    // Erstelle die E-Mail-Nachricht
    $to = "info@gerdt-webdesign.de";
    $subject = "Neue Anfrage von $vorname $nachname";
    $message = "Neue Anfrage erhalten:\n\n";
    $message .= "Postleitzahl $plz\n";
    $message .= "Standort:\n";
    $message .= "- Ort: $Ort\n";
    $message .= "- Straße: $Strasse\n";
    $message .= "- Hausnummer: $Hausnummer\n\n";
    $message .= "Art der Arbeit: $art_der_arbeit\n";
    $message .= "Innen oder Außen: $innen_oder_außen\n\n";
    $message .= "Zu streichende Teile:\n";
    $message .= "- Wände: $waende\n";
    $message .= "- Zimmerdecken: $zimmerdecken\n";
    $message .= "- Türen: $tueren\n";
    $message .= "- Fensterrahmen: $fensterrahmen\n";
    $message .= "- Andere: $andere\n\n";
    $message .= "Quadratmeter: $quadratmeter\n";
    $message .= "Gesamtpreis ca.: $gesamtpreis,-\n";
    $message .= "Datum der Durchführung: $datum\n";
    $message .= "Projektbeschreibung: $projektbeschreibung\n\n";
    $message .= "Kontaktdaten:\n";
    $message .= "- Vorname: $vorname\n";
    $message .= "- Nachname: $nachname\n";
    $message .= "- E-Mail: $email\n";
    $message .= "- Telefon: $telefon\n";

    // Zusätzliche E-Mail-Header
    $headers = "From: info@gerdt-webdesign.de" . "\r\n" .
               "Reply-To: $email" . "\r\n" .
               "X-Mailer: PHP/" . phpversion();

    // Versuche, die E-Mail zu senden
    if (mail($to, $subject, $message, $headers)) {
        $response = ["success" => true, "message" => "Anfrage erfolgreich gesendet. Wir werden uns bald bei Ihnen melden."];
    } else {
        $response = ["success" => false, "message" => "E-Mail-Versand fehlgeschlagen. Bitte versuchen Sie es später erneut."];
    }

    // Sende die JSON-Antwort zurück
    header('Content-Type: application/json');
    echo json_encode($response);

} else {
    // Wenn nicht per POST angefragt wird, sende eine Fehlermeldung zurück
    $response = ["success" => false, "message" => "Ungültige Anfragemethode."];
    header('Content-Type: application/json');
    echo json_encode($response);
}
