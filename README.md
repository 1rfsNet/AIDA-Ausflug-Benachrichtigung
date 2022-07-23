
# AIDA Ausflüge Benachrichtigung

Dieses Script fragt bei AIDA die Verfügbarkeit des gesuchten Ausflugs ab und gibt die Ergebnisse aus.
Das Script fragt direkt die API bei AIDA ab. Da diese API ständig Probleme macht (hast du sicherlich auf der Webseite auch schon oft bemerkt), habe ich das Error-Reporting in cURL deaktiviert, damit ich nicht ständig Benachrichtigungen bekomme. Mindestens 3-4x am Tag ist die API nicht erreichbar, das ist aber offenbar normal.
Ich nutze das Script in Kombination mit ioBroker, daher gibt dieses Script die Ergebnisse einfach nur direkt aus. Mein Script in ioBroker hatte das Script alle 5 Minuten aufgerufen und die Ergebnisse abgegriffen und mir per Telegram Nachricht geschickt. Statt dem "echo $text", kann man sich natürlich auch einfach eine E-Mail schicken lassen oder so.

## Vorwort
Ich habe dieses Script auf die schnelle für mich geschrieben, um einen Ausflug zu buchen, der ständig ausgebucht war. 
Ich stelle es hier gerne zur Verfügung, denn vielleicht erspart es jemandem 1-2 Stunden Arbeit.
Ich werde  jedoch keine Verbesserungen an dem Script durchführen. Das Script kann gerne von jedem ergänzt werden, für mich hat es den Zweck aber erfüllt.
Ich bitte daher auch um Verständnis, dass ich keinen Support leiste. Wer mit diesem Script überfordert ist, hat leider Pech gehabt.

## Setup
Es wird ein Webserver mit PHP und PHP-cURL benötigt. 
Des Weiteren musst du den X-API-Key für die API Anfragen ermitteln. Das wäre sicherlich auch automatisiert möglich gewesen, jedoch habe ich das nicht umgesetzt (s. Vorwort).
#### Ermitteln und Verwenden des X-API-Key
1. Einloggen: https://aida.de/login/myaida
2. Öffne die Entwickleroptionen des Browsers (z.B. bei Chrome F12) und wähle den Tab "Network" aus
3. Auf der AIDA Webseite solltest du deine anstehende Reise sehen. Unter dem Bild der Reiseroute befindet sich der Punkt Ausflüge. Klicke auf "Ausflugsangebote ansehen"
4. Sobald die Seite vollständig geladen ist, kannst du in den Entwickleroptionen nach "sessions" filtern
5. Dort solltest du jetzt zwei Einträge wie "sessions?lastName=XYZ&first ...." sehen. Wähle den Treffer aus, dessen Typ "xhr" ist. 
6. Du siehst nun den Header der Anfrage. Scrolle ganz nach unten, dort solltest du jetzt den x-api-key sehen.
7. Öffne die aida.php Datei und suche nach "===enter x-api-key here===". Ersetze das durch den x-api-key. Es gibt zwei Zeilen, in denen du das ersetzen musst!

## Verwendung
Der Aufruf erfolgt wie folgt:
https://example.com/aida.php?bookingid=XXXX&lastname=YYYYY&firstname=ZZZZ&date=2022-01-01&product=ABC01

- bookingid: ist deine Buchungsnummer
- lastname: dein Nachname
- firstname: dein Vorname
- date: Datum des Ausflugs
- product: Der Ausflugscode**

** Den Ausflugscode kannst du über die AIDA Webseite rausfinden. Die ersten drei Buchstaben sind die Abkürzung für den Hafen, gefolgt von einer Nummer. Wichtig: Wenn es mehrere Ausflugszeiten gibt, dann folgt noch ein weiterer Buchstabe (normal A-Z). Dieser Buchstabe darf nicht angegeben werden! Das Script versucht automatisch alle verfügbaren Ausflugszeiten durch. Gibt es den Auflug an mehreren Tagen, muss das Script für jeden Tag einzeln aufgerufen werden.

## FAQ
### Wie kann ich das Script testen?
Probiere das Script am besten erstmal mit einem Ausflug, der noch verfügbar ist. Dann sollte eine entsprechende Ausgabe kommen.
### Die Seite ist einfach nur weiß, was soll ich tun?
Ist der Ausflug nicht verfügbar, gibt es keinen Ausgabe und die Seite bleibt leer. Teste das Script am besten erstmal. Wenn du auch da keine Ausgabe bekommst, kannst du die in dem Script auskommentierten Zeilen für das cURL Error Reporting aktivieren und dir die Fehler anzeigen lassen.
### Das Script aktualisiert sich nicht
Das Script muss beispielsweise durch einen Cron-Job regelmäßig ausgeführt bzw. aufgerufen werden.
### Ich möchte per E-Mail benachrichtigt werden
Suche nach "echo $text;" im Quelltext. Die Zeile darunter ist für den Versand einer E-Mail zuständig. Vergess nicht deine E-Mail Adresse dort einzutragen.
