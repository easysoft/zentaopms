<?php
global $config;
list($productModule, $productMethod)     = explode('-', $config->productLink);
list($projectModule, $projectMethod)     = explode('-', $config->projectLink);
list($executionModule, $executionMethod) = explode('-', $config->executionLink);
list($docModule, $docMethod)             = explode('-', $config->docLink);

$lang->execution->common  = '看板';
$lang->executionCommon    = '看板';
$lang->task->common       = '任务';
$lang->story->common      = '目标';

/* Main Navigation. */
$lang->mainNav            = new stdclass();
$lang->mainNav->my        = "{$lang->navIcons['my']} {$lang->my->shortCommon}|my|index|";
$lang->mainNav->project   = "{$lang->navIcons['project']} {$lang->projectCommon}|$projectModule|$projectMethod|";
$lang->mainNav->execution = "{$lang->navIcons['execution']} 任务|$executionModule|$executionMethod|";
$lang->mainNav->kanban    = "{$lang->navIcons['kanban']} {$lang->kanban->common}|kanban|space|";
$lang->mainNav->doc       = "{$lang->navIcons['doc']} {$lang->doc->common}|$docModule|$docMethod|";
$lang->mainNav->system    = "{$lang->navIcons['system']} {$lang->system->common}|my|team|";
$lang->mainNav->admin     = "{$lang->navIcons['admin']} {$lang->admin->common}|admin|index|";

if($config->edition != 'open')
{
    $lang->navIcons['feedback'] = "<i class='icon icon-feedback'></i>";
    if(helper::hasFeature('OA')) $lang->navIcons['oa'] = "<i class='icon icon-oa'></i>";

    $lang->mainNav->feedback = $lang->navIcons['feedback'] . ' 反馈|feedback|browse|browseType=unclosed';
    if(helper::hasFeature('OA')) $lang->mainNav->oa = $lang->navIcons['oa'] . ' 办公|attend|personal|';

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
    $lang->dividerMenu = ',oa,admin,';

    if($config->visions == ',lite,') unset($lang->mainNav->menuOrder[25]);
}
else
{
    $lang->dividerMenu = ',doc,admin,';
}

$lang->task->common = '任务';
$lang->dashboard = isset($lang->dashboard->common) ? $lang->dashboard->common : $lang->dashboard;

/* Feedback. */
if($config->edition != 'open')
{
    $lang->feedback->menu->ticket   = array('link' => '工单|ticket|browse|browseType=unclosed');
    $lang->feedback->menu->products = array('link' => '设置|feedback|products', 'alias' => 'manageproduct');

    $lang->feedback->menuOrder[5]  = 'browse';
    $lang->feedback->menuOrder[10] = 'ticket';
    $lang->feedback->menuOrder[15] = 'faq';
    $lang->feedback->menuOrder[20] = 'products';
}

/* My menu. */
$lang->my->menu           = new stdclass();
$lang->my->menu->index    = array('link' => "$lang->dashboard|my|index");
$lang->my->menu->calendar = array('link' => "$lang->calendar|my|calendar|", 'subModule' => 'todo', 'alias' => 'todo');
$lang->my->menu->task     = array('link' => "{$lang->task->common}|my|contribute|mode=task&type=assignedTo", 'subModule' => 'task');
$lang->my->menu->contacts = array('link' => "{$lang->contact->common}|my|managecontacts|");

if($config->edition != 'open') $lang->my->menu->effort = array('link' => '日志|effort|calendar|', 'exclude' => 'my-todo');

/* My menu order. */
$lang->my->menuOrder     = array();
$lang->my->menuOrder[5]  = 'index';
$lang->my->menuOrder[10] = 'calendar';
if($config->edition != 'open') $lang->my->menuOrder[11] = 'effort';
$lang->my->menuOrder[20] = 'task';
$lang->my->menuOrder[25] = 'contacts';

$lang->my->dividerMenu = ',calendar,';

$lang->project->target = '目标';

/* Scrum menu. */
$lang->kanbanProject->menu            = new stdclass();
$lang->kanbanProject->menu->index     = array('link' => "{$lang->dashboard}|project|index|project=%s");
$lang->kanbanProject->menu->execution = array('link' => "$lang->executionKanban|project|execution|status=all&projectID=%s", 'subModule' => 'execution,task,tree');
$lang->kanbanProject->menu->story     = array('link' => "{$lang->project->target}|projectstory|story|projectID=%s", 'subModule' => 'projectstory,tree,story', 'alias' => 'story,track');
$lang->kanbanProject->menu->doc       = array('link' => "{$lang->doc->common}|doc|projectSpace|objectID=%s", 'subModule' => 'doc');
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
$lang->execution->menu->kanban    = array('link' => "看板视图|execution|kanban|executionID=%s");
$lang->execution->menu->task      = array('link' => "列表视图|execution|task|executionID=%s");
if($config->edition != 'open') $lang->execution->menu->calendar = array('link' => "日历视图|execution|calendar|executionID=%s");
if($config->edition != 'open') $lang->execution->menu->gantt    = array('link' => "甘特图|execution|gantt|executionID=%s");
$lang->execution->menu->tree      = array('link' => "树状图|execution|tree|executionID=%s");
$lang->execution->menu->grouptask = array('link' => "分组视图|execution|grouptask|executionID=%s");

$lang->kanbanProject->menu->settings['subMenu']              = new stdclass();
$lang->kanbanProject->menu->settings['subMenu']->view        = array('link' => "$lang->overview|project|view|project=%s", 'alias' => 'edit');
$lang->kanbanProject->menu->settings['subMenu']->members     = array('link' => "{$lang->team->common}|project|team|project=%s", 'alias' => 'managemembers,team');
$lang->kanbanProject->menu->settings['subMenu']->whitelist   = array('link' => "{$lang->whitelist}|project|whitelist|project=%s", 'subModule' => 'personnel');

$lang->URCommon = '目标';
$lang->SRCommon = '目标';

/* Doc menu. */
$lang->doc->menu            = new stdclass();
$lang->doc->menu->dashboard = array('link' => "{$lang->dashboard}|doc|index");
$lang->doc->menu->quick     = array('link' => "{$lang->doc->quick}|doc|quick");
$lang->doc->menu->my        = array('link' => "{$lang->doc->mySpace}|doc|mySpace|", 'alias' => 'myspace');
$lang->doc->menu->project   = array('link' => "{$lang->doc->projectSpace}|doc|projectSpace", 'alias' => 'showfiles,project');
$lang->doc->menu->custom    = array('link' => "{$lang->doc->teamSpace}|doc|teamSpace|", 'alias' => 'teamspace');

$lang->doc->dividerMenu = ',project,';

/* Doc menu order. */
$lang->doc->menuOrder     = array();
$lang->doc->menuOrder[5]  = 'dashboard';
$lang->doc->menuOrder[10] = 'quick';
$lang->doc->menuOrder[15] = 'my';
$lang->doc->menuOrder[20] = 'custom';
$lang->doc->menuOrder[25] = 'project';

if(strpos(',max,ipd,', $config->edition) !== false)
{
    $lang->doc->menu->template = array('link' => "模板广场|doc|browseTemplate|", 'alias' => 'browsetemplate');
    $lang->doc->menuOrder[30]  = 'template';
    $lang->doc->dividerMenu   .= ',template,';
}

/* Admin menu. */
$lang->admin->menu = new stdclass();

/* adjust items of search. */
$lang->searchObjects['all']       = '全部';
$lang->searchObjects['todo']      = '待办';
$lang->searchObjects['story']     = '目标';
$lang->searchObjects['task']      = '任务';
$lang->searchObjects['doc']       = '文档';
$lang->searchObjects['project']   = $lang->projectCommon;
$lang->searchObjects['execution'] = '看板';
$lang->searchObjects['user']      = '用户';

if($config->edition != 'open') $lang->searchObjects['feedback'] = '反馈';
if($config->visions == ',lite,') unset($lang->searchObjects['feedback']);

$lang->navGroup->task      = 'project';
$lang->navGroup->execution = 'project';

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

if($config->edition != 'open') unset($lang->workflow->menu->flowgroup);
if($config->edition != 'open') unset($lang->workflow->menu->ruler);

$lang->createObjects['story']     = '目标';
$lang->createObjects['task']      = '任务';
$lang->createObjects['execution'] = '任务看板';
$lang->createObjects['kanban']    = '通用看板';

$lang->createIcons['execution'] = 'kanban';

/* Xuan */
if(isset($config->xuanxuan) && strpos($config->visions, ',rnd,') === false)
{
    $lang->xuanxuan = new stdclass();

    $lang->navGroup->im      = 'admin';
    $lang->navGroup->setting = 'admin';
    $lang->navGroup->client  = 'admin';

    $lang->confirmDelete = '您确定要执行删除操作吗？';
}
