<?php
/**
 * The task module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: zh-tw.php 5040 2013-07-06 06:22:18Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->task->index               = "任務一覽";
$lang->task->create              = "建任務";
$lang->task->batchCreate         = "批量創建";
$lang->task->batchCreateChildren = "批量建子任務";
$lang->task->batchEdit           = "批量編輯";
$lang->task->batchChangeModule   = "批量修改模組";
$lang->task->batchClose          = "批量關閉";
$lang->task->batchCancel         = "批量取消";
$lang->task->edit                = "編輯任務";
$lang->task->delete              = "刪除";
$lang->task->deleteAction        = "刪除任務";
$lang->task->deleted             = "已刪除";
$lang->task->delayed             = '延期';
$lang->task->view                = "查看任務";
$lang->task->logEfforts          = "記錄工時";
$lang->task->record              = "工時";
$lang->task->teamConsumed        = "團隊工時";
$lang->task->start               = "開始";
$lang->task->startAction         = "開始任務";
$lang->task->restart             = "繼續";
$lang->task->restartAction       = "繼續任務";
$lang->task->finishAction        = "完成任務";
$lang->task->finish              = "完成";
$lang->task->pause               = "暫停";
$lang->task->pauseAction         = "暫停任務";
$lang->task->close               = "關閉";
$lang->task->closeAction         = "關閉任務";
$lang->task->cancel              = "取消";
$lang->task->cancelAction        = "取消任務";
$lang->task->activateAction      = "激活任務";
$lang->task->activate            = "激活";
$lang->task->export              = "導出數據";
$lang->task->exportAction        = "導出任務";
$lang->task->reportChart         = "報表統計";
$lang->task->fromBug             = '來源Bug';
$lang->task->case                = '相關用例';
$lang->task->confirmStoryChange  = "確認{$lang->SRCommon}變動";
$lang->task->storyChange         = "{$lang->SRCommon}變更";
$lang->task->progress            = '進度';
$lang->task->progressAB          = '進度';
$lang->task->progressTips        = '已消耗/(已消耗+剩餘)';
$lang->task->copy                = '複製任務';
$lang->task->waitTask            = '未開始的任務';
$lang->task->allModule           = '所有模組';

$lang->task->common            = '任務';
$lang->task->id                = '編號';
$lang->task->project           = '所屬項目';
$lang->task->execution         = '所屬' . $lang->execution->common;
$lang->task->module            = '所屬模組';
$lang->task->moduleAB          = '模組';
$lang->task->design            = '相關設計';
$lang->task->story             = "相關{$lang->SRCommon}";
$lang->task->storyAB           = $lang->SRCommon;
$lang->task->storySpec         = "{$lang->SRCommon}描述";
$lang->task->storyVerify       = '驗收標準';
$lang->task->storyVersion      = "{$lang->SRCommon}版本";
$lang->task->designVersion     = "設計版本";
$lang->task->color             = '標題顏色';
$lang->task->name              = '任務名稱';
$lang->task->type              = '任務類型';
$lang->task->typeAB            = '類型';
$lang->task->sync2Gitlab       = '同步到GitLab';
$lang->task->pri               = '優先順序';
$lang->task->mailto            = '抄送給';
$lang->task->estimate          = '最初預計';
$lang->task->estimateAB        = '預計';
$lang->task->left              = '預計剩餘';
$lang->task->leftAB            = '剩餘';
$lang->task->consumed          = '總計消耗';
$lang->task->currentConsumed   = '本次消耗';
$lang->task->myConsumed        = '我的總消耗';
$lang->task->consumedAB        = '消耗';
$lang->task->hour              = '小時';
$lang->task->consumedThisTime  = '工時';
$lang->task->leftThisTime      = '剩餘';
$lang->task->datePlan          = '日程規劃';
$lang->task->estStarted        = '預計開始';
$lang->task->realStarted       = '實際開始';
$lang->task->date              = '日期';
$lang->task->deadline          = '截止日期';
$lang->task->deadlineAB        = '截止';
$lang->task->status            = '任務狀態';
$lang->task->subStatus         = '子狀態';
$lang->task->desc              = '任務描述';
$lang->task->version           = '版本';
$lang->task->estimateStartDate = '預計開始日期';
$lang->task->actualStartDate   = '實際開始日期';
$lang->task->planDuration      = '計劃持續天數';
$lang->task->realDuration      = '實際持續天數';
$lang->task->version           = '版本';
$lang->task->assign            = '指派';
$lang->task->assignAction      = '指派任務';
$lang->task->assignTo          = $lang->task->assign;
$lang->task->batchAssignTo     = '批量指派';
$lang->task->assignedTo        = '指派給';
$lang->task->assignedToAB      = '指派給';
$lang->task->assignedDate      = '指派日期';
$lang->task->openedBy          = '由誰創建';
$lang->task->openedDate        = '創建日期';
$lang->task->openedDateAB      = '創建';
$lang->task->activatedDate     = '激活日期';
$lang->task->finishedBy        = '由誰完成';
$lang->task->finishedByAB      = '完成者';
$lang->task->finishedDate      = '實際完成';
$lang->task->finishedDateAB    = '實際完成';
$lang->task->finishedList      = '完成者列表';
$lang->task->canceledBy        = '由誰取消';
$lang->task->canceledDate      = '取消時間';
$lang->task->closedBy          = '由誰關閉';
$lang->task->closedDate        = '關閉時間';
$lang->task->closedReason      = '關閉原因';
$lang->task->lastEditedBy      = '最後修改';
$lang->task->lastEditedDate    = '最後修改日期';
$lang->task->lastEdited        = '最後編輯';
$lang->task->recordWorkhour    = '工時';
$lang->task->editEffort      = '編輯工時';
$lang->task->deleteWorkhour    = '刪除工時';
$lang->task->colorTag          = '顏色標籤';
$lang->task->files             = '附件';
$lang->task->hasConsumed       = '之前消耗';
$lang->task->multiple          = '多人任務';
$lang->task->multipleAB        = '多人';
$lang->task->team              = '團隊';
$lang->task->transfer          = '轉交';
$lang->task->transferTo        = '轉交給';
$lang->task->children          = '子任務';
$lang->task->childrenAB        = '子';
$lang->task->parent            = '父任務';
$lang->task->parentAB          = '父';
$lang->task->lblPri            = 'P';
$lang->task->lblHour           = '(h)';
$lang->task->lblTestStory      = "測試{$lang->SRCommon}";

/* Fields of zt_taskestimate. */
$lang->task->task    = '任務';
$lang->task->account = '用戶名';
$lang->task->work    = '工作內容';

$lang->task->recordWorkhourAction = '添加工時';

$lang->task->ditto             = '同上';
$lang->task->dittoNotice       = "該任務與上一任務不屬於同一%s！";
$lang->task->selectTestStory   = "選擇{$lang->SRCommon}";
$lang->task->selectAllUser     = '全部';
$lang->task->noStory           = "無{$lang->SRCommon}";
$lang->task->noAssigned        = '未指派';
$lang->task->noFinished        = '未完成';
$lang->task->noClosed          = '未關閉';
$lang->task->yesterdayFinished = '昨日完成任務數';
$lang->task->allTasks          = '總任務';
$lang->task->linkMR            = '相關合并請求';

$lang->task->statusList['']       = '';
$lang->task->statusList['wait']   = '未開始';
$lang->task->statusList['doing']  = '進行中';
$lang->task->statusList['done']   = '已完成';
$lang->task->statusList['pause']  = '已暫停';
$lang->task->statusList['cancel'] = '已取消';
$lang->task->statusList['closed'] = '已關閉';

$lang->task->typeList['']        = '';
$lang->task->typeList['design']  = '設計';
$lang->task->typeList['devel']   = '開發';
$lang->task->typeList['request'] = '需求';
$lang->task->typeList['test']    = '測試';
$lang->task->typeList['study']   = '研究';
$lang->task->typeList['discuss'] = '討論';
$lang->task->typeList['ui']      = '界面';
$lang->task->typeList['affair']  = '事務';
$lang->task->typeList['misc']    = '其他';

$lang->task->priList[0] = '';
$lang->task->priList[1] = '1';
$lang->task->priList[2] = '2';
$lang->task->priList[3] = '3';
$lang->task->priList[4] = '4';

$lang->task->reasonList['']       = '';
$lang->task->reasonList['done']   = '已完成';
$lang->task->reasonList['cancel'] = '已取消';

$lang->task->afterChoices['continueAdding'] = "繼續為該{$lang->SRCommon}添加任務";
$lang->task->afterChoices['toTaskList']     = '返回任務列表';
$lang->task->afterChoices['toStoryList']    = "返回{$lang->SRCommon}列表";

$lang->task->legendBasic  = '基本信息';
$lang->task->legendEffort = '工時信息';
$lang->task->legendLife   = '任務的一生';
$lang->task->legendDesc   = '任務描述';
$lang->task->legendDetail = '任務詳情';
$lang->task->legendMisc   = '其他相關';

$lang->task->confirmDelete          = "您確定要刪除這個任務嗎？";
$lang->task->confirmDeleteEffort    = "您確定要刪除這個記錄嗎？";
$lang->task->copyStoryTitle         = "同{$lang->SRCommon}";
$lang->task->afterSubmit            = "添加之後";
$lang->task->successSaved           = "成功添加，";
$lang->task->delayWarning           = " <strong class='text-danger'> 延期%s天 </strong>";
$lang->task->remindBug              = "該任務為Bug轉化得到，是否更新Bug:%s ?";
$lang->task->confirmChangeExecution = "修改{$lang->executionCommon}會導致相應的所屬模組、相關{$lang->SRCommon}和指派人發生變化，確定嗎？";
$lang->task->confirmFinish          = '"預計剩餘"為0，確認將任務狀態改為"已完成"嗎？';
$lang->task->confirmRecord          = '"剩餘"為0，任務將標記為"已完成"，您確定嗎？';
$lang->task->confirmTransfer        = '"當前剩餘"為0，任務將被轉交，您確定嗎？';
$lang->task->noticeTaskStart        = '"總計消耗"和"預計剩餘"不能同時為0';
$lang->task->noticeLinkStory        = "沒有可關聯的相關{$lang->SRCommon}，您可以為當前項目%s，然後%s";
$lang->task->noticeSaveRecord       = '您有尚未保存的工時記錄，請先將其保存。';
$lang->task->commentActions         = '%s. %s, 由 <strong>%s</strong> 添加備註。';
$lang->task->deniedNotice           = '當前任務只有%s才可以%s。';
$lang->task->noTask                 = '暫時沒有任務。';
$lang->task->createDenied           = '你不能在該項目添加任務';
$lang->task->cannotDeleteParent     = '不能刪除父任務。';
$lang->task->addChildTask           = '因該任務已經產生消耗，為保證數據一致性，我們會幫您創建一條同名子任務記錄該消耗。';

$lang->task->error                    = new stdclass();
$lang->task->error->totalNumber       = '"總計消耗"必須為數字';
$lang->task->error->consumedNumber    = '"本次消耗"必須為數字';
$lang->task->error->estimateNumber    = '"最初預計"必須為數字';
$lang->task->error->recordMinus       = '工時不能為負數';
$lang->task->error->leftNumber        = '"預計剩餘"必須為數字';
$lang->task->error->recordMinus       = '工時不能為負數';
$lang->task->error->consumedSmall     = '"總計消耗"必須大於之前消耗';
$lang->task->error->consumedThisTime  = '請填寫"工時"';
$lang->task->error->left              = '請填寫"剩餘"';
$lang->task->error->work              = '"備註"必須小於%d個字元';
$lang->task->error->skipClose         = '任務：%s 不是“已完成”或“已取消”狀態，確定要關閉嗎？';
$lang->task->error->consumed          = '任務：%s工時不能小於0，忽略該任務工時的改動';
$lang->task->error->assignedTo        = '當前狀態的多人任務不能指派給任務團隊外的成員。';
$lang->task->error->consumedEmpty     = '"本次消耗"不能為0';
$lang->task->error->deadlineSmall     = '"截止日期"必須大於"預計開始"';
$lang->task->error->alreadyStarted    = '此任務已被啟動，不能重複啟動！';
$lang->task->error->realStartedEmpty  = '實際開始不能為空';
$lang->task->error->finishedDateEmpty = '實際完成不能為空';
$lang->task->error->finishedDateSmall = '實際完成不能小於實際開始';
$lang->task->error->alreadyConsumed   = '當前選中的父任務已有消耗。';
$lang->task->error->date              = '日期不能大於今天';

/* Report. */
$lang->task->report         = new stdclass();
$lang->task->report->common = '報表';
$lang->task->report->select = '請選擇報表類型';
$lang->task->report->create = '生成報表';
$lang->task->report->value  = '任務數';

$lang->task->report->charts['tasksPerExecution']    = '按' . $lang->executionCommon . '任務數統計';
$lang->task->report->charts['tasksPerModule']       = '按模組任務數統計';
$lang->task->report->charts['tasksPerAssignedTo']   = '按指派給統計';
$lang->task->report->charts['tasksPerType']         = '按任務類型統計';
$lang->task->report->charts['tasksPerPri']          = '按優先順序統計';
$lang->task->report->charts['tasksPerStatus']       = '按任務狀態統計';
$lang->task->report->charts['tasksPerDeadline']     = '按截止日期統計';
$lang->task->report->charts['tasksPerEstimate']     = '按預計時間統計';
$lang->task->report->charts['tasksPerLeft']         = '按剩餘時間統計';
$lang->task->report->charts['tasksPerConsumed']     = '按消耗時間統計';
$lang->task->report->charts['tasksPerFinishedBy']   = '按由誰完成統計';
$lang->task->report->charts['tasksPerClosedReason'] = '按關閉原因統計';
$lang->task->report->charts['finishedTasksPerDay']  = '按每天完成統計';

$lang->task->report->options         = new stdclass();
$lang->task->report->options->graph  = new stdclass();
$lang->task->report->options->type   = 'pie';
$lang->task->report->options->width  = 500;
$lang->task->report->options->height = 140;

$lang->task->report->tasksPerExecution    = new stdclass();
$lang->task->report->tasksPerModule       = new stdclass();
$lang->task->report->tasksPerAssignedTo   = new stdclass();
$lang->task->report->tasksPerType         = new stdclass();
$lang->task->report->tasksPerPri          = new stdclass();
$lang->task->report->tasksPerStatus       = new stdclass();
$lang->task->report->tasksPerDeadline     = new stdclass();
$lang->task->report->tasksPerEstimate     = new stdclass();
$lang->task->report->tasksPerLeft         = new stdclass();
$lang->task->report->tasksPerConsumed     = new stdclass();
$lang->task->report->tasksPerFinishedBy   = new stdclass();
$lang->task->report->tasksPerClosedReason = new stdclass();
$lang->task->report->finishedTasksPerDay  = new stdclass();

$lang->task->report->tasksPerExecution->item    = $lang->executionCommon;
$lang->task->report->tasksPerModule->item       = '模組';
$lang->task->report->tasksPerAssignedTo->item   = '用戶';
$lang->task->report->tasksPerType->item         = '類型';
$lang->task->report->tasksPerPri->item          = '優先順序';
$lang->task->report->tasksPerStatus->item       = '狀態';
$lang->task->report->tasksPerDeadline->item     = '日期';
$lang->task->report->tasksPerEstimate->item     = '預計';
$lang->task->report->tasksPerLeft->item         = '剩餘';
$lang->task->report->tasksPerConsumed->item     = '消耗';
$lang->task->report->tasksPerFinishedBy->item   = '用戶';
$lang->task->report->tasksPerClosedReason->item = '原因';
$lang->task->report->finishedTasksPerDay->item  = '日期';

$lang->task->report->tasksPerExecution->graph    = new stdclass();
$lang->task->report->tasksPerModule->graph       = new stdclass();
$lang->task->report->tasksPerAssignedTo->graph   = new stdclass();
$lang->task->report->tasksPerType->graph         = new stdclass();
$lang->task->report->tasksPerPri->graph          = new stdclass();
$lang->task->report->tasksPerStatus->graph       = new stdclass();
$lang->task->report->tasksPerDeadline->graph     = new stdclass();
$lang->task->report->tasksPerEstimate->graph     = new stdclass();
$lang->task->report->tasksPerLeft->graph         = new stdclass();
$lang->task->report->tasksPerConsumed->graph     = new stdclass();
$lang->task->report->tasksPerFinishedBy->graph   = new stdclass();
$lang->task->report->tasksPerClosedReason->graph = new stdclass();
$lang->task->report->finishedTasksPerDay->graph  = new stdclass();

$lang->task->report->tasksPerExecution->graph->xAxisName    = $lang->executionCommon;
$lang->task->report->tasksPerModule->graph->xAxisName       = '模組';
$lang->task->report->tasksPerAssignedTo->graph->xAxisName   = '用戶';
$lang->task->report->tasksPerType->graph->xAxisName         = '類型';
$lang->task->report->tasksPerPri->graph->xAxisName          = '優先順序';
$lang->task->report->tasksPerStatus->graph->xAxisName       = '狀態';
$lang->task->report->tasksPerDeadline->graph->xAxisName     = '日期';
$lang->task->report->tasksPerEstimate->graph->xAxisName     = '時間';
$lang->task->report->tasksPerLeft->graph->xAxisName         = '時間';
$lang->task->report->tasksPerConsumed->graph->xAxisName     = '時間';
$lang->task->report->tasksPerFinishedBy->graph->xAxisName   = '用戶';
$lang->task->report->tasksPerClosedReason->graph->xAxisName = '關閉原因';

$lang->task->report->finishedTasksPerDay->type             = 'bar';
$lang->task->report->finishedTasksPerDay->graph->xAxisName = '日期';

$lang->taskestimate           = new stdclass();
$lang->taskestimate->consumed = '工時';
