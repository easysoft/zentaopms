<?php
/**
 * The execution module zh-tw file of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2023 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: zh-tw.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
/* 欄位列表。*/
$lang->execution->allExecutions    = '所有' . $lang->execution->common;
$lang->execution->allExecutionAB   = "{$lang->execution->common}列表";
$lang->execution->id               = $lang->executionCommon . '編號';
$lang->execution->type             = $lang->executionCommon . '類型';
$lang->execution->name             = $lang->executionCommon . '名稱';
$lang->execution->code             = $lang->executionCommon . '代號';
$lang->execution->projectName      = '所屬項目';
$lang->execution->execName         = "{$lang->execution->common}名稱";
$lang->execution->execCode         = "{$lang->execution->common}代號";
$lang->execution->execType         = "{$lang->execution->common}類型";
$lang->execution->stage            = '階段';
$lang->execution->pri              = '優先順序';
$lang->execution->openedBy         = '由誰創建';
$lang->execution->openedDate       = '創建日期';
$lang->execution->closedBy         = '由誰關閉';
$lang->execution->closedDate       = '關閉日期';
$lang->execution->canceledBy       = '由誰取消';
$lang->execution->canceledDate     = '取消日期';
$lang->execution->begin            = '計劃開始';
$lang->execution->end              = '計劃完成';
$lang->execution->dateRange        = '起始日期';
$lang->execution->realBeganAB      = '實際開始';
$lang->execution->realEndAB        = '實際完成';
$lang->execution->realBegan        = '實際開始日期';
$lang->execution->realEnd          = '實際完成日期';
$lang->execution->to               = '至';
$lang->execution->days             = '可用工作日';
$lang->execution->day              = '天';
$lang->execution->workHour         = '工時';
$lang->execution->workHourUnit     = 'h';
$lang->execution->totalHours       = '可用工時';
$lang->execution->totalDays        = '可用工日';
$lang->execution->status           = $lang->executionCommon . '狀態';
$lang->execution->execStatus       = "{$lang->execution->common}狀態";
$lang->execution->subStatus        = '子狀態';
$lang->execution->desc             = $lang->executionCommon . '描述';
$lang->execution->execDesc         = "{$lang->execution->common}描述";
$lang->execution->owner            = '負責人';
$lang->execution->PO               = $lang->productCommon . '負責人';
$lang->execution->PM               = $lang->executionCommon . '負責人';
$lang->execution->execPM           = "{$lang->execution->common}負責人";
$lang->execution->QD               = '測試負責人';
$lang->execution->RD               = '發佈負責人';
$lang->execution->release          = '發佈';
$lang->execution->teamCount        = '人數';
$lang->execution->acl              = '訪問控制';
$lang->execution->teamName         = '團隊名稱';
$lang->execution->updateOrder      = '排序';
$lang->execution->order            = $lang->executionCommon . '排序';
$lang->execution->orderAB          = '排序';
$lang->execution->products         = '相關' . $lang->productCommon;
$lang->execution->whitelist        = '白名單';
$lang->execution->addWhitelist     = '添加白名單';
$lang->execution->unbindWhitelist  = '刪除白名單';
$lang->execution->totalEstimate    = '預計';
$lang->execution->totalConsumed    = '消耗';
$lang->execution->totalLeft        = '剩餘';
$lang->execution->progress         = '進度';
$lang->execution->hours            = '預計 %s 消耗 %s 剩餘 %s';
$lang->execution->viewBug          = '查看bug';
$lang->execution->noProduct        = "無{$lang->executionCommon}";
$lang->execution->createStory      = "提{$lang->SRCommon}";
$lang->execution->storyTitle       = "{$lang->SRCommon}名稱";
$lang->execution->all              = '所有';
$lang->execution->undone           = '未完成';
$lang->execution->unclosed         = '未關閉';
$lang->execution->typeDesc         = "運維{$lang->executionCommon}沒有{$lang->SRCommon}、bug、版本、測試功能。";
$lang->execution->mine             = '我負責：';
$lang->execution->involved         = '我參與';
$lang->execution->other            = '其他';
$lang->execution->deleted          = '已刪除';
$lang->execution->delayed          = '已延期';
$lang->execution->product          = $lang->execution->products;
$lang->execution->readjustTime     = "調整{$lang->executionCommon}起止時間";
$lang->execution->readjustTask     = '順延任務的起止時間';
$lang->execution->effort           = '日誌';
$lang->execution->storyEstimate    = '需求估算';
$lang->execution->newEstimate      = '新一輪估算';
$lang->execution->reestimate       = '重新估算';
$lang->execution->selectRound      = '選擇輪次';
$lang->execution->average          = '平均值';
$lang->execution->relatedMember    = '相關成員';
$lang->execution->watermark        = '由禪道導出';
$lang->execution->burnXUnit        = '(日期)';
$lang->execution->burnYUnit        = '(工時)';
$lang->execution->waitTasks        = '待處理';
$lang->execution->viewByUser       = '按用戶查看';
$lang->execution->oneProduct       = "階段只能關聯一個{$lang->productCommon}";
$lang->execution->noLinkProduct    = "階段沒有關聯{$lang->productCommon}";
$lang->execution->recent           = '近期訪問：';
$lang->execution->copyNoExecution  = '沒有可用的' . $lang->executionCommon . '來複制';
$lang->execution->noTeam           = '暫時沒有團隊成員';
$lang->execution->or               = '或';
$lang->execution->selectProject    = '請選擇項目';
$lang->execution->unfoldClosed     = '展開已結束';
$lang->execution->editName         = '編輯名稱';
$lang->execution->setWIP           = '在製品數量設置（WIP）';
$lang->execution->sortColumn       = '看板列卡片排序';
$lang->execution->batchCreateStory = "批量新建{$lang->SRCommon}";
$lang->execution->batchCreateTask  = '批量建任務';

/* Fields of zt_team. */
$lang->execution->root        = '源ID';
$lang->execution->estimate    = '預計';
$lang->execution->consumed    = '消耗';
$lang->execution->left        = '剩餘';
$lang->execution->copyTeamTip = "可以選擇複製項目或{$lang->execution->common}團隊的成員";

$lang->execution->start    = "開始";
$lang->execution->activate = "激活";
$lang->execution->putoff   = "延期";
$lang->execution->suspend  = "掛起";
$lang->execution->close    = "關閉";
$lang->execution->export   = "導出";

$lang->execution->endList[7]   = '一星期';
$lang->execution->endList[14]  = '兩星期';
$lang->execution->endList[31]  = '一個月';
$lang->execution->endList[62]  = '兩個月';
$lang->execution->endList[93]  = '三個月';
$lang->execution->endList[186] = '半年';
$lang->execution->endList[365] = '一年';

$lang->execution->lifeTimeList['short'] = "短期";
$lang->execution->lifeTimeList['long']  = "長期";
$lang->execution->lifeTimeList['ops']   = "運維";

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

$lang->execution->basicInfo = '基本信息';
$lang->execution->otherInfo = '其他信息';

/* 欄位取值列表。*/
$lang->execution->statusList['wait']      = '未開始';
$lang->execution->statusList['doing']     = '進行中';
$lang->execution->statusList['suspended'] = '已掛起';
$lang->execution->statusList['closed']    = '已關閉';

global $config;
$lang->execution->aclList['private'] = "私有（團隊成員和項目負責人、干係人可訪問）";
$lang->execution->aclList['open']    = "繼承項目訪問權限（能訪問當前項目，即可訪問）";

$lang->execution->storyPoint = '故事點';

$lang->execution->burnByList['left']       = '按剩餘工時查看';
$lang->execution->burnByList['estimate']   = "按計劃工時查看";
$lang->execution->burnByList['storyPoint'] = '按故事點查看';

/* 方法列表。*/
$lang->execution->index             = "{$lang->execution->common}主頁";
$lang->execution->task              = '任務列表';
$lang->execution->groupTask         = '分組瀏覽任務';
$lang->execution->story             = "{$lang->SRCommon}列表";
$lang->execution->qa                = '測試儀表盤';
$lang->execution->bug               = 'Bug列表';
$lang->execution->testcase          = '用例列表';
$lang->execution->dynamic           = '動態';
$lang->execution->latestDynamic     = '最新動態';
$lang->execution->build             = '所有版本';
$lang->execution->testtask          = '測試單';
$lang->execution->burn              = '燃盡圖';
$lang->execution->computeBurn       = '更新燃盡圖';
$lang->execution->burnData          = '燃盡圖數據';
$lang->execution->fixFirst          = '修改首天工時';
$lang->execution->team              = '團隊成員';
$lang->execution->doc               = '文檔列表';
$lang->execution->doclib            = '文檔庫列表';
$lang->execution->manageProducts    = '關聯' . $lang->productCommon;
$lang->execution->linkStory         = "關聯{$lang->SRCommon}";
$lang->execution->linkStoryByPlan   = "按照計劃關聯";
$lang->execution->linkPlan          = "關聯計劃";
$lang->execution->unlinkStoryTasks  = "未關聯{$lang->SRCommon}任務";
$lang->execution->linkedProducts    = '已關聯';
$lang->execution->unlinkedProducts  = '未關聯';
$lang->execution->view              = "{$lang->execution->common}概況";
$lang->execution->startAction       = "開始{$lang->execution->common}";
$lang->execution->activateAction    = "激活{$lang->execution->common}";
$lang->execution->delayAction       = "延期{$lang->execution->common}";
$lang->execution->suspendAction     = "掛起{$lang->execution->common}";
$lang->execution->closeAction       = "關閉{$lang->execution->common}";
$lang->execution->testtaskAction    = "{$lang->execution->common}測試單";
$lang->execution->teamAction        = "{$lang->execution->common}團隊";
$lang->execution->kanbanAction      = "{$lang->execution->common}看板";
$lang->execution->printKanbanAction = "打印看板";
$lang->execution->treeAction        = "{$lang->execution->common}樹狀圖";
$lang->execution->exportAction      = "導出{$lang->execution->common}";
$lang->execution->computeBurnAction = "計算燃盡圖";
$lang->execution->create            = "添加{$lang->executionCommon}";
$lang->execution->createExec        = "添加{$lang->execution->common}";
$lang->execution->copyExec          = "複製{$lang->execution->common}";
$lang->execution->copy              = "複製{$lang->executionCommon}";
$lang->execution->delete            = "刪除{$lang->executionCommon}";
$lang->execution->deleteAB          = "刪除{$lang->execution->common}";
$lang->execution->browse            = "瀏覽{$lang->execution->common}";
$lang->execution->edit              = "編輯{$lang->executionCommon}";
$lang->execution->editAction        = "編輯{$lang->execution->common}";
$lang->execution->batchEdit         = "編輯";
$lang->execution->batchEditAction   = "批量編輯";
$lang->execution->manageMembers     = '團隊管理';
$lang->execution->unlinkMember      = '移除成員';
$lang->execution->unlinkStory       = "移除{$lang->SRCommon}";
$lang->execution->unlinkStoryAB     = "移除{$lang->SRCommon}";
$lang->execution->batchUnlinkStory  = "批量移除{$lang->SRCommon}";
$lang->execution->importTask        = '轉入任務';
$lang->execution->importPlanStories = "按計劃關聯{$lang->SRCommon}";
$lang->execution->importBug         = '導入Bug';
$lang->execution->tree              = '樹狀圖';
$lang->execution->treeTask          = '只看任務';
$lang->execution->treeStory         = "只看{$lang->SRCommon}";
$lang->execution->treeViewTask      = '樹狀圖查看任務';
$lang->execution->treeViewStory     = "樹狀圖查看{$lang->SRCommon}";
$lang->execution->storyKanban       = "{$lang->SRCommon}看板";
$lang->execution->storySort         = "{$lang->SRCommon}排序";
$lang->execution->importPlanStory   = '創建' . $lang->executionCommon . '成功！\n是否導入計劃關聯的相關' . $lang->SRCommon . '？';
$lang->execution->iteration         = '版本迭代';
$lang->execution->iterationInfo     = '迭代%s次';
$lang->execution->viewAll           = '查看所有';
$lang->execution->testreport        = '測試報告';

/* 分組瀏覽。*/
$lang->execution->allTasks     = '所有';
$lang->execution->assignedToMe = '指派給我';
$lang->execution->myInvolved   = '由我參與';

$lang->execution->statusSelects['']             = '更多';
$lang->execution->statusSelects['wait']         = '未開始';
$lang->execution->statusSelects['doing']        = '進行中';
$lang->execution->statusSelects['undone']       = '未完成';
$lang->execution->statusSelects['finishedbyme'] = '我完成';
$lang->execution->statusSelects['done']         = '已完成';
$lang->execution->statusSelects['closed']       = '已關閉';
$lang->execution->statusSelects['cancel']       = '已取消';

$lang->execution->groups['']           = '分組查看';
$lang->execution->groups['story']      = "{$lang->SRCommon}分組";
$lang->execution->groups['status']     = '狀態分組';
$lang->execution->groups['pri']        = '優先順序分組';
$lang->execution->groups['assignedTo'] = '指派給分組';
$lang->execution->groups['finishedBy'] = '完成者分組';
$lang->execution->groups['closedBy']   = '關閉者分組';
$lang->execution->groups['type']       = '類型分組';

$lang->execution->groupFilter['story']['all']         = '所有';
$lang->execution->groupFilter['story']['linked']      = "已關聯{$lang->SRCommon}的任務";
$lang->execution->groupFilter['pri']['all']           = '所有';
$lang->execution->groupFilter['pri']['noset']         = '未設置';
$lang->execution->groupFilter['assignedTo']['undone'] = '未完成';
$lang->execution->groupFilter['assignedTo']['all']    = '所有';

$lang->execution->byQuery = '搜索';

/* 查詢條件列表。*/
$lang->execution->allExecution      = "所有{$lang->executionCommon}";
$lang->execution->aboveAllProduct   = "以上所有{$lang->productCommon}";
$lang->execution->aboveAllExecution = "以上所有{$lang->executionCommon}";

/* 頁面提示。*/
$lang->execution->linkStoryByPlanTips = "此操作會將所選計划下面的{$lang->SRCommon}全部關聯到此{$lang->executionCommon}中";
$lang->execution->selectExecution     = "請選擇{$lang->execution->common}";
$lang->execution->beginAndEnd         = '起止時間';
$lang->execution->lblStats            = '工時統計';
$lang->execution->stats               = '可用工時 <strong>%s</strong> 工時，總共預計 <strong>%s</strong> 工時，已經消耗 <strong>%s</strong> 工時，預計剩餘 <strong>%s</strong> 工時';
$lang->execution->taskSummary         = "本頁共 <strong>%s</strong> 個任務，未開始 <strong>%s</strong>，進行中 <strong>%s</strong>，總預計 <strong>%s</strong> 工時，已消耗 <strong>%s</strong> 工時，剩餘 <strong>%s</strong> 工時。";
$lang->execution->pageSummary         = "本頁共 <strong>%total%</strong> 個任務，未開始 <strong>%wait%</strong>，進行中 <strong>%doing%</strong>，總預計 <strong>%estimate%</strong> 工時，已消耗 <strong>%consumed%</strong> 工時，剩餘 <strong>%left%</strong> 工時。";
$lang->execution->checkedSummary      = "選中 <strong>%total%</strong> 個任務，未開始 <strong>%wait%</strong>，進行中 <strong>%doing%</strong>，總預計 <strong>%estimate%</strong> 工時，已消耗 <strong>%consumed%</strong> 工時，剩餘 <strong>%left%</strong> 工時。";
$lang->execution->memberHoursAB       = "<div>%s有 <strong>%s</strong> 工時</div>";
$lang->execution->memberHours         = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">%s可用工時</div><div class="segment-value">%s</div></div></div></div>';
$lang->execution->countSummary        = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">總任務</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">進行中</div><div class="segment-value"><span class="label label-dot label-primary"></span> %s</div></div><div class="segment"><div class="segment-title">未開始</div><div class="segment-value"><span class="label label-dot label-primary muted"></span> %s</div></div></div></div>';
$lang->execution->timeSummary         = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">總預計</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">已消耗</div><div class="segment-value text-red">%s</div></div><div class="segment"><div class="segment-title">剩餘</div><div class="segment-value">%s</div></div></div></div>';
$lang->execution->groupSummaryAB      = "<div>總任務 <strong>%s : </strong><span class='text-muted'>未開始</span> %s &nbsp; <span class='text-muted'>進行中</span> %s</div><div>總預計 <strong>%s : </strong><span class='text-muted'>已消耗</span> %s &nbsp; <span class='text-muted'>剩餘</span> %s</div>";
$lang->execution->wbs                 = "分解任務";
$lang->execution->batchWBS            = "批量分解";
$lang->execution->howToUpdateBurn     = "<a href='https://api.zentao.net/goto.php?item=burndown&lang=zh-tw' target='_blank' title='如何更新燃盡圖？' class='btn btn-link'>幫助 <i class='icon icon-help'></i></a>";
$lang->execution->whyNoStories        = "看起來沒有{$lang->SRCommon}可以關聯。請檢查下{$lang->executionCommon}關聯的{$lang->productCommon}中有沒有{$lang->SRCommon}，而且要確保它們已經審核通過。";
$lang->execution->productStories      = "{$lang->executionCommon}關聯的{$lang->SRCommon}是{$lang->productCommon}{$lang->SRCommon}的子集，並且只有評審通過的{$lang->SRCommon}才能關聯。請<a href='%s'>關聯{$lang->SRCommon}</a>。";
$lang->execution->haveDraft           = "有%s條草稿狀態的{$lang->SRCommon}無法關聯到該{$lang->executionCommon}";
$lang->execution->doneExecutions      = '已結束';
$lang->execution->selectDept          = '選擇部門';
$lang->execution->selectDeptTitle     = '選擇一個部門的成員';
$lang->execution->copyTeam            = '複製團隊';
$lang->execution->copyFromTeam        = "複製自{$lang->execution->common}團隊： <strong>%s</strong>";
$lang->execution->noMatched           = "找不到包含'%s'的{$lang->execution->common}";
$lang->execution->copyTitle           = "請選擇一個{$lang->execution->common}來複制";
$lang->execution->copyNoExecution     = "沒有可用的{$lang->execution->common}來複制";
$lang->execution->copyFromExecution   = "複製自{$lang->execution->common} <strong>%s</strong>";
$lang->execution->cancelCopy          = '取消複製';
$lang->execution->byPeriod            = '按時間段';
$lang->execution->byUser              = '按用戶';
$lang->execution->noExecution         = "暫時沒有{$lang->executionCommon}。";
$lang->execution->noExecutions        = "暫時沒有{$lang->execution->common}。";
$lang->execution->noPrintData         = "暫無數據可打印";
$lang->execution->noMembers           = '暫時沒有團隊成員。';
$lang->execution->workloadTotal       = "工作量占比累計不應當超過100, 當前產品下的工作量之和為%s";
$lang->execution->linkAllStoryTip     = "(項目下還未關聯{$lang->SRCommon}，可直接關聯該{$lang->execution->common}所關聯產品的{$lang->SRCommon})";
$lang->execution->copyTeamTitle       = "選擇一個{$lang->project->common}或{$lang->execution->common}團隊來複制";

/* 交互提示。*/
$lang->execution->confirmDelete               = "您確定刪除{$lang->executionCommon}[%s]嗎？";
$lang->execution->confirmUnlinkMember         = "您確定從該{$lang->executionCommon}中移除該用戶嗎？";
$lang->execution->confirmUnlinkStory          = "移除該{$lang->SRCommon}後，該{$lang->SRCommon}關聯的用例將被移除，該{$lang->SRCommon}關聯的任務將被取消，請確認。";
$lang->execution->confirmSyncStories          = "修改所屬項目後，執行關聯的{$lang->SRCommon}是否同步到新項目中？";
$lang->execution->confirmUnlinkExecutionStory = "您確定從該項目中移除該{$lang->SRCommon}嗎？";
$lang->execution->notAllowedUnlinkStory       = "該{$lang->SRCommon}已經與項目下{$lang->executionCommon}相關聯，請從{$lang->executionCommon}中移除後再操作。";
$lang->execution->notAllowRemoveProducts      = "該{$lang->productCommon}中的{$lang->SRCommon}已與該{$lang->executionCommon}進行了關聯，請取消關聯後再操作。";
$lang->execution->errorNoLinkedProducts       = "該{$lang->executionCommon}沒有關聯的{$lang->productCommon}，系統將轉到{$lang->productCommon}關聯頁面";
$lang->execution->errorSameProducts           = "{$lang->executionCommon}不能關聯多個相同的{$lang->productCommon}。";
$lang->execution->errorSameBranches           = "{$lang->executionCommon}不能關聯多個相同的分支。";
$lang->execution->errorBegin                  = "{$lang->executionCommon}的開始時間不能小於所屬項目的開始時間%s。";
$lang->execution->errorEnd                    = "{$lang->executionCommon}的截止時間不能大於所屬項目的結束時間%s。";
$lang->execution->accessDenied                = "您無權訪問該{$lang->executionCommon}！";
$lang->execution->tips                        = '提示';
$lang->execution->afterInfo                   = "{$lang->executionCommon}添加成功，您現在可以進行以下操作：";
$lang->execution->setTeam                     = '設置團隊';
$lang->execution->linkStory                   = "關聯{$lang->SRCommon}";
$lang->execution->createTask                  = '創建任務';
$lang->execution->goback                      = "返回任務列表";
$lang->execution->noweekend                   = '去除周末';
$lang->execution->withweekend                 = '顯示周末';
$lang->execution->interval                    = '間隔';
$lang->execution->fixFirstWithLeft            = '修改剩餘工時';
$lang->execution->unfinishedExecution         = "該{$lang->executionCommon}下還有";
$lang->execution->unfinishedTask              = "[%s]個未完成的任務，";
$lang->execution->unresolvedBug               = "[%s]個未解決的bug，";
$lang->execution->projectNotEmpty             = '所屬項目不能為空。';
$lang->execution->confirmStoryToTask          = '%s' . $lang->SRCommon . '已經在當前' . $lang->execution->common . '中轉了任務，請確認是否重複轉任務。';
$lang->execution->ge                          = "『%s』應當不小於實際開始時間『%s』。";

/* 統計。*/
$lang->execution->charts = new stdclass();
$lang->execution->charts->burn = new stdclass();
$lang->execution->charts->burn->graph = new stdclass();
$lang->execution->charts->burn->graph->caption      = "燃盡圖";
$lang->execution->charts->burn->graph->xAxisName    = "日期";
$lang->execution->charts->burn->graph->yAxisName    = "HOUR";
$lang->execution->charts->burn->graph->baseFontSize = 12;
$lang->execution->charts->burn->graph->formatNumber = 0;
$lang->execution->charts->burn->graph->animation    = 0;
$lang->execution->charts->burn->graph->rotateNames  = 1;
$lang->execution->charts->burn->graph->showValues   = 0;
$lang->execution->charts->burn->graph->reference    = '參考';
$lang->execution->charts->burn->graph->actuality    = '實際';

$lang->execution->placeholder = new stdclass();
$lang->execution->placeholder->code      = '團隊內部的簡稱';
$lang->execution->placeholder->totalLeft = "{$lang->executionCommon}開始時的總預計工時";

$lang->execution->selectGroup = new stdclass();
$lang->execution->selectGroup->done = '(已結束)';

$lang->execution->orderList['order_asc']  = "{$lang->SRCommon}排序正序";
$lang->execution->orderList['order_desc'] = "{$lang->SRCommon}排序倒序";
$lang->execution->orderList['pri_asc']    = "{$lang->SRCommon}優先順序正序";
$lang->execution->orderList['pri_desc']   = "{$lang->SRCommon}優先順序倒序";
$lang->execution->orderList['stage_asc']  = "{$lang->SRCommon}階段正序";
$lang->execution->orderList['stage_desc'] = "{$lang->SRCommon}階段倒序";

$lang->execution->kanban        = "看板";
$lang->execution->kanbanSetting = "看板設置";
$lang->execution->resetKanban   = "恢復預設";
$lang->execution->printKanban   = "打印看板";
$lang->execution->fullScreen    = "看板全屏展示";
$lang->execution->bugList       = "Bug列表";

$lang->execution->kanbanHideCols   = '看板隱藏已關閉、已取消列';
$lang->execution->kanbanShowOption = '顯示摺疊信息';
$lang->execution->kanbanColsColor  = '看板列自定義顏色';
$lang->execution->kanbanCardsUnit  = '個';

$lang->execution->kanbanViewList['all']   = '綜合看板';
$lang->execution->kanbanViewList['story'] = "{$lang->SRCommon}看板";
$lang->execution->kanbanViewList['bug']   = 'Bug看板';
$lang->execution->kanbanViewList['task']  = '任務看板';

$lang->kanbanSetting = new stdclass();
$lang->kanbanSetting->noticeReset     = '是否恢復看板預設設置？';
$lang->kanbanSetting->optionList['0'] = '隱藏';
$lang->kanbanSetting->optionList['1'] = '顯示';

$lang->printKanban = new stdclass();
$lang->printKanban->common  = '打印看板';
$lang->printKanban->content = '內容';
$lang->printKanban->print   = '打印';

$lang->printKanban->taskStatus = '狀態';

$lang->printKanban->typeList['all']       = '全部';
$lang->printKanban->typeList['increment'] = '增量';

$lang->execution->typeList['']       = '';
$lang->execution->typeList['stage']  = '階段';
$lang->execution->typeList['sprint'] = $lang->executionCommon;

$lang->execution->featureBar['task']['all']          = $lang->execution->allTasks;
$lang->execution->featureBar['task']['unclosed']     = $lang->execution->unclosed;
$lang->execution->featureBar['task']['assignedtome'] = $lang->execution->assignedToMe;
$lang->execution->featureBar['task']['myinvolved']   = $lang->execution->myInvolved;
$lang->execution->featureBar['task']['delayed']      = '已延期';
$lang->execution->featureBar['task']['needconfirm']  = "{$lang->SRCommon}變更";
$lang->execution->featureBar['task']['status']       = $lang->execution->statusSelects[''];

$lang->execution->featureBar['all']['all']       = $lang->execution->all;
$lang->execution->featureBar['all']['undone']    = $lang->execution->undone;
$lang->execution->featureBar['all']['wait']      = $lang->execution->statusList['wait'];
$lang->execution->featureBar['all']['doing']     = $lang->execution->statusList['doing'];
$lang->execution->featureBar['all']['suspended'] = $lang->execution->statusList['suspended'];
$lang->execution->featureBar['all']['closed']    = $lang->execution->statusList['closed'];

$lang->execution->myExecutions = '我參與的';
$lang->execution->doingProject = '進行中的項目';

$lang->execution->kanbanColType['wait']      = $lang->execution->statusList['wait']      . '的' . $lang->execution->common;
$lang->execution->kanbanColType['doing']     = $lang->execution->statusList['doing']     . '的' . $lang->execution->common;
$lang->execution->kanbanColType['suspended'] = $lang->execution->statusList['suspended'] . '的' . $lang->execution->common;
$lang->execution->kanbanColType['closed']    = $lang->execution->statusList['closed']    . '的' . $lang->execution->common . '(最近2期)';

$lang->execution->treeLevel = array();
$lang->execution->treeLevel['all']   = '全部展開';
$lang->execution->treeLevel['root']  = '全部摺疊';
$lang->execution->treeLevel['task']  = '全部顯示';
$lang->execution->treeLevel['story'] = "只看{$lang->SRCommon}";

$lang->execution->action = new stdclass();
$lang->execution->action->opened  = '$date, 由 <strong>$actor</strong> 創建。$extra' . "\n";
$lang->execution->action->managed = '$date, 由 <strong>$actor</strong> 維護。$extra' . "\n";
$lang->execution->action->edited  = '$date, 由 <strong>$actor</strong> 編輯。$extra' . "\n";
$lang->execution->action->extra   = '相關產品為 %s。';

$lang->execution->statusColorList = array();
$lang->execution->statusColorList['wait']      = '#0991FF';
$lang->execution->statusColorList['doing']     = '#0BD986';
$lang->execution->statusColorList['suspended'] = '#fdc137';
$lang->execution->statusColorList['closed']    = '#838A9D';

$lang->execution->teamWords  = '團隊';

$lang->execution->boardColorList = array('#32C5FF', '#006AF1', '#9D28B2', '#FF8F26', '#7FBB00', '#424BAC', '#66c5f8', '#EC2761');
