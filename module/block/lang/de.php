<?php
/**
 * The en file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
global $config;
$lang->block = new stdclass();
$lang->block->common     = 'InfoBlock';
$lang->block->id         = 'ID';
$lang->block->params     = 'Params';
$lang->block->name       = 'Name';
$lang->block->style      = 'Style';
$lang->block->grid       = 'Gitter';
$lang->block->color      = 'Farbe';
$lang->block->reset      = 'Zurücksetzen';
$lang->block->story      = 'Story';
$lang->block->investment = 'Investment';
$lang->block->estimate   = 'Estimate';
$lang->block->last       = 'Last';

$lang->block->account = 'Konto';
$lang->block->module  = 'Modul';
$lang->block->title   = 'Titel';
$lang->block->source  = 'Source module';
$lang->block->block   = 'Source block';
$lang->block->order   = 'Sortierung';
$lang->block->height  = 'Höhe';
$lang->block->role    = 'Rolle';

$lang->block->lblModule    = 'Modul';
$lang->block->lblBlock     = 'InfoBlock';
$lang->block->lblNum       = 'Nummer';
$lang->block->lblHtml      = 'HTML';
$lang->block->dynamic      = 'Verlauf';
$lang->block->assignToMe   = 'Todo';
$lang->block->wait         = 'Wait';
$lang->block->doing        = 'Doing';
$lang->block->done         = 'Done';
$lang->block->lblFlowchart = 'Workflow';
$lang->block->welcome      = 'Willkommen';
$lang->block->lblTesttask  = 'Test Request Detail';
$lang->block->contribute   = 'Personal Contribution';
$lang->block->finish       = 'Finish';

$lang->block->leftToday           = 'Arbeit für Heute';
$lang->block->myTask              = 'Aufgaben';
$lang->block->myStory             = 'Story';
$lang->block->myBug               = 'Bugs';
$lang->block->myExecution         = 'Unclosed ' . $lang->executionCommon . 's';
$lang->block->myProduct           = 'Unclosed ' . $lang->productCommon . 's';
$lang->block->delayed             = 'Verspätet';
$lang->block->noData              = 'Keine Daten für diesen Bericht.';
$lang->block->emptyTip            = 'No Data.';
$lang->block->createdTodos        = 'Todos Created';
$lang->block->createdRequirements = 'UR/Epics Created';
$lang->block->createdStories      = 'SR/Stories Created';
$lang->block->finishedTasks       = 'Tasks Finished';
$lang->block->createdBugs         = 'Bugs Created';
$lang->block->resolvedBugs        = 'Bugs Resolved';
$lang->block->createdCases        = 'Cases Created';
$lang->block->createdRisks        = 'Risks Created';
$lang->block->resolvedRisks       = 'Risks Resolved';
$lang->block->createdIssues       = 'Issues Created';
$lang->block->resolvedIssues      = 'Issues Resolved';
$lang->block->createdDocs         = 'Docs Created';
$lang->block->allExecutions       = 'All ' . $lang->executionCommon;
$lang->block->doingExecution      = 'Doning ' . $lang->executionCommon;
$lang->block->finishExecution     = 'Finish ' . $lang->executionCommon;
$lang->block->estimatedHours      = 'Estimated';
$lang->block->consumedHours       = 'Cost';
$lang->block->time                = 'No';
$lang->block->week                = 'Week';
$lang->block->month               = 'Month';
$lang->block->selectProduct       = 'Product selection';
$lang->block->of                  = ' von ';
$lang->block->remain              = 'Left';
$lang->block->allStories          = 'All';

$lang->block->createBlock        = 'InfoBlock erstellen';
$lang->block->editBlock          = 'Bearbeiten';
$lang->block->ordersSaved        = 'Sortierung gespeichert.';
$lang->block->confirmRemoveBlock = 'Möchten Sie den Block löschen?';
$lang->block->noticeNewBlock     = 'ZenTao 10.+ hat neue Layouts für jeden View. Möchten Sie die neue Ansicht nutzen?';
$lang->block->confirmReset       = 'Möchten Sie das Dashboard zurücksetzen?';
$lang->block->closeForever       = 'Dauerhaft schließen';
$lang->block->confirmClose       = 'Möchten Sie den Block dauerhaft schlißen? Nach der Ausführung ist der Block nicht mehr verfügbar. Er kann unter Admin->Custom aber wieder aktiviert werden.';
$lang->block->remove             = 'Entfernen';
$lang->block->refresh            = 'Aktualisieren';
$lang->block->nbsp               = '';
$lang->block->hidden             = 'Verstecken';
$lang->block->dynamicInfo        = "<span class='timeline-tag'>%s</span> <span class='timeline-text'>%s<em class='label-action'>%s</em>%s<a href='%s' title='%s'>%s</a></span>";
$lang->block->noLinkDynamic      = "<span class='timeline-tag'>%s</span> <span class='timeline-text' title='%s'>%s<em class='label-action'>%s</em>%s<span class='label-name'>%s</span></span>";
$lang->block->cannotPlaceInLeft  = 'Kann den Block nicht auf der linken Seite platzieren.';
$lang->block->cannotPlaceInRight = 'Kann den Block nicht auf der rechten Seite platzieren.';

$lang->block->productName  = $lang->productCommon . ' Name';
$lang->block->totalStory   = 'Total Story';
$lang->block->totalBug     = 'Total Bug';
$lang->block->totalRelease = 'Release The Number';
$lang->block->totalTask    = 'The Total ' . $lang->task->common;

$lang->block->totalInvestment = 'Total investment';
$lang->block->totalPeople     = 'Total';
$lang->block->spent           = 'Has Been Spent';
$lang->block->budget          = 'Budget';
$lang->block->left            = 'Remain';

$lang->block->titleList['flowchart']      = 'Flow Chart';
$lang->block->titleList['statistic']      = 'Statistic';
$lang->block->titleList['recentproject']  = 'Recent Project';
$lang->block->titleList['assigntome']     = 'Assign to me';
$lang->block->titleList['projectteam']    = 'Project manpower input';
$lang->block->titleList['project']        = 'Project List';
$lang->block->titleList['dynamic']        = 'Dynamic';
$lang->block->titleList['list']           = 'Todo List';
$lang->block->titleList['contribute']     = 'Contribute';
$lang->block->titleList['scrumoverview']  = 'Scrumoverview';
$lang->block->titleList['scrumtest']      = 'Scrum Test';
$lang->block->titleList['scrumlist']      = 'Scrum List';
$lang->block->titleList['sprint']         = 'Sprint';
$lang->block->titleList['projectdynamic'] = 'Project Dynamic';
$lang->block->titleList['bug']            = 'Bug';
$lang->block->titleList['case']           = 'Case';
$lang->block->titleList['testtask']       = 'Test Task';

$lang->block->default['waterfall']['project']['3']['title']  = 'Plan Gantt Chart';
$lang->block->default['waterfall']['project']['3']['block']  = 'waterfallgantt';
$lang->block->default['waterfall']['project']['3']['source'] = 'project';
$lang->block->default['waterfall']['project']['3']['grid']   = 8;

$lang->block->default['waterfall']['project']['6']['title']  = 'Dynamic';
$lang->block->default['waterfall']['project']['6']['block']  = 'projectdynamic';
$lang->block->default['waterfall']['project']['6']['grid']   = 4;
$lang->block->default['waterfall']['project']['6']['source'] = 'project';

$lang->block->default['scrum']['project']['1']['title'] =  'Project Overall';
$lang->block->default['scrum']['project']['1']['block'] = 'scrumoverview';
$lang->block->default['scrum']['project']['1']['grid']  = 8;

$lang->block->default['scrum']['project']['2']['title'] = $lang->executionCommon . ' List';
$lang->block->default['scrum']['project']['2']['block'] = 'scrumlist';
$lang->block->default['scrum']['project']['2']['grid']  = 8;

$lang->block->default['scrum']['project']['2']['params']['type']    = 'undone';
$lang->block->default['scrum']['project']['2']['params']['count']   = '20';
$lang->block->default['scrum']['project']['2']['params']['orderBy'] = 'id_desc';

$lang->block->default['scrum']['project']['3']['title'] = 'Test Version';
$lang->block->default['scrum']['project']['3']['block'] = 'scrumtest';
$lang->block->default['scrum']['project']['3']['grid']  = 8;

$lang->block->default['scrum']['project']['3']['params']['type']    = 'wait';
$lang->block->default['scrum']['project']['3']['params']['count']   = '15';
$lang->block->default['scrum']['project']['3']['params']['orderBy'] = 'id_desc';

$lang->block->default['scrum']['project']['4']['title'] = $lang->executionCommon . ' Overview';
$lang->block->default['scrum']['project']['4']['block'] = 'sprint';
$lang->block->default['scrum']['project']['4']['grid']  = 4;

$lang->block->default['scrum']['project']['5']['title'] = 'Dynamic';
$lang->block->default['scrum']['project']['5']['block'] = 'projectdynamic';
$lang->block->default['scrum']['project']['5']['grid']  = 4;
$lang->block->default['kanban'] = $lang->block->default['scrum'];

$lang->block->default['product']['1']['title'] = $lang->productCommon . ' Berichte';
$lang->block->default['product']['1']['block'] = 'statistic';
$lang->block->default['product']['1']['grid']  = 8;

$lang->block->default['product']['1']['params']['type']  = 'all';
$lang->block->default['product']['1']['params']['count'] = '20';

$lang->block->default['product']['2']['title'] = $lang->productCommon . ' Übersicht';
$lang->block->default['product']['2']['block'] = 'overview';
$lang->block->default['product']['2']['grid']  = 4;

$lang->block->default['product']['3']['title'] = 'Offene ' . $lang->productCommon . 's';
$lang->block->default['product']['3']['block'] = 'list';
$lang->block->default['product']['3']['grid']  = 8;

$lang->block->default['product']['3']['params']['count'] = 15;
$lang->block->default['product']['3']['params']['type']  = 'noclosed';

$lang->block->default['product']['4']['title'] = 'Meine Story';
$lang->block->default['product']['4']['block'] = 'story';
$lang->block->default['product']['4']['grid']  = 4;

$lang->block->default['product']['4']['params']['count']   = 15;
$lang->block->default['product']['4']['params']['orderBy'] = 'id_desc';
$lang->block->default['product']['4']['params']['type']    = 'assignedTo';

$lang->block->default['execution']['1']['title'] = 'Execution Berichte';
$lang->block->default['execution']['1']['block'] = 'statistic';
$lang->block->default['execution']['1']['grid']  = 8;

$lang->block->default['execution']['1']['params']['type']  = 'all';
$lang->block->default['execution']['1']['params']['count'] = '20';

$lang->block->default['execution']['2']['title'] = 'Execution Übersicht';
$lang->block->default['execution']['2']['block'] = 'overview';
$lang->block->default['execution']['2']['grid']  = 4;

$lang->block->default['execution']['3']['title'] = 'Aktive Executions';
$lang->block->default['execution']['3']['block'] = 'list';
$lang->block->default['execution']['3']['grid']  = 8;

$lang->block->default['execution']['3']['params']['count']   = 15;
$lang->block->default['execution']['3']['params']['orderBy'] = 'id_desc';
$lang->block->default['execution']['3']['params']['type']    = 'undone';

$lang->block->default['execution']['4']['title'] = 'Meine Aufgaben';
$lang->block->default['execution']['4']['block'] = 'task';
$lang->block->default['execution']['4']['grid']  = 4;

$lang->block->default['execution']['4']['params']['count']   = 15;
$lang->block->default['execution']['4']['params']['orderBy'] = 'id_desc';
$lang->block->default['execution']['4']['params']['type']    = 'assignedTo';

$lang->block->default['execution']['5']['title'] = 'Build List';
$lang->block->default['execution']['5']['block'] = 'build';
$lang->block->default['execution']['5']['grid']  = 8;

$lang->block->default['execution']['5']['params']['count']   = 15;
$lang->block->default['execution']['5']['params']['orderBy'] = 'id_desc';

$lang->block->default['qa']['1']['title'] = 'Test Report';
$lang->block->default['qa']['1']['block'] = 'statistic';
$lang->block->default['qa']['1']['grid']  = 8;

$lang->block->default['qa']['1']['params']['type']  = 'noclosed';
$lang->block->default['qa']['1']['params']['count'] = '20';

//$lang->block->default['qa']['2']['title'] = 'Testcase Overview';
//$lang->block->default['qa']['2']['block'] = 'overview';
//$lang->block->default['qa']['2']['grid']  = 4;

$lang->block->default['qa']['2']['title'] = 'Testcase Overview';
$lang->block->default['qa']['2']['block'] = 'overview';
$lang->block->default['qa']['2']['grid']  = 4;

$lang->block->default['qa']['2']['params']['count']   = 15;
$lang->block->default['qa']['2']['params']['orderBy'] = 'id_desc';
$lang->block->default['qa']['2']['params']['type']    = 'assignedTo';

$lang->block->default['qa']['3']['title'] = 'My Case';
$lang->block->default['qa']['3']['block'] = 'case';
$lang->block->default['qa']['3']['grid']  = 4;

$lang->block->default['qa']['3']['params']['count']   = 15;
$lang->block->default['qa']['3']['params']['orderBy'] = 'id_desc';
$lang->block->default['qa']['3']['params']['type']    = 'assigntome';

$lang->block->default['qa']['4']['title'] = 'Ausstehende Builds';
$lang->block->default['qa']['4']['block'] = 'testtask';
$lang->block->default['qa']['4']['grid']  = 8;

$lang->block->default['qa']['4']['params']['count']   = 15;
$lang->block->default['qa']['4']['params']['orderBy'] = 'id_desc';
$lang->block->default['qa']['4']['params']['type']    = 'wait';

$lang->block->default['full']['my']['1']['title']  = 'Willkommen';
$lang->block->default['full']['my']['1']['block']  = 'welcome';
$lang->block->default['full']['my']['1']['grid']   = 8;
$lang->block->default['full']['my']['1']['source'] = '';

$lang->block->default['full']['my']['2']['title']  = 'Verlauf';
$lang->block->default['full']['my']['2']['block']  = 'dynamic';
$lang->block->default['full']['my']['2']['grid']   = 4;
$lang->block->default['full']['my']['2']['source'] = '';

$lang->block->default['full']['my']['3']['title']  = 'My Todos';
$lang->block->default['full']['my']['3']['block']  = 'list';
$lang->block->default['full']['my']['3']['source'] = 'todo';
$lang->block->default['full']['my']['3']['grid']   = 4;

$lang->block->default['full']['my']['4']['title']           = 'Project Statistic';
$lang->block->default['full']['my']['4']['block']           = 'statistic';
$lang->block->default['full']['my']['4']['grid']            = 8;
$lang->block->default['full']['my']['4']['source']          = 'project';
$lang->block->default['full']['my']['4']['params']['count'] = '20';

if($config->systemMode == 'new')
{
    $lang->block->default['full']['my']['5']['title']           = 'Personal Contribution';
    $lang->block->default['full']['my']['5']['block']           = 'contribute';
    $lang->block->default['full']['my']['5']['source']          = '';
    $lang->block->default['full']['my']['5']['grid']            = 4;
    $lang->block->default['full']['my']['5']['params']['count'] = '20';
}

$lang->block->default['full']['my']['6']['title']  = 'Recent Project';
$lang->block->default['full']['my']['6']['block']  = 'recentproject';
$lang->block->default['full']['my']['6']['source'] = 'project';
$lang->block->default['full']['my']['6']['grid']   = 8;

$lang->block->default['full']['my']['7']['title']  = 'Todo';
$lang->block->default['full']['my']['7']['block']  = 'assigntome';
$lang->block->default['full']['my']['7']['source'] = '';
$lang->block->default['full']['my']['7']['grid']   = 8;

$lang->block->default['full']['my']['8']['title']  = 'Human Input';
$lang->block->default['full']['my']['8']['block']  = 'projectteam';
$lang->block->default['full']['my']['8']['source'] = 'project';
$lang->block->default['full']['my']['8']['grid']   = 8;

$lang->block->default['full']['my']['8']['params']['todoCount']    = '20';
$lang->block->default['full']['my']['8']['params']['taskCount']    = '20';
$lang->block->default['full']['my']['8']['params']['bugCount']     = '20';
$lang->block->default['full']['my']['8']['params']['riskCount']    = '20';
$lang->block->default['full']['my']['8']['params']['issueCount']   = '20';
$lang->block->default['full']['my']['8']['params']['storyCount']   = '20';
$lang->block->default['full']['my']['8']['params']['meetingCount'] = '20';

if($config->systemMode == 'new')
{
    $lang->block->default['full']['my']['9']['title']  = 'Project List';
    $lang->block->default['full']['my']['9']['block']  = 'project';
    $lang->block->default['full']['my']['9']['source'] = 'project';
    $lang->block->default['full']['my']['9']['grid']   = 8;
}

$lang->block->default['full']['my']['10']['title']  = 'Project List';
$lang->block->default['full']['my']['10']['block']  = 'project';
$lang->block->default['full']['my']['10']['source'] = 'project';
$lang->block->default['full']['my']['10']['grid']   = 8;
if($config->systemMode == 'classic')
{
    $lang->block->default['full']['my']['10']['block']  = 'project';
    $lang->block->default['full']['my']['10']['source'] = 'project';
}

$lang->block->default['full']['my']['10']['params']['orderBy'] = 'id_desc';
$lang->block->default['full']['my']['10']['params']['count']   = '15';

$lang->block->count   = 'Count';
$lang->block->type    = 'Type';
$lang->block->orderBy = 'Order by';

$lang->block->availableBlocks              = new stdclass();
$lang->block->availableBlocks->todo        = 'Meine Zeitplan';
$lang->block->availableBlocks->task        = 'Meine Aufgaben';
$lang->block->availableBlocks->bug         = 'Meine Bugs';
$lang->block->availableBlocks->case        = 'Meine Fälle';
$lang->block->availableBlocks->story       = 'Meine Story';
$lang->block->availableBlocks->requirement = 'Requirements';
$lang->block->availableBlocks->product     = $lang->productCommon . 'Liste';
$lang->block->availableBlocks->execution   = $lang->executionCommon . 'Liste';
$lang->block->availableBlocks->plan        = 'Plan';
$lang->block->availableBlocks->release     = 'Release';
$lang->block->availableBlocks->build       = 'Build';
$lang->block->availableBlocks->testtask    = 'Testaufgaben';
$lang->block->availableBlocks->risk        = 'My Risks';
$lang->block->availableBlocks->issue       = 'My Issues';
$lang->block->availableBlocks->meeting     = 'My Meetings';
$lang->block->availableBlocks->feedback    = 'My Feedbacks';
$lang->block->availableBlocks->ticket      = 'Ticket';

if($config->systemMode == 'new') $lang->block->moduleList['project'] = 'Project';
$lang->block->moduleList['product']   = $lang->productCommon;
$lang->block->moduleList['execution'] = $lang->execution->common;
$lang->block->moduleList['qa']        = 'QA';
$lang->block->moduleList['todo']      = 'Todos';

$lang->block->modules['project'] = new stdclass();
$lang->block->modules['project']->availableBlocks = new stdclass();
$lang->block->modules['project']->availableBlocks->project       = 'Project List';
$lang->block->modules['project']->availableBlocks->recentproject = 'Recent Project';
$lang->block->modules['project']->availableBlocks->statistic     = 'Project Statistic';
if($config->systemMode == 'new') $lang->block->modules['project']->availableBlocks->projectteam = 'Project Human Input';

$lang->block->modules['scrum']['index'] = new stdclass();
$lang->block->modules['scrum']['index']->availableBlocks = new stdclass();
$lang->block->modules['scrum']['index']->availableBlocks->scrumoverview  = 'Project Overview';
$lang->block->modules['scrum']['index']->availableBlocks->scrumlist      = $lang->executionCommon . ' List';
$lang->block->modules['scrum']['index']->availableBlocks->sprint         = $lang->executionCommon . ' Overview';
$lang->block->modules['scrum']['index']->availableBlocks->scrumtest      = 'Test Version';
$lang->block->modules['scrum']['index']->availableBlocks->projectdynamic = 'Dynamics';

$lang->block->modules['waterfall']['index'] = new stdclass();
$lang->block->modules['waterfall']['index']->availableBlocks = new stdclass();
$lang->block->modules['waterfall']['index']->availableBlocks->waterfallgantt = 'Plan Gantt Chart';
$lang->block->modules['waterfall']['index']->availableBlocks->projectdynamic = 'Dynamics';

$lang->block->modules['product'] = new stdclass();
$lang->block->modules['product']->availableBlocks = new stdclass();
$lang->block->modules['product']->availableBlocks->statistic = $lang->productCommon . ' Berichte';
$lang->block->modules['product']->availableBlocks->overview  = $lang->productCommon . ' Übersicht';
$lang->block->modules['product']->availableBlocks->list      = $lang->productCommon . ' Liste';
$lang->block->modules['product']->availableBlocks->story     = 'Story';
$lang->block->modules['product']->availableBlocks->plan      = 'Plan';
$lang->block->modules['product']->availableBlocks->release   = 'Release';

$lang->block->modules['execution'] = new stdclass();
$lang->block->modules['execution']->availableBlocks = new stdclass();
$lang->block->modules['execution']->availableBlocks->statistic = $lang->execution->common . ' Statistik';
$lang->block->modules['execution']->availableBlocks->overview  = $lang->execution->common . ' Übersicht';
$lang->block->modules['execution']->availableBlocks->list      = $lang->execution->common . ' Liste';
$lang->block->modules['execution']->availableBlocks->task      = 'Aufgaben';
$lang->block->modules['execution']->availableBlocks->build     = 'Build';

$lang->block->modules['qa'] = new stdclass();
$lang->block->modules['qa']->availableBlocks = new stdclass();
$lang->block->modules['qa']->availableBlocks->statistic = 'Test Berichte';
//$lang->block->modules['qa']->availableBlocks->overview  = 'Testcase Overview';
$lang->block->modules['qa']->availableBlocks->bug      = 'Bugs';
$lang->block->modules['qa']->availableBlocks->case     = 'Fälle';
$lang->block->modules['qa']->availableBlocks->testtask = 'Testaufgaben';

$lang->block->modules['todo'] = new stdclass();
$lang->block->modules['todo']->availableBlocks = new stdclass();
$lang->block->modules['todo']->availableBlocks->list = 'Todos';

$lang->block->orderByList = new stdclass();

$lang->block->orderByList->product = array();
$lang->block->orderByList->product['id_asc']      = 'ID Aufsteigend';
$lang->block->orderByList->product['id_desc']     = 'ID Absteigend';
$lang->block->orderByList->product['status_asc']  = 'Status Aufsteigend';
$lang->block->orderByList->product['status_desc'] = 'Status Absteigend';

$lang->block->orderByList->project = array();
$lang->block->orderByList->project['id_asc']      = 'ID Aufsteigend';
$lang->block->orderByList->project['id_desc']     = 'ID Absteigend';
$lang->block->orderByList->project['status_asc']  = 'Status Aufsteigend';
$lang->block->orderByList->project['status_desc'] = 'Status Absteigend';

$lang->block->orderByList->execution = array();
$lang->block->orderByList->execution['id_asc']      = 'ID Aufsteigend';
$lang->block->orderByList->execution['id_desc']     = 'ID Absteigend';
$lang->block->orderByList->execution['status_asc']  = 'Status Aufsteigend';
$lang->block->orderByList->execution['status_desc'] = 'Status Absteigend';

$lang->block->orderByList->task = array();
$lang->block->orderByList->task['id_asc']        = 'ID Aufsteigend';
$lang->block->orderByList->task['id_desc']       = 'ID Absteigend';
$lang->block->orderByList->task['pri_asc']       = 'Prorität Aufsteigend';
$lang->block->orderByList->task['pri_desc']      = 'Prorität Absteigend';
$lang->block->orderByList->task['estimate_asc']  = 'Schätzung Aufsteigend';
$lang->block->orderByList->task['estimate_desc'] = 'Schätzung Absteigend';
$lang->block->orderByList->task['status_asc']    = 'Status Aufsteigend';
$lang->block->orderByList->task['status_desc']   = 'Status Absteigend';
$lang->block->orderByList->task['deadline_asc']  = 'Deadline Aufsteigend';
$lang->block->orderByList->task['deadline_desc'] = 'Deadline Absteigend';

$lang->block->orderByList->bug = array();
$lang->block->orderByList->bug['id_asc']        = 'ID Aufsteigend';
$lang->block->orderByList->bug['id_desc']       = 'ID Absteigend';
$lang->block->orderByList->bug['pri_asc']       = 'Priorität Aufsteigend';
$lang->block->orderByList->bug['pri_desc']      = 'Priorität Absteigend';
$lang->block->orderByList->bug['severity_asc']  = 'Dringlichkeit Aufsteigend';
$lang->block->orderByList->bug['severity_desc'] = 'Dringlichkeit Absteigend';

$lang->block->orderByList->case = array();
$lang->block->orderByList->case['id_asc']   = 'ID Aufsteigend';
$lang->block->orderByList->case['id_desc']  = 'ID Absteigend';
$lang->block->orderByList->case['pri_asc']  = 'Priorität Aufsteigend';
$lang->block->orderByList->case['pri_desc'] = 'Priorität Absteigend';

$lang->block->orderByList->story = array();
$lang->block->orderByList->story['id_asc']      = 'ID Aufsteigend';
$lang->block->orderByList->story['id_desc']     = 'ID Absteigend';
$lang->block->orderByList->story['pri_asc']     = 'Priorität Aufsteigend';
$lang->block->orderByList->story['pri_desc']    = 'Priorität Absteigend';
$lang->block->orderByList->story['status_asc']  = 'Status Aufsteigend';
$lang->block->orderByList->story['status_desc'] = 'Status Absteigend';
$lang->block->orderByList->story['stage_asc']   = 'Phase Aufsteigend';
$lang->block->orderByList->story['stage_desc']  = 'Phase Absteigend';

$lang->block->todoCount     = 'Todos';
$lang->block->taskCount     = 'Aufgaben';
$lang->block->bugCount      = 'Bugs';
$lang->block->riskCount     = 'Risk';
$lang->block->issueCount    = 'Issues';
$lang->block->storyCount    = 'Stories';
$lang->block->meetingCount  = 'Meetings';
$lang->block->feedbackCount = 'Feedbacks';
$lang->block->ticketCount   = 'Tickets';

$lang->block->typeList = new stdclass();

$lang->block->typeList->task['assignedTo'] = 'Mir zugewiesen';
$lang->block->typeList->task['openedBy']   = 'Von mir erstellt';
$lang->block->typeList->task['finishedBy'] = 'Von mir abgeschlossen';
$lang->block->typeList->task['closedBy']   = 'Von mir geschlossen';
$lang->block->typeList->task['canceledBy'] = 'Von mir abgebrochen';

$lang->block->typeList->bug['assignedTo'] = 'Mir zugewiesen';
$lang->block->typeList->bug['openedBy']   = 'Von mir erstellt';
$lang->block->typeList->bug['resolvedBy'] = 'Von mir gelöst';
$lang->block->typeList->bug['closedBy']   = 'Von mir geschlossen';

$lang->block->typeList->case['assigntome'] = 'Mir zugewiesen';
$lang->block->typeList->case['openedbyme'] = 'Von mir erstellt';

$lang->block->typeList->story['assignedTo'] = 'Mir zugewiesen';
$lang->block->typeList->story['openedBy']   = 'Von mir erstellt';
$lang->block->typeList->story['reviewedBy'] = 'Von mir überprüft';
$lang->block->typeList->story['closedBy']   = 'Von mir geschlossen' ;

$lang->block->typeList->product['noclosed'] = 'Offen';
$lang->block->typeList->product['closed']   = 'Geschlossen';
$lang->block->typeList->product['all']      = 'Alle';
$lang->block->typeList->product['involved'] = 'Beteiligt';

$lang->block->typeList->project['undone']   = 'Nicht abgeschlossen';
$lang->block->typeList->project['doing']    = 'In Arbeit';
$lang->block->typeList->project['all']      = 'Alle';
$lang->block->typeList->project['involved'] = 'Betieligt';

$lang->block->typeList->execution['undone']   = 'Unfinished';
$lang->block->typeList->execution['doing']    = 'Ongoing';
$lang->block->typeList->execution['all']      = 'All';
$lang->block->typeList->execution['involved'] = 'Involved';

$lang->block->typeList->scrum['undone']   = 'Unfinished';
$lang->block->typeList->scrum['doing']    = 'Ongoing';
$lang->block->typeList->scrum['all']      = 'All';
$lang->block->typeList->scrum['involved'] = 'Involved';

$lang->block->typeList->testtask['wait']    = 'Wartend';
$lang->block->typeList->testtask['doing']   = 'In Arbeit';
$lang->block->typeList->testtask['blocked'] = 'Blockiert';
$lang->block->typeList->testtask['done']    = 'Erledigt';
$lang->block->typeList->testtask['all']     = 'Alle';

$lang->block->modules['project']->moreLinkList = new stdclass();
$lang->block->modules['project']->moreLinkList->recentproject  = 'project|browse|';
$lang->block->modules['project']->moreLinkList->statistic      = 'project|browse|';
$lang->block->modules['project']->moreLinkList->project        = 'project|browse|';
$lang->block->modules['project']->moreLinkList->cmmireport     = 'weekly|index|';
$lang->block->modules['project']->moreLinkList->cmmiestimate   = 'workestimation|index|';
$lang->block->modules['project']->moreLinkList->cmmiissue      = 'issue|browse|';
$lang->block->modules['project']->moreLinkList->cmmirisk       = 'risk|browse|';
$lang->block->modules['project']->moreLinkList->scrumlist      = 'project|execution|';
$lang->block->modules['project']->moreLinkList->scrumtest      = 'testtask|browse|';
$lang->block->modules['project']->moreLinkList->scrumproduct   = 'product|all|';
$lang->block->modules['project']->moreLinkList->sprint         = 'project|execution|';
$lang->block->modules['project']->moreLinkList->projectdynamic = 'project|dynamic|';

$lang->block->modules['product']->moreLinkList        = new stdclass();
$lang->block->modules['product']->moreLinkList->list  = 'product|all|';
$lang->block->modules['product']->moreLinkList->story = 'my|story|type=%s';

$lang->block->modules['execution']->moreLinkList       = new stdclass();
$lang->block->modules['execution']->moreLinkList->list = 'execution|all|status=%s&executionID=';
$lang->block->modules['execution']->moreLinkList->task = 'my|task|type=%s';

$lang->block->modules['qa']->moreLinkList           = new stdclass();
$lang->block->modules['qa']->moreLinkList->bug      = 'my|bug|type=%s';
$lang->block->modules['qa']->moreLinkList->case     = 'my|testcase|type=%s';
$lang->block->modules['qa']->moreLinkList->testtask = 'testtask|browse|type=%s';

$lang->block->modules['todo']->moreLinkList       = new stdclass();
$lang->block->modules['todo']->moreLinkList->list = 'my|todo|type=all';

$lang->block->modules['common']                        = new stdclass();
$lang->block->modules['common']->moreLinkList          = new stdclass();
$lang->block->modules['common']->moreLinkList->dynamic = 'company|dynamic|';

$lang->block->welcomeList['06:00'] = 'Guten Morgen, %s';
$lang->block->welcomeList['11:30'] = 'Guten Tag, %s';
$lang->block->welcomeList['13:30'] = 'Guten Tag, %s';
$lang->block->welcomeList['19:00'] = 'Guten Abend, %s';

$lang->block->gridOptions[8] = 'Links';
$lang->block->gridOptions[4] = 'Rechts';

$lang->block->flowchart            = array();
$lang->block->flowchart['admin']   = array('Administrator', 'Abteilung erstellen', 'Benutzer erstellen', 'Rechte pflegen');
if($config->systemMode == 'new') $lang->block->flowchart['program'] = array('Program Owner', 'Create Program', 'Link Product', "Create Project", "Budgeting and planning", 'Add Stakeholder');
$lang->block->flowchart['product'] = array($lang->productCommon . ' Besitzer', $lang->productCommon . ' erstellen', 'Module pflegen', 'Pläne pflegen', 'Storys pflegen', 'Release erstellen');
$lang->block->flowchart['project'] = array('Project Manager', 'Add projects and ' . $lang->executionCommon . 's', 'Maintain Teams', 'Link Stories', 'Create Tasks', 'Track');
if($config->systemMode == 'new') $lang->block->flowchart['project'] = array('Project Manager', 'Add projects and ' . $lang->executionCommon . 's', 'Maintain Teams', 'Link Stories', 'Create Tasks', 'Track');
$lang->block->flowchart['dev']     = array('Entwickler', 'Aufgabe/Bugs anfordern', 'Update Status', 'Aufgaben/Bugs abschließen');
$lang->block->flowchart['tester']  = array('QS Team', 'Fälle erstellen', 'Fälle ausführen', 'Bug Berichte', 'Bugs überprüfen', 'Bugs schließen');

$lang->block->zentaoapp = new stdclass();
$lang->block->zentaoapp->thisYearInvestment   = 'Investment The Year';
$lang->block->zentaoapp->sinceTotalInvestment = 'Total Investment';
$lang->block->zentaoapp->myStory              = 'My Story';
$lang->block->zentaoapp->allStorySum          = 'Total Stories';
$lang->block->zentaoapp->storyCompleteRate    = 'Story CompleteRate';
$lang->block->zentaoapp->latestExecution      = 'Latest Execution';
$lang->block->zentaoapp->involvedExecution    = 'Involved Execution';
$lang->block->zentaoapp->mangedProduct        = 'Manged Product';
$lang->block->zentaoapp->involvedProject      = 'Involved Project';
$lang->block->zentaoapp->customIndexCard      = 'Custom Index Cards';
$lang->block->zentaoapp->createStory          = 'Story Create';
$lang->block->zentaoapp->createEffort         = 'Effort Create';
$lang->block->zentaoapp->createDoc            = 'Doc Create';
$lang->block->zentaoapp->createTodo           = 'Todo Create';
$lang->block->zentaoapp->workbench            = 'Workbench';
$lang->block->zentaoapp->notSupportKanban     = 'The mobile terminal does not support the R&D Kanban mode';
$lang->block->zentaoapp->notSupportVersion    = 'This version of ZenTao is not currently supported on the mobile terminal';
$lang->block->zentaoapp->incompatibleVersion  = 'The current version of ZenTao is lower, please upgrade to the latest version and try again';
$lang->block->zentaoapp->canNotGetVersion     = 'Failed to get ZenTao version, please confirm if the URL is correct';
