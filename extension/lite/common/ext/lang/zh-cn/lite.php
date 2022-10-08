<?php
$lang->execution->common  = '看板';
$lang->executionCommon    = '看板';
$lang->task->common       = '任务';
$lang->story->common      = '目标';

/* Main Navigation. */
$lang->mainNav            = new stdclass();
$lang->mainNav->my        = "{$lang->navIcons['my']} {$lang->my->shortCommon}|my|index|";
$lang->mainNav->project   = "{$lang->navIcons['project']} {$lang->project->common}|$projectModule|$projectMethod|";
$lang->mainNav->execution = "{$lang->navIcons['execution']} 任务|$executionModule|$executionMethod|";
$lang->mainNav->kanban    = "{$lang->navIcons['kanban']} {$lang->kanban->common}|kanban|space|";
$lang->mainNav->doc       = "{$lang->navIcons['doc']} {$lang->doc->common}|doc|index|";
$lang->mainNav->system    = "{$lang->navIcons['system']} {$lang->system->common}|my|team|";
$lang->mainNav->admin     = "{$lang->navIcons['admin']} {$lang->admin->common}|admin|index|";

if($config->edition != 'open')
{
    $lang->navIcons['feedback'] = "<i class='icon icon-feedback'></i>";
    $lang->navIcons['oa']       = "<i class='icon icon-oa'></i>";
    $lang->navIcons['workflow'] = "<i class='icon icon-flow'></i>";

    $lang->mainNav->feedback = $lang->navIcons['feedback'] . '反馈|feedback|browse|browseType=unclosed';
    $lang->mainNav->oa       = $lang->navIcons['oa'] . '办公|attend|personal|';
    $lang->mainNav->workflow = $lang->navIcons['workflow'] . '工作流|workflow|browseFlow|';

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

$lang->task->common = '任务';
$lang->dashboard = isset($lang->dashboard->common) ? $lang->dashboard->common : $lang->dashboard;

/* My menu. */
$lang->my->menu           = new stdclass();
$lang->my->menu->index    = array('link' => "$lang->dashboard|my|index");
$lang->my->menu->calendar = array('link' => "$lang->calendar|my|calendar|", 'subModule' => 'todo', 'alias' => 'todo');
$lang->my->menu->task     = array('link' => "{$lang->task->common}|my|contribute|mode=task&type=assignedTo", 'subModule' => 'task');
$lang->my->menu->contacts = array('link' => "$lang->contact|my|managecontacts|");

global $config;
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
$lang->execution->menu->kanban    = array('link' => "看板视图|execution|kanban|executionID=%s");
$lang->execution->menu->task      = array('link' => "列表视图|execution|task|executionID=%s");
if($config->edition != 'open') $lang->execution->menu->calendar = array('link' => "日历视图|execution|calendar|executionID=%s");
if($config->edition != 'open') $lang->execution->menu->gantt    = array('link' => "甘特图|execution|gantt|executionID=%s");
$lang->execution->menu->tree      = array('link' => "树状图|execution|tree|executionID=%s");
$lang->execution->menu->grouptask = array('link' => "分组视图|execution|grouptask|executionID=%s");

$lang->kanbanProject->menu->doc['subMenu'] = new stdclass();

$lang->kanbanProject->menu->settings['subMenu']              = new stdclass();
$lang->kanbanProject->menu->settings['subMenu']->view        = array('link' => "$lang->overview|project|view|project=%s", 'alias' => 'edit');
$lang->kanbanProject->menu->settings['subMenu']->members     = array('link' => "{$lang->team->common}|project|team|project=%s", 'alias' => 'managemembers,team');
$lang->kanbanProject->menu->settings['subMenu']->whitelist   = array('link' => "{$lang->whitelist}|project|whitelist|project=%s", 'subModule' => 'personnel');

unset($lang->doc->menu->product);
unset($lang->doc->menu->api);
unset($lang->doc->menu->execution);

$lang->URCommon = '目标';
$lang->SRCommon = '目标';

/* Doc menu. */
$lang->doc->menu            = new stdclass();
$lang->doc->menu->dashboard = array('link' => "{$lang->dashboard}|doc|index");
$lang->doc->menu->recent    = array('link' => "{$lang->doc->recent}|doc|browse|browseTyp=byediteddate", 'alias' => 'recent');
$lang->doc->menu->my        = array('link' => "{$lang->doc->my}|doc|browse|browseTyp=openedbyme", 'alias' => 'my');
$lang->doc->menu->collect   = array('link' => "{$lang->doc->favorite}|doc|browse|browseTyp=collectedbyme", 'alias' => 'collect');
$lang->doc->menu->project   = array('link' => "{$lang->doc->project}|doc|tableContents|type=project", 'alias' => 'showfiles,project');
$lang->doc->menu->custom    = array('link' => "{$lang->doc->custom}|doc|tableContents|type=custom", 'alias' => 'custom');

$lang->doc->dividerMenu = ',project,';

/* Doc menu order. */
$lang->doc->menuOrder     = array();
$lang->doc->menuOrder[5]  = 'dashboard';
$lang->doc->menuOrder[10] = 'recent';
$lang->doc->menuOrder[15] = 'my';
$lang->doc->menuOrder[20] = 'collect';
$lang->doc->menuOrder[25] = 'project';
$lang->doc->menuOrder[30] = 'custom';

$lang->doc->menu->project['subMenu'] = new stdclass();
$lang->doc->menu->custom['subMenu']  = new stdclass();

if(isset($lang->admin->menu->xuanxuan)) $xuanxuanMenu = $lang->admin->menu->xuanxuan;
/* Admin menu. */
$lang->admin->menu            = new stdclass();
$lang->admin->menu->index     = array('link' => "$lang->indexPage|admin|index", 'alias' => 'register,certifytemail,certifyztmobile,ztcompany');
if(isset($xuanxuanMenu)) $lang->admin->menu->xuanxuan = $xuanxuanMenu;
$lang->admin->menu->company   = array('link' => "{$lang->personnel->common}|company|browse|", 'subModule' => ',user,dept,group,');
$lang->admin->menu->custom    = array('link' => "{$lang->custom->common}|custom|index", 'exclude' => 'custom-browsestoryconcept,custom-timezone,custom-estimate');
$lang->admin->menu->extension = array('link' => "{$lang->extension->common}|extension|browse", 'subModule' => 'extension');
$lang->admin->menu->dev       = array('link' => "$lang->redev|dev|api", 'alias' => 'db', 'subModule' => 'dev,editor,entry');
$lang->admin->menu->message   = array('link' => "{$lang->message->common}|message|index", 'subModule' => 'message,mail,webhook,sms');
$lang->admin->menu->system    = array('link' => "{$lang->admin->system}|backup|index", 'subModule' => 'cron,backup,action,admin,search,ldap', 'exclude' => 'admin-index,admin-xuanxuan,admin-register,admin-ztcompany');

if($config->vision != 'lite') $lang->admin->menu->xuanxuan['link']  = '聊天|admin|xuanxuan';

/* Admin menu order. */
$lang->admin->menuOrder = array();
$lang->admin->menuOrder[5]  = 'index';
$lang->admin->menuOrder[6]  = 'xuanxuan';
$lang->admin->menuOrder[10] = 'company';
$lang->admin->menuOrder[15] = 'custom';
$lang->admin->menuOrder[20] = 'message';
$lang->admin->menuOrder[25] = 'extension';
$lang->admin->menuOrder[30] = 'dev';
$lang->admin->menuOrder[35] = 'system';

$lang->admin->menu->message['subMenu']          = new stdclass();
$lang->admin->menu->message['subMenu']->message = array();
$lang->admin->menu->message['subMenu']->mail    = array('link' => "{$lang->mail->common}|mail|index", 'subModule' => 'mail');
$lang->admin->menu->message['subMenu']->webhook = array('link' => "Webhook|webhook|browse", 'subModule' => 'webhook');

if($config->edition != 'open') $lang->admin->menu->message['subMenu']->sms = array('link' => "短信|sms|index");

$lang->admin->menu->message['subMenu']->browser = array('link' => "$lang->browser|message|browser");
$lang->admin->menu->message['subMenu']->setting = array('link' => "$lang->settings|message|setting");

$lang->admin->menu->message['menuOrder'][5]  = 'mail';
$lang->admin->menu->message['menuOrder'][10] = 'webhook';
$lang->admin->menu->message['menuOrder'][11] = 'sms';
$lang->admin->menu->message['menuOrder'][15] = 'browser';
$lang->admin->menu->message['menuOrder'][20] = 'setting';

$lang->admin->menu->company['subMenu']              = new stdclass();
$lang->admin->menu->company['subMenu']->browseUser  = array('link' => "{$lang->user->common}|company|browse", 'subModule' => 'user');
$lang->admin->menu->company['subMenu']->dept        = array('link' => "{$lang->dept->common}|dept|browse", 'subModule' => 'dept');
$lang->admin->menu->company['subMenu']->browseGroup = array('link' => "{$lang->priv}|group|browse", 'subModule' => 'group');

$lang->admin->menu->dev['subMenu']         = new stdclass();
$lang->admin->menu->dev['subMenu']->api    = array('link' => "API|dev|api");
$lang->admin->menu->dev['subMenu']->db     = array('link' => "$lang->db|dev|db");
$lang->admin->menu->dev['subMenu']->editor = array('link' => "$lang->editor|dev|editor");
$lang->admin->menu->dev['subMenu']->entry  = array('link' => "{$lang->admin->entry}|entry|browse", 'subModule' => 'entry');

$lang->admin->menu->dev['menuOrder'][5]  = 'api';
$lang->admin->menu->dev['menuOrder'][10] = 'db';
$lang->admin->menu->dev['menuOrder'][15] = 'editor';
$lang->admin->menu->dev['menuOrder'][20] = 'entry';

$lang->admin->menu->system['subMenu']              = new stdclass();
$lang->admin->menu->system['subMenu']->backup      = array('link' => "{$lang->backup->common}|backup|index");
$lang->admin->menu->system['subMenu']->trash       = array('link' => "{$lang->action->trash}|action|trash");
$lang->admin->menu->system['subMenu']->safe        = array('link' => "$lang->security|admin|safe", 'alias' => 'checkweak');
$lang->admin->menu->system['subMenu']->cron        = array('link' => "{$lang->admin->cron}|cron|index", 'subModule' => 'cron');
$lang->admin->menu->system['subMenu']->timezone    = array('link' => "$lang->timezone|custom|timezone");
$lang->admin->menu->system['subMenu']->buildIndex  = array('link' => "{$lang->admin->buildIndex}|search|buildindex|");

if($config->visions == ',lite,' and $config->edition != 'open') $lang->admin->menu->system['subMenu']->license = array('link' => "授权信息|admin|license'", 'alias' => 'license');
if($config->visions != ',lite,') unset($lang->admin->menu->system['subMenu']->buildIndex);

if($config->edition != 'open')
{
    $lang->admin->menu->system['subMenu']->ldap        = array('link' => 'LDAP|ldap|set', 'subModule' => 'ldap');
    $lang->admin->menu->system['subMenu']->libreoffice = array('link' => 'Office|custom|libreoffice');
}

$lang->admin->dividerMenu = ',company,message,system,';

/* adjust items of search. */
$lang->searchObjects['all']       = '全部';
$lang->searchObjects['todo']      = '待办';
$lang->searchObjects['story']     = '目标';
$lang->searchObjects['task']      = '任务';
$lang->searchObjects['doc']       = '文档';
$lang->searchObjects['project']   = '项目';
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

$lang->createObjects['story']     = '目标';
$lang->createObjects['task']      = '任务';
$lang->createObjects['execution'] = '任务看板';
$lang->createObjects['kanban']    = '通用看板';

$lang->createIcons['execution'] = 'kanban';

/* Xuan */
global $config;
if(isset($config->xuanxuan) && strpos($config->visions, ',rnd,') === false)
{
    $lang->xuanxuan = new stdclass();
    $lang->admin->menu->xuanxuan = array('link' => '聊天|admin|xuanxuan', 'subModule' => 'client,setting');
    $lang->admin->menuOrder[6]   = 'xuanxuan';

    $lang->admin->menu->xuanxuan['subMenu'] = new stdclass();
    $lang->admin->menu->xuanxuan['subMenu']->index   = array('link' => '首页|admin|xuanxuan');
    $lang->admin->menu->xuanxuan['subMenu']->setting = array('link' => '参数|setting|xuanxuan');
    $lang->admin->menu->xuanxuan['subMenu']->update  = array('link' => '更新|client|browse', 'subModule' => 'client');

    $lang->admin->menu->xuanxuan['menuOrder'][0]  = 'index';
    $lang->admin->menu->xuanxuan['menuOrder'][5]  = 'setting';
    $lang->admin->menu->xuanxuan['menuOrder'][10] = 'update';

    if($config->edition != 'open')
    {
        $lang->admin->menu->xuanxuan['subModule']           = 'client,setting,conference';
        $lang->admin->menu->xuanxuan['subMenu']->conference = array('link' => '音视频|conference|admin');
        $lang->admin->menu->xuanxuan['menuOrder'][7]        = 'conference';
        $lang->navGroup->conference                         = 'admin';
    }

    $lang->navGroup->im      = 'admin';
    $lang->navGroup->setting = 'admin';
    $lang->navGroup->client  = 'admin';

    $lang->confirmDelete = '您确定要执行删除操作吗？';
}
