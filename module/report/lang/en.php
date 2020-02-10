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
$lang->report->index      = 'Report Home';
$lang->report->list       = 'Report';
$lang->report->item       = 'Item';
$lang->report->value      = 'Value';
$lang->report->percent    = '%';
$lang->report->undefined  = 'Undefined';
$lang->report->query      = 'Query';
$lang->report->annual     = 'Annual Summary';

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

$lang->report->assign['noassign'] = 'Unassigned';
$lang->report->assign['assign'] = 'Assigned';

$lang->report->singleColor[] = 'F6BD0F';

$lang->report->projectDeviation = $lang->projectCommon . ' Deviation';
$lang->report->productSummary   = $lang->productCommon . ' Summary';
$lang->report->bugCreate        = 'Bug Reported Summary';
$lang->report->bugAssign        = 'Bug Assigned Summary';
$lang->report->workload         = 'Team Workload Summary';
$lang->report->workloadAB       = 'Workload';
$lang->report->bugOpenedDate    = 'Bug reported from';
$lang->report->beginAndEnd      = ' From';
$lang->report->dept             = 'Department';
$lang->report->deviationChart   = $lang->projectCommon . ' Deviation Chart';

$lang->reportList->project->lists[10] = $lang->projectCommon . ' Deviation|report|projectdeviation';
$lang->reportList->product->lists[10] = $lang->productCommon . ' Summary|report|productsummary';
$lang->reportList->test->lists[10]    = 'Bug Reported Summary|report|bugcreate';
$lang->reportList->test->lists[13]    = 'Bug Assigned Summary|report|bugassign';
$lang->reportList->staff->lists[10]   = 'Team Workload Summary|report|workload';

$lang->report->id            = 'ID';
$lang->report->project       = $lang->projectCommon;
$lang->report->product       = $lang->productCommon;
$lang->report->user          = 'User';
$lang->report->bugTotal      = 'Bug';
$lang->report->task          = 'Task';
$lang->report->estimate      = 'Estimates';
$lang->report->consumed      = 'Cost';
$lang->report->remain        = 'Left';
$lang->report->deviation     = 'Deviation';
$lang->report->deviationRate = 'Deviation Rate';
$lang->report->total         = 'Total';
$lang->report->to            = 'to';
$lang->report->taskTotal     = "Total Tasks";
$lang->report->manhourTotal  = "Total Hours";
$lang->report->validRate     = "Valid Rate";
$lang->report->validRateTips = "Resolution is Resolved/Postponed or status is Resolved/Closed.";
$lang->report->unplanned     = 'Unplanned';
$lang->report->workday       = 'Hours/Day';
$lang->report->diffDays      = 'days';

$lang->report->typeList['default'] = 'Default';
$lang->report->typeList['pie']     = 'Pie';
$lang->report->typeList['bar']     = 'Bar';
$lang->report->typeList['line']    = 'Line';

$lang->report->conditions    = 'Filter by:';
$lang->report->closedProduct = 'Closed ' . $lang->productCommon . 's';
$lang->report->overduePlan   = 'Expired Plans';

/* daily reminder. */
$lang->report->idAB         = 'ID';
$lang->report->bugTitle     = 'Bug Name';
$lang->report->taskName     = 'Task Name';
$lang->report->todoName     = 'Todo Name';
$lang->report->testTaskName = 'Request Name';
$lang->report->deadline     = 'Deadline';

$lang->report->mailTitle           = new stdclass();
$lang->report->mailTitle->begin    = 'Notice: You have';
$lang->report->mailTitle->bug      = " Bug (%s),";
$lang->report->mailTitle->task     = " Task (%s),";
$lang->report->mailTitle->todo     = " Todo (%s),";
$lang->report->mailTitle->testTask = " Request (%s),";

$lang->report->proVersion   = '<a href="https://api.zentao.net/goto.php?item=proversion&from=reportpage" target="_blank">Try ZenTao Pro for more!</a>';
$lang->report->proVersionEn = '<a href="http://api.zentao.pm/goto.php?item=proversion&from=reportpage" target="_blank">Try ZenTao Pro for more!</a>';

$lang->report->annualData = new stdclass();
$lang->report->annualData->title            = "%s Work Summary —— %s";
$lang->report->annualData->baseInfo         = "Basic Information";
$lang->report->annualData->logins           = "Login";
$lang->report->annualData->actions          = "Action";
$lang->report->annualData->efforts          = "Effort";
$lang->report->annualData->consumed         = "Hour Cost";
$lang->report->annualData->foundBugs        = "Bug Reported";
$lang->report->annualData->createdCases     = "Case Created";
$lang->report->annualData->involvedProducts = "{$lang->productCommon} Involved";
$lang->report->annualData->createdPlans     = "Plan Created";
$lang->report->annualData->createdStories   = "{$lang->storyCommon} Created";

$lang->report->annualData->productOverview = "{$lang->productCommon} {$lang->storyCommon} Count and Percent";
$lang->report->annualData->qaOverview      = "{$lang->productCommon} Bug Count and Percent";
$lang->report->annualData->projectOverview = "{$lang->projectCommon} Overview";
$lang->report->annualData->doneProject     = "Done {$lang->projectCommon}";
$lang->report->annualData->doingProject    = "Ongoing {$lang->projectCommon}";
$lang->report->annualData->suspendProject  = "Suspended {$lang->projectCommon}";

$lang->report->annualData->projectName   = "{$lang->projectCommon}";
$lang->report->annualData->finishedStory = "{$lang->storyCommon} Finished";
$lang->report->annualData->finishedTask  = 'Task Finished';
$lang->report->annualData->foundBug      = 'Bug Reported';
$lang->report->annualData->resolvedBug   = 'Bug Resolved';
$lang->report->annualData->productName   = "{$lang->productCommon}";
$lang->report->annualData->planCount     = 'Plan';
$lang->report->annualData->storyCount    = "{$lang->storyCommon}";

$lang->report->annualData->qaData           = "Bug Reported and Case Created";
$lang->report->annualData->totalCreatedBug  = 'Bug Reported';
$lang->report->annualData->totalCreatedCase = 'Case Created';

$lang->report->annualData->devData           = "Task Finished and Bug Resolved";
$lang->report->annualData->totalFinishedTask = 'Task Finished';
$lang->report->annualData->totalResolvedBug  = 'Bug Resolved';
$lang->report->annualData->totalConsumed     = 'Hour Cost';

$lang->report->annualData->poData          = "{$lang->storyCommon} Created, Priority and Status";
$lang->report->annualData->totalStoryPri   = "{$lang->storyCommon} Priority";
$lang->report->annualData->totalStoryStage = "{$lang->storyCommon} Stage";

$lang->report->annualData->qaStatistics  = "Monthly Created Bug and Case";
$lang->report->annualData->poStatistics  = "Monthly Created {$lang->storyCommon}";
$lang->report->annualData->devStatistics = "Monthly Finished Task, Hour, and Resolved Bug";

$lang->report->annualData->unit = " ";
