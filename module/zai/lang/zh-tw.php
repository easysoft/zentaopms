<?php
$lang->zai->setting    = 'ZAI 設定';
$lang->zai->appID      = '應用集成ID';
$lang->zai->host       = '主機';
$lang->zai->port       = '端口';
$lang->zai->token      = '應用密鑰';
$lang->zai->adminToken = '管理密鑰';
$lang->zai->addSetting = '添加 ZAI 設定';

$lang->zai->configurationUnavailable = 'ZAI 設定不可用。';
$lang->zai->illegalZentaoUser        = '非法禪道用戶！';
$lang->zai->onlyPostRequest          = '此操作只支持 POST 請求。';
$lang->zai->vectorizedAlreadyEnabled = '數據向量化已經啟用。';
$lang->zai->vectorizedEnabled        = '數據向量化已啟用。';
$lang->zai->authenticationFailed     = '認證失敗！';
$lang->zai->syncRequestFailed        = '同步請求失敗，請稍後再試';
$lang->zai->syncingHint              = '同步過程中，關閉此頁面將會暫停同步。';
$lang->zai->syncedWithFailedHint     = '一些數據同步失敗，請稍後再試';
$lang->zai->cannotFindMemoryInZai    = '無法在 ZAI 中找到指定 key 的知識庫，請重置同步目標。';
$lang->zai->confirmResetSync         = '是否重置同步狀態，這將在 ZAI 中創建新的知識庫。';
$lang->zai->settingTips              = '請安裝<a class="btn btn-link text-primary px-1" style="text-decoration: none;" href="%s" target="_blank">ZAI服務</a>獲取金鑰。';

$lang->zai->zentaoVectorization       = '禪道數據向量化';
$lang->zai->vectorized                = '數據向量化';
$lang->zai->vectorizedIntro           = '數據向量化會將禪道系統內產生的數據進行向量化，以便於在 AI 對話中進行引用，讓 AI 可以更準確地回答問題。';
$lang->zai->vectorizedUnavailableHint = '請先設定 ZAI 應用，並確保 ZAI 服務可用。';
$lang->zai->callZaiAPIFailed          = '調用 ZAI API（%s）失敗：%s';

$lang->zai->vectorizedStatus = '狀態';
$lang->zai->syncProgress     = '同步進度';
$lang->zai->syncingType      = '同步類型';
$lang->zai->finished         = '已完成';
$lang->zai->failed           = '失敗';
$lang->zai->totalSync        = '總計';
$lang->zai->lastSyncTime     = '上次同步時間';

$lang->zai->syncActions = new stdClass();
$lang->zai->syncActions->enable     = '啟用數據向量化';
$lang->zai->syncActions->startSync  = '開始同步';
$lang->zai->syncActions->resync     = '重新同步';
$lang->zai->syncActions->pauseSync  = '暫停同步';
$lang->zai->syncActions->resumeSync = '繼續同步';
$lang->zai->syncActions->resetSync  = '重置同步';

$lang->zai->syncingTypeList = array();
$lang->zai->syncingTypeList['story']    = '需求';
$lang->zai->syncingTypeList['demand']   = '需求池需求';
$lang->zai->syncingTypeList['bug']      = 'BUG';
$lang->zai->syncingTypeList['doc']      = '文檔';
$lang->zai->syncingTypeList['design']   = '設計';
$lang->zai->syncingTypeList['feedback'] = '反饋';

$lang->zai->vectorizedStatusList = array();
$lang->zai->vectorizedStatusList['unavailable'] = '不可用';   // <== 持久化狀態
$lang->zai->vectorizedStatusList['disabled']    = '未啟用';   // <== 持久化狀態
$lang->zai->vectorizedStatusList['wait']        = '等待同步';  // <== 持久化狀態
$lang->zai->vectorizedStatusList['syncing']     = '同步中';   // <== 持久化狀態
$lang->zai->vectorizedStatusList['paused']      = '已暫停';
$lang->zai->vectorizedStatusList['synced']      = '已同步';   // <== 持久化狀態
$lang->zai->vectorizedStatusList['failed']      = '同步失敗';

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

$lang->zai->zentaoSkill          = '禪道技能';
$lang->zai->zentaoSkillPromotion = '<div class="text-md text-fore">想在外部Agents中使用禪道？</div><div class="text-gray mt-2">禪道CLI已就緒。</div><div class="text-primary font-bold flex gap-1 items-center mt-2">立即上手<i class="icon icon-arrow-right"></i></div>';
$lang->zai->zentaoSkillLeading   = '通過禪道CLI';
$lang->zai->zentaoSkillTitle     = '在外部Agents工具中使用禪道';
$lang->zai->zentaoSkillSubtitle  = '支持 Claude Code、Codex、VSCode、Cursor、OpenClaw、Hermes...';
$lang->zai->zentaoSkillGuide     = <<<'MARKDOWN'
禪道全新發布ZenTao CLI工具——它不只是一個命令行工具，也是AI與研發管理數據之間的橋樑。

安裝這個技能後，你可以讓AI Agent（如Cursor、Claude Code等）直接查詢項目進度、分析Bug風險，甚至自動生成需求文檔。技能會調用ZenTao CLI讀寫禪道數據，讓大模型變身你的研發管理助手。

#### 支持的Agents工具

禪道CLI可在所有支持技能或MCP的Agent工具中使用。下表按上手難度從易到難列出常見選擇：

| 新手推薦 | 開發者推薦 | 進階/付費推薦 |
|:----------:|:----------:|:------------:|
| [Cursor](https://www.cursor.com/) | [Cline](https://cline.bot/) | [Trae](https://www.trae.ai/) |
| [VS Code Copilot](https://code.visualstudio.com/docs/copilot/overview) | [OpenClaw](https://www.openclaw.ai/) | [Codex](https://openai.com/codex/) |
| [Cherry Studio](https://www.cherry-ai.com/) | [OpenCode](https://www.opencode.ai/) | [Antigravity](https://antigravity.google/) |
| | [Claude Code](https://docs.anthropic.com/en/docs/claude-code.md) | [Codex CLI](https://developers.openai.com/codex/cli/reference) |

#### 快速開始

##### 第一步：安裝技能

**1. 讓Agent自動安裝**：現代Agent工具大都支持自動發現並安裝技能，把下面這段話發給Agent即可：

```
安裝https://cn.clawhub-mirror.com/catouse/zentao-cli技能，並安裝技能所需的zentao-cli命令行工具。
```

**2. 手動安裝**：開發者也可以直接在終端裡執行命令安裝：

```
# 全局安裝 zentao-cli 工具
$ npm install -g zentao-cli
# 其他安裝與運行方式
# bun install -g zentao-cli  # ← 使用 bun 安裝
# npx zentao-cli             # ← 通過 npx 免安裝運行
# pnpm dlx zentao-cli        # ← 通過 pnpm 免安裝運行

# 安裝完成後，一鍵把技能裝到Agent中
$ zentao add-skill
請選擇要安裝的AI Agent:
  1) Claude Code
  2) Cursor
  3) Cherry Studio
  4) Codex
  5) OpenCode
  6) VS Code
  7) Antigravity
  8) Gemini
  9) 全部安裝
請輸入編號 (1-9):9
```

##### 第二步：帳號登入與鑑權

裝好後需要先登入一次。出於帳號安全考慮，強烈建議不要把帳號密碼發給AI Agent，請改用以下本地配置方式：

1. 環境變數（推薦）：把禪道URL、用戶名和密碼寫到環境變數裡，工具會自動登入並續期Token。

```sh
export ZENTAO_URL=https://zentao.example.com
export ZENTAO_ACCOUNT=admin
export ZENTAO_PASSWORD=123456
```

2. 命令行登入：也可以用命令行手動登入：

```sh
zentao login -s https://zentao.example.com -u admin -p 123456
```

##### 第三步：對話與實戰

配置好後，你就能在對應的 Agent 工具裡像和同事聊天一樣使用禪道了。下面是幾個實戰範例：

* 需求與規劃：「我想創建一個產品，用來在線收集用戶信息，請幫我整理思路，並生成第一版需求和計劃，有問題儘管問我。」
* 進度追蹤：「上週新增了哪些需求？哪些比較難？我想針對難點提前制定方案。」
* 缺陷分析：「BUG 329 是什麼問題？可能的原因是什麼？有解決方案嗎？」
* 風險分析：「迭代10的執行情況如何？有哪些風險？」

#### 升級與維護

ZenTao CLI或技能有新版本時，可以這樣升級：

```sh
# 升級CLI本身
zentao upgrade
# 再用add-skill命令升級技能
zentao add-skill
```

也可以直接讓Agent幫你升級：

```
「請幫我升級 zentao-cli，並通過 zentao add-skill 命令重新安裝最新的技能。」
```

#### 常見問題 (FAQ)

##### Q：這個CLI技能和之前發布的ZenTao-API技能有什麼不同？該用哪個？

A：強烈推薦CLI技能。它把複雜的API細節封裝好了，支持更多功能（如數據過濾、Markdown轉換），還更省Token，大模型不用操心API調用，可以專注解決真實問題；而ZenTao API技能要大模型自己處理API，容易出錯。

##### Q：我不懂Agent、技能這些概念，怎麼上手？

A：在AI接管地球之前，先別著急。受限於Agent能力，目前還不能完全替代禪道GUI。建議先從簡單查詢開始，或者試試內置的ZenTao Tour技能，它會用有趣的方式帶你體驗。

##### Q：可以在禪道AI裡使用嗎？

A：暫時還不支持在禪道內直接用CLI，但我們正在加緊開發ZAI Agents平台，之後會在禪道裡直接支持安裝技能。

##### Q：為什麼有些操作（比如操作模塊、讀寫文檔）實現不了？

A：CLI目前依賴禪道API 2.0，部分接口還在完善中，敬請期待後續更新。

#### 相關內容

* 禪道官方技能庫：<https://github.com/easysoft/zentao-skills>
* 禪道CLI開源倉庫：<https://github.com/easysoft/zentao-cli>
MARKDOWN;
