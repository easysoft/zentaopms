<?php
$lang->zai->setting    = 'ZAI Setting';
$lang->zai->appID      = 'App ID';
$lang->zai->host       = 'Host';
$lang->zai->port       = 'Port';
$lang->zai->token      = 'App Secret';
$lang->zai->adminToken = 'Admin Secret';
$lang->zai->addSetting = 'Add ZAI Setting';

$lang->zai->configurationUnavailable = 'ZAI configuration unavailable.';
$lang->zai->illegalZentaoUser        = 'Illegal Zentao user!';
$lang->zai->onlyPostRequest          = 'This operation only supports POST requests.';
$lang->zai->vectorizedAlreadyEnabled = 'Data vectorization is already enabled.';
$lang->zai->vectorizedEnabled        = 'Data vectorization enabled.';
$lang->zai->authenticationFailed     = 'Authentication failed!';
$lang->zai->syncRequestFailed        = 'Sync request failed, please try again later';
$lang->zai->syncingHint              = 'Closing this page during sync will pause the sync process.';
$lang->zai->syncedWithFailedHint     = 'Some data sync failed, please try again later';
$lang->zai->cannotFindMemoryInZai    = 'Cannot find knowledge base with specified key in ZAI, please reset sync target.';
$lang->zai->confirmResetSync         = 'Do you want to reset sync status? This will create a new knowledge base in ZAI.';
$lang->zai->settingTips              = 'Please install <a class="btn btn-link text-primary px-1" style="text-decoration: none;" href="%s" target="_blank">ZAI service</a> to get the key.';

$lang->zai->zentaoVectorization       = 'Zentao Data Vectorization';
$lang->zai->vectorized                = 'Data Vectorization';
$lang->zai->vectorizedIntro           = 'Data vectorization will convert data generated in the Zentao system into vectors for reference in AI conversations, allowing AI to answer questions more accurately.';
$lang->zai->vectorizedUnavailableHint = 'Please configure ZAI application first and ensure ZAI service is available.';
$lang->zai->callZaiAPIFailed          = 'Failed to call ZAI API (%s): %s';

$lang->zai->vectorizedStatus = 'Status';
$lang->zai->syncProgress     = 'Sync Progress';
$lang->zai->syncingType      = 'Sync Type';
$lang->zai->finished         = 'Finished';
$lang->zai->failed           = 'Failed';
$lang->zai->totalSync        = 'Total';
$lang->zai->lastSyncTime     = 'Last Sync Time';

$lang->zai->syncActions = new stdClass();
$lang->zai->syncActions->enable     = 'Enable Data Vectorization';
$lang->zai->syncActions->startSync  = 'Start Sync';
$lang->zai->syncActions->resync     = 'Resync';
$lang->zai->syncActions->pauseSync  = 'Pause Sync';
$lang->zai->syncActions->resumeSync = 'Resume Sync';
$lang->zai->syncActions->resetSync  = 'Reset Sync';

$lang->zai->syncingTypeList = array();
$lang->zai->syncingTypeList['story']    = 'Story';
$lang->zai->syncingTypeList['demand']   = 'Demand';
$lang->zai->syncingTypeList['bug']      = 'Bug';
$lang->zai->syncingTypeList['doc']      = 'Document';
$lang->zai->syncingTypeList['design']   = 'Design';
$lang->zai->syncingTypeList['feedback'] = 'Feedback';

$lang->zai->vectorizedStatusList = array();
$lang->zai->vectorizedStatusList['unavailable'] = 'Unavailable';   // <== Persistent state
$lang->zai->vectorizedStatusList['disabled']    = 'Disabled';      // <== Persistent state
$lang->zai->vectorizedStatusList['wait']        = 'Waiting Sync';  // <== Persistent state
$lang->zai->vectorizedStatusList['syncing']     = 'Syncing';       // <== Persistent state
$lang->zai->vectorizedStatusList['paused']      = 'Paused';
$lang->zai->vectorizedStatusList['synced']      = 'Synced';        // <== Persistent state
$lang->zai->vectorizedStatusList['failed']      = 'Sync Failed';

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
$lang->zai->zentaoSkillPromotion = '<div class="text-md text-fore">Want to use ZenTao in external Agents?</div><div class="text-gray mt-2">Zentao CLI is ready.</div><div class="text-primary font-bold flex gap-1 items-center mt-2">Get Started<i class="icon icon-arrow-right"></i></div>';
$lang->zai->zentaoSkillLeading   = 'Powered by ZenTao CLI';
$lang->zai->zentaoSkillTitle     = 'Connect ZenTao to Your AI Agent Tools';
$lang->zai->zentaoSkillSubtitle  = 'Supports Claude Code, Codex, VSCode, Cursor, OpenClaw, Hermes...';
$lang->zai->zentaoSkillGuide     = <<<'MARKDOWN'
ZenTao CLI is more than a command-line tool. It connects AI agents with your R&D management data.

After installing the ZenTao skill, AI agents such as Cursor and Claude Code can check project status, assess bug risks, and even generate requirements documents. The skill reads and writes ZenTao data through ZenTao CLI, turning your LLM-powered tools into practical R&D management assistants.

#### Supported Agent Tools

ZenTao CLI can be used in all agent tools that support skills or MCP. The table below lists common options sorted by ease of use, from easiest to most advanced:

| Beginner-Friendly | Developer-Friendly | Advanced/Premium |
|:-----------------:|:------------------:|:----------------:|
| [Cursor](https://www.cursor.com/) | [Cline](https://cline.bot/) | [Trae](https://www.trae.ai/) |
| [VS Code Copilot](https://code.visualstudio.com/docs/copilot/overview) | [OpenClaw](https://www.openclaw.ai/) | [Codex](https://openai.com/codex/) |
| [Cherry Studio](https://www.cherry-ai.com/) | [OpenCode](https://www.opencode.ai/) | [Antigravity](https://antigravity.google/) |
| | [Claude Code](https://docs.anthropic.com/en/docs/claude-code.md) | [Codex CLI](https://developers.openai.com/codex/cli/reference) |

#### Quick Start

##### Step 1: Install the Skill

**1. Let your agent install it automatically**: Most modern Agent tools support automatic discovery and installation of skills. Simply send the following message to the Agent:

```
Please install the ZenTao CLI skill from https://cn.clawhub-mirror.com/catouse/zentao-cli and set up the required zentao-cli command-line tool.
```

**2. Manual installation**: Developers can also install directly via the terminal:

```
# Install zentao-cli globally
$ npm install -g zentao-cli
# Other installation and runtime options
# bun install -g zentao-cli  # ← Install with bun
# npx zentao-cli             # ← Run without installation via npx
# pnpm dlx zentao-cli        # ← Run without installation via pnpm

# After installation, install the skill to the Agent with one command
$ zentao add-skill
Please select the AI Agent to install:
  1) Claude Code
  2) Cursor
  3) Cherry Studio
  4) Codex
  5) OpenCode
  6) VS Code
  7) Antigravity
  8) Gemini
  9) Install all
Enter a number (1-9):9
```

##### Step 2: Account Login and Authentication

After installation, you need to log in once. For account security, it is strongly recommended not to share your account credentials with AI Agents. Instead, use the following local configuration methods:

1. Environment variables (recommended): Set the ZenTao URL, username, and password as environment variables. The tool will automatically log in and refresh tokens.

```sh
export ZENTAO_URL=https://zentao.example.com
export ZENTAO_ACCOUNT=admin
export ZENTAO_PASSWORD=123456
```

2. Command-line login: You can also log in manually via the command line:

```sh
zentao login -s https://zentao.example.com -u admin -p 123456
```

##### Step 3: Conversations in Practice

Once configured, you can use ZenTao in the corresponding Agent tool just like chatting with a colleague. Here are some practical examples:

* Requirements & Planning: "I want to create a product to collect user information online. Please help me organize my thoughts and generate the first version of requirements and plans. Feel free to ask me any questions."
* Progress Tracking: "What new requirements were added last week? Which ones are more challenging? I'd like to develop plans for the difficult ones in advance."
* Defect Analysis: "What is Bug 329 about? What are the possible causes? Are there any solutions?"
* Risk Analysis: "How is Sprint 10 progressing? What are the risks?"

#### Upgrades and Maintenance

When new versions of ZenTao CLI or the skill are available, you can upgrade as follows:

```sh
# Upgrade the CLI itself
zentao upgrade
# Reinstall the skill with the add-skill command
zentao add-skill
```

You can also ask the Agent to help you upgrade:

```
"Please help me upgrade zentao-cli and reinstall the latest skill using the zentao add-skill command."
```

#### FAQ

##### Q: How is the ZenTao CLI skill different from the earlier ZenTao API skill? Which one should I use?

A: We strongly recommend the ZenTao CLI skill. It wraps the lower-level API details, supports more capabilities such as data filtering and Markdown conversion, and uses tokens more efficiently. With the CLI skill, LLMs can focus on solving real tasks instead of managing API calls directly. The ZenTao API skill gives the model direct access to the APIs, but that approach is more error-prone.

##### Q: I'm not familiar with agents or skills. How should I get started?

A: No worries. You don't need to master everything on day one. Given the current limitations of AI agents, they cannot fully replace the ZenTao GUI yet. We recommend starting with simple queries first, or trying the built-in ZenTao Tour skill, which guides you through common workflows step by step.

##### Q: Can I use this in ZenTao AI?

A: Direct CLI usage inside ZenTao AI is not supported yet. We are actively developing the ZAI Agents platform, which will support installing skills directly inside ZenTao in the future.

##### Q: Why can't I perform certain operations, such as module operations or reading and writing documents?

A: The CLI currently relies on ZenTao API 2.0, and some API endpoints are still being improved. More capabilities will be added in future updates.

#### Related Resources

* ZenTao Official Skill Library: <https://github.com/easysoft/zentao-skills>
* ZenTao CLI Open Source Repository: <https://github.com/easysoft/zentao-cli>
MARKDOWN;
