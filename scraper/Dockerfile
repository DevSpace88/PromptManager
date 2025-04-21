# Node.js Basisimage
FROM node:18-slim

# Playwright benötigt zusätzliche Abhängigkeiten
RUN apt-get update && apt-get install -y \
    wget \
    gnupg \
    ca-certificates \
    curl \
    --no-install-recommends

# Neuere Methode zur Installation von Google Chrome
RUN curl -fsSL https://dl.google.com/linux/linux_signing_key.pub | gpg --dearmor -o /usr/share/keyrings/google-chrome.gpg \
    && echo "deb [arch=amd64 signed-by=/usr/share/keyrings/google-chrome.gpg] http://dl.google.com/linux/chrome/deb/ stable main" > /etc/apt/sources.list.d/google-chrome.list \
    && apt-get update \
    && apt-get install -y google-chrome-stable fonts-ipafont-gothic fonts-wqy-zenhei fonts-thai-tlwg fonts-kacst fonts-freefont-ttf libxss1 \
      --no-install-recommends \
    && rm -rf /var/lib/apt/lists/*

# Arbeitsverzeichnis setzen
WORKDIR /usr/src/app

# package.json kopieren und Abhängigkeiten installieren
COPY package*.json ./
# Wichtig: "--unsafe-perm" wird oft für Playwright-Installationsskripte benötigt
RUN npm install --production --unsafe-perm

# Den Rest des App-Codes kopieren
COPY . .

# Port freigeben, auf dem der Server lauschen wird
EXPOSE 3000

# Standard-Startbefehl
CMD [ "node", "server.js" ]
