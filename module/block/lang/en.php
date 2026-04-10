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
$lang->block->params     = 'Parameters';
$lang->block->name       = 'Block Name';
$lang->block->style      = 'Appearance';
$lang->block->grid       = 'Position';
$lang->block->color      = 'Color';
$lang->block->reset      = 'Restore Defaults';
$lang->block->story      = 'Story';
$lang->block->investment = 'Investment';
$lang->block->estimate   = 'Estimate';
$lang->block->last       = 'Recent';
$lang->block->width      = 'Width';

$lang->block->account = 'Account';
$lang->block->title   = 'Block Name';
$lang->block->module  = 'Module';
$lang->block->code    = 'Block';
$lang->block->order   = 'Order';
$lang->block->height  = 'Height';
$lang->block->role    = 'Role';

$lang->block->lblModule       = 'Module';
$lang->block->lblBlock        = 'Block';
$lang->block->lblNum          = 'Item Count';
$lang->block->lblHtml         = 'HTML Content';
$lang->block->html            = 'HTML';
$lang->block->dynamic         = 'Recents';
$lang->block->zentaoDynamic   = 'ZenTao News';
$lang->block->assignToMe      = 'Pending Items';
$lang->block->wait            = 'Waiting';
$lang->block->doing           = 'Doing';
$lang->block->done            = 'Done';
$lang->block->lblFlowchart    = 'Essential Flow';
$lang->block->lblTesttask     = 'View Test Details';
$lang->block->contribute      = 'My Contributions';
$lang->block->finish          = 'Done';
$lang->block->guide           = 'Guide';
$lang->block->teamAchievement = 'Team Achievements';
$lang->block->learnMore       = 'Learn More';
$lang->block->prevPage        = 'Back';
$lang->block->nextPage        = 'Next';
$lang->block->experience      = 'Go';

$lang->block->leftToday           = 'Total Work Remaining Today';
$lang->block->myTask              = 'My Tasks';
$lang->block->myStory             = "My {$lang->SRCommon}";
$lang->block->myBug               = 'My Bugs';
$lang->block->myExecution         = 'Open' . $lang->executionCommon;
$lang->block->myProduct           = 'Open' . $lang->productCommon;
$lang->block->delay               = 'delay';
$lang->block->delayed             = 'Delayed';
$lang->block->noData              = 'No data for this report type';
$lang->block->emptyTip            = 'No data';
$lang->block->createdTodos        = 'To-dos Created';
$lang->block->createdRequirements = 'Created' . $lang->URCommon . 'Count';
$lang->block->createdStories      = 'Created' . $lang->SRCommon . 'Count';
$lang->block->finishedTasks       = 'Tasks Completed';
$lang->block->createdBugs         = 'Bugs Reported';
$lang->block->resolvedBugs        = 'Bugs Resolved';
$lang->block->createdCases        = 'Cases Created';
$lang->block->createdRisks        = 'Risks Created';
$lang->block->resolvedRisks       = 'Risks Resolved';
$lang->block->createdIssues       = 'Issues Created';
$lang->block->resolvedIssues      = 'Issues Resolved';
$lang->block->createdDocs         = 'Docs Created';
$lang->block->allExecutions       = 'All ' . $lang->executionCommon;
$lang->block->doingExecution      = 'Doning ' . $lang->executionCommon;
$lang->block->finishExecution     = 'Total ' . $lang->executionCommon;
$lang->block->estimatedHours      = 'Estimated';
$lang->block->consumedHours       = 'Cost';
$lang->block->time                = 'No.';
$lang->block->week                = 'Week';
$lang->block->month               = 'Month';
$lang->block->selectProduct       = "{$lang->productCommon} selection";
$lang->block->blockTitle          = '%1$s %2$s';
$lang->block->remain              = 'Left';
$lang->block->allStories          = 'Total Stories';

$lang->block->createBlock        = 'Add Block';
$lang->block->editBlock          = 'Edit Block';
$lang->block->ordersSaved        = 'The order is saved.';
$lang->block->confirmRemoveBlock = 'Are you sure you want to hide this block?';
$lang->block->noticeNewBlock     = 'New layouts are available for all view homepages starting from version 10.0. Would you like to switch to the new one?';
$lang->block->confirmReset       = 'Reset to default layout?';
$lang->block->closeForever       = 'Remove Permanently';
$lang->block->confirmClose       = 'Are you sure you want to disable this block? Once disabled, it will be unavailable to all users. You can re-enable it in [Admin > Feature>Dashboard > Blocks].';
$lang->block->remove             = 'Remove';
$lang->block->refresh            = 'Refresh';
$lang->block->nbsp               = ' ';
$lang->block->hidden             = 'Hide';
$lang->block->dynamicInfo        = "<span class='timeline-tag'>%s</span> <span class='timeline-text'>%s<span class='label-action'>%s</span>%s<a href='%s' title='%s'>%s</a></span>";
$lang->block->noLinkDynamic      = "<span class='timeline-tag'>%s</span> <span class='timeline-text' title='%s'>%s<span class='label-action'>%s</span>%s<span class='label-name'>%s</span></span>";
$lang->block->cannotPlaceInLeft  = 'This block cannot be placed on the left.';
$lang->block->cannotPlaceInRight = 'This block cannot be placed on the right.';
$lang->block->tutorial           = 'Enter the tutorial';
$lang->block->filterProject      = "Filter {$lang->projectCommon}";

$lang->block->productName   = $lang->productCommon . ' Name';
$lang->block->totalStory    = 'Total ' . $lang->SRCommon;
$lang->block->totalBug      = 'Total Bugs';
$lang->block->totalRelease  = 'Total Releases';
$lang->block->totalTask     = 'Total ' . $lang->task->common;
$lang->block->projectMember = 'Team Members';
$lang->block->totalMember   = '%s member(s)';

$lang->block->totalInvestment = 'Invested';
$lang->block->totalPeople     = 'Headcount';
$lang->block->spent           = 'Cost';
$lang->block->budget          = 'Budget';
$lang->block->left            = 'left';

$lang->block->summary = new stdclass();
$lang->block->summary->welcome    = '%s with Zentao! %s  and your tasks and bugs are waiting for your great work today!';
$lang->block->summary->yesterday  = '<strong>yesterday</strong>,';
$lang->block->summary->noWork     = 'A quiet day ';
$lang->block->summary->finishTask = 'You completed <a href="' .  helper::createLink('my', 'contribute', 'mode=task&type=finishedBy') . '" class="text-success">%s</a> task(s)';
$lang->block->summary->fixBug     = 'You resolved <a href="' . helper::createLink('my', 'contribute', 'mode=bug&type=resolvedBy') . '" class="text-success">%s</a> bug(s)';
$lang->block->summary->fixBugEn   = 'resolved <a href="' . helper::createLink('my', 'contribute', 'mode=bug&type=resolvedBy') . '" class="text-success">%s</a> bug(s)';

$lang->block->dashboard['default'] = 'My Overview';
$lang->block->dashboard['my']      = 'Dashboard';

$lang->block->titleList['flowchart']      = 'Essential Flow';
$lang->block->titleList['guide']          = 'User Guide';
$lang->block->titleList['statistic']      = 'Statistics';
$lang->block->titleList['recentproject']  = "My Recent {$lang->projectCommon}s";
$lang->block->titleList['assigntome']     = 'Pending Items';
$lang->block->titleList['project']        = "{$lang->projectCommon}s";
$lang->block->titleList['dynamic']        = 'Recents';
$lang->block->titleList['list']           = 'My To-dos';
$lang->block->titleList['scrumoverview']  = "{$lang->projectCommon} Overview";
$lang->block->titleList['scrumtest']      = 'Test Requests';
$lang->block->titleList['scrumlist']      = 'Iterations';
$lang->block->titleList['sprint']         = 'Iteration Overview';
$lang->block->titleList['projectdynamic'] = "Recents";
$lang->block->titleList['bug']            = 'Bugs Assigned to Me';
$lang->block->titleList['case']           = 'Cases Assigned to Me';
$lang->block->titleList['testtask']       = 'Test Requests';
$lang->block->titleList['statistic']      = "{$lang->projectCommon}s Statistics";

$lang->block->default['scrumproject'][] = array('title' => "{$lang->projectCommon} Overview",   'module' => 'scrumproject', 'code' => 'scrumoverview',  'width' => '2');
$lang->block->default['scrumproject'][] = array('title' => "{$lang->executionCommon} List",     'module' => 'scrumproject', 'code' => 'scrumlist',      'width' => '2', 'params' => array('type' => 'undone', 'count' => '20', 'orderBy' => 'id_desc'));
$lang->block->default['scrumproject'][] = array('title' => 'Pending Test Requests',             'module' => 'scrumproject', 'code' => 'scrumtest',      'width' => '2', 'params' => array('type' => 'wait', 'count' => '15', 'orderBy' => 'id_desc'));
$lang->block->default['scrumproject'][] = array('title' => "{$lang->executionCommon} Overview", 'module' => 'scrumproject', 'code' => 'sprint',         'width' => '1');
$lang->block->default['scrumproject'][] = array('title' => 'Recents',                   'module' => 'scrumproject', 'code' => 'projectdynamic', 'width' => '1');

$lang->block->default['kanbanproject']    = $lang->block->default['scrumproject'];
unset($lang->block->default['kanbanproject'][2]);
$lang->block->default['agileplusproject'] = $lang->block->default['scrumproject'];

$lang->block->default['waterfallproject'][] = array('title' => "{$lang->projectCommon} Plan", 'module' => 'waterfallproject', 'code' => 'waterfallgantt', 'width' => '2');
$lang->block->default['waterfallproject'][] = array('title' => 'Recents',                   'module' => 'waterfallproject', 'code' => 'projectdynamic', 'width' => '1');

$lang->block->default['waterfallplusproject'] = $lang->block->default['waterfallproject'];
$lang->block->default['ipdproject']           = $lang->block->default['waterfallproject'];

$lang->block->default['product'][] = array('title' => "{$lang->productCommon} Overview",                   'module' => 'product', 'code' => 'overview',         'width' => '3');
$lang->block->default['product'][] = array('title' => "Unclosed {$lang->productCommon} Statistics",         'module' => 'product', 'code' => 'statistic',        'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20'));
$lang->block->default['product'][] = array('title' => "Open {$lang->productCommon}'s Test Statistics",      'module' => 'product', 'code' => 'bugstatistic',     'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20'));
$lang->block->default['product'][] = array('title' => "{$lang->productCommon} Monthly Advancing Analysis", 'module' => 'product', 'code' => 'monthlyprogress',  'width' => '2');
$lang->block->default['product'][] = array('title' => "{$lang->productCommon} Annual Workload Statistisc",  'module' => 'product', 'code' => 'annualworkload',   'width' => '2');
$lang->block->default['product'][] = array('title' => "Unclosed {$lang->productCommon} List",              'module' => 'product', 'code' => 'list',             'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20', 'orderBy' => 'id_desc'));
$lang->block->default['product'][] = array('title' => "Unclosed {$lang->productCommon} Releases",          'module' => 'product', 'code' => 'release',          'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20'));
$lang->block->default['product'][] = array('title' => "Unclosed {$lang->productCommon} Plans",             'module' => 'product', 'code' => 'plan',             'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20'));
$lang->block->default['product'][] = array('title' => "{$lang->productCommon} Release Statistics",          'module' => 'product', 'code' => 'releasestatistic', 'width' => '1');
$lang->block->default['product'][] = array('title' => "{$lang->SRCommon} Assigned to Me",            'module' => 'product', 'code' => 'story',            'width' => '1', 'params' => array('type' => 'assignedTo', 'count' => '20', 'orderBy' => 'id_desc'));

$lang->block->default['singleproduct'][] = array('title' => "{$lang->productCommon} Statistics",                  'module' => 'singleproduct', 'code' => 'singlestatistic',        'width' => '2', 'params' => array('count' => '20'));
$lang->block->default['singleproduct'][] = array('title' => "{$lang->productCommon} Bug Statistics",              'module' => 'singleproduct', 'code' => 'singlebugstatistic',     'width' => '2', 'params' => array('count' => '20'));
$lang->block->default['singleproduct'][] = array('title' => "{$lang->productCommon} Roadmap",                    'module' => 'singleproduct', 'code' => 'roadmap',                'width' => '2');
$lang->block->default['singleproduct'][] = array('title' => "{$lang->SRCommon} Assigned to Me",                  'module' => 'singleproduct', 'code' => 'singlestory',            'width' => '2', 'params' => array('type' => 'assignedTo', 'count' => '20', 'orderBy' => 'id_desc'));
$lang->block->default['singleproduct'][] = array('title' => "{$lang->productCommon} Plans",                      'module' => 'singleproduct', 'code' => 'singleplan',             'width' => '2', 'params' => array('count' => '20'));
$lang->block->default['singleproduct'][] = array('title' => "{$lang->productCommon} Releases",                   'module' => 'singleproduct', 'code' => 'singlerelease',          'width' => '2', 'params' => array('count' => '20'));
$lang->block->default['singleproduct'][] = array('title' => "Recents",                                           'module' => 'singleproduct', 'code' => 'singledynamic',          'width' => '1');
$lang->block->default['singleproduct'][] = array('title' => "Monthly{$lang->productCommon}Progress Analysis",       'module' => 'singleproduct', 'code' => 'singlemonthlyprogress',  'width' => '1');

$lang->block->default['qa'][] = array('title' => 'Test Report',           'module' => 'qa', 'code' => 'statistic', 'width' => '2', 'params' => array('type' => 'noclosed',   'count' => '20'));
$lang->block->default['qa'][] = array('title' => 'Pending Test Requests', 'module' => 'qa', 'code' => 'testtask',  'width' => '2', 'params' => array('type' => 'wait',       'count' => '15', 'orderBy' => 'id_desc'));
$lang->block->default['qa'][] = array('title' => 'My Bugs',               'module' => 'qa', 'code' => 'bug',       'width' => '1', 'params' => array('type' => 'assignedTo', 'count' => '15', 'orderBy' => 'id_desc'));
$lang->block->default['qa'][] = array('title' => 'Cases Assigned to Me',  'module' => 'qa', 'code' => 'case',      'width' => '1', 'params' => array('type' => 'assigntome', 'count' => '15', 'orderBy' => 'id_desc'));

$lang->block->default['full']['my'][] = array('title' => 'welcome',                                         'module' => 'welcome',         'code' => 'welcome',         'width' => '2');
$lang->block->default['full']['my'][] = array('title' => 'Guides',                                          'module' => 'guide',           'code' => 'guide',           'width' => '2');
$lang->block->default['full']['my'][] = array('title' => 'My Work',                                         'module' => 'assigntome',      'code' => 'assigntome',      'width' => '2', 'params' => array('todoCount' => '20',  'taskCount' => '20', 'bugCount' => '20', 'riskCount' => '20', 'issueCount' => '20', 'storyCount' => '20', 'reviewCount' => '20', 'meetingCount' => '20', 'feedbackCount' => '20'));
$lang->block->default['full']['my'][] = array('title' => "Recent {$lang->projectCommon}s",                  'module' => 'project',         'code' => 'recentproject',   'width' => '2');
$lang->block->default['full']['my'][] = array('title' => "Uncompleted {$lang->projectCommon}s",              'module' => 'project',         'code' => 'project',         'width' => '2', 'params' => array('type' => 'undone',   'count' => '20', 'orderBy' => 'id_desc'));
$lang->block->default['full']['my'][] = array('title' => "Uncompleted {$lang->projectCommon}s Statistics",                'module' => 'project',         'code' => 'statistic',       'width' => '2', 'params' => array('type' => 'undone',   'count' => '20'));
$lang->block->default['full']['my'][] = array('title' => "Uncompleted {$lang->execution->common}s Statistics",     'module' => 'execution',       'code' => 'statistic',       'width' => '2', 'params' => array('type' => 'undone',   'count' => '20'));
if($config->vision != 'lite') $lang->block->default['full']['my'][] = array('title' => "Unclosed {$lang->productCommon}s Statistic",       'module' => 'product',         'code' => 'statistic',       'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20'));
if($config->vision != 'lite') $lang->block->default['full']['my'][] = array('title' => "Unclosed {$lang->productCommon}'s test statistics", 'module' => 'qa',              'code' => 'statistic',       'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20'));
$lang->block->default['full']['my'][] = array('title' => "Zentao Dynamic",                                  'module' => 'zentaodynamic',   'code' => 'zentaodynamic',   'width' => '1');
$lang->block->default['full']['my'][] = array('title' => 'Recents',                                         'module' => 'dynamic',         'code' => 'dynamic',         'width' => '1');
$lang->block->default['full']['my'][] = array('title' => "Team",                                            'module' => 'teamachievement', 'code' => 'teamachievement', 'width' => '1');
if($config->vision != 'lite') $lang->block->default['full']['my'][] = array('title' => "{$lang->productCommon} Overview",                 'module' => 'product',         'code' => 'overview',        'width' => '1');
$lang->block->default['full']['my'][] = array('title' => "{$lang->projectCommon} Overview",                 'module' => 'project',         'code' => 'overview',        'width' => '1');
$lang->block->default['full']['my'][] = array('title' => "{$lang->execution->common} Overview",             'module' => 'execution',       'code' => 'overview',        'width' => '1');

$lang->block->default['doc'][] = array('title' => 'Statistics',                      'module' => 'doc', 'code' => 'docstatistic',    'width' => '2');
$lang->block->default['doc'][] = array('title' => 'My Favorites',                    'module' => 'doc', 'code' => 'docmycollection', 'width' => '2');
$lang->block->default['doc'][] = array('title' => 'My Created Documents',            'module' => 'doc', 'code' => 'docmycreated',    'width' => '2');
$lang->block->default['doc'][] = array('title' => 'Recently Updated Documents',      'module' => 'doc', 'code' => 'docrecentupdate', 'width' => '2');
if($config->vision == 'rnd') $lang->block->default['doc'][] = array('title' => "{$lang->productCommon} Document", 'module' => 'doc', 'code' => 'productdoc',      'width' => '2', 'params' => array('count' => '20'));
$lang->block->default['doc'][] = array('title' => "{$lang->projectCommon} Document", 'module' => 'doc', 'code' => 'projectdoc',      'width' => '2', 'params' => array('count' => '20'));
$lang->block->default['doc'][] = array('title' => 'Recents',                         'module' => 'doc', 'code' => 'docdynamic',      'width' => '1');
$lang->block->default['doc'][] = array('title' => 'Most Viewed',                     'module' => 'doc', 'code' => 'docviewlist',     'width' => '1');
$lang->block->default['doc'][] = array('title' => 'Top Favorites',                   'module' => 'doc', 'code' => 'doccollectlist',  'width' => '1');

$lang->block->count   = 'Count';
$lang->block->type    = 'Type';
$lang->block->orderBy = 'Order';

$lang->block->availableBlocks['todo']        = 'To-dos';
$lang->block->availableBlocks['task']        = 'Tasks';
$lang->block->availableBlocks['bug']         = 'Bugs';
$lang->block->availableBlocks['case']        = 'Cases';
$lang->block->availableBlocks['story']       = "Stories";
$lang->block->availableBlocks['requirement'] = "{$lang->URCommon}";
$lang->block->availableBlocks['product']     = $lang->productCommon . 'List';
$lang->block->availableBlocks['execution']   = $lang->execution->common . 'List';
$lang->block->availableBlocks['plan']        = 'Plans';
$lang->block->availableBlocks['release']     = 'Releases';
$lang->block->availableBlocks['build']       = 'Builds';
$lang->block->availableBlocks['testcase']    = 'Test Cases';
$lang->block->availableBlocks['testtask']    = 'Test Requests';
$lang->block->availableBlocks['risk']        = 'Risks';
$lang->block->availableBlocks['reviewissue'] = 'Review Issue';
$lang->block->availableBlocks['issue']       = 'Issues';
$lang->block->availableBlocks['meeting']     = 'Meetings';
$lang->block->availableBlocks['feedback']    = 'Feedbacks';
$lang->block->availableBlocks['ticket']      = 'Tickets';
$lang->block->availableBlocks['demand']      = 'Stories';

$lang->block->modules['project'] = new stdclass();
$lang->block->modules['project']->availableBlocks['overview']      = "{$lang->projectCommon} Overview";
$lang->block->modules['project']->availableBlocks['recentproject'] = "My Recent {$lang->projectCommon}s";
$lang->block->modules['project']->availableBlocks['statistic']     = "{$lang->projectCommon}s Statistics";
$lang->block->modules['project']->availableBlocks['project']       = "{$lang->projectCommon}s";

$lang->block->modules['scrumproject'] = new stdclass();
$lang->block->modules['scrumproject']->availableBlocks['scrumoverview']  = "{$lang->projectCommon} Overview";
$lang->block->modules['scrumproject']->availableBlocks['scrumlist']      = $lang->executionCommon . ' List';
$lang->block->modules['scrumproject']->availableBlocks['sprint']         = $lang->executionCommon . ' Overview';
$lang->block->modules['scrumproject']->availableBlocks['scrumtest']      = 'Test Requests';
$lang->block->modules['scrumproject']->availableBlocks['projectdynamic'] = 'Recents';

$lang->block->modules['waterfallproject'] = new stdclass();
$lang->block->modules['waterfallproject']->availableBlocks['waterfallgantt'] = "{$lang->projectCommon} Plan";
$lang->block->modules['waterfallproject']->availableBlocks['projectdynamic'] = 'Recents';

$lang->block->modules['agileplusproject']     = $lang->block->modules['scrumproject'];
$lang->block->modules['waterfallplusproject'] = $lang->block->modules['waterfallproject'];
$lang->block->modules['ipdproject']           = $lang->block->modules['waterfallproject'];

$lang->block->modules['product'] = new stdclass();
$lang->block->modules['product']->availableBlocks['overview']         = "{$lang->productCommon} Overview";
$lang->block->modules['product']->availableBlocks['statistic']        = "{$lang->productCommon}s Statistics";
$lang->block->modules['product']->availableBlocks['releasestatistic'] = "{$lang->productCommon} Release Statistics";
$lang->block->modules['product']->availableBlocks['bugstatistic']     = "{$lang->productCommon}'s Test Statistics";
$lang->block->modules['product']->availableBlocks['annualworkload']   = "{$lang->productCommon} Annual Workload Statistics";
$lang->block->modules['product']->availableBlocks['monthlyprogress']  = "Monthly {$lang->productCommon} Progress Analysis";
$lang->block->modules['product']->availableBlocks['list']             = "{$lang->productCommon} List";
$lang->block->modules['product']->availableBlocks['plan']             = "{$lang->productCommon} Plans";
$lang->block->modules['product']->availableBlocks['release']          = "{$lang->productCommon} Releases";
$lang->block->modules['product']->availableBlocks['story']            = "{$lang->SRCommon} List";

$lang->block->modules['singleproduct'] = new stdclass();
$lang->block->modules['singleproduct']->availableBlocks['singlestatistic']       = "{$lang->productCommon} Statistics";
$lang->block->modules['singleproduct']->availableBlocks['singlebugstatistic']    = "{$lang->productCommon} Bug Statistics";
$lang->block->modules['singleproduct']->availableBlocks['roadmap']               = "{$lang->productCommon} RoadMap";
$lang->block->modules['singleproduct']->availableBlocks['singlestory']           = "{$lang->SRCommon} List";
$lang->block->modules['singleproduct']->availableBlocks['singleplan']            = "{$lang->productCommon} Plans";
$lang->block->modules['singleproduct']->availableBlocks['singlerelease']         = "{$lang->productCommon} Releases";
$lang->block->modules['singleproduct']->availableBlocks['singledynamic']         = 'Recents';
$lang->block->modules['singleproduct']->availableBlocks['singlemonthlyprogress'] = "Monthly {$lang->productCommon} Progress Analysis";

$lang->block->modules['execution'] = new stdclass();
$lang->block->modules['execution']->availableBlocks['statistic'] = $lang->execution->common . 's Statistics';
$lang->block->modules['execution']->availableBlocks['overview']  = $lang->execution->common . ' Overview';
$lang->block->modules['execution']->availableBlocks['list']      = $lang->execution->common . ' List';
$lang->block->modules['execution']->availableBlocks['task']      = 'Tasks';
$lang->block->modules['execution']->availableBlocks['build']     = 'Builds';

$lang->block->modules['qa'] = new stdclass();
$lang->block->modules['qa']->availableBlocks['statistic'] = "{$lang->productCommon} Test Statistics";
$lang->block->modules['qa']->availableBlocks['bug']       = 'Bugs';
$lang->block->modules['qa']->availableBlocks['case']      = 'Cases';
$lang->block->modules['qa']->availableBlocks['testtask']  = 'Test Requests';

$lang->block->modules['todo'] = new stdclass();
$lang->block->modules['todo']->availableBlocks['list'] = 'To-dos';

$lang->block->modules['doc'] = new stdclass();
$lang->block->modules['doc']->availableBlocks['docstatistic']    = 'Statistics';
$lang->block->modules['doc']->availableBlocks['docdynamic']      = 'Recents';
$lang->block->modules['doc']->availableBlocks['docmycollection'] = 'My Favorites';
$lang->block->modules['doc']->availableBlocks['docmycreated']    = 'My Created';
$lang->block->modules['doc']->availableBlocks['docrecentupdate'] = 'Recently Updated';
$lang->block->modules['doc']->availableBlocks['docviewlist']     = 'Top Viewed';
if($config->vision == 'rnd') $lang->block->modules['doc']->availaableBlocks['productdoc'] = $lang->productCommon . ' Document';
$lang->block->modules['doc']->availableBlocks['doccollectlist']  = 'Top Favorites';
$lang->block->modules['doc']->availableBlocks['projectdoc']      = $lang->projectCommon . ' Document';

$lang->block->orderByList = new stdclass();
$lang->block->orderByList->product = array();
$lang->block->orderByList->product['id_asc']      = 'ID Ascending';
$lang->block->orderByList->product['id_desc']     = 'ID Descending';
$lang->block->orderByList->product['status_asc']  = 'Status Ascending';
$lang->block->orderByList->product['status_desc'] = 'Status Descending';

$lang->block->orderByList->project = array();
$lang->block->orderByList->project['id_asc']      = "ID Ascending";
$lang->block->orderByList->project['id_desc']     = "ID Descending";
$lang->block->orderByList->project['status_asc']  = "Status Ascending";
$lang->block->orderByList->project['status_desc'] = "Status Descending";

$lang->block->orderByList->execution = array();
$lang->block->orderByList->execution['id_asc']      = 'ID Ascending';
$lang->block->orderByList->execution['id_desc']     = 'ID Descending';
$lang->block->orderByList->execution['status_asc']  = 'Status Ascending';
$lang->block->orderByList->execution['status_desc'] = 'Status Descending';

$lang->block->orderByList->task = array();
$lang->block->orderByList->task['id_asc']        = 'ID Ascending';
$lang->block->orderByList->task['id_desc']       = 'ID Descending';
$lang->block->orderByList->task['pri_asc']       = 'Priority (Low to High)';
$lang->block->orderByList->task['pri_desc']      = 'Priority (High to Low)';
$lang->block->orderByList->task['estimate_asc']  = 'Task Estimates Ascending';
$lang->block->orderByList->task['estimate_desc'] = 'Task Estimates Descending';
$lang->block->orderByList->task['status_asc']    = 'Status Ascending';
$lang->block->orderByList->task['status_desc']   = 'Status Descending';
$lang->block->orderByList->task['deadline_asc']  = 'Deadline Ascending';
$lang->block->orderByList->task['deadline_desc'] = 'Deadline Descending';

$lang->block->orderByList->bug = array();
$lang->block->orderByList->bug['id_asc']        = 'ID Ascending';
$lang->block->orderByList->bug['id_desc']       = 'ID Descending';
$lang->block->orderByList->bug['pri_asc']       = 'Priority (Low to High)';
$lang->block->orderByList->bug['pri_desc']      = 'Priority (High to Low)';
$lang->block->orderByList->bug['severity_asc']  = 'Severity Ascending';
$lang->block->orderByList->bug['severity_desc'] = 'Severity Descending';

$lang->block->orderByList->case = array();
$lang->block->orderByList->case['id_asc']   = 'ID Ascending';
$lang->block->orderByList->case['id_desc']  = 'ID Descending';
$lang->block->orderByList->case['pri_asc']  = 'Priority (Low to High)';
$lang->block->orderByList->case['pri_desc'] = 'Priority (High to Low)';

$lang->block->orderByList->story = array();
$lang->block->orderByList->story['id_asc']      = 'ID Ascending';
$lang->block->orderByList->story['id_desc']     = 'ID Descending';
$lang->block->orderByList->story['pri_asc']     = 'Priority (Low to High)';
$lang->block->orderByList->story['pri_desc']    = 'Priority (High to Low)';
$lang->block->orderByList->story['status_asc']  = 'Status Ascending';
$lang->block->orderByList->story['status_desc'] = 'Status Descending';
$lang->block->orderByList->story['stage_asc']   = 'Phase Ascending';
$lang->block->orderByList->story['stage_desc']  = 'Phase Descending';

$lang->block->todoCount     = 'To-dos';
$lang->block->taskCount     = 'Tasks';
$lang->block->bugCount      = 'Bugs';
$lang->block->riskCount     = 'Risks';
$lang->block->issueCount    = 'Issues';
$lang->block->storyCount    = $lang->SRCommon . 'count';
$lang->block->reviewCount   = 'Reviews';
$lang->block->meetingCount  = 'Meetings';
$lang->block->feedbackCount = 'Feedbacks';
$lang->block->ticketCount   = 'Tickets';

$lang->block->typeList = new stdclass();
$lang->block->typeList->task['assignedTo'] = 'Assigned to Me';
$lang->block->typeList->task['openedBy']   = 'Created by Me';
$lang->block->typeList->task['finishedBy'] = 'Completed by Me';
$lang->block->typeList->task['closedBy']   = 'Closed by Me';
$lang->block->typeList->task['canceledBy'] = 'Canceled by Me';

$lang->block->typeList->bug['assignedTo'] = 'Assigned to Me';
$lang->block->typeList->bug['openedBy']   = 'Created by Me';
$lang->block->typeList->bug['resolvedBy'] = 'Resolved by Me';
$lang->block->typeList->bug['closedBy']   = 'Closed by Me';

$lang->block->typeList->case['assigntome'] = 'Assigned to Me';
$lang->block->typeList->case['openedbyme'] = 'Created by Me';

$lang->block->typeList->story['assignedTo'] = 'Assigned to Me';
$lang->block->typeList->story['reviewBy']   = 'My Pending Review';
$lang->block->typeList->story['openedBy']   = 'Created by Me';
$lang->block->typeList->story['reviewedBy'] = 'Reviewed by Me';
$lang->block->typeList->story['closedBy']   = 'Closed by Me' ;

$lang->block->typeList->product['noclosed'] = 'Open';
$lang->block->typeList->product['closed']   = 'Closed';
$lang->block->typeList->product['all']      = 'All';
$lang->block->typeList->product['involved'] = 'My Involvement';

$lang->block->typeList->project['undone']   = 'Uncompleted';
$lang->block->typeList->project['doing']    = 'Doing';
$lang->block->typeList->project['all']      = 'All';
$lang->block->typeList->project['involved'] = 'My Involvement';

$lang->block->typeList->projectAll['all']       = 'All';
$lang->block->typeList->projectAll['undone']    = 'Uncompleted';
$lang->block->typeList->projectAll['wait']      = 'Waiting';
$lang->block->typeList->projectAll['doing']     = 'Doing';
$lang->block->typeList->projectAll['suspended'] = 'On Hold';
$lang->block->typeList->projectAll['closed']    = 'Closed';

$lang->block->typeList->execution['undone']   = 'Uncompleted';
$lang->block->typeList->execution['doing']    = 'Doing';
$lang->block->typeList->execution['all']      = 'All';
$lang->block->typeList->execution['involved'] = 'My Involvement';

$lang->block->typeList->scrum['undone']   = 'Uncompleted';
$lang->block->typeList->scrum['doing']    = 'Doing';
$lang->block->typeList->scrum['all']      = 'All';
$lang->block->typeList->scrum['involved'] = 'My Involvement';

$lang->block->typeList->testtask['wait']    = 'Waiting';
$lang->block->typeList->testtask['doing']   = 'Doing';
$lang->block->typeList->testtask['blocked'] = 'Blocked';
$lang->block->typeList->testtask['done']    = 'Done';
$lang->block->typeList->testtask['all']     = 'All';

$lang->block->typeList->risk['all']      = 'All';
$lang->block->typeList->risk['active']   = 'Open';
$lang->block->typeList->risk['assignTo'] = 'Assigned to Me';
$lang->block->typeList->risk['assignBy'] = 'Assigned by Me';
$lang->block->typeList->risk['closed']   = 'Closed';
$lang->block->typeList->risk['hangup']   = 'On Hold';
$lang->block->typeList->risk['canceled'] = 'Canceled';

$lang->block->typeList->issue['all']      = 'All';
$lang->block->typeList->issue['open']     = 'Public';
$lang->block->typeList->issue['assignto'] = 'Assigned to Me';
$lang->block->typeList->issue['assignby'] = 'Assigned by Me';
$lang->block->typeList->issue['closed']   = 'Closed';
$lang->block->typeList->issue['resolved'] = 'Resolved';
$lang->block->typeList->issue['canceled'] = 'Canceled';

$lang->block->welcomeList['06:00'] = 'Good morning, %s';
$lang->block->welcomeList['11:30'] = 'Good afternoon, %s';
$lang->block->welcomeList['13:30'] = 'Good afternoon, %s';
$lang->block->welcomeList['19:00'] = 'Good evening, %s';

$lang->block->gridOptions[8] = 'Left';
$lang->block->gridOptions[4] = 'Right';

$lang->block->widthOptions['1'] = 'Short Block';
$lang->block->widthOptions['2'] = 'Long Block';
$lang->block->widthOptions['3'] = 'Max Block';

$lang->block->flowchart            = array();
$lang->block->flowchart['admin']   = array('Admins', 'Manage Departments', 'Add User', 'Manage Permissions');
if($config->systemMode == 'ALM') $lang->block->flowchart['program'] = array('Program Owner', 'Create Program', "Link {$lang->productCommon}", "Create {$lang->projectCommon}", "Budgeting and planning", 'Add Stakeholder');
$lang->block->flowchart['product'] = array($lang->productCommon . ' Manager', 'Create' . $lang->productCommon, 'Manage Modules', 'Manage Plans', 'Manage Stories', 'Create Release');
$lang->block->flowchart['project'] = array('Project Manager', "Create {$lang->projectCommon} & " . $lang->execution->common, 'Manage Team', 'Link Stories', 'Decompose Tasks', 'Track Progress');
$lang->block->flowchart['dev']     = array('Developers', 'Claim Tasks & Bugs', 'Design Solution', 'Commit Code', 'Update Status', 'Complete Tasks & Bugs');
$lang->block->flowchart['tester']  = array('Test Team', 'Write Cases', 'Execute Cases', 'Report Bugs', 'Verify Bugs', 'Close Bugs');

$lang->block->zentaoapp = new stdclass();
$lang->block->zentaoapp->common               = 'ZenTao App';
$lang->block->zentaoapp->thisYearInvestment   = 'Investment This Year';
$lang->block->zentaoapp->sinceTotalInvestment = 'Total Investment';
$lang->block->zentaoapp->myStory              = 'My Stories';
$lang->block->zentaoapp->allStorySum          = 'Total Stories';
$lang->block->zentaoapp->storyCompleteRate    = 'Story Completion Rate';
$lang->block->zentaoapp->latestExecution      = 'Recent Executions';
$lang->block->zentaoapp->involvedExecution    = 'My Executions';
$lang->block->zentaoapp->mangedProduct        = "Manged {$lang->productCommon}";
$lang->block->zentaoapp->involvedProject      = "Involved {$lang->projectCommon}";
$lang->block->zentaoapp->customIndexCard      = 'Customize Dashboard';
$lang->block->zentaoapp->createStory          = 'Create Story';
$lang->block->zentaoapp->createEffort         = 'Log Effort';
$lang->block->zentaoapp->createDoc            = 'Create Doc';
$lang->block->zentaoapp->createTodo           = 'Create To-do';
$lang->block->zentaoapp->workbench            = 'Workspace';
$lang->block->zentaoapp->notSupportKanban     = 'R&D Kanban Mode is not supported on mobile.';
$lang->block->zentaoapp->notSupportVersion    = 'This version of ZenTao is not currently supported on the mobile terminal';
$lang->block->zentaoapp->incompatibleVersion  = 'Your ZenTao version is outdated. Please upgrade to the latest version and try again.';
$lang->block->zentaoapp->canNotGetVersion     = 'Failed to retrieve ZenTao version. Please check if the URL is correct.';
$lang->block->zentaoapp->desc                 = "ZenTao Mobile APP provides you with a mobile work environment to manage personal To-dos and track {$lang->projectCommon} progress anytime, enhancing the flexibility and agility of {$lang->projectCommon} management.";
$lang->block->zentaoapp->downloadTip          = 'Scan QR code to download';

$lang->block->zentaoclient = new stdClass();
$lang->block->zentaoclient->common = 'ZenTao Client';
$lang->block->zentaoclient->desc   = 'Use ZenTao Desktop for direct access without switching browsers. It also features Chat, Notifications, Bots, and embedded Mini-Programs, making team collaboration more efficient.';

$lang->block->zentaoclient->edition = new stdclass();
$lang->block->zentaoclient->edition->win64   = 'Windows';
$lang->block->zentaoclient->edition->linux64 = 'Linux';
$lang->block->zentaoclient->edition->mac64   = 'Mac OS';

$lang->block->guideTabs['flowchart']      = 'Essential Flow';
if($config->systemMode != 'PLM') $lang->block->guideTabs['systemMode']     = 'Operating Modes';
$lang->block->guideTabs['visionSwitch']   = 'Switch Interface';
$lang->block->guideTabs['themeSwitch']    = 'Switch Theme ';
$lang->block->guideTabs['preference']     = 'Personalization';
$lang->block->guideTabs['downloadClient'] = 'Download Desktop';
$lang->block->guideTabs['downloadMobile'] = 'Download Mobile APP';

$lang->block->themes['default']    = 'Default';
$lang->block->themes['blue']       = 'Sky Blue';
$lang->block->themes['green']      = 'Green';
$lang->block->themes['red']        = 'Red';
$lang->block->themes['purple']     = 'Purple';
$lang->block->themes['blackberry'] = 'Blackberry';

$lang->block->visionTitle            = 'ZenTao offers two interfaces:';
$lang->block->visions['rnd']         = new stdclass();
$lang->block->visions['rnd']->key    = 'rnd';
$lang->block->visions['rnd']->title  = 'R&D interface';
$lang->block->visions['rnd']->text   = "An all-in-one lifecycle project management solution.";
$lang->block->visions['lite']        = new stdclass();
$lang->block->visions['lite']->key   = 'lite';
$lang->block->visions['lite']->title = 'Operations Management Interface';
$lang->block->visions['lite']->text  = "A visual, intuitive experience tailored for non-R&D teams.";

$lang->block->customModes['light'] = 'Lightweight Management Mode';
$lang->block->customModes['ALM']   = 'ALM Mode';

$lang->block->honorary = array();
$lang->block->honorary['bug']    = 'Bug King';
$lang->block->honorary['task']   = 'Task King';
$lang->block->honorary['review'] = 'Review King';

$lang->block->welcome = new stdclass();
$lang->block->welcome->common     = 'Welcome Overview';
$lang->block->welcome->reviewByMe = 'My Pending Reviews';
$lang->block->welcome->assignToMe = 'Assigned to Me';

$lang->block->welcome->reviewList = array();
$lang->block->welcome->reviewList['story']      = 'Stories';
$lang->block->welcome->reviewList['reviewByMe'] = 'My Pending Reviews';

$lang->block->welcome->assignList = array();
$lang->block->welcome->assignList['task'] = 'Tasks';
if($config->vision != 'or') $lang->block->welcome->assignList['bug']   = 'Bugs';
if($config->vision != 'or') $lang->block->welcome->assignList['story'] = 'SRStroy';
$lang->block->welcome->assignList['testcase'] = 'Cases';
if($config->URAndSR && $config->vision != 'or')  $lang->block->welcome->assignList['requirement'] = "Features";
if($config->enableER && $config->vision != 'or') $lang->block->welcome->assignList['epic']        = "{$lang->ERCommon}";

$lang->block->customModeTip = new stdClass();
$lang->block->customModeTip->common = 'ZenTao offers two operating modes:';
$lang->block->customModeTip->ALM    = '';
$lang->block->customModeTip->light  = "";

$lang->block->productstatistic = new stdclass();
$lang->block->productstatistic->effectiveStory  = 'Effective Stories';
$lang->block->productstatistic->delivered       = 'Delivered';
$lang->block->productstatistic->unclosed        = 'Open';
$lang->block->productstatistic->storyStatistics = 'Story Statistics';
$lang->block->productstatistic->monthDone       = 'Completed this month <span class="text-success font-bold">%s</span>';
$lang->block->productstatistic->monthOpened     = 'Added this month <span class="text-primary font-bold">%s</span>';
$lang->block->productstatistic->opened          = 'Added';
$lang->block->productstatistic->done            = 'Done';
$lang->block->productstatistic->news            = 'Latest Product Progress';
$lang->block->productstatistic->newPlan         = 'Latest Plan';
$lang->block->productstatistic->newExecution    = 'Latest Execution';
$lang->block->productstatistic->newRelease      = 'Latest Release';
$lang->block->productstatistic->deliveryRate    = 'Story Delivery Rate';

$lang->block->projectoverview = new stdclass();
$lang->block->projectoverview->totalProject  = 'Total Projects';
$lang->block->projectoverview->thisYear      = 'Completed This Year';
$lang->block->projectoverview->lastThreeYear = 'Project Completion Trend (Last 3 Years)';

$lang->block->projectstatistic = new stdclass();
$lang->block->projectstatistic->story            = 'Story';
$lang->block->projectstatistic->cost             = 'Cost';
$lang->block->projectstatistic->task             = 'Task';
$lang->block->projectstatistic->bug              = 'Bug';
$lang->block->projectstatistic->storyPoints      = 'Total';
$lang->block->projectstatistic->done             = 'Done';
$lang->block->projectstatistic->undone           = 'Open';
$lang->block->projectstatistic->costs            = 'Invested';
$lang->block->projectstatistic->consumed         = 'Cost';
$lang->block->projectstatistic->remainder        = 'Left';
$lang->block->projectstatistic->tasks            = 'tasks';
$lang->block->projectstatistic->wait             = 'Waiting';
$lang->block->projectstatistic->doing            = 'Doing';
$lang->block->projectstatistic->bugs             = 'bugs';
$lang->block->projectstatistic->stories          = 'stories';
$lang->block->projectstatistic->closed           = 'Closed';
$lang->block->projectstatistic->activated        = 'Activated';
$lang->block->projectstatistic->unit             = 'unit';
$lang->block->projectstatistic->total            = 'Total';
$lang->block->projectstatistic->SP               = $config->hourUnit;
$lang->block->projectstatistic->personDay        = 'PD';
$lang->block->projectstatistic->day              = 'day(s)';
$lang->block->projectstatistic->hour             = 'Hours';
$lang->block->projectstatistic->leftDaysPre      = 'Time Remaining';
$lang->block->projectstatistic->delayDaysPre     = 'Overdue by';
$lang->block->projectstatistic->existRisks       = 'Risks';
$lang->block->projectstatistic->existIssues      = 'Issues';
$lang->block->projectstatistic->lastestExecution = 'Latest Execution';
$lang->block->projectstatistic->projectClosed    = "{$lang->projectCommon} has been closed.";
$lang->block->projectstatistic->longTimeProject  = "Long Term {$lang->projectCommon}";
$lang->block->projectstatistic->totalProgress    = 'Total Progress';
$lang->block->projectstatistic->totalProgressTip = "<strong>Total Project Progress</strong> = Total Consumed Task Hours / (Total Consumed Task Hours + Total Remaining Task Hours)<br/>
<strong>Total Consumed Task Hours </strong>: Sum of consumed hours for all tasks in the project, excluding deleted tasks, parent tasks, and tasks in deleted executions.<br/>
<strong>Total Remaining Task Hours </strong>: Sum of remaining hours for all tasks in the project, excluding deleted tasks, parent tasks, and tasks in deleted executions.";
$lang->block->projectstatistic->currentCost      = 'Current Costs';
$lang->block->projectstatistic->sv               = 'Schedule Variance(SV)';
$lang->block->projectstatistic->pv               = 'Planned Value(PV)';
$lang->block->projectstatistic->ev               = 'Earned Value(EV)';
$lang->block->projectstatistic->cv               = 'Cost Variance(CV)';
$lang->block->projectstatistic->ac               = 'Actual Cost(AC)';

$lang->block->qastatistic = new stdclass();
$lang->block->qastatistic->fixBugRate        = 'Bug Fix Rate';
$lang->block->qastatistic->closedBugRate     = 'Bug Closed Rate';
$lang->block->qastatistic->totalBug          = 'Total Bugs';
$lang->block->qastatistic->bugStatistics     = 'Bug Statistics';
$lang->block->qastatistic->addYesterday      = 'Added Yesterday';
$lang->block->qastatistic->addToday          = 'Added Today';
$lang->block->qastatistic->resolvedYesterday = 'Resolved Yesterday';
$lang->block->qastatistic->resolvedToday     = 'Resolved Today';
$lang->block->qastatistic->closedYesterday   = 'Closed Yesterday';
$lang->block->qastatistic->closedToday       = 'Closed Today';
$lang->block->qastatistic->unclosedTesttasks = 'Unclosed Test tasks';
$lang->block->qastatistic->bugStatusStat     = 'Monthly Bug Trend';

$lang->block->bugstatistic = new stdclass();
$lang->block->bugstatistic->effective = 'Valid Bugs';
$lang->block->bugstatistic->fixed     = 'Fixed';
$lang->block->bugstatistic->activated = 'Activated';

$lang->block->executionstatistic = new stdclass();
$lang->block->executionstatistic->allProject        = 'All Projects';
$lang->block->executionstatistic->progress          = 'Progress';
$lang->block->executionstatistic->totalEstimate     = 'Estimate';
$lang->block->executionstatistic->totalConsumed     = 'Cost';
$lang->block->executionstatistic->totalLeft         = 'Left';
$lang->block->executionstatistic->burn              = $lang->execution->common . ' Burndown Chart';
$lang->block->executionstatistic->cfd               = $lang->execution->common . ' Cumulative Flow Diagram';
$lang->block->executionstatistic->story             = 'Story';
$lang->block->executionstatistic->doneStory         = 'Done';
$lang->block->executionstatistic->totalStory        = 'Total';
$lang->block->executionstatistic->task              = 'Task';
$lang->block->executionstatistic->totalTask         = 'Total';
$lang->block->executionstatistic->undoneTask        = 'Uncompleted';
$lang->block->executionstatistic->yesterdayDoneTask = 'Complated Yesterday';

$lang->block->executionoverview = new stdclass();
$lang->block->executionoverview->totalExecution = "Total {$lang->execution->common}s";
$lang->block->executionoverview->thisYear       = 'Completed This Year';
$lang->block->executionoverview->statusCount    = "Status Distribution of Open {$lang->execution->common}s";

$lang->block->productoverview = new stdclass();
$lang->block->productoverview->overview                = 'Overview Data';
$lang->block->productoverview->yearFinished            = 'Annual Product Progress Statistics';
$lang->block->productoverview->productLineCount        = 'Total Product Lines';
$lang->block->productoverview->productCount            = 'Total Products';
$lang->block->productoverview->releaseCount            = 'Released This Year';
$lang->block->productoverview->milestoneCount          = 'Released Milestones';
$lang->block->productoverview->unfinishedPlanCount     = 'Uncompleted Plans';
$lang->block->productoverview->unclosedStoryCount      = 'Uncompleted Stories';
$lang->block->productoverview->activeBugCount          = 'Active Bugs';
$lang->block->productoverview->finishedReleaseCount    = 'Completed Releases';
$lang->block->productoverview->finishedStoryCount      = 'Completed Stories';
$lang->block->productoverview->finishedStoryPoint      = 'Completed Story Points';
$lang->block->productoverview->thisWeek                = 'This Week';

$lang->block->productlist = new stdclass();
$lang->block->productlist->unclosedFeedback  = 'Unclosed Feedback';
$lang->block->productlist->activatedStory    = 'Active Stories';
$lang->block->productlist->storyCompleteRate = 'Story Completion Rate';
$lang->block->productlist->activatedBug      = 'Active Bugs';

$lang->block->sprint = new stdclass();
$lang->block->sprint->totalExecution = "Total{$lang->executionCommon}";
$lang->block->sprint->thisYear       = 'Completed This Year';
$lang->block->sprint->statusCount    = "{$lang->executionCommon} Status Distribution";

$lang->block->zentaodynamic = new stdclass();
$lang->block->zentaodynamic->zentaosalon  = 'ZenTao · China Travel';
$lang->block->zentaodynamic->publicclass  = 'ZenTao Webinar';
$lang->block->zentaodynamic->release      = 'Latest Release';
$lang->block->zentaodynamic->registration = 'Register Now';
$lang->block->zentaodynamic->reservation  = 'Book Now';

$lang->block->monthlyprogress = new stdclass();
$lang->block->monthlyprogress->doneStoryEstimateTrendChart = "The Completed Story Scope Trend Chart";
$lang->block->monthlyprogress->storyTrendChart             = "The Created and Completed Stories Trend Chart";
$lang->block->monthlyprogress->bugTrendChart               = 'The New and Resolved Bugs Trend Chart';

$lang->block->annualworkload = new stdclass();
$lang->block->annualworkload->doneStoryEstimate = "Completed Story Scope";
$lang->block->annualworkload->doneStoryCount    = "Completed Stories";
$lang->block->annualworkload->resolvedBugCount  = 'Resolved Bugs';

$lang->block->releasestatistic = new stdclass();
$lang->block->releasestatistic->monthly = 'Monthly releases trend chart';
$lang->block->releasestatistic->annual  = "Annual Release Leaderboard (%s)";

$lang->block->teamachievement = new stdclass();
$lang->block->teamachievement->finishedTasks  = 'Completed Tasks';
$lang->block->teamachievement->createdStories = 'Created Stories';
$lang->block->teamachievement->closedBugs     = 'Closed Bugs';
$lang->block->teamachievement->runCases       = 'Executed Cases';
$lang->block->teamachievement->consumedHours  = 'Cost';
$lang->block->teamachievement->totalWorkload  = 'Total Workload';
$lang->block->teamachievement->vs             = 'VS Yesterday';
$lang->block->teamachievement->accrued        = 'Total';

$lang->block->estimate = new stdclass();
$lang->block->estimate->costs    = 'Labor';
$lang->block->estimate->workhour = 'Workhour';
$lang->block->estimate->people   = 'People';
$lang->block->estimate->expect   = 'Estimate';
$lang->block->estimate->consumed = 'Cost';
$lang->block->estimate->surplus  = 'Left';
$lang->block->estimate->hour     = 'H';

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
$lang->block->tooltips['deliveryRate']      = "{$lang->SRCommon} Completion Rate by {$lang->productCommon} = Number of {$lang->SRCommon} delivered by {$lang->productCommon} / Number of effective {$lang->SRCommon} by {$lang->productCommon} * 100%";
$lang->block->tooltips['resolvedRate']      = "Bug Fix Rate by {$lang->productCommon} = Fixed Bugs by {$lang->productCommon} / Valid Bugs by {$lang->productCommon}";
$lang->block->tooltips['effectiveStory']    = "Total {$lang->SRCommon}s by {$lang->productCommon}: The sum of {$lang->SRCommon}s within {$lang->productCommon}. (Excludes deleted {$lang->SRCommon}s and {$lang->productCommon}s)";
$lang->block->tooltips['deliveredStory']    = "Delivered {$lang->SRCommon}s by {$lang->productCommon}: Sum of {$lang->SRCommon}s in {$lang->productCommon} where Stage is \"Released\" or Closed Reason is \"Completed\". (Excludes deleted {$lang->SRCommon}s and {$lang->productCommon}s)";
$lang->block->tooltips['costs']             = "Total Effort (FTE) =Hours Cost / Daily Capacity Configured in Admin";
$lang->block->tooltips['sv']                = "Schedule Variance = (EV - PV) / PV * 100% ";
$lang->block->tooltips['ev']                = 'If Task Status is "Completed", sum Estimated Effort. <br/>If Task Status is "Closed" and Closed Reason is "Completed", sum Estimated Effort. <br/>If Task Status is "Doing" or "Paused", sum (Estimated Effort * Task Progress). <br/>';
$lang->block->tooltips['pv']                = "If Task Deadline ≤ End Date of this week, sum Estimated Effort. <br/>If Task Estimated Start Date ≤ End Date of this week AND Estimated Deadline > End Date of this week, sum Estimated Effort = (Estimated Effort / Task Duration in Days) x Days from Estimated Start to End Date of this week. <br/>";
$lang->block->tooltips['cv']                = 'Cost Variance = (EV - AC) / AC * 100%';
$lang->block->tooltips['ac']                = "Sum of all logged hours before the end of this week in Waterfall {$lang->projectCommon}, excluding deleted {$lang->projectCommon}.";
$lang->block->tooltips['executionProgress'] = "<strong>{$lang->execution->common} Progress</strong> = Sum of Task Cost Hours by {$lang->execution->common} / (Sum of Task Cost Hours by {$lang->execution->common} + Sum of Task Left Hours by {$lang->execution->common}) <br/>
<strong>Sum of Task Cost Hours by {$lang->execution->common}</strong>: Sum of Cost Hours on tasks within the {$lang->execution->common}, excluding deleted tasks, parent tasks, deleted {$lang->execution->common}s, and deleted {$lang->projectCommon}s. <br/>
<strong>Sum of Task Left Hours by {$lang->execution->common}</strong>: Sum of left hours of tasks within the {$lang->execution->common}, excluding deleted tasks, parent tasks, deleted {$lang->execution->common}s, and deleted {$lang->projectCommon}s.";
$lang->block->tooltips['metricTime']        = 'Statistics will be updated hourly. The latest update time is %s.';
