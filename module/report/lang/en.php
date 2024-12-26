<?php
/**
 * The report module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     report
 * @version     $Id: en.php 5080 2013-07-10 00:46:59Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->report->index     = 'Report Home';
$lang->report->list      = 'Pivot Table';
$lang->report->item      = 'Item';
$lang->report->value     = 'Value';
$lang->report->percent   = '%';
$lang->report->undefined = 'Undefined';
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

$lang->report->assign['noassign'] = 'Unassigned';
$lang->report->assign['assign']   = 'Assigned';

$lang->report->singleColor[] = 'F6BD0F';

$lang->report->projectDeviation = "{$lang->execution->common} Deviation";
$lang->report->productSummary   = $lang->productCommon . ' Summary';
$lang->report->bugCreate        = 'Bug Reported Summary';
$lang->report->bugAssign        = 'Bug Assigned Summary';
$lang->report->workload         = 'Team Workload Summary';
$lang->report->workloadAB       = 'Workload';
$lang->report->bugOpenedDate    = 'Bug reported from';
$lang->report->beginAndEnd      = ' From';
$lang->report->begin            = ' Begin';
$lang->report->end              = ' End';
$lang->report->dept             = 'Department';
$lang->report->deviationChart   = "{$lang->projectCommon} Deviation Chart";

$lang->report->id            = 'ID';
$lang->report->execution     = $lang->execution->common;
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

$lang->report->annualData = new stdclass();
$lang->report->annualData->title            = "%s work summary in %s";
$lang->report->annualData->exportByZentao   = "Export By ZenTao";
$lang->report->annualData->scope            = "Scope";
$lang->report->annualData->allUser          = "All Users";
$lang->report->annualData->allDept          = "Whole Company";
$lang->report->annualData->soFar            = " (%s)";
$lang->report->annualData->baseInfo         = "Basic Data";
$lang->report->annualData->actionData       = "Operation Data";
$lang->report->annualData->contributionData = "Contribution Data";
$lang->report->annualData->radar            = "Capability Radar Chart";
$lang->report->annualData->executions       = "{$lang->executionCommon} Data";
$lang->report->annualData->products         = "{$lang->productCommon} Data";
$lang->report->annualData->stories          = "Story Data";
$lang->report->annualData->tasks            = "Task Data";
$lang->report->annualData->bugs             = "Bug Data";
$lang->report->annualData->cases            = "Case Data";
$lang->report->annualData->statusStat       = "{$lang->SRCommon}/task/bug status distribution (as of today)";

$lang->report->annualData->companyUsers     = "Number of company";
$lang->report->annualData->deptUsers        = "Number of departments";
$lang->report->annualData->logins           = "Login times";
$lang->report->annualData->actions          = "Number of operations";
$lang->report->annualData->contributions    = "Number of contributions";
$lang->report->annualData->consumed         = "Consumed";
$lang->report->annualData->todos            = "Number of Todos";

$lang->report->annualData->storyStatusStat = "Story status distribution";
$lang->report->annualData->taskStatusStat  = "Task status distribution";
$lang->report->annualData->bugStatusStat   = "Bug status distribution";
$lang->report->annualData->caseResultStat  = "Case result distribution";
$lang->report->annualData->allStory        = "Total";
$lang->report->annualData->allTask         = "Total";
$lang->report->annualData->allBug          = "Total";
$lang->report->annualData->undone          = "Undone";
$lang->report->annualData->unresolve       = "Unresolve";

$lang->report->annualData->storyMonthActions = "Monthly story operation";
$lang->report->annualData->taskMonthActions  = "Monthly task operation";
$lang->report->annualData->bugMonthActions   = "Monthly bug operation";
$lang->report->annualData->caseMonthActions  = "Monthly case operation";

$lang->report->annualData->executionFields['name']  = "{$lang->executionCommon} name";
$lang->report->annualData->executionFields['story'] = "Finished stories";
$lang->report->annualData->executionFields['task']  = "Finished tasks";
$lang->report->annualData->executionFields['bug']   = "Repaired bugs";

$lang->report->annualData->productFields['name'] = "{$lang->productCommon} name";
$lang->report->annualData->productFields['plan'] = "Plans";
$lang->report->annualData->productFields['epic'] = "Created {$lang->ERCommon}";
global $config;
if(!empty($config->URAndSR))
{
    $lang->report->annualData->productFields['requirement'] = "Created {$lang->URCommon}";
}
$lang->report->annualData->productFields['story']  = "Created {$lang->SRCommon}";
$lang->report->annualData->productFields['closed'] = "Closed {$lang->SRCommon}";

$lang->report->annualData->objectTypeList['product']     = $lang->productCommon;
$lang->report->annualData->objectTypeList['story']       = $lang->SRCommon;
$lang->report->annualData->objectTypeList['productplan'] = "Plan";
$lang->report->annualData->objectTypeList['release']     = "Release";
$lang->report->annualData->objectTypeList['project']     = $lang->projectCommon;
$lang->report->annualData->objectTypeList['execution']   = $lang->executionCommon;
$lang->report->annualData->objectTypeList['task']        = 'Task';
$lang->report->annualData->objectTypeList['repo']        = 'Code';
$lang->report->annualData->objectTypeList['bug']         = 'Bug';
$lang->report->annualData->objectTypeList['build']       = 'Build';
$lang->report->annualData->objectTypeList['testtask']    = 'TestTask';
$lang->report->annualData->objectTypeList['case']        = 'Case';
$lang->report->annualData->objectTypeList['doc']         = 'Document';

$lang->report->annualData->actionList['create']    = 'Created';
$lang->report->annualData->actionList['edit']      = 'Edited';
$lang->report->annualData->actionList['close']     = 'Closed';
$lang->report->annualData->actionList['review']    = 'Reviewed';
$lang->report->annualData->actionList['gitCommit'] = 'GIT Commited';
$lang->report->annualData->actionList['svnCommit'] = 'SVN Commited';
$lang->report->annualData->actionList['start']     = 'Started';
$lang->report->annualData->actionList['finish']    = 'Finished';
$lang->report->annualData->actionList['assign']    = 'Assigned';
$lang->report->annualData->actionList['activate']  = 'Activated';
$lang->report->annualData->actionList['resolve']   = 'Resolved';
$lang->report->annualData->actionList['run']       = 'Execute';
$lang->report->annualData->actionList['stop']      = 'Stop Maintenance';
$lang->report->annualData->actionList['putoff']    = 'Postponed ';
$lang->report->annualData->actionList['suspend']   = 'Suspended';
$lang->report->annualData->actionList['change']    = 'Changed';
$lang->report->annualData->actionList['pause']     = 'Paused';
$lang->report->annualData->actionList['cancel']    = 'Canceled';
$lang->report->annualData->actionList['confirm']   = 'Confirmed';
$lang->report->annualData->actionList['createBug'] = 'Turn to bug';
$lang->report->annualData->actionList['delete']    = 'Delete';
$lang->report->annualData->actionList['toAudit']   = 'To Audit';
$lang->report->annualData->actionList['audit']     = 'Audit';

$lang->report->annualData->todoStatus['all']    = 'All';
$lang->report->annualData->todoStatus['undone'] = 'Undone';
$lang->report->annualData->todoStatus['done']   = 'Done';

$lang->report->annualData->radarItems['product']   = $lang->productCommon;
$lang->report->annualData->radarItems['execution'] = $lang->projectCommon;
$lang->report->annualData->radarItems['devel']     = "Development";
$lang->report->annualData->radarItems['qa']        = "QA";
$lang->report->annualData->radarItems['other']     = "Other";

$lang->report->companyRadar        = "公司能力雷达图";
$lang->report->outputData          = "产出数据";
$lang->report->outputTotal         = "产出总数";
$lang->report->storyOutput         = "需求产出";
$lang->report->planOutput          = "计划产出";
$lang->report->releaseOutput       = "发布产出";
$lang->report->executionOutput     = "执行产出";
$lang->report->taskOutput          = "任务产出";
$lang->report->bugOutput           = "Bug产出";
$lang->report->caseOutput          = "用例产出";
$lang->report->bugProgress         = "Bug进展";
$lang->report->productProgress     = "{$lang->productCommon}进展";
$lang->report->executionProgress   = "执行进展";
$lang->report->projectProgress     = "{$lang->projectCommon}进展";
$lang->report->yearProjectOverview = "年度{$lang->projectCommon}总览";
$lang->report->projectOverview     = "截止目前{$lang->projectCommon}总览";

$lang->report->contributionCountObject = array();
$lang->report->contributionCountObject['task']        = "Tasks: Create, Complete, Close, Cancel, Assign";
$lang->report->contributionCountObject['story']       = "Stories: Create, Review, Close, Assign";
$lang->report->contributionCountObject['requirement'] = "User Requirements: Create, Review, Close, Assign";
$lang->report->contributionCountObject['epic']        = "Epics: Create, Review, Close, Assign";
$lang->report->contributionCountObject['bug']         = "Bugs: Create, Solve, Close, Assign";
$lang->report->contributionCountObject['testcase']    = "Testcases: Create";
$lang->report->contributionCountObject['testtask']    = "Testtasks: Closed";
$lang->report->contributionCountObject['audit']       = "Audit: Initiate, Audit";
$lang->report->contributionCountObject['doc']         = "Document: Create, Edit";
$lang->report->contributionCountObject['issue']       = "Issue: Create, Close, Assign";
$lang->report->contributionCountObject['risk']        = "Risk: Create, Close, Assign";
$lang->report->contributionCountObject['qa']          = "QA: Create, Solve, Close, Assign";
$lang->report->contributionCountObject['feedback']    = "Feedback: Create, Review, Assign, Close";
$lang->report->contributionCountObject['ticket']      = "Tickets: Create, Solve, Assign, Close";

$lang->report->tips = new stdclass();
$lang->report->tips->basic = array();
$lang->report->tips->basic['company'] = '
1.Number of company: Sum the number of all users in the system and filter the deleted users. <br>
2.Number of operations: Sum the number of operations performed by the system in a given year. <br>
3.Consumed: Sum the time consumed by the system in a given year. <br>
4.Number of Todos: Sum the todo for all users of the system. <br>
5.Number of contributions: Sum the contributions of all users of the system.';
$lang->report->tips->basic['dept'] = '
1.Number of departments: Sum the number of all users in a department and filter the deleted users. <br>
2.Number of operations: Sum the number of operations performed by users in a department in a given year. <br>
3.Consumed: Sum the working hours consumed by a department user in a given year. <br>
4.Number of Todos: Sum the todo for users in a department. <br>
5.Number of contributions: Sum the contribution data of users in a department.';
$lang->report->tips->basic['user'] = '
1.Login times: Sum the login times of a user in a given year. <br>
2.Number of operations: Sum the number of operations performed by a user in a given year. <br>
3.Consumed: Sum the hours consumed by a user in a given year. <br>
4.Number of Todos: Sum the todo for a user. <br>
5.Number of contributions: Sum the contribution data for a user.';

$lang->report->tips->contributionCount['company'] = "Contribution data of all user in the selected year, including:";
$lang->report->tips->contributionCount['dept']    = "Contribution data of the selected department in the selected year, including:";
$lang->report->tips->contributionCount['user']    = "Contribution data of the selected user in the selected year, including:";

$lang->report->tips->contribute['company'] = 'Sum the number of operations on different system objects in a given year.';
$lang->report->tips->contribute['dept']    = 'Sum the number of operations performed on different system objects in a particular year. The operation user must belong to the selected department.';
$lang->report->tips->contribute['user']    = 'Sum the number of operations performed on different system objects in a particular year. Ensure that the operation user belongs to the selected user.';

$lang->report->tips->radar = '
1.Product management includes: product, plan, requirements, release related operational data.<br>
2.Project management includes: project, iteration, version, task related operational data.<br>
3.Development includes: tasks, code, Bug resolution related operational data.<br>
4.Tests include: Bug creation, Bug activation, Bug closure, use cases, and test single related operational data.<br>
5.Other includes: other scattered dynamic data.';

$lang->report->tips->execution['company'] = '
Finished stories: The sum of the number of ongoing stories created in {year} requires the status of R&D requirements to be closed, the reason for closure to be completed, and filters the deleted R&D requirements.<br>
Finished tasks: Sum the number of ongoing tasks created in {year}. The status is completed. Filters the deleted tasks.<br>
Resolved bugs: The number of bugs created in {year} whose execution status is closed and the solution is resolved.';
$lang->report->tips->execution['dept'] = '
Finished stories: The sum of the number of stories created in {year} requires that the status of R&D requirements is closed, the shutdown reason is completed, the deleted R&D requirements are filtered, and the creator is a selected department user.<br>
Finished tasks: Sum the number of tasks created in {year} that are in the process of execution. The status is completed, the deleted tasks are filtered out, and the created tasks are selected department users.Number of Completed tasks: Sum the number of tasks created in {year} that are in the process of execution. The status is completed, the deleted tasks are filtered out, and the created tasks are selected department users.<br>
Resolved bugs: Number of bugs created in {year} whose execution status is closed and the solution is resolved. The creator is a selected department user.';
$lang->report->tips->execution['user'] = '
Finished stories: The sum of the number of ongoing stories created in {year} requires that the status of the R&D requirements is closed, the reason for the shutdown is completed, filters the deleted R&D requirements, and the creator is a selected user.<br>
Finished tasks: Sum the number of ongoing tasks created in {year}. The status is completed. The deleted tasks are filtered and the creator is a selected user.<br>
Resolved bugs: The number of bugs created in {year} whose execution status is closed and the solution is resolved, and the creator is a selected user.';

$lang->report->tips->product['company'] = '
Plans: The number of plans created in a product in a given year.<br>
Created epics: Specifies the number of epics created in a particular year.<br>
Created requirements: Specifies the number of user requirements created in a particular year.<br>
Created stories: The number of stories in a product created in a given year.<br>
Closed stories: The number of stories in a product with a shutdown time in a given year.';
$lang->report->tips->product['dept'] = '
Plans：The number of plans created in a product in a given year. The creator is a user in the selected department.<br>
Created epics: Specifies the number of epics created in a particular year. The creator is a user in the selected department.<br>
Created requirements: Specifies the number of user requirements created in a particular year. The creator is a user in the selected department.<br>
Created stories: The number of stories in a product created in a given year. The creator is a user in the selected department.<br>
Closed stories: The number of stories needs in a product with a shutdown time in a given year, and the shutdown is for users in the selected department.';
$lang->report->tips->product['user'] = '
Plans: Number of plans created in the product in a certain year. The creator is the selected user.<br>
Create epics：Specifies the number of epics created in a particular year. The creator is the selected user.<br>
Create requirment：Specifies the number of user requirements created in a particular year. The creator is the selected user.<br>
Created stories: The number of stories created in a product in a given year. The creator is the selected user.<br>
Closed stories: The number of stories in the product that were closed in a given year, and the shutdown is the selected user.';

$lang->report->tips->story['company'] = '
Story status distribution: Story data distribution in different states. The creation time must be a certain year.<br>
Monthly story operations: Sum the number of story operations. The required operation time is a certain year.';
$lang->report->tips->story['dept'] = '
Story status distribution: Indicates the story data distribution in different states. The creation time must be a year, and the created user must be a user in the selected department.<br>
Monthly story operations: Sum the number of story operations. The operation time is a year and the operation user is a user in the selected department.';
$lang->report->tips->story['user'] = '
Story status distribution: Indicates the story data distribution in different states. The creation time is a year, and the created user is the selected user.<br>
Monthly story operations: Sum the number of story operations. The operation time is a year and the operation user is the selected user.';

$lang->report->tips->bug['company'] = '
Bug status distribution: Distribution of Bug data in different states. The creation time must be a certain year.<br>
Monthly Bug operations: Sum the number of Bug operations. The operation time must be a year.';
$lang->report->tips->bug['dept'] = '
Bug status distribution: Distribution of Bug data in different states. The creation time must be a year, and the created user must be a user in the selected department.<br>
Monthly Bug operations: Sum the number of Bug operations. The operation time is a year and the operation user is a user in the selected department.';
$lang->report->tips->bug['user'] = '
Bug status distribution: Distribution of Bug data in different states. The creation time must be a year, and the user to be created is the selected user.<br>
Monthly Bug operations: Sum the number of Bug operations. The operation time is a year and the operation user is the selected user.';

$lang->report->tips->case['company'] = '
Case result distribution: The distribution of use case data for different execution results is required to be created in a certain year.<br>
Monthly case operations: Sum the number of operations of the use case. The operation time must be a certain year.';
$lang->report->tips->case['dept'] = '
Case status distribution: Use case data distribution with different execution results. The creation time must be a year and the run user must be a user in the selected department.<br>
Monthly case operations: Sum the operation times of the use case. The operation time must be a year and the operation user must be a user in the selected department.';
$lang->report->tips->case['user'] = '
Case status distribution: Use case data distribution with different execution results. The creation time must be a certain year, and the run user is the selected user.<br>
Monthly case operations: Sum the number of operations of a use case. The operation time must be a year and the operation user is the selected user.';

$lang->report->tips->task['company'] = '
Task status distribution: Task data in different states must be created in a certain year.<br>
Monthly task operations: Sum the number of tasks performed in a year.';
$lang->report->tips->task['dept'] = '
Task status distribution: Task data in different states is distributed. The creation time must be a year and the created user must be a user in the selected department.<br>
Monthly operation information: Sum the operation times of a task. The operation time must be a year and the operation user must be a user in the selected department.';
$lang->report->tips->task['user'] = '
Task status distribution: Task data in different states is distributed. The creation time must be a year and the created user is the selected user.<br>
Monthly task operations: Sum the number of operations performed on a task. The operation time is a year and the operation user is the selected user.';
