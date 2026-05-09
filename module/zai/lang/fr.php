<?php
$lang->zai->setting    = 'Configuration ZAI';
$lang->zai->appID      = 'ID Application';
$lang->zai->host       = 'Hôte';
$lang->zai->port       = 'Port';
$lang->zai->token      = 'Clé Application';
$lang->zai->adminToken = 'Clé Admin';
$lang->zai->addSetting = 'Ajouter Configuration ZAI';

$lang->zai->configurationUnavailable = 'Configuration ZAI non disponible.';
$lang->zai->illegalZentaoUser        = 'Utilisateur Zentao illégal !';
$lang->zai->onlyPostRequest          = 'Cette opération ne prend en charge que les requêtes POST.';
$lang->zai->vectorizedAlreadyEnabled = 'La vectorisation des données est déjà activée.';
$lang->zai->vectorizedEnabled        = 'Vectorisation des données activée.';
$lang->zai->authenticationFailed     = 'Échec de l\'authentification !';
$lang->zai->syncRequestFailed        = 'Échec de la demande de synchronisation, veuillez réessayer plus tard';
$lang->zai->syncingHint              = 'Fermer cette page pendant la synchronisation mettra en pause le processus de synchronisation.';
$lang->zai->syncedWithFailedHint     = 'Certaines données de synchronisation ont échoué, veuillez réessayer plus tard';
$lang->zai->cannotFindMemoryInZai    = 'Impossible de trouver la base de connaissances avec la clé spécifiée dans ZAI, veuillez réinitialiser la cible de synchronisation.';
$lang->zai->confirmResetSync         = 'Voulez-vous réinitialiser l\'état de synchronisation ? Cela créera une nouvelle base de connaissances dans ZAI.';
$lang->zai->settingTips              = 'Please install <a class="btn btn-link text-primary px-1" style="text-decoration: none;" href="%s" target="_blank">ZAI service</a> to get the key.';

$lang->zai->zentaoVectorization       = 'Vectorisation des Données Zentao';
$lang->zai->vectorized                = 'Vectorisation des Données';
$lang->zai->vectorizedIntro           = 'La vectorisation des données convertira les données générées dans le système Zentao en vecteurs pour référence dans les conversations IA, permettant à l\'IA de répondre aux questions plus précisément.';
$lang->zai->vectorizedUnavailableHint = 'Veuillez d\'abord configurer l\'application ZAI et vous assurer que le service ZAI est disponible.';
$lang->zai->callZaiAPIFailed          = 'Échec de l\'appel à l\'API ZAI (%s) : %s';

$lang->zai->vectorizedStatus = 'État';
$lang->zai->syncProgress     = 'Progrès de Synchronisation';
$lang->zai->syncingType      = 'Type de Synchronisation';
$lang->zai->finished         = 'Terminé';
$lang->zai->failed           = 'Échoué';
$lang->zai->totalSync        = 'Total';
$lang->zai->lastSyncTime     = 'Dernière Synchronisation';

$lang->zai->syncActions = new stdClass();
$lang->zai->syncActions->enable     = 'Activer la Vectorisation des Données';
$lang->zai->syncActions->startSync  = 'Démarrer la Synchronisation';
$lang->zai->syncActions->resync     = 'Resynchroniser';
$lang->zai->syncActions->pauseSync  = 'Suspendre la Synchronisation';
$lang->zai->syncActions->resumeSync = 'Reprendre la Synchronisation';
$lang->zai->syncActions->resetSync  = 'Réinitialiser la Synchronisation';

$lang->zai->syncingTypeList = array();
$lang->zai->syncingTypeList['story']    = 'Histoire';
$lang->zai->syncingTypeList['demand']   = 'Demande';
$lang->zai->syncingTypeList['bug']      = 'Bug';
$lang->zai->syncingTypeList['doc']      = 'Document';
$lang->zai->syncingTypeList['design']   = 'Conception';
$lang->zai->syncingTypeList['feedback'] = 'Commentaire';

$lang->zai->vectorizedStatusList = array();
$lang->zai->vectorizedStatusList['unavailable'] = 'Non disponible';   // <== État persistant
$lang->zai->vectorizedStatusList['disabled']    = 'Désactivé';        // <== État persistant
$lang->zai->vectorizedStatusList['wait']        = 'En attente';       // <== État persistant
$lang->zai->vectorizedStatusList['syncing']     = 'Synchronisation';  // <== État persistant
$lang->zai->vectorizedStatusList['paused']      = 'En pause';
$lang->zai->vectorizedStatusList['synced']      = 'Synchronisé';      // <== État persistant
$lang->zai->vectorizedStatusList['failed']      = 'Échec de Synchronisation';

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
$lang->zai->zentaoSkillPromotion = '<div class="text-md text-fore">Voulez-vous utiliser ZenTao dans des Agents externes ?</div><div class="text-gray mt-2">ZenTao CLI est prêt.</div><div class="text-primary font-bold flex gap-1 items-center mt-2">Commencer<i class="icon icon-arrow-right"></i></div>';
$lang->zai->zentaoSkillLeading   = 'Avec ZenTao CLI';
$lang->zai->zentaoSkillTitle     = 'Utilisez ZenTao dans les outils Agents externes';
$lang->zai->zentaoSkillSubtitle  = 'Compatible avec Claude Code, Codex, VSCode, Cursor, OpenClaw, Hermes...';
$lang->zai->zentaoSkillGuide     = <<<'MARKDOWN'
ZenTao vient de publier l'outil ZenTao CLI — ce n'est pas seulement un outil en ligne de commande, c'est aussi un pont entre l'IA et les données de gestion de développement.

Après avoir installé cette compétence, vous pouvez demander aux Agents IA (tels que Cursor, Claude Code, etc.) de consulter directement l'avancement du projet, d'analyser les risques liés aux bugs, ou même de générer automatiquement des documents de exigences. La compétence utilise ZenTao CLI pour lire et écrire les données ZenTao, transformant les grands modèles en votre assistant de gestion de développement.

#### Outils Agents pris en charge

ZenTao CLI peut être utilisé dans tous les outils Agents qui prennent en charge les compétences ou MCP. Le tableau ci-dessous résume les options courantes, triées par facilité d'utilisation :

| Débutants | Développeurs | Avancé/Premium |
|:---------:|:------------:|:--------------:|
| [Cursor](https://www.cursor.com/) | [Cline](https://cline.bot/) | [Trae](https://www.trae.ai/) |
| [VS Code Copilot](https://code.visualstudio.com/docs/copilot/overview) | [OpenClaw](https://www.openclaw.ai/) | [Codex](https://openai.com/codex/) |
| [Cherry Studio](https://www.cherry-ai.com/) | [OpenCode](https://www.opencode.ai/) | [Antigravity](https://antigravity.google/) |
| | [Claude Code](https://docs.anthropic.com/en/docs/claude-code.md) | [Codex CLI](https://developers.openai.com/codex/cli/reference) |

#### Démarrage rapide

##### Étape 1 : Installer la compétence

**1. Installation automatique par l'Agent** : La plupart des outils Agents modernes prennent en charge la découverte et l'installation automatiques des compétences. Envoyez simplement le message suivant à l'Agent :

```
Installez la compétence https://cn.clawhub-mirror.com/catouse/zentao-cli et installez l'outil en ligne de commande zentao-cli requis par la compétence.
```

**2. Installation manuelle** : Les développeurs peuvent également installer directement via le terminal :

```
# Installer zentao-cli globalement
$ npm install -g zentao-cli
# Autres options d'installation et d'exécution
# bun install -g zentao-cli  # ← Installer avec bun
# npx zentao-cli             # ← Exécuter sans installation via npx
# pnpm dlx zentao-cli        # ← Exécuter sans installation via pnpm

# Après l'installation, installez la compétence dans l'Agent en une commande
$ zentao add-skill
Veuillez sélectionner l'Agent IA à installer :
  1) Claude Code
  2) Cursor
  3) Cherry Studio
  4) Codex
  5) OpenCode
  6) VS Code
  7) Antigravity
  8) Gemini
  9) Tout installer
Entrez un numéro (1-9) :9
```

##### Étape 2 : Connexion et authentification du compte

Après l'installation, vous devez vous connecter une première fois. Pour la sécurité de votre compte, il est fortement recommandé de ne pas partager vos identifiants avec les Agents IA. Utilisez plutôt les méthodes de configuration locales suivantes :

1. Variables d'environnement (recommandé) : Définissez l'URL ZenTao, le nom d'utilisateur et le mot de passe comme variables d'environnement. L'outil se connectera automatiquement et renouvellera les jetons.

```sh
export ZENTAO_URL=https://zentao.example.com
export ZENTAO_ACCOUNT=admin
export ZENTAO_PASSWORD=123456
```

2. Connexion en ligne de commande : Vous pouvez également vous connecter manuellement via la ligne de commande :

```sh
zentao login -s https://zentao.example.com -u admin -p 123456
```

##### Étape 3 : Conversations en pratique

Une fois configuré, vous pouvez utiliser ZenTao dans l'outil Agent correspondant comme si vous discutiez avec un collègue. Voici quelques exemples pratiques :

* Exigences et planification : « Je veux créer un produit pour collecter des informations utilisateur en ligne. Aidez-moi à organiser mes idées et à générer la première version des exigences et des plans. N'hésitez pas à me poser des questions. »
* Suivi de progression : « Quelles nouvelles exigences ont été ajoutées la semaine dernière ? Lesquelles sont plus difficiles ? Je voudrais élaborer des plans pour les plus difficiles à l'avance. »
* Analyse des défauts : « De quoi s'agit-il dans le Bug 329 ? Quelles sont les causes possibles ? Y a-t-il des solutions ? »
* Analyse des risques : « Comment se déroule le Sprint 10 ? Quels sont les risques ? »

#### Mises à niveau et maintenance

Lorsque de nouvelles versions de ZenTao CLI ou de la compétence sont disponibles, vous pouvez mettre à niveau comme suit :

```sh
# Mettre à niveau le CLI lui-même
zentao upgrade
# Réinstaller la compétence avec la commande add-skill
zentao add-skill
```

Vous pouvez également demander à l'Agent de vous aider à mettre à niveau :

```
« Aidez-moi à mettre à niveau zentao-cli et à réinstaller la dernière compétence avec la commande zentao add-skill. »
```

#### FAQ

##### Q : En quoi cette compétence CLI diffère de la compétence ZenTao API publiée précédemment ? Laquelle dois-je utiliser ?

A : Nous recommandons vivement la compétence CLI. Elle encapsule les détails complexes de l'API, prend en charge davantage de fonctionnalités (telles que le filtrage de données, la conversion Markdown), et consomme moins de jetons. Les grands modèles n'ont pas à se soucier des appels API et peuvent se concentrer sur la résolution de problèmes réels ; tandis que la compétence API ZenTao nécessite que le modèle gère directement les API, ce qui est sujet aux erreurs.

##### Q : Je ne suis pas familier avec les Agents ou les compétences. Comment commencer ?

A : Pas de souci — avant que l'IA ne prenne le contrôle du monde, il n'y a pas d'urgence. En raison des limitations actuelles des Agents, ils ne peuvent pas remplacer entièrement l'interface graphique ZenTao. Nous vous suggérons de commencer par des requêtes simples, ou d'essayer la compétence intégrée ZenTao Tour, qui vous guidera de manière engageante.

##### Q : Puis-je utiliser ceci dans ZenTao AI ?

A : L'utilisation directe de CLI dans ZenTao n'est pas encore prise en charge, mais nous développons activement la plateforme ZAI Agents, qui prendra en charge l'installation de compétences directement dans ZenTao à l'avenir.

##### Q : Pourquoi certaines opérations (par exemple, les opérations sur les modules, la lecture/écriture de documents) ne fonctionnent pas ?

A : Le CLI s'appuie actuellement sur l'API ZenTao 2.0, et certaines interfaces sont encore en cours d'amélioration. Restez à l'écoute pour les futures mises à jour.

#### Ressources associées

* Bibliothèque de compétences officielle ZenTao : <https://github.com/easysoft/zentao-skills>
* Dépôt open source ZenTao CLI : <https://github.com/easysoft/zentao-cli>
MARKDOWN;
