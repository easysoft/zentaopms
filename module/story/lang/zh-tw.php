<?php
/**
 * The story module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: zh-tw.php 5141 2013-07-15 05:57:15Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->story->create      = "提需求";
$lang->story->batchCreate = "批量添加";
$lang->story->change      = "變更";
$lang->story->changed     = '需求變更';
$lang->story->review      = '評審';
$lang->story->batchReview = '批量評審';
$lang->story->edit        = "編輯";
$lang->story->batchEdit   = "批量編輯";
$lang->story->subdivide   = '細分';
$lang->story->close       = '關閉';
$lang->story->batchClose  = '批量關閉';
$lang->story->activate    = '激活';
$lang->story->delete      = "刪除";
$lang->story->view        = "需求詳情";
$lang->story->tasks       = "相關任務";
$lang->story->bugs        = "相關Bug";
$lang->story->cases       = "相關用例";
$lang->story->taskCount   = '任務數';
$lang->story->bugCount    = 'Bug數';
$lang->story->caseCount   = '用例數';
$lang->story->taskCountAB = 'T';
$lang->story->bugCountAB  = 'B';
$lang->story->caseCountAB = 'C';
$lang->story->linkStory   = '關聯需求';
$lang->story->unlinkStory = '移除相關需求';
$lang->story->export      = "導出數據";
$lang->story->zeroCase    = "零用例需求";
$lang->story->zeroTask    = "只列零任務需求";
$lang->story->reportChart = "統計報表";
$lang->story->copyTitle   = "同需求名稱";
$lang->story->batchChangePlan   = "批量修改計劃";
$lang->story->batchChangeBranch = "批量修改分支";
$lang->story->batchChangeStage  = "批量修改階段";
$lang->story->batchAssignTo     = "批量指派";
$lang->story->batchChangeModule = "批量修改模組";

$lang->story->common         = '需求';
$lang->story->id             = '編號';
$lang->story->product        = "所屬{$lang->productCommon}";
$lang->story->branch         = "分支/平台";
$lang->story->module         = '所屬模組';
$lang->story->moduleAB       = '模組';
$lang->story->source         = '需求來源';
$lang->story->sourceNote     = '來源備註';
$lang->story->fromBug        = '來源Bug';
$lang->story->title          = '需求名稱';
$lang->story->spec           = '需求描述';
$lang->story->verify         = '驗收標準';
$lang->story->pri            = '優先順序';
$lang->story->estimate       = '預計工時';
$lang->story->estimateAB     = '預計';
$lang->story->hour           = '小時';
$lang->story->status         = '當前狀態';
$lang->story->stage          = '所處階段';
$lang->story->stageAB        = '階段';
$lang->story->mailto         = '抄送給';
$lang->story->openedBy       = '由誰創建';
$lang->story->openedDate     = '創建日期';
$lang->story->assignedTo     = '指派給';
$lang->story->assignedDate   = '指派日期';
$lang->story->lastEditedBy   = '最後修改';
$lang->story->lastEditedDate = '最後修改日期';
$lang->story->closedBy       = '由誰關閉';
$lang->story->closedDate     = '關閉日期';
$lang->story->closedReason   = '關閉原因';
$lang->story->rejectedReason = '拒絶原因';
$lang->story->reviewedBy     = '由誰評審';
$lang->story->reviewedDate   = '評審時間';
$lang->story->version        = '版本號';
$lang->story->plan           = '所屬計劃';
$lang->story->planAB         = '計劃';
$lang->story->comment        = '備註';
$lang->story->linkStories    = '相關需求';
$lang->story->childStories   = '細分需求';
$lang->story->duplicateStory = '重複需求';
$lang->story->reviewResult   = '評審結果';
$lang->story->preVersion     = '之前版本';
$lang->story->keywords       = '關鍵詞';
$lang->story->newStory       = '繼續添加需求';
$lang->story->colorTag       = '顏色標籤';
$lang->story->files          = '附件';

$lang->story->ditto       = '同上';
$lang->story->dittoNotice = '該需求與上一需求不屬於同一產品！';

$lang->story->useList[0] = '不使用';
$lang->story->useList[1] = '使用';

$lang->story->statusList['']          = '';
$lang->story->statusList['draft']     = '草稿';
$lang->story->statusList['active']    = '激活';
$lang->story->statusList['closed']    = '已關閉';
$lang->story->statusList['changed']   = '已變更';

$lang->story->stageList['']           = '';
$lang->story->stageList['wait']       = '未開始';
$lang->story->stageList['planned']    = '已計劃';
$lang->story->stageList['projected']  = '已立項';
$lang->story->stageList['developing'] = '研發中';
$lang->story->stageList['developed']  = '研發完畢';
$lang->story->stageList['testing']    = '測試中';
$lang->story->stageList['tested']     = '測試完畢';
$lang->story->stageList['verified']   = '已驗收';
$lang->story->stageList['released']   = '已發佈';

$lang->story->reasonList['']           = '';
$lang->story->reasonList['done']       = '已完成';
$lang->story->reasonList['subdivided'] = '已細分';
$lang->story->reasonList['duplicate']  = '重複';
$lang->story->reasonList['postponed']  = '延期';
$lang->story->reasonList['willnotdo']  = '不做';
$lang->story->reasonList['cancel']     = '已取消';
$lang->story->reasonList['bydesign']   = '設計如此';
//$lang->story->reasonList['isbug']      = '是個Bug';

$lang->story->reviewResultList['']        = '';
$lang->story->reviewResultList['pass']    = '確認通過';
$lang->story->reviewResultList['revert']  = '撤銷變更';
$lang->story->reviewResultList['clarify'] = '有待明確';
$lang->story->reviewResultList['reject']  = '拒絶';

$lang->story->reviewList[0] = '否';
$lang->story->reviewList[1] = '是';

$lang->story->sourceList['']           = '';
$lang->story->sourceList['customer']   = '客戶';
$lang->story->sourceList['user']       = '用戶';
$lang->story->sourceList['po']         = $lang->productCommon . '經理';
$lang->story->sourceList['market']     = '市場';
$lang->story->sourceList['service']    = '客服';
$lang->story->sourceList['operation']  = '運營';
$lang->story->sourceList['support']    = '技術支持';
$lang->story->sourceList['competitor'] = '競爭對手';
$lang->story->sourceList['partner']    = '合作夥伴';
$lang->story->sourceList['dev']        = '開發人員';
$lang->story->sourceList['tester']     = '測試人員';
$lang->story->sourceList['bug']        = 'Bug';
$lang->story->sourceList['other']      = '其他';

$lang->story->priList[]   = '';
$lang->story->priList[3]  = '3';
$lang->story->priList[1]  = '1';
$lang->story->priList[2]  = '2';
$lang->story->priList[4]  = '4';

$lang->story->legendBasicInfo      = '基本信息';
$lang->story->legendLifeTime       = '需求的一生';
$lang->story->legendRelated        = '相關信息';
$lang->story->legendMailto         = '抄送給';
$lang->story->legendAttatch        = '附件';
$lang->story->legendProjectAndTask = $lang->projectCommon . '任務';
$lang->story->legendBugs           = '相關Bug';
$lang->story->legendFromBug        = '來源Bug';
$lang->story->legendCases          = '相關用例';
$lang->story->legendLinkStories    = '相關需求';
$lang->story->legendChildStories   = '細分需求';
$lang->story->legendSpec           = '需求描述';
$lang->story->legendVerify         = '驗收標準';
$lang->story->legendMisc           = '其他相關';

$lang->story->lblChange            = '變更需求';
$lang->story->lblReview            = '評審需求';
$lang->story->lblActivate          = '激活需求';
$lang->story->lblClose             = '關閉需求';

$lang->story->checkAffection       = '檢查影響';
$lang->story->affectedProjects     = '影響的' . $lang->projectCommon;
$lang->story->affectedBugs         = '影響的Bug';
$lang->story->affectedCases        = '影響的用例';

$lang->story->specTemplate          = "建議參考的模板：作為一名<某種類型的用戶>，我希望<達成某些目的>，這樣可以<開發的價值>。";
$lang->story->needNotReview         = '不需要評審';
$lang->story->successSaved          = "需求成功添加，";
$lang->story->confirmDelete         = "您確認刪除該需求嗎?";
$lang->story->errorEmptyChildStory  = '『細分需求』不能為空。';
$lang->story->mustChooseResult      = '必須選擇評審結果';
$lang->story->mustChoosePreVersion  = '必須選擇回溯的版本';

$lang->story->form = new stdclass();
$lang->story->form->area      = '該需求所屬範圍';
$lang->story->form->desc      = '描述及標準，什麼需求？如何驗收？';
$lang->story->form->resource  = '資源分配，有誰完成？需要多少時間？';
$lang->story->form->file      = '附件，如果該需求有相關檔案，請點此上傳。';

$lang->story->action = new stdclass();
$lang->story->action->reviewed            = array('main' => '$date, 由 <strong>$actor</strong> 記錄評審結果，結果為 <strong>$extra</strong>。', 'extra' => 'reviewResultList');
$lang->story->action->closed              = array('main' => '$date, 由 <strong>$actor</strong> 關閉，原因為 <strong>$extra</strong> $appendLink。', 'extra' => 'reasonList');
$lang->story->action->linked2plan         = array('main' => '$date, 由 <strong>$actor</strong> 關聯到計劃 <strong>$extra</strong>。');
$lang->story->action->unlinkedfromplan    = array('main' => '$date, 由 <strong>$actor</strong> 從計劃 <strong>$extra</strong> 移除。');
$lang->story->action->linked2project      = array('main' => '$date, 由 <strong>$actor</strong> 關聯到' . $lang->projectCommon . ' <strong>$extra</strong>。');
$lang->story->action->unlinkedfromproject = array('main' => '$date, 由 <strong>$actor</strong> 從' . $lang->projectCommon . ' <strong>$extra</strong> 移除。');
$lang->story->action->linked2build        = array('main' => '$date, 由 <strong>$actor</strong> 關聯到版本 <strong>$extra</strong>。');
$lang->story->action->unlinkedfrombuild   = array('main' => '$date, 由 <strong>$actor</strong> 從版本 <strong>$extra</strong> 移除。');
$lang->story->action->linked2release      = array('main' => '$date, 由 <strong>$actor</strong> 關聯到發佈 <strong>$extra</strong>。');
$lang->story->action->unlinkedfromrelease = array('main' => '$date, 由 <strong>$actor</strong> 從發佈 <strong>$extra</strong> 移除。');
$lang->story->action->linkrelatedstory    = array('main' => '$date, 由 <strong>$actor</strong> 關聯相關需求 <strong>$extra</strong>。');
$lang->story->action->subdividestory      = array('main' => '$date, 由 <strong>$actor</strong> 細分為需求 <strong>$extra</strong>。');
$lang->story->action->unlinkrelatedstory  = array('main' => '$date, 由 <strong>$actor</strong> 移除相關需求 <strong>$extra</strong>。');
$lang->story->action->unlinkchildstory    = array('main' => '$date, 由 <strong>$actor</strong> 移除細分需求 <strong>$extra</strong>。');

/* 統計報表。*/
$lang->story->report = new stdclass();
$lang->story->report->common = '報表';
$lang->story->report->select = '請選擇報表類型';
$lang->story->report->create = '生成報表';
$lang->story->report->value  = '需求數';

$lang->story->report->charts['storysPerProduct']        = $lang->productCommon . '需求數量';
$lang->story->report->charts['storysPerModule']         = '模組需求數量';
$lang->story->report->charts['storysPerSource']         = '需求來源統計';
$lang->story->report->charts['storysPerPlan']           = '計划進行統計';
$lang->story->report->charts['storysPerStatus']         = '狀態進行統計';
$lang->story->report->charts['storysPerStage']          = '所處階段進行統計';
$lang->story->report->charts['storysPerPri']            = '優先順序進行統計';
$lang->story->report->charts['storysPerEstimate']       = '預計工時進行統計';
$lang->story->report->charts['storysPerOpenedBy']       = '由誰創建來進行統計';
$lang->story->report->charts['storysPerAssignedTo']     = '當前指派來進行統計';
$lang->story->report->charts['storysPerClosedReason']   = '關閉原因來進行統計';
$lang->story->report->charts['storysPerChange']         = '變更次數來進行統計';

$lang->story->report->options = new stdclass();
$lang->story->report->options->graph   = new stdclass();
$lang->story->report->options->type    = 'pie';
$lang->story->report->options->width   = 500;
$lang->story->report->options->height  = 140;

$lang->story->report->storysPerProduct      = new stdclass();
$lang->story->report->storysPerModule       = new stdclass();
$lang->story->report->storysPerSource       = new stdclass();
$lang->story->report->storysPerPlan         = new stdclass();
$lang->story->report->storysPerStatus       = new stdclass();
$lang->story->report->storysPerStage        = new stdclass();
$lang->story->report->storysPerPri          = new stdclass();
$lang->story->report->storysPerOpenedBy     = new stdclass();
$lang->story->report->storysPerAssignedTo   = new stdclass();
$lang->story->report->storysPerClosedReason = new stdclass();
$lang->story->report->storysPerEstimate     = new stdclass();
$lang->story->report->storysPerChange       = new stdclass();

$lang->story->report->storysPerProduct->item      = $lang->productCommon;
$lang->story->report->storysPerModule->item       = '模組';
$lang->story->report->storysPerSource->item       = '來源';
$lang->story->report->storysPerPlan->item         = '計劃';
$lang->story->report->storysPerStatus->item       = '狀態';
$lang->story->report->storysPerStage->item        = '階段';
$lang->story->report->storysPerPri->item          = '優先順序';
$lang->story->report->storysPerOpenedBy->item     = '用戶';
$lang->story->report->storysPerAssignedTo->item   = '用戶';
$lang->story->report->storysPerClosedReason->item = '原因';
$lang->story->report->storysPerEstimate->item     = '預計工時';
$lang->story->report->storysPerChange->item       = '變更次數';

$lang->story->report->storysPerProduct->graph      = new stdclass();
$lang->story->report->storysPerModule->graph       = new stdclass();
$lang->story->report->storysPerSource->graph       = new stdclass();
$lang->story->report->storysPerPlan->graph         = new stdclass();
$lang->story->report->storysPerStatus->graph       = new stdclass();
$lang->story->report->storysPerStage->graph        = new stdclass();
$lang->story->report->storysPerPri->graph          = new stdclass();
$lang->story->report->storysPerOpenedBy->graph     = new stdclass();
$lang->story->report->storysPerAssignedTo->graph   = new stdclass();
$lang->story->report->storysPerClosedReason->graph = new stdclass();
$lang->story->report->storysPerEstimate->graph     = new stdclass();
$lang->story->report->storysPerChange->graph       = new stdclass();

$lang->story->report->storysPerProduct->graph->xAxisName      = $lang->productCommon;
$lang->story->report->storysPerModule->graph->xAxisName       = '模組';
$lang->story->report->storysPerSource->graph->xAxisName       = '來源';
$lang->story->report->storysPerPlan->graph->xAxisName         = '計劃';
$lang->story->report->storysPerStatus->graph->xAxisName       = '狀態';
$lang->story->report->storysPerStage->graph->xAxisName        = '所處階段';
$lang->story->report->storysPerPri->graph->xAxisName          = '優先順序';
$lang->story->report->storysPerOpenedBy->graph->xAxisName     = '由誰創建';
$lang->story->report->storysPerAssignedTo->graph->xAxisName   = '當前指派';
$lang->story->report->storysPerClosedReason->graph->xAxisName = '關閉原因';
$lang->story->report->storysPerEstimate->graph->xAxisName     = '預計時間';
$lang->story->report->storysPerChange->graph->xAxisName       = '變更次數';

$lang->story->placeholder = new stdclass();
$lang->story->placeholder->estimate = $lang->story->hour;

$lang->story->chosen = new stdClass();
$lang->story->chosen->reviewedBy = '選擇評審人...';

$lang->story->notice = new stdClass();
$lang->story->notice->closed = '您選擇的需求已經被關閉了！';
