<?php
$lang->execution->common  = 'Kanban';
$lang->executionCommon    = 'Kanban';
$lang->task->common       = 'Task';
$lang->story->common      = 'Story';

/* Main Navigation. */
$lang->mainNav            = new stdclass();
$lang->mainNav->my        = "{$lang->navIcons['my']} {$lang->my->shortCommon}|my|index|";
$lang->mainNav->project   = "{$lang->navIcons['project']} {$lang->projectCommon}|$projectModule|$projectMethod|";
$lang->mainNav->execution = "{$lang->navIcons['execution']} Task|$executionModule|$executionMethod|";
$lang->mainNav->kanban    = "{$lang->navIcons['kanban']} {$lang->kanban->common}|kanban|space|";
$lang->mainNav->doc       = "{$lang->navIcons['doc']} {$lang->doc->common}|doc|index|";
$lang->mainNav->system    = "{$lang->navIcons['system']} {$lang->system->common}|my|team|";
$lang->mainNav->admin     = "{$lang->navIcons['admin']} {$lang->admin->common}|admin|index|";

if($config->edition != 'open')
{
    $lang->navIcons['feedback'] = "<i class='icon icon-feedback'></i>";
    $lang->navIcons['oa']       = "<i class='icon icon-oa'></i>";
    $lang->navIcons['workflow'] = "<i class='icon icon-flow'></i>";

    $lang->mainNav->feedback = $lang->navIcons['feedback'] . 'Feedback|feedback|browse|browseType=unclosed';
    $lang->mainNav->oa       = $lang->navIcons['oa'] . 'OA|attend|personal|';
    $lang->mainNav->workflow = $lang->navIcons['workflow'] . 'Workflow|workflow|browseFlow|';

    if($config->visions == ',lite,') unset($lang->mainNav->feedback);
}

/* Menu order. */
$lang->mainNav->menuOrder     = array();
$lang->mainNav->menuOrder[5]  = 'my';
$lang->mainNav->menuOrder[10] = 'project';
$lang->mainNav->menuOrder[15] = 'kanban';
$lang->mainNav->menuOrder[35] = 'doc';
$lang->mainNav->menuOrder[45] = 'system';
$lang->mainNav->menuOrder[65] = 'admin';

if($config->edition != 'open')
{
    $lang->mainNav->menuOrder[21] = 'oa';
    $lang->mainNav->menuOrder[25] = 'feedback';
    $lang->mainNav->menuOrder[60] = 'workflow';
    $lang->dividerMenu = ',oa,admin,';

    if($config->visions == ',lite,') unset($lang->mainNav->menuOrder[25]);
}
else
{
    $lang->dividerMenu = ',doc,admin,';
}

$lang->task->common = 'Task';
$lang->dashboard = isset($lang->dashboard->common) ? $lang->dashboard->common : $lang->dashboard;

/* Ticket. */
if($config->edition != 'open') $lang->feedback->menu->ticket = array('link' => 'Ticket|ticket|browse|browseType=unclosed');

/* My menu. */
$lang->my->menu           = new stdclass();
$lang->my->menu->index    = array('link' => "$lang->dashboard|my|index");
$lang->my->menu->calendar = array('link' => "$lang->calendar|my|calendar|", 'subModule' => 'todo', 'alias' => 'todo');
$lang->my->menu->task     = array('link' => "{$lang->task->common}|my|contribute|mode=task&type=assignedTo", 'subModule' => 'task');
$lang->my->menu->contacts = array('link' => "{$lang->contact->common}|my|managecontacts|");

global $config;
if($config->edition != 'open') $lang->my->menu->effort = array('link' => 'Effort|effort|calendar|', 'exclude' => 'my-todo');

/* My menu order. */
$lang->my->menuOrder     = array();
$lang->my->menuOrder[5]  = 'index';
$lang->my->menuOrder[10] = 'calendar';
if($config->edition != 'open') $lang->my->menuOrder[11] = 'effort';
$lang->my->menuOrder[20] = 'task';
$lang->my->menuOrder[25] = 'contacts';

$lang->my->dividerMenu = ',calendar,';

$lang->project->target = 'Story';

/* Scrum menu. */
$lang->kanbanProject->menu            = new stdclass();
$lang->kanbanProject->menu->index     = array('link' => "{$lang->dashboard}|project|index|project=%s");
$lang->kanbanProject->menu->execution = array('link' => "$lang->executionKanban|project|execution|status=all&projectID=%s", 'subModule' => 'execution,task');
$lang->kanbanProject->menu->story     = array('link' => "{$lang->project->target}|projectstory|story|projectID=%s", 'subModule' => 'projectstory,tree,story', 'alias' => 'story,track');
$lang->kanbanProject->menu->doc       = array('link' => "{$lang->doc->common}|doc|tableContents|type=project&objectID=%s", 'subModule' => 'doc');
$lang->kanbanProject->menu->dynamic   = array('link' => "$lang->dynamic|project|dynamic|project=%s");
$lang->kanbanProject->menu->settings  = array('link' => "$lang->settings|project|view|project=%s", 'subModule' => 'stakeholder', 'alias' => 'edit,manageproducts,group,managemembers,manageview,managepriv,whitelist,addwhitelist,team');

$lang->kanbanProject->dividerMenu = ',execution,settings,';

/* Scrum menu order. */
$lang->kanbanProject->menuOrder     = array();
$lang->kanbanProject->menuOrder[5]  = 'index';
$lang->kanbanProject->menuOrder[10] = 'execution';
$lang->kanbanProject->menuOrder[15] = 'story';
$lang->kanbanProject->menuOrder[20] = 'doc';
$lang->kanbanProject->menuOrder[25] = 'dynamic';
$lang->kanbanProject->menuOrder[30] = 'settings';

$lang->execution->menu            = new stdclass();
$lang->execution->menu->kanban    = array('link' => "Kanban view|execution|kanban|executionID=%s");
$lang->execution->menu->task      = array('link' => "List view|execution|task|executionID=%s");
if($config->edition != 'open') $lang->execution->menu->calendar = array('link' => "Calendar view|execution|calendar|executionID=%s");
if($config->edition != 'open') $lang->execution->menu->gantt    = array('link' => "Gantt|execution|gantt|executionID=%s");
$lang->execution->menu->tree      = array('link' => "Tree|execution|tree|executionID=%s");
$lang->execution->menu->grouptask = array('link' => "Group view|execution|grouptask|executionID=%s");

$lang->kanbanProject->menu->settings['subMenu']              = new stdclass();
$lang->kanbanProject->menu->settings['subMenu']->view        = array('link' => "$lang->overview|project|view|project=%s", 'alias' => 'edit');
$lang->kanbanProject->menu->settings['subMenu']->members     = array('link' => "{$lang->team->common}|project|team|project=%s", 'alias' => 'managemembers,team');
$lang->kanbanProject->menu->settings['subMenu']->whitelist   = array('link' => "{$lang->whitelist}|project|whitelist|project=%s", 'subModule' => 'personnel');

$lang->URCommon = 'Story';
$lang->SRCommon = 'Story';

/* Doc menu. */
$lang->doc->menu            = new stdclass();
$lang->doc->menu->dashboard = array('link' => "{$lang->dashboard}|doc|index");
$lang->doc->menu->my        = array('link' => "{$lang->doc->mySpace}|doc|mySpace|type=mine", 'alias' => 'myspace');
$lang->doc->menu->project   = array('link' => "{$lang->doc->projectSpace}|doc|projectSpace", 'alias' => 'showfiles,project');
$lang->doc->menu->custom    = array('link' => "{$lang->doc->teamSpace}|doc|teamSpace|", 'alias' => 'teamspace');

$lang->doc->dividerMenu = ',project,';

/* Doc menu order. */
$lang->doc->menuOrder[5]  = 'dashboard';
$lang->doc->menuOrder[10] = 'my';
$lang->doc->menuOrder[15] = 'project';
$lang->doc->menuOrder[20] = 'custom';

/* Admin menu. */
$lang->admin->menu = new stdclass();

/* adjust items of search. */
$lang->searchObjects['all']       = 'All';
$lang->searchObjects['todo']      = 'Todo';
$lang->searchObjects['story']     = 'Story';
$lang->searchObjects['task']      = 'Task';
$lang->searchObjects['doc']       = 'Doc';
$lang->searchObjects['project']   = $lang->projectCommon;
$lang->searchObjects['execution'] = 'Kanban';
$lang->searchObjects['user']      = 'User';

if($config->edition != 'open') $lang->searchObjects['feedback'] = 'Feedback';
if($config->visions == ',lite,') unset($lang->searchObjects['feedback']);

$lang->navGroup->task      = $lang->projectCommon;
$lang->navGroup->execution = $lang->projectCommon;

unset($lang->searchObjects['bug']);
unset($lang->searchObjects['testcase']);
unset($lang->searchObjects['product']);
unset($lang->searchObjects['build']);
unset($lang->searchObjects['release']);
unset($lang->searchObjects['productplan']);
unset($lang->searchObjects['testtask']);
unset($lang->searchObjects['caselib']);
unset($lang->searchObjects['testreport']);
unset($lang->searchObjects['program']);

/* biz search. */
unset($lang->searchObjects['service']);
unset($lang->searchObjects['deploy']);
unset($lang->searchObjects['deploystep']);

/* max search. */
unset($lang->searchObjects['trainplan']);
unset($lang->searchObjects['risk']);
unset($lang->searchObjects['issue']);
unset($lang->searchObjects['opportunity']);

/* adjust items of global create. */
unset($lang->createIcons['effort']);
unset($lang->createIcons['bug']);
unset($lang->createIcons['testcase']);
unset($lang->createIcons['product']);
unset($lang->createIcons['program']);

$lang->createObjects['story']     = 'Story';
$lang->createObjects['task']      = 'Task';
$lang->createObjects['execution'] = 'TaskKanban';
$lang->createObjects['kanban']    = 'Kanban';

$lang->createIcons['execution'] = 'kanban';

/* Xuan */
global $config;
if(isset($config->xuanxuan) && strpos($config->visions, ',rnd,') === false)
{
    $lang->xuanxuan = new stdclass();

    $lang->navGroup->im      = 'admin';
    $lang->navGroup->setting = 'admin';
    $lang->navGroup->client  = 'admin';

    $lang->confirmDelete = 'Do you want to delete it?';
}
