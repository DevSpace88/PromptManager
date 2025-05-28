# Anleitung für den Scraper-Dienst (server.js)

Dieser Dienst ermöglicht das dynamische Scrapen von Webseiteninhalten basierend auf übergebenen CSS-Selektoren.

## Voraussetzungen

- Node.js (Version 18 oder höher empfohlen)
- NPM oder Yarn

## Installation

1.  Navigiere in das `scraper`-Verzeichnis deines Projekts:
    ```bash
    cd path/to/your/project/scraper
    ```
2.  Installiere die Abhängigkeiten:
    ```bash
    npm install
    ```
    oder
    ```bash
    yarn install
    ```
    _Hinweis: Bei der Installation von Playwright (eine Abhängigkeit) werden Browser-Binärdateien heruntergeladen. Dies kann einen Moment dauern._

## Starten des Dienstes

Führe folgenden Befehl im `scraper`-Verzeichnis aus, um den Dienst zu starten:

```bash
node server.js
```

Standardmäßig lauscht der Server auf Port `3000`. Du kannst den Port über die Umgebungsvariable `PORT` ändern:

```bash
PORT=3001 node server.js
```

Wenn der Server erfolgreich gestartet wurde, siehst du eine Meldung wie:
`Scraper server listening on port 3000`

## API Endpunkt

Der Dienst stellt einen einzigen API-Endpunkt zur Verfügung:

- **`POST /scrape`**

### Anfrage-Body (JSON)

Der Endpunkt erwartet einen JSON-Body mit folgenden Feldern:

- `url` (String, erforderlich): Die vollständige URL der Webseite, die gescraped werden soll.
- `containerSelector` (String, erforderlich): Ein CSS-Selector, der auf die übergeordneten Elemente jedes einzelnen Items zeigt, das extrahiert werden soll (z.B. `div.product-item` für eine Produktliste).
- `fieldSelectors` (Array von Objekten, erforderlich): Ein Array, das definiert, welche Daten aus jedem Element extrahiert werden sollen, das durch den `containerSelector` gefunden wurde. Jedes Objekt im Array muss haben:
  - `name` (String): Der Name, unter dem das extrahierte Feld im Ergebnis erscheinen soll (z.B. `productTitle`, `price`).
  - `selector` (String): Der CSS-Selector für das spezifische Datenelement _innerhalb_ des `containerSelector`.
- `linkFieldName` (String, optional): Wenn du einen Link extrahieren möchtest, gib hier den Namen des Feldes an, unter dem der Link im Ergebnis gespeichert werden soll (z.B. `detailsLink`).
- `linkSelector` (String, optional): Der CSS-Selector für das `<a>`-Tag _innerhalb_ des `containerSelector`, dessen `href`-Attribut extrahiert werden soll. Erforderlich, wenn `linkFieldName` angegeben ist.

### Beispiel für einen Anfrage-Body:

```json
{
  "url": "https://quotes.toscrape.com/",
  "containerSelector": "div.quote",
  "fieldSelectors": [
    { "name": "text", "selector": "span.text" },
    { "name": "author", "selector": "small.author" }
  ],
  "linkFieldName": "author_bio_link",
  "linkSelector": "span a"
}
```

### Erfolgsantwort (Status `200 OK`)

Bei Erfolg gibt der Dienst ein JSON-Array zurück. Jedes Element im Array ist ein Objekt, das die gemäß den `fieldSelectors` (und optional dem `linkSelector`) extrahierten Daten für ein gefundenes Item enthält.

Beispiel für eine Erfolgsantwort (basierend auf dem obigen Anfragebeispiel):

```json
[
  {
    "text": "“The world as we have created it is a process of our thinking. It cannot be changed without changing our thinking.”",
    "author": "Albert Einstein",
    "author_bio_link": "/author/Albert-Einstein"
  },
  {
    "text": "“It is our choices, Harry, that show what we truly are, far more than our abilities.”",
    "author": "J.K. Rowling",
    "author_bio_link": "/author/J-K-Rowling"
  }
  // ... weitere extrahierte Zitate
]
```

_Hinweis: Link-URLs werden relativ zur Basis-URL der gescrapten Seite extrahiert, es sei denn, sie sind bereits absolut._

### Fehlerantworten

- **Status `400 Bad Request`**:

  - Wenn erforderliche Felder im Anfrage-Body fehlen (z.B. `url`, `containerSelector`, `fieldSelectors`).
  - Wenn `fieldSelectors` kein Array ist oder leer ist.
  - Wenn ein Objekt in `fieldSelectors` nicht die erforderlichen `name`- oder `selector`-Eigenschaften hat.
  - Wenn `linkFieldName` ohne `linkSelector` (oder umgekehrt) angegeben wird.
    Die Antwort enthält ein JSON-Objekt mit einer `error`-Meldung.
    Beispiel: `{"error": "URL is required"}`

- **Status `500 Internal Server Error`**:
  - Wenn der `containerSelector` auf der Seite nicht innerhalb des Timeouts gefunden werden kann.
  - Wenn während des Scraping-Prozesses ein interner Fehler auftritt (z.B. Playwright-Fehler, Navigationsfehler).
  - Wenn der Scraper-Dienst selbst einen unerwarteten Fehler hat.
    Die Antwort enthält ein JSON-Objekt mit `error` und oft `details`.
    Beispiel: `{"error": "Content not loaded in time based on container selector", "details": "Waiting for selector \"div.nonexistent\" failed: timeout 45000ms exceeded"}`

## Testen des Endpunkts

Du kannst den Endpunkt mit Tools wie `curl`, Postman oder Insomnia testen.

### Beispiel mit `curl`:

Stelle sicher, dass der Server läuft. Öffne dann ein neues Terminalfenster und führe folgenden Befehl aus (ersetze `PORT` falls nötig):

```bash
curl -X POST -H "Content-Type: application/json" \
-d '{
  "url": "https://quotes.toscrape.com/",
  "containerSelector": "div.quote",
  "fieldSelectors": [
    { "name": "quote_text", "selector": "span.text" },
    { "name": "author_name", "selector": "small.author" }
  ],
  "linkFieldName": "author_link",
  "linkSelector": "span a"
}' \
http://localhost:3000/scrape
```

Du solltest eine JSON-Antwort mit den extrahierten Zitaten erhalten.

## Hinweise für die Entwicklung

- **CSS-Selektoren**: Das Finden der korrekten und stabilen CSS-Selektoren ist entscheidend für erfolgreiches Scraping. Nutze die Entwicklertools deines Browsers (Rechtsklick -> Untersuchen), um die HTML-Struktur der Zielseite zu analysieren und passende Selektoren zu identifizieren.
- **Dynamische Inhalte**: Dieser Scraper wartet, bis das Netzwerk relativ ruhig ist (`networkidle`) und der `containerSelector` erscheint. Bei sehr dynamischen Webseiten, die Inhalte stark nachladen oder komplexe JavaScript-Interaktionen erfordern, um Inhalte anzuzeigen, könnten zusätzliche Wartezeiten oder Playwright-Aktionen im `server.js` notwendig werden.
- **Fehlerbehandlung**: Die Protokollierung im `server.js` (`console.log`, `console.error`) gibt Auskunft über den Scraping-Prozess und eventuelle Fehler.
- **Docker**: Das beiliegende `Dockerfile` kann verwendet werden, um den Scraper-Dienst zu containerisieren.
