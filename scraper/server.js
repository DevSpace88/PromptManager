const express = require("express");
const { chromium } = require("playwright"); // Oder firefox, webkit

const app = express();
const port = process.env.PORT || 3000; // Allow port to be configured by environment variable

app.use(express.json()); // Middleware, um JSON-Bodies zu parsen

// The actual scraping logic, now more generic
async function scrapePage(
  url,
  containerSelector,
  fieldSelectors,
  linkFieldName,
  linkSelector,
) {
  console.log(`Scraping URL: ${url}`);
  console.log(`Container Selector: ${containerSelector}`);
  console.log(`Field Selectors: ${JSON.stringify(fieldSelectors)}`);
  if (linkFieldName && linkSelector) {
    console.log(`Link Field: ${linkFieldName}, Link Selector: ${linkSelector}`);
  }

  let browser = null;
  try {
    // Browser starten (Flags sind wichtig für Docker!)
    browser = await chromium.launch({
      headless: true, // Headless-Modus
      args: [
        "--no-sandbox",
        "--disable-setuid-sandbox",
        "--disable-dev-shm-usage", // Oft hilfreich in Docker
        "--disable-accelerated-2d-canvas",
        "--no-first-run",
        "--no-zygote",
        // '--single-process', // Nur für Linux, manchmal nötig, aber veraltet
        "--disable-gpu",
      ],
    });

    const context = await browser.newContext({
      // Gängigen User Agent setzen, um weniger aufzufallen
      userAgent:
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36",
    });
    const page = await context.newPage();

    console.log("Navigating to page...");
    await page.goto(url, { waitUntil: "networkidle", timeout: 60000 }); // Warten bis Netzwerk relativ ruhig ist, Timeout 60s
    console.log(
      "Page loaded, waiting for content based on container selector...",
    );

    if (!containerSelector) {
      console.error("Container selector is mandatory.");
      return { error: "Container selector is mandatory" };
    }

    try {
      await page.waitForSelector(containerSelector, { timeout: 45000 }); // Warte bis zu 45 Sekunden
      console.log("Container elements found.");
    } catch (e) {
      console.error(
        `Container selector "${containerSelector}" not found within timeout.`,
      );
      // Optional: Screenshot oder HTML speichern für Debugging
      // await page.screenshot({ path: 'error_screenshot.png' });
      // const html = await page.content();
      // require('fs').writeFileSync('error_page.html', html);
      return {
        error: "Content not loaded in time based on container selector",
        details: e.message,
      };
    }

    console.log("Extracting data...");
    const extractedData = await page.$$eval(
      containerSelector,
      (items, fieldSelectorsArray, itemLinkFieldName, itemLinkSelector) => {
        return items.map((item) => {
          const data = {};
          fieldSelectorsArray.forEach((fs) => {
            const element = item.querySelector(fs.selector);
            data[fs.name] = element ? element.innerText.trim() : null;
          });

          // Handle link extraction if selectors are provided
          if (itemLinkFieldName && itemLinkSelector) {
            const linkElement = item.querySelector(itemLinkSelector);
            data[itemLinkFieldName] = linkElement ? linkElement.href : null;
          }
          return data;
        });
      },
      fieldSelectors,
      linkFieldName,
      linkSelector,
    ); // Pass fieldSelectors, linkFieldName, linkSelector to $$eval

    console.log(`Found ${extractedData.length} items.`);
    return extractedData;
  } catch (error) {
    console.error("Error during scraping:", error);
    return { error: "Scraping failed", details: error.message };
  } finally {
    if (browser) {
      console.log("Closing browser...");
      await browser.close();
    }
  }
}

// API Endpoint
app.post("/scrape", async (req, res) => {
  const {
    url,
    containerSelector,
    fieldSelectors,
    linkFieldName,
    linkSelector,
  } = req.body;

  if (!url) {
    return res.status(400).json({ error: "URL is required" });
  }
  if (!containerSelector) {
    return res.status(400).json({ error: "containerSelector is required" });
  }
  if (
    !fieldSelectors ||
    !Array.isArray(fieldSelectors) ||
    fieldSelectors.length === 0
  ) {
    return res
      .status(400)
      .json({
        error: "fieldSelectors is required and must be a non-empty array",
      });
  }

  // Validate each field selector
  for (const fs of fieldSelectors) {
    if (!fs.name || !fs.selector) {
      return res
        .status(400)
        .json({
          error: "Each field selector must have a name and a selector property",
        });
    }
  }

  // Validate link selector if link field name is provided
  if (linkFieldName && !linkSelector) {
    return res
      .status(400)
      .json({ error: "linkSelector is required if linkFieldName is provided" });
  }
  if (!linkFieldName && linkSelector) {
    return res
      .status(400)
      .json({ error: "linkFieldName is required if linkSelector is provided" });
  }

  try {
    const data = await scrapePage(
      url,
      containerSelector,
      fieldSelectors,
      linkFieldName,
      linkSelector,
    );
    if (data.error) {
      // Sende den Fehler als Teil der Antwort, damit n8n ihn sehen kann
      // Status 500 signalisiert n8n, dass etwas schief lief
      return res.status(500).json(data);
    }
    res.json(data);
  } catch (error) {
    console.error("API Error:", error);
    res
      .status(500)
      .json({
        error: "Internal server error during scraping",
        details: error.message,
      });
  }
});

app.listen(port, () => {
  console.log(`Scraper server listening on port ${port}`);
});
