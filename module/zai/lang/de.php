<?php
$lang->zai->setting    = 'ZAI Einstellungen';
$lang->zai->appID      = 'App ID';
$lang->zai->host       = 'Host';
$lang->zai->port       = 'Port';
$lang->zai->token      = 'App Schlüssel';
$lang->zai->adminToken = 'Admin Schlüssel';
$lang->zai->addSetting = 'ZAI Einstellungen hinzufügen';

$lang->zai->configurationUnavailable = 'ZAI Konfiguration nicht verfügbar.';
$lang->zai->illegalZentaoUser        = 'Ungültiger Zentao-Benutzer!';
$lang->zai->onlyPostRequest          = 'Diese Operation unterstützt nur POST-Anfragen.';
$lang->zai->vectorizedAlreadyEnabled = 'Datenvektorisierung ist bereits aktiviert.';
$lang->zai->vectorizedEnabled        = 'Datenvektorisierung aktiviert.';
$lang->zai->authenticationFailed     = 'Authentifizierung fehlgeschlagen!';
$lang->zai->syncRequestFailed        = 'Synchronisierungsanfrage fehlgeschlagen, bitte versuchen Sie es später erneut';
$lang->zai->syncingHint              = 'Das Schließen dieser Seite während der Synchronisierung pausiert den Synchronisierungsprozess.';
$lang->zai->syncedWithFailedHint     = 'Einige Datensynchronisierungen sind fehlgeschlagen, bitte versuchen Sie es später erneut';
$lang->zai->cannotFindMemoryInZai    = 'Kann Wissensdatenbank mit angegebenem Schlüssel in ZAI nicht finden, bitte setzen Sie das Synchronisierungsziel zurück.';
$lang->zai->confirmResetSync         = 'Möchten Sie den Synchronisierungsstatus zurücksetzen? Dies erstellt eine neue Wissensdatenbank in ZAI.';
$lang->zai->settingTips              = 'Please install <a class="btn btn-link text-primary px-1" style="text-decoration: none;" href="%s" target="_blank">ZAI service</a> to get the key.';

$lang->zai->zentaoVectorization       = 'Zentao Datenvektorisierung';
$lang->zai->vectorized                = 'Datenvektorisierung';
$lang->zai->vectorizedIntro           = 'Die Datenvektorisierung konvertiert im Zentao-System generierte Daten in Vektoren zur Referenz in KI-Gesprächen, wodurch die KI Fragen genauer beantworten kann.';
$lang->zai->vectorizedUnavailableHint = 'Bitte konfigurieren Sie zuerst die ZAI-Anwendung und stellen Sie sicher, dass der ZAI-Service verfügbar ist.';
$lang->zai->callZaiAPIFailed          = 'Aufruf der ZAI API (%s) fehlgeschlagen: %s';

$lang->zai->vectorizedStatus = 'Status';
$lang->zai->syncProgress     = 'Synchronisierungsfortschritt';
$lang->zai->syncingType      = 'Synchronisierungstyp';
$lang->zai->finished         = 'Abgeschlossen';
$lang->zai->failed           = 'Fehlgeschlagen';
$lang->zai->totalSync        = 'Gesamt';
$lang->zai->lastSyncTime     = 'Letzte Synchronisierung';

$lang->zai->syncActions = new stdClass();
$lang->zai->syncActions->enable     = 'Datenvektorisierung aktivieren';
$lang->zai->syncActions->startSync  = 'Synchronisierung starten';
$lang->zai->syncActions->resync     = 'Neu synchronisieren';
$lang->zai->syncActions->pauseSync  = 'Synchronisierung pausieren';
$lang->zai->syncActions->resumeSync = 'Synchronisierung fortsetzen';
$lang->zai->syncActions->resetSync  = 'Synchronisierung zurücksetzen';

$lang->zai->syncingTypeList = array();
$lang->zai->syncingTypeList['story']    = 'Story';
$lang->zai->syncingTypeList['demand']   = 'Anforderung';
$lang->zai->syncingTypeList['bug']      = 'Bug';
$lang->zai->syncingTypeList['doc']      = 'Dokument';
$lang->zai->syncingTypeList['design']   = 'Design';
$lang->zai->syncingTypeList['feedback'] = 'Feedback';

$lang->zai->vectorizedStatusList = array();
$lang->zai->vectorizedStatusList['unavailable'] = 'Nicht verfügbar';    // <== Persistenter Zustand
$lang->zai->vectorizedStatusList['disabled']    = 'Deaktiviert';        // <== Persistenter Zustand
$lang->zai->vectorizedStatusList['wait']        = 'Warten auf Sync';    // <== Persistenter Zustand
$lang->zai->vectorizedStatusList['syncing']     = 'Synchronisierung';   // <== Persistenter Zustand
$lang->zai->vectorizedStatusList['paused']      = 'Pausiert';
$lang->zai->vectorizedStatusList['synced']      = 'Synchronisiert';     // <== Persistenter Zustand
$lang->zai->vectorizedStatusList['failed']      = 'Synchronisierung fehlgeschlagen';

$vectorizedPanelLang = new \stdClass();
$vectorizedPanelLang->vectorized           = $lang->zai->vectorized;
$vectorizedPanelLang->vectorizedIntro      = $lang->zai->vectorizedIntro;
$vectorizedPanelLang->vectorizedStatus     = $lang->zai->vectorizedStatus;
$vectorizedPanelLang->syncProgress         = $lang->zai->syncProgress;
$vectorizedPanelLang->syncingType          = $lang->zai->syncingType;
$vectorizedPanelLang->finished             = $lang->zai->finished;
$vectorizedPanelLang->failed               = $lang->zai->failed;
$vectorizedPanelLang->syncActions          = $lang->zai->syncActions;
$vectorizedPanelLang->syncingTypeList      = $lang->zai->syncingTypeList;
$vectorizedPanelLang->vectorizedStatusList = $lang->zai->vectorizedStatusList;
$vectorizedPanelLang->syncRequestFailed    = $lang->zai->syncRequestFailed;
$vectorizedPanelLang->confirmResetSync     = $lang->zai->confirmResetSync;

$lang->zai->vectorizedPanelLang = $vectorizedPanelLang;

$lang->zai->zentaoSkill          = 'ZenTao Skill';
$lang->zai->zentaoSkillPromotion = '<div class="text-md text-fore">Möchten Sie ZenTao in externen Agents verwenden?</div><div class="text-gray mt-2">ZenTao CLI ist bereit.</div><div class="text-primary font-bold flex gap-1 items-center mt-2">Beginnen<i class="icon icon-arrow-right"></i></div>';
$lang->zai->zentaoSkillLeading   = 'Mit ZenTao CLI';
$lang->zai->zentaoSkillTitle     = 'ZenTao in externen Agent-Tools verwenden';
$lang->zai->zentaoSkillSubtitle  = 'Unterstützt Claude Code, Codex, VSCode, Cursor, OpenClaw, Hermes...';
$lang->zai->zentaoSkillGuide     = <<<'MARKDOWN'
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
