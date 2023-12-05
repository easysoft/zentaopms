<?php
/**
 * The report module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     report
 * @version     $Id: zh-tw.php 5080 2013-07-10 00:46:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->report->index     = '統計首頁';
$lang->report->list      = '透视表';
$lang->report->item      = '條目';
$lang->report->value     = '值';
$lang->report->percent   = '百分比';
$lang->report->undefined = '未設定';
$lang->report->project   = $lang->projectCommon;
$lang->report->PO        = 'PO';

$lang->report->colors[] = 'AFD8F8';
$lang->report->colors[] = 'F6BD0F';
$lang->report->colors[] = '8BBA00';
$lang->report->colors[] = 'FF8E46';
$lang->report->colors[] = '008E8E';
$lang->report->colors[] = 'D64646';
$lang->report->colors[] = '8E468E';
$lang->report->colors[] = '588526';
$lang->report->colors[] = 'B3AA00';
$lang->report->colors[] = '008ED6';
$lang->report->colors[] = '9D080D';
$lang->report->colors[] = 'A186BE';

$lang->report->assign['noassign'] = '未指派';
$lang->report->assign['assign']   = '已指派';

$lang->report->singleColor[] = 'F6BD0F';

$lang->report->projectDeviation = "{$lang->execution->common}偏差報表";
$lang->report->productSummary   = $lang->productCommon . '彙總表';
$lang->report->bugCreate        = 'Bug創建表';
$lang->report->bugAssign        = '未解决Bug指派表';
$lang->report->workload         = '員工負載表';
$lang->report->workloadAB       = '工作負載';
$lang->report->bugOpenedDate    = 'Bug創建時間';
$lang->report->beginAndEnd      = '起止時間';
$lang->report->begin            = '起始日期';
$lang->report->end              = '結束日期';
$lang->report->dept             = '部門';
$lang->report->deviationChart   = "{$lang->execution->common}偏差曲綫";

$lang->report->id            = '編號';
$lang->report->execution     = $lang->execution->common;
$lang->report->product       = $lang->productCommon;
$lang->report->user          = '姓名';
$lang->report->bugTotal      = 'Bug';
$lang->report->task          = '任務數';
$lang->report->estimate      = '總預計';
$lang->report->consumed      = '總消耗';
$lang->report->remain        = '剩餘工時';
$lang->report->deviation     = '偏差';
$lang->report->deviationRate = '偏差率';
$lang->report->total         = '總計';
$lang->report->to            = '至';
$lang->report->taskTotal     = "總任務數";
$lang->report->manhourTotal  = "總工時";
$lang->report->validRate     = "有效率";
$lang->report->validRateTips = "方案為已解決或延期/狀態為已解決或已關閉";
$lang->report->unplanned     = "未計劃";
$lang->report->workday       = '每天工時';
$lang->report->diffDays      = '工作日天數';

$lang->report->typeList['default'] = '預設';
$lang->report->typeList['pie']     = '餅圖';
$lang->report->typeList['bar']     = '柱狀圖';
$lang->report->typeList['line']    = '折線圖';

$lang->report->conditions    = '篩選條件：';
$lang->report->closedProduct = '關閉' . $lang->productCommon;
$lang->report->overduePlan   = "過期計劃";

/* daily reminder. */
$lang->report->idAB         = 'ID';
$lang->report->bugTitle     = 'Bug標題';
$lang->report->taskName     = '任務名稱';
$lang->report->todoName     = '待辦名稱';
$lang->report->testTaskName = '版本名稱';
$lang->report->deadline     = '截止日期';

$lang->report->mailTitle           = new stdclass();
$lang->report->mailTitle->begin    = '提醒：您有';
$lang->report->mailTitle->bug      = " Bug(%s),";
$lang->report->mailTitle->task     = " 任務(%s),";
$lang->report->mailTitle->todo     = " 待辦(%s),";
$lang->report->mailTitle->testTask = " 測試版本(%s),";

$lang->report->annualData = new stdclass();
$lang->report->annualData->title            = "%s %s年工作彙總";
$lang->report->annualData->exportByZentao   = "由禪道系統導出";
$lang->report->annualData->scope            = "統計範圍";
$lang->report->annualData->allUser          = "所有用戶";
$lang->report->annualData->allDept          = "全公司";
$lang->report->annualData->soFar            = "（%s年）";
$lang->report->annualData->baseInfo         = "基本數據";
$lang->report->annualData->actionData       = "操作數據";
$lang->report->annualData->contributionData = "貢獻數據";
$lang->report->annualData->radar            = "能力雷達圖";
$lang->report->annualData->executions       = "{$lang->executionCommon}數據";
$lang->report->annualData->products         = "{$lang->productCommon}數據";
$lang->report->annualData->stories          = "需求數據";
$lang->report->annualData->tasks            = "任務數據";
$lang->report->annualData->bugs             = "Bug數據";
$lang->report->annualData->cases            = "用例數據";
$lang->report->annualData->statusStat       = "{$lang->SRCommon}/任務/Bug狀態分佈（截止今日）";

$lang->report->annualData->companyUsers     = "公司總人數";
$lang->report->annualData->deptUsers        = "部門人數";
$lang->report->annualData->logins           = "登錄次數";
$lang->report->annualData->actions          = "操作次數";
$lang->report->annualData->contributions    = "貢獻數";
$lang->report->annualData->consumed         = "消耗工時";
$lang->report->annualData->todos            = "待辦數";

$lang->report->annualData->storyStatusStat = "需求狀態分佈";
$lang->report->annualData->taskStatusStat  = "任務狀態分佈";
$lang->report->annualData->bugStatusStat   = "Bug狀態分佈";
$lang->report->annualData->caseResultStat  = "用例結果分佈";
$lang->report->annualData->allStory        = "總需求";
$lang->report->annualData->allTask         = "總任務";
$lang->report->annualData->allBug          = "總Bug";
$lang->report->annualData->undone          = "未完成";
$lang->report->annualData->unresolve       = "未解決";

$lang->report->annualData->storyMonthActions = "每月需求操作情況";
$lang->report->annualData->taskMonthActions  = "每月任務操作情況";
$lang->report->annualData->bugMonthActions   = "每月Bug操作情況";
$lang->report->annualData->caseMonthActions  = "每月用例操作情況";

$lang->report->annualData->executionFields['name']  = "{$lang->executionCommon}名稱";
$lang->report->annualData->executionFields['story'] = "完成{$lang->SRCommon}數";
$lang->report->annualData->executionFields['task']  = "完成任務數";
$lang->report->annualData->executionFields['bug']   = "解決Bug數";

$lang->report->annualData->productFields['name'] = "{$lang->productCommon}名稱";
$lang->report->annualData->productFields['plan'] = "計劃數";
global $config;
if(!empty($config->URAndSR))
{
    $lang->report->annualData->productFields['requirement'] = "創建{$lang->URCommon}數";
}
$lang->report->annualData->productFields['story']  = "創建{$lang->SRCommon}數";
$lang->report->annualData->productFields['closed'] = "關閉需求數";

$lang->report->annualData->objectTypeList['product']     = $lang->productCommon;
$lang->report->annualData->objectTypeList['story']       = "需求";
$lang->report->annualData->objectTypeList['productplan'] = "計劃";
$lang->report->annualData->objectTypeList['release']     = "發佈";
$lang->report->annualData->objectTypeList['execution']   = $lang->executionCommon;
$lang->report->annualData->objectTypeList['task']        = '任務';
$lang->report->annualData->objectTypeList['repo']        = '代碼';
$lang->report->annualData->objectTypeList['bug']         = 'Bug';
$lang->report->annualData->objectTypeList['build']       = '版本';
$lang->report->annualData->objectTypeList['testtask']    = '測試單';
$lang->report->annualData->objectTypeList['case']        = '用例';
$lang->report->annualData->objectTypeList['doc']         = '文檔';

$lang->report->annualData->actionList['create']    = '創建';
$lang->report->annualData->actionList['edit']      = '編輯';
$lang->report->annualData->actionList['close']     = '關閉';
$lang->report->annualData->actionList['review']    = '評審';
$lang->report->annualData->actionList['gitCommit'] = 'GIT提交';
$lang->report->annualData->actionList['svnCommit'] = 'SVN提交';
$lang->report->annualData->actionList['start']     = '開始';
$lang->report->annualData->actionList['finish']    = '完成';
$lang->report->annualData->actionList['assign']    = '指派';
$lang->report->annualData->actionList['activate']  = '激活';
$lang->report->annualData->actionList['resolve']   = '解決';
$lang->report->annualData->actionList['run']       = '執行';
$lang->report->annualData->actionList['stop']      = '停止維護';
$lang->report->annualData->actionList['putoff']    = '延期';
$lang->report->annualData->actionList['suspend']   = '掛起';
$lang->report->annualData->actionList['change']    = '變更';
$lang->report->annualData->actionList['pause']     = '暫停';
$lang->report->annualData->actionList['cancel']    = '取消';
$lang->report->annualData->actionList['confirm']   = '確認';
$lang->report->annualData->actionList['createBug'] = '轉Bug';

$lang->report->annualData->todoStatus['all']    = '所有待辦';
$lang->report->annualData->todoStatus['undone'] = '未完成';
$lang->report->annualData->todoStatus['done']   = '已完成';

$lang->report->annualData->radarItems['product']   = '產品管理';
$lang->report->annualData->radarItems['execution'] = '項目管理';
$lang->report->annualData->radarItems['devel']     = "研發";
$lang->report->annualData->radarItems['qa']        = "測試";
$lang->report->annualData->radarItems['other']     = "其他";

$lang->report->companyRadar        = "公司能力雷達圖";
$lang->report->outputData          = "產出數據";
$lang->report->outputTotal         = "產出總數";
$lang->report->storyOutput         = "需求產出";
$lang->report->planOutput          = "計劃產出";
$lang->report->releaseOutput       = "發佈產出";
$lang->report->executionOutput     = "執行產出";
$lang->report->taskOutput          = "任務產出";
$lang->report->bugOutput           = "Bug產出";
$lang->report->caseOutput          = "用例產出";
$lang->report->bugProgress         = "Bug進展";
$lang->report->productProgress     = "產品進展";
$lang->report->executionProgress   = "執行進展";
$lang->report->projectProgress     = "項目進展";
$lang->report->yearProjectOverview = "年度項目總覽";
$lang->report->projectOverview     = "截止目前項目總覽";
