const express = require('express');
const { chromium } = require('playwright'); // Oder firefox, webkit

const app = express();
const port = 3000;

app.use(express.json()); // Middleware, um JSON-Bodies zu parsen

// Die eigentliche Scraping-Logik
async function scrapeLastMinute(url) {
  console.log(`Scraping URL: ${url}`);
  let browser = null;
  try {
    // Browser starten (Flags sind wichtig für Docker!)
    browser = await chromium.launch({
      headless: true, // Headless-Modus
      args: [
        '--no-sandbox',
        '--disable-setuid-sandbox',
        '--disable-dev-shm-usage', // Oft hilfreich in Docker
        '--disable-accelerated-2d-canvas',
        '--no-first-run',
        '--no-zygote',
        // '--single-process', // Nur für Linux, manchmal nötig, aber veraltet
        '--disable-gpu'
      ]
    });

    const context = await browser.newContext({
      // Gängigen User Agent setzen, um weniger aufzufallen
      userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'
    });
    const page = await context.newPage();

    console.log("Navigating to page...");
    await page.goto(url, { waitUntil: 'networkidle', timeout: 60000 }); // Warten bis Netzwerk relativ ruhig ist, Timeout 60s
    console.log("Page loaded, waiting for offers...");

    // *** SEHR WICHTIG: Warte auf ein Element, das erst erscheint, wenn die Angebote geladen sind ***
    // Dieser Selector ist NUR EIN BEISPIEL und muss für lastminute.de GENAU ermittelt werden!
    // Nutze die Entwicklertools im Browser, um einen stabilen Selector zu finden.
    const offerContainerSelector = '[data-testid="card-container"]'; // Beispiel! Muss angepasst werden!
    try {
      await page.waitForSelector(offerContainerSelector, { timeout: 45000 }); // Warte bis zu 45 Sekunden
      console.log("Offer container found.");
    } catch (e) {
      console.error(`Offer container selector "${offerContainerSelector}" not found within timeout.`);
      // Optional: Screenshot oder HTML speichern für Debugging
      // await page.screenshot({ path: 'error_screenshot.png' });
      // const html = await page.content();
      // require('fs').writeFileSync('error_page.html', html);
      return { error: 'Offers not loaded in time', details: e.message };
    }


    console.log("Extracting data...");
    // Daten extrahieren
    const offers = await page.$$eval(offerContainerSelector, (cards) => {
      return cards.map(card => {
        // *** Diese Selectoren sind ebenfalls BEISPIELE und müssen angepasst werden! ***
        const hotelNameEl = card.querySelector('.ftjcvO'); // Beispiel für Hotelnamen-Selector
        const priceEl = card.querySelector('.ktSaL'); // Beispiel für Preis-Selector
        const pricePerPersonEl = card.querySelector('.cEAJDX'); // Beispiel für "p.P."
        const ratingValueEl = card.querySelector('.hTPTFz'); // Beispiel für Bewertungswert
        const ratingTextEl = card.querySelector('.dGvdtZ'); // Beispiel für Bewertungstext
        const reviewsEl = card.querySelector('.bAgvvK'); // Beispiel für Anzahl Bewertungen
        const locationEl = card.querySelector('.hieBFs'); // Beispiel für Standort
        const linkEl = card.querySelector('a[data-testid="gallery"]'); // Beispiel für Link

        const priceRaw = priceEl ? priceEl.innerText.trim() : null;
        const priceText = pricePerPersonEl ? pricePerPersonEl.innerText.trim() : '';

        return {
          hotelName: hotelNameEl ? hotelNameEl.innerText.trim() : null,
          priceRaw: priceRaw,
          pricePerPersonText: priceText,
          ratingValue: ratingValueEl ? ratingValueEl.innerText.trim() : null,
          ratingText: ratingTextEl ? ratingTextEl.innerText.trim() : null,
          reviewsText: reviewsEl ? reviewsEl.innerText.trim() : null,
          location: locationEl ? locationEl.innerText.trim() : null,
          // Link relativ? Falls ja, Basis-URL hinzufügen. Hier als Beispiel angenommen, dass er absolut ist.
          detailsLink: linkEl ? linkEl.href : null,
        };
      }).filter(offer => offer.hotelName); // Nur Angebote mit Hotelnamen behalten
    });

    console.log(`Found ${offers.length} offers.`);
    return offers;

  } catch (error) {
    console.error('Error during scraping:', error);
    return { error: 'Scraping failed', details: error.message };
  } finally {
    if (browser) {
      console.log("Closing browser...");
      await browser.close();
    }
  }
}

// API Endpunkt
app.post('/scrape', async (req, res) => {
  const { url } = req.body;

  if (!url) {
    return res.status(400).json({ error: 'URL is required' });
  }

  try {
    const data = await scrapeLastMinute(url);
    if (data.error) {
      // Sende den Fehler als Teil der Antwort, damit n8n ihn sehen kann
      // Status 500 signalisiert n8n, dass etwas schief lief
      return res.status(500).json(data);
    }
    res.json(data);
  } catch (error) {
    console.error('API Error:', error);
    res.status(500).json({ error: 'Internal server error during scraping', details: error.message });
  }
});

app.listen(port, () => {
  console.log(`Scraper server listening on port ${port}`);
});
