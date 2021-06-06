<?php
/**
 * The report module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     report
 * @version     $Id: en.php 5080 2013-07-10 00:46:59Z wyd621@gmail.com $
 * @link        https://www.zentao.pm
 */
$lang->report->index     = 'Accueil Rapports';
$lang->report->list      = 'Rapports';
$lang->report->item      = 'Item';
$lang->report->value     = 'Valeur';
$lang->report->percent   = '%';
$lang->report->undefined = 'N/D';
$lang->report->query     = 'Requête';
$lang->report->annual    = 'Résumé Annuel';
$lang->report->project   = 'Project';

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

$lang->report->assign['noassign'] = 'Non affecté';
$lang->report->assign['assign']   = 'Affecté';

$lang->report->singleColor[] = 'F6BD0F';

$lang->report->projectDeviation = 'Rapport de déviation du projet';
$lang->report->productSummary   = 'Résumé ' . $lang->productCommon;
$lang->report->bugCreate        = 'Résumé remontée Bugs';
$lang->report->bugAssign        = 'Résumé affectation Bugs';
$lang->report->workload         = 'Résumé charge travail';
$lang->report->workloadAB       = 'Charge Travail';
$lang->report->bugOpenedDate    = 'Bugs signalés de';
$lang->report->beginAndEnd      = ' de';
$lang->report->begin            = 'Begin';
$lang->report->end              = 'End';
$lang->report->dept             = 'Compartiment';
$lang->report->deviationChart   = 'Courbe de déviation du projet';

$lang->reportList = new stdclass();
$lang->reportList->project = new stdclass();
$lang->reportList->product = new stdclass();
$lang->reportList->test    = new stdclass();
$lang->reportList->staff   = new stdclass();

$lang->reportList->project->lists[10] = 'Dériv Effectué|report|projectdeviation';
$lang->reportList->product->lists[10] = 'Résumé ' . $lang->productCommon . '|report|productsummary';
$lang->reportList->test->lists[10]    = 'Résumé remontée Bugs|report|bugcreate';
$lang->reportList->test->lists[13]    = 'Résumé affectation Bugs|report|bugassign';
$lang->reportList->staff->lists[10]   = 'Résumé charge travail|report|workload';

$lang->report->id            = 'ID';
$lang->report->execution     = $lang->executionCommon;
$lang->report->product       = $lang->productCommon;
$lang->report->user          = 'Utilisateur';
$lang->report->bugTotal      = 'Bugs';
$lang->report->task          = 'Tâches';
$lang->report->estimate      = 'Estimé';
$lang->report->consumed      = 'Coût';
$lang->report->remain        = 'Reste';
$lang->report->deviation     = 'Dérive';
$lang->report->deviationRate = 'Taux Dérive';
$lang->report->total         = 'Total';
$lang->report->to            = 'à';
$lang->report->taskTotal     = "Tâches Totales";
$lang->report->manhourTotal  = "Heures Totales";
$lang->report->validRate     = "Taux Validation";
$lang->report->validRateTips = "Résolution est Résolu/Reporté ou statut est Résolu/Fermé.";
$lang->report->unplanned     = 'Non planifié';
$lang->report->workday       = 'Heures/Jour';
$lang->report->diffDays      = 'jours';

$lang->report->typeList['default'] = 'Défaut';
$lang->report->typeList['pie']     = 'Camenbert';
$lang->report->typeList['bar']     = 'Barres';
$lang->report->typeList['line']    = 'Lignes';

$lang->report->conditions    = 'Filtré par :';
$lang->report->closedProduct = $lang->productCommon . 's Fermés';
$lang->report->overduePlan   = 'Plans Echus';

/* daily reminder. */
$lang->report->idAB         = 'ID';
$lang->report->bugTitle     = 'Nom Bug';
$lang->report->taskName     = 'Nom Tâche';
$lang->report->todoName     = 'Nom Agenda';
$lang->report->testTaskName = 'Nom Recette';
$lang->report->deadline     = 'Date Butoir';

$lang->report->mailTitle           = new stdclass();
$lang->report->mailTitle->begin    = 'Note : Vous avez';
$lang->report->mailTitle->bug      = " Bug (%s),";
$lang->report->mailTitle->task     = " Tâches (%s),";
$lang->report->mailTitle->todo     = " Agenda (%s),";
$lang->report->mailTitle->testTask = " Recettes (%s),";

$lang->report->proVersion   = '<a href="https://www.zentao.pm/page/vs.html" target="_blank">Essayez ZenTao Pro pour en savoir plus !</a>';
$lang->report->proVersionEn = '<a href="https://www.zentao.pm/page/vs.html" target="_blank">Try ZenTao Pro for more!</a>';

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
$lang->report->annualData->productFields['story']    = "Created stories";
$lang->report->annualData->productFields['finished'] = "Finished stories";

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
$lang->report->annualData->actionList['change']    = 'Changed';
$lang->report->annualData->actionList['pause']     = 'Paused';
$lang->report->annualData->actionList['cancel']    = 'Canceled';
$lang->report->annualData->actionList['confirm']   = 'Confirmed';
$lang->report->annualData->actionList['createBug'] = 'Turn to bug';

$lang->report->annualData->todoStatus['all']    = 'All';
$lang->report->annualData->todoStatus['undone'] = 'Undone';
$lang->report->annualData->todoStatus['done']   = 'Done';

$lang->report->annualData->radarItems['product']   = "Product";
$lang->report->annualData->radarItems['execution'] = "Project";
$lang->report->annualData->radarItems['devel']     = "Development";
$lang->report->annualData->radarItems['qa']        = "QA";
$lang->report->annualData->radarItems['other']     = "Other";
