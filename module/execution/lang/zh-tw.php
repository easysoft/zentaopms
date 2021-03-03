<?php
/**
 * The project module zh-tw file of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2015 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: zh-tw.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
/* 欄位列表。*/
$lang->project->common         = $lang->executionCommon . '視圖';
$lang->project->allProjects    = '所有' . $lang->executionCommon;
$lang->project->id             = $lang->executionCommon . '編號';
$lang->project->type           = $lang->executionCommon . '類型';
$lang->project->name           = $lang->executionCommon . '名稱';
$lang->project->code           = $lang->executionCommon . '代號';
$lang->project->statge         = '階段';
$lang->project->pri            = '優先順序';
$lang->project->openedBy       = '由誰創建';
$lang->project->openedDate     = '創建日期';
$lang->project->closedBy       = '由誰關閉';
$lang->project->closedDate     = '關閉日期';
$lang->project->canceledBy     = '由誰取消';
$lang->project->canceledDate   = '取消日期';
$lang->project->begin          = '開始日期';
$lang->project->end            = '結束日期';
$lang->project->dateRange      = '起始日期';
$lang->project->to             = '至';
$lang->project->days           = '可用工作日';
$lang->project->day            = '天';
$lang->project->workHour       = '工時';
$lang->project->workHourUnit   = 'H';
$lang->project->totalHours     = '可用工時';
$lang->project->totalDays      = '可用工日';
$lang->project->status         = $lang->executionCommon . '狀態';
$lang->project->subStatus      = '子狀態';
$lang->project->desc           = $lang->executionCommon . '描述';
$lang->project->owner          = '負責人';
$lang->project->PO             = $lang->productCommon . '負責人';
$lang->project->PM             = $lang->executionCommon . '負責人';
$lang->project->QD             = '測試負責人';
$lang->project->RD             = '發佈負責人';
$lang->project->qa             = '測試';
$lang->project->release        = '發佈';
$lang->project->acl            = '訪問控制';
$lang->project->teamname       = '團隊名稱';
$lang->project->order          = $lang->executionCommon . '排序';
$lang->project->orderAB        = '排序';
$lang->project->products       = '相關' . $lang->productCommon;
$lang->project->whitelist      = '白名單';
$lang->project->addWhitelist   = '添加白名單';
$lang->project->unbindWhielist = '刪除白名單';
$lang->project->totalEstimate  = '預計';
$lang->project->totalConsumed  = '消耗';
$lang->project->totalLeft      = '剩餘';
$lang->project->progress       = '進度';
$lang->project->hours          = '預計 %s 消耗 %s 剩餘 %s';
$lang->project->viewBug        = '查看bug';
$lang->project->noProduct      = "無{$lang->executionCommon}";
$lang->project->createStory    = "添加{$lang->SRCommon}";
$lang->project->storyTitle     = "{$lang->SRCommon}名稱";
$lang->project->all            = '所有';
$lang->project->undone         = '未完成';
$lang->project->unclosed       = '未關閉';
$lang->project->typeDesc       = "運維{$lang->executionCommon}沒有{$lang->SRCommon}、bug、版本、測試功能。";
$lang->project->mine           = '我負責：';
$lang->project->other          = '其他：';
$lang->project->deleted        = '已刪除';
$lang->project->delayed        = '已延期';
$lang->project->product        = $lang->project->products;
$lang->project->readjustTime   = "調整{$lang->executionCommon}起止時間";
$lang->project->readjustTask   = '順延任務的起止時間';
$lang->project->effort         = '日誌';
$lang->project->relatedMember  = '相關成員';
$lang->project->watermark      = '由禪道導出';
$lang->project->burnXUnit      = '(日期)';
$lang->project->burnYUnit      = '(工時)';
$lang->project->waitTasks      = '待處理';
$lang->project->viewByUser     = '按用戶查看';
$lang->project->oneProduct     = "階段只能關聯一個{$lang->productCommon}";
$lang->project->noLinkProduct  = "階段沒有關聯{$lang->productCommon}";
$lang->project->recent         = '近期访问：';

$lang->project->start    = "開始";
$lang->project->activate = "激活";
$lang->project->putoff   = "延期";
$lang->project->suspend  = "掛起";
$lang->project->close    = "關閉";
$lang->project->export   = "導出";

$lang->project->endList[7]   = '一星期';
$lang->project->endList[14]  = '兩星期';
$lang->project->endList[31]  = '一個月';
$lang->project->endList[62]  = '兩個月';
$lang->project->endList[93]  = '三個月';
$lang->project->endList[186] = '半年';
$lang->project->endList[365] = '一年';

$lang->team = new stdclass();
$lang->team->account    = '用戶';
$lang->team->role       = '角色';
$lang->team->join       = '加盟日';
$lang->team->hours      = '可用工時/天';
$lang->team->days       = '可用工日';
$lang->team->totalHours = '總計';

$lang->team->limited            = '受限用戶';
$lang->team->limitedList['yes'] = '是';
$lang->team->limitedList['no']  = '否';

$lang->project->basicInfo = '基本信息';
$lang->project->otherInfo = '其他信息';

/* 欄位取值列表。*/
$lang->project->statusList['wait']      = '未開始';
$lang->project->statusList['doing']     = '進行中';
$lang->project->statusList['suspended'] = '已掛起';
$lang->project->statusList['closed']    = '已關閉';

$lang->project->aclList['private'] = "私有（團隊成員和項目負責人、干係人可訪問）";
$lang->project->aclList['open']    = "繼承項目訪問權限（能訪問當前項目，即可訪問）";

$lang->project->storyPoint = '故事點';

$lang->project->burnByList['left']       = '按剩餘工時查看';
$lang->project->burnByList['estimate']   = "按計劃工時查看";
$lang->project->burnByList['storyPoint'] = '按故事點查看';

/* 方法列表。*/
$lang->project->index             = "{$lang->executionCommon}主頁";
$lang->project->task              = '任務列表';
$lang->project->groupTask         = '分組瀏覽任務';
$lang->project->story             = "{$lang->SRCommon}列表";
$lang->project->bug               = 'Bug列表';
$lang->project->dynamic           = '動態';
$lang->project->latestDynamic     = '最新動態';
$lang->project->build             = '所有版本';
$lang->project->testtask          = '測試單';
$lang->project->burn              = '燃盡圖';
$lang->project->computeBurn       = '更新燃盡圖';
$lang->project->burnData          = '燃盡圖數據';
$lang->project->fixFirst          = '修改首天工時';
$lang->project->team              = '團隊成員';
$lang->project->doc               = '文檔列表';
$lang->project->doclib            = '文檔庫列表';
$lang->project->manageProducts    = '關聯' . $lang->productCommon;
$lang->project->linkStory         = "關聯{$lang->SRCommon}";
$lang->project->linkStoryByPlan   = "按照計劃關聯";
$lang->project->linkPlan          = "關聯計劃";
$lang->project->unlinkStoryTasks  = "未關聯{$lang->SRCommon}任務";
$lang->project->linkedProducts    = '已關聯';
$lang->project->unlinkedProducts  = '未關聯';
$lang->project->view              = "{$lang->executionCommon}概況";
$lang->project->startAction       = "開始{$lang->executionCommon}";
$lang->project->activateAction    = "激活{$lang->executionCommon}";
$lang->project->delayAction       = "延期{$lang->executionCommon}";
$lang->project->suspendAction     = "掛起{$lang->executionCommon}";
$lang->project->closeAction       = "關閉{$lang->executionCommon}";
$lang->project->testtaskAction    = "{$lang->executionCommon}測試單";
$lang->project->teamAction        = "{$lang->executionCommon}團隊";
$lang->project->kanbanAction      = "{$lang->executionCommon}看板";
$lang->project->printKanbanAction = "打印看板";
$lang->project->treeAction        = "{$lang->executionCommon}樹狀圖";
$lang->project->exportAction      = "導出{$lang->executionCommon}";
$lang->project->computeBurnAction = "計算燃盡圖";
$lang->project->create            = "添加{$lang->executionCommon}";
$lang->project->copy              = "複製{$lang->executionCommon}";
$lang->project->delete            = "刪除{$lang->executionCommon}";
$lang->project->browse            = "瀏覽{$lang->executionCommon}";
$lang->project->edit              = "編輯{$lang->executionCommon}";
$lang->project->batchEdit         = "批量編輯";
$lang->project->manageMembers     = '團隊管理';
$lang->project->unlinkMember      = '移除成員';
$lang->project->unlinkStory       = "移除{$lang->SRCommon}";
$lang->project->unlinkStoryAB     = "移除{$lang->SRCommon}";
$lang->project->batchUnlinkStory  = "批量移除{$lang->SRCommon}";
$lang->project->importTask        = '轉入任務';
$lang->project->importPlanStories = "按計劃關聯{$lang->SRCommon}";
$lang->project->importBug         = '導入Bug';
$lang->project->updateOrder       = "{$lang->executionCommon}排序";
$lang->project->tree              = '樹狀圖';
$lang->project->treeTask          = '只看任務';
$lang->project->treeStory         = "只看{$lang->SRCommon}";
$lang->project->treeOnlyTask      = '樹狀圖只看任務';
$lang->project->treeOnlyStory     = "樹狀圖只看{$lang->SRCommon}";
$lang->project->storyKanban       = "{$lang->SRCommon}看板";
$lang->project->storySort         = "{$lang->SRCommon}排序";
$lang->project->importPlanStory   = '創建' . $lang->executionCommon . '成功！\n是否導入計劃關聯的相關' . $lang->SRCommon . '？';
$lang->project->iteration         = '版本迭代';
$lang->project->iterationInfo     = '迭代%s次';
$lang->project->viewAll           = '查看所有';

/* 分組瀏覽。*/
$lang->project->allTasks     = '所有';
$lang->project->assignedToMe = '指派給我';
$lang->project->myInvolved   = '由我參與';

$lang->project->statusSelects['']             = '更多';
$lang->project->statusSelects['wait']         = '未開始';
$lang->project->statusSelects['doing']        = '進行中';
$lang->project->statusSelects['undone']       = '未完成';
$lang->project->statusSelects['finishedbyme'] = '我完成';
$lang->project->statusSelects['done']         = '已完成';
$lang->project->statusSelects['closed']       = '已關閉';
$lang->project->statusSelects['cancel']       = '已取消';

$lang->project->groups['']           = '分組查看';
$lang->project->groups['story']      = "{$lang->SRCommon}分組";
$lang->project->groups['status']     = '狀態分組';
$lang->project->groups['pri']        = '優先順序分組';
$lang->project->groups['assignedTo'] = '指派給分組';
$lang->project->groups['finishedBy'] = '完成者分組';
$lang->project->groups['closedBy']   = '關閉者分組';
$lang->project->groups['type']       = '類型分組';

$lang->project->groupFilter['story']['all']         = '所有';
$lang->project->groupFilter['story']['linked']      = "已關聯{$lang->SRCommon}的任務";
$lang->project->groupFilter['pri']['all']           = '所有';
$lang->project->groupFilter['pri']['noset']         = '未設置';
$lang->project->groupFilter['assignedTo']['undone'] = '未完成';
$lang->project->groupFilter['assignedTo']['all']    = '所有';

$lang->project->byQuery = '搜索';

/* 查詢條件列表。*/
$lang->project->allProject      = "所有{$lang->executionCommon}";
$lang->project->aboveAllProduct = "以上所有{$lang->productCommon}";
$lang->project->aboveAllProject = "以上所有{$lang->executionCommon}";

/* 頁面提示。*/
$lang->project->linkStoryByPlanTips = "此操作會將所選計划下面的{$lang->SRCommon}全部關聯到此{$lang->executionCommon}中";
$lang->project->selectProject       = "請選擇{$lang->executionCommon}";
$lang->project->selectExecution     = "請選擇階段/迭代/衝刺";
$lang->project->beginAndEnd         = '起止時間';
$lang->project->begin               = '開始日期';
$lang->project->end                 = '截止日期';
$lang->project->lblStats            = '工時統計';
$lang->project->stats               = '可用工時 <strong>%s</strong> 工時，總共預計 <strong>%s</strong> 工時，已經消耗 <strong>%s</strong> 工時，預計剩餘 <strong>%s</strong> 工時';
$lang->project->taskSummary         = "本頁共 <strong>%s</strong> 個任務，未開始 <strong>%s</strong>，進行中 <strong>%s</strong>，總預計 <strong>%s</strong> 工時，已消耗 <strong>%s</strong> 工時，剩餘 <strong>%s</strong> 工時。";
$lang->project->pageSummary         = "本頁共 <strong>%total%</strong> 個任務，未開始 <strong>%wait%</strong>，進行中 <strong>%doing%</strong>，總預計 <strong>%estimate%</strong> 工時，已消耗 <strong>%consumed%</strong> 工時，剩餘 <strong>%left%</strong> 工時。";
$lang->project->checkedSummary      = "選中 <strong>%total%</strong> 個任務，未開始 <strong>%wait%</strong>，進行中 <strong>%doing%</strong>，總預計 <strong>%estimate%</strong> 工時，已消耗 <strong>%consumed%</strong> 工時，剩餘 <strong>%left%</strong> 工時。";
$lang->project->memberHoursAB       = "<div>%s有 <strong>%s</strong> 工時</div>";
$lang->project->memberHours         = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">%s可用工時</div><div class="segment-value">%s</div></div></div></div>';
$lang->project->countSummary        = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">總任務</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">進行中</div><div class="segment-value"><span class="label label-dot label-primary"></span> %s</div></div><div class="segment"><div class="segment-title">未開始</div><div class="segment-value"><span class="label label-dot label-primary muted"></span> %s</div></div></div></div>';
$lang->project->timeSummary         = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">總預計</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">已消耗</div><div class="segment-value text-red">%s</div></div><div class="segment"><div class="segment-title">剩餘</div><div class="segment-value">%s</div></div></div></div>';
$lang->project->groupSummaryAB      = "<div>總任務 <strong>%s : </strong><span class='text-muted'>未開始</span> %s &nbsp; <span class='text-muted'>進行中</span> %s</div><div>總預計 <strong>%s : </strong><span class='text-muted'>已消耗</span> %s &nbsp; <span class='text-muted'>剩餘</span> %s</div>";
$lang->project->wbs                 = "分解任務";
$lang->project->batchWBS            = "批量分解";
$lang->project->howToUpdateBurn     = "<a href='https://api.zentao.net/goto.php?item=burndown&lang=zh-tw' target='_blank' title='如何更新燃盡圖？' class='btn btn-link'>幫助 <i class='icon icon-help'></i></a>";
$lang->project->whyNoStories        = "看起來沒有{$lang->SRCommon}可以關聯。請檢查下{$lang->executionCommon}關聯的{$lang->productCommon}中有沒有{$lang->SRCommon}，而且要確保它們已經審核通過。";
$lang->project->productStories      = "{$lang->executionCommon}關聯的{$lang->SRCommon}是{$lang->productCommon}{$lang->SRCommon}的子集，並且只有評審通過的{$lang->SRCommon}才能關聯。請<a href='%s'>關聯{$lang->SRCommon}</a>。";
$lang->project->haveDraft           = "有%s條草稿狀態的{$lang->SRCommon}無法關聯到該{$lang->executionCommon}";
$lang->project->doneProjects        = '已結束';
$lang->project->selectDept          = '選擇部門';
$lang->project->selectDeptTitle     = '選擇一個部門的成員';
$lang->project->copyTeam            = '複製團隊';
$lang->project->copyFromTeam        = "複製自{$lang->executionCommon}團隊： <strong>%s</strong>";
$lang->project->noMatched           = "找不到包含'%s'的$lang->executionCommon";
$lang->project->copyTitle           = "請選擇一個{$lang->executionCommon}來複制";
$lang->project->copyTeamTitle       = "選擇一個{$lang->executionCommon}團隊來複制";
$lang->project->copyNoProject       = "沒有可用的{$lang->executionCommon}來複制";
$lang->project->copyFromProject     = "複製自{$lang->executionCommon} <strong>%s</strong>";
$lang->project->cancelCopy          = '取消複製';
$lang->project->byPeriod            = '按時間段';
$lang->project->byUser              = '按用戶';
$lang->project->noProject           = "暫時沒有{$lang->executionCommon}。";
$lang->project->noMembers           = '暫時沒有團隊成員。';
$lang->project->workloadTotal       = "工作量占比累計不應當超過100, 當前產品下的工作量之和為%s";
$lang->project->linkPRJStoryTip     = "(關聯{$lang->SRCommon}來源於項目下所關聯的{$lang->SRCommon})";
$lang->project->linkAllStoryTip     = "(項目下還未關聯{$lang->SRCommon}，可直接關聯該迭代/階段/衝刺所關聯產品的{$lang->SRCommon})";

/* 交互提示。*/
$lang->project->confirmDelete             = "您確定刪除{$lang->executionCommon}[%s]嗎？";
$lang->project->confirmUnlinkMember       = "您確定從該{$lang->executionCommon}中移除該用戶嗎？";
$lang->project->confirmUnlinkStory        = "您確定從該{$lang->executionCommon}中移除該{$lang->SRCommon}嗎？";
$lang->project->confirmUnlinkProjectStory = "您確定從該項目中移除該{$lang->SRCommon}嗎？";
$lang->project->notAllowedUnlinkStory     = "該{$lang->SRCommon}已經與項目下{$lang->executionCommon}相關聯，請從{$lang->executionCommon}中移除後再操作。";
$lang->project->notAllowRemoveProducts    = "該產品中的需求已與該{$lang->executionCommon}進行了關聯，請取消關聯後再操作。";
$lang->project->errorNoLinkedProducts     = "該{$lang->executionCommon}沒有關聯的{$lang->productCommon}，系統將轉到{$lang->productCommon}關聯頁面";
$lang->project->errorSameProducts         = "{$lang->executionCommon}不能關聯多個相同的{$lang->productCommon}。";
$lang->project->accessDenied              = "您無權訪問該{$lang->executionCommon}！";
$lang->project->tips                      = '提示';
$lang->project->afterInfo                 = "{$lang->executionCommon}添加成功，您現在可以進行以下操作：";
$lang->project->setTeam                   = '設置團隊';
$lang->project->linkStory                 = "關聯{$lang->SRCommon}";
$lang->project->createTask                = '創建任務';
$lang->project->goback                    = "返回任務列表";
$lang->project->noweekend                 = '去除周末';
$lang->project->withweekend               = '顯示周末';
$lang->project->interval                  = '間隔';
$lang->project->fixFirstWithLeft          = '修改剩餘工時';
$lang->project->unfinishedProject         = "該{$lang->executionCommon}下還有";
$lang->project->unfinishedTask            = "[%s]個未完成的任務，";
$lang->project->unresolvedBug             = "[%s]個未解決的bug，";

$lang->project->action = new stdclass();
$lang->project->action->opened  = '$date, 由 <strong>$actor</strong> 創建。$extra' . "\n";
$lang->project->action->managed = '$date, 由 <strong>$actor</strong> 維護。$extra' . "\n";
$lang->project->action->edited  = '$date, 由 <strong>$actor</strong> 編輯。$extra' . "\n";
$lang->project->action->extra   = '相關產品為 %s。';

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
$lang->project->charts->burn->graph->reference    = '參考';
$lang->project->charts->burn->graph->actuality    = '實際';

$lang->project->placeholder = new stdclass();
$lang->project->placeholder->code      = '團隊內部的簡稱';
$lang->project->placeholder->totalLeft = "{$lang->executionCommon}開始時的總預計工時";

$lang->project->selectGroup = new stdclass();
$lang->project->selectGroup->done = '(已結束)';

$lang->project->orderList['order_asc']  = "{$lang->SRCommon}排序正序";
$lang->project->orderList['order_desc'] = "{$lang->SRCommon}排序倒序";
$lang->project->orderList['pri_asc']    = "{$lang->SRCommon}優先順序正序";
$lang->project->orderList['pri_desc']   = "{$lang->SRCommon}優先順序倒序";
$lang->project->orderList['stage_asc']  = "{$lang->SRCommon}階段正序";
$lang->project->orderList['stage_desc'] = "{$lang->SRCommon}階段倒序";

$lang->project->kanban        = "看板";
$lang->project->kanbanSetting = "看板設置";
$lang->project->resetKanban   = "恢復預設";
$lang->project->printKanban   = "打印看板";
$lang->project->bugList       = "Bug列表";

$lang->project->kanbanHideCols   = '看板隱藏已關閉、已取消列';
$lang->project->kanbanShowOption = '顯示摺疊信息';
$lang->project->kanbanColsColor  = '看板列自定義顏色';

$lang->kanbanSetting = new stdclass();
$lang->kanbanSetting->noticeReset     = '是否恢復看板預設設置？';
$lang->kanbanSetting->optionList['0'] = '隱藏';
$lang->kanbanSetting->optionList['1'] = '顯示';

$lang->printKanban = new stdclass();
$lang->printKanban->common  = '看板打印';
$lang->printKanban->content = '內容';
$lang->printKanban->print   = '打印';

$lang->printKanban->taskStatus = '狀態';

$lang->printKanban->typeList['all']       = '全部';
$lang->printKanban->typeList['increment'] = '增量';

$lang->project->typeList['']       = '';
$lang->project->typeList['stage']  = '階段';
$lang->project->typeList['sprint'] = $lang->executionCommon;

$lang->project->featureBar['task']['all']          = $lang->project->allTasks;
$lang->project->featureBar['task']['unclosed']     = $lang->project->unclosed;
$lang->project->featureBar['task']['assignedtome'] = $lang->project->assignedToMe;
$lang->project->featureBar['task']['myinvolved']   = $lang->project->myInvolved;
$lang->project->featureBar['task']['delayed']      = '已延期';
$lang->project->featureBar['task']['needconfirm']  = "{$lang->SRCommon}變更";
$lang->project->featureBar['task']['status']       = $lang->project->statusSelects[''];

$lang->project->featureBar['all']['all']       = $lang->project->all;
$lang->project->featureBar['all']['undone']    = $lang->project->undone;
$lang->project->featureBar['all']['wait']      = $lang->project->statusList['wait'];
$lang->project->featureBar['all']['doing']     = $lang->project->statusList['doing'];
$lang->project->featureBar['all']['suspended'] = $lang->project->statusList['suspended'];
$lang->project->featureBar['all']['closed']    = $lang->project->statusList['closed'];

$lang->project->treeLevel = array();
$lang->project->treeLevel['all']   = '全部展開';
$lang->project->treeLevel['root']  = '全部摺疊';
$lang->project->treeLevel['task']  = '全部顯示';
$lang->project->treeLevel['story'] = "只看{$lang->SRCommon}";

$lang->execution->action = new stdclass();
$lang->execution->action->opened  = '$date, 由 <strong>$actor</strong> 創建。$extra' . "\n";
$lang->execution->action->managed = '$date, 由 <strong>$actor</strong> 維護。$extra' . "\n";
$lang->execution->action->edited  = '$date, 由 <strong>$actor</strong> 編輯。$extra' . "\n";
$lang->execution->action->extra   = '相關產品為 %s。';

