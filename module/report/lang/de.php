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
$lang->report->index     = 'Home';
$lang->report->list      = 'Liste';
$lang->report->item      = 'Eintrag';
$lang->report->value     = 'Wert';
$lang->report->percent   = '%';
$lang->report->undefined = 'Undefiniert';
$lang->report->project   = $lang->projectCommon;
$lang->report->PO        = 'PO';

$lang->report->colors[] = 'AFD8F8';
$lang->report->colors[] = 'AFD8F8';
$lang->report->colors[] = 'AFD8F8';
$lang->report->colors[] = 'AFD8F8';
$lang->report->colors[] = 'AFD8F8';
$lang->report->colors[] = 'AFD8F8';
$lang->report->colors[] = 'AFD8F8';
$lang->report->colors[] = 'AFD8F8';
$lang->report->colors[] = 'AFD8F8';
$lang->report->colors[] = 'AFD8F8';
$lang->report->colors[] = 'AFD8F8';
$lang->report->colors[] = 'AFD8F8';

$lang->report->assign['noassign'] = 'Nicht zugeordnet';
$lang->report->assign['assign']   = 'Zugeordnet';

$lang->report->singleColor[] = 'F6BD0F';

$lang->report->projectDeviation = "{$lang->execution->common}abweichungsbericht";
$lang->report->productSummary   = $lang->productCommon . ' Zusammenfassung';
$lang->report->bugCreate        = 'Bug gemeldet';
$lang->report->bugAssign        = 'Bug zugeordnet';
$lang->report->workload         = 'Team Arbeitslast ';
$lang->report->workloadAB       = 'Arbeitslast';
$lang->report->bugOpenedDate    = 'Bug gemeldet am';
$lang->report->beginAndEnd      = ' : von';
$lang->report->begin            = 'Begin';
$lang->report->end              = 'End';
$lang->report->dept             = 'Abteilung';
$lang->report->deviationChart   = "{$lang->projectCommon} Deviation Chart";

$lang->report->id            = 'ID';
$lang->report->execution     = $lang->executionCommon;
$lang->report->product       = $lang->productCommon;
$lang->report->user          = 'Name';
$lang->report->bugTotal      = 'Bug';
$lang->report->task          = 'Aufgabe';
$lang->report->estimate      = 'Schätzung(h)';
$lang->report->consumed      = 'Verbraucht';
$lang->report->remain        = 'Rest';
$lang->report->deviation     = 'Abweichung';
$lang->report->deviationRate = 'Abweichungsrate';
$lang->report->total         = 'Summe';
$lang->report->to            = 'bis';
$lang->report->taskTotal     = "Summe Aufgaben";
$lang->report->manhourTotal  = "Summe Stunden";
$lang->report->validRate     = "Gültigkeitsrate";
$lang->report->validRateTips = "Lösung ist gelöst/verschoben oder der Status ist gelöst/geschlossen.";
$lang->report->unplanned     = 'Ungeplant';
$lang->report->workday       = 'Stunden/Tag';
$lang->report->diffDays      = 'Manntage';

$lang->report->typeList['default'] = 'Standard';
$lang->report->typeList['pie']     = 'Torte';
$lang->report->typeList['bar']     = 'Balken';
$lang->report->typeList['line']    = 'Linien';

$lang->report->conditions    = 'Filter:';
$lang->report->closedProduct = 'Geschlossen ' . $lang->productCommon;
$lang->report->overduePlan   = 'Fälligkeit Plan';

/* daily reminder. */
$lang->report->idAB         = 'ID';
$lang->report->bugTitle     = 'Bug-Name';
$lang->report->taskName     = 'Aufgabe-Name';
$lang->report->todoName     = 'Todo-Name';
$lang->report->testTaskName = 'Testaufgabe-Name';
$lang->report->deadline     = 'Fällig';

$lang->report->mailTitle           = new stdclass();
$lang->report->mailTitle->begin    = 'Hinweis: Sie haben';
$lang->report->mailTitle->bug      = " Bug (%s),";
$lang->report->mailTitle->task     = " Aufgaben (%s),";
$lang->report->mailTitle->todo     = " Todo (%s),";
$lang->report->mailTitle->testTask = " Test Aufgaben (%s),";

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
$lang->report->annualData->executionFields['bug']   = "Resolved bugs";

$lang->report->annualData->productFields['name'] = "{$lang->productCommon} name";
$lang->report->annualData->productFields['plan'] = "Plans";
global $config;
if(!empty($config->URAndSR))
{
    $lang->report->annualData->productFields['requirement'] = "Created requirements";
}
$lang->report->annualData->productFields['story']  = "Created stories";
$lang->report->annualData->productFields['closed'] = "Closed stories";

$lang->report->annualData->objectTypeList['product']     = $lang->productCommon;
$lang->report->annualData->objectTypeList['story']       = $lang->SRCommon;
$lang->report->annualData->objectTypeList['productplan'] = "Plan";
$lang->report->annualData->objectTypeList['release']     = "Release";
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
