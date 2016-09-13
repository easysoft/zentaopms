<?php
/**
 * The report module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     report
 * @version     $Id: zh-tw.php 5080 2013-07-10 00:46:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->report->common     = '統計視圖';
$lang->report->index      = '統計首頁';
$lang->report->list       = '統計報表';
$lang->report->item       = '條目';
$lang->report->value      = '值';
$lang->report->percent    = '百分比';
$lang->report->undefined  = '未設定';

$lang->report->colors[]   = 'AFD8F8';
$lang->report->colors[]   = 'F6BD0F';
$lang->report->colors[]   = '8BBA00';
$lang->report->colors[]   = 'FF8E46';
$lang->report->colors[]   = '008E8E';
$lang->report->colors[]   = 'D64646';
$lang->report->colors[]   = '8E468E';
$lang->report->colors[]   = '588526';
$lang->report->colors[]   = 'B3AA00';
$lang->report->colors[]   = '008ED6';
$lang->report->colors[]   = '9D080D';
$lang->report->colors[]   = 'A186BE';

$lang->report->singleColor[] = 'F6BD0F';

$lang->report->projectDeviation = $lang->projectCommon . '偏差報表';
$lang->report->productSummary   = $lang->productCommon . '彙總表';
$lang->report->bugCreate        = 'Bug創建表';
$lang->report->bugAssign        = 'Bug指派表';
$lang->report->workload         = '員工負載表';
$lang->report->workloadAB       = '工作負載';
$lang->report->bugOpenedDate    = 'Bug創建時間';
$lang->report->taskAssignedDate = '起止時間';
$lang->report->beginAndEnd      = '起止時間';
$lang->report->dept             = '部門';

$lang->reportList->project->lists[10] = $lang->projectCommon . '偏差報表|report|projectdeviation';
$lang->reportList->product->lists[10] = $lang->productCommon . '彙總表|report|productsummary';
$lang->reportList->test->lists[10]    = 'Bug創建表|report|bugcreate';
$lang->reportList->test->lists[13]    = 'Bug指派表|report|bugassign';
$lang->reportList->staff->lists[10]   = '員工負載表|report|workload';

$lang->report->id            = '編號';
$lang->report->project       = $lang->projectCommon;
$lang->report->product       = $lang->productCommon;
$lang->report->user          = '姓名';
$lang->report->bug           = 'Bug';
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
$lang->report->unplanned     = '未計劃';
$lang->report->workday       = '每天工時';
$lang->report->diffDays      = '工作日天數';

$lang->report->conditions    = '篩選條件：';
$lang->report->closedProduct = '關閉' . $lang->productCommon;
$lang->report->overduePlan   = '過期計劃';

/* daily reminder. */
$lang->report->idAB         = 'ID';
$lang->report->bugTitle     = 'Bug標題';
$lang->report->taskName     = '任務名稱';
$lang->report->todoName     = '待辦名稱';
$lang->report->testTaskName = '版本名稱';

$lang->report->mailTitle           = new stdclass();
$lang->report->mailTitle->begin    = '提醒：您有';
$lang->report->mailTitle->bug      = " Bug(%s),";
$lang->report->mailTitle->task     = " 任務(%s),";
$lang->report->mailTitle->todo     = " 待辦(%s),";
$lang->report->mailTitle->testTask = " 測試版本(%s),";

$lang->report->proVersion = '<a href="http://api.zentao.net/goto.php?item=proversion&from=reportpage" target="_blank">更多精彩，盡在專業版！</a>';
