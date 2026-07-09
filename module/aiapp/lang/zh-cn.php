<?php

/**
 * The ai module zh-cn lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
$lang->aiapp->common           = 'AI';
$lang->aiapp->squareCategories = array('collection' => '我的收藏', 'discovery' => '发现', 'latest' => '最新');
$lang->aiapp->newVersionTip    = '小程序已于%s更新，以上为过往记录';
$lang->aiapp->noMiniProgram    = '您访问的小程序不存在';
$lang->aiapp->title            = '小程序';
$lang->aiapp->unpublishedTip   = '您使用的小程序没有发布';
$lang->aiapp->noModelError     = '暂无可用的语言模型，请联系管理员配置。';
$lang->aiapp->chatNoResponse   = '会话发生了错误';
$lang->aiapp->more             = '更多';
$lang->aiapp->collect          = '收藏';
$lang->aiapp->deleted          = '已删除';
$lang->aiapp->clear            = '清空';
$lang->aiapp->modelCurrent     = '当前语言模型';
$lang->aiapp->categoryList     = array('work' => '工作', 'personal' => '个人', 'life' => '生活', 'creative' => '创意', 'others' => '其它');
$lang->aiapp->generate         = '生成';
$lang->aiapp->regenerate       = '重新生成';
$lang->aiapp->emptyNameWarning = '「%s」不能为空';
$lang->aiapp->chatTip          = '请在左侧输入字段内容，生成结果试试吧。';
$lang->aiapp->noModel          = array('尚未配置语言模型，请联系管理员或跳转至后台配置<a id="to-language-model">语言模型</a>。', '若已完成相关配置，请尝试<a id="reload-current">重新加载</a>页面。');
$lang->aiapp->clearContext     = '上下文内容已清除';
$lang->aiapp->newChatTip       = '请在左侧输入字段内容，开启新对话。';
$lang->aiapp->disabledTip      = '当前小程序已被禁用。';
$lang->aiapp->continueasking   = '继续追问';

$lang->aiapp->miniProgramSquare  = '查看通用智能体广场';
$lang->aiapp->collectMiniProgram = '收藏通用智能体';
$lang->aiapp->miniProgramChat    = '执行通用智能体';
$lang->aiapp->view               = '查看通用智能体详情';
$lang->aiapp->browseConversation = '浏览智能会话';
$lang->aiapp->manageGeneralAgent = '管理通用智能体';
$lang->aiapp->models             = '浏览模型列表';
$lang->aiapp->toolkit            = '智能工具';
$lang->aiapp->viewAiToolkit      = '查看智能工具';

$lang->aiapp->id                 = 'ID';
$lang->aiapp->model              = '模型名称';
$lang->aiapp->converse           = '开始会话';
$lang->aiapp->pageSummary        = '共%s项';

$lang->aiapp->tips = new stdClass();
$lang->aiapp->tips->noData = '暂无数据';

$lang->aiapp->langData                      = new stdClass();
$lang->aiapp->langData->name                = '禅道';
$lang->aiapp->langData->storyReview         = '需求评审';
$lang->aiapp->langData->storyReviewHint     = '对当前页面需求进行评审';
$lang->aiapp->langData->storyReviewMessage  = "下面是要进行评审的需求：\n\n### 需求标题\n\n{title}\n\n### 需求描述\n\n{spec}\n\n### 需求验收标准\n\n{verify}";
$lang->aiapp->langData->aiReview            = 'AI评审';
$lang->aiapp->langData->currentPage         = '当前页面';
$lang->aiapp->langData->story               = '需求';
$lang->aiapp->langData->demand              = '需求池需求';
$lang->aiapp->langData->bug                 = 'BUG';
$lang->aiapp->langData->doc                 = '文档';
$lang->aiapp->langData->design              = '设计';
$lang->aiapp->langData->feedback            = '反馈';
$lang->aiapp->langData->currentDocContent   = '当前文档';
$lang->aiapp->langData->globalMemoryTitle   = '全部';
$lang->aiapp->langData->zaiConfigNotValid   = '尚未进行ZAI配置，请联系管理员进行<a href="{zaiConfigUrl}">ZAI配置</a>。<br>若已完成相关配置，请尝试重新加载页面。';
$lang->aiapp->langData->unauthorizedError   = '授权失败，无效的API密钥，请联系管理员进行<a href="{zaiConfigUrl}">ZAI配置</a>。<br>若已完成相关配置，请尝试重新加载页面。';
$lang->aiapp->langData->processDataPrefix   = "要进行处理的数据如下：\n{data}";
$lang->aiapp->langData->processedDataResult = "处理后的数据如下：\n```json\n{data}\n```";
$lang->aiapp->langData->agentResultSummary  = '对方案中数据的变化进行解释，尽量对变化的属性分别进行说明。';
$lang->aiapp->langData->promptResultTitle   = '方案标题，如果没有合适标题可以省略';
$lang->aiapp->langData->promptExtraLimit    = '通常工具 `{toolName}` 只需要调用一次，除非用户特殊要求提供多个方案。';
$lang->aiapp->langData->promptResultReturn  = '已经在界面展示处理后的数据，无需对处理后的数据进行重复展示，也不需要进一步描述和解释，禁止向用户展示处理后的原始 JSON 数据，仅需要提醒我可以通过点击“应用到{formName}表单”按钮来使用这些数据即可。';
$lang->aiapp->langData->goTesting           = '去调试';
$lang->aiapp->langData->notSupportPreview   = '暂不支持预览该内容';
$lang->aiapp->langData->dataListSizeInfo    = '共%s条数据';
$lang->aiapp->langData->promptTestDataIntro = '下面是要进行{name}的示例{type}：';
$lang->aiapp->langData->searchingKLibs      = '正在查找知识库...';
$lang->aiapp->langData->recentChats         = '最近聊天';
$lang->aiapp->langData->aiTeammateTasks     = '数字员工任务';
$lang->aiapp->langData->searchTasks         = '搜索数字员工任务';
$lang->aiapp->langData->formFillTitle       = '表单填充';
$lang->aiapp->langData->formFillUserMessage = '请根据当前页面信息填写表单';
$lang->aiapp->langData->formPageContext     = '当前页面上下文';
$lang->aiapp->langData->formCurrentData     = '当前表单数据';
$lang->aiapp->langData->formFillableFields  = '可填充字段';
$lang->aiapp->langData->formFieldDefinition = '字段说明';
$lang->aiapp->langData->formRequiredField   = '必填';
$lang->aiapp->langData->formReturnJSONArray = '请返回 JSON 数组，每个数组元素对应表中的一行数据，键名对应上述可填充字段名。必填字段必须提供值。';
$lang->aiapp->langData->formZentaoAPITip    = "请先使用 zentao-api 工具获取所需的上下文数据，然后使用 submitFormData 工具返回填充后的表单数据。必填字段必须提供值。\n通常 submitFormData 只需要调用一次，除非用户特殊要求提供多个方案。";
$lang->aiapp->langData->formResultGenerated = '表单数据已生成。';
$lang->aiapp->langData->formCurrentTarget   = '当前';
$lang->aiapp->langData->formApplyDataTip    = '请点击"应用到当前表单"按钮将数据填充到表单中。';

$lang->aiapp->langData->submitFormDisplayName = '提交表单数据';
$lang->aiapp->langData->submitFormDescription = '将填充后的表单数据返回给用户';

$lang->aiapp->toolkitTitle = '禅道智能工具箱';
$lang->aiapp->toolkitItems = array();
$lang->aiapp->toolkitItems['cli']    = array('title' => 'CLI技能');
$lang->aiapp->toolkitItems['mcp']    = array('title' => 'MCP服务');

$lang->aiapp->toolkitItems['cli']['image']    = 'static/images/zentao-cli.png';
$lang->aiapp->toolkitItems['cli']['subtitle'] = '让Agents工具通过命令行来使用禅道';
$lang->aiapp->toolkitItems['cli']['intro']    = <<<'MARKDOWN'
禅道全新发布了命令行工具，支持通过命令行的方式来访问禅道数据和操作禅道。
命令行工具同时提供开箱即用的技能供Agents使用，安装禅道命令行技能后，您可以让AI Agent（如Cursor、Claude Code等）直接查询项目进度、分析Bug风险，甚至自动生成需求文档。技能会调用禅道命令行工具读写禅道数据，让大模型变身您的研发管理助手。

#### 主要特性

* 基于禅道"RESTful API 2.0"
* 一行命令即可运行：`npx zentao-cli`
* 安全认证，支持多用户切换
* 支持数据筛选、过滤、排序，自动将HTML转为Markdown
* 对AI Agent友好，内置完善帮助信息，原生支持Markdown输出
* 支持作为AI技能使用，`zentao add-skill`一键安装到Agent
* 内置MCP服务，`npx zentao-cli mcp`即可启动

#### 支持的Agents工具

禅道CLI可在所有支持技能或MCP的Agent工具中使用。下表按上手难度从易到难列出常见选择：

| 新手推荐 | 开发者推荐 | 进阶/付费推荐 |
|:----------:|:----------:|:------------:|
| [Cursor](https://www.cursor.com/) | [Cline](https://cline.bot/) | [Trae](https://www.trae.ai/) |
| [VS Code Copilot](https://code.visualstudio.com/docs/copilot/overview) | [OpenClaw](https://www.openclaw.ai/) | [Codex](https://openai.com/codex/) |
| [Cherry Studio](https://www.cherry-ai.com/) | [OpenCode](https://www.opencode.ai/) | [Antigravity](https://antigravity.google/) |
| | [Claude Code](https://docs.anthropic.com/en/docs/claude-code.md) | [Codex CLI](https://developers.openai.com/codex/cli/reference) |

#### 快速开始

##### 第一步：安装技能

**1. 让Agent自动安装**：现代Agent工具大都支持自动发现并安装技能，把下面这段话发给Agent即可：

```
安装https://cn.clawhub-mirror.com/catouse/zentao-cli技能，并安装技能所需的zentao-cli命令行工具。
```

**2. 手动安装**：开发者也可以直接在终端里执行命令安装：

```
# 全局安装zentao-cli工具
$ npm install -g zentao-cli
# 其他安装与运行方式
# bun install -g zentao-cli  # ← 使用bun安装
# npx zentao-cli             # ← 通过npx免安装运行
# pnpm dlx zentao-cli        # ← 通过pnpm免安装运行

# 安装完成后，一键把技能装到Agent中
$ zentao add-skill
请选择要安装的AI Agent:
  1) Claude Code
  2) Cursor
  3) Cherry Studio
  4) Codex
  5) OpenCode
  6) VS Code
  7) Antigravity
  8) Gemini
  9) 全部安装
请输入编号 (1-9):9
```

##### 第二步：账号登录与鉴权

装好后需要先登录一次。出于账号安全考虑，强烈建议不要把账号密码发给AI Agent，请改用以下本地配置方式：

1. 环境变量（推荐）：把禅道URL、用户名和密码写到环境变量里，工具会自动登录并续期Token。

```sh
export ZENTAO_URL=https://zentao.example.com
export ZENTAO_ACCOUNT=admin
export ZENTAO_PASSWORD=123456
```

2. 命令行登录：也可以用命令行手动登录：

```sh
zentao login -s https://zentao.example.com -u admin -p 123456
```

##### 第三步：对话与实战

配置好后，您就能在对应的Agent工具里像和同事聊天一样使用禅道了。下面是几个实战示例：

* 需求与规划：“我想创建一个产品，用来在线收集用户信息，请帮我整理思路，并生成第一版需求和计划，有问题尽管问我。”
* 进度追踪：“上周新增了哪些需求？哪些比较难？我想针对难点提前制定方案。”
* 缺陷分析：“BUG 329 是什么问题？可能的原因是什么？有解决方案吗？”
* 风险分析：“迭代10的执行情况如何？有哪些风险？”

#### 升级与维护

ZenTao CLI或技能有新版本时，可以这样升级：

```sh
# 升级CLI本身
zentao upgrade
# 再用add-skill命令升级技能
zentao add-skill
```

也可以直接让Agent帮您升级：

```
请帮我升级zentao-cli，并通过zentao add-skill命令重新安装最新的技能。
```

#### 常见问题 (FAQ)

##### Q：这个CLI技能和之前发布的ZenTao-API技能有什么不同？该用哪个？

A：强烈推荐CLI技能。它把复杂的API细节封装好了，支持更多功能（如数据过滤、Markdown转换），还更省Token，大模型不用操心API调用，可以专注解决真实问题；而ZenTao API技能要大模型自己处理API，容易出错。

##### Q：我不懂Agent、技能这些概念，怎么上手？

A：在AI接管地球之前，先别着急。受限于Agent能力，目前还不能完全替代禅道GUI。建议先从简单查询开始，或者试试内置的ZenTao Tour技能，它会用有趣的方式带您体验。

##### Q：可以在禅道AI里使用吗？

A：暂时还不支持在禅道内直接用CLI，但我们正在加紧开发ZAI Agents平台，之后会在禅道里直接支持安装技能。

##### Q：为什么有些操作（比如操作模块、读写文档）实现不了？

A：CLI目前依赖禅道API 2.0，部分接口还在完善中，敬请期待后续更新。

#### 相关内容

* 禅道官方技能库：https://github.com/easysoft/zentao-skills
* 禅道CLI开源仓库：https://github.com/easysoft/zentao-cli
MARKDOWN;

$lang->aiapp->toolkitItems['mcp']['image']    = 'static/images/zentao-mcp.png';
$lang->aiapp->toolkitItems['mcp']['subtitle'] = '让Agents工具通过MCP协议来使用禅道';
$lang->aiapp->toolkitItems['mcp']['intro']    = <<<'MARKDOWN'
禅道MCP是基于MCP模型上下文协议实现的桥接代理服务。可将禅道API2.0等遵循OpenAPI规范的REST接口，自动转为MCP标准工具，供Claude、Cursor、CodeBuddy等AI助手统一调用，实现跟禅道数据的相互调用（可以从禅道中获取数据，也可以更新禅道中的数据）。

#### 核心特性

* **自动转换能力**：从OpenAPI/Swagger文档自动生成MCP工具，无需人工编写适配逻辑，适配所有遵循该规范的REST API。
* **传输协议支持**：同时兼容Streamable HTTP和SSE（Server-Sent Events），兼顾兼容（HTTP）和实时性（SSE），适配不同AI客户端的通信需求。
* **链路追踪**：内置OpenTelemetry链路追踪和指标收集，能监控服务调用链路、收集运行指标，方便问题排查和服务优化。
* **多服务代理**：单个禅道MCP实例可同时代理多个不同的API服务，不仅支持禅道API，还能适配其他遵循OpenAPI规范的系统API，扩展性强。
* **跨平台部署**：支持Linux、macOS、Windows主流系统，部署灵活。

#### 快速开始

##### （一）配置MCP服务（四选一即可）

###### 1. Windows用户配置方式

**第一步：下载安装包**

* [AMD 64位包](https://pkg.zentao.net/zentao-mcp/1.0.1/zentao-mcp-windows-amd64.zip)
* [ARM 64位包](https://pkg.zentao.net/zentao-mcp/1.0.1/zentao-mcp-windows-arm64.zip)

**第二步：解压包**

以AMD-64位为例，将下载的包解压到目录 `D:\zentao-mcp`。

**第三步：修改MCP配置**

```sh
# 复制配置模板：
copy D:\zentao-mcp\config.example.yaml D:\zentao-mcp\config.yaml

# 修改配置文件：
D:\zentao-mcp\config.yaml
schema_url: "D:/zentao-mcp/docs/zentao-openapi.json" # 更新为实际文件路径
base_url: "https://禅道域名/api.php/v2"               # 修改您的禅道访问域名
```

**第四步：启动MCP服务**

```sh
# 在cmd命令行执行启动命令：
D:\zentao-mcp\bin\zentao-mcp-windows-amd64.exe -config D:\zentao-mcp\config.yaml
```

###### 2. Linux用户配置方式

**第一步：下载包**

```sh
# AMD-64位：
curl -k -L -O https://pkg.zentao.net/zentao-mcp/1.0.1/zentao-mcp-linux-amd64.tar.gz
# ARM-64位：
curl -k -L -O https://pkg.zentao.net/zentao-mcp/1.0.1/zentao-mcp-linux-arm64.tar.gz
```

**第二步：解压包**

以AMD-64位为例：

```sh
# 建目录：
mkdir -p /opt/zentao-mcp
# 解压包：
tar -zxvf zentao-mcp-linux-amd64.tar.gz -C /opt/zentao-mcp
```

**第三步：修改MCP配置**

```sh
# 复制配置模板：
cp /opt/zentao-mcp/config.example.yaml /opt/zentao-mcp/config.yaml

# 修改配置文件：
/opt/zentao-mcp/config.yaml
schema_url: "/opt/zentao-mcp/docs/zentao-openapi.json" # 更新为实际文件路径
base_url: "https://禅道域名/api.php/v2"                 # 修改您的禅道访问域名
```

**第四步：启动MCP服务**

```sh
/opt/zentao-mcp/bin/zentao-mcp-linux-amd64 -config /opt/zentao-mcp/config.yaml
```

###### 3. Mac用户配置方式

**第一步：下载包**

```sh
# AMD-64位：
curl -k -L -O https://pkg.zentao.net/zentao-mcp/1.0.1/zentao-mcp-darwin-amd64.tar.gz
# ARM-64位：
curl -k -L -O https://pkg.zentao.net/zentao-mcp/1.0.1/zentao-mcp-darwin-arm64.tar.gz
```

**第二步：解压包**

以AMD-64位为例：

```sh
# 建目录：
mkdir /opt/zentao-mcp
# 解压包：
tar -zxvf zentao-mcp-darwin-amd64.tar.gz -C /opt/zentao-mcp
```

**第三步：修改MCP配置**

```sh
# 复制配置模板：
cp /opt/zentao-mcp/config.example.yaml /opt/zentao-mcp/config.yaml

# 修改配置文件：
/opt/zentao-mcp/config.yaml
schema_url: "/opt/zentao-mcp/docs/zentao-openapi.json" # 更新为实际文件路径
base_url: "https://禅道域名/api.php/v2"                 # 修改您的禅道访问域名
```

**第四步：启动MCP服务**

```sh
/opt/zentao-mcp/bin/zentao-mcp-darwin-amd64 -config /opt/zentao-mcp/config.yaml
```

###### 4. 源代码启动（面向开发者）

**第一步：克隆代码**

```sh
git clone https://github.com/easysoft/zentao-mcp.git
```

**第二步：启动项目**

```sh
# 进入项目：
cd zentao-mcp
# 下载依赖：
go mod tidy
# 启动命令：
go build -o zentao-mcp ./cmd/app
```

##### （二）配置MCP客户端（AI助手）

**第一步：调用禅道API V2接口获取Token**

```sh
curl -X POST "http://您的禅道域名/api.php/v2/user/login" \
   -H "Content-Type: application/json" \
   -d '{"account":"用户名","password":"密码"}'
```

该请求返回的 JSON 对象中 `token` 属性即为 Token。

**第二步：在AI助手中配置MCP**

```json
{
  "mcpServers": {
    "zentao": {
      "disabled": false,
      "type": "mcp",
      "url": "http://127.0.0.1:9090/zentao/mcp",
      "timeout": 60000,
      "headers": {
        "token": "禅道API V2 Token",
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

#### 场景示例

* **创建产品**：在禅道中创建一个名为“运维监控平台”的产品。
* **创建需求**：在禅道xxx产品中的产品创建一个xxxx需求。
* **创建代码库**：在GitFox创建名为example-repo的代码库。
* **生成代码并推送至仓库**：在GitFox代码库生成一份脚手架代码并推送。

#### 相关链接

* 禅道API手册：https://www.zentao.net/book/api/2309.html
* GitFox介绍：https://www.gitfox.net/
* 项目源码：https://github.com/easysoft/zentao-mcp
MARKDOWN;
