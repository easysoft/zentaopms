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
$lang->aiapp->langData->storyReview        = 'Story Review';
$lang->aiapp->langData->storyReviewHint    = 'Review the story on the current page';
$lang->aiapp->langData->storyReviewMessage = "Here is the story to be reviewed:\n\n### Story Title\n\n{title}\n\n### Story Description\n\n{spec}\n\n### Acceptance Criteria\n\n{verify}";
$lang->aiapp->langData->aiReview           = 'AI Review';
$lang->aiapp->langData->currentPage        = 'Current Page';
$lang->aiapp->langData->story              = 'Story';
$lang->aiapp->langData->demand             = 'Demand Pool Story';
$lang->aiapp->langData->bug                = 'Bug';
$lang->aiapp->langData->doc                = 'Document';
$lang->aiapp->langData->design             = 'Design';
$lang->aiapp->langData->feedback           = 'Feedback';
$lang->aiapp->langData->currentDocContent  = 'Current Document';
$lang->aiapp->langData->globalMemoryTitle  = 'ZenTao';
$lang->aiapp->langData->zaiConfigNotValid  = 'ZAI configuration has not been set up yet. Please contact the administrator to <a href="{zaiConfigUrl}">configure ZAI</a>.<br>If the configuration has been completed, please try reloading the page.';
$lang->aiapp->langData->unauthorizedError  = 'Authorization failed, invalid API key. Please contact the administrator to <a href="{zaiConfigUrl}">configure ZAI</a>.<br>If the configuration has been completed, please try reloading the page.';
$lang->aiapp->langData->processDataPrefix  = "The data to be processed is as follows:\n{data}";
$lang->aiapp->langData->processedDataResult= "The processed data is as follows:\n```json\n{data}\n```";
$lang->aiapp->langData->agentResultSummary = 'Explain the changes of the data in the solution, try to explain each changed attribute.';
$lang->aiapp->langData->promptResultTitle  = 'Solution title, if no suitable title can be omitted';
$lang->aiapp->langData->promptExtraLimit   = 'Usually tool `{toolName}` only needs to be called once, unless the user requires multiple solutions.';
$lang->aiapp->langData->promptResultReturn = 'The processed data has been displayed on the interface. No need to repeat the display, nor further describe or explain it. Remind me that I can use this data by clicking the "Apply to {formName} form" button.';
$lang->aiapp->langData->goTesting          = 'Go Testing';
$lang->aiapp->langData->notSupportPreview  = 'Not support preview this content';
$lang->aiapp->langData->dataListSizeInfo   = 'Total %s items';
$lang->aiapp->langData->promptTestDataIntro= 'Here is the example {type} of {name}:';
$lang->aiapp->langData->searchingKLibs     = 'Searching knowledge libraries...';
$lang->aiapp->langData->recentChats        = 'Recent Chats';
$lang->aiapp->langData->aiTeammateTasks    = 'Digital Teammate Tasks';
$lang->aiapp->langData->searchTasks        = 'Search Digital Teammate Tasks';

$lang->aiapp->toolkitTitle = 'ZenTao Toolkit';
$lang->aiapp->toolkitItems = array();
$lang->aiapp->toolkitItems['cli']    = array('title' => 'CLI Skill');
$lang->aiapp->toolkitItems['mcp']    = array('title' => 'MCP Service');
$lang->aiapp->toolkitItems['cli']['image']    = 'static/images/zentao-cli.png';
$lang->aiapp->toolkitItems['cli']['subtitle'] = 'Let Agents tools use ZenTao through command line';
$lang->aiapp->toolkitItems['cli']['intro']    = <<<'MARKDOWN'
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
Please help me upgrade zentao-cli and reinstall the latest skill using the zentao add-skill command.
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
