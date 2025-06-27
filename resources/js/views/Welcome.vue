<script setup></script>

<template>
  <Head title="Welcome to AI Prompt Flow" />

  <!-- Hero -->
  <div class="bg-body-extra-light">
    <div class="content content-full text-center">
      <div class="row justify-content-center py-8">
        <div class="col-lg-9">
          <div class="d-flex align-items-center justify-content-center gap-3">
            <!-- Ersetze die Icons hier mit passenderen Icons für den PromptManager -->
            <i class="fa fa-3x fa-brain" style="color: #7270ec"></i>
            <i class="fa fa-3x fa-cogs" style="color: #934ee7"></i>
            <i class="fa fa-3x fa-key" style="color: #3fb27f"></i>
          </div>
          <h1 class="fw-black mt-3 mb-2">Willkommen bei AI Prompt Flow!</h1>
          <p class="fs-lg fw-medium text-muted mb-4">
            Verwalte, versioniere und automatisiere deine AI Prompts und
            Workflows. Erstelle einen Account oder logge dich ein, um
            loszulegen.
          </p>
          <div class="d-flex justify-content-center gap-2">
            <Link
              :href="route('register')"
              class="btn btn-lg btn-primary"
              v-click-ripple
            >
              Account erstellen
              <i class="fa fa-fw fa-user-plus ms-1 opacity-50"></i>
            </Link>
            <Link
              :href="route('login')"
              class="btn btn-lg btn-outline-primary"
              v-click-ripple
            >
              Einloggen
              <i class="fa fa-fw fa-sign-in-alt ms-1 opacity-50"></i>
            </Link>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Features -->
  <div id="promptmanager-features" class="bg-body-light">
    <div class="content content-full">
      <div class="py-5">
        <div class="row mb-5">
          <div class="col-md-6">
            <h2 class="h1 fw-black mb-2">
              Mächtige
              <span class="fw-normal">Funktionen</span>
            </h2>
            <p class="fs-lg fw-medium text-muted mb-0">
              AI Prompt Flow bietet dir alles, was du für effizientes Prompt-
              und Workflow-Management benötigst.
            </p>
          </div>
          <div
            class="col-md-6 d-none d-md-flex align-items-md-center justify-content-md-end"
          >
            <p class="h1 fw-bold text-body-bg-dark mb-0">
              Optimiert für Entwickler.
            </p>
          </div>
        </div>
        <div class="row g-6">
          <div class="col-sm-6 col-lg-4">
            <!-- Prompts -->
            <div class="item item-rounded bg-body-extra-light my-4">
              <i class="fa fa-2x fa-pen-alt text-primary"></i>
            </div>
            <h4 class="mb-2">Prompt Management</h4>
            <p class="text-muted mb-0">
              Erstelle, versioniere und organisiere deine Prompts an einem
              zentralen Ort. Nutze Variablen für maximale Flexibilität.
            </p>
            <!-- END Prompts -->
          </div>
          <div class="col-sm-6 col-lg-4">
            <!-- Workflows -->
            <div class="item item-rounded bg-body-extra-light my-4">
              <i class="fa fa-2x fa-diagram-project text-warning"></i>
            </div>
            <h4 class="mb-2">Workflow Automatisierung</h4>
            <p class="text-muted mb-0">
              Verbinde Prompts, API-Aufrufe und Logik zu komplexen,
              automatisierten Workflows, um anspruchsvolle KI-Aufgaben zu
              bewältigen.
            </p>
            <!-- END Workflows -->
          </div>
          <div class="col-sm-6 col-lg-4">
            <!-- API Keys -->
            <div class="item item-rounded bg-body-extra-light my-4">
              <i class="fa fa-2x fa-key text-info"></i>
            </div>
            <h4 class="mb-2">API Key Verwaltung</h4>
            <p class="text-muted mb-0">
              Verbinde deine KI-Service-APIs wie OpenAI, Anthropic oder Google
              sicher und einfach, um sie in deinen Prompts und Workflows zu
              nutzen.
            </p>
            <!-- END API Keys -->
          </div>
          <div class="col-sm-6 col-lg-4">
            <!-- Versionierung -->
            <div class="item item-rounded bg-body-extra-light my-4">
              <i class="fa fa-2x fa-code-branch text-success"></i>
            </div>
            <h4 class="mb-2">Versionierung</h4>
            <p class="text-muted mb-0">
              Behalte den Überblick über Änderungen an deinen Prompts und
              Workflows mit einer integrierten Versionierungsfunktion.
            </p>
            <!-- END Versionierung -->
          </div>
          <div class="col-sm-6 col-lg-4">
            <!-- Kollaboration -->
            <div class="item item-rounded bg-body-extra-light my-4">
              <i class="fa fa-2x fa-users text-danger"></i>
            </div>
            <h4 class="mb-2">Team Kollaboration</h4>
            <p class="text-muted mb-0">
              Arbeite im Team an Prompts und Workflows und teile deine besten
              Kreationen (Feature in Entwicklung).
            </p>
            <!-- END Kollaboration -->
          </div>
          <div class="col-sm-6 col-lg-4">
            <!-- Ausführungs-Log -->
            <div class="item item-rounded bg-body-extra-light my-4">
              <i class="fa fa-2x fa-history text-indigo"></i>
            </div>
            <h4 class="mb-2">Ausführungs-Log</h4>
            <p class="text-muted mb-0">
              Verfolge die Ausführung deiner Workflows und analysiere die
              Ergebnisse, um deine Prozesse kontinuierlich zu verbessern.
            </p>
            <!-- END Ausführungs-Log -->
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- END Features -->

  <!-- Call to action -->
  <div class="bg-body-extra-light">
    <div class="content content-full text-center">
      <div class="py-5">
        <h2 class="h1 fw-black mb-2">Bereit loszulegen?</h2>
        <p class="fs-lg fw-medium text-muted mb-4">
          Erstelle jetzt deinen kostenlosen Account und entdecke die
          Möglichkeiten von AI Prompt Flow.
        </p>
        <Link
          :href="route('register')"
          class="btn btn-lg btn-primary"
          v-click-ripple
        >
          Jetzt registrieren
          <i class="fa fa-fw fa-arrow-right ms-1 opacity-50"></i>
        </Link>
        <p class="mt-3 mb-0">
          Schon einen Account?
          <Link :href="route('login')">Hier einloggen</Link>.
        </p>
      </div>
    </div>
  </div>
  <!-- END Call to action -->
</template>
