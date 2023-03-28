<?php
/**
 * The pivot module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     pivot
 * @version     $Id: en.php 5080 2013-07-10 00:46:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->pivot->index     = 'Pivot Home';
$lang->pivot->list      = 'Pivot Table';
$lang->pivot->preview   = 'View Pivot Table';
$lang->pivot->item      = 'Item';
$lang->pivot->value     = 'Value';
$lang->pivot->percent   = '%';
$lang->pivot->undefined = 'Undefined';
$lang->pivot->query     = 'Query';
$lang->pivot->project   = $lang->projectCommon;
$lang->pivot->PO        = 'PO';

$lang->pivot->colors[] = 'AFD8F8';
$lang->pivot->colors[] = 'F6BD0F';
$lang->pivot->colors[] = '8BBA00';
$lang->pivot->colors[] = 'FF8E46';
$lang->pivot->colors[] = '008E8E';
$lang->pivot->colors[] = 'D64646';
$lang->pivot->colors[] = '8E468E';
$lang->pivot->colors[] = '588526';
$lang->pivot->colors[] = 'B3AA00';
$lang->pivot->colors[] = '008ED6';
$lang->pivot->colors[] = '9D080D';
$lang->pivot->colors[] = 'A186BE';

$lang->pivot->assign['noassign'] = 'Unassigned';
$lang->pivot->assign['assign']   = 'Assigned';

$lang->pivot->singleColor[] = 'F6BD0F';

$lang->pivot->projectDeviation = "{$lang->execution->common} Deviation";
$lang->pivot->productSummary   = $lang->productCommon . ' Summary';
$lang->pivot->bugCreate        = 'Bug Reported Summary';
$lang->pivot->bugAssign        = 'Bug Assigned Summary';
$lang->pivot->workload         = 'Team Workload Summary';
$lang->pivot->workloadAB       = 'Workload';
$lang->pivot->bugOpenedDate    = 'Bug pivoted from';
$lang->pivot->beginAndEnd      = ' From';
$lang->pivot->begin            = ' Begin';
$lang->pivot->end              = ' End';
$lang->pivot->dept             = 'Department';
$lang->pivot->deviationChart   = "{$lang->projectCommon} Deviation Chart";

$lang->pivotList = new stdclass();
$lang->pivotList->product = new stdclass();
$lang->pivotList->project = new stdclass();
$lang->pivotList->test    = new stdclass();
$lang->pivotList->staff   = new stdclass();

$lang->pivotList->product->lists[10] = $lang->productCommon . ' Summary|pivot|productsummary';
$lang->pivotList->project->lists[10] = "{$lang->execution->common} Deviation|pivot|projectdeviation";
$lang->pivotList->test->lists[10]    = 'Bug Reported Summary|pivot|bugcreate';
$lang->pivotList->test->lists[13]    = 'Bug Assigned Summary|pivot|bugassign';
$lang->pivotList->staff->lists[10]   = 'Team Workload Summary|pivot|workload';

$lang->pivot->id            = 'ID';
$lang->pivot->execution     = $lang->execution->common;
$lang->pivot->product       = $lang->productCommon;
$lang->pivot->user          = 'User';
$lang->pivot->bugTotal      = 'Bug';
$lang->pivot->task          = 'Task';
$lang->pivot->estimate      = 'Estimates';
$lang->pivot->consumed      = 'Cost';
$lang->pivot->remain        = 'Left';
$lang->pivot->deviation     = 'Deviation';
$lang->pivot->deviationRate = 'Deviation Rate';
$lang->pivot->total         = 'Total';
$lang->pivot->to            = 'to';
$lang->pivot->taskTotal     = "Total Tasks";
$lang->pivot->manhourTotal  = "Total Hours";
$lang->pivot->validRate     = "Valid Rate";
$lang->pivot->validRateTips = "Resolution is Resolved/Postponed or status is Resolved/Closed.";
$lang->pivot->unplanned     = 'Unplanned';
$lang->pivot->workday       = 'Hours/Day';
$lang->pivot->diffDays      = 'days';

$lang->pivot->typeList['default'] = 'Default';
$lang->pivot->typeList['pie']     = 'Pie';
$lang->pivot->typeList['bar']     = 'Bar';
$lang->pivot->typeList['line']    = 'Line';

$lang->pivot->conditions    = 'Filter by:';
$lang->pivot->closedProduct = 'Closed ' . $lang->productCommon . 's';
$lang->pivot->overduePlan   = 'Expired Plans';

$lang->pivot->idAB         = 'ID';
$lang->pivot->bugTitle     = 'Bug Name';
$lang->pivot->taskName     = 'Task Name';
$lang->pivot->todoName     = 'Todo Name';
$lang->pivot->testTaskName = 'Request Name';
$lang->pivot->deadline     = 'Deadline';

$lang->pivot->deviationDesc = 'According to the Closed Execution Deviation Rate = ((Total Cost - Total Estimate) / Total Estimate), the Deviation Rate is n/a when the Total Estimate is 0.';
$lang->pivot->proVersion    = '<a href="https://www.zentao.net/page/enterprise.html" target="_blank">Try ZenTao Biz for more!</a>';
$lang->pivot->proVersionEn  = '<a href="https://www.zentao.pm/" target="_blank">Try ZenTao Biz for more!</a>';
$lang->pivot->workloadDesc  = 'Workload = the total left hours of all tasks of the user / selected days * hours per day.
For example: the begin and end date is January 1st to January 7th, and the total work days is 5 days, 8 hours per day. The Work load is all unfinished tasks assigned to this user to be finished in 5 days, 8 hours per day.';
