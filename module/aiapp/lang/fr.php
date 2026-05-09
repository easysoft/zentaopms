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
$lang->aiapp->langData->storyReview        = 'Revue d\'exigence';
$lang->aiapp->langData->storyReviewHint    = 'Réviser les exigences de la page actuelle';
$lang->aiapp->langData->storyReviewMessage = "Voici l'exigence à réviser :\n\n### Titre de l'exigence\n\n{title}\n\n### Description de l'exigence\n\n{spec}\n\n### Critères d'acceptation de l'exigence\n\n{verify}";
$lang->aiapp->langData->aiReview           = 'Revue IA';
$lang->aiapp->langData->currentPage        = 'Page actuelle';
$lang->aiapp->langData->story              = 'Exigence';
$lang->aiapp->langData->demand             = 'Exigence du pool de demandes';
$lang->aiapp->langData->bug                = 'BUG';
$lang->aiapp->langData->doc                = 'Document';
$lang->aiapp->langData->design             = 'Conception';
$lang->aiapp->langData->feedback           = 'Retour';
$lang->aiapp->langData->currentDocContent  = 'Document actuel';
$lang->aiapp->langData->globalMemoryTitle  = 'ZenTao';
$lang->aiapp->langData->zaiConfigNotValid  = 'La configuration ZAI n\'a pas encore été effectuée. Veuillez contacter l\'administrateur pour <a href="{zaiConfigUrl}">configurer ZAI</a>.<br>Si la configuration correspondante a été terminée, veuillez essayer de recharger la page.';
$lang->aiapp->langData->unauthorizedError  = 'Échec d\'autorisation, clé API invalide. Veuillez contacter l\'administrateur pour <a href="{zaiConfigUrl}">configurer ZAI</a>.<br>Si la configuration correspondante a été terminée, veuillez essayer de recharger la page.';
$lang->aiapp->langData->processDataPrefix  = "Les données à traiter sont les suivantes :\n{data}";
$lang->aiapp->langData->processedDataResult= "Les données traitées sont les suivantes :\n```json\n{data}\n```";
$lang->aiapp->langData->agentResultSummary = 'Expliquez les changements apportés aux données, essayez d\'expliquer chaque attribut modifié.';
$lang->aiapp->langData->promptResultTitle  = 'Titre de la solution, si aucun titre approprié n\'est disponible';
$lang->aiapp->langData->promptExtraLimit   = 'Normalement, l\'outil `{toolName}` ne doit être appelé qu\'une seule fois, sauf si l\'utilisateur demande plusieurs solutions.';
$lang->aiapp->langData->promptResultReturn = 'Les données traitées ont été affichées sur l\'interface. Pas besoin de répéter l\'affichage, ni de décrire ou d\'expliquer davantage. Rappelez-moi que je peux utiliser ces données en cliquant sur le bouton « Appliquer au formulaire {formName} ».';
$lang->aiapp->langData->goTesting          = 'Aller au test';
$lang->aiapp->langData->notSupportPreview  = 'Aperçu non pris en charge pour ce contenu';
$lang->aiapp->langData->dataListSizeInfo   = 'Total %s éléments';
$lang->aiapp->langData->promptTestDataIntro= 'Voici l\'exemple {type} de {name} :';
$lang->aiapp->langData->searchingKLibs     = 'Recherche de bases de connaissances...';
$lang->aiapp->langData->recentChats        = 'Chats récents';
$lang->aiapp->langData->aiTeammateTasks    = 'Tâches de l\'employé numérique';
$lang->aiapp->langData->searchTasks        = 'Rechercher les tâches de l\'employé numérique';

$lang->aiapp->toolkitTitle = 'ZenTao Toolkit';
$lang->aiapp->toolkitItems = array();
$lang->aiapp->toolkitItems['cli']    = array('title' => 'CLI Skill');
$lang->aiapp->toolkitItems['mcp']    = array('title' => 'MCP Service');
$lang->aiapp->toolkitItems['cli']['image']    = 'static/images/zentao-cli.png';
$lang->aiapp->toolkitItems['cli']['subtitle'] = 'Let Agents tools use ZenTao through command line';
$lang->aiapp->toolkitItems['cli']['intro']    = <<<'MARKDOWN'
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
Aidez-moi à mettre à niveau zentao-cli et à réinstaller la dernière compétence avec la commande zentao add-skill.
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
