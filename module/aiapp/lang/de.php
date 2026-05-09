<?php

/**
 * The ai module en lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
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
$lang->aiapp->langData->globalMemoryTitle  = 'ZenTao';
$lang->aiapp->langData->zaiConfigNotValid  = 'ZAI-Konfiguration wurde noch nicht eingerichtet. Bitte wenden Sie sich an den Administrator, um <a href="{zaiConfigUrl}">ZAI zu konfigurieren</a>.<br>Falls die Konfiguration bereits abgeschlossen wurde, versuchen Sie bitte, die Seite neu zu laden.';
$lang->aiapp->langData->unauthorizedError  = 'Autorisierung fehlgeschlagen, ungültiger API-Schlüssel. Bitte wenden Sie sich an den Administrator, um <a href="{zaiConfigUrl}">ZAI zu konfigurieren</a>.<br>Falls die Konfiguration bereits abgeschlossen wurde, versuchen Sie bitte, die Seite neu zu laden.';
$lang->aiapp->langData->processDataPrefix  = "Die zu verarbeitenden Daten lauten wie folgt:\n{data}";
$lang->aiapp->langData->processedDataResult= "Die verarbeiteten Daten lauten wie folgt:\n```json\n{data}\n```";
$lang->aiapp->langData->agentResultSummary = 'Erklären Sie die Änderungen an den Daten in der Lösung, versuchen Sie, jede geänderte Eigenschaft zu erklären.';
$lang->aiapp->langData->promptResultTitle  = 'Lösungstitel, wenn kein geeigneter Titel angegeben werden kann';
$lang->aiapp->langData->promptExtraLimit   = 'Normalerweise muss das Werkzeug `{toolName}` nur einmal aufgerufen werden, es sei denn, der Benutzer fordert mehrere Lösungen an.';
$lang->aiapp->langData->promptResultReturn = 'Die verarbeiteten Daten wurden auf der Oberfläche angezeigt. Keine Wiederholung der Anzeige erforderlich, auch keine weitere Beschreibung oder Erklärung. Erinnern Sie mich daran, dass ich diese Daten verwenden kann, indem ich auf die Schaltfläche „Auf {formName}-Formular anwenden" klicke.';
$lang->aiapp->langData->goTesting          = 'Zum Testen';
$lang->aiapp->langData->notSupportPreview  = 'Vorschau für diesen Inhalt nicht unterstützt';
$lang->aiapp->langData->dataListSizeInfo   = 'Insgesamt %s Einträge';
$lang->aiapp->langData->promptTestDataIntro= 'Hier ist das Beispiel {type} von {name}:';
$lang->aiapp->langData->searchingKLibs     = 'Suche nach Wissensdatenbanken...';
$lang->aiapp->langData->recentChats        = 'Letzte Chats';
$lang->aiapp->langData->aiTeammateTasks    = 'Digitale Mitarbeiter-Aufgaben';
$lang->aiapp->langData->searchTasks        = 'Suche nach digitalen Mitarbeiter-Aufgaben';

$lang->aiapp->toolkitTitle = 'ZenTao Toolkit';
$lang->aiapp->toolkitItems = array();
$lang->aiapp->toolkitItems['cli']    = array('title' => 'CLI Skill');
$lang->aiapp->toolkitItems['mcp']    = array('title' => 'MCP Service');
$lang->aiapp->toolkitItems['cli']['image']    = 'static/images/zentao-cli.png';
$lang->aiapp->toolkitItems['cli']['subtitle'] = 'Let Agents tools use ZenTao through command line';
$lang->aiapp->toolkitItems['cli']['intro']    = <<<'MARKDOWN'
ZenTao hat das ZenTao CLI-Tool neu veröffentlicht — es ist nicht nur ein Kommandozeilen-Tool, sondern auch eine Brücke zwischen KI- und Entwicklungsmanagementdaten.

Nach der Installation dieses Skills können Sie KI-Agents (wie Cursor, Claude Code usw.) direkt Projektfortschritte abfragen, Bug-Risiken analysieren oder sogar automatisch Anforderungsdokumente erstellen lassen. Der Skill nutzt ZenTao CLI zum Lesen und Schreiben von ZenTao-Daten und verwandelt große Sprachmodelle in Ihren Entwicklungsmanagement-Assistenten.

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

* Offizielle ZenTao Skill-Bibliothek: <https://github.com/easysoft/zentao-skills>
* ZenTao CLI Open-Source-Repository: <https://github.com/easysoft/zentao-cli>
MARKDOWN;
