<?php

/**
 * The ai module en lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
$lang->aiapp->common           = 'AI';
$lang->aiapp->squareCategories = array('collection' => 'My collection', 'discovery' => 'Discovery', 'latest' => 'Latest');
$lang->aiapp->newVersionTip    = 'The mini-program has been updated on %s. The above is the past record.';
$lang->aiapp->noMiniProgram    = 'The mini program you visited does not exist.';
$lang->aiapp->title            = 'Mini Programs';
$lang->aiapp->unpublishedTip   = 'The mini program you are using is not published.';
$lang->aiapp->noModelError     = 'No language model is configured, please contact the administrator.';
$lang->aiapp->chatNoResponse   = 'Something went wrong.';
$lang->aiapp->more             = 'More';
$lang->aiapp->collect          = 'Collect';
$lang->aiapp->deleted          = 'Deleted';
$lang->aiapp->clear            = 'Reset';
$lang->aiapp->modelCurrent     = 'Current Model';
$lang->aiapp->categoryList     = array('work' => 'Work', 'personal' => 'Personal', 'life' => 'Life', 'creative' => 'Creative', 'others' => 'Others');
$lang->aiapp->generate         = 'Generate';
$lang->aiapp->regenerate       = 'Regenerate';
$lang->aiapp->emptyNameWarning = '「%s」 cannot be empty';
$lang->aiapp->chatTip          = 'Please enter the field content on the left and try generating the results.';
$lang->aiapp->noModel          = array('The language model has not been configured yet. Please contact the administrator or go to the backend to configure <a id="to-language-model"> the language model.</a>。', 'If the relevant configuration has been completed, please try <a id="reload-current">reloading</a> the page.');
$lang->aiapp->clearContext     = 'The context content has been cleared.';
$lang->aiapp->newChatTip       = 'Please enter the fields on the left to start a new conversation.';
$lang->aiapp->disabledTip      = 'The current mini program is disabled.';
$lang->aiapp->continueasking   = 'Continue asking';

$lang->aiapp->miniProgramSquare  = 'Browse General Agent List';
$lang->aiapp->collectMiniProgram = 'Collect General Agent';
$lang->aiapp->miniProgramChat    = 'Execute General Agent';
$lang->aiapp->view               = 'View General Agent Details';
$lang->aiapp->browseConversation = 'Browse Conversation';
$lang->aiapp->manageGeneralAgent = 'Manage General Agent';
$lang->aiapp->models             = 'Browse Model List';
$lang->aiapp->toolkit            = 'AI Toolkit';
$lang->aiapp->viewAiToolkit      = 'View AI Toolkit';

$lang->aiapp->id                 = 'ID';
$lang->aiapp->model              = 'Model Name';
$lang->aiapp->converse           = 'Converse';
$lang->aiapp->pageSummary        = 'Total %s items.';

$lang->aiapp->tips = new stdClass();
$lang->aiapp->tips->noData = 'No data';

$lang->aiapp->langData                     = new stdClass();
$lang->aiapp->langData->name               = 'ZenTao';
$lang->aiapp->langData->storyReview        = 'Story-Bewertung';
$lang->aiapp->langData->storyReviewHint    = 'Story auf der aktuellen Seite bewerten';
$lang->aiapp->langData->storyReviewMessage = "Hier ist die zu bewertende Story:\n\n### Story-Titel\n\n{title}\n\n### Story-Beschreibung\n\n{spec}\n\n### Akzeptanzkriterien\n\n{verify}";
$lang->aiapp->langData->aiReview           = 'KI-Bewertung';
$lang->aiapp->langData->currentPage        = 'Aktuelle Seite';
$lang->aiapp->langData->story              = 'Story';
$lang->aiapp->langData->demand             = 'Anforderungspool-Story';
$lang->aiapp->langData->bug                = 'Fehler';
$lang->aiapp->langData->doc                = 'Dokument';
$lang->aiapp->langData->design             = 'Design';
$lang->aiapp->langData->feedback           = 'Rückmeldung';
$lang->aiapp->langData->currentDocContent  = 'Aktuelles Dokument';
$lang->aiapp->langData->globalMemoryTitle  = 'All';
$lang->aiapp->langData->zaiConfigNotValid  = 'ZAI-Konfiguration wurde noch nicht eingerichtet. Bitte wenden Sie sich an den Administrator, um <a href="{zaiConfigUrl}">ZAI zu konfigurieren</a>.<br>Falls die Konfiguration bereits abgeschlossen wurde, versuchen Sie bitte, die Seite neu zu laden.';
$lang->aiapp->langData->unauthorizedError  = 'Autorisierung fehlgeschlagen, ungültiger API-Schlüssel. Bitte wenden Sie sich an den Administrator, um <a href="{zaiConfigUrl}">ZAI zu konfigurieren</a>.<br>Falls die Konfiguration bereits abgeschlossen wurde, versuchen Sie bitte, die Seite neu zu laden.';
$lang->aiapp->langData->processDataPrefix  = "Die zu verarbeitenden Daten lauten wie folgt:\n{data}";
$lang->aiapp->langData->processedDataResult= "Die verarbeiteten Daten lauten wie folgt:\n```json\n{data}\n```";
$lang->aiapp->langData->agentResultSummary = 'Erklären Sie die Änderungen an den Daten in der Lösung, versuchen Sie, jede geänderte Eigenschaft zu erklären.';
$lang->aiapp->langData->promptResultTitle  = 'Lösungstitel, wenn kein geeigneter Titel angegeben werden kann';
$lang->aiapp->langData->promptExtraLimit   = 'Normalerweise muss das Werkzeug `{toolName}` nur einmal aufgerufen werden, es sei denn, der Benutzer fordert mehrere Lösungen an.';
$lang->aiapp->langData->promptResultReturn = 'Die verarbeiteten Daten wurden auf der Oberfläche angezeigt. Keine Wiederholung der Anzeige erforderlich, auch keine weitere Beschreibung oder Erklärung. Zeigen Sie dem Benutzer nicht die rohen JSON-Daten des verarbeiteten Ergebnisses. Erinnern Sie mich lediglich daran, dass ich diese Daten verwenden kann, indem ich auf die Schaltfläche „Auf {formName}-Formular anwenden" klicke.';
$lang->aiapp->langData->goTesting          = 'Zum Testen';
$lang->aiapp->langData->notSupportPreview  = 'Vorschau für diesen Inhalt nicht unterstützt';
$lang->aiapp->langData->dataListSizeInfo   = 'Insgesamt %s Einträge';
$lang->aiapp->langData->promptTestDataIntro= 'Hier ist das Beispiel {type} von {name}:';
$lang->aiapp->langData->searchingKLibs     = 'Suche nach Wissensdatenbanken...';
$lang->aiapp->langData->recentChats        = 'Letzte Chats';
$lang->aiapp->langData->aiTeammateTasks    = 'Digitale Mitarbeiter-Aufgaben';

$lang->aiapp->langData->searchTasks         = 'Suche nach digitalen Mitarbeiter-Aufgaben';
$lang->aiapp->langData->formFillTitle       = 'Formularausfüllung';
$lang->aiapp->langData->formFillUserMessage = 'Bitte füllen Sie das Formular basierend auf den aktuellen Seiteninformationen aus';
$lang->aiapp->langData->formPageContext     = 'Aktueller Seitenkontext';
$lang->aiapp->langData->formCurrentData     = 'Aktuelle Formulardaten';
$lang->aiapp->langData->formFillableFields  = 'Ausfüllbare Felder';
$lang->aiapp->langData->formFieldDefinition = 'Felddefinitionen';
$lang->aiapp->langData->formRequiredField   = 'Pflichtfeld';
$lang->aiapp->langData->formReturnJSONArray = 'Bitte geben Sie ein JSON-Array zurück, jedes Array-Element entspricht einer Datenzeile, die Schlüssel entsprechen den ausfüllbaren Feldnamen. Pflichtfelder müssen Werte haben.';
$lang->aiapp->langData->formZentaoAPITip    = "Bitte verwenden Sie zuerst die zentao-api-Tools, um die erforderlichen Kontextdaten abzurufen, und verwenden Sie dann das submitFormData-Tool, um die ausgefüllten Formulardaten zurückzugeben. Pflichtfelder müssen Werte haben.\nNormalerweise muss submitFormData nur einmal aufgerufen werden, es sei denn, der Benutzer benötigt mehrere Lösungen.";
$lang->aiapp->langData->formResultGenerated = 'Formulardaten wurden generiert.';
$lang->aiapp->langData->formCurrentTarget   = 'Aktuelles';
$lang->aiapp->langData->formApplyDataTip    = 'Bitte klicken Sie auf die Schaltfläche "Auf das aktuelle Formular anwenden", um die Daten in das Formular zu übernehmen.';

$lang->aiapp->langData->submitFormDisplayName = 'Formulardaten einreichen';
$lang->aiapp->langData->submitFormDescription = 'Die ausgefüllten Formulardaten an den Benutzer zurückgeben';
$lang->aiapp->langData->vectorizedData        = 'Vektorisierte Daten';

$lang->aiapp->toolkitTitle = 'ZenTao Toolkit';
$lang->aiapp->toolkitItems = array();
$lang->aiapp->toolkitItems['cli']    = array('title' => 'CLI Skill');
$lang->aiapp->toolkitItems['mcp']    = array('title' => 'MCP Service');
$lang->aiapp->toolkitItems['cli']['image']    = 'static/images/zentao-cli.png';
$lang->aiapp->toolkitItems['cli']['subtitle'] = 'Agents-Tools über die Kommandozeile ZenTao nutzen lassen';
$lang->aiapp->toolkitItems['cli']['intro']    = <<<'MARKDOWN'
ZenTao hat das ZenTao CLI-Tool neu veröffentlicht — es ist nicht nur ein Kommandozeilen-Tool, sondern auch eine Brücke zwischen KI- und Entwicklungsmanagementdaten.

Nach der Installation dieses Skills können Sie KI-Agents (wie Cursor, Claude Code usw.) direkt Projektfortschritte abfragen, Bug-Risiken analysieren oder sogar automatisch Anforderungsdokumente erstellen lassen. Der Skill nutzt ZenTao CLI zum Lesen und Schreiben von ZenTao-Daten und verwandelt große Sprachmodelle in Ihren Entwicklungsmanagement-Assistenten.

#### Hauptmerkmale

* Basiert auf der ZenTao RESTful API 2.0
* Sofort startklar mit einem einzigen Befehl: `npx zentao-cli`
* Sichere Authentifizierung mit Benutzerwechsel
* Daten filtern, sortieren und verarbeiten — HTML wird automatisch zu Markdown konvertiert
* KI-Agent-freundlich mit integrierter Hilfe und nativer Markdown-Ausgabe
* Als KI-Skill verwendbar — Installation per `zentao add-skill` in jeden Agent
* Integrierter MCP-Dienst — starten mit `npx zentao-cli mcp`

#### Unterstützte Agent-Tools

ZenTao CLI kann in allen Agent-Tools verwendet werden, die Skills oder MCP unterstützen. Die folgende Tabelle listet gängige Optionen nach Benutzerfreundlichkeit sortiert auf:

| Anfängerfreundlich | Entwicklerfreundlich | Fortgeschritten/Premium |
|:------------------:|:--------------------:|:-----------------------:|
| [Cursor](https://www.cursor.com/) | [Cline](https://cline.bot/) | [Trae](https://www.trae.ai/) |
| [VS Code Copilot](https://code.visualstudio.com/docs/copilot/overview) | [OpenClaw](https://www.openclaw.ai/) | [Codex](https://openai.com/codex/) |
| [Cherry Studio](https://www.cherry-ai.com/) | [OpenCode](https://www.opencode.ai/) | [Antigravity](https://antigravity.google/) |
| | [Claude Code](https://docs.anthropic.com/en/docs/claude-code.md) | [Codex CLI](https://developers.openai.com/codex/cli/reference) |

#### Schnellstart

##### Schritt 1: Skill installieren

**1. Automatische Installation durch den Agent**: Die meisten modernen Agent-Tools unterstützen die automatische Erkennung und Installation von Skills. Senden Sie einfach die folgende Nachricht an den Agent:

```
Installieren Sie den Skill https://cn.clawhub-mirror.com/catouse/zentao-cli und das vom Skill benötigte zentao-cli Kommandozeilen-Tool.
```

**2. Manuelle Installation**: Entwickler können auch direkt über das Terminal installieren:

```
# zentao-cli global installieren
$ npm install -g zentao-cli
# Weitere Installations- und Ausführungsoptionen
# bun install -g zentao-cli  # ← Mit bun installieren
# npx zentao-cli             # ← Ohne Installation über npx ausführen
# pnpm dlx zentao-cli        # ← Ohne Installation über pnpm ausführen

# Nach der Installation den Skill mit einem Befehl im Agent installieren
$ zentao add-skill
Bitte wählen Sie den zu installierenden KI-Agent:
  1) Claude Code
  2) Cursor
  3) Cherry Studio
  4) Codex
  5) OpenCode
  6) VS Code
  7) Antigravity
  8) Gemini
  9) Alle installieren
Bitte geben Sie eine Nummer ein (1-9):9
```

##### Schritt 2: Kontoanmeldung und Authentifizierung

Nach der Installation müssen Sie sich einmal anmelden. Aus Sicherheitsgründen wird dringend empfohlen, Ihre Anmeldedaten nicht an KI-Agents weiterzugeben. Verwenden Sie stattdessen die folgenden lokalen Konfigurationsmethoden:

1. Umgebungsvariablen (empfohlen): Setzen Sie die ZenTao-URL, den Benutzernamen und das Passwort als Umgebungsvariablen. Das Tool meldet sich automatisch an und erneuert Tokens.

```sh
export ZENTAO_URL=https://zentao.example.com
export ZENTAO_ACCOUNT=admin
export ZENTAO_PASSWORD=123456
```

2. Anmeldung über die Kommandozeile: Sie können sich auch manuell über die Kommandozeile anmelden:

```sh
zentao login -s https://zentao.example.com -u admin -p 123456
```

##### Schritt 3: Gespräche in der Praxis

Nach der Konfiguration können Sie ZenTao im entsprechenden Agent-Tool verwenden, als würden Sie mit einem Kollegen chatten. Hier sind einige praktische Beispiele:

* Anforderungen & Planung: „Ich möchte ein Produkt erstellen, um Benutzerinformationen online zu sammeln. Helfen Sie mir, meine Gedanken zu ordnen und die erste Version der Anforderungen und Pläne zu erstellen. Stellen Sie gerne Fragen."
* Fortschrittsverfolgung: „Welche neuen Anforderungen wurden letzte Woche hinzugefügt? Welche sind schwieriger? Ich möchte im Voraus Pläne für die schwierigen entwickeln."
* Defektanalyse: „Worum geht es bei Bug 329? Was sind die möglichen Ursachen? Gibt es Lösungen?"
* Risikoanalyse: „Wie verläuft Sprint 10? Welche Risiken gibt es?"

#### Upgrades und Wartung

Wenn neue Versionen von ZenTao CLI oder dem Skill verfügbar sind, können Sie wie folgt aktualisieren:

```sh
# CLI selbst aktualisieren
zentao upgrade
# Skill mit dem Befehl add-skill neu installieren
zentao add-skill
```

Sie können auch den Agent bitten, beim Upgrade zu helfen:

```
Helfen Sie mir, zentao-cli zu aktualisieren und den neuesten Skill mit dem Befehl zentao add-skill neu zu installieren.
```

#### FAQ

##### Q: Worin unterscheidet sich dieser CLI-Skill vom zuvor veröffentlichten ZenTao API-Skill? Welchen sollte ich verwenden?

A: Wir empfehlen dringend den CLI-Skill. Er kapselt komplexe API-Details ab, unterstützt mehr Funktionen (wie Datenfilterung, Markdown-Konvertierung) und verbraucht weniger Token. Große Modelle müssen sich keine Sorgen um API-Aufrufe machen und können sich auf die Lösung realer Probleme konzentrieren; während der ZenTao API-Skill vom Modell verlangt, APIs direkt zu verwalten, was fehleranfällig ist.

##### Q: Ich kenne mich mit Agents oder Skills nicht aus. Wie fange ich an?

A: Keine Sorge — bevor die KI die Welt übernimmt, besteht keine Eile. Aufgrund der aktuellen Einschränkungen von Agents können sie die ZenTao-Oberfläche nicht vollständig ersetzen. Wir empfehlen, mit einfachen Abfragen zu beginnen oder den integrierten ZenTao Tour-Skill auszuprobieren, der Sie auf unterhaltsame Weise begleitet.

##### Q: Kann ich dies in ZenTao AI verwenden?

A: Die direkte Verwendung von CLI innerhalb von ZenTao wird noch nicht unterstützt, aber wir entwickeln aktiv die ZAI Agents-Plattform, die in Zukunft die Skill-Installation direkt in ZenTao unterstützen wird.

##### Q: Warum funktionieren bestimmte Operationen (z. B. Moduloperationen, Lesen/Schreiben von Dokumenten) nicht?

A: Das CLI stützt sich derzeit auf die ZenTao API 2.0, und einige Schnittstellen werden noch verbessert. Bleiben Sie gespannt auf zukünftige Updates.

#### Verwandte Ressourcen

* Offizielle ZenTao Skill-Bibliothek: https://github.com/easysoft/zentao-skills
* ZenTao CLI Open-Source-Repository: https://github.com/easysoft/zentao-cli
MARKDOWN;

$lang->aiapp->toolkitItems['mcp']['image']    = 'static/images/zentao-mcp.png';
$lang->aiapp->toolkitItems['mcp']['subtitle'] = 'Agents-Tools über das MCP-Protokoll ZenTao nutzen lassen';
$lang->aiapp->toolkitItems['mcp']['intro']    = <<<'MARKDOWN'
ZenTao MCP ist ein Bridge-Proxy-Dienst, der auf dem MCP-Protokoll (Model Context Protocol) basiert. Er konvertiert automatisch ZenTao API 2.0 und andere OpenAPI-konforme REST-Schnittstellen in Standard-MCP-Tools, sodass KI-Assistenten wie Claude, Cursor und CodeBuddy diese einheitlich aufrufen können – für eine bidirektionale Interaktion mit ZenTao-Daten (Daten aus ZenTao lesen und in ZenTao schreiben).

#### Kernfunktionen

* **Automatische Konvertierung**: Generiert MCP-Tools automatisch aus OpenAPI/Swagger-Dokumenten, ohne manuellen Adapter-Code. Kompatibel mit allen REST-APIs, die dieser Spezifikation entsprechen.
* **Transportprotokoll-Unterstützung**: Unterstützt sowohl Streamable HTTP als auch SSE (Server-Sent Events) – für Kompatibilität (HTTP) und Echtzeit (SSE) bei verschiedenen KI-Clients.
* **Verteiltes Tracing**: Integriertes OpenTelemetry-Tracing und Metrik-Erfassung zur Überwachung von Serviceaufrufketten und Laufzeitmetriken, um Fehler einfacher zu beheben und Dienste zu optimieren.
* **Multi-Service-Proxy**: Eine einzige ZenTao MCP-Instanz kann mehrere verschiedene API-Dienste gleichzeitig vermitteln – nicht nur die ZenTao API, sondern jedes andere OpenAPI-konforme System. Hochgradig erweiterbar.
* **Plattformübergreifend**: Unterstützt Linux, macOS und Windows.

#### Schnellstart

##### (1) MCP-Dienst konfigurieren (eine von vier Optionen wählen)

###### 1. Windows-Konfiguration

**Schritt 1: Paket herunterladen**

* [AMD 64-Bit-Paket](https://pkg.zentao.net/zentao-mcp/1.0.1/zentao-mcp-windows-amd64.zip)
* [ARM 64-Bit-Paket](https://pkg.zentao.net/zentao-mcp/1.0.1/zentao-mcp-windows-arm64.zip)

**Schritt 2: Paket entpacken**

Am Beispiel AMD-64: Das heruntergeladene Paket nach `D:\zentao-mcp` entpacken.

**Schritt 3: MCP-Konfiguration anpassen**

```sh
# Konfigurationsvorlage kopieren:
copy D:\zentao-mcp\config.example.yaml D:\zentao-mcp\config.yaml

# Konfigurationsdatei bearbeiten:
D:\zentao-mcp\config.yaml
schema_url: "D:/zentao-mcp/docs/zentao-openapi.json" # Auf tatsächlichen Dateipfad aktualisieren
base_url: "https://ihre-zentao-domain/api.php/v2"    # Ihre ZenTao-Domain eintragen
```

**Schritt 4: MCP-Dienst starten**

```sh
# Folgenden Befehl in der cmd-Konsole ausführen:
D:\zentao-mcp\bin\zentao-mcp-windows-amd64.exe -config D:\zentao-mcp\config.yaml
```

###### 2. Linux-Konfiguration

**Schritt 1: Paket herunterladen**

```sh
# AMD-64:
curl -k -L -O https://pkg.zentao.net/zentao-mcp/1.0.1/zentao-mcp-linux-amd64.tar.gz
# ARM-64:
curl -k -L -O https://pkg.zentao.net/zentao-mcp/1.0.1/zentao-mcp-linux-arm64.tar.gz
```

**Schritt 2: Paket entpacken**

Am Beispiel AMD-64:

```sh
# Verzeichnis erstellen:
mkdir -p /opt/zentao-mcp
# Paket entpacken:
tar -zxvf zentao-mcp-linux-amd64.tar.gz -C /opt/zentao-mcp
```

**Schritt 3: MCP-Konfiguration anpassen**

```sh
# Konfigurationsvorlage kopieren:
cp /opt/zentao-mcp/config.example.yaml /opt/zentao-mcp/config.yaml

# Konfigurationsdatei bearbeiten:
/opt/zentao-mcp/config.yaml
schema_url: "/opt/zentao-mcp/docs/zentao-openapi.json" # Auf tatsächlichen Dateipfad aktualisieren
base_url: "https://ihre-zentao-domain/api.php/v2"       # Ihre ZenTao-Domain eintragen
```

**Schritt 4: MCP-Dienst starten**

```sh
/opt/zentao-mcp/bin/zentao-mcp-linux-amd64 -config /opt/zentao-mcp/config.yaml
```

###### 3. macOS-Konfiguration

**Schritt 1: Paket herunterladen**

```sh
# AMD-64:
curl -k -L -O https://pkg.zentao.net/zentao-mcp/1.0.1/zentao-mcp-darwin-amd64.tar.gz
# ARM-64:
curl -k -L -O https://pkg.zentao.net/zentao-mcp/1.0.1/zentao-mcp-darwin-arm64.tar.gz
```

**Schritt 2: Paket entpacken**

Am Beispiel AMD-64:

```sh
# Verzeichnis erstellen:
mkdir /opt/zentao-mcp
# Paket entpacken:
tar -zxvf zentao-mcp-darwin-amd64.tar.gz -C /opt/zentao-mcp
```

**Schritt 3: MCP-Konfiguration anpassen**

```sh
# Konfigurationsvorlage kopieren:
cp /opt/zentao-mcp/config.example.yaml /opt/zentao-mcp/config.yaml

# Konfigurationsdatei bearbeiten:
/opt/zentao-mcp/config.yaml
schema_url: "/opt/zentao-mcp/docs/zentao-openapi.json" # Auf tatsächlichen Dateipfad aktualisieren
base_url: "https://ihre-zentao-domain/api.php/v2"       # Ihre ZenTao-Domain eintragen
```

**Schritt 4: MCP-Dienst starten**

```sh
/opt/zentao-mcp/bin/zentao-mcp-darwin-amd64 -config /opt/zentao-mcp/config.yaml
```

###### 4. Aus dem Quellcode starten (für Entwickler)

**Schritt 1: Repository klonen**

```sh
git clone https://github.com/easysoft/zentao-mcp.git
```

**Schritt 2: Projekt starten**

```sh
# Projektverzeichnis aufrufen:
cd zentao-mcp
# Abhängigkeiten herunterladen:
go mod tidy
# Kompilieren:
go build -o zentao-mcp ./cmd/app
```

##### (2) MCP-Client (KI-Assistent) konfigurieren

**Schritt 1: Token über die ZenTao API V2 abrufen**

```sh
curl -X POST "http://ihre-zentao-domain/api.php/v2/user/login" \
   -H "Content-Type: application/json" \
   -d '{"account":"Benutzername","password":"Passwort"}'
```

Das Feld `token` im zurückgegebenen JSON-Objekt ist der Token.

**Schritt 2: MCP im KI-Assistenten konfigurieren**

```json
{
  "mcpServers": {
    "zentao": {
      "disabled": false,
      "type": "mcp",
      "url": "http://127.0.0.1:9090/zentao/mcp",
      "timeout": 60000,
      "headers": {
        "token": "ZenTao API V2 Token",
        "Authorization": ""
      }
    },
    "gitfox": {
      "disabled": false,
      "type": "sse",
      "url": "http://127.0.0.1:9090/gitfox/sse",
      "timeout": 60000,
      "headers": {
        "Authorization": "GitFox Token"
      }
    }
  }
}
```

#### Anwendungsbeispiele

* **Produkt erstellen**: Ein Produkt mit dem Namen „Betriebsüberwachungsplattform" in ZenTao erstellen.
* **Anforderung erstellen**: Eine Anforderung in einem bestimmten ZenTao-Produkt erstellen.
* **Repository erstellen**: Ein Repository mit dem Namen example-repo in GitFox erstellen.
* **Code generieren und pushen**: Scaffold-Code in einem GitFox-Repository generieren und pushen.

#### Verwandte Links

* ZenTao API-Dokumentation: https://www.zentao.net/book/api/2309.html
* GitFox-Einführung: https://www.gitfox.net/
* Projektquellcode: https://github.com/easysoft/zentao-mcp
MARKDOWN;
