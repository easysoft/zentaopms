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
$lang->aiapp->modelID            = 'Model ID';
$lang->aiapp->abilities          = 'Abilities';
$lang->aiapp->converse           = 'Converse';
$lang->aiapp->pageSummary        = 'Total %s items.';
$lang->aiapp->abilityTypes       = [];
$lang->aiapp->abilityTypes['chat']             = 'Chat';
$lang->aiapp->abilityTypes['function-calling'] = 'Function Calling';
$lang->aiapp->abilityTypes['reasoning']        = 'Reasoning';
$lang->aiapp->abilityTypes['embedding']        = 'Embedding';

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
$lang->aiapp->langData->globalMemoryTitle  = 'All';
$lang->aiapp->langData->zaiConfigNotValid  = 'ZAI configuration has not been set up yet. Please contact the administrator to <a href="{zaiConfigUrl}">configure ZAI</a>.<br>If the configuration has been completed, please try reloading the page.';
$lang->aiapp->langData->unauthorizedError  = 'Authorization failed, invalid API key. Please contact the administrator to <a href="{zaiConfigUrl}">configure ZAI</a>.<br>If the configuration has been completed, please try reloading the page.';
$lang->aiapp->langData->processDataPrefix  = "The data to be processed is as follows:\n{data}";
$lang->aiapp->langData->promptExtraLimit   = 'Usually tool `{toolName}` only needs to be called once, unless the user requires multiple solutions.';
$lang->aiapp->langData->promptResultReturn = 'The processed data has been displayed on the interface. No need to repeat the display, nor further describe or explain it. Do not show the raw JSON data of the processed result to the user. Just remind me that I can use this data by clicking the "Apply to {formName} form" button.';
$lang->aiapp->langData->goTesting          = 'Go Testing';
$lang->aiapp->langData->notSupportPreview  = 'Not support preview this content';
$lang->aiapp->langData->dataListSizeInfo   = 'Total %s items';
$lang->aiapp->langData->promptTestDataIntro= 'Here is the example {type} of {name}:';
$lang->aiapp->langData->searchingKLibs     = 'Searching knowledge libraries...';
$lang->aiapp->langData->recentChats        = 'Recent Chats';
$lang->aiapp->langData->aiTeammateTasks    = 'Digital Teammate Tasks';

$lang->aiapp->langData->processedDataResult = "The processed data is as follows:\n```json\n{data}\n```";
$lang->aiapp->langData->agentResultSummary  = 'Explain the changes of the data in the solution, try to explain each changed attribute.';
$lang->aiapp->langData->promptResultTitle   = 'Solution title, if no suitable title can be omitted';
$lang->aiapp->langData->searchTasks         = 'Search Digital Teammate Tasks';
$lang->aiapp->langData->formFillTitle       = 'Form Filling';
$lang->aiapp->langData->formFillUserMessage = 'Please fill in the form based on the current page information';
$lang->aiapp->langData->formPageContext     = 'Current page context';
$lang->aiapp->langData->formCurrentData     = 'Current form data';
$lang->aiapp->langData->formFillableFields  = 'Fillable fields';
$lang->aiapp->langData->formFieldDefinition = 'Field definitions';
$lang->aiapp->langData->formRequiredField   = 'Required';
$lang->aiapp->langData->formReturnJSONArray = 'Please return a JSON array, each array element corresponds to a row of data, keys correspond to the fillable field names. Required fields must have values.';
$lang->aiapp->langData->formZentaoAPITip    = "Please first use the zentao-api tools to obtain the required context data, then use the submitFormData tool to return the filled form data. Required fields must have values.\nUsually submitFormData only needs to be called once, unless the user requires multiple solutions.";
$lang->aiapp->langData->formResultGenerated = 'Form data has been generated.';
$lang->aiapp->langData->formCurrentTarget   = 'Current';
$lang->aiapp->langData->stepDescription     = 'Step description';
$lang->aiapp->langData->expectDescription   = 'Expected result';

$lang->aiapp->langData->submitFormDisplayName = 'Submit Form Data';
$lang->aiapp->langData->submitFormDescription = 'Return the filled form data to the user';
$lang->aiapp->langData->vectorizedData        = 'Vectorized Data';

$lang->aiapp->toolkitTitle = 'ZenTao Toolkit';
$lang->aiapp->toolkitItems = array();
$lang->aiapp->toolkitItems['cli']    = array('title' => 'CLI Skill');
$lang->aiapp->toolkitItems['mcp']    = array('title' => 'MCP Service');
$lang->aiapp->toolkitItems['cli']['image']    = 'static/images/zentao-cli.png';
$lang->aiapp->toolkitItems['cli']['subtitle'] = 'Let Agents tools use ZenTao through command line';
$lang->aiapp->toolkitItems['cli']['intro']    = <<<'MARKDOWN'
ZenTao CLI is more than a command-line tool. It connects AI agents with your R&D management data.

After installing the ZenTao skill, AI agents such as Cursor and Claude Code can check project status, assess bug risks, and even generate requirements documents. The skill reads and writes ZenTao data through ZenTao CLI, turning your LLM-powered tools into practical R&D management assistants.

#### Key Features

* Built on ZenTao RESTful API 2.0
* Run instantly with a single command: `npx zentao-cli`
* Secure authentication with multi-user switching
* Filter, sort, and process data with automatic HTML-to-Markdown conversion
* AI Agent-friendly with built-in help documentation and native Markdown output
* Use as an AI skill — install to any Agent with `zentao add-skill`
* Built-in MCP service — start with `npx zentao-cli mcp`

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

* ZenTao Official Skill Library: https://github.com/easysoft/zentao-skills
* ZenTao CLI Open Source Repository: https://github.com/easysoft/zentao-cli
MARKDOWN;

$lang->aiapp->toolkitItems['mcp']['image']    = 'static/images/zentao-mcp.png';
$lang->aiapp->toolkitItems['mcp']['subtitle'] = 'Let Agents tools use ZenTao through MCP protocol';
$lang->aiapp->toolkitItems['mcp']['intro']    = <<<'MARKDOWN'
ZenTao MCP is a bridge proxy service based on the MCP (Model Context Protocol). It automatically converts ZenTao API 2.0 and other OpenAPI-compliant REST interfaces into standard MCP tools, allowing AI assistants such as Claude, Cursor, and CodeBuddy to call them uniformly, enabling bidirectional interaction with ZenTao data (both reading from and writing to ZenTao).

#### Core Features

* **Automatic Conversion**: Automatically generates MCP tools from OpenAPI/Swagger documents without manual adapter code. Compatible with all REST APIs following the specification.
* **Transport Protocol Support**: Supports both Streamable HTTP and SSE (Server-Sent Events), balancing compatibility (HTTP) and real-time performance (SSE) for different AI clients.
* **Distributed Tracing**: Built-in OpenTelemetry tracing and metrics collection to monitor service call chains and gather runtime metrics, making troubleshooting and optimization easier.
* **Multi-Service Proxy**: A single ZenTao MCP instance can proxy multiple different API services simultaneously — not just ZenTao API, but any other OpenAPI-compliant system. Highly extensible.
* **Cross-Platform**: Supports Linux, macOS, and Windows.

#### Quick Start

##### (1) Configure MCP Service (choose one of four options)

###### 1. Windows Configuration

**Step 1: Download the package**

* [AMD 64-bit package](https://pkg.zentao.net/zentao-mcp/1.0.1/zentao-mcp-windows-amd64.zip)
* [ARM 64-bit package](https://pkg.zentao.net/zentao-mcp/1.0.1/zentao-mcp-windows-arm64.zip)

**Step 2: Extract the package**

Using AMD-64 as an example, extract the downloaded package to `D:\zentao-mcp`.

**Step 3: Edit MCP configuration**

```sh
# Copy the configuration template:
copy D:\zentao-mcp\config.example.yaml D:\zentao-mcp\config.yaml

# Edit the configuration file:
D:\zentao-mcp\config.yaml
schema_url: "D:/zentao-mcp/docs/zentao-openapi.json" # Update to actual file path
base_url: "https://your-zentao-domain/api.php/v2"    # Update your ZenTao domain
```

**Step 4: Start the MCP service**

```sh
# Run the following command in cmd:
D:\zentao-mcp\bin\zentao-mcp-windows-amd64.exe -config D:\zentao-mcp\config.yaml
```

###### 2. Linux Configuration

**Step 1: Download the package**

```sh
# AMD-64:
curl -k -L -O https://pkg.zentao.net/zentao-mcp/1.0.1/zentao-mcp-linux-amd64.tar.gz
# ARM-64:
curl -k -L -O https://pkg.zentao.net/zentao-mcp/1.0.1/zentao-mcp-linux-arm64.tar.gz
```

**Step 2: Extract the package**

Using AMD-64 as an example:

```sh
# Create directory:
mkdir -p /opt/zentao-mcp
# Extract:
tar -zxvf zentao-mcp-linux-amd64.tar.gz -C /opt/zentao-mcp
```

**Step 3: Edit MCP configuration**

```sh
# Copy the configuration template:
cp /opt/zentao-mcp/config.example.yaml /opt/zentao-mcp/config.yaml

# Edit the configuration file:
/opt/zentao-mcp/config.yaml
schema_url: "/opt/zentao-mcp/docs/zentao-openapi.json" # Update to actual file path
base_url: "https://your-zentao-domain/api.php/v2"       # Update your ZenTao domain
```

**Step 4: Start the MCP service**

```sh
/opt/zentao-mcp/bin/zentao-mcp-linux-amd64 -config /opt/zentao-mcp/config.yaml
```

###### 3. macOS Configuration

**Step 1: Download the package**

```sh
# AMD-64:
curl -k -L -O https://pkg.zentao.net/zentao-mcp/1.0.1/zentao-mcp-darwin-amd64.tar.gz
# ARM-64:
curl -k -L -O https://pkg.zentao.net/zentao-mcp/1.0.1/zentao-mcp-darwin-arm64.tar.gz
```

**Step 2: Extract the package**

Using AMD-64 as an example:

```sh
# Create directory:
mkdir /opt/zentao-mcp
# Extract:
tar -zxvf zentao-mcp-darwin-amd64.tar.gz -C /opt/zentao-mcp
```

**Step 3: Edit MCP configuration**

```sh
# Copy the configuration template:
cp /opt/zentao-mcp/config.example.yaml /opt/zentao-mcp/config.yaml

# Edit the configuration file:
/opt/zentao-mcp/config.yaml
schema_url: "/opt/zentao-mcp/docs/zentao-openapi.json" # Update to actual file path
base_url: "https://your-zentao-domain/api.php/v2"       # Update your ZenTao domain
```

**Step 4: Start the MCP service**

```sh
/opt/zentao-mcp/bin/zentao-mcp-darwin-amd64 -config /opt/zentao-mcp/config.yaml
```

###### 4. Build from Source (for developers)

**Step 1: Clone the repository**

```sh
git clone https://github.com/easysoft/zentao-mcp.git
```

**Step 2: Start the project**

```sh
# Enter project directory:
cd zentao-mcp
# Download dependencies:
go mod tidy
# Build:
go build -o zentao-mcp ./cmd/app
```

##### (2) Configure MCP Client (AI Assistant)

**Step 1: Get the Token via ZenTao API V2**

```sh
curl -X POST "http://your-zentao-domain/api.php/v2/user/login" \
   -H "Content-Type: application/json" \
   -d '{"account":"username","password":"password"}'
```

The `token` field in the returned JSON is the Token.

**Step 2: Configure MCP in your AI assistant**

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

#### Example Scenarios

* **Create a product**: Create a product named "Operations Monitoring Platform" in ZenTao.
* **Create a story**: Create a story in a specific ZenTao product.
* **Create a repository**: Create a repository named example-repo in GitFox.
* **Generate and push code**: Generate scaffold code in a GitFox repository and push it.

#### Related Links

* ZenTao API Documentation: https://www.zentao.net/book/api/2309.html
* GitFox Introduction: https://www.gitfox.net/
* Project Source Code: https://github.com/easysoft/zentao-mcp
MARKDOWN;
