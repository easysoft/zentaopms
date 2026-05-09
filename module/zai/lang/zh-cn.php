<?php
$lang->zai->setting    = 'ZAI 配置';
$lang->zai->appID      = '应用集成ID';
$lang->zai->host       = '主机';
$lang->zai->port       = '端口';
$lang->zai->token      = '应用密钥';
$lang->zai->adminToken = '管理密钥';
$lang->zai->addSetting = '添加 ZAI 配置';

$lang->zai->configurationUnavailable = 'ZAI 配置不可用。';
$lang->zai->illegalZentaoUser        = '非法禅道用户！';
$lang->zai->onlyPostRequest          = '此操作只支持 POST 请求。';
$lang->zai->vectorizedAlreadyEnabled = '数据向量化已经启用。';
$lang->zai->vectorizedEnabled        = '数据向量化已启用。';
$lang->zai->authenticationFailed     = '认证失败！';
$lang->zai->syncRequestFailed        = '同步请求失败，请稍后再试';
$lang->zai->syncingHint              = '同步过程中，关闭此页面将会暂停同步。';
$lang->zai->syncedWithFailedHint     = '一些数据同步失败，请稍后再试';
$lang->zai->cannotFindMemoryInZai    = '无法在 ZAI 中找到指定 key 的知识库，请重置同步目标。';
$lang->zai->confirmResetSync         = '是否重置同步状态，这将在 ZAI 中创建新的知识库。';
$lang->zai->settingTips              = '请安装<a class="btn btn-link text-primary px-1" style="text-decoration: none;" href="%s" target="_blank">ZAI服务</a>获取密钥。';

$lang->zai->zentaoVectorization       = '禅道数据向量化';
$lang->zai->vectorized                = '数据向量化';
$lang->zai->vectorizedIntro           = '数据向量化会将禅道系统内产生的数据进行向量化，以便于在 AI 对话中进行引用，让 AI 可以更准确地回答问题。';
$lang->zai->vectorizedUnavailableHint = '请先配置 ZAI 应用，并确保 ZAI 服务可用。';
$lang->zai->callZaiAPIFailed          = '调用 ZAI API（%s）失败：%s';

$lang->zai->vectorizedStatus = '状态';
$lang->zai->syncProgress     = '同步进度';
$lang->zai->syncingType      = '同步类型';
$lang->zai->finished         = '已完成';
$lang->zai->failed           = '失败';
$lang->zai->totalSync        = '总计';
$lang->zai->lastSyncTime     = '上次同步时间';

$lang->zai->syncActions = new stdClass();
$lang->zai->syncActions->enable     = '启用数据向量化';
$lang->zai->syncActions->startSync  = '开始同步';
$lang->zai->syncActions->resync     = '重新同步';
$lang->zai->syncActions->pauseSync  = '暂停同步';
$lang->zai->syncActions->resumeSync = '继续同步';
$lang->zai->syncActions->resetSync  = '重置同步';

$lang->zai->syncingTypeList = array();
$lang->zai->syncingTypeList['story']    = '需求';
$lang->zai->syncingTypeList['demand']   = '需求池需求';
$lang->zai->syncingTypeList['bug']      = 'BUG';
$lang->zai->syncingTypeList['doc']      = '文档';
$lang->zai->syncingTypeList['design']   = '设计';
$lang->zai->syncingTypeList['feedback'] = '反馈';

$lang->zai->vectorizedStatusList = array();
$lang->zai->vectorizedStatusList['unavailable'] = '不可用';   // <== 持久化状态
$lang->zai->vectorizedStatusList['disabled']    = '未启用';   // <== 持久化状态
$lang->zai->vectorizedStatusList['wait']        = '等待同步';  // <== 持久化状态
$lang->zai->vectorizedStatusList['syncing']     = '同步中';   // <== 持久化状态
$lang->zai->vectorizedStatusList['paused']      = '已暂停';
$lang->zai->vectorizedStatusList['synced']      = '已同步';   // <== 持久化状态
$lang->zai->vectorizedStatusList['failed']      = '同步失败';

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

$lang->zai->zentaoSkill          = '禅道技能';
$lang->zai->zentaoSkillPromotion = '<div class="text-md text-fore">想在外部Agents中使用禅道？</div><div class="text-gray mt-2">禅道CLI已就绪。</div><div class="text-primary font-bold flex gap-1 items-center mt-2">立即上手<i class="icon icon-arrow-right"></i></div>';
$lang->zai->zentaoSkillLeading   = '通过禅道CLI';
$lang->zai->zentaoSkillTitle     = '在外部Agents工具中使用禅道';
$lang->zai->zentaoSkillSubtitle  = '支持 Claude Code、Codex、VSCode、Cursor、OpenClaw、Hermes...';
$lang->zai->zentaoSkillGuide     = <<<'MARKDOWN'
禅道全新发布ZenTao CLI工具——它不只是一个命令行工具，也是AI与研发管理数据之间的桥梁。

安装这个技能后，你可以让AI Agent（如Cursor、Claude Code等）直接查询项目进度、分析Bug风险，甚至自动生成需求文档。技能会调用ZenTao CLI读写禅道数据，让大模型变身你的研发管理助手。

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
# 全局安装 zentao-cli 工具
$ npm install -g zentao-cli
# 其他安装与运行方式
# bun install -g zentao-cli  # ← 使用 bun 安装
# npx zentao-cli             # ← 通过 npx 免安装运行
# pnpm dlx zentao-cli        # ← 通过 pnpm 免安装运行

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

配置好后，你就能在对应的 Agent 工具里像和同事聊天一样使用禅道了。下面是几个实战示例：

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

也可以直接让Agent帮你升级：

```
“请帮我升级 zentao-cli，并通过 zentao add-skill 命令重新安装最新的技能。”
```

#### 常见问题 (FAQ)

##### Q：这个CLI技能和之前发布的ZenTao-API技能有什么不同？该用哪个？

A：强烈推荐CLI技能。它把复杂的API细节封装好了，支持更多功能（如数据过滤、Markdown转换），还更省Token，大模型不用操心API调用，可以专注解决真实问题；而ZenTao API技能要大模型自己处理API，容易出错。

##### Q：我不懂Agent、技能这些概念，怎么上手？

A：在AI接管地球之前，先别着急。受限于Agent能力，目前还不能完全替代禅道GUI。建议先从简单查询开始，或者试试内置的ZenTao Tour技能，它会用有趣的方式带你体验。

##### Q：可以在禅道AI里使用吗？

A：暂时还不支持在禅道内直接用CLI，但我们正在加紧开发ZAI Agents平台，之后会在禅道里直接支持安装技能。

##### Q：为什么有些操作（比如操作模块、读写文档）实现不了？

A：CLI目前依赖禅道API 2.0，部分接口还在完善中，敬请期待后续更新。

#### 相关内容

* 禅道官方技能库：<https://github.com/easysoft/zentao-skills>
* 禅道CLI开源仓库：<https://github.com/easysoft/zentao-cli>
MARKDOWN;
