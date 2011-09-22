<?php
/**
 * The project module zh-tw file of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2011 青島易軟天創網絡科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: zh-tw.php 2089 2011-09-18 07:48:31Z wwccss $
 * @link        http://www.zentao.net
 */
/* 欄位列表。*/
$lang->project->common       = '項目視圖';
$lang->project->id           = '項目編號';
$lang->project->company      = '所屬公司';
$lang->project->iscat        = '作為目錄';
$lang->project->type         = '項目類型';
$lang->project->parent       = '上級項目';
$lang->project->name         = '項目名稱';
$lang->project->code         = '項目代號';
$lang->project->begin        = '開始日期';
$lang->project->end          = '結束日期';
$lang->project->status       = '項目狀態';
$lang->project->statge       = '所處階段';
$lang->project->pri          = '優先順序';
$lang->project->desc         = '項目描述';
$lang->project->goal         = '項目目標';
$lang->project->openedBy     = '由誰創建';
$lang->project->openedDate   = '創建日期';
$lang->project->closedBy     = '由誰關閉';
$lang->project->closedDate   = '關閉日期';
$lang->project->canceledBy   = '由誰取消';
$lang->project->canceledDate = '取消日期';
$lang->project->PO           = '產品負責人';
$lang->project->PM           = '項目負責人';
$lang->project->QM           = '測試負責人';
$lang->project->RM           = '發佈負責人';
$lang->project->acl          = '訪問控制';
$lang->project->teamname     = '團隊名稱';
$lang->project->products     = '相關產品';
$lang->project->childProjects= '子項目';
$lang->project->whitelist    = '分組白名單';
$lang->project->totalEstimate= '總預計';
$lang->project->totalConsumed= '總消耗';
$lang->project->totalLeft    = '總剩餘';
$lang->project->progess      = '進度';
$lang->project->noProduct    = '無產品項目';

$lang->team->account     = '用戶';
$lang->team->role        = '角色';
$lang->team->joinDate    = '加盟日';
$lang->team->workingHour = '工時/天';

/* 欄位取值列表。*/
$lang->project->statusList['']      = '';
$lang->project->statusList['wait']  = '未開始';
$lang->project->statusList['doing'] = '進行中';
$lang->project->statusList['done']  = '已完成';

$lang->project->aclList['open']    = '預設設置(有項目視圖權限，即可訪問)';
$lang->project->aclList['private'] = '私有項目(只有項目團隊成員才能訪問)';
$lang->project->aclList['custom']  = '自定義白名單(團隊成員和白名單的成員可以訪問)';

/* 方法列表。*/
$lang->project->index          = "項目首頁";
$lang->project->task           = '任務列表';
$lang->project->groupTask      = '分組瀏覽任務';
$lang->project->story          = '需求列表';
$lang->project->bug            = 'Bug列表';
$lang->project->build          = 'Build列表';
$lang->project->burn           = '燃盡圖';
$lang->project->computeBurn    = '更新燃盡圖';
$lang->project->burnData       = '燃盡圖數據';
$lang->project->team           = '團隊成員';
$lang->project->doc            = '文檔列表';
$lang->project->manageProducts = '關聯產品';
$lang->project->linkStory      = '關聯需求';
$lang->project->view           = "基本信息";
$lang->project->create         = "添加項目";
$lang->project->delete         = "刪除項目";
$lang->project->browse         = "瀏覽項目";
$lang->project->edit           = "編輯項目";
$lang->project->manageMembers  = '團隊管理';
$lang->project->unlinkMember   = '移除成員';
$lang->project->unlinkStory    = '移除需求';
$lang->project->importTask     = '導入之前未完';
$lang->project->ajaxGetProducts= '介面：獲得項目產品列表';

/* 分組瀏覽。*/
$lang->project->allTasks            = '所有任務';
$lang->project->assignedToMe        = '指派給我';
$lang->project->finishedByMe        = '由我完成';
$lang->project->statusWait          = '未開始';
$lang->project->statusDoing         = '進行中';
$lang->project->statusDone          = '已完成';
$lang->project->statusClosed        = '已關閉';
$lang->project->delayed             = '已延期';
$lang->project->groups['']          = '分組查看';
$lang->project->groups['story']     = '需求分組';
$lang->project->groups['status']    = '狀態分組';
$lang->project->groups['pri']       = '優先順序分組';
$lang->project->groups['openedby']  = '創建者分組';
$lang->project->groups['assignedTo']= '指派給分組';
$lang->project->groups['finishedby']= '完成者分組';
$lang->project->groups['closedby']  = '關閉者分組';
$lang->project->groups['estimate']  = '預計分組';
$lang->project->groups['consumed']  = '已消耗分組';
$lang->project->groups['left']      = '剩餘分組';
$lang->project->groups['type']      = '類型分組';
$lang->project->groups['deadline']  = '截止分組';
$lang->project->listTaskNeedConfrim = '需求變動';
$lang->project->byQuery             = '搜索';

/* 查詢條件列表。*/
$lang->project->allProject          = '所有項目';

/* 頁面提示。*/
$lang->project->selectProject  = "請選擇項目";
$lang->project->beginAndEnd    = '起止時間';
$lang->project->lblStats       = '工時統計';
$lang->project->stats          = '總共預計<strong>%s</strong>工時<br />已經消耗<strong>%s</strong>工時<br />預計剩餘<strong>%s</strong>工時';
$lang->project->oneLineStats   = "項目<strong>%s</strong>, 代號為<strong>%s</strong>, 相關產品為<strong>%s</strong>，<strong>%s</strong>開始，<strong>%s</strong>結束，總預計<strong>%s</strong>工時，已消耗<strong>%s</strong>工時，預計剩餘<strong>%s</strong>工時。";
$lang->project->taskSummary    = "本頁共 <strong>%s</strong> 個任務，未開始<strong>%s</strong>，進行中<strong>%s</strong>，總預計<strong>%s</strong>工時，已消耗<strong>%s</strong>工時，剩餘<strong>%s</strong>工時。";
$lang->project->groupSummary   = "本組共 <strong>%s</strong> 個任務，未開始<strong>%s</strong>，進行中<strong>%s</strong>，總預計<strong>%s</strong>工時，已消耗<strong>%s</strong>工時，剩餘<strong>%s</strong>工時。";
$lang->project->wbs            = "分解任務";
$lang->project->largeBurnChart = '點擊查看大圖';
$lang->project->howToUpdateBurn= "<a href='%s' class='helplink'><i>如何更新?</i></a>";
$lang->project->whyNoStories   = "看起來沒有需求可以關聯。請檢查下項目關聯的產品中有沒有需求，而且要確保它們已經審核通過。";

/* 交互提示。*/
$lang->project->confirmDelete         = '您確定刪除項目[%s]嗎？';
$lang->project->confirmUnlinkMember   = '您確定從該項目中移除該用戶嗎？';
$lang->project->confirmUnlinkStory    = '您確定從該項目中移除該需求嗎？';
$lang->project->errorNoLinkedProducts = '該項目沒有關聯的產品，系統將轉到產品關聯頁面';
$lang->project->accessDenied          = '您無權訪問該項目！';
$lang->project->tips                  = '提示';
$lang->project->afterInfo             = '項目添加成功，您現在可以進行以下操作：';
$lang->project->setTeam               = '設置團隊';
$lang->project->linkStory             = '關聯需求';
$lang->project->createTask            = '添加任務';
$lang->project->goback                = '返回項目首頁（5秒後將自動跳轉）';

/* 統計。*/
$lang->project->charts->burn->graph->caption      = "燃盡圖";
$lang->project->charts->burn->graph->xAxisName    = "日期";
$lang->project->charts->burn->graph->yAxisName    = "HOUR";
$lang->project->charts->burn->graph->baseFontSize = 12;
$lang->project->charts->burn->graph->formatNumber = 0;
$lang->project->charts->burn->graph->animation    = 0;
$lang->project->charts->burn->graph->rotateNames  = 1;
$lang->project->charts->burn->graph->showValues   = 0;
