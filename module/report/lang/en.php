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
$lang->report->annualData->title            = "%s年工作内容统计一览表 —— %s";
$lang->report->annualData->baseInfo         = "基本数据信息";
$lang->report->annualData->logins           = "累计登录次数";
$lang->report->annualData->actions          = "累计动态数";
$lang->report->annualData->efforts          = "累计日志数";
$lang->report->annualData->consumed         = "累计工时数";
$lang->report->annualData->foundBugs        = "累计发现Bug数";
$lang->report->annualData->createdCases     = "累计创建用例数";
$lang->report->annualData->involvedProducts = "累计参与{$lang->productCommon}数";
$lang->report->annualData->createdPlans     = "累计创建计划数";
$lang->report->annualData->createdStories   = "累计创建{$lang->storyCommon}数";

$lang->report->annualData->productOverview = "{$lang->productCommon}创建的{$lang->storyCommon}数及占比";
$lang->report->annualData->qaOverview      = "{$lang->productCommon}创建的Bug数及占比";
$lang->report->annualData->projectOverview = "参与{$lang->projectCommon}概览";
$lang->report->annualData->doneProject     = "已完成的{$lang->projectCommon}";
$lang->report->annualData->doingProject    = "正在进行的{$lang->projectCommon}";
$lang->report->annualData->suspendProject  = "已挂起的{$lang->projectCommon}";

$lang->report->annualData->projectName   = "{$lang->projectCommon}名称";
$lang->report->annualData->finishedStory = "完成{$lang->storyCommon}数";
$lang->report->annualData->finishedTask  = '完成任务数';
$lang->report->annualData->foundBug      = '发现Bug数';
$lang->report->annualData->resolvedBug   = '解决Bug数';
$lang->report->annualData->productName   = "{$lang->productCommon}名称";
$lang->report->annualData->planCount     = '计划数';
$lang->report->annualData->storyCount    = "{$lang->storyCommon}数";

$lang->report->annualData->qaData           = "累计发现的Bug数和创建的用例数";
$lang->report->annualData->totalCreatedBug  = '累计发现的Bug数';
$lang->report->annualData->totalCreatedCase = '累计创建的用例数';

$lang->report->annualData->devData           = "完成的任务数和解决的Bug数";
$lang->report->annualData->totalFinishedTask = '完成的任务数';
$lang->report->annualData->totalResolvedBug  = '解决的Bug数';
$lang->report->annualData->totalConsumed     = '累计工时';

$lang->report->annualData->poData          = "所创建的{$lang->storyCommon}数对应的优先级及状态";
$lang->report->annualData->totalStoryPri   = "创建{$lang->storyCommon}优先级分布";
$lang->report->annualData->totalStoryStage = "创建{$lang->storyCommon}阶段分布";

$lang->report->annualData->qaStatistics  = "月发现的Bug数和创建的用例数";
$lang->report->annualData->poStatistics  = "月创建{$lang->storyCommon}数";
$lang->report->annualData->devStatistics = "月完成任务数及累计工时和解决的Bug数";

$lang->report->annualData->unit = "个";
