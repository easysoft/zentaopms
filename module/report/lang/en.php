<?php
/**
 * The report module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     report
 * @version     $Id: en.php 5080 2013-07-10 00:46:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->report->common     = 'Report';
$lang->report->index      = 'Home';
$lang->report->list       = 'List';
$lang->report->item       = 'Item';
$lang->report->value      = 'Value';
$lang->report->percent    = '%';
$lang->report->undefined  = 'Undefined';

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

$lang->report->projectDeviation = $lang->projectCommon . ' Deviation';
$lang->report->productSummary   = $lang->productCommon . ' Summary';
$lang->report->bugCreate        = 'Bugs';
$lang->report->bugAssign        = 'Bug Assignment';
$lang->report->workload         = 'Workload';
$lang->report->workloadAB       = 'Workload';
$lang->report->bugOpenedDate    = 'Bug Open on';
$lang->report->taskAssignedDate = 'Time Frame';
$lang->report->beginAndEnd      = 'Date';
$lang->report->dept             = 'Dept';

$lang->reportList->project->lists[10] = $lang->projectCommon . ' deviation|report|projectdeviation';
$lang->reportList->product->lists[10] = $lang->productCommon . ' summary|report|productsummary';
$lang->reportList->test->lists[10]    = 'Bugs|report|bugcreate';
$lang->reportList->test->lists[13]    = 'Bug Assignment|report|bugassign';
$lang->reportList->staff->lists[10]   = 'Workload|report|workload';

$lang->report->id            = 'ID';
$lang->report->project       = $lang->projectCommon;
$lang->report->product       = $lang->productCommon;
$lang->report->user          = 'Username';
$lang->report->bug           = 'Bugs';
$lang->report->task          = 'Tasks';
$lang->report->estimate      = 'Est.';
$lang->report->consumed      = 'Consumed';
$lang->report->remain        = 'Remained';
$lang->report->deviation     = 'Deviation';
$lang->report->deviationRate = 'Deviation Rate';
$lang->report->total         = 'Total';
$lang->report->to            = 'to';
$lang->report->taskTotal     = "Total Tasks";
$lang->report->manhourTotal  = "Total Man-Hour";
$lang->report->validRate     = "Efficiency";
$lang->report->validRateTips = "Solution is fixed or postponed / status is resolved or closed.";
$lang->report->unplanned     = 'Unplanned';
$lang->report->workday       = 'Man-Hour/Day';
$lang->report->diffDays      = 'Work Days';

$lang->report->conditions    = 'Filter by:';
$lang->report->closedProduct = 'Closed ' . $lang->productCommon;
$lang->report->overduePlan   = 'Overdue Plan';

/* daily reminder. */
$lang->report->idAB  = 'ID';
$lang->report->bugTitle     = 'Bug Title';
$lang->report->taskName     = 'Task Name';
$lang->report->todoName     = 'To-Do Name';
$lang->report->testTaskName = 'Test Task Name';

$lang->report->mailTitle           = new stdclass();
$lang->report->mailTitle->begin    = 'Notice: You have';
$lang->report->mailTitle->bug      = " Bug(%s),";
$lang->report->mailTitle->task     = " Task(%s),";
$lang->report->mailTitle->todo     = " To-Do(%s),";
$lang->report->mailTitle->testTask = " Test Task(%s),";

$lang->report->proVersion = '<a href="http://api.zentao.net/goto.php?item=proversion&from=reportpage" target="_blank">Try ZenTao Pro for more!</a>';
