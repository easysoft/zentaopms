<?php
/**
 * The en file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        https://www.zentao.net
 */
global $config;
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
$lang->block->width      = 'Width';

$lang->block->account = 'Konto';
$lang->block->title   = 'Titel';
$lang->block->module  = 'Modul';
$lang->block->code    = 'InfoBlock';
$lang->block->order   = 'Sortierung';
$lang->block->height  = 'Höhe';
$lang->block->role    = 'Rolle';

$lang->block->lblModule       = 'Modul';
$lang->block->lblBlock        = 'InfoBlock';
$lang->block->lblNum          = 'Nummer';
$lang->block->lblHtml         = 'HTML';
$lang->block->html            = 'HTML';
$lang->block->dynamic         = 'Verlauf';
$lang->block->zentaoDynamic   = 'ZenTao Dynamics';
$lang->block->assignToMe      = 'Todo';
$lang->block->wait            = 'Wait';
$lang->block->doing           = 'Doing';
$lang->block->done            = 'Done';
$lang->block->lblFlowchart    = 'Workflow';
$lang->block->lblTesttask     = 'Test Request Detail';
$lang->block->contribute      = 'Personal Contribution';
$lang->block->finish          = 'Finish';
$lang->block->guide           = 'Guide';
$lang->block->teamAchievement = 'Team Achievements';

$lang->block->leftToday           = 'Arbeit für Heute';
$lang->block->myTask              = 'Aufgaben';
$lang->block->myStory             = 'Story';
$lang->block->myBug               = 'Bugs';
$lang->block->myExecution         = 'Unclosed ' . $lang->executionCommon . 's';
$lang->block->myProduct           = 'Unclosed ' . $lang->productCommon . 's';
$lang->block->delay               = 'delay';
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
$lang->block->selectProduct       = "{$lang->productCommon} selection";
$lang->block->blockTitle          = '%2$s von %1$s';
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
$lang->block->dynamicInfo        = "<span class='timeline-tag'>%s</span> <span class='timeline-text'>%s<span class='label-action'>%s</span>%s<a href='%s' title='%s'>%s</a></span>";
$lang->block->noLinkDynamic      = "<span class='timeline-tag'>%s</span> <span class='timeline-text' title='%s'>%s<span class='label-action'>%s</span>%s<span class='label-name'>%s</span></span>";
$lang->block->cannotPlaceInLeft  = 'Kann den Block nicht auf der linken Seite platzieren.';
$lang->block->cannotPlaceInRight = 'Kann den Block nicht auf der rechten Seite platzieren.';
$lang->block->tutorial           = 'Enter the tutorial';

$lang->block->productName   = $lang->productCommon . ' Name';
$lang->block->totalStory    = 'Total Story';
$lang->block->totalBug      = 'Total Bug';
$lang->block->totalRelease  = 'Release The Number';
$lang->block->totalTask     = 'The Total ' . $lang->task->common;
$lang->block->projectMember = 'Team Member';
$lang->block->totalMember   = '%s members in total';

$lang->block->totalInvestment = 'Have Invested';
$lang->block->totalPeople     = 'Total';
$lang->block->spent           = 'Has Been Spent';
$lang->block->budget          = 'Budget';
$lang->block->left            = 'Remain';

$lang->block->summary = new stdclass();
$lang->block->summary->welcome = 'Zentao has been with you for %s days. <strong>Yesterday</strong>, you has finished  <a href="' . helper::createLink('my', 'contribute', 'mode=task&type=finishedBy') . '" class="text-success">%s</a> tasks , <a href="' . helper::createLink('my', 'contribute', 'mode=bug&type=resolvedBy') . '" class="text-success">%s</a>  bugs were resolved.';

$lang->block->dashboard['default'] = 'Dashboard';
$lang->block->dashboard['my']      = 'My';

$lang->block->titleList['flowchart']      = 'Flow Chart';
$lang->block->titleList['guide']          = 'Guides';
$lang->block->titleList['statistic']      = 'Statistic';
$lang->block->titleList['recentproject']  = "Recent {$lang->projectCommon}";
$lang->block->titleList['assigntome']     = 'Assign to me';
$lang->block->titleList['project']        = "{$lang->projectCommon} List";
$lang->block->titleList['dynamic']        = 'Dynamic';
$lang->block->titleList['list']           = 'Todo List';
$lang->block->titleList['scrumoverview']  = "{$lang->projectCommon} Overview";
$lang->block->titleList['scrumtest']      = 'Scrum Test Request';
$lang->block->titleList['scrumlist']      = 'Scrum List';
$lang->block->titleList['sprint']         = 'Sprint';
$lang->block->titleList['projectdynamic'] = "{$lang->projectCommon} Dynamic";
$lang->block->titleList['bug']            = 'Bug';
$lang->block->titleList['case']           = 'Case';
$lang->block->titleList['testtask']       = 'Test Request';
$lang->block->titleList['statistic']      = "{$lang->projectCommon} Statistic";

$lang->block->default['scrumproject'][] = array('title' => "{$lang->projectCommon} Overview",   'module' => 'scrumproject', 'code' => 'scrumoverview',  'width' => '2');
$lang->block->default['scrumproject'][] = array('title' => "{$lang->executionCommon} List",     'module' => 'scrumproject', 'code' => 'scrumlist',      'width' => '2', 'params' => array('type' => 'undone', 'count' => '20', 'orderBy' => 'id_desc'));
$lang->block->default['scrumproject'][] = array('title' => 'Test Requests of Waiting',          'module' => 'scrumproject', 'code' => 'scrumtest',      'width' => '2', 'params' => array('type' => 'wait', 'count' => '15', 'orderBy' => 'id_desc'));
$lang->block->default['scrumproject'][] = array('title' => "{$lang->executionCommon} Overview", 'module' => 'scrumproject', 'code' => 'sprint',         'width' => '1');
$lang->block->default['scrumproject'][] = array('title' => 'Dynamic',                           'module' => 'scrumproject', 'code' => 'projectdynamic', 'width' => '1');

$lang->block->default['kanbanproject']    = $lang->block->default['scrumproject'];
$lang->block->default['agileplusproject'] = $lang->block->default['scrumproject'];

$lang->block->default['waterfallproject'][] = array('title' => "{$lang->projectCommon}Plan", 'module' => 'waterfallproject', 'code' => 'waterfallgantt', 'width' => '2');
$lang->block->default['waterfallproject'][] = array('title' => 'Dynamic',                    'module' => 'waterfallproject', 'code' => 'projectdynamic', 'width' => '1');

$lang->block->default['waterfallplusproject'] = $lang->block->default['waterfallproject'];
$lang->block->default['ipdproject']           = $lang->block->default['waterfallproject'];

$lang->block->default['product'][] = array('title' => "{$lang->productCommon} Overview",                   'module' => 'product', 'code' => 'overview',         'width' => '3');
$lang->block->default['product'][] = array('title' => "Unclosed {$lang->productCommon} Statistic",         'module' => 'product', 'code' => 'statistic',        'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20'));
$lang->block->default['product'][] = array('title' => "Bug Data For Unclosed {$lang->productCommon}",      'module' => 'product', 'code' => 'bugstatistic',     'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20'));
$lang->block->default['product'][] = array('title' => "{$lang->productCommon} Monthly Advancing Analysis", 'module' => 'product', 'code' => 'monthlyprogress',  'width' => '2');
$lang->block->default['product'][] = array('title' => "{$lang->productCommon} Annual Workload Statistic",  'module' => 'product', 'code' => 'annualworkload',   'width' => '2');
$lang->block->default['product'][] = array('title' => "Unclosed {$lang->productCommon} List",              'module' => 'product', 'code' => 'list',             'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20', 'orderBy' => 'id_desc'));
$lang->block->default['product'][] = array('title' => "Unclosed {$lang->productCommon} Releases",          'module' => 'product', 'code' => 'release',          'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20'));
$lang->block->default['product'][] = array('title' => "Unclosed {$lang->productCommon} Plans",             'module' => 'product', 'code' => 'plan',             'width' => '2', 'params' => array('count' => '20'));
$lang->block->default['product'][] = array('title' => "{$lang->productCommon} Release Statistic",          'module' => 'product', 'code' => 'releasestatistic', 'width' => '1');
$lang->block->default['product'][] = array('title' => "{$lang->SRCommon} Assigned To Me",                  'module' => 'product', 'code' => 'story',            'width' => '1', 'params' => array('type' => 'assignedTo', 'count' => '20', 'orderBy' => 'id_desc'));

$lang->block->default['singleproduct'][] = array('title' => "{$lang->productCommon} Statistic",                  'module' => 'singleproduct', 'code' => 'singlestatistic',        'width' => '2', 'params' => array('count' => '20'));
$lang->block->default['singleproduct'][] = array('title' => "{$lang->productCommon} Bug Statistic",              'module' => 'singleproduct', 'code' => 'singlebugstatistic',     'width' => '2', 'params' => array('count' => '20'));
$lang->block->default['singleproduct'][] = array('title' => "{$lang->productCommon} Roadmap",                    'module' => 'singleproduct', 'code' => 'roadmap',                'width' => '2');
$lang->block->default['singleproduct'][] = array('title' => "Unclosed {$lang->productCommon} Stories",           'module' => 'singleproduct', 'code' => 'singlestory',            'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20', 'orderBy' => 'id_desc'));
$lang->block->default['singleproduct'][] = array('title' => "Unclosed {$lang->productCommon} Plans",             'module' => 'singleproduct', 'code' => 'singleplan',             'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20'));
$lang->block->default['singleproduct'][] = array('title' => "Unclosed {$lang->productCommon} Releases",          'module' => 'singleproduct', 'code' => 'singlerelease',          'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20'));
$lang->block->default['singleproduct'][] = array('title' => "Dynamic",                                           'module' => 'singleproduct', 'code' => 'singledynamic',          'width' => '1');
$lang->block->default['singleproduct'][] = array('title' => "{$lang->productCommon} Monthly Advancing Analysis", 'module' => 'singleproduct', 'code' => 'singlemonthlyprogress',  'width' => '1');

$lang->block->default['qa'][] = array('title' => 'Test Report',    'module' => 'qa', 'code' => 'statistic', 'width' => '2', 'params' => array('type' => 'noclosed',   'count' => '20'));
$lang->block->default['qa'][] = array('title' => 'Wait Test List', 'module' => 'qa', 'code' => 'testtask',  'width' => '2', 'params' => array('type' => 'wait',       'count' => '15', 'orderBy' => 'id_desc'));
$lang->block->default['qa'][] = array('title' => 'My Bug List',    'module' => 'qa', 'code' => 'bug',       'width' => '1', 'params' => array('type' => 'assignedTo', 'count' => '15', 'orderBy' => 'id_desc'));
$lang->block->default['qa'][] = array('title' => 'My Case List',   'module' => 'qa', 'code' => 'case',      'width' => '1', 'params' => array('type' => 'assigntome', 'count' => '15', 'orderBy' => 'id_desc'));

$lang->block->default['full']['my'][] = array('title' => 'welcome',                                         'module' => 'welcome',         'code' => 'welcome',         'width' => '2');
$lang->block->default['full']['my'][] = array('title' => 'Guides',                                          'module' => 'guide',           'code' => 'guide',           'width' => '2');
$lang->block->default['full']['my'][] = array('title' => "Recent {$lang->projectCommon}s",                  'module' => 'project',         'code' => 'recentproject',   'width' => '2');
$lang->block->default['full']['my'][] = array('title' => 'Todo',                                            'module' => 'assigntome',      'code' => 'assigntome',      'width' => '2', 'params' => array('todoCount' => '20',  'taskCount' => '20', 'bugCount' => '20', 'riskCount' => '20', 'issueCount' => '20', 'storyCount' => '20', 'reviewCount' => '20', 'meetingCount' => '20', 'feedbackCount' => '20'));
$lang->block->default['full']['my'][] = array('title' => "Unclosed {$lang->productCommon} Statistic",       'module' => 'product',         'code' => 'statistic',       'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20'));
$lang->block->default['full']['my'][] = array('title' => "{$lang->projectCommon} Statistic",                'module' => 'project',         'code' => 'statistic',       'width' => '2', 'params' => array('type' => 'undone',   'count' => '20'));
$lang->block->default['full']['my'][] = array('title' => "Undone {$lang->execution->common} Statistic",     'module' => 'execution',       'code' => 'statistic',       'width' => '2', 'params' => array('type' => 'undone',   'count' => '20'));
$lang->block->default['full']['my'][] = array('title' => "Unclosed {$lang->productCommon} test statistics", 'module' => 'qa',              'code' => 'statistic',       'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20'));
$lang->block->default['full']['my'][] = array('title' => "Undone {$lang->projectCommon} list",              'module' => 'project',         'code' => 'project',         'width' => '2', 'params' => array('type' => 'undone',   'count' => '20', 'orderBy' => 'id_desc'));
$lang->block->default['full']['my'][] = array('title' => "Zentao Dynamic",                                  'module' => 'zentaodynamic',   'code' => 'zentaodynamic',   'width' => '1');
$lang->block->default['full']['my'][] = array('title' => "Team",                                            'module' => 'teamachievement', 'code' => 'teamachievement', 'width' => '1');
$lang->block->default['full']['my'][] = array('title' => 'Dynamic',                                         'module' => 'dynamic',         'code' => 'dynamic',         'width' => '1');
$lang->block->default['full']['my'][] = array('title' => "{$lang->productCommon} Overview",                 'module' => 'product',         'code' => 'overview',        'width' => '1');
$lang->block->default['full']['my'][] = array('title' => "{$lang->projectCommon} Overview",                 'module' => 'project',         'code' => 'overview',        'width' => '1');
$lang->block->default['full']['my'][] = array('title' => "{$lang->executionCommon} Overview",               'module' => 'execution',       'code' => 'overview',        'width' => '1');

$lang->block->default['doc'][] = array('title' => 'Statistic',                       'module' => 'doc', 'code' => 'docstatistic',    'width' => '2');
$lang->block->default['doc'][] = array('title' => 'My Collection Document',          'module' => 'doc', 'code' => 'docmycollection', 'width' => '2');
$lang->block->default['doc'][] = array('title' => 'Recently Update Document',        'module' => 'doc', 'code' => 'docrecentupdate', 'width' => '2');
$lang->block->default['doc'][] = array('title' => "{$lang->productCommon} Document", 'module' => 'doc', 'code' => 'productdoc',      'width' => '2', 'params' => array('count' => '20'));
$lang->block->default['doc'][] = array('title' => "{$lang->projectCommon} Document", 'module' => 'doc', 'code' => 'projectdoc',      'width' => '2', 'params' => array('count' => '20'));
$lang->block->default['doc'][] = array('title' => 'Dynamic',                         'module' => 'doc', 'code' => 'docdynamic',      'width' => '1');
$lang->block->default['doc'][] = array('title' => 'Browse Leaderboard',              'module' => 'doc', 'code' => 'docviewlist',     'width' => '1');
$lang->block->default['doc'][] = array('title' => 'Favorite Leaderboard',            'module' => 'doc', 'code' => 'doccollectlist',  'width' => '1');

$lang->block->count   = 'Count';
$lang->block->type    = 'Type';
$lang->block->orderBy = 'Order by';

$lang->block->availableBlocks['todo']        = 'Schedule';
$lang->block->availableBlocks['task']        = 'Tasks';
$lang->block->availableBlocks['bug']         = 'Bugs';
$lang->block->availableBlocks['case']        = 'Cases';
$lang->block->availableBlocks['story']       = 'Stories';
$lang->block->availableBlocks['requirement'] = 'Requirements';
$lang->block->availableBlocks['product']     = $lang->productCommon . 's';
$lang->block->availableBlocks['execution']   = $lang->executionCommon . 's';
$lang->block->availableBlocks['plan']        = 'Plans';
$lang->block->availableBlocks['release']     = 'Releases';
$lang->block->availableBlocks['build']       = 'Builds';
$lang->block->availableBlocks['testcase']    = 'Testcase';
$lang->block->availableBlocks['testtask']    = 'Testtask';
$lang->block->availableBlocks['risk']        = 'Risks';
$lang->block->availableBlocks['issue']       = 'Issues';
$lang->block->availableBlocks['meeting']     = 'Meetings';
$lang->block->availableBlocks['feedback']    = 'Feedbacks';
$lang->block->availableBlocks['ticket']      = 'Tickets';

$lang->block->modules['project'] = new stdclass();
$lang->block->modules['project']->availableBlocks['overview']      = "{$lang->projectCommon} Overview";
$lang->block->modules['project']->availableBlocks['recentproject'] = "Recent {$lang->projectCommon}";
$lang->block->modules['project']->availableBlocks['statistic']     = "{$lang->projectCommon} Statistic";
$lang->block->modules['project']->availableBlocks['project']       = "{$lang->projectCommon} List";

$lang->block->modules['scrumproject'] = new stdclass();
$lang->block->modules['scrumproject']->availableBlocks['scrumoverview']  = "{$lang->projectCommon} Overview";
$lang->block->modules['scrumproject']->availableBlocks['scrumlist']      = $lang->executionCommon . ' List';
$lang->block->modules['scrumproject']->availableBlocks['sprint']         = $lang->executionCommon . ' Overview';
$lang->block->modules['scrumproject']->availableBlocks['scrumtest']      = 'Test Requests';
$lang->block->modules['scrumproject']->availableBlocks['projectdynamic'] = 'Dynamics';

$lang->block->modules['waterfallproject'] = new stdclass();
$lang->block->modules['waterfallproject']->availableBlocks['waterfallgantt'] = "{$lang->projectCommon} Plan";
$lang->block->modules['waterfallproject']->availableBlocks['projectdynamic'] = 'Dynamics';

$lang->block->modules['agileplus']     = $lang->block->modules['scrumproject'];
$lang->block->modules['waterfallplus'] = $lang->block->modules['waterfallproject'];

$lang->block->modules['product'] = new stdclass();
$lang->block->modules['product']->availableBlocks['overview']         = $lang->productCommon . ' Overview';
$lang->block->modules['product']->availableBlocks['statistic']        = $lang->productCommon . ' Statistic';
$lang->block->modules['product']->availableBlocks['releasestatistic'] = "{$lang->productCommon} Release Statistic";
$lang->block->modules['product']->availableBlocks['bugstatistic']     = "{$lang->productCommon} Bug Statistic";
$lang->block->modules['product']->availableBlocks['annualworkload']   = "{$lang->productCommon} Annual Workload Statistic";
$lang->block->modules['product']->availableBlocks['monthlyprogress']  = "{$lang->productCommon} Monthly Advancing Analysis";
$lang->block->modules['product']->availableBlocks['list']             = $lang->productCommon . ' List';
$lang->block->modules['product']->availableBlocks['plan']             = 'Plan';
$lang->block->modules['product']->availableBlocks['release']          = 'Release';
$lang->block->modules['product']->availableBlocks['story']            = 'Story';

$lang->block->modules['singleproduct'] = new stdclass();
$lang->block->modules['singleproduct']->availableBlocks['singlestatistic']       = $lang->productCommon . ' Statistic';
$lang->block->modules['singleproduct']->availableBlocks['singlebugstatistic']    = "{$lang->productCommon} Bug Statistic";
$lang->block->modules['singleproduct']->availableBlocks['roadmap']               = "{$lang->productCommon} RoadMap";
$lang->block->modules['singleproduct']->availableBlocks['singlestory']           = "{$lang->SRCommon} List";
$lang->block->modules['singleproduct']->availableBlocks['singleplan']            = "Plan List";
$lang->block->modules['singleproduct']->availableBlocks['singlerelease']         = 'Release List';
$lang->block->modules['singleproduct']->availableBlocks['singledynamic']         = 'Dynamic';
$lang->block->modules['singleproduct']->availableBlocks['singlemonthlyprogress'] = "{$lang->productCommon} Monthly Advancing Analysis";

$lang->block->modules['execution'] = new stdclass();
$lang->block->modules['execution']->availableBlocks['statistic'] = $lang->execution->common . ' Statistics';
$lang->block->modules['execution']->availableBlocks['overview']  = $lang->execution->common . ' Overview';
$lang->block->modules['execution']->availableBlocks['list']      = $lang->execution->common . ' List';
$lang->block->modules['execution']->availableBlocks['task']      = 'Task';
$lang->block->modules['execution']->availableBlocks['build']     = 'Build';

$lang->block->modules['qa'] = new stdclass();
$lang->block->modules['qa']->availableBlocks['statistic'] = "{$lang->productCommon} Test Berichte";
$lang->block->modules['qa']->availableBlocks['bug']       = 'Bug';
$lang->block->modules['qa']->availableBlocks['case']      = 'Case';
$lang->block->modules['qa']->availableBlocks['testtask']  = 'Test Request';

$lang->block->modules['todo'] = new stdclass();
$lang->block->modules['todo']->availableBlocks['list'] = 'Todo';

$lang->block->modules['doc'] = new stdclass();
$lang->block->modules['doc']->availableBlocks['docstatistic']    = 'Statistic';
$lang->block->modules['doc']->availableBlocks['docdynamic']      = 'Dynamic';
$lang->block->modules['doc']->availableBlocks['docmycollection'] = 'My Collection';
$lang->block->modules['doc']->availableBlocks['docrecentupdate'] = 'Recently Update';
$lang->block->modules['doc']->availableBlocks['docviewlist']     = 'Browse Leaderboard';
if($config->vision == 'rnd') $lang->block->modules['doc']->availaableBlocks['productdoc'] = $lang->productCommon . 'Document';
$lang->block->modules['doc']->availableBlocks['doccollectlist']  = 'Favorite Leaderboard';
$lang->block->modules['doc']->availableBlocks['projectdoc']      = $lang->projectCommon . 'Document';

$lang->block->orderByList = new stdclass();
$lang->block->orderByList->product = array();
$lang->block->orderByList->product['id_asc']      = 'ID Aufsteigend';
$lang->block->orderByList->product['id_desc']     = 'ID Absteigend';
$lang->block->orderByList->product['status_asc']  = 'Status Aufsteigend';
$lang->block->orderByList->product['status_desc'] = 'Status Absteigend';

$lang->block->orderByList->project = array();
$lang->block->orderByList->project['id_asc']      = "{$lang->projectCommon} ID ASC";
$lang->block->orderByList->project['id_desc']     = "{$lang->projectCommon} ID DESC";
$lang->block->orderByList->project['status_asc']  = "{$lang->projectCommon} Status ASC";
$lang->block->orderByList->project['status_desc'] = "{$lang->projectCommon} Status DESC";

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
$lang->block->reviewCount   = 'Reviews';
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
$lang->block->typeList->story['reviewBy']   = 'Von mir üderzeit berprüft';
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

$lang->block->typeList->projectAll['all']       = 'All';
$lang->block->typeList->projectAll['undone']    = 'Undone';
$lang->block->typeList->projectAll['wait']      = 'Wait';
$lang->block->typeList->projectAll['doing']     = 'Doing';
$lang->block->typeList->projectAll['suspended'] = 'Suspended';
$lang->block->typeList->projectAll['closed']    = 'Closed';

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

$lang->block->typeList->risk['all']      = 'All';
$lang->block->typeList->risk['active']   = 'Active';
$lang->block->typeList->risk['assignTo'] = 'Assign To';
$lang->block->typeList->risk['assignBy'] = 'Assign By';
$lang->block->typeList->risk['closed']   = 'Closed';
$lang->block->typeList->risk['hangup']   = 'Hangup';
$lang->block->typeList->risk['canceled'] = 'Canceled';

$lang->block->typeList->issue['all']      = 'All';
$lang->block->typeList->issue['open']     = 'Open';
$lang->block->typeList->issue['assignto'] = 'Assign To';
$lang->block->typeList->issue['assignby'] = 'Assign By';
$lang->block->typeList->issue['closed']   = 'Closed';
$lang->block->typeList->issue['resolved'] = 'Resolved';
$lang->block->typeList->issue['canceled'] = 'Canceled';

$lang->block->welcomeList['06:00'] = 'Guten Morgen, %s';
$lang->block->welcomeList['11:30'] = 'Guten Tag, %s';
$lang->block->welcomeList['13:30'] = 'Guten Tag, %s';
$lang->block->welcomeList['19:00'] = 'Guten Abend, %s';

$lang->block->gridOptions[8] = 'Links';
$lang->block->gridOptions[4] = 'Rechts';

$lang->block->widthOptions['1'] = 'Short Block';
$lang->block->widthOptions['2'] = 'Long Block';
$lang->block->widthOptions['3'] = 'Max Block';

$lang->block->flowchart            = array();
$lang->block->flowchart['admin']   = array('Administrator', 'Add Departments', 'Benutzer erstellen', 'Rechte pflegen');
if($config->systemMode == 'ALM') $lang->block->flowchart['program'] = array('Program Owner', 'Create Program', "Link {$lang->productCommon}", "Create {$lang->projectCommon}", "Budgeting and planning", 'Add Stakeholder');
$lang->block->flowchart['product'] = array($lang->productCommon . ' Besitzer', $lang->productCommon . ' erstellen', 'Module pflegen', 'Pläne pflegen', 'Storys pflegen', 'Release erstellen');
$lang->block->flowchart['project'] = array('Project Manager', "Add {$lang->productCommon}s and " . $lang->execution->common . 's', 'Maintain Teams', 'Link Stories', 'Create Tasks', 'Track');
$lang->block->flowchart['dev']     = array('Entwickler', 'Aufgabe/Bugs anfordern', 'Update Status', 'Aufgaben/Bugs abschließen');
$lang->block->flowchart['tester']  = array('QS Team', 'Fälle erstellen', 'Fälle ausführen', 'Bug Berichte', 'Bugs überprüfen', 'Bugs schließen');

$lang->block->zentaoapp = new stdclass();
$lang->block->zentaoapp->common               = 'ZenTao App';
$lang->block->zentaoapp->thisYearInvestment   = 'Investment The Year';
$lang->block->zentaoapp->sinceTotalInvestment = 'Total Investment';
$lang->block->zentaoapp->myStory              = 'My Story';
$lang->block->zentaoapp->allStorySum          = 'Total Stories';
$lang->block->zentaoapp->storyCompleteRate    = 'Story CompleteRate';
$lang->block->zentaoapp->latestExecution      = 'Latest Execution';
$lang->block->zentaoapp->involvedExecution    = 'Involved Execution';
$lang->block->zentaoapp->mangedProduct        = "Manged {$lang->productCommon}";
$lang->block->zentaoapp->involvedProject      = "Involved {$lang->projectCommon}";
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
$lang->block->zentaoapp->desc                 = "ZenTao mobile app provides you with a mobile work environment, which is convenient for managing personal to-do tasks at any time, tracking {$lang->projectCommon} progress, and enhancing the flexibility and agility of {$lang->projectCommon} management.";
$lang->block->zentaoapp->downloadTip          = 'Scan QR code to download';

$lang->block->zentaoclient = new stdClass();
$lang->block->zentaoclient->common = 'ZenTao Client';
$lang->block->zentaoclient->desc   = 'The ZenTao client provides functions such as chat, information notification, robot, and embedding ZenTao applet, which makes teamwork more convenient without frequently switching browsers.';

$lang->block->zentaoclient->edition = new stdclass();
$lang->block->zentaoclient->edition->win64   = 'Windows';
$lang->block->zentaoclient->edition->linux64 = 'Linux';
$lang->block->zentaoclient->edition->mac64   = 'Mac OS';

$lang->block->guideTabs['flowchart']      = 'Flowchart';
if($config->systemMode != 'PLM') $lang->block->guideTabs['systemMode']     = 'Operating Modes';
$lang->block->guideTabs['visionSwitch']   = 'Interface Switch';
$lang->block->guideTabs['themeSwitch']    = 'Theme Switch';
$lang->block->guideTabs['preference']     = 'Personalized setting';
$lang->block->guideTabs['downloadClient'] = 'Desktop Client download';
$lang->block->guideTabs['downloadMobile'] = 'Mobile Apps download';

$lang->block->themes['default']    = 'Default';
$lang->block->themes['blue']       = 'Young Blue';
$lang->block->themes['green']      = 'Green';
$lang->block->themes['red']        = 'Red';
$lang->block->themes['pink']       = 'Pink';
$lang->block->themes['blackberry'] = 'Blackberry';
$lang->block->themes['classic']    = 'Classic';
$lang->block->themes['purple']     = 'Purple';

$lang->block->visionTitle            = 'The user interface of ZenTao is divided into 【Full feature interface】 and 【Operation Management Interface】.';
$lang->block->visions['rnd']         = new stdclass();
$lang->block->visions['rnd']->key    = 'rnd';
$lang->block->visions['rnd']->title  = 'Full feature interface';
$lang->block->visions['rnd']->text   = "Integrate the program, {$lang->productCommon}, {$lang->projectCommon}, execution, test, etc., and provide the lifecycle {$lang->projectCommon} management solution.";
$lang->block->visions['lite']        = new stdclass();
$lang->block->visions['lite']->key   = 'lite';
$lang->block->visions['lite']->title = 'Operation Management Interface';
$lang->block->visions['lite']->text  = "Specially designed for Non-R&D teams, and based on the visual Kanban {$lang->projectCommon} management model.";

$lang->block->customModes['light'] = 'Light Mode';
$lang->block->customModes['ALM']   = 'ALM Mode';

$lang->block->honorary = array();
$lang->block->honorary['bug']    = 'Bug King';
$lang->block->honorary['task']   = 'Task King';
$lang->block->honorary['review'] = 'Review King';

$lang->block->welcome = new stdclass();
$lang->block->welcome->common     = 'Willkommen';
$lang->block->welcome->reviewByMe = 'ReviewByMe';
$lang->block->welcome->assignToMe = 'AssignToMe';

$lang->block->welcome->reviewList = array();
$lang->block->welcome->reviewList['story']    = 'Story';
$lang->block->welcome->reviewList['feedback'] = 'Feedback';
$lang->block->welcome->reviewList['testcase'] = 'Testcase';

$lang->block->welcome->assignList = array();
$lang->block->welcome->assignList['task']        = 'Task';
$lang->block->welcome->assignList['bug']         = 'Bug';
$lang->block->welcome->assignList['story']       = 'SRStroy';
$lang->block->welcome->assignList['testcase']    = 'TestCase';
$lang->block->welcome->assignList['requirement'] = 'URStory';
$lang->block->welcome->assignList['testtask']    = 'Testtask';
$lang->block->welcome->assignList['issue']       = 'Issue';
$lang->block->welcome->assignList['risk']        = 'Risk';
$lang->block->welcome->assignList['qa']          = 'QA';
$lang->block->welcome->assignList['meeting']     = 'Meeting';
$lang->block->welcome->assignList['feedback']    = 'Feedback';

$lang->block->customModeTip = new stdClass();
$lang->block->customModeTip->common = 'There are 2 running modes of ZenTao:  Light Mode and ALM Mode.';
$lang->block->customModeTip->ALM    = 'The concept is more complete and rigorous, and the function is more abundant. It is suitable for medium and large R&D teams.';
$lang->block->customModeTip->light  = "Provides the core function of {$lang->projectCommon} management, suitable for small R&D teams.";

$lang->block->productstatistic = new stdclass();
$lang->block->productstatistic->effectiveStory  = 'Effective stories';
$lang->block->productstatistic->delivered       = 'Delivered';
$lang->block->productstatistic->unclosed        = 'unclosed';
$lang->block->productstatistic->storyStatistics = 'Story Statistics';
$lang->block->productstatistic->monthDone       = 'Completed this month <span class="text-success font-bold">%s</span>';
$lang->block->productstatistic->monthOpened     = 'Added this month <span class="text-primary font-bold">%s</span>';
$lang->block->productstatistic->opened          = 'Opened';
$lang->block->productstatistic->done            = 'Done';
$lang->block->productstatistic->news            = 'Latest product advancements';
$lang->block->productstatistic->newPlan         = 'Latest Plan';
$lang->block->productstatistic->newExecution    = 'Latest Execution';
$lang->block->productstatistic->newRelease      = 'Latest Release';
$lang->block->productstatistic->deliveryRate    = 'Delivery Rate';

$lang->block->projectoverview = new stdclass();
$lang->block->projectoverview->totalProject  = 'Total';
$lang->block->projectoverview->thisYear      = 'This Year';
$lang->block->projectoverview->lastThreeYear = 'Done in last three years';

$lang->block->projectstatistic = new stdclass();
$lang->block->projectstatistic->story            = 'Story';
$lang->block->projectstatistic->cost             = 'Cost';
$lang->block->projectstatistic->task             = 'Task';
$lang->block->projectstatistic->bug              = 'Bug';
$lang->block->projectstatistic->storyPoints      = 'Story Points';
$lang->block->projectstatistic->done             = 'Done';
$lang->block->projectstatistic->undone           = 'Undone';
$lang->block->projectstatistic->costs            = 'Costs';
$lang->block->projectstatistic->consumed         = 'Consumed';
$lang->block->projectstatistic->remainder        = 'Remainder';
$lang->block->projectstatistic->tasks            = 'Number';
$lang->block->projectstatistic->wait             = 'Wait';
$lang->block->projectstatistic->doing            = 'Doing';
$lang->block->projectstatistic->bugs             = 'Number';
$lang->block->projectstatistic->closed           = 'Closed';
$lang->block->projectstatistic->activated        = 'Activated';
$lang->block->projectstatistic->unit             = 'unit';
$lang->block->projectstatistic->SP               = 'SP';
$lang->block->projectstatistic->personDay        = 'ManHour';
$lang->block->projectstatistic->day              = 'day';
$lang->block->projectstatistic->hour             = 'hour';
$lang->block->projectstatistic->leftDaysPre      = 'Before the end has ';
$lang->block->projectstatistic->delayDaysPre     = 'Has been delayed for ';
$lang->block->projectstatistic->existRisks       = 'Risks:';
$lang->block->projectstatistic->existIssues      = 'Issues:';
$lang->block->projectstatistic->lastestExecution = 'Lastest Execution';
$lang->block->projectstatistic->projectClosed    = "{$lang->projectCommon} has been closed.";
$lang->block->projectstatistic->longTimeProject  = "Long Time {$lang->projectCommon}";
$lang->block->projectstatistic->totalProgress    = 'Total Progress';
$lang->block->projectstatistic->totalProgressTip = "<strong>Total Progress</strong> = Number of hours spent on tasks by {$lang->projectCommon}/（Number of hours spent on tasks by {$lang->projectCommon} + Number of hours remaining on tasks by {$lang->projectCommon}）<br/>
<strong>Number of hours consumed on tasks by {$lang->projectCommon}</strong>: Summarise the number of hours spent on tasks in a {$lang->projectCommon}, filter deleted tasks, filter parent tasks, filter tasks in deleted {$lang->execution->common}.<br/>
<strong>Number of hours remaining on tasks by {$lang->projectCommon}</strong>: Summarise the remaining hours of tasks in a {$lang->projectCommon}, filter deleted tasks, filter parent tasks, filter tasks in deleted {$lang->execution->common}.";
$lang->block->projectstatistic->currentCost      = 'Current Cost';
$lang->block->projectstatistic->sv               = 'Schedule Variance(SV)';
$lang->block->projectstatistic->pv               = 'Planned Value(PV)';
$lang->block->projectstatistic->ev               = 'Earned Value(EV)';
$lang->block->projectstatistic->cv               = 'Cost Variance(CV)';
$lang->block->projectstatistic->ac               = 'Actual Cost(AC)';

$lang->block->qastatistic = new stdclass();
$lang->block->qastatistic->fixBugRate        = 'Fix Bug Rate';
$lang->block->qastatistic->closedBugRate     = 'Closed Bug Rate';
$lang->block->qastatistic->totalBug          = 'Bug Total';
$lang->block->qastatistic->bugStatistics     = 'Bug Statistics';
$lang->block->qastatistic->addYesterday      = 'Added Yesterday';
$lang->block->qastatistic->addToday          = 'Added Today';
$lang->block->qastatistic->resolvedYesterday = 'Resolved Yesterday';
$lang->block->qastatistic->resolvedToday     = 'Resolved Today';
$lang->block->qastatistic->closedYesterday   = 'Closed Yesterday';
$lang->block->qastatistic->closedToday       = 'Closed Today';
$lang->block->qastatistic->latestTesttask    = 'Latest Testtask';
$lang->block->qastatistic->bugStatusStat     = 'Bug Status Distribution';

$lang->block->bugstatistic = new stdclass();
$lang->block->bugstatistic->effective = 'effectived';
$lang->block->bugstatistic->fixed     = 'fixed';
$lang->block->bugstatistic->activated = 'activated';

$lang->block->executionstatistic = new stdclass();
$lang->block->executionstatistic->allProject        = 'All Project';
$lang->block->executionstatistic->progress          = 'Progress';
$lang->block->executionstatistic->totalEstimate     = 'Estimate';
$lang->block->executionstatistic->totalConsumed     = 'Consumed';
$lang->block->executionstatistic->totalLeft         = 'Left';
$lang->block->executionstatistic->burn              = 'Execution Burn';
$lang->block->executionstatistic->story             = 'Story';
$lang->block->executionstatistic->doneStory         = 'Done';
$lang->block->executionstatistic->totalStory        = 'Total';
$lang->block->executionstatistic->task              = 'Task';
$lang->block->executionstatistic->totalTask         = 'Total';
$lang->block->executionstatistic->undoneTask        = 'Undone';
$lang->block->executionstatistic->yesterdayDoneTask = 'Complated Yesterday';

$lang->block->executionoverview = new stdclass();
$lang->block->executionoverview->totalExecution = 'Total';
$lang->block->executionoverview->thisYear       = 'This Year';
$lang->block->executionoverview->statusCount    = "Status of unclosed {$lang->executionCommon}";

$lang->block->productoverview = new stdclass();
$lang->block->productoverview->overview                = 'Total Overview';
$lang->block->productoverview->yearFinished            = 'Year Overview';
$lang->block->productoverview->productLineCount        = 'Product Line Count';
$lang->block->productoverview->productCount            = 'Total Product Count';
$lang->block->productoverview->releaseCount            = 'Number Of Releases This Year';
$lang->block->productoverview->milestoneCount          = 'Number of Milestones This Year';
$lang->block->productoverview->unfinishedPlanCount     = 'Unfinished Plans';
$lang->block->productoverview->unclosedStoryCount      = 'Unclosed Stories';
$lang->block->productoverview->activeBugCount          = 'Active Bugs';
$lang->block->productoverview->finishedReleaseCount    = 'Finished Releases';
$lang->block->productoverview->finishedStoryCount      = 'Finished Stories';
$lang->block->productoverview->finishedStoryPoint      = 'Finished Story Points';
$lang->block->productoverview->thisWeek                = 'This Week';

$lang->block->productlist = new stdclass();
$lang->block->productlist->unclosedFeedback  = 'Number Of Feedback Not Closed';
$lang->block->productlist->activatedStory    = 'Activate Requirements';
$lang->block->productlist->storyCompleteRate = 'Requirement Completion Rate';
$lang->block->productlist->activatedBug      = 'Activate Bugs';

$lang->block->sprint = new stdclass();
$lang->block->sprint->totalExecution = 'Total';
$lang->block->sprint->thisYear       = 'This Year';
$lang->block->sprint->statusCount    = "Status of {$lang->executionCommon}";

$lang->block->zentaodynamic = new stdclass();
$lang->block->zentaodynamic->zentaosalon  = 'ZenTao China Travel';
$lang->block->zentaodynamic->publicclass  = 'ZenTao Webinar';
$lang->block->zentaodynamic->release      = 'Latest Release';
$lang->block->zentaodynamic->registration = 'Registration';
$lang->block->zentaodynamic->reservation  = 'Reservation';

$lang->block->monthlyprogress = new stdclass();
$lang->block->monthlyprogress->doneStoryEstimateTrendChart = "The Finished {$lang->SRCommon} Scale Trend Chart";
$lang->block->monthlyprogress->storyTrendChart             = "The New and Finished {$lang->SRCommon} Trend Chart";
$lang->block->monthlyprogress->bugTrendChart               = 'The New and Resolved Bugs Trend Chart';

$lang->block->annualworkload = new stdclass();
$lang->block->annualworkload->doneStoryEstimate = "Finished {$lang->SRCommon} Scale";
$lang->block->annualworkload->doneStoryCount    = "Finished {$lang->SRCommon} Count";
$lang->block->annualworkload->resolvedBugCount  = 'Resolved Bugs';

$lang->block->releasestatistic = new stdclass();
$lang->block->releasestatistic->monthly = 'Monthly releases trend chart';
$lang->block->releasestatistic->annual  = "Annual release list (%s year)";

$lang->block->teamachievement = new stdclass();
$lang->block->teamachievement->finishedTasks  = 'Finished Tasks';
$lang->block->teamachievement->createdStories = 'New Stories';
$lang->block->teamachievement->closedBugs     = 'Closed Bugs';
$lang->block->teamachievement->runCases       = 'Run Cases';
$lang->block->teamachievement->consumedHours  = 'Consumed Hours';
$lang->block->teamachievement->totalWorkload  = 'Total Workload';
$lang->block->teamachievement->vs             = 'VS';

$lang->block->moduleList['product']         = $lang->productCommon;
$lang->block->moduleList['project']         = $lang->projectCommon;
$lang->block->moduleList['execution']       = $lang->execution->common;
$lang->block->moduleList['qa']              = $lang->qa->common;
$lang->block->moduleList['welcome']         = $lang->block->welcome->common;
$lang->block->moduleList['guide']           = $lang->block->guide;
$lang->block->moduleList['zentaodynamic']   = $lang->block->zentaoDynamic;
$lang->block->moduleList['teamachievement'] = $lang->block->teamAchievement;
$lang->block->moduleList['assigntome']      = $lang->block->assignToMe;
$lang->block->moduleList['dynamic']         = $lang->block->dynamic;
$lang->block->moduleList['html']            = $lang->block->html;

$lang->block->tooltips = array();
$lang->block->tooltips['deliveryRate']      = "{$lang->SRCommon} delivery rate = Number of {$lang->SRCommon} delivered by {$lang->productCommon} / (Total number of {$lang->SRCommon} by {$lang->productCommon} - Number of {$lang->SRCommon} delivered by {$lang->productCommon}) * 100%";
$lang->block->tooltips['resolvedRate']      = "Bug repair rate by {$lang->productCommon} = number of fixed bugs by {$lang->productCommon} / number of valid bugs by {$lang->productCommon}";
$lang->block->tooltips['effectiveStory']    = "Total number of {$lang->SRCommon} by {$lang->productCommon}: Sum the number of {$lang->SRCommon} in a {$lang->productCommon}, filter deleted {$lang->SRCommon} and filter deleted {$lang->productCommon}.";
$lang->block->tooltips['deliveredStory']    = "Number of {$lang->SRCommon} delivered by {$lang->productCommon}: Sum the number of {$lang->SRCommon} in the {$lang->productCommon}, the stage is released or the reason for closure is done, filter the deleted {$lang->SRCommon} and filter the deleted {$lang->productCommon}.";
$lang->block->tooltips['costs']             = "Have invested = Hours consumed / Available hours per day";
$lang->block->tooltips['sv']                = "Schedule Variance = (EV - PV) / PV * 100% ";
$lang->block->tooltips['ev']                = "<strong>Earned Value</strong> = Number of estimated hours worked on tasks by {$lang->projectCommon} * Progress of tasks by {$lang->projectCommon}, filter deleted tasks, filter cancelled tasks, filter tasks in deleted tasks, filter tasks in deleted {$lang->projectCommon}. <br/>
<strong>Number of estimated hours worked on tasks by {$lang->projectCommon}</strong>: Summarise the estimated hours of tasks in a {$lang->projectCommon}, filter deleted tasks, filter parent tasks, filter tasks in deleted tasks, filter tasks in deleted {$lang->projectCommon}.";
$lang->block->tooltips['pv']                = "Planned Value: Summarise the estimated hours for all tasks in the waterfall {$lang->projectCommon}, filter deleted tasks, filter cancelled tasks, filter tasks in deleted tasks, filter tasks in deleted {$lang->projectCommon}.";
$lang->block->tooltips['cv']                = 'Cost Variance = (EV - AC) / AC * 100%';
$lang->block->tooltips['ac']                = "Actual Cost: Summarise all logged hours in the waterfall {$lang->projectCommon}, filtering for deleted {$lang->projectCommon}.";
$lang->block->tooltips['executionProgress'] = "<strong>Total Progress</strong> = Number of hours consumed for task by {$lang->execution->common}/(Number of hours consumed for tasks by {$lang->execution->common} + Number of hours remaining for tasks by {$lang->execution->common})<br/>
<strong>Number of hours consumed for tasks by {$lang->execution->common}</strong>: Summarise the number of hours consumed for tasks by {$lang->execution->common}, filter deleted tasks, filter parent tasks, filter tasks in deleted {$lang->execution->common}, filter tasks in deleted {$lang->projectCommon}.<br/>
<strong>Number of hours remaining for tasks by {$lang->execution->common}</strong>: Summarise the number of remaining hours for tasks by {$lang->execution->common}, filter deleted tasks, filter parent tasks, filter tasks in deleted {$lang->execution->common}, filter tasks in deleted {$lang->projectCommon}.";
