<?php
/**
 * The project module zh-tw file of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2013 青島易軟天創網絡科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: zh-tw.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
/* 欄位列表。*/
$lang->project->common        = '項目視圖';
$lang->project->id            = '項目編號';
$lang->project->company       = '所屬公司';
$lang->project->fromproject   = '所屬項目';
$lang->project->iscat         = '作為目錄';
$lang->project->type          = '項目類型';
$lang->project->parent        = '上級項目';
$lang->project->name          = '項目名稱';
$lang->project->code          = '項目代號';
$lang->project->begin         = '開始日期';
$lang->project->end           = '結束日期';
$lang->project->dateRange     = '起始日期';
$lang->project->to            = '至';
$lang->project->days          = '可用工作日';
$lang->project->day           = '天';
$lang->project->status        = '項目狀態';
$lang->project->statge        = '所處階段';
$lang->project->pri           = '優先順序';
$lang->project->desc          = '項目描述';
$lang->project->openedBy      = '由誰創建';
$lang->project->openedDate    = '創建日期';
$lang->project->closedBy      = '由誰關閉';
$lang->project->closedDate    = '關閉日期';
$lang->project->canceledBy    = '由誰取消';
$lang->project->canceledDate  = '取消日期';
$lang->project->owner         = '負責人';
$lang->project->PO            = '產品負責人';
$lang->project->PM            = '項目負責人';
$lang->project->QD            = '測試負責人';
$lang->project->RD            = '發佈負責人';
$lang->project->acl           = '訪問控制';
$lang->project->teamname      = '團隊名稱';
$lang->project->order         = '項目排序';
$lang->project->products      = '相關產品';
$lang->project->childProjects = '子項目';
$lang->project->whitelist     = '分組白名單';
$lang->project->totalEstimate = '總預計';
$lang->project->totalConsumed = '總消耗';
$lang->project->totalLeft     = '總剩餘';
$lang->project->progess       = '進度';
$lang->project->viewBug       = '查看bug';
$lang->project->noProduct     = '無產品項目';
$lang->project->select        = '--請選擇項目--';
$lang->project->createStory   = "新增需求";
$lang->project->all           = '所有';
$lang->project->undone        = '未完成';
$lang->project->unclosed      = '未關閉';
$lang->project->typeDesc      = '運維項目禁用燃盡圖和需求。';
$lang->project->mine          = '我負責：';
$lang->project->other         = '其他：';
$lang->project->deleted       = '已刪除';

$lang->project->start    = '開始';
$lang->project->activate = '激活';
$lang->project->putoff   = '延期';
$lang->project->suspend  = '掛起';
$lang->project->close    = '結束';

$lang->project->typeList['sprint']    = '短期迭代';
$lang->project->typeList['waterfall'] = '長期項目';
$lang->project->typeList['ops']       = '運維項目';

$lang->project->endList[7]    = '一星期';
$lang->project->endList[14]   = '兩星期';
$lang->project->endList[31]   = '一個月';
$lang->project->endList[62]   = '兩個月';
$lang->project->endList[93]   = '三個月';
$lang->project->endList[186]  = '半年';
$lang->project->endList[365]  = '一年';

$lang->team = new stdclass();
$lang->team->account    = '用戶';
$lang->team->role       = '角色';
$lang->team->join       = '加盟日';
$lang->team->hours      = '可用工時/天';
$lang->team->days       = '可用工日';
$lang->team->totalHours = '總計';
 
$lang->project->basicInfo = '基本信息';
$lang->project->otherInfo = '其他信息';

/* 欄位取值列表。*/
$lang->project->statusList['wait']      = '未開始';
$lang->project->statusList['doing']     = '進行中';
$lang->project->statusList['suspended'] = '已掛起';
$lang->project->statusList['done']      = '已完成';

$lang->project->aclList['open']    = '預設設置(有項目視圖權限，即可訪問)';
$lang->project->aclList['private'] = '私有項目(只有項目團隊成員才能訪問)';
$lang->project->aclList['custom']  = '自定義白名單(團隊成員和白名單的成員可以訪問)';

/* 方法列表。*/
$lang->project->index            = "項目首頁";
$lang->project->task             = '任務列表';
$lang->project->groupTask        = '分組瀏覽任務';
$lang->project->story            = '需求列表';
$lang->project->bug              = 'Bug列表';
$lang->project->dynamic          = '動態';
$lang->project->build            = '版本列表';
$lang->project->testtask         = '測試任務';
$lang->project->burn             = '燃盡圖';
$lang->project->computeBurn      = '更新';
$lang->project->burnData         = '燃盡圖數據';
$lang->project->team             = '團隊成員';
$lang->project->doc              = '文檔列表';
$lang->project->manageProducts   = '關聯產品';
$lang->project->linkStory        = '關聯需求';
$lang->project->view             = "項目概況";
$lang->project->create           = "添加項目";
$lang->project->copy             = "複製項目";
$lang->project->delete           = "刪除項目";
$lang->project->browse           = "瀏覽項目";
$lang->project->edit             = "編輯項目";
$lang->project->batchEdit        = "批量編輯";
$lang->project->manageMembers    = '團隊管理';
$lang->project->unlinkMember     = '移除成員';
$lang->project->unlinkStory      = '移除需求';
$lang->project->batchUnlinkStory = '批量移除需求';
$lang->project->importTask       = '轉入任務';
$lang->project->importBug        = '導入Bug';
$lang->project->ajaxGetProducts  = '介面：獲得項目產品列表';

/* 分組瀏覽。*/
$lang->project->allTasks             = '所有';
$lang->project->assignedToMe         = '指派給我';

$lang->project->statusSelects['']             = '更多';
$lang->project->statusSelects['finishedbyme'] = '我完成';
$lang->project->statusSelects['wait']         = '未開始';
$lang->project->statusSelects['doing']        = '進行中';
$lang->project->statusSelects['undone']       = '未完成';
$lang->project->statusSelects['done']         = '已完成';
$lang->project->statusSelects['closed']       = '已關閉';
$lang->project->statusSelects['delayed']      = '已延期';
$lang->project->statusSelects['needconfirm']  = '需求變動';
$lang->project->statusSelects['cancel']       = '已取消';
$lang->project->groups['']           = '分組查看';
$lang->project->groups['story']      = '需求分組';
$lang->project->groups['status']     = '狀態分組';
$lang->project->groups['pri']        = '優先順序分組';
$lang->project->groups['openedby']   = '創建者分組';
$lang->project->groups['assignedTo'] = '指派給分組';
$lang->project->groups['finishedby'] = '完成者分組';
$lang->project->groups['closedby']   = '關閉者分組';
$lang->project->groups['estimate']   = '預計分組';
$lang->project->groups['consumed']   = '已消耗分組';
$lang->project->groups['left']       = '剩餘分組';
$lang->project->groups['type']       = '類型分組';
$lang->project->groups['deadline']   = '截止分組';

$lang->project->moduleTask           = '按模組';
$lang->project->byQuery              = '搜索';

/* 查詢條件列表。*/
$lang->project->allProject      = '所有項目';
$lang->project->aboveAllProduct = '以上所有產品';
$lang->project->aboveAllProject = '以上所有項目';

/* 頁面提示。*/
$lang->project->selectProject   = "請選擇項目";
$lang->project->beginAndEnd     = '起止時間';
$lang->project->lblStats        = '工時統計';
$lang->project->stats           = '可用工時<strong>%s</strong>工時<br />總共預計<strong>%s</strong>工時<br />已經消耗<strong>%s</strong>工時<br />預計剩餘<strong>%s</strong>工時';
$lang->project->oneLineStats    = "項目<strong>%s</strong>, 代號為<strong>%s</strong>, 相關產品為<strong>%s</strong>，<strong>%s</strong>開始，<strong>%s</strong>結束，總預計<strong>%s</strong>工時，已消耗<strong>%s</strong>工時，預計剩餘<strong>%s</strong>工時。";
$lang->project->taskSummary     = "本頁共 <strong>%s</strong> 個任務，未開始<strong>%s</strong>，進行中<strong>%s</strong>，總預計<strong>%s</strong>工時，已消耗<strong>%s</strong>工時，剩餘<strong>%s</strong>工時。";
$lang->project->memberHours     = "%s共有 <strong>%s</strong> 個可用工時，";
$lang->project->groupSummary    = "本組共 <strong>%s</strong> 個任務，未開始<strong>%s</strong>，進行中<strong>%s</strong>，總預計<strong>%s</strong>工時，已消耗<strong>%s</strong>工時，剩餘<strong>%s</strong>工時。";
$lang->project->wbs             = "分解任務";
$lang->project->batchWBS        = "批量分解";
$lang->project->largeBurnChart  = '點擊查看大圖';
$lang->project->howToUpdateBurn = "<a href='http://api.zentao.net/goto.php?item=burndown&lang=zh-tw' target='_blank' title='如何更新燃盡圖？'><i class='icon-question-sign'></i></a>";
$lang->project->whyNoStories    = "看起來沒有需求可以關聯。請檢查下項目關聯的產品中有沒有需求，而且要確保它們已經審核通過。";
$lang->project->doneProjects    = '已結束';
$lang->project->unDoneProjects  = '未結束';
$lang->project->copyTeam        = '複製團隊';
$lang->project->copyFromTeam    = '複製自項目團隊： <strong>%s</strong>';
$lang->project->noMatched       = '找不到包含"%s"的項目';
$lang->project->copyTitle       = '請選擇一個項目來複制';
$lang->project->copyTeamTitle   = '請選擇一個項目團隊來複制';
$lang->project->copyNoProject   = '沒有可用的項目來複制';
$lang->project->copyFromProject = '複製自項目： <strong>%s</strong>';
$lang->project->reCopy          = '重新複製';
$lang->project->cancelCopy      = '取消複製';
$lang->project->byPeriod        = '按時間段';
$lang->project->byUser          = '按用戶';

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
$lang->project->goback                = '返回項目首頁';
$lang->project->linkProduct           = '選擇產品關聯...';
$lang->project->noweekend             = '去除周末';
$lang->project->withweekend           = '顯示周末';
$lang->project->interval              = '間隔';

/* 統計。*/
$lang->project->charts = new stdclass();
$lang->project->charts->burn = new stdclass();
$lang->project->charts->burn->graph = new stdclass();
$lang->project->charts->burn->graph->caption      = "燃盡圖";
$lang->project->charts->burn->graph->xAxisName    = "日期";
$lang->project->charts->burn->graph->yAxisName    = "HOUR";
$lang->project->charts->burn->graph->baseFontSize = 12;
$lang->project->charts->burn->graph->formatNumber = 0;
$lang->project->charts->burn->graph->animation    = 0;
$lang->project->charts->burn->graph->rotateNames  = 1;
$lang->project->charts->burn->graph->showValues   = 0;

$lang->project->placeholder = new stdclass();
$lang->project->placeholder->code = '團隊內部的簡稱';

$lang->project->selectGroup = new stdclass();
$lang->project->selectGroup->doing     = '(進行中)';
$lang->project->selectGroup->suspended = '(已掛起)';
$lang->project->selectGroup->done      = '(已結束)';

$lang->project->projectTasks = '項目';
