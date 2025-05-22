<!--<template>-->
<!--  <div>-->
<!--    &lt;!&ndash; Page Header &ndash;&gt;-->
<!--    <div class="bg-body-light">-->
<!--      <div class="content content-full">-->
<!--        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">-->
<!--          <div class="flex-grow-1">-->
<!--            <h1 class="h3 fw-bold mb-1">-->
<!--              AI Playground-->
<!--            </h1>-->
<!--            <h2 class="fs-base lh-base fw-medium text-muted mb-0">-->
<!--              {{ prompt.title }}-->
<!--            </h2>-->
<!--          </div>-->
<!--          <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">-->
<!--            <ol class="breadcrumb breadcrumb-alt">-->
<!--              <li class="breadcrumb-item">-->
<!--                <Link :href="route('dashboard')" class="link-fx">Dashboard</Link>-->
<!--              </li>-->
<!--              <li class="breadcrumb-item">-->
<!--                <Link :href="route('playground.index')" class="link-fx">Playground</Link>-->
<!--              </li>-->
<!--              <li class="breadcrumb-item" aria-current="page">-->
<!--                {{ prompt.title }}-->
<!--              </li>-->
<!--            </ol>-->
<!--          </nav>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->
<!--    &lt;!&ndash; END Page Header &ndash;&gt;-->

<!--    &lt;!&ndash; Page Content &ndash;&gt;-->
<!--    <div class="content">-->
<!--      &lt;!&ndash; Tab Navigation &ndash;&gt;-->
<!--      <ul class="nav nav-tabs nav-tabs-block" role="tablist">-->
<!--        <li class="nav-item" role="presentation">-->
<!--          <button class="nav-link active" id="playground-test-tab" data-bs-toggle="tab"-->
<!--                  data-bs-target="#playground-test" type="button" role="tab" aria-controls="playground-test"-->
<!--                  aria-selected="true">-->
<!--            <i class="fa fa-flask me-1"></i> Test-->
<!--          </button>-->
<!--        </li>-->
<!--        <li class="nav-item" role="presentation">-->
<!--          <button class="nav-link" id="playground-compare-tab" data-bs-toggle="tab" data-bs-target="#playground-compare"-->
<!--                  type="button" role="tab" aria-controls="playground-compare" aria-selected="false">-->
<!--            <i class="fa fa-balance-scale me-1"></i> Versionen vergleichen-->
<!--          </button>-->
<!--        </li>-->
<!--        <li class="nav-item" role="presentation">-->
<!--          <button class="nav-link" id="playground-history-tab" data-bs-toggle="tab" data-bs-target="#playground-history"-->
<!--                  type="button" role="tab" aria-controls="playground-history" aria-selected="false">-->
<!--            <i class="fa fa-history me-1"></i> Verlauf-->
<!--          </button>-->
<!--        </li>-->
<!--        <li class="nav-item ms-auto">-->
<!--          <div class="btn-group btn-group-sm">-->
<!--            <Link :href="route('prompts.edit', prompt.id)" class="btn btn-alt-primary">-->
<!--              <i class="fa fa-edit me-1"></i> Prompt bearbeiten-->
<!--            </Link>-->
<!--            <button class="btn btn-alt-primary dropdown-toggle" type="button" id="dropdownOptions"-->
<!--                    data-bs-toggle="dropdown" aria-expanded="false">-->
<!--              <i class="fa fa-cog"></i>-->
<!--            </button>-->
<!--            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownOptions">-->
<!--              <li>-->
<!--                <button class="dropdown-item" @click="duplicatePrompt">-->
<!--                  <i class="fa fa-fw fa-copy me-1"></i> Prompt duplizieren-->
<!--                </button>-->
<!--              </li>-->
<!--              <li>-->
<!--                <button class="dropdown-item" @click="saveCurrentTest">-->
<!--                  <i class="fa fa-fw fa-save me-1"></i> Aktuelle Konfiguration speichern-->
<!--                </button>-->
<!--              </li>-->
<!--              <li>-->
<!--                <button class="dropdown-item" @click="sharePrompt" v-if="false">-->
<!--                  <i class="fa fa-fw fa-share-alt me-1"></i> Prompt teilen-->
<!--                </button>-->
<!--              </li>-->
<!--              <li>-->
<!--                <hr class="dropdown-divider">-->
<!--              </li>-->
<!--              <li>-->
<!--                <button class="dropdown-item text-danger" @click="confirmDelete">-->
<!--                  <i class="fa fa-fw fa-trash me-1"></i> Prompt löschen-->
<!--                </button>-->
<!--              </li>-->
<!--            </ul>-->
<!--          </div>-->
<!--        </li>-->
<!--      </ul>-->

<!--      &lt;!&ndash; Tab Content &ndash;&gt;-->
<!--      <div class="tab-content">-->
<!--        &lt;!&ndash; Test Tab &ndash;&gt;-->
<!--        <div class="tab-pane fade show active" id="playground-test" role="tabpanel"-->
<!--             aria-labelledby="playground-test-tab">-->
<!--          <div class="row">-->
<!--            &lt;!&ndash; Left Column: Prompt & Variables &ndash;&gt;-->
<!--            <div class="col-lg-6">-->
<!--              <div class="block block-rounded h-100">-->
<!--                <div class="block-header block-header-default">-->
<!--                  <h3 class="block-title">Prompt bearbeiten</h3>-->
<!--                  <div class="block-options">-->
<!--                    <div class="dropdown">-->
<!--                      <button class="btn btn-sm btn-alt-secondary dropdown-toggle" type="button" id="dropdownVersions" data-bs-toggle="dropdown" aria-expanded="false">-->
<!--                        Version: {{ currentVersionLabel }}-->
<!--                      </button>-->
<!--                      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownVersions">-->
<!--                        <li v-for="version in availableVersions" :key="version.id">-->
<!--                          <button-->
<!--                            class="dropdown-item"-->
<!--                            @click="loadVersion(version.id)"-->
<!--                            :class="{'active': version.is_current}"-->
<!--                          >-->
<!--                            <i class="fa fa-fw" :class="version.is_current ? 'fa-check' : 'fa-file-alt'"></i>-->
<!--                            Version {{ version.version }} <span v-if="version.is_current">(Aktuell)</span>-->
<!--                            <small class="d-block text-muted">{{ formatDateTime(version.created_at) }}</small>-->
<!--                          </button>-->
<!--                        </li>-->
<!--                      </ul>-->
<!--                    </div>-->
<!--                  </div>-->
<!--                </div>-->
<!--                <div class="block-content h-100 d-flex flex-column">-->
<!--                  <div class="mb-4 flex-grow-1">-->
<!--                    <label class="form-label" for="promptContent">Prompt-Inhalt</label>-->
<!--                    <textarea-->
<!--                      v-model="content"-->
<!--                      class="form-control h-100"-->
<!--                      id="promptContent"-->
<!--                      style="min-height: 200px;"-->
<!--                      placeholder="Geben Sie Ihren Prompt ein..."-->
<!--                    ></textarea>-->
<!--                    <div class="form-text">-->
<!--                      Variablen im Format <code v-pre>{{variable}}</code> werden durch die Werte unten ersetzt.-->
<!--                    </div>-->
<!--                  </div>-->

<!--                  &lt;!&ndash; Variables Section &ndash;&gt;-->
<!--                  <div v-if="variables.length > 0" class="mb-4">-->
<!--                    <div class="d-flex justify-content-between align-items-center mb-2">-->
<!--                      <h4 class="h5 fw-bold mb-0">Variablen</h4>-->
<!--                      <button type="button" class="btn btn-sm btn-alt-secondary" @click="resetVariables">-->
<!--                        <i class="fa fa-undo me-1"></i> Zurücksetzen-->
<!--                      </button>-->
<!--                    </div>-->
<!--                    <div class="row">-->
<!--                      <div-->
<!--                        v-for="(variable, index) in variables"-->
<!--                        :key="index"-->
<!--                        class="col-md-6 mb-3"-->
<!--                      >-->
<!--                        <label class="form-label" :for="`variable-${index}`">{{ variable }}</label>-->
<!--                        <div class="input-group">-->
<!--                          <input-->
<!--                            v-model="variableValues[variable]"-->
<!--                            type="text"-->
<!--                            class="form-control"-->
<!--                            :id="`variable-${index}`"-->
<!--                            :placeholder="`Wert für ${variable}`"-->
<!--                          >-->
<!--                          <button-->
<!--                            class="btn btn-outline-secondary"-->
<!--                            type="button"-->
<!--                            @click="generateVariableValue(variable)"-->
<!--                            title="Vorschlag generieren"-->
<!--                          >-->
<!--                            <i class="fa fa-magic"></i>-->
<!--                          </button>-->
<!--                        </div>-->
<!--                      </div>-->
<!--                    </div>-->
<!--                  </div>-->

<!--                  &lt;!&ndash; Model Settings &ndash;&gt;-->
<!--                  <div class="block block-rounded mb-4">-->
<!--                    <div class="block-header block-header-default">-->
<!--                      <h3 class="block-title">Modell-Einstellungen</h3>-->
<!--                      <div class="block-options">-->
<!--                        <button type="button" class="btn-block-option" @click="toggleSettings">-->
<!--                          <i class="fa" :class="showSettings ? 'fa-chevron-up' : 'fa-chevron-down'"></i>-->
<!--                        </button>-->
<!--                      </div>-->
<!--                    </div>-->
<!--                    <div class="block-content pb-3" v-show="showSettings">-->
<!--                      <div class="row mb-4">-->
<!--                        <div class="col-md-6 mb-3">-->
<!--                          <label class="form-label" for="provider">Provider</label>-->
<!--                          <select id="provider" v-model="provider" class="form-select" @change="updateModelOptions">-->
<!--                            <option value="openai">OpenAI</option>-->
<!--                            <option value="anthropic">Anthropic</option>-->
<!--                            <option value="google">Google (Gemini)</option>-->
<!--                            <option value="ollama">Ollama (Local LLMs)</option>-->
<!--                            <option value="deepseek">DeepSeek</option>-->
<!--                          </select>-->
<!--                        </div>-->
<!--                        <div class="col-md-6 mb-3">-->
<!--                          <label class="form-label" for="model">Modell</label>-->
<!--                          <select id="model" v-model="model" class="form-select">-->
<!--                            <option v-for="option in modelOptions" :key="option.value" :value="option.value">-->
<!--                              {{ option.label }}-->
<!--                            </option>-->
<!--                          </select>-->
<!--                        </div>-->
<!--                      </div>-->

<!--                      <div class="row mb-4">-->
<!--                        <div class="col-md-6 mb-3">-->
<!--                          <label class="form-label" for="temperature">Temperatur: {{ temperature }}</label>-->
<!--                          <input-->
<!--                            type="range"-->
<!--                            class="form-range"-->
<!--                            id="temperature"-->
<!--                            v-model.number="temperature"-->
<!--                            min="0"-->
<!--                            max="2"-->
<!--                            step="0.1"-->
<!--                          >-->
<!--                          <div class="d-flex justify-content-between">-->
<!--                            <small>Präzise (0)</small>-->
<!--                            <small>Kreativ (2)</small>-->
<!--                          </div>-->
<!--                        </div>-->
<!--                        <div class="col-md-6 mb-3">-->
<!--                          <label class="form-label" for="max_tokens">Max Tokens: {{ maxTokens }}</label>-->
<!--                          <input-->
<!--                            type="range"-->
<!--                            class="form-range"-->
<!--                            id="max_tokens"-->
<!--                            v-model.number="maxTokens"-->
<!--                            min="100"-->
<!--                            max="8192"-->
<!--                            step="100"-->
<!--                          >-->
<!--                          <div class="d-flex justify-content-between">-->
<!--                            <small>100</small>-->
<!--                            <small>8192</small>-->
<!--                          </div>-->
<!--                        </div>-->
<!--                      </div>-->

<!--                      <div class="row" v-if="provider === 'custom' || provider === 'ollama'">-->
<!--                        <div class="col-12 mb-3">-->
<!--                          <label class="form-label" for="custom_provider">Server URL</label>-->
<!--                          <input-->
<!--                            type="text"-->
<!--                            class="form-control"-->
<!--                            id="custom_provider"-->
<!--                            v-model="customProviderUrl"-->
<!--                            placeholder="https://api.example.com/v1/completions"-->
<!--                          >-->
<!--                        </div>-->
<!--                      </div>-->
<!--                    </div>-->
<!--                  </div>-->

<!--                  <div class="mb-3">-->
<!--                    <div class="d-flex justify-content-between gap-2">-->
<!--                      <button-->
<!--                        type="button"-->
<!--                        class="btn btn-alt-info"-->
<!--                        @click="enhancePrompt"-->
<!--                        :disabled="isEnhancing || !content.trim()"-->
<!--                      >-->
<!--                        <i v-if="isEnhancing" class="fa fa-spinner fa-spin me-1"></i>-->
<!--                        <i v-else class="fa fa-magic me-1"></i>-->
<!--                        Prompt verbessern-->
<!--                      </button>-->
<!--                      <div>-->
<!--                        <button-->
<!--                          type="button"-->
<!--                          class="btn btn-alt-secondary me-1"-->
<!--                          @click="previewPrompt"-->
<!--                          :disabled="!content.trim()"-->
<!--                          title="Vorschau mit Variablen"-->
<!--                        >-->
<!--                          <i class="fa fa-eye me-1"></i>-->
<!--                          Vorschau-->
<!--                        </button>-->
<!--                        <button-->
<!--                          type="button"-->
<!--                          class="btn btn-primary"-->
<!--                          @click="testPrompt"-->
<!--                          :disabled="isLoading || !content.trim()"-->
<!--                        >-->
<!--                          <i v-if="isLoading" class="fa fa-spinner fa-spin me-1"></i>-->
<!--                          <i v-else class="fa fa-play me-1"></i>-->
<!--                          Ausführen-->
<!--                        </button>-->
<!--                      </div>-->
<!--                    </div>-->
<!--                  </div>-->
<!--                </div>-->
<!--              </div>-->
<!--            </div>-->

<!--            &lt;!&ndash; Right Column: Results &ndash;&gt;-->
<!--            <div class="col-lg-6">-->
<!--              <div class="block block-rounded h-100">-->
<!--                <div class="block-header block-header-default">-->
<!--                  <h3 class="block-title">-->
<!--                    <i class="fa fa-robot me-1"></i> AI Antwort-->
<!--                  </h3>-->
<!--                  <div class="block-options">-->
<!--                    <div class="dropdown d-inline-block">-->
<!--                      <button type="button" class="btn-block-option dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
<!--                        <i class="fa fa-ellipsis-v"></i>-->
<!--                      </button>-->
<!--                      <div class="dropdown-menu dropdown-menu-end">-->
<!--                        <button type="button" class="dropdown-item" @click="copyResponse" v-if="response">-->
<!--                          <i class="fa fa-fw fa-copy me-1"></i> Kopieren-->
<!--                        </button>-->
<!--                        <button type="button" class="dropdown-item" @click="saveAsPrompt" v-if="response">-->
<!--                          <i class="fa fa-fw fa-save me-1"></i> Als Prompt speichern-->
<!--                        </button>-->
<!--                        <button type="button" class="dropdown-item" @click="saveToWorkflow" v-if="response">-->
<!--                          <i class="fa fa-fw fa-sitemap me-1"></i> Zum Workflow hinzufügen-->
<!--                        </button>-->
<!--                        <button type="button" class="dropdown-item" @click="downloadResponse" v-if="response">-->
<!--                          <i class="fa fa-fw fa-file-download me-1"></i> Exportieren-->
<!--                        </button>-->
<!--                      </div>-->
<!--                    </div>-->
<!--                  </div>-->
<!--                </div>-->
<!--                <div class="block-content h-100 d-flex flex-column">-->
<!--                  <div class="position-relative flex-grow-1 mb-4" style="min-height: 300px;">-->
<!--                    <div v-if="isLoading" class="position-absolute inset-0 d-flex flex-column justify-content-center align-items-center bg-body-light bg-opacity-75">-->
<!--                      <div class="spinner-border text-primary mb-3" role="status">-->
<!--                        <span class="visually-hidden">Lädt...</span>-->
<!--                      </div>-->
<!--                      <p class="text-muted">Generiere Antwort...</p>-->
<!--                      <div class="progress w-75 mt-2" style="height: 5px;">-->
<!--                        <div class="progress-bar progress-bar-striped progress-bar-animated" :style="{width: loadingProgress + '%'}"></div>-->
<!--                      </div>-->
<!--                    </div>-->

<!--                    <div v-else-if="response" class="h-100">-->
<!--                      <div class="p-3 bg-body-light rounded h-100 overflow-auto" style="max-height: 500px;">-->
<!--                        <div v-html="formattedResponse" class="fs-sm response-content"></div>-->
<!--                      </div>-->
<!--                    </div>-->

<!--                    <div v-else class="h-100 d-flex flex-column justify-content-center align-items-center bg-body-light rounded">-->
<!--                      <div class="mb-3">-->
<!--                        <i class="fa fa-robot fa-4x text-muted"></i>-->
<!--                      </div>-->
<!--                      <p class="text-muted">Führen Sie den Prompt aus, um eine AI-Antwort zu erhalten.</p>-->
<!--                    </div>-->
<!--                  </div>-->

<!--                  <div v-if="response" class="d-flex justify-content-between text-muted fs-sm mb-3">-->
<!--                    <span>-->
<!--                      <i class="fa fa-clock me-1"></i>-->
<!--                      Generiert in {{ responseTime ? (responseTime/1000).toFixed(2) + 's' : '&#45;&#45;' }}-->
<!--                    </span>-->
<!--                    <span>-->
<!--                      <i class="fa fa-tag me-1"></i>-->
<!--                      {{ model }}-->
<!--                    </span>-->
<!--                  </div>-->

<!--                  <div v-if="response && estimatedCost > 0" class="alert alert-info mb-3">-->
<!--                    <div class="d-flex">-->
<!--                      <div class="flex-shrink-0">-->
<!--                        <i class="fa fa-info-circle fa-2x"></i>-->
<!--                      </div>-->
<!--                      <div class="flex-grow-1 ms-3">-->
<!--                        <h4 class="alert-heading fs-sm fw-bold mb-1">Kosteninformation</h4>-->
<!--                        <p class="fs-sm mb-0">-->
<!--                          Geschätzte Kosten für diesen API-Aufruf: <strong>{{ formatCurrency(estimatedCost) }}</strong>-->
<!--                          <br>-->
<!--                          <small>Basierend auf {{ estimatedTokens }} Tokens ({{ inputTokens }} Eingabe, {{ outputTokens }} Ausgabe)</small>-->
<!--                        </p>-->
<!--                      </div>-->
<!--                    </div>-->
<!--                  </div>-->
<!--                </div>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->

<!--        &lt;!&ndash; Compare Tab &ndash;&gt;-->
<!--        <div class="tab-pane fade" id="playground-compare" role="tabpanel" aria-labelledby="playground-compare-tab">-->
<!--          <div class="block block-rounded">-->
<!--            <div class="block-header block-header-default">-->
<!--              <h3 class="block-title">Prompt-Versionen vergleichen</h3>-->
<!--            </div>-->
<!--            <div class="block-content">-->
<!--              <div class="row mb-4">-->
<!--                <div class="col-md-6">-->
<!--                  <label class="form-label">Zu vergleichende Versionen</label>-->
<!--                  <div class="d-flex flex-wrap gap-2">-->
<!--                    <div v-for="version in availableVersions" :key="version.id" class="form-check">-->
<!--                      <input-->
<!--                        class="form-check-input"-->
<!--                        type="checkbox"-->
<!--                        :value="version.id"-->
<!--                        :id="`version-check-${version.id}`"-->
<!--                        v-model="compareVersionIds"-->
<!--                        :disabled="compareVersionIds.length >= 3 && !compareVersionIds.includes(version.id)"-->
<!--                      >-->
<!--                      <label class="form-check-label" :for="`version-check-${version.id}`">-->
<!--                        Version {{ version.version }}-->
<!--                        <span v-if="version.is_current" class="badge bg-primary ms-1">Aktuell</span>-->
<!--                      </label>-->
<!--                    </div>-->
<!--                  </div>-->
<!--                  <div class="form-text">-->
<!--                    Wählen Sie bis zu 3 Versionen zum Vergleichen aus-->
<!--                  </div>-->
<!--                </div>-->
<!--                <div class="col-md-6">-->
<!--                  <label class="form-label">Variablen für den Vergleich</label>-->
<!--                  <div class="mb-2" v-for="(variable, index) in variables" :key="index">-->
<!--                    <label class="form-label" :for="`compare-var-${index}`">{{ variable }}</label>-->
<!--                    <input-->
<!--                      v-model="compareVariableValues[variable]"-->
<!--                      type="text"-->
<!--                      class="form-control"-->
<!--                      :id="`compare-var-${index}`"-->
<!--                      :placeholder="`Wert für ${variable}`"-->
<!--                    >-->
<!--                  </div>-->
<!--                </div>-->
<!--              </div>-->

<!--              <div class="mb-4">-->
<!--                <div class="d-flex justify-content-end">-->
<!--                  <button-->
<!--                    type="button"-->
<!--                    class="btn btn-primary"-->
<!--                    @click="compareVersions"-->
<!--                    :disabled="isComparing || compareVersionIds.length < 2"-->
<!--                  >-->
<!--                    <i v-if="isComparing" class="fa fa-spinner fa-spin me-1"></i>-->
<!--                    <i v-else class="fa fa-play me-1"></i>-->
<!--                    Versionen vergleichen-->
<!--                  </button>-->
<!--                </div>-->
<!--              </div>-->

<!--              &lt;!&ndash; Comparison Results &ndash;&gt;-->
<!--              <div v-if="comparisonResults.length > 0" class="mb-4">-->
<!--                <div class="row row-deck">-->
<!--                  <div-->
<!--                    v-for="result in comparisonResults"-->
<!--                    :key="result.version_id"-->
<!--                    class="col-md-6 col-xl-4"-->
<!--                  >-->
<!--                    <div class="block block-rounded h-100">-->
<!--                      <div class="block-header block-header-default">-->
<!--                        <h3 class="block-title">Version {{ result.version_number }}</h3>-->
<!--                        <div class="block-options">-->
<!--                          <button type="button" class="btn-block-option" @click="copyVersionResult(result)">-->
<!--                            <i class="fa fa-copy"></i>-->
<!--                          </button>-->
<!--                          <button-->
<!--                            type="button"-->
<!--                            class="btn-block-option"-->
<!--                            @click="setAsCurrentVersion(result.version_id)"-->
<!--                            v-if="!isCurrentVersion(result.version_id)"-->
<!--                          >-->
<!--                            <i class="fa fa-check" title="Als aktuelle Version festlegen"></i>-->
<!--                          </button>-->
<!--                        </div>-->
<!--                      </div>-->
<!--                      <div class="block-content">-->
<!--                        <div class="p-3 bg-body-light rounded" style="max-height: 400px; overflow-y: auto;">-->
<!--                          <div v-html="formatResponse(result.response)" class="fs-sm"></div>-->
<!--                        </div>-->
<!--                      </div>-->
<!--                    </div>-->
<!--                  </div>-->
<!--                </div>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->

<!--        &lt;!&ndash; History Tab &ndash;&gt;-->
<!--        <div class="tab-pane fade" id="playground-history" role="tabpanel" aria-labelledby="playground-history-tab">-->
<!--          <div class="block block-rounded">-->
<!--            <div class="block-header block-header-default">-->
<!--              <h3 class="block-title">Ausführungsverlauf</h3>-->
<!--              <div class="block-options">-->
<!--                <button type="button" class="btn btn-sm btn-alt-secondary" @click="clearHistory">-->
<!--                  <i class="fa fa-trash me-1"></i> Verlauf löschen-->
<!--                </button>-->
<!--              </div>-->
<!--            </div>-->
<!--            <div class="block-content p-0">-->
<!--              <div v-if="promptHistory.length === 0" class="text-center py-4">-->
<!--                <div class="mb-3">-->
<!--                  <i class="fa fa-history fa-4x text-muted"></i>-->
<!--                </div>-->
<!--                <p class="text-muted">Keine Einträge im Ausführungsverlauf.</p>-->
<!--              </div>-->
<!--              <div v-else>-->
<!--                <div class="table-responsive">-->
<!--                  <table class="table table-bordered table-striped table-vcenter">-->
<!--                    <thead>-->
<!--                    <tr>-->
<!--                      <th>Zeitpunkt</th>-->
<!--                      <th>Prompt</th>-->
<!--                      <th>Modell</th>-->
<!--                      <th class="text-center">Aktionen</th>-->
<!--                    </tr>-->
<!--                    </thead>-->
<!--                    <tbody>-->
<!--                    <tr v-for="(item, index) in promptHistory" :key="index">-->
<!--                      <td class="fs-sm">-->
<!--                        {{ formatDateTime(item.timestamp) }}-->
<!--                      </td>-->
<!--                      <td>-->
<!--                        <div class="fw-semibold">{{ truncate(item.content, 50) }}</div>-->
<!--                        <div v-if="Object.keys(item.variables || {}).length > 0" class="fs-sm text-muted">-->
<!--                            <span v-for="(value, key) in item.variables" :key="key" class="me-2">-->
<!--                              <strong>{{ key }}:</strong> {{ truncate(value, 15) }}-->
<!--                            </span>-->
<!--                        </div>-->
<!--                      </td>-->
<!--                      <td class="fs-sm">-->
<!--                        {{ item.provider }} / {{ item.model }}-->
<!--                      </td>-->
<!--                      <td class="text-center">-->
<!--                        <div class="btn-group">-->
<!--                          <button-->
<!--                            type="button"-->
<!--                            class="btn btn-sm btn-alt-secondary"-->
<!--                            @click="selectHistoryItem(item)"-->
<!--                            title="Ausführen"-->
<!--                          >-->
<!--                            <i class="fa fa-fw fa-play"></i>-->
<!--                          </button>-->
<!--                          <button-->
<!--                            type="button"-->
<!--                            class="btn btn-sm btn-alt-secondary"-->
<!--                            @click="viewHistoryItem(item)"-->
<!--                            title="Anzeigen"-->
<!--                          >-->
<!--                            <i class="fa fa-fw fa-eye"></i>-->
<!--                          </button>-->
<!--                          <button-->
<!--                            type="button"-->
<!--                            class="btn btn-sm btn-alt-secondary"-->
<!--                            @click="deleteHistoryItem(index)"-->
<!--                            title="Löschen"-->
<!--                          >-->
<!--                            <i class="fa fa-fw fa-trash"></i>-->
<!--                          </button>-->
<!--                        </div>-->
<!--                      </td>-->
<!--                    </tr>-->
<!--                    </tbody>-->
<!--                  </table>-->
<!--                </div>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->
<!--    &lt;!&ndash; END Page Content &ndash;&gt;-->

<!--    &lt;!&ndash; Modals &ndash;&gt;-->
<!--    &lt;!&ndash; Save as Prompt Modal &ndash;&gt;-->
<!--    <div class="modal" id="modal-save-prompt" tabindex="-1" role="dialog" aria-labelledby="modal-save-prompt" aria-hidden="true">-->
<!--      <div class="modal-dialog" role="document">-->
<!--        <div class="modal-content">-->
<!--          <div class="block block-rounded block-transparent mb-0">-->
<!--            <div class="block-header block-header-default">-->
<!--              <h3 class="block-title">Antwort als Prompt speichern</h3>-->
<!--              <div class="block-options">-->
<!--                <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">-->
<!--                  <i class="fa fa-fw fa-times"></i>-->
<!--                </button>-->
<!--              </div>-->
<!--            </div>-->
<!--            <div class="block-content fs-sm">-->
<!--              <div class="mb-4">-->
<!--                <label class="form-label" for="new-prompt-title">Titel</label>-->
<!--                <input-->
<!--                  v-model="newPromptTitle"-->
<!--                  type="text"-->
<!--                  class="form-control"-->
<!--                  id="new-prompt-title"-->
<!--                  placeholder="Titel für den neuen Prompt"-->
<!--                >-->
<!--              </div>-->
<!--              <div class="mb-4">-->
<!--                <label class="form-label" for="new-prompt-description">Beschreibung (optional)</label>-->
<!--                <textarea-->
<!--                  v-model="newPromptDescription"-->
<!--                  class="form-control"-->
<!--                  id="new-prompt-description"-->
<!--                  rows="3"-->
<!--                  placeholder="Beschreibung für den neuen Prompt"-->
<!--                ></textarea>-->
<!--              </div>-->
<!--              <div class="mb-4">-->
<!--                <label class="form-label" for="new-prompt-tags">Tags (optional)</label>-->
<!--                <input-->
<!--                  v-model="newPromptTags"-->
<!--                  type="text"-->
<!--                  class="form-control"-->
<!--                  id="new-prompt-tags"-->
<!--                  placeholder="Tags durch Komma getrennt (z.B. marketing, email, sales)"-->
<!--                >-->
<!--              </div>-->
<!--            </div>-->
<!--            <div class="block-content block-content-full text-end bg-body">-->
<!--              <button type="button" class="btn btn-sm btn-alt-secondary me-1" data-bs-dismiss="modal">Abbrechen</button>-->
<!--              <button-->
<!--                type="button"-->
<!--                class="btn btn-sm btn-primary"-->
<!--                @click="createPromptFromResponse"-->
<!--                :disabled="!newPromptTitle || isSaving"-->
<!--              >-->
<!--                <i v-if="isSaving" class="fa fa-spinner fa-spin me-1"></i>-->
<!--                <i v-else class="fa fa-save me-1"></i>-->
<!--                Speichern-->
<!--              </button>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->

<!--    &lt;!&ndash; Preview Modal &ndash;&gt;-->
<!--    <div class="modal" id="modal-preview" tabindex="-1" role="dialog" aria-labelledby="modal-preview" aria-hidden="true">-->
<!--      <div class="modal-dialog modal-lg" role="document">-->
<!--        <div class="modal-content">-->
<!--          <div class="block block-rounded block-transparent mb-0">- -->
<!--            <div class="block-header block-header-default">-->
<!--            <h3 class="block-title">Prompt-Vorschau</h3>-->
<!--            <div class="block-options">-->
<!--              <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">-->
<!--                <i class="fa fa-fw fa-times"></i>-->
<!--              </button>-->
<!--            </div>-->
<!--          </div>-->
<!--          <div class="block-content">-->
<!--            <div class="alert alert-info">-->
<!--              <i class="fa fa-info-circle me-1"></i> Dies ist der Prompt mit allen eingesetzten Variablen, so wie er an die AI gesendet wird.-->
<!--            </div>-->
<!--            <div class="p-3 bg-body-light rounded">-->
<!--              <pre class="mb-0">{{ previewContent }}</pre>-->
<!--            </div>-->
<!--          </div>-->
<!--          <div class="block-content block-content-full text-end bg-body">-->
<!--            <button type="button" class="btn btn-sm btn-alt-secondary" data-bs-dismiss="modal">Schließen</button>-->
<!--            <button type="button" class="btn btn-sm btn-alt-primary" @click="copyPreview">-->
<!--              <i class="fa fa-copy me-1"></i> Kopieren-->
<!--            </button>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->
<!--  </div>-->

<!--  &lt;!&ndash; View History Item Modal &ndash;&gt;-->
<!--  <div class="modal" id="modal-history-item" tabindex="-1" role="dialog" aria-labelledby="modal-history-item" aria-hidden="true">-->
<!--    <div class="modal-dialog modal-lg" role="document">-->
<!--      <div class="modal-content">-->
<!--        <div class="block block-rounded block-transparent mb-0">-->
<!--          <div class="block-header block-header-default">-->
<!--            <h3 class="block-title">Verlaufseintrag</h3>-->
<!--            <div class="block-options">-->
<!--              <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">-->
<!--                <i class="fa fa-fw fa-times"></i>-->
<!--              </button>-->
<!--            </div>-->
<!--          </div>-->
<!--          <div class="block-content">-->
<!--            <div class="row mb-4">-->
<!--              <div class="col-md-6">-->
<!--                <h4 class="h5">Prompt</h4>-->
<!--                <div class="p-3 bg-body-light rounded" style="max-height: 300px; overflow-y: auto;">-->
<!--                  <pre class="mb-0">{{ historyItemDetails?.content || '' }}</pre>-->
<!--                </div>-->
<!--                <div class="mt-3" v-if="historyItemDetails && Object.keys(historyItemDetails.variables || {}).length > 0">-->
<!--                  <h5 class="h6">Verwendete Variablen</h5>-->
<!--                  <div class="table-responsive">-->
<!--                    <table class="table table-sm table-vcenter">-->
<!--                      <thead>-->
<!--                      <tr>-->
<!--                        <th>Variable</th>-->
<!--                        <th>Wert</th>-->
<!--                      </tr>-->
<!--                      </thead>-->
<!--                      <tbody>-->
<!--                      <tr v-for="(value, key) in historyItemDetails.variables" :key="key">-->
<!--                        <td><code>{{ key }}</code></td>-->
<!--                        <td>{{ value }}</td>-->
<!--                      </tr>-->
<!--                      </tbody>-->
<!--                    </table>-->
<!--                  </div>-->
<!--                </div>-->
<!--              </div>-->
<!--              <div class="col-md-6">-->
<!--                <h4 class="h5">Antwort</h4>-->
<!--                <div class="p-3 bg-body-light rounded" style="max-height: 300px; overflow-y: auto;">-->
<!--                  <div v-html="formatResponse(historyItemDetails?.response || '')"></div>-->
<!--                </div>-->
<!--                <div class="mt-3">-->
<!--                  <h5 class="h6">Modell-Details</h5>-->
<!--                  <ul class="list-group list-group-flush fs-sm">-->
<!--                    <li class="list-group-item d-flex justify-content-between align-items-center">-->
<!--                      Provider-->
<!--                      <span class="badge bg-primary">{{ historyItemDetails?.provider || '-' }}</span>-->
<!--                    </li>-->
<!--                    <li class="list-group-item d-flex justify-content-between align-items-center">-->
<!--                      Modell-->
<!--                      <span class="badge bg-primary">{{ historyItemDetails?.model || '-' }}</span>-->
<!--                    </li>-->
<!--                    <li class="list-group-item d-flex justify-content-between align-items-center">-->
<!--                      Temperatur-->
<!--                      <span class="badge bg-primary">{{ historyItemDetails?.temperature || '-' }}</span>-->
<!--                    </li>-->
<!--                    <li class="list-group-item d-flex justify-content-between align-items-center">-->
<!--                      Zeitpunkt-->
<!--                      <span>{{ historyItemDetails?.timestamp ? formatDateTime(historyItemDetails.timestamp) : '-' }}</span>-->
<!--                    </li>-->
<!--                  </ul>-->
<!--                </div>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--          <div class="block-content block-content-full text-end bg-body">-->
<!--            <button type="button" class="btn btn-sm btn-alt-secondary me-1" data-bs-dismiss="modal">Schließen</button>-->
<!--            <button-->
<!--              type="button"-->
<!--              class="btn btn-sm btn-primary"-->
<!--              @click="loadHistoryItemAsActive"-->
<!--            >-->
<!--              <i class="fa fa-play me-1"></i> Ausführen-->
<!--            </button>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->
<!--  </div>-->

<!--  &lt;!&ndash; Delete Confirmation Modal &ndash;&gt;-->
<!--  <div class="modal" id="modal-delete-prompt" tabindex="-1" role="dialog" aria-labelledby="modal-delete-prompt" aria-hidden="true">-->
<!--    <div class="modal-dialog" role="document">-->
<!--      <div class="modal-content">-->
<!--        <div class="block block-rounded block-transparent mb-0">-->
<!--          <div class="block-header block-header-default">-->
<!--            <h3 class="block-title">Prompt löschen</h3>-->
<!--            <div class="block-options">-->
<!--              <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">-->
<!--                <i class="fa fa-fw fa-times"></i>-->
<!--              </button>-->
<!--            </div>-->
<!--          </div>-->
<!--          <div class="block-content fs-sm">-->
<!--            <p>Sind Sie sicher, dass Sie diesen Prompt löschen möchten? Diese Aktion kann nicht rückgängig gemacht werden.</p>-->
<!--            <p><strong>{{ prompt?.title }}</strong></p>-->
<!--          </div>-->
<!--          <div class="block-content block-content-full text-end bg-body">-->
<!--            <button type="button" class="btn btn-sm btn-alt-secondary me-1" data-bs-dismiss="modal">Abbrechen</button>-->
<!--            <button type="button" class="btn btn-sm btn-danger" @click="deletePrompt" :disabled="isDeleting">-->
<!--              <i v-if="isDeleting" class="fa fa-spinner fa-spin me-1"></i>-->
<!--              Löschen-->
<!--            </button>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->
<!--  </div>-->
<!--  </div>-->
<!--</template>-->

<!--<script setup>-->
<!--import { ref, computed, watch, onMounted } from 'vue';-->
<!--import { router, Link } from '@inertiajs/vue3';-->
<!--import axios from 'axios';-->
<!--import DOMPurify from 'dompurify';-->
<!--import { marked } from 'marked';-->
<!--import hljs from 'highlight.js';-->

<!--// Props from controller-->
<!--const props = defineProps({-->
<!--  prompt: Object,-->
<!--  currentVersion: Object,-->
<!--});-->

<!--// Reaktive Variablen-->
<!--const content = ref(props.currentVersion?.content || '');-->
<!--const variables = ref([]);-->
<!--const variableValues = ref({});-->
<!--const compareVariableValues = ref({});-->
<!--const provider = ref('openai');-->
<!--const model = ref('gpt-4-turbo');-->
<!--const customProviderUrl = ref('');-->
<!--const temperature = ref(0.7);-->
<!--const maxTokens = ref(2048);-->
<!--const response = ref('');-->
<!--const responseTime = ref(null);-->
<!--const isLoading = ref(false);-->
<!--const loadingProgress = ref(0);-->
<!--const isEnhancing = ref(false);-->
<!--const isComparing = ref(false);-->
<!--const isDeleting = ref(false);-->
<!--const isSaving = ref(false);-->
<!--const promptHistory = ref([]);-->
<!--const historyItemDetails = ref(null);-->
<!--const availableVersions = ref([]);-->
<!--const compareVersionIds = ref([]);-->
<!--const comparisonResults = ref([]);-->
<!--const showSettings = ref(true);-->
<!--const estimatedTokens = ref(0);-->
<!--const inputTokens = ref(0);-->
<!--const outputTokens = ref(0);-->
<!--const estimatedCost = ref(0);-->
<!--const previewContent = ref('');-->

<!--// Neue Variablen für Modaler Dialog-->
<!--const newPromptTitle = ref('');-->
<!--const newPromptDescription = ref('');-->
<!--const newPromptTags = ref('');-->

<!--// Computed properties-->
<!--const formattedResponse = computed(() => {-->
<!--  if (!response.value) return '';-->
<!--  return formatResponse(response.value);-->
<!--});-->

<!--const formatResponse = (responseText) => {-->
<!--  if (!responseText) return '';-->

<!--  // Konfiguriere marked für Code-Highlighting-->
<!--  marked.setOptions({-->
<!--    highlight: function(code, lang) {-->
<!--      if (lang && hljs.getLanguage(lang)) {-->
<!--        return hljs.highlight(code, { language: lang }).value;-->
<!--      }-->
<!--      return hljs.highlightAuto(code).value;-->
<!--    },-->
<!--    breaks: true,-->
<!--    gfm: true-->
<!--  });-->

<!--  // Säubere und transformiere die Markdown-Antwort zu HTML-->
<!--  const clean = DOMPurify.sanitize(marked.parse(responseText));-->
<!--  return clean;-->
<!--};-->

<!--const currentVersionLabel = computed(() => {-->
<!--  const current = availableVersions.value.find(v => v.is_current);-->
<!--  return current ? `Version ${current.version}` : 'Aktuelle Version';-->
<!--});-->

<!--const modelOptions = computed(() => {-->
<!--  if (provider.value === 'openai') {-->
<!--    return [-->
<!--      { value: 'gpt-4-turbo', label: 'GPT-4 Turbo' },-->
<!--      { value: 'gpt-4', label: 'GPT-4' },-->
<!--      { value: 'gpt-3.5-turbo', label: 'GPT-3.5 Turbo' }-->
<!--    ];-->
<!--  } else if (provider.value === 'anthropic') {-->
<!--    return [-->
<!--      { value: 'claude-3-opus', label: 'Claude 3 Opus' },-->
<!--      { value: 'claude-3-sonnet', label: 'Claude 3 Sonnet' },-->
<!--      { value: 'claude-3-haiku', label: 'Claude 3 Haiku' }-->
<!--    ];-->
<!--  } else if (provider.value === 'google') {-->
<!--    return [-->
<!--      { value: 'gemini-pro', label: 'Gemini Pro' },-->
<!--      { value: 'gemini-ultra', label: 'Gemini Ultra' }-->
<!--    ];-->
<!--  } else if (provider.value === 'ollama') {-->
<!--    return [-->
<!--      { value: 'llama3', label: 'Llama 3' },-->
<!--      { value: 'mistral', label: 'Mistral' },-->
<!--      { value: 'phi', label: 'Phi' },-->
<!--      { value: 'codellama', label: 'Code Llama' },-->
<!--      { value: 'custom', label: 'Anderes Modell' }-->
<!--    ];-->
<!--  } else if (provider.value === 'deepseek') {-->
<!--    return [-->
<!--      { value: 'deepseek-coder', label: 'DeepSeek Coder' },-->
<!--      { value: 'deepseek-llm', label: 'DeepSeek LLM' },-->
<!--      { value: 'deepseek-chat', label: 'DeepSeek Chat' }-->
<!--    ];-->
<!--  } else {-->
<!--    return [-->
<!--      { value: 'custom', label: 'Benutzerdefiniert' }-->
<!--    ];-->
<!--  }-->
<!--});-->

<!--// Methoden-->
<!--const extractVariables = () => {-->
<!--  const matches = content.value.match(/\{\{(.*?)\}\}/g) || [];-->
<!--  const extracted = matches.map(match => match.replace(/\{\{|\}\}/g, '').trim());-->
<!--  variables.value = [...new Set(extracted)];-->

<!--  // Initialisiere variableValues und compareVariableValues mit leeren Strings für neue Variablen-->
<!--  variables.value.forEach(variable => {-->
<!--    if (!variableValues.value[variable]) {-->
<!--      variableValues.value[variable] = '';-->
<!--    }-->
<!--    if (!compareVariableValues.value[variable]) {-->
<!--      compareVariableValues.value[variable] = '';-->
<!--    }-->
<!--  });-->
<!--};-->

<!--// Watchers-->
<!--watch(content, () => {-->
<!--  extractVariables();-->
<!--}, { immediate: true });-->

<!--// Aktualisiere das Modell bei Provider-Wechsel-->
<!--watch(provider, () => {-->
<!--  if (modelOptions.value.length > 0) {-->
<!--    model.value = modelOptions.value[0].value;-->
<!--  }-->
<!--});-->



<!--// Laden der verfügbaren Versionen-->
<!--const loadVersions = async () => {-->
<!--  try {-->
<!--    const response = await axios.get(route('prompts.versions', props.prompt.id));-->
<!--    if (response.data && response.data.versions) {-->
<!--      availableVersions.value = response.data.versions;-->
<!--    }-->
<!--  } catch (error) {-->
<!--    console.error('Error loading versions:', error);-->
<!--    notifyError('Fehler beim Laden der Versionen');-->
<!--  }-->
<!--};-->

<!--// Laden einer bestimmten Version-->
<!--const loadVersion = async (versionId) => {-->
<!--  try {-->
<!--    const response = await axios.get(route('prompts.preview.version', [props.prompt.id, versionId]));-->
<!--    if (response.data) {-->
<!--      content.value = response.data.content;-->
<!--      extractVariables();-->
<!--    }-->
<!--  } catch (error) {-->
<!--    console.error('Error loading version:', error);-->
<!--    notifyError('Fehler beim Laden der Version');-->
<!--  }-->
<!--};-->

<!--// Zurücksetzen der Variablenwerte-->
<!--const resetVariables = () => {-->
<!--  variables.value.forEach(variable => {-->
<!--    variableValues.value[variable] = '';-->
<!--  });-->
<!--};-->

<!--// Generieren eines Vorschlags für eine Variable-->
<!--const generateVariableValue = async (variable) => {-->
<!--  // Einfache Implementierung: Schlage einen Wert basierend auf Variablennamen vor-->
<!--  try {-->
<!--    const examples = {-->
<!--      'name': ['Max Mustermann', 'Maria Schmidt', 'John Doe'],-->
<!--      'email': ['example@domain.com', 'user@company.de', 'contact@business.org'],-->
<!--      'company': ['ACME Corp', 'TechStart GmbH', 'Global Industries'],-->
<!--      'product': ['Premium-Paket', 'Enterprise-Lösung', 'Basic-Plan'],-->
<!--      'date': [new Date().toLocaleDateString('de-DE')],-->
<!--      'phone': ['+49 123 4567890', '0123 456789'],-->
<!--      'address': ['Hauptstraße 1, 10115 Berlin', 'Marktplatz 5, 80331 München'],-->
<!--    };-->

<!--    // Suche nach Teilstring-Matches im Variablennamen-->
<!--    const lowerVar = variable.toLowerCase();-->
<!--    let suggestion = '';-->

<!--    for (const [key, values] of Object.entries(examples)) {-->
<!--      if (lowerVar.includes(key)) {-->
<!--        // Zufälligen Wert aus der passenden Kategorie wählen-->
<!--        suggestion = values[Math.floor(Math.random() * values.length)];-->
<!--        break;-->
<!--      }-->
<!--    }-->

<!--    // Wenn kein Match gefunden wurde, verwende einen generischen Platzhalter-->
<!--    if (!suggestion) {-->
<!--      suggestion = `Beispielwert für ${variable}`;-->
<!--    }-->

<!--    variableValues.value[variable] = suggestion;-->

<!--    // Erfolgsbenachrichtigung anzeigen-->
<!--    window.OneUI.helpers('jq-notify', {-->
<!--      type: 'success',-->
<!--      icon: 'fa fa-magic me-1',-->
<!--      message: `Vorschlag für "${variable}" generiert!`-->
<!--    });-->

<!--  } catch (error) {-->
<!--    console.error('Error generating variable value:', error);-->
<!--    notifyError('Fehler beim Generieren des Vorschlags');-->
<!--  }-->
<!--};-->

<!--const updateModelOptions = () => {-->
<!--  // Wenn wir zu einem neuen Provider wechseln, setzen wir das Standard-Modell-->
<!--  if (modelOptions.value.length > 0) {-->
<!--    model.value = modelOptions.value[0].value;-->
<!--  }-->
<!--};-->

<!--// Aktualisiere die Loading-Animation-->
<!--const updateLoadingProgress = () => {-->
<!--  const interval = setInterval(() => {-->
<!--    loadingProgress.value += 5;-->
<!--    if (loadingProgress.value >= 100) {-->
<!--      loadingProgress.value = 0;-->
<!--    }-->
<!--    if (!isLoading.value) {-->
<!--      clearInterval(interval);-->
<!--      loadingProgress.value = 0;-->
<!--    }-->
<!--  }, 300);-->
<!--};-->

<!--// Vorschau des Prompts mit eingesetzten Variablen-->
<!--const previewPrompt = () => {-->
<!--  let processedContent = content.value;-->

<!--  // Variablen ersetzen-->
<!--  for (const [key, value] of Object.entries(variableValues.value)) {-->
<!--    const regex = new RegExp(`\\{\\{${key}\\}\\}`, 'g');-->
<!--    processedContent = processedContent.replace(regex, value || `[${key}]`);-->
<!--  }-->

<!--  previewContent.value = processedContent;-->

<!--  // Modal anzeigen-->
<!--  new bootstrap.Modal(document.getElementById('modal-preview')).show();-->
<!--};-->

<!--// Kopieren der Vorschau in die Zwischenablage-->
<!--const copyPreview = () => {-->
<!--  navigator.clipboard.writeText(previewContent.value)-->
<!--    .then(() => {-->
<!--      window.OneUI.helpers('jq-notify', {-->
<!--        type: 'success',-->
<!--        icon: 'fa fa-check me-1',-->
<!--        message: 'Prompt-Vorschau in die Zwischenablage kopiert!'-->
<!--      });-->
<!--    })-->
<!--    .catch(err => {-->
<!--      console.error('Fehler beim Kopieren:', err);-->
<!--      notifyError('Fehler beim Kopieren');-->
<!--    });-->
<!--};-->

<!--// Ausführen des Prompts-->
<!--const testPrompt = async () => {-->
<!--  if (!content.value.trim()) return;-->

<!--  isLoading.value = true;-->
<!--  loadingProgress.value = 0;-->
<!--  updateLoadingProgress();-->
<!--  const startTime = Date.now();-->
<!--  response.value = '';-->

<!--  try {-->
<!--    const resp = await axios.post(route('playground.test'), {-->
<!--      prompt_id: props.prompt?.id,-->
<!--      content: content.value,-->
<!--      variables: variableValues.value,-->
<!--      provider: provider.value,-->
<!--      model: model.value,-->
<!--      temperature: temperature.value,-->
<!--      max_tokens: maxTokens.value,-->
<!--    });-->

<!--    const endTime = Date.now();-->
<!--    responseTime.value = endTime - startTime;-->

<!--    // Speichere die Antwort-->
<!--    if (resp.data) {-->
<!--      // Check for error-->
<!--      if (resp.data.error) {-->
<!--        notifyError(resp.data.message || 'Fehler bei der Anfrage');-->
<!--        response.value = 'Fehler: ' + (resp.data.message || 'Unbekannter Fehler bei der Anfrage an den AI-Provider');-->
<!--      } else {-->
<!--        // Ergebnis setzen (unterstützt beide Formate: content oder text)-->
<!--        response.value = resp.data.content || resp.data.text || '';-->

<!--        // Token-Schätzung aktualisieren, falls verfügbar-->
<!--        if (resp.data.usage) {-->
<!--          estimatedTokens.value = resp.data.usage.total_tokens || 0;-->
<!--          inputTokens.value = resp.data.usage.prompt_tokens || 0;-->
<!--          outputTokens.value = resp.data.usage.completion_tokens || 0;-->
<!--          estimatedCost.value = calculateCost(provider.value, model.value, inputTokens.value, outputTokens.value);-->
<!--        } else if (resp.data.raw_response && resp.data.raw_response.usage) {-->
<!--          // Falls die Token-Informationen im raw_response Objekt sind-->
<!--          estimatedTokens.value = resp.data.raw_response.usage.total_tokens || 0;-->
<!--          inputTokens.value = resp.data.raw_response.usage.prompt_tokens || 0;-->
<!--          outputTokens.value = resp.data.raw_response.usage.completion_tokens || 0;-->
<!--          estimatedCost.value = calculateCost(provider.value, model.value, inputTokens.value, outputTokens.value);-->
<!--        }-->

<!--        // Zum Verlauf hinzufügen-->
<!--        promptHistory.value.unshift({-->
<!--          content: content.value,-->
<!--          response: response.value,-->
<!--          variables: { ...variableValues.value },-->
<!--          provider: provider.value,-->
<!--          model: model.value,-->
<!--          temperature: temperature.value,-->
<!--          maxTokens: maxTokens.value,-->
<!--          timestamp: new Date().toISOString()-->
<!--        });-->

<!--        // Begrenze Verlauf auf 20 Einträge-->
<!--        if (promptHistory.value.length > 20) {-->
<!--          promptHistory.value = promptHistory.value.slice(0, 20);-->
<!--        }-->

<!--        // Speichere Verlauf im localStorage-->
<!--        saveHistoryToLocalStorage();-->
<!--      }-->
<!--    }-->
<!--  } catch (error) {-->
<!--    console.error('Error testing prompt:', error);-->
<!--    response.value = 'Fehler beim Generieren der Antwort. Bitte versuchen Sie es erneut.';-->
<!--    notifyError('Fehler bei der API-Anfrage');-->
<!--  } finally {-->
<!--    isLoading.value = false;-->
<!--  }-->
<!--};-->

<!--// Berechnung der Kosten basierend auf Modell und Token-Anzahl-->
<!--const calculateCost = (provider, model, inputTokens, outputTokens) => {-->
<!--  // Preise in $ pro 1000 Token (ungefähre Werte, können sich ändern)-->
<!--  const prices = {-->
<!--    'openai': {-->
<!--      'gpt-4': { input: 0.03, output: 0.06 },-->
<!--      'gpt-4-turbo': { input: 0.01, output: 0.03 },-->
<!--      'gpt-3.5-turbo': { input: 0.0005, output: 0.0015 }-->
<!--    },-->
<!--    'anthropic': {-->
<!--      'claude-3-opus': { input: 0.015, output: 0.075 },-->
<!--      'claude-3-sonnet': { input: 0.003, output: 0.015 },-->
<!--      'claude-3-haiku': { input: 0.00025, output: 0.00125 }-->
<!--    },-->
<!--    'google': {-->
<!--      'gemini-pro': { input: 0.00025, output: 0.0005 },-->
<!--      'gemini-ultra': { input: 0.0008, output: 0.0016 }-->
<!--    },-->
<!--    'deepseek': {-->
<!--      'deepseek-coder': { input: 0.0005, output: 0.0015 },-->
<!--      'deepseek-llm': { input: 0.001, output: 0.002 },-->
<!--      'deepseek-chat': { input: 0.001, output: 0.002 }-->
<!--    }-->
<!--  };-->

<!--  if (prices[provider] && prices[provider][model]) {-->
<!--    const inputCost = (inputTokens / 1000) * prices[provider][model].input;-->
<!--    const outputCost = (outputTokens / 1000) * prices[provider][model].output;-->
<!--    return inputCost + outputCost;-->
<!--  }-->

<!--  return 0;-->
<!--};-->

<!--// Formatieren von Währungswerten-->
<!--const formatCurrency = (value) => {-->
<!--  return new Intl.NumberFormat('de-DE', {-->
<!--    style: 'currency',-->
<!--    currency: 'EUR',-->
<!--    minimumFractionDigits: 4,-->
<!--    maximumFractionDigits: 4-->
<!--  }).format(value);-->
<!--};-->

<!--// Verbessern des Prompts-->
<!--const enhancePrompt = async () => {-->
<!--  if (!content.value.trim()) return;-->

<!--  isEnhancing.value = true;-->

<!--  try {-->
<!--    const resp = await axios.post(route('prompts.enhance'), {-->
<!--      prompt: content.value,-->
<!--      context: 'Playground-Test'-->
<!--    });-->

<!--    if (resp.data && resp.data.improved_prompt) {-->
<!--      content.value = resp.data.improved_prompt;-->
<!--      extractVariables();-->

<!--      // Erfolgsbenachrichtigung-->
<!--      window.OneUI.helpers('jq-notify', {-->
<!--        type: 'success',-->
<!--        icon: 'fa fa-magic me-1',-->
<!--        message: 'Prompt wurde verbessert!'-->
<!--      });-->
<!--    }-->
<!--  } catch (error) {-->
<!--    console.error('Error enhancing prompt:', error);-->
<!--    notifyError('Fehler beim Verbessern des Prompts');-->
<!--  } finally {-->
<!--    isEnhancing.value = false;-->
<!--  }-->
<!--};-->

<!--// Kopieren der Antwort in die Zwischenablage-->
<!--const copyResponse = () => {-->
<!--  if (!response.value) return;-->

<!--  navigator.clipboard.writeText(response.value)-->
<!--    .then(() => {-->
<!--      window.OneUI.helpers('jq-notify', {-->
<!--        type: 'success',-->
<!--        icon: 'fa fa-check me-1',-->
<!--        message: 'Antwort in die Zwischenablage kopiert!'-->
<!--      });-->
<!--    })-->
<!--    .catch(err => {-->
<!--      console.error('Fehler beim Kopieren:', err);-->
<!--      notifyError('Fehler beim Kopieren');-->
<!--    });-->
<!--};-->

<!--// Kopieren eines Vergleichsergebnisses-->
<!--const copyVersionResult = (result) => {-->
<!--  if (!result || !result.response) return;-->

<!--  navigator.clipboard.writeText(result.response)-->
<!--    .then(() => {-->
<!--      window.OneUI.helpers('jq-notify', {-->
<!--        type: 'success',-->
<!--        icon: 'fa fa-check me-1',-->
<!--        message: 'Antwort für Version ' + result.version_number + ' kopiert!'-->
<!--      });-->
<!--    })-->
<!--    .catch(err => {-->
<!--      console.error('Fehler beim Kopieren:', err);-->
<!--    });-->
<!--};-->

<!--// Festlegen einer Version als aktuelle Version-->
<!--const setAsCurrentVersion = async (versionId) => {-->
<!--  try {-->
<!--    await axios.post(route('prompts.versions.set-current', [props.prompt.id, versionId]));-->

<!--    // Aktualisiere die Versionen nach dem Setzen-->
<!--    await loadVersions();-->

<!--    // Zeige Bestätigung-->
<!--    window.OneUI.helpers('jq-notify', {-->
<!--      type: 'success',-->
<!--      icon: 'fa fa-check me-1',-->
<!--      message: 'Version wurde als aktuell festgelegt!'-->
<!--    });-->
<!--  } catch (error) {-->
<!--    console.error('Error setting version:', error);-->
<!--    notifyError('Fehler beim Festlegen der Version');-->
<!--  }-->
<!--};-->

<!--// Prüfen, ob eine Version die aktuelle ist-->
<!--const isCurrentVersion = (versionId) => {-->
<!--  const version = availableVersions.value.find(v => v.id === versionId);-->
<!--  return version && version.is_current;-->
<!--};-->

<!--// Modal zum Speichern als Prompt öffnen-->
<!--const saveAsPrompt = () => {-->
<!--  newPromptTitle.value = '';-->
<!--  newPromptDescription.value = '';-->
<!--  newPromptTags.value = '';-->

<!--  // OneUI modal anzeigen-->
<!--  new bootstrap.Modal(document.getElementById('modal-save-prompt')).show();-->
<!--};-->

<!--// Speichern der aktuellen Testkonfiguration-->
<!--const saveCurrentTest = () => {-->
<!--  // Diese Funktion wird die aktuelle Konfiguration speichern-->
<!--  window.OneUI.helpers('jq-notify', {-->
<!--    type: 'info',-->
<!--    icon: 'fa fa-info-circle me-1',-->
<!--    message: 'Diese Funktion wird in einer zukünftigen Version verfügbar sein.'-->
<!--  });-->
<!--};-->

<!--// Erstellen eines neuen Prompts aus der Antwort-->
<!--const createPromptFromResponse = () => {-->
<!--  if (!response.value || !newPromptTitle.value) return;-->

<!--  isSaving.value = true;-->

<!--  // Tags verarbeiten: Aus Komma-getrennter Liste ein Array machen-->
<!--  const tags = newPromptTags.value-->
<!--    .split(',')-->
<!--    .map(tag => tag.trim())-->
<!--    .filter(tag => tag.length > 0);-->

<!--  router.post(route('prompts.store'), {-->
<!--    title: newPromptTitle.value,-->
<!--    description: newPromptDescription.value,-->
<!--    content: response.value,-->
<!--    tags: tags.length > 0 ? tags : null,-->
<!--  }, {-->
<!--    onSuccess: () => {-->
<!--      // Modal wird durch Inertia-Refresh automatisch geschlossen-->
<!--      isSaving.value = false;-->

<!--      // Benachrichtigung anzeigen-->
<!--      window.OneUI.helpers('jq-notify', {-->
<!--        type: 'success',-->
<!--        icon: 'fa fa-check me-1',-->
<!--        message: 'Prompt wurde erfolgreich gespeichert!'-->
<!--      });-->
<!--    },-->
<!--    onError: () => {-->
<!--      isSaving.value = false;-->
<!--      notifyError('Fehler beim Speichern des Prompts');-->
<!--    }-->
<!--  });-->
<!--};-->

<!--// Eine Antwort exportieren-->
<!--const downloadResponse = () => {-->
<!--  if (!response.value) return;-->

<!--  // Dateinamen aus Prompt-Titel oder Standardname-->
<!--  const filename = props.prompt?.title-->
<!--    ? `${props.prompt.title.toLowerCase().replace(/\s+/g, '-')}-response.md`-->
<!--    : 'ai-response.md';-->

<!--  // Inhalt für den Download vorbereiten-->
<!--  const content = `# ${props.prompt?.title || 'AI Antwort'}\n\n${response.value}`;-->

<!--  // Blob erstellen und Download starten-->
<!--  const blob = new Blob([content], { type: 'text/markdown' });-->
<!--  const url = URL.createObjectURL(blob);-->

<!--  const a = document.createElement('a');-->
<!--  a.href = url;-->
<!--  a.download = filename;-->
<!--  document.body.appendChild(a);-->
<!--  a.click();-->

<!--  // Aufräumen-->
<!--  setTimeout(() => {-->
<!--    document.body.removeChild(a);-->
<!--    URL.revokeObjectURL(url);-->
<!--  }, 0);-->
<!--};-->

<!--// Prompt zum Workflow hinzufügen-->
<!--const saveToWorkflow = () => {-->
<!--  // Diese Funktion würde zu einem Workflow-Editor weiterleiten-->
<!--  // oder ein Modal zum Auswählen/Erstellen eines Workflows öffnen-->
<!--  window.OneUI.helpers('jq-notify', {-->
<!--    type: 'info',-->
<!--    icon: 'fa fa-info-circle me-1',-->
<!--    message: 'Diese Funktion wird in einer zukünftigen Version verfügbar sein.'-->
<!--  });-->
<!--};-->

<!--// Prompt teilen-->
<!--const sharePrompt = () => {-->
<!--  // Diese Funktion würde den Prompt teilen-->
<!--  window.OneUI.helpers('jq-notify', {-->
<!--    type: 'info',-->
<!--    icon: 'fa fa-info-circle me-1',-->
<!--    message: 'Diese Funktion wird in einer zukünftigen Version verfügbar sein.'-->
<!--  });-->
<!--};-->

<!--// Prompt duplizieren-->
<!--const duplicatePrompt = () => {-->
<!--  router.post(route('prompts.store'), {-->
<!--    title: `Kopie von ${props.prompt.title}`,-->
<!--    description: props.prompt.description,-->
<!--    content: content.value,-->
<!--    tags: props.prompt.tags,-->
<!--  }, {-->
<!--    onSuccess: () => {-->
<!--      window.OneUI.helpers('jq-notify', {-->
<!--        type: 'success',-->
<!--        icon: 'fa fa-copy me-1',-->
<!--        message: 'Prompt wurde dupliziert!'-->
<!--      });-->
<!--    },-->
<!--    onError: () => {-->
<!--      notifyError('Fehler beim Duplizieren des Prompts');-->
<!--    }-->
<!--  });-->
<!--};-->

<!--// Bestätigungsdialog für das Löschen-->
<!--const confirmDelete = () => {-->
<!--  // Modal anzeigen-->
<!--  new bootstrap.Modal(document.getElementById('modal-delete-prompt')).show();-->
<!--};-->

<!--// Prompt löschen-->
<!--const deletePrompt = () => {-->
<!--  isDeleting.value = true;-->

<!--  router.delete(route('prompts.destroy', props.prompt.id), {-->
<!--    onSuccess: () => {-->
<!--      isDeleting.value = false;-->
<!--      // Weiterleitung wird durch Inertia-Refresh automatisch durchgeführt-->
<!--    },-->
<!--    onError: () => {-->
<!--      isDeleting.value = false;-->
<!--      notifyError('Fehler beim Löschen des Prompts');-->
<!--    }-->
<!--  });-->
<!--};-->

<!--// Vergleich von Versionen-->
<!--const compareVersions = async () => {-->
<!--  if (compareVersionIds.value.length < 2) return;-->

<!--  isComparing.value = true;-->
<!--  comparisonResults.value = [];-->

<!--  try {-->
<!--    const resp = await axios.post(route('playground.compare'), {-->
<!--      prompt_id: props.prompt.id,-->
<!--      version_ids: compareVersionIds.value,-->
<!--      variables: compareVariableValues.value,-->
<!--      provider: provider.value,-->
<!--      model: model.value,-->
<!--      temperature: temperature.value,-->
<!--      max_tokens: maxTokens.value,-->
<!--    });-->

<!--    if (resp.data && resp.data.results) {-->
<!--      comparisonResults.value = resp.data.results;-->
<!--    }-->
<!--  } catch (error) {-->
<!--    console.error('Error comparing versions:', error);-->
<!--    notifyError('Fehler beim Vergleichen der Versionen');-->
<!--  } finally {-->
<!--    isComparing.value = false;-->
<!--  }-->
<!--};-->

<!--// Aus Verlauf auswählen-->
<!--const selectHistoryItem = (item) => {-->
<!--  content.value = item.content;-->
<!--  response.value = item.response;-->
<!--  provider.value = item.provider;-->
<!--  model.value = item.model;-->
<!--  temperature.value = item.temperature;-->
<!--  maxTokens.value = item.maxTokens;-->

<!--  // Variablen setzen-->
<!--  if (item.variables) {-->
<!--    variableValues.value = { ...item.variables };-->
<!--  }-->

<!--  // Wechsle zum Playground-Tab-->
<!--  document.getElementById('playground-test-tab').click();-->
<!--};-->

<!--// Verlaufseintrag anzeigen-->
<!--const viewHistoryItem = (item) => {-->
<!--  historyItemDetails.value = item;-->
<!--  new bootstrap.Modal(document.getElementById('modal-history-item')).show();-->
<!--};-->

<!--// Verlaufseintrag zum aktuellen Prompt laden-->
<!--const loadHistoryItemAsActive = () => {-->
<!--  if (!historyItemDetails.value) return;-->

<!--  selectHistoryItem(historyItemDetails.value);-->

<!--  // Modal schließen-->
<!--  bootstrap.Modal.getInstance(document.getElementById('modal-history-item')).hide();-->
<!--};-->

<!--// Verlaufseintrag löschen-->
<!--const deleteHistoryItem = (index) => {-->
<!--  promptHistory.value.splice(index, 1);-->
<!--  saveHistoryToLocalStorage();-->
<!--};-->

<!--// Verlauf löschen-->
<!--const clearHistory = () => {-->
<!--  promptHistory.value = [];-->
<!--  localStorage.removeItem('promptHistory');-->

<!--  window.OneUI.helpers('jq-notify', {-->
<!--    type: 'info',-->
<!--    icon: 'fa fa-trash me-1',-->
<!--    message: 'Verlauf wurde gelöscht!'-->
<!--  });-->
<!--};-->

<!--// Text kürzen für die Anzeige-->
<!--const truncate = (text, length) => {-->
<!--  if (!text) return '';-->
<!--  return text.length > length ? text.substring(0, length) + '...' : text;-->
<!--};-->

<!--// Formatierung von Datum und Uhrzeit-->
<!--const formatDateTime = (dateString) => {-->
<!--  if (!dateString) return '';-->

<!--  const date = new Date(dateString);-->
<!--  return new Intl.DateTimeFormat('de-DE', {-->
<!--    day: '2-digit',-->
<!--    month: '2-digit',-->
<!--    year: 'numeric',-->
<!--    hour: '2-digit',-->
<!--    minute: '2-digit'-->
<!--  }).format(date);-->
<!--};-->

<!--// Anzeigen einer Fehlermeldung-->
<!--const notifyError = (message) => {-->
<!--  window.OneUI.helpers('jq-notify', {-->
<!--    type: 'danger',-->
<!--    icon: 'fa fa-times me-1',-->
<!--    message: message-->
<!--  });-->
<!--};-->

<!--// Zeigen oder Verstecken der Einstellungen-->
<!--const toggleSettings = () => {-->
<!--  showSettings.value = !showSettings.value;-->
<!--};-->

<!--// Verlauf im localStorage speichern-->
<!--const saveHistoryToLocalStorage = () => {-->
<!--  try {-->
<!--    localStorage.setItem(`promptHistory_${props.prompt.id}`, JSON.stringify(promptHistory.value));-->
<!--  } catch (e) {-->
<!--    console.error('Error saving history to localStorage:', e);-->
<!--  }-->
<!--};-->

<!--// Verlauf aus localStorage laden-->
<!--const loadHistoryFromLocalStorage = () => {-->
<!--  try {-->
<!--    const saved = localStorage.getItem(`promptHistory_${props.prompt.id}`);-->
<!--    if (saved) {-->
<!--      promptHistory.value = JSON.parse(saved);-->
<!--    }-->
<!--  } catch (e) {-->
<!--    console.error('Error loading history from localStorage:', e);-->
<!--  }-->
<!--};-->

<!--// Initialisierung-->
<!--onMounted(async () => {-->
<!--  // Extrahiere Variablen beim Laden-->
<!--  extractVariables();-->

<!--  // Verfügbare Versionen laden-->
<!--  await loadVersions();-->

<!--  // Lade Verlauf aus localStorage-->
<!--  loadHistoryFromLocalStorage();-->

<!--  // Model entsprechend des Providers einstellen-->
<!--  updateModelOptions();-->

<!--  // Standard-Vergleichsversionen setzen (falls verfügbar)-->
<!--  if (availableVersions.value.length >= 2) {-->
<!--    compareVersionIds.value = [-->
<!--      availableVersions.value[0].id,-->
<!--      availableVersions.value[1].id-->
<!--    ];-->
<!--  }-->
<!--});-->
<!--</script>-->

<!--<style>-->
<!--/* Add syntax highlighting styles */-->
<!--@import 'highlight.js/styles/github.css';-->

<!--/* Additional CSS for the response area */-->
<!--.response-content {-->
<!--  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;-->
<!--  line-height: 1.6;-->
<!--}-->

<!--.response-content pre {-->
<!--  background-color: #f5f7f9;-->
<!--  border-radius: 4px;-->
<!--  padding: 12px;-->
<!--  overflow-x: auto;-->
<!--}-->

<!--.response-content code {-->
<!--  background-color: #f0f2f5;-->
<!--  border-radius: 3px;-->
<!--  padding: 2px 4px;-->
<!--  font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;-->
<!--}-->

<!--.response-content blockquote {-->
<!--  border-left: 4px solid #e9ecef;-->
<!--  padding-left: 1rem;-->
<!--  color: #6c757d;-->
<!--}-->

<!--.response-content table {-->
<!--  width: 100%;-->
<!--  border-collapse: collapse;-->
<!--  margin-bottom: 1rem;-->
<!--}-->

<!--.response-content th,-->
<!--.response-content td {-->
<!--  padding: 0.5rem;-->
<!--  border: 1px solid #dee2e6;-->
<!--}-->

<!--.response-content th {-->
<!--  background-color: #f8f9fa;-->
<!--}-->
<!--</style>-->


<template>
  <div>
    <!-- Page Header -->
    <div class="bg-body-light">
      <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-3">
          <div class="flex-grow-1">
            <h1 class="h3 fw-bold mb-2">
              AI Playground
            </h1>
            <h2 class="fs-base lh-base fw-medium text-muted mb-0">
              {{ prompt.title }}
            </h2>
          </div>
          <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-alt mb-0">
              <li class="breadcrumb-item">
                <Link :href="route('dashboard')" class="link-fx">Dashboard</Link>
              </li>
              <li class="breadcrumb-item">
                <Link :href="route('playground.index')" class="link-fx">Playground</Link>
              </li>
              <li class="breadcrumb-item active" aria-current="page">
                {{ prompt.title }}
              </li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <!-- END Page Header -->

    <!-- Page Content -->
    <div class="content">
      <!-- Tab Navigation -->
      <ul class="nav nav-tabs nav-tabs-block mb-3" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="playground-test-tab" data-bs-toggle="tab"
                  data-bs-target="#playground-test" type="button" role="tab" aria-controls="playground-test"
                  aria-selected="true">
            <i class="fa fa-flask me-1"></i> Test
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="playground-compare-tab" data-bs-toggle="tab" data-bs-target="#playground-compare"
                  type="button" role="tab" aria-controls="playground-compare" aria-selected="false">
            <i class="fa fa-balance-scale me-1"></i> Versionen vergleichen
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="playground-history-tab" data-bs-toggle="tab" data-bs-target="#playground-history"
                  type="button" role="tab" aria-controls="playground-history" aria-selected="false">
            <i class="fa fa-history me-1"></i> Verlauf
          </button>
        </li>
        <li class="nav-item ms-auto d-flex align-items-center">
          <div class="btn-group btn-group-sm">
            <Link :href="route('prompts.edit', prompt.id)" class="btn btn-alt-primary">
              <i class="fa fa-edit me-1"></i> Prompt bearbeiten
            </Link>
            <button class="btn btn-alt-primary dropdown-toggle" type="button" id="dropdownOptions"
                    data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fa fa-cog"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownOptions">
              <li>
                <button class="dropdown-item" @click="duplicatePrompt">
                  <i class="fa fa-fw fa-copy me-1"></i> Prompt duplizieren
                </button>
              </li>
              <li>
                <button class="dropdown-item" @click="saveCurrentTest">
                  <i class="fa fa-fw fa-save me-1"></i> Aktuelle Konfiguration speichern
                </button>
              </li>
              <li v-if="false">
                <button class="dropdown-item" @click="sharePrompt">
                  <i class="fa fa-fw fa-share-alt me-1"></i> Prompt teilen
                </button>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <button class="dropdown-item text-danger" @click="confirmDelete">
                  <i class="fa fa-fw fa-trash me-1"></i> Prompt löschen
                </button>
              </li>
            </ul>
          </div>
        </li>
      </ul>

      <!-- Tab Content -->
      <div class="tab-content">
        <!-- Test Tab -->
        <div class="tab-pane fade show active" id="playground-test" role="tabpanel" aria-labelledby="playground-test-tab">
          <div class="row g-4">
            <!-- Left Column: Prompt & Variables -->
            <div class="col-lg-6">
              <div class="block block-rounded h-100">
                <div class="block-header block-header-default">
                  <h3 class="block-title">Prompt bearbeiten</h3>
                  <div class="block-options">
                    <div class="dropdown">
                      <button class="btn btn-sm btn-alt-secondary dropdown-toggle" type="button" id="dropdownVersions"
                              data-bs-toggle="dropdown" aria-expanded="false">
                        Version: {{ currentVersionLabel }}
                      </button>
                      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownVersions">
                        <li v-for="version in availableVersions" :key="version.id">
                          <button
                            class="dropdown-item d-flex align-items-center"
                            @click="loadVersion(version.id)"
                            :class="{'active': version.is_current}"
                          >
                            <i class="fa fa-fw me-2" :class="version.is_current ? 'fa-check' : 'fa-file-alt'"></i>
                            <div>
                              Version {{ version.version }}
                              <span v-if="version.is_current" class="badge bg-primary ms-1">Aktuell</span>
                              <small class="d-block text-muted">{{ formatDateTime(version.created_at) }}</small>
                            </div>
                          </button>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="block-content">
                  <div class="mb-4">
                    <label class="form-label" for="promptContent">Prompt-Inhalt</label>
                    <textarea
                      v-model="content"
                      class="form-control"
                      id="promptContent"
                      rows="8"
                      placeholder="Geben Sie Ihren Prompt ein..."
                    ></textarea>
                    <div class="form-text">
                      Variablen im Format <code v-pre>{{variable}}</code> werden durch die Werte unten ersetzt.
                    </div>
                  </div>

                  <!-- Variables Section -->
                  <div v-if="variables.length > 0" class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <h4 class="h5 fw-bold mb-0">Variablen</h4>
                      <button type="button" class="btn btn-sm btn-alt-secondary" @click="resetVariables">
                        <i class="fa fa-undo me-1"></i> Zurücksetzen
                      </button>
                    </div>
                    <div class="row g-3">
                      <div
                        v-for="(variable, index) in variables"
                        :key="index"
                        class="col-md-6"
                      >
                        <label class="form-label" :for="`variable-${index}`">{{ variable }}</label>
                        <div class="input-group">
                          <input
                            v-model="variableValues[variable]"
                            type="text"
                            class="form-control"
                            :id="`variable-${index}`"
                            :placeholder="`Wert für ${variable}`"
                          >
                          <button
                            class="btn btn-outline-secondary"
                            type="button"
                            @click="generateVariableValue(variable)"
                            title="Vorschlag generieren"
                          >
                            <i class="fa fa-magic"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Model Settings -->
                  <div class="block block-rounded mb-4">
                    <div class="block-header block-header-default">
                      <h3 class="block-title">Modell-Einstellungen</h3>
                      <div class="block-options">
                        <button type="button" class="btn-block-option" @click="toggleSettings">
                          <i class="fa" :class="showSettings ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </button>
                      </div>
                    </div>
                    <div class="block-content" v-show="showSettings">
                      <div class="row g-3 mb-4">
                        <div class="col-md-6">
                          <label class="form-label" for="provider">Provider</label>
                          <select id="provider" v-model="provider" class="form-select" @change="updateModelOptions">
                            <option value="openai">OpenAI</option>
                            <option value="anthropic">Anthropic</option>
                            <option value="google">Google (Gemini)</option>
                            <option value="ollama">Ollama (Local LLMs)</option>
                            <option value="deepseek">DeepSeek</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label" for="model">Modell</label>
                          <select id="model" v-model="model" class="form-select">
                            <option v-for="option in modelOptions" :key="option.value" :value="option.value">
                              {{ option.label }}
                            </option>
                          </select>
                        </div>
                      </div>

                      <div class="row g-3 mb-4">
                        <div class="col-md-6">
                          <label class="form-label" for="temperature">Temperatur: {{ temperature }}</label>
                          <input
                            type="range"
                            class="form-range"
                            id="temperature"
                            v-model.number="temperature"
                            min="0"
                            max="2"
                            step="0.1"
                          >
                          <div class="d-flex justify-content-between fs-sm text-muted">
                            <small>Präzise (0)</small>
                            <small>Kreativ (2)</small>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label" for="max_tokens">Max Tokens: {{ maxTokens }}</label>
                          <input
                            type="range"
                            class="form-range"
                            id="max_tokens"
                            v-model.number="maxTokens"
                            min="100"
                            max="8192"
                            step="100"
                          >
                          <div class="d-flex justify-content-between fs-sm text-muted">
                            <small>100</small>
                            <small>8192</small>
                          </div>
                        </div>
                      </div>

                      <div class="row g-3" v-if="provider === 'custom' || provider === 'ollama'">
                        <div class="col-12">
                          <label class="form-label" for="custom_provider">Server URL</label>
                          <input
                            type="text"
                            class="form-control"
                            id="custom_provider"
                            v-model="customProviderUrl"
                            placeholder="https://api.example.com/v1/completions"
                          >
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="d-sm-flex justify-content-between align-items-center mt-4">
                    <button
                      type="button"
                      class="btn btn-alt-info mb-2 mb-sm-0"
                      @click="enhancePrompt"
                      :disabled="isEnhancing || !content.trim()"
                    >
                      <i v-if="isEnhancing" class="fa fa-spinner fa-spin me-1"></i>
                      <i v-else class="fa fa-magic me-1"></i>
                      Prompt verbessern
                    </button>
                    <div class="d-flex gap-2">
                      <button
                        type="button"
                        class="btn btn-alt-secondary"
                        @click="previewPrompt"
                        :disabled="!content.trim()"
                        title="Vorschau mit Variablen"
                      >
                        <i class="fa fa-eye me-1"></i>
                        Vorschau
                      </button>
                      <button
                        type="button"
                        class="btn btn-primary"
                        @click="testPrompt"
                        :disabled="isLoading || !content.trim()"
                      >
                        <i v-if="isLoading" class="fa fa-spinner fa-spin me-1"></i>
                        <i v-else class="fa fa-play me-1"></i>
                        Ausführen
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Right Column: Results -->
            <div class="col-lg-6">
              <div class="block block-rounded h-100">
                <div class="block-header block-header-default">
                  <h3 class="block-title">
                    <i class="fa fa-robot me-1"></i> AI Antwort
                  </h3>
                  <div class="block-options">
                    <div class="dropdown">
                      <button type="button" class="btn-block-option dropdown-toggle" data-bs-toggle="dropdown"
                              aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-v"></i>
                      </button>
                      <div class="dropdown-menu dropdown-menu-end">
                        <button type="button" class="dropdown-item" @click="copyResponse"
                                v-if="response" :disabled="!response">
                          <i class="fa fa-fw fa-copy me-1"></i> Kopieren
                        </button>
                        <button type="button" class="dropdown-item" @click="saveAsPrompt"
                                v-if="response" :disabled="!response">
                          <i class="fa fa-fw fa-save me-1"></i> Als Prompt speichern
                        </button>
                        <button type="button" class="dropdown-item" @click="saveToWorkflow"
                                v-if="response" :disabled="!response">
                          <i class="fa fa-fw fa-sitemap me-1"></i> Zum Workflow hinzufügen
                        </button>
                        <button type="button" class="dropdown-item" @click="downloadResponse"
                                v-if="response" :disabled="!response">
                          <i class="fa fa-fw fa-file-download me-1"></i> Exportieren
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="block-content">
                  <div class="position-relative" style="min-height: 300px;">
                    <!-- Loading State -->
                    <div v-if="isLoading" class="position-absolute w-100 h-100 d-flex flex-column justify-content-center align-items-center bg-body-light bg-opacity-75 rounded">
                      <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Lädt...</span>
                      </div>
                      <p class="text-muted mb-2">Generiere Antwort...</p>
                      <div class="progress w-75" style="height: 5px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated"
                             :style="{width: loadingProgress + '%'}"></div>
                      </div>
                    </div>

                    <!-- Response Content -->
                    <div v-else-if="response">
                      <div class="p-3 bg-body-light rounded mb-3" style="max-height: 500px; overflow-y: auto;">
                        <div v-html="formattedResponse" class="fs-sm response-content"></div>
                      </div>

                      <!-- Response Info -->
                      <div class="d-flex justify-content-between text-muted fs-sm mb-3">
                        <span>
                          <i class="fa fa-clock me-1"></i>
                          Generiert in {{ responseTime ? (responseTime/1000).toFixed(2) + 's' : '--' }}
                        </span>
                        <span>
                          <i class="fa fa-tag me-1"></i>
                          {{ model }}
                        </span>
                      </div>

                      <!-- Cost Information -->
                      <div v-if="estimatedCost > 0" class="alert alert-info mb-0">
                        <div class="d-flex">
                          <div class="flex-shrink-0">
                            <i class="fa fa-info-circle fa-2x"></i>
                          </div>
                          <div class="flex-grow-1 ms-3">
                            <h4 class="alert-heading fs-sm fw-bold mb-1">Kosteninformation</h4>
                            <p class="fs-sm mb-0">
                              Geschätzte Kosten für diesen API-Aufruf: <strong>{{ formatCurrency(estimatedCost) }}</strong>
                              <br>
                              <small>Basierend auf {{ estimatedTokens }} Tokens ({{ inputTokens }} Eingabe, {{ outputTokens }} Ausgabe)</small>
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="h-100 d-flex flex-column justify-content-center align-items-center text-center p-4">
                      <div class="mb-3">
                        <i class="fa fa-robot fa-4x text-muted"></i>
                      </div>
                      <p class="text-muted">Führen Sie den Prompt aus, um eine AI-Antwort zu erhalten.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Compare Tab -->
        <div class="tab-pane fade" id="playground-compare" role="tabpanel" aria-labelledby="playground-compare-tab">
          <div class="block block-rounded">
            <div class="block-header block-header-default">
              <h3 class="block-title">Prompt-Versionen vergleichen</h3>
            </div>
            <div class="block-content">
              <div class="row g-4 mb-4">
                <div class="col-md-6">
                  <label class="form-label">Zu vergleichende Versionen</label>
                  <div class="d-flex flex-wrap gap-3">
                    <div v-for="version in availableVersions" :key="version.id" class="form-check">
                      <input
                        class="form-check-input"
                        type="checkbox"
                        :value="version.id"
                        :id="`version-check-${version.id}`"
                        v-model="compareVersionIds"
                        :disabled="compareVersionIds.length >= 3 && !compareVersionIds.includes(version.id)"
                      >
                      <label class="form-check-label" :for="`version-check-${version.id}`">
                        Version {{ version.version }}
                        <span v-if="version.is_current" class="badge bg-primary ms-1">Aktuell</span>
                      </label>
                    </div>
                  </div>
                  <div class="form-text mt-2">
                    Wählen Sie bis zu 3 Versionen zum Vergleichen aus
                  </div>
                </div>
                <div class="col-md-6">
                  <label class="form-label mb-3">Variablen für den Vergleich</label>
                  <div v-for="(variable, index) in variables" :key="index" class="mb-3">
                    <label class="form-label" :for="`compare-var-${index}`">{{ variable }}</label>
                    <input
                      v-model="compareVariableValues[variable]"
                      type="text"
                      class="form-control"
                      :id="`compare-var-${index}`"
                      :placeholder="`Wert für ${variable}`"
                    >
                  </div>
                </div>
              </div>

              <div class="mb-4">
                <div class="d-flex justify-content-end">
                  <button
                    type="button"
                    class="btn btn-primary"
                    @click="compareVersions"
                    :disabled="isComparing || compareVersionIds.length < 2"
                  >
                    <i v-if="isComparing" class="fa fa-spinner fa-spin me-1"></i>
                    <i v-else class="fa fa-play me-1"></i>
                    Versionen vergleichen
                  </button>
                </div>
              </div>

              <!-- Comparison Results -->
              <div v-if="comparisonResults.length > 0">
                <div class="row g-3">
                  <div
                    v-for="result in comparisonResults"
                    :key="result.version_id"
                    class="col-md-6 col-xl-4"
                  >
                    <div class="block block-rounded h-100">
                      <div class="block-header block-header-default">
                        <h3 class="block-title">Version {{ result.version_number }}</h3>
                        <div class="block-options">
                          <button type="button" class="btn-block-option me-2" @click="copyVersionResult(result)">
                            <i class="fa fa-copy"></i>
                          </button>
                          <button
                            type="button"
                            class="btn-block-option"
                            @click="setAsCurrentVersion(result.version_id)"
                            v-if="!isCurrentVersion(result.version_id)"
                          >
                            <i class="fa fa-check" title="Als aktuelle Version festlegen"></i>
                          </button>
                        </div>
                      </div>
                      <div class="block-content">
                        <div class="p-3 bg-body-light rounded" style="max-height: 400px; overflow-y: auto;">
                          <div v-html="formatResponse(result.response)" class="fs-sm"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- History Tab -->
        <div class="tab-pane fade" id="playground-history" role="tabpanel" aria-labelledby="playground-history-tab">
          <div class="block block-rounded">
            <div class="block-header block-header-default">
              <h3 class="block-title">Ausführungsverlauf</h3>
              <div class="block-options">
                <button type="button" class="btn btn-sm btn-alt-secondary" @click="clearHistory">
                  <i class="fa fa-trash me-1"></i> Verlauf löschen
                </button>
              </div>
            </div>
            <div class="block-content">
              <div v-if="promptHistory.length === 0" class="text-center py-5">
                <div class="mb-3">
                  <i class="fa fa-history fa-4x text-muted"></i>
                </div>
                <p class="text-muted mb-0">Keine Einträge im Ausführungsverlauf.</p>
              </div>
              <div v-else class="table-responsive">
                <table class="table table-bordered table-striped table-vcenter mb-0">
                  <thead>
                  <tr>
                    <th style="width: 15%;">Zeitpunkt</th>
                    <th style="width: 45%;">Prompt</th>
                    <th style="width: 20%;">Modell</th>
                    <th style="width: 20%;" class="text-center">Aktionen</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="(item, index) in promptHistory" :key="index">
                    <td class="fs-sm">
                      {{ formatDateTime(item.timestamp) }}
                    </td>
                    <td>
                      <div class="fw-semibold mb-1">{{ truncate(item.content, 50) }}</div>
                      <div v-if="Object.keys(item.variables || {}).length > 0" class="fs-sm text-muted">
                          <span v-for="(value, key) in item.variables" :key="key" class="d-inline-block me-2">
                            <strong>{{ key }}:</strong> {{ truncate(value, 15) }}
                          </span>
                      </div>
                    </td>
                    <td class="fs-sm">
                      {{ item.provider }} / {{ item.model }}
                    </td>
                    <td class="text-center">
                      <div class="btn-group btn-group-sm">
                        <button
                          type="button"
                          class="btn btn-sm btn-alt-secondary"
                          @click="selectHistoryItem(item)"
                          title="Ausführen"
                        >
                          <i class="fa fa-fw fa-play"></i>
                        </button>
                        <button
                          type="button"
                          class="btn btn-sm btn-alt-secondary"
                          @click="viewHistoryItem(item)"
                          title="Anzeigen"
                        >
                          <i class="fa fa-fw fa-eye"></i>
                        </button>
                        <button
                          type="button"
                          class="btn btn-sm btn-alt-secondary"
                          @click="deleteHistoryItem(index)"
                          title="Löschen"
                        >
                          <i class="fa fa-fw fa-trash"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END Page Content -->

    <!-- Modals -->
    <!-- Save as Prompt Modal -->
    <div class="modal fade" id="modal-save-prompt" tabindex="-1" role="dialog" aria-labelledby="modal-save-prompt" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form class="block block-rounded block-transparent mb-0">
            <div class="block-header block-header-default">
              <h3 class="block-title">Antwort als Prompt speichern</h3>
              <div class="block-options">
                <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                  <i class="fa fa-fw fa-times"></i>
                </button>
              </div>
            </div>
            <div class="block-content fs-sm">
              <div class="mb-4">
                <label class="form-label" for="new-prompt-title">Titel</label>
                <input
                  v-model="newPromptTitle"
                  type="text"
                  class="form-control"
                  id="new-prompt-title"
                  placeholder="Titel für den neuen Prompt"
                  required
                >
              </div>
              <div class="mb-4">
                <label class="form-label" for="new-prompt-description">Beschreibung (optional)</label>
                <textarea
                  v-model="newPromptDescription"
                  class="form-control"
                  id="new-prompt-description"
                  rows="3"
                  placeholder="Beschreibung für den neuen Prompt"
                ></textarea>
              </div>
              <div class="mb-4">
                <label class="form-label" for="new-prompt-tags">Tags (optional)</label>
                <input
                  v-model="newPromptTags"
                  type="text"
                  class="form-control"
                  id="new-prompt-tags"
                  placeholder="Tags durch Komma getrennt (z.B. marketing, email, sales)"
                >
              </div>
            </div>
            <div class="block-content block-content-full text-end bg-body">
              <button type="button" class="btn btn-sm btn-alt-secondary me-1" data-bs-dismiss="modal">Abbrechen</button>
              <button
                type="button"
                class="btn btn-sm btn-primary"
                @click="createPromptFromResponse"
                :disabled="!newPromptTitle || isSaving"
              >
                <i v-if="isSaving" class="fa fa-spinner fa-spin me-1"></i>
                <i v-else class="fa fa-save me-1"></i>
                Speichern
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Preview Modal -->
    <div class="modal fade" id="modal-preview" tabindex="-1" role="dialog" aria-labelledby="modal-preview" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="block block-rounded block-transparent mb-0">
            <div class="block-header block-header-default">
              <h3 class="block-title">Prompt-Vorschau</h3>
              <div class="block-options">
                <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                  <i class="fa fa-fw fa-times"></i>
                </button>
              </div>
            </div>
            <div class="block-content">
              <div class="alert alert-info mb-4">
                <i class="fa fa-info-circle me-1"></i> Dies ist der Prompt mit allen eingesetzten Variablen, so wie er an die AI gesendet wird.
              </div>
              <div class="p-3 bg-body-light rounded">
                <pre class="mb-0">{{ previewContent }}</pre>
              </div>
            </div>
            <div class="block-content block-content-full text-end bg-body">
              <button type="button" class="btn btn-sm btn-alt-secondary me-1" data-bs-dismiss="modal">Schließen</button>
              <button type="button" class="btn btn-sm btn-alt-primary" @click="copyPreview">
                <i class="fa fa-copy me-1"></i> Kopieren
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- View History Item Modal -->
    <div class="modal fade" id="modal-history-item" tabindex="-1" role="dialog" aria-labelledby="modal-history-item" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="block block-rounded block-transparent mb-0">
            <div class="block-header block-header-default">
              <h3 class="block-title">Verlaufseintrag</h3>
              <div class="block-options">
                <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                  <i class="fa fa-fw fa-times"></i>
                </button>
              </div>
            </div>
            <div class="block-content">
              <div class="row g-4">
                <div class="col-md-6">
                  <h4 class="h5 mb-3">Prompt</h4>
                  <div class="p-3 bg-body-light rounded mb-3" style="max-height: 300px; overflow-y: auto;">
                    <pre class="mb-0">{{ historyItemDetails?.content || '' }}</pre>
                  </div>
                  <div v-if="historyItemDetails && Object.keys(historyItemDetails.variables || {}).length > 0">
                    <h5 class="h6 mb-2">Verwendete Variablen</h5>
                    <div class="table-responsive">
                      <table class="table table-sm table-vcenter">
                        <thead>
                        <tr>
                          <th>Variable</th>
                          <th>Wert</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(value, key) in historyItemDetails.variables" :key="key">
                          <td><code>{{ key }}</code></td>
                          <td>{{ value }}</td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <h4 class="h5 mb-3">Antwort</h4>
                  <div class="p-3 bg-body-light rounded mb-3" style="max-height: 300px; overflow-y: auto;">
                    <div v-html="formatResponse(historyItemDetails?.response || '')"></div>
                  </div>
                  <div>
                    <h5 class="h6 mb-3">Modell-Details</h5>
                    <div class="list-group">
                      <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Provider</span>
                        <span class="badge bg-primary">{{ historyItemDetails?.provider || '-' }}</span>
                      </div>
                      <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Modell</span>
                        <span class="badge bg-primary">{{ historyItemDetails?.model || '-' }}</span>
                      </div>
                      <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Temperatur</span>
                        <span class="badge bg-primary">{{ historyItemDetails?.temperature || '-' }}</span>
                      </div>
                      <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Zeitpunkt</span>
                        <span>{{ historyItemDetails?.timestamp ? formatDateTime(historyItemDetails.timestamp) : '-' }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="block-content block-content-full text-end bg-body">
              <button type="button" class="btn btn-sm btn-alt-secondary me-1" data-bs-dismiss="modal">Schließen</button>
              <button
                type="button"
                class="btn btn-sm btn-primary"
                @click="loadHistoryItemAsActive"
              >
                <i class="fa fa-play me-1"></i> Ausführen
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="modal-delete-prompt" tabindex="-1" role="dialog" aria-labelledby="modal-delete-prompt" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="block block-rounded block-transparent mb-0">
            <div class="block-header block-header-default">
              <h3 class="block-title">Prompt löschen</h3>
              <div class="block-options">
                <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                  <i class="fa fa-fw fa-times"></i>
                </button>
              </div>
            </div>
            <div class="block-content fs-sm py-4">
              <p>Sind Sie sicher, dass Sie diesen Prompt löschen möchten? Diese Aktion kann nicht rückgängig gemacht werden.</p>
              <p class="fw-bold mb-0">{{ prompt?.title }}</p>
            </div>
            <div class="block-content block-content-full text-end bg-body">
              <button type="button" class="btn btn-sm btn-alt-secondary me-1" data-bs-dismiss="modal">Abbrechen</button>
              <button type="button" class="btn btn-sm btn-danger" @click="deletePrompt" :disabled="isDeleting">
                <i v-if="isDeleting" class="fa fa-spinner fa-spin me-1"></i>
                <i v-else class="fa fa-trash me-1"></i>
                Löschen
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import axios from 'axios';
import DOMPurify from 'dompurify';
import { marked } from 'marked';
import hljs from 'highlight.js';

// Props from controller
const props = defineProps({
  prompt: Object,
  currentVersion: Object,
});

// Reaktive Variablen
const content = ref(props.currentVersion?.content || '');
const variables = ref([]);
const variableValues = ref({});
const compareVariableValues = ref({});
const provider = ref('openai');
const model = ref('gpt-4-turbo');
const customProviderUrl = ref('');
const temperature = ref(0.7);
const maxTokens = ref(2048);
const response = ref('');
const responseTime = ref(null);
const isLoading = ref(false);
const loadingProgress = ref(0);
const isEnhancing = ref(false);
const isComparing = ref(false);
const isDeleting = ref(false);
const isSaving = ref(false);
const promptHistory = ref([]);
const historyItemDetails = ref(null);
const availableVersions = ref([]);
const compareVersionIds = ref([]);
const comparisonResults = ref([]);
const showSettings = ref(true);
const estimatedTokens = ref(0);
const inputTokens = ref(0);
const outputTokens = ref(0);
const estimatedCost = ref(0);
const previewContent = ref('');

// Neue Variablen für Modaler Dialog
const newPromptTitle = ref('');
const newPromptDescription = ref('');
const newPromptTags = ref('');

// Computed properties
const formattedResponse = computed(() => {
  if (!response.value) return '';
  return formatResponse(response.value);
});

const formatResponse = (responseText) => {
  if (!responseText) return '';

  // Konfiguriere marked für Code-Highlighting
  marked.setOptions({
    highlight: function(code, lang) {
      if (lang && hljs.getLanguage(lang)) {
        return hljs.highlight(code, { language: lang }).value;
      }
      return hljs.highlightAuto(code).value;
    },
    breaks: true,
    gfm: true
  });

  // Säubere und transformiere die Markdown-Antwort zu HTML
  const clean = DOMPurify.sanitize(marked.parse(responseText));
  return clean;
};

const currentVersionLabel = computed(() => {
  const current = availableVersions.value.find(v => v.is_current);
  return current ? `Version ${current.version}` : 'Aktuelle Version';
});

const modelOptions = computed(() => {
  if (provider.value === 'openai') {
    return [
      { value: 'gpt-4-turbo', label: 'GPT-4 Turbo' },
      { value: 'gpt-4', label: 'GPT-4' },
      { value: 'gpt-3.5-turbo', label: 'GPT-3.5 Turbo' }
    ];
  } else if (provider.value === 'anthropic') {
    return [
      { value: 'claude-3-opus', label: 'Claude 3 Opus' },
      { value: 'claude-3-sonnet', label: 'Claude 3 Sonnet' },
      { value: 'claude-3-haiku', label: 'Claude 3 Haiku' }
    ];
  } else if (provider.value === 'google') {
    return [
      { value: 'gemini-pro', label: 'Gemini Pro' },
      { value: 'gemini-ultra', label: 'Gemini Ultra' }
    ];
  } else if (provider.value === 'ollama') {
    return [
      { value: 'llama3', label: 'Llama 3' },
      { value: 'mistral', label: 'Mistral' },
      { value: 'phi', label: 'Phi' },
      { value: 'codellama', label: 'Code Llama' },
      { value: 'custom', label: 'Anderes Modell' }
    ];
  } else if (provider.value === 'deepseek') {
    return [
      { value: 'deepseek-coder', label: 'DeepSeek Coder' },
      { value: 'deepseek-llm', label: 'DeepSeek LLM' },
      { value: 'deepseek-chat', label: 'DeepSeek Chat' }
    ];
  } else {
    return [
      { value: 'custom', label: 'Benutzerdefiniert' }
    ];
  }
});

// Methoden
const extractVariables = () => {
  const matches = content.value.match(/\{\{(.*?)\}\}/g) || [];
  const extracted = matches.map(match => match.replace(/\{\{|\}\}/g, '').trim());
  variables.value = [...new Set(extracted)];

  // Initialisiere variableValues und compareVariableValues mit leeren Strings für neue Variablen
  variables.value.forEach(variable => {
    if (!variableValues.value[variable]) {
      variableValues.value[variable] = '';
    }
    if (!compareVariableValues.value[variable]) {
      compareVariableValues.value[variable] = '';
    }
  });
};

// Watchers
watch(content, () => {
  extractVariables();
}, { immediate: true });

// Aktualisiere das Modell bei Provider-Wechsel
watch(provider, () => {
  if (modelOptions.value.length > 0) {
    model.value = modelOptions.value[0].value;
  }
});

// Laden der verfügbaren Versionen
const loadVersions = async () => {
  try {
    const response = await axios.get(route('prompts.versions', props.prompt.id));
    if (response.data && response.data.versions) {
      availableVersions.value = response.data.versions;
    }
  } catch (error) {
    console.error('Error loading versions:', error);
    notifyError('Fehler beim Laden der Versionen');
  }
};

// Laden einer bestimmten Version
const loadVersion = async (versionId) => {
  try {
    const response = await axios.get(route('prompts.versions.preview', [props.prompt.id, versionId]));
    if (response.data) {
      content.value = response.data.content;
      extractVariables();
    }
  } catch (error) {
    console.error('Error loading version:', error);
    notifyError('Fehler beim Laden der Version');
  }
};

// Zurücksetzen der Variablenwerte
const resetVariables = () => {
  variables.value.forEach(variable => {
    variableValues.value[variable] = '';
  });
};

// Generieren eines Vorschlags für eine Variable
const generateVariableValue = async (variable) => {
  try {
    const examples = {
      'name': ['Max Mustermann', 'Maria Schmidt', 'John Doe'],
      'email': ['example@domain.com', 'user@company.de', 'contact@business.org'],
      'company': ['ACME Corp', 'TechStart GmbH', 'Global Industries'],
      'product': ['Premium-Paket', 'Enterprise-Lösung', 'Basic-Plan'],
      'date': [new Date().toLocaleDateString('de-DE')],
      'phone': ['+49 123 4567890', '0123 456789'],
      'address': ['Hauptstraße 1, 10115 Berlin', 'Marktplatz 5, 80331 München'],
    };

    const lowerVar = variable.toLowerCase();
    let suggestion = '';

    for (const [key, values] of Object.entries(examples)) {
      if (lowerVar.includes(key)) {
        suggestion = values[Math.floor(Math.random() * values.length)];
        break;
      }
    }

    if (!suggestion) {
      suggestion = `Beispielwert für ${variable}`;
    }

    variableValues.value[variable] = suggestion;

    window.OneUI.helpers('jq-notify', {
      type: 'success',
      icon: 'fa fa-magic me-1',
      message: `Vorschlag für "${variable}" generiert!`
    });

  } catch (error) {
    console.error('Error generating variable value:', error);
    notifyError('Fehler beim Generieren des Vorschlags');
  }
};

const updateModelOptions = () => {
  if (modelOptions.value.length > 0) {
    model.value = modelOptions.value[0].value;
  }
};

// Aktualisiere die Loading-Animation
const updateLoadingProgress = () => {
  const interval = setInterval(() => {
    loadingProgress.value += 5;
    if (loadingProgress.value >= 100) {
      loadingProgress.value = 0;
    }
    if (!isLoading.value) {
      clearInterval(interval);
      loadingProgress.value = 0;
    }
  }, 300);
};

// Vorschau des Prompts mit eingesetzten Variablen
const previewPrompt = () => {
  let processedContent = content.value;

  for (const [key, value] of Object.entries(variableValues.value)) {
    const regex = new RegExp(`\\{\\{${key}\\}\\}`, 'g');
    processedContent = processedContent.replace(regex, value || `[${key}]`);
  }

  previewContent.value = processedContent;
  new bootstrap.Modal(document.getElementById('modal-preview')).show();
};

// Kopieren der Vorschau in die Zwischenablage
const copyPreview = () => {
  navigator.clipboard.writeText(previewContent.value)
    .then(() => {
      window.OneUI.helpers('jq-notify', {
        type: 'success',
        icon: 'fa fa-check me-1',
        message: 'Prompt-Vorschau in die Zwischenablage kopiert!'
      });
    })
    .catch(err => {
      console.error('Fehler beim Kopieren:', err);
      notifyError('Fehler beim Kopieren');
    });
};

// Ausführen des Prompts
const testPrompt = async () => {
  if (!content.value.trim()) return;

  isLoading.value = true;
  loadingProgress.value = 0;
  updateLoadingProgress();
  const startTime = Date.now();
  response.value = '';

  try {
    const resp = await axios.post(route('playground.test'), {
      prompt_id: props.prompt?.id,
      content: content.value,
      variables: variableValues.value,
      provider: provider.value,
      model: model.value,
      temperature: temperature.value,
      max_tokens: maxTokens.value,
    });

    const endTime = Date.now();
    responseTime.value = endTime - startTime;

    if (resp.data) {
      if (resp.data.error) {
        notifyError(resp.data.message || 'Fehler bei der Anfrage');
        response.value = 'Fehler: ' + (resp.data.message || 'Unbekannter Fehler bei der Anfrage an den AI-Provider');
      } else {
        response.value = resp.data.content || resp.data.text || '';

        if (resp.data.usage) {
          estimatedTokens.value = resp.data.usage.total_tokens || 0;
          inputTokens.value = resp.data.usage.prompt_tokens || 0;
          outputTokens.value = resp.data.usage.completion_tokens || 0;
          estimatedCost.value = calculateCost(provider.value, model.value, inputTokens.value, outputTokens.value);
        } else if (resp.data.raw_response && resp.data.raw_response.usage) {
          estimatedTokens.value = resp.data.raw_response.usage.total_tokens || 0;
          inputTokens.value = resp.data.raw_response.usage.prompt_tokens || 0;
          outputTokens.value = resp.data.raw_response.usage.completion_tokens || 0;
          estimatedCost.value = calculateCost(provider.value, model.value, inputTokens.value, outputTokens.value);
        }

        promptHistory.value.unshift({
          content: content.value,
          response: response.value,
          variables: { ...variableValues.value },
          provider: provider.value,
          model: model.value,
          temperature: temperature.value,
          maxTokens: maxTokens.value,
          timestamp: new Date().toISOString()
        });

        if (promptHistory.value.length > 20) {
          promptHistory.value = promptHistory.value.slice(0, 20);
        }

        saveHistoryToLocalStorage();
      }
    }
  } catch (error) {
    console.error('Error testing prompt:', error);
    response.value = 'Fehler beim Generieren der Antwort. Bitte versuchen Sie es erneut.';
    notifyError('Fehler bei der API-Anfrage');
  } finally {
    isLoading.value = false;
  }
};

// Berechnung der Kosten basierend auf Modell und Token-Anzahl
const calculateCost = (provider, model, inputTokens, outputTokens) => {
  const prices = {
    'openai': {
      'gpt-4': { input: 0.03, output: 0.06 },
      'gpt-4-turbo': { input: 0.01, output: 0.03 },
      'gpt-3.5-turbo': { input: 0.0005, output: 0.0015 }
    },
    'anthropic': {
      'claude-3-opus': { input: 0.015, output: 0.075 },
      'claude-3-sonnet': { input: 0.003, output: 0.015 },
      'claude-3-haiku': { input: 0.00025, output: 0.00125 }
    },
    'google': {
      'gemini-pro': { input: 0.00025, output: 0.0005 },
      'gemini-ultra': { input: 0.0008, output: 0.0016 }
    },
    'deepseek': {
      'deepseek-coder': { input: 0.0005, output: 0.0015 },
      'deepseek-llm': { input: 0.001, output: 0.002 },
      'deepseek-chat': { input: 0.001, output: 0.002 }
    }
  };

  if (prices[provider] && prices[provider][model]) {
    const inputCost = (inputTokens / 1000) * prices[provider][model].input;
    const outputCost = (outputTokens / 1000) * prices[provider][model].output;
    return inputCost + outputCost;
  }

  return 0;
};

// Formatieren von Währungswerten
const formatCurrency = (value) => {
  return new Intl.NumberFormat('de-DE', {
    style: 'currency',
    currency: 'EUR',
    minimumFractionDigits: 4,
    maximumFractionDigits: 4
  }).format(value);
};

// Verbessern des Prompts
const enhancePrompt = async () => {
  if (!content.value.trim()) return;

  isEnhancing.value = true;

  try {
    const resp = await axios.post(route('prompts.enhance'), {
      prompt: content.value,
      context: 'Playground-Test'
    });

    if (resp.data && resp.data.improved_prompt) {
      content.value = resp.data.improved_prompt;
      extractVariables();

      window.OneUI.helpers('jq-notify', {
        type: 'success',
        icon: 'fa fa-magic me-1',
        message: 'Prompt wurde verbessert!'
      });
    }
  } catch (error) {
    console.error('Error enhancing prompt:', error);
    notifyError('Fehler beim Verbessern des Prompts');
  } finally {
    isEnhancing.value = false;
  }
};

// Kopieren der Antwort in die Zwischenablage
const copyResponse = () => {
  if (!response.value) return;

  navigator.clipboard.writeText(response.value)
    .then(() => {
      window.OneUI.helpers('jq-notify', {
        type: 'success',
        icon: 'fa fa-check me-1',
        message: 'Antwort in die Zwischenablage kopiert!'
      });
    })
    .catch(err => {
      console.error('Fehler beim Kopieren:', err);
      notifyError('Fehler beim Kopieren');
    });
};

// Kopieren eines Vergleichsergebnisses
const copyVersionResult = (result) => {
  if (!result || !result.response) return;

  navigator.clipboard.writeText(result.response)
    .then(() => {
      window.OneUI.helpers('jq-notify', {
        type: 'success',
        icon: 'fa fa-check me-1',
        message: 'Antwort für Version ' + result.version_number + ' kopiert!'
      });
    })
    .catch(err => {
      console.error('Fehler beim Kopieren:', err);
    });
};

// Festlegen einer Version als aktuelle Version
const setAsCurrentVersion = async (versionId) => {
  try {
    await axios.post(route('prompts.versions.set-current', [props.prompt.id, versionId]));
    await loadVersions();

    window.OneUI.helpers('jq-notify', {
      type: 'success',
      icon: 'fa fa-check me-1',
      message: 'Version wurde als aktuell festgelegt!'
    });
  } catch (error) {
    console.error('Error setting version:', error);
    notifyError('Fehler beim Festlegen der Version');
  }
};

// Prüfen, ob eine Version die aktuelle ist
const isCurrentVersion = (versionId) => {
  const version = availableVersions.value.find(v => v.id === versionId);
  return version && version.is_current;
};

// Modal zum Speichern als Prompt öffnen
const saveAsPrompt = () => {
  newPromptTitle.value = '';
  newPromptDescription.value = '';
  newPromptTags.value = '';
  new bootstrap.Modal(document.getElementById('modal-save-prompt')).show();
};

// Speichern der aktuellen Testkonfiguration
const saveCurrentTest = () => {
  window.OneUI.helpers('jq-notify', {
    type: 'info',
    icon: 'fa fa-info-circle me-1',
    message: 'Diese Funktion wird in einer zukünftigen Version verfügbar sein.'
  });
};

// Erstellen eines neuen Prompts aus der Antwort
const createPromptFromResponse = () => {
  if (!response.value || !newPromptTitle.value) return;

  isSaving.value = true;

  const tags = newPromptTags.value
    .split(',')
    .map(tag => tag.trim())
    .filter(tag => tag.length > 0);

  router.post(route('prompts.store'), {
    title: newPromptTitle.value,
    description: newPromptDescription.value,
    content: response.value,
    tags: tags.length > 0 ? tags : null,
  }, {
    onSuccess: () => {
      isSaving.value = false;
      window.OneUI.helpers('jq-notify', {
        type: 'success',
        icon: 'fa fa-check me-1',
        message: 'Prompt wurde erfolgreich gespeichert!'
      });
    },
    onError: () => {
      isSaving.value = false;
      notifyError('Fehler beim Speichern des Prompts');
    }
  });
};

// Eine Antwort exportieren
const downloadResponse = () => {
  if (!response.value) return;

  const filename = props.prompt?.title
    ? `${props.prompt.title.toLowerCase().replace(/\s+/g, '-')}-response.md`
    : 'ai-response.md';

  const content = `# ${props.prompt?.title || 'AI Antwort'}\n\n${response.value}`;

  const blob = new Blob([content], { type: 'text/markdown' });
  const url = URL.createObjectURL(blob);

  const a = document.createElement('a');
  a.href = url;
  a.download = filename;
  document.body.appendChild(a);
  a.click();

  setTimeout(() => {
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
  }, 0);
};

// Prompt zum Workflow hinzufügen
const saveToWorkflow = () => {
  window.OneUI.helpers('jq-notify', {
    type: 'info',
    icon: 'fa fa-info-circle me-1',
    message: 'Diese Funktion wird in einer zukünftigen Version verfügbar sein.'
  });
};

// Prompt teilen
const sharePrompt = () => {
  window.OneUI.helpers('jq-notify', {
    type: 'info',
    icon: 'fa fa-info-circle me-1',
    message: 'Diese Funktion wird in einer zukünftigen Version verfügbar sein.'
  });
};

// Prompt duplizieren
const duplicatePrompt = () => {
  router.post(route('prompts.store'), {
    title: `Kopie von ${props.prompt.title}`,
    description: props.prompt.description,
    content: content.value,
    tags: props.prompt.tags,
  }, {
    onSuccess: () => {
      window.OneUI.helpers('jq-notify', {
        type: 'success',
        icon: 'fa fa-copy me-1',
        message: 'Prompt wurde dupliziert!'
      });
    },
    onError: () => {
      notifyError('Fehler beim Duplizieren des Prompts');
    }
  });
};

// Bestätigungsdialog für das Löschen
const confirmDelete = () => {
  new bootstrap.Modal(document.getElementById('modal-delete-prompt')).show();
};

// Prompt löschen
const deletePrompt = () => {
  isDeleting.value = true;

  router.delete(route('prompts.destroy', props.prompt.id), {
    onSuccess: () => {
      isDeleting.value = false;
    },
    onError: () => {
      isDeleting.value = false;
      notifyError('Fehler beim Löschen des Prompts');
    }
  });
};

// Vergleich von Versionen
const compareVersions = async () => {
  if (compareVersionIds.value.length < 2) return;

  isComparing.value = true;
  comparisonResults.value = [];

  try {
    const resp = await axios.post(route('playground.compare'), {
      prompt_id: props.prompt.id,
      version_ids: compareVersionIds.value,
      variables: compareVariableValues.value,
      provider: provider.value,
      model: model.value,
      temperature: temperature.value,
      max_tokens: maxTokens.value,
    });

    if (resp.data && resp.data.results) {
      comparisonResults.value = resp.data.results;
    }
  } catch (error) {
    console.error('Error comparing versions:', error);
    notifyError('Fehler beim Vergleichen der Versionen');
  } finally {
    isComparing.value = false;
  }
};

// Aus Verlauf auswählen
const selectHistoryItem = (item) => {
  content.value = item.content;
  response.value = item.response;
  provider.value = item.provider;
  model.value = item.model;
  temperature.value = item.temperature;
  maxTokens.value = item.maxTokens;

  if (item.variables) {
    variableValues.value = { ...item.variables };
  }

  document.getElementById('playground-test-tab').click();
};

// Verlaufseintrag anzeigen
const viewHistoryItem = (item) => {
  historyItemDetails.value = item;
  new bootstrap.Modal(document.getElementById('modal-history-item')).show();
};

// Verlaufseintrag zum aktuellen Prompt laden
const loadHistoryItemAsActive = () => {
  if (!historyItemDetails.value) return;

  selectHistoryItem(historyItemDetails.value);
  bootstrap.Modal.getInstance(document.getElementById('modal-history-item')).hide();
};

// Verlaufseintrag löschen
const deleteHistoryItem = (index) => {
  promptHistory.value.splice(index, 1);
  saveHistoryToLocalStorage();
};

// Verlauf löschen
const clearHistory = () => {
  promptHistory.value = [];
  localStorage.removeItem(`promptHistory_${props.prompt.id}`);

  window.OneUI.helpers('jq-notify', {
    type: 'info',
    icon: 'fa fa-trash me-1',
    message: 'Verlauf wurde gelöscht!'
  });
};

// Text kürzen für die Anzeige
const truncate = (text, length) => {
  if (!text) return '';
  return text.length > length ? text.substring(0, length) + '...' : text;
};

// Formatierung von Datum und Uhrzeit
const formatDateTime = (dateString) => {
  if (!dateString) return '';

  const date = new Date(dateString);
  return new Intl.DateTimeFormat('de-DE', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date);
};

// Anzeigen einer Fehlermeldung
const notifyError = (message) => {
  window.OneUI.helpers('jq-notify', {
    type: 'danger',
    icon: 'fa fa-times me-1',
    message: message
  });
};

// Zeigen oder Verstecken der Einstellungen
const toggleSettings = () => {
  showSettings.value = !showSettings.value;
};

// Verlauf im localStorage speichern
const saveHistoryToLocalStorage = () => {
  try {
    localStorage.setItem(`promptHistory_${props.prompt.id}`, JSON.stringify(promptHistory.value));
  } catch (e) {
    console.error('Error saving history to localStorage:', e);
  }
};

// Verlauf aus localStorage laden
const loadHistoryFromLocalStorage = () => {
  try {
    const saved = localStorage.getItem(`promptHistory_${props.prompt.id}`);
    if (saved) {
      promptHistory.value = JSON.parse(saved);
    }
  } catch (e) {
    console.error('Error loading history from localStorage:', e);
  }
};

// Initialisierung
onMounted(async () => {
  extractVariables();
  await loadVersions();
  loadHistoryFromLocalStorage();
  updateModelOptions();

  if (availableVersions.value.length >= 2) {
    compareVersionIds.value = [
      availableVersions.value[0].id,
      availableVersions.value[1].id
    ];
  }
});
</script>

<style>
/* Import syntax highlighting styles */
@import 'highlight.js/styles/github.css';

/* Additional CSS for the response area */
.response-content {
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
  line-height: 1.6;
}

.response-content pre {
  background-color: #f5f7f9;
  border-radius: 4px;
  padding: 16px;
  margin: 8px 0;
  overflow-x: auto;
}

.response-content code {
  background-color: #f0f2f5;
  border-radius: 3px;
  padding: 2px 6px;
  font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
  font-size: 90%;
}

.response-content blockquote {
  border-left: 4px solid #e9ecef;
  padding-left: 16px;
  margin: 16px 0;
  color: #6c757d;
}

.response-content table {
  width: 100%;
  border-collapse: collapse;
  margin: 16px 0;
}

.response-content th,
.response-content td {
  padding: 8px 12px;
  border: 1px solid #dee2e6;
}

.response-content th {
  background-color: #f8f9fa;
  font-weight: 600;
}

/* Ensure proper spacing for form groups */
.form-label {
  margin-bottom: 0.5rem;
}

/* Improve modal appearance */
.modal-content {
  border-radius: 0.5rem;
}

/* Ensure dropdown menus have proper z-index */
.dropdown-menu {
  z-index: 1050;
}

/* Improve button group appearance */
.btn-group-sm > .btn {
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
}

/* Ensure proper spacing in block headers */
.block-header {
  padding: 1rem 1.5rem;
}

/* Improve table appearance */
.table > :not(caption) > * > * {
  padding: 0.75rem;
}

/* Ensure proper height for textarea */
textarea.form-control {
  min-height: 150px;
}

/* Improve tab navigation spacing */
.nav-tabs {
  border-bottom: 2px solid #dee2e6;
}

.nav-tabs .nav-link {
  border: none;
  padding: 0.75rem 1rem;
}

.nav-tabs .nav-link.active {
  border-bottom: 2px solid #0d6efd;
  margin-bottom: -2px;
}

/* Ensure proper spacing for alerts */
.alert {
  margin-bottom: 1rem;
}

/* Improve card-like blocks */
.block {
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

/* Ensure proper spacing for form elements */
.form-control,
.form-select {
  margin-bottom: 0.25rem;
}

/* Improve the appearance of range inputs */
.form-range {
  margin-bottom: 0.5rem;
}

/* Ensure modals have proper backdrop */
.modal-backdrop {
  background-color: rgba(0, 0, 0, 0.5);
}

/* Fix spacing issues in dropdowns */
.dropdown-item {
  padding: 0.5rem 1rem;
}

/* Improve the appearance of badges */
.badge {
  padding: 0.25em 0.5em;
}

/* Ensure proper spacing in the history table */
.table-vcenter td {
  vertical-align: middle;
}

/* Improve the appearance of the loading overlay */
.position-absolute.bg-body-light {
  inset: 0;
  z-index: 1;
}

/* Ensure proper spacing for block content */
.block-content {
  padding: 1.5rem;
}

/* Improve the appearance of list groups */
.list-group-item {
  padding: 0.75rem 1rem;
}

/* Ensure proper spacing for form text */
.form-text {
  margin-top: 0.25rem;
  font-size: 0.875em;
  color: #6c757d;
}

/* Improve the appearance of input groups */
.input-group > .form-control {
  margin-bottom: 0;
}

/* Ensure proper spacing for buttons */
.btn + .btn {
  margin-left: 0.5rem;
}

/* Improve the appearance of progress bars */
.progress {
  background-color: #e9ecef;
}

/* Ensure proper spacing for row gutters */
.row.g-3 > * {
  padding-right: calc(var(--bs-gutter-x) * 0.5);
  padding-left: calc(var(--bs-gutter-x) * 0.5);
}

/* Improve the appearance of pre elements */
pre {
  white-space: pre-wrap;
  word-wrap: break-word;
}

/* Ensure proper spacing for dropdown toggles */
.dropdown-toggle::after {
  margin-left: 0.255em;
}

/* Improve the appearance of form check inputs */
.form-check {
  min-height: 1.5rem;
  padding-left: 1.5em;
}

/* Ensure proper spacing for breadcrumbs */
.breadcrumb-item + .breadcrumb-item::before {
  padding-right: 0.5rem;
  padding-left: 0.5rem;
}

/* Improve the appearance of spinners */
.spinner-border {
  width: 2rem;
  height: 2rem;
}

/* Ensure proper spacing for tab content */
.tab-content {
  padding-top: 1rem;
}

/* Improve the appearance of block options */
.block-options {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

/* Ensure proper spacing for flex containers */
.d-flex.gap-2 {
  gap: 0.5rem;
}

/* Improve the appearance of small text */
.fs-sm {
  font-size: 0.875rem;
}

/* Ensure proper spacing for text utilities */
.text-muted {
  color: #6c757d !important;
}

/* Improve the appearance of overflow containers */
.overflow-auto {
  overflow: auto;
}

/* Ensure proper spacing for responsive columns */
@media (min-width: 992px) {
  .col-lg-6 {
    flex: 0 0 auto;
    width: 50%;
  }
}

/* Improve the appearance of responsive utilities */
@media (max-width: 575.98px) {
  .d-sm-flex {
    display: block !important;
  }

  .justify-content-sm-between {
    justify-content: flex-start !important;
  }
}
</style>
