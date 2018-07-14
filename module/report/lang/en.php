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
$lang->report->list       = 'Report List';
$lang->report->item       = 'Item';
$lang->report->value      = 'Value';
$lang->report->percent    = '%';
$lang->report->undefined  = 'Undefined';
$lang->report->query      = 'Query';

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

$lang->report->assign['noassign'] = 'Not assigned';
$lang->report->assign['assign'] = 'Assigned';

$lang->report->singleColor[] = 'F6BD0F';

$lang->report->projectDeviation = $lang->projectCommon . ' Deviation';
$lang->report->productSummary   = $lang->productCommon . ' Summary';
$lang->report->bugCreate        = 'Bug Reported Statement';
$lang->report->bugAssign        = 'Bug Assigned Statement';
$lang->report->workload         = 'Team Workload Statement';
$lang->report->workloadAB       = 'Workload Statement';
$lang->report->bugOpenedDate    = 'Bug reported from';
$lang->report->taskAssignedDate = 'From';
$lang->report->beginAndEnd      = ' : from';
$lang->report->dept             = 'Dept';
$lang->report->deviationChart   = $lang->projectCommon . ' Deviation Chart';

$lang->reportList->project->lists[10] = $lang->projectCommon . ' Deviation|report|projectdeviation';
$lang->reportList->product->lists[10] = $lang->productCommon . ' Summary|report|productsummary';
$lang->reportList->test->lists[10]    = 'Bugs Reported Statement|report|bugcreate';
$lang->reportList->test->lists[13]    = 'Bugs Assigned Statement|report|bugassign';
$lang->reportList->staff->lists[10]   = 'Team Workload Statement|report|workload';

$lang->report->id            = 'ID';
$lang->report->project       = $lang->projectCommon;
$lang->report->product       = $lang->productCommon;
$lang->report->user          = 'Name';
$lang->report->bugTotal      = 'Bug';
$lang->report->task          = 'Task';
$lang->report->estimate      = 'Est.(h)';
$lang->report->consumed      = 'Cost';
$lang->report->remain        = 'Left';
$lang->report->deviation     = 'Deviation';
$lang->report->deviationRate = 'Deviation Rate';
$lang->report->total         = 'Total';
$lang->report->to            = 'to';
$lang->report->taskTotal     = "Total Tasks";
$lang->report->manhourTotal  = "Total Hours";
$lang->report->validRate     = "Valid Rate";
$lang->report->validRateTips = "Solution is fixed/postponed or status is resolved/closed.";
$lang->report->unplanned     = 'Unplanned';
$lang->report->workday       = 'Hours/Day';
$lang->report->diffDays      = 'Man-Day';

$lang->report->typeList['']     = 'Default';
$lang->report->typeList['pie']  = 'Pie';
$lang->report->typeList['bar']  = 'Bar';
$lang->report->typeList['line'] = 'Line';

$lang->report->conditions    = 'Filter by:';
$lang->report->closedProduct = 'Closed ' . $lang->productCommon;
$lang->report->overduePlan   = 'Expired Plan';

/* daily reminder. */
$lang->report->idAB  = 'ID';
$lang->report->bugTitle     = 'Bug Name';
$lang->report->taskName     = 'Task Name';
$lang->report->todoName     = 'Todo Name';
$lang->report->testTaskName = 'TestTask Name';
$lang->report->deadline     = 'Deadline';

$lang->report->mailTitle           = new stdclass();
$lang->report->mailTitle->begin    = 'Notice: You have';
$lang->report->mailTitle->bug      = " Bug (%s),";
$lang->report->mailTitle->task     = " Task (%s),";
$lang->report->mailTitle->todo     = " Todo (%s),";
$lang->report->mailTitle->testTask = " Test Task (%s),";

$lang->report->proVersion   = '<a href="http://api.zentao.net/goto.php?item=proversion&from=reportpage" target="_blank">Try ZenTao Pro for more!</a>';
$lang->report->proVersionEn = '<a href="http://api.zentao.pm/goto.php?item=proversion&from=reportpage" target="_blank">Try ZenTao Pro for more!</a>';
