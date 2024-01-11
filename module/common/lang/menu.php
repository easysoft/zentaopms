<?php
$lang->navIcons              = array();
$lang->navIcons['my']        = "<i class='icon icon-menu-my'></i>";
$lang->navIcons['program']   = "<i class='icon icon-program'></i>";
$lang->navIcons['product']   = "<i class='icon icon-product'></i>";
$lang->navIcons['project']   = "<i class='icon icon-project'></i>";
$lang->navIcons['execution'] = "<i class='icon icon-run'></i>";
$lang->navIcons['qa']        = "<i class='icon icon-test'></i>";
$lang->navIcons['devops']    = "<i class='icon icon-devops'></i>";
$lang->navIcons['kanban']    = "<i class='icon icon-kanban'></i>";
$lang->navIcons['doc']       = "<i class='icon icon-doc'></i>";
$lang->navIcons['bi']        = "<i class='icon icon-statistic'></i>";
$lang->navIcons['system']    = "<i class='icon icon-group'></i>";
$lang->navIcons['admin']     = "<i class='icon icon-cog-outline'></i>";

$lang->navIconNames              = array();
$lang->navIconNames['my']        = 'menu-my';
$lang->navIconNames['program']   = 'program';
$lang->navIconNames['product']   = 'product';
$lang->navIconNames['project']   = 'project';
$lang->navIconNames['execution'] = 'run';
$lang->navIconNames['qa']        = 'test';
$lang->navIconNames['devops']    = 'devops';
$lang->navIconNames['kanban']    = 'kanban';
$lang->navIconNames['doc']       = 'doc';
$lang->navIconNames['bi']        = 'statistic';
$lang->navIconNames['system']    = 'group';
$lang->navIconNames['admin']     = 'cog-outline';

global $config;
list($programModule, $programMethod)     = explode('-', $config->programLink);
list($productModule, $productMethod)     = explode('-', $config->productLink);
list($projectModule, $projectMethod)     = explode('-', $config->projectLink);
list($executionModule, $executionMethod) = explode('-', $config->executionLink);

if(isset($_SESSION['tutorialMode']) && $_SESSION['tutorialMode'])
{
    $programModule   = 'program';
    $programMethod   = 'browse';
    $productModule   = 'product';
    $productMethod   = 'all';
    $projectModule   = 'project';
    $projectMethod   = 'browse';
    $executionModule = 'execution';
    $executionMethod = 'task';
}

/* Main Navigation. */
$lang->mainNav            = new stdclass();
$lang->mainNav->my        = "{$lang->navIcons['my']} {$lang->my->shortCommon}|my|index|";
$lang->mainNav->program   = "{$lang->navIcons['program']} {$lang->program->common}|$programModule|$programMethod|";
$lang->mainNav->product   = "{$lang->navIcons['product']} {$lang->productCommon}|$productModule|$productMethod|";
$lang->mainNav->project   = "{$lang->navIcons['project']} {$lang->projectCommon}|$projectModule|$projectMethod|";
$lang->mainNav->execution = "{$lang->navIcons['execution']} {$lang->execution->common}|$executionModule|$executionMethod|";
$lang->mainNav->qa        = "{$lang->navIcons['qa']} {$lang->qa->common}|qa|index|";
$lang->mainNav->devops    = "{$lang->navIcons['devops']} DevOps|repo|maintain|";
$lang->mainNav->kanban    = "{$lang->navIcons['kanban']} {$lang->kanban->common}|kanban|space|";
$lang->mainNav->doc       = "{$lang->navIcons['doc']} {$lang->doc->common}|doc|index|";
$lang->mainNav->bi        = "{$lang->navIcons['bi']} {$lang->bi->common}|screen|browse|";
$lang->mainNav->system    = "{$lang->navIcons['system']} {$lang->system->common}|my|team|";
$lang->mainNav->admin     = "{$lang->navIcons['admin']} {$lang->admin->common}|admin|index|";

$lang->dividerMenu = ',kanban,oa,admin,';

$lang->mainNav->menuOrder[5]  = 'my';
$lang->mainNav->menuOrder[10] = 'program';
$lang->mainNav->menuOrder[15] = 'product';
$lang->mainNav->menuOrder[20] = 'project';
$lang->mainNav->menuOrder[25] = 'execution';
$lang->mainNav->menuOrder[30] = 'qa';
$lang->mainNav->menuOrder[35] = 'devops';
$lang->mainNav->menuOrder[40] = 'kanban';
$lang->mainNav->menuOrder[45] = 'doc';
$lang->mainNav->menuOrder[50] = 'bi';
$lang->mainNav->menuOrder[55] = 'system';
$lang->mainNav->menuOrder[60] = 'admin';

if($config->systemMode == 'light') unset($lang->mainNav->program, $lang->mainNav->menuOrder[10]);

/* My menu. */
$lang->my->menu             = new stdclass();
$lang->my->menu->index      = array('link' => "$lang->dashboard|my|index");
$lang->my->menu->calendar   = array('link' => "$lang->calendar|my|calendar|", 'subModule' => 'todo', 'alias' => 'todo');
$lang->my->menu->work       = array('link' => "{$lang->my->work}|my|work|mode=task", 'subModule' => 'task');
$lang->my->menu->audit      = array('link' => "{$lang->review->common}|my|audit|type=all&param=&orderBy=time_desc", 'subModule' => 'review');
$lang->my->menu->project    = array('link' => "{$lang->project->common}|my|project|");
$lang->my->menu->execution  = array('link' => "{$lang->execution->common}|my|execution|type=undone");
$lang->my->menu->contribute = array('link' => "$lang->contribute|my|contribute|mode=task");
$lang->my->menu->dynamic    = array('link' => "$lang->dynamic|my|dynamic|");
$lang->my->menu->score      = array('link' => "{$lang->score->shortCommon}|my|score|", 'subModule' => 'score');
$lang->my->menu->contacts   = array('link' => "{$lang->contact->common}|my|managecontacts|");

/* My menu order. */
$lang->my->menuOrder[5]  = 'index';
$lang->my->menuOrder[10] = 'calendar';
$lang->my->menuOrder[15] = 'work';
$lang->my->menuOrder[20] = 'audit';
$lang->my->menuOrder[25] = 'project';
$lang->my->menuOrder[30] = 'execution';
$lang->my->menuOrder[35] = 'contribute';
$lang->my->menuOrder[40] = 'dynamic';
$lang->my->menuOrder[45] = 'score';
$lang->my->menuOrder[50] = 'contacts';

if(!$config->systemScore) unset($lang->my->menu->score, $lang->my->menuOrder[45]);

$lang->my->menu->work['subMenu']              = new stdclass();
$lang->my->menu->work['subMenu']->task        = array('link' => "{$lang->task->common}|my|work|mode=task", 'subModule' => 'task', 'alias' => 'task');
$lang->my->menu->work['subMenu']->story       = array('link' => "$lang->SRCommon|my|work|mode=story", 'alias' => 'story');
$lang->my->menu->work['subMenu']->requirement = array('link' => "$lang->URCommon|my|work|mode=requirement", 'alias' => 'requirement');
$lang->my->menu->work['subMenu']->bug         = array('link' => "{$lang->bug->common}|my|work|mode=bug", 'subModule' => 'bug', 'alias' => 'bug');
$lang->my->menu->work['subMenu']->testcase    = array('link' => "{$lang->testcase->common}|my|work|mode=testcase", 'subModule' => 'testtask,testcase', 'alias' => 'testcase');
$lang->my->menu->work['subMenu']->testtask    = array('link' => "{$lang->testtask->common}|my|work|mode=testtask", 'alias' => 'testtask');

$lang->my->menu->work['menuOrder'][5]  = 'task';
$lang->my->menu->work['menuOrder'][10] = 'story';
$lang->my->menu->work['menuOrder'][15] = 'requirement';
$lang->my->menu->work['menuOrder'][20] = 'bug';
$lang->my->menu->work['menuOrder'][25] = 'testcase';
$lang->my->menu->work['menuOrder'][30] = 'testtask';
$lang->my->menu->work['menuOrder'][35] = 'audit';

if(!$config->URAndSR) unset($lang->my->menu->work['subMenu']->requirement, $lang->my->menu->work['menuOrder'][10]);

$lang->my->menu->contribute['subMenu']              = new stdclass();
$lang->my->menu->contribute['subMenu']->task        = array('link' => "{$lang->task->common}|my|contribute|mode=task", 'alias' => 'task');
$lang->my->menu->contribute['subMenu']->story       = array('link' => "$lang->SRCommon|my|contribute|mode=story", 'alias' => 'story');
$lang->my->menu->contribute['subMenu']->requirement = array('link' => "$lang->URCommon|my|contribute|mode=requirement", 'alias' => 'requirement');
$lang->my->menu->contribute['subMenu']->bug         = array('link' => "{$lang->bug->common}|my|contribute|mode=bug", 'alias' => 'bug');
$lang->my->menu->contribute['subMenu']->testcase    = array('link' => "{$lang->testcase->shortCommon}|my|contribute|mode=testcase", 'alias' => 'testcase');
$lang->my->menu->contribute['subMenu']->testtask    = array('link' => "{$lang->testtask->common}|my|contribute|mode=testtask", 'alias' => 'testtask');
$lang->my->menu->contribute['subMenu']->audit       = array('link' => "{$lang->review->common}|my|contribute|mode=audit&type=reviewedbyme", 'subModule' => 'review', 'alias' => 'audit');
$lang->my->menu->contribute['subMenu']->doc         = array('link' => "{$lang->doc->common}|my|contribute|mode=doc", 'alias' => 'doc');

$lang->my->menu->contribute['menuOrder'][5]  = 'task';
$lang->my->menu->contribute['menuOrder'][10] = 'story';
$lang->my->menu->contribute['menuOrder'][15] = 'requirement';
$lang->my->menu->contribute['menuOrder'][20] = 'bug';
$lang->my->menu->contribute['menuOrder'][25] = 'testcase';
$lang->my->menu->contribute['menuOrder'][30] = 'testtask';
$lang->my->menu->contribute['menuOrder'][35] = 'audit';
$lang->my->menu->contribute['menuOrder'][40] = 'doc';

if(!$config->URAndSR) unset($lang->my->menu->contribute['subMenu']->requirement, $lang->my->menu->contribute['menuOrder'][10]);

$lang->my->dividerMenu = ',work,dynamic,';

/* Program menu. */
$lang->program->homeMenu = new stdclass();
$lang->program->homeMenu->browse      = array('link' => "{$lang->program->projectView}|program|browse|", 'alias' => 'create,edit', 'subModule' => 'project');
$lang->program->homeMenu->productView = array('link' => "{$lang->program->productView}|program|productview|", 'alias' => 'create,edit', 'subModule' => 'project');
$lang->program->homeMenu->kanban      = array('link' => "{$lang->program->kanban}|program|kanban|");

$lang->program->menu = new stdclass();
$lang->program->menu->product     = array('link' => "{$lang->productCommon}|program|product|programID=%s", 'alias' => 'view');
$lang->program->menu->project     = array('link' => "{$lang->projectCommon}|program|project|programID=%s");
$lang->program->menu->personnel   = array('link' => "{$lang->personnel->common}|personnel|invest|programID=%s");
$lang->program->menu->stakeholder = array('link' => "{$lang->stakeholder->common}|program|stakeholder|programID=%s", 'alias' => 'createstakeholder');

/* Program menu order. */
$lang->program->menuOrder[5]  = 'product';
$lang->program->menuOrder[10] = 'project';
$lang->program->menuOrder[15] = 'personnel';
$lang->program->menuOrder[20] = 'stakeholder';

$lang->program->menu->personnel['subMenu'] = new stdClass();
$lang->program->menu->personnel['subMenu']->invest     = array('link' => "{$lang->personnel->invest}|personnel|invest|programID=%s");
$lang->program->menu->personnel['subMenu']->accessible = array('link' => "{$lang->personnel->accessible}|personnel|accessible|programID=%s");
$lang->program->menu->personnel['subMenu']->whitelist  = array('link' => "{$lang->whitelist}|personnel|whitelist|objectID=%s", 'alias' => 'addwhitelist');

/* Product menu. */
$lang->product->homeMenu = new stdclass();
$lang->product->homeMenu->home   = array('link' => "{$lang->dashboard}|product|index|");
$lang->product->homeMenu->list   = array('link' => $lang->product->list . '|product|all|', 'alias' => 'create,batchedit,manageline');
$lang->product->homeMenu->kanban = array('link' => "{$lang->product->kanban}|product|kanban|");

$lang->product->menu              = new stdclass();
$lang->product->menu->dashboard   = array('link' => "{$lang->dashboard}|product|dashboard|productID=%s");
$lang->product->menu->story       = array('link' => "$lang->SRCommon|product|browse|productID=%s", 'alias' => 'batchedit', 'subModule' => 'story', 'exclude' => (isset($_GET['storyType']) ? ($_GET['storyType'] == 'requirement' ? 'browse,story-report,story-create,story-batchcreate' : '') : '') . ',batchtotask');
$lang->product->menu->plan        = array('link' => "{$lang->productplan->shortCommon}|productplan|browse|productID=%s", 'subModule' => 'productplan,bug');
$lang->product->menu->project     = array('link' => "{$lang->projectCommon}|product|project|status=all&productID=%s");
$lang->product->menu->release     = array('link' => "{$lang->release->common}|release|browse|productID=%s", 'subModule' => 'release');
$lang->product->menu->roadmap     = array('link' => "{$lang->roadmap}|product|roadmap|productID=%s");
$lang->product->menu->requirement = array('link' => "{$lang->URCommon}|product|browse|productID=%s&branch=&browseType=unclosed&param=0&storyType=requirement", 'alias' => 'batchedit', 'subModule' => 'story');
$lang->product->menu->track       = array('link' => "{$lang->track}|product|track|productID=%s");
$lang->product->menu->doc         = array('link' => "{$lang->doc->common}|doc|productSpace|objectID=%s", 'subModule' => 'doc,api');
$lang->product->menu->dynamic     = array('link' => "{$lang->dynamic}|product|dynamic|productID=%s");
$lang->product->menu->settings    = array('link' => "{$lang->settings}|product|view|productID=%s", 'subModule' => 'tree,branch', 'alias' => 'edit,whitelist,addwhitelist');

/* Product menu order. */
$lang->product->menuOrder[5]  = 'dashboard';
$lang->product->menuOrder[10] = 'story';
$lang->product->menuOrder[15] = 'plan';
$lang->product->menuOrder[20] = 'project';
$lang->product->menuOrder[25] = 'release';
$lang->product->menuOrder[30] = 'roadmap';
$lang->product->menuOrder[35] = 'requirement';
$lang->product->menuOrder[40] = 'track';
$lang->product->menuOrder[45] = 'doc';
$lang->product->menuOrder[50] = 'dynamic';
$lang->product->menuOrder[55] = 'settings';
$lang->product->menuOrder[60] = 'create';
$lang->product->menuOrder[65] = 'all';

$lang->product->menu->settings['subMenu'] = new stdclass();
$lang->product->menu->settings['subMenu']->view      = array('link' => "{$lang->overview}|product|view|productID=%s", 'alias' => 'edit');
$lang->product->menu->settings['subMenu']->module    = array('link' => "{$lang->module}|tree|browse|product=%s&view=story", 'subModule' => 'tree');
$lang->product->menu->settings['subMenu']->branch    = array('link' => "@branch@|branch|manage|product=%s", 'subModule' => 'branch');
$lang->product->menu->settings['subMenu']->whitelist = array('link' => "{$lang->whitelist}|product|whitelist|product=%s", 'subModule' => 'personnel');

$lang->product->dividerMenu = $config->URAndSR ? ',story,requirement,settings,' : ',story,track,settings,';

/* Project menu. */
$lang->project->homeMenu         = new stdclass();
$lang->project->homeMenu->browse = array('link' => "{$lang->project->list}|project|browse|", 'alias' => 'batchedit,create');
$lang->project->homeMenu->kanban = array('link' => "{$lang->project->kanban}|project|kanban|");

/* Scrum menu. */
$lang->scrum->menu              = new stdclass();
$lang->scrum->menu->index       = array('link' => "{$lang->dashboard}|project|index|project=%s");
$lang->scrum->menu->execution   = array('link' => "$lang->executionCommon|project|execution|status=undone&projectID=%s", 'exclude' => 'execution-testreport', 'subModule' => 'task');
$lang->scrum->menu->storyGroup  = array('link' => "{$lang->common->story}|projectstory|story|projectID=%s&product=%s",'class' => 'dropdown dropdown-hover', 'exclude' => 'tree-browse');
$lang->scrum->menu->story       = array('link' => "$lang->SRCommon|projectstory|story|projectID=%s", 'subModule' => 'projectstory,tree', 'alias' => 'story,track', 'exclude' => 'tree-browse');
$lang->scrum->menu->projectplan = array('link' => "{$lang->productplan->shortCommon}|projectplan|browse|productID=%s", 'subModule' => 'productplan');
$lang->scrum->menu->doc         = array('link' => "{$lang->doc->common}|doc|projectSpace|objectID=%s", 'subModule' => 'doc,api');
$lang->scrum->menu->qa          = array('link' => "{$lang->qa->common}|project|bug|projectID=%s", 'subModule' => 'testcase,testtask,bug,testreport', 'alias' => 'bug,testtask,testcase,testreport', 'exclude' => 'execution-create,execution-batchedit');
$lang->scrum->menu->devops      = array('link' => "{$lang->repo->common}|repo|browse|repoID=0&branchID=&objectID=%s", 'subModule' => 'repo');
$lang->scrum->menu->build       = array('link' => "{$lang->build->common}|projectbuild|browse|project=%s", 'subModule' => 'projectbuild');
$lang->scrum->menu->release     = array('link' => "{$lang->release->common}|projectrelease|browse|project=%s", 'subModule' => 'projectrelease');
$lang->scrum->menu->dynamic     = array('link' => "$lang->dynamic|project|dynamic|project=%s");
$lang->scrum->menu->settings    = array('link' => "$lang->settings|project|view|project=%s", 'subModule' => 'tree,stakeholder', 'alias' => 'edit,manageproducts,group,managemembers,manageview,managepriv,whitelist,addwhitelist,team', 'exclude' => 'tree-browsetask');

$lang->scrum->menu->storyGroup['dropMenu'] = new stdclass();
$lang->scrum->menu->storyGroup['dropMenu']->story       = array('link' => "{$lang->SRCommon}|projectstory|story|projectID=%s&productID=%s", 'subModule' => 'tree');
$lang->scrum->menu->storyGroup['dropMenu']->requirement = array('link' => "{$lang->URCommon}|projectstory|story|projectID=%s&productID=%s&branch=0&browseType=&param=0&storyType=requirement", 'subModule' => 'tree');

$lang->scrum->dividerMenu = ',execution,programplan,doc,settings,';

/* Scrum menu order. */
$lang->scrum->menuOrder[5]  = 'index';
$lang->scrum->menuOrder[10] = 'execution';
$lang->scrum->menuOrder[15] = 'story';
$lang->scrum->menuOrder[16] = 'storyGroup';
$lang->scrum->menuOrder[18] = 'projectplan';
$lang->scrum->menuOrder[20] = 'qa';
$lang->scrum->menuOrder[25] = 'devops';
$lang->scrum->menuOrder[30] = 'doc';
$lang->scrum->menuOrder[35] = 'build';
$lang->scrum->menuOrder[40] = 'release';
$lang->scrum->menuOrder[45] = 'dynamic';
$lang->scrum->menuOrder[55] = 'settings';

$lang->scrum->menu->qa['subMenu'] = new stdclass();
//$lang->scrum->menu->qa['subMenu']->index      = array('link' => "$lang->dashboard|project|qa|projectID=%s");
$lang->scrum->menu->qa['subMenu']->bug        = array('link' => "{$lang->bug->common}|project|bug|projectID=%s", 'subModule' => 'bug');
$lang->scrum->menu->qa['subMenu']->testcase   = array('link' => "{$lang->testcase->shortCommon}|project|testcase|projectID=%s", 'subModule' => 'testsuite,testcase,caselib,tree');
$lang->scrum->menu->qa['subMenu']->testtask   = array('link' => "{$lang->testtask->common}|project|testtask|projectID=%s", 'subModule' => 'testtask', 'class' => 'dropdown dropdown-hover');
$lang->scrum->menu->qa['subMenu']->testreport = array('link' => "{$lang->testreport->common}|project|testreport|projectID=%s", 'subModule' => 'testreport');

$lang->scrum->menu->settings['subMenu']              = new stdclass();
$lang->scrum->menu->settings['subMenu']->view        = array('link' => "$lang->overview|project|view|project=%s", 'alias' => 'edit');
$lang->scrum->menu->settings['subMenu']->products    = array('link' => "{$lang->productCommon}|project|manageProducts|project=%s", 'alias' => 'manageproducts');
$lang->scrum->menu->settings['subMenu']->members     = array('link' => "{$lang->team->common}|project|team|project=%s", 'alias' => 'managemembers,team');
$lang->scrum->menu->settings['subMenu']->whitelist   = array('link' => "{$lang->whitelist}|project|whitelist|project=%s", 'subModule' => 'personnel');
$lang->scrum->menu->settings['subMenu']->stakeholder = array('link' => "{$lang->stakeholder->common}|stakeholder|browse|project=%s", 'subModule' => 'stakeholder');
$lang->scrum->menu->settings['subMenu']->group       = array('link' => "{$lang->priv}|project|group|project=%s", 'alias' => 'group,manageview,managepriv');
$lang->scrum->menu->settings['subMenu']->module      = array('link' => "{$lang->module}|tree|browse|product=%s&view=story");

/* Waterfall menu. */
$lang->waterfall->menu = new stdclass();
$lang->waterfall->menu->index      = array('link' => "$lang->dashboard|project|index|project=%s");
$lang->waterfall->menu->execution  = array('link' => "{$lang->stage->common}|project|execution|status=undone&projectID=%s", 'subModule' => 'programplan,task');
$lang->waterfall->menu->storyGroup = array('link' => "{$lang->common->story}|projectstory|story|projectID=%s",'class' => 'dropdown dropdown-hover', 'exclude' => 'tree-browse,projectstory-track');
$lang->waterfall->menu->story      = array('link' => "$lang->SRCommon|projectstory|story|projectID=%s", 'subModule' => 'projectstory,tree', 'alias' => 'story', 'exclude' => 'projectstory-track,tree-browse');
$lang->waterfall->menu->design     = array('link' => "{$lang->design->common}|design|browse|project=%s");
$lang->waterfall->menu->qa         = array('link' => "{$lang->qa->common}|project|bug|projectID=%s", 'subModule' => 'testcase,testtask,bug,testreport', 'alias' => 'bug,testtask,testcase,testreport');
$lang->waterfall->menu->doc        = array('link' => "{$lang->doc->common}|doc|projectSpace|objectID=%s");
$lang->waterfall->menu->devops     = array('link' => "{$lang->repo->common}|repo|browse|repoID=0&branchID=&objectID=%s", 'subModule' => 'repo');
$lang->waterfall->menu->build      = array('link' => "{$lang->build->common}|projectbuild|browse|project=%s", 'subModule' => 'projectbuild');
$lang->waterfall->menu->release    = array('link' => "{$lang->release->common}|projectrelease|browse|project=%s", 'subModule' => 'projectrelease');
$lang->waterfall->menu->dynamic    = array('link' => "$lang->dynamic|project|dynamic|project=%s");

$lang->waterfall->menu->settings = $lang->scrum->menu->settings;
$lang->waterfall->dividerMenu    = ',programplan,build,dynamic,';

$lang->waterfall->menu->storyGroup['dropMenu'] = $lang->scrum->menu->storyGroup['dropMenu'];

/* Waterfall menu order. */
$lang->waterfall->menuOrder[5]  = 'index';
$lang->waterfall->menuOrder[15] = 'programplan';
$lang->waterfall->menuOrder[20] = 'execution';
$lang->waterfall->menuOrder[25] = 'story';
$lang->waterfall->menuOrder[26] = 'storyGroup';
$lang->waterfall->menuOrder[30] = 'design';
$lang->waterfall->menuOrder[35] = 'devops';
$lang->waterfall->menuOrder[55] = 'qa';
$lang->waterfall->menuOrder[60] = 'doc';
$lang->waterfall->menuOrder[65] = 'build';
$lang->waterfall->menuOrder[70] = 'release';
$lang->waterfall->menuOrder[80] = 'dynamic';

$lang->waterfall->menu->programplan['subMenu'] = new stdclass();
$lang->waterfall->menu->programplan['subMenu']->lists = array('link' => "{$lang->stage->list}|programplan|browse|projectID=%s&productID=0&type=lists", 'alias' => 'create');

$lang->waterfall->menu->qa['subMenu'] = new stdclass();
$lang->waterfall->menu->qa['subMenu']->bug        = array('link' => "{$lang->bug->common}|project|bug|projectID=%s", 'subModule' => 'bug');
$lang->waterfall->menu->qa['subMenu']->testcase   = array('link' => "{$lang->testcase->shortCommon}|project|testcase|projectID=%s", 'subModule' => 'testsuite,testcase,caselib,tree');
$lang->waterfall->menu->qa['subMenu']->testtask   = array('link' => "{$lang->testtask->common}|project|testtask|projectID=%s", 'subModule' => 'testtask', 'class' => 'dropdown dropdown-hover');
$lang->waterfall->menu->qa['subMenu']->testreport = array('link' => "{$lang->testreport->common}|project|testreport|projectID=%s", 'subModule' => 'testreport');

$lang->waterfall->menu->design['subMenu'] = new stdclass();
$lang->waterfall->menu->design['subMenu']->all  = array('link' => "$lang->all|design|browse|projectID=%s&productID=0&browseType=all");
$lang->waterfall->menu->design['subMenu']->hlds = array('link' => "{$lang->design->HLDS}|design|browse|projectID=%s&productID=0&browseType=HLDS");
$lang->waterfall->menu->design['subMenu']->dds  = array('link' => "{$lang->design->DDS}|design|browse|projectID=%s&productID=0&browseType=DDS");
$lang->waterfall->menu->design['subMenu']->dbds = array('link' => "{$lang->design->DBDS}|design|browse|projectID=%s&productID=0&browseType=DBDS");
$lang->waterfall->menu->design['subMenu']->ads  = array('link' => "{$lang->design->ADS}|design|browse|projectID=%s&productID=0&browseType=ADS");

/* Kanban project menu. */
$lang->kanbanProject = new stdclass();
$lang->kanbanProject->menu = new stdclass();
$lang->kanbanProject->menu->index    = array('link' => "{$lang->kanban->common}|project|index|project=%s");
$lang->kanbanProject->menu->build    = array('link' => "{$lang->build->common}|projectbuild|browse|project=%s", 'subModule' => 'projectbuild');
$lang->kanbanProject->menu->settings = array('link' => "$lang->settings|project|view|project=%s", 'subModule' => 'tree,stakeholder', 'alias' => 'edit,manageproducts,group,managemembers,manageview,managepriv,whitelist,addwhitelist,team');

$lang->kanbanProject->dividerMenu = '';

$lang->kanbanProject->menuOrder     = array();
$lang->kanbanProject->menuOrder[5]  = 'index';
$lang->kanbanProject->menuOrder[10] = 'build';
$lang->kanbanProject->menuOrder[15] = 'settings';

$lang->kanbanProject->menu->settings['subMenu']            = new stdclass();
$lang->kanbanProject->menu->settings['subMenu']->view      = array('link' => "$lang->overview|project|view|project=%s", 'alias' => 'edit');
$lang->kanbanProject->menu->settings['subMenu']->products  = array('link' => "{$lang->productCommon}|project|manageProducts|project=%s", 'alias' => 'manageproducts');
$lang->kanbanProject->menu->settings['subMenu']->members   = array('link' => "{$lang->team->common}|project|team|project=%s", 'alias' => 'managemembers,team');
$lang->kanbanProject->menu->settings['subMenu']->whitelist = array('link' => "{$lang->whitelist}|project|whitelist|project=%s", 'subModule' => 'personnel');
$lang->kanbanProject->menu->settings['subMenu']->module    = array('link' => "{$lang->module}|tree|browse|product=%s&view=story");

/* Execution menu. */
$lang->execution->homeMenu = new stdclass();
$lang->execution->homeMenu->all             = array('link' => "{$lang->execution->all}|execution|all|", 'alias' => 'create,batchedit', 'exclude' => 'mr-create');
$lang->execution->homeMenu->executionkanban = array('link' => "{$lang->execution->executionKanban}|execution|executionkanban|");

$lang->execution->menu = new stdclass();
$lang->execution->menu->task   = array('link' => "{$lang->task->common}|execution|task|executionID=%s", 'subModule' => 'task,tree', 'alias' => 'importtask,importbug', 'exclude' => 'tree-browse');
$lang->execution->menu->kanban = array('link' => "$lang->executionKanban|execution|taskkanban|executionID=%s");
$lang->execution->menu->burn   = array('link' => "$lang->burn|execution|burn|executionID=%s");
$lang->execution->menu->view   = array('link' => "$lang->view|execution|grouptask|executionID=%s", 'alias' => 'grouptask,tree,taskeffort,gantt,calendar,relation,maintainrelation');

if($config->edition != 'open') $lang->execution->menu->view = array('link' => "$lang->view|execution|gantt|executionID=%s", 'alias' => 'grouptask,tree,taskeffort,gantt,calendar,relation,maintainrelation');

$lang->execution->menu->storyGroup = array('link' => "{$lang->common->story}|execution|story|executionID=%s",'class' => 'dropdown dropdown-hover', 'subModule' => 'story', 'alias' => 'batchcreate,storykanban,batchtotask');
$lang->execution->menu->story      = array('link' => "$lang->SRCommon|execution|story|executionID=%s", 'subModule' => 'story', 'alias' => 'storyview,storykanban,linkstory,batchtotask');
$lang->execution->menu->qa         = array('link' => "{$lang->qa->common}|execution|bug|executionID=%s", 'subModule' => 'bug,testcase,testtask,testreport', 'alias' => 'qa,bug,testcase,testtask,testreport');
$lang->execution->menu->devops     = array('link' => "{$lang->devops->common}|repo|browse|repoID=0&branchID=&objectID=%s", 'subModule' => 'repo,mr', 'alias' => 'create');
$lang->execution->menu->doc        = array('link' => "{$lang->doc->common}|execution|doc|objectID=%s", 'subModule' => 'doc');
$lang->execution->menu->build      = array('link' => "{$lang->build->common}|execution|build|executionID=%s", 'subModule' => 'build');
$lang->execution->menu->action     = array('link' => "$lang->dynamic|execution|dynamic|executionID=%s");
$lang->execution->menu->settings   = array('link' => "$lang->settings|execution|view|executionID=%s", 'subModule' => 'personnel', 'alias' => 'edit,manageproducts,team,whitelist,addwhitelist,managemembers', 'class' => 'dropdown dropdown-hover');
$lang->execution->menu->more       = array('link' => "$lang->more|execution|more|%s");

$lang->execution->menu->storyGroup['dropMenu'] = new stdclass();
$lang->execution->menu->storyGroup['dropMenu']->story       = array('link' => "{$lang->SRCommon}|execution|story|executionID=%s");
$lang->execution->menu->storyGroup['dropMenu']->requirement = array('link' => "{$lang->URCommon}|execution|story|executionID=%s&storyType=requirement");

/* Execution menu order. */
$lang->execution->menuOrder[5]  = 'task';
$lang->execution->menuOrder[10] = 'kanban';
$lang->execution->menuOrder[15] = 'CFD';
$lang->execution->menuOrder[20] = 'burn';
$lang->execution->menuOrder[25] = 'view';
$lang->execution->menuOrder[30] = 'story';
$lang->execution->menuOrder[35] = 'qa';
$lang->execution->menuOrder[40] = 'repo';
$lang->execution->menuOrder[45] = 'devops';
$lang->execution->menuOrder[50] = 'doc';
$lang->execution->menuOrder[55] = 'build';
$lang->execution->menuOrder[60] = 'release';
$lang->execution->menuOrder[65] = 'action';
$lang->execution->menuOrder[70] = 'settings';

$lang->execution->menu->view['subMenu']            = new stdclass();
$lang->execution->menu->view['subMenu']->groupTask = "$lang->groupView|execution|grouptask|executionID=%s";
$lang->execution->menu->view['subMenu']->tree      = "$lang->treeView|execution|tree|executionID=%s";

$lang->execution->menu->qa['subMenu'] = new stdclass();
//$lang->execution->menu->qa['subMenu']->qa         = array('link' => "$lang->dashboard|execution|qa|executionID=%s");
$lang->execution->menu->qa['subMenu']->bug        = array('link' => "{$lang->bug->common}|execution|bug|executionID=%s", 'subModule' => 'bug');
$lang->execution->menu->qa['subMenu']->testcase   = array('link' => "{$lang->testcase->shortCommon}|execution|testcase|executionID=%s", 'subModule' => 'testcase');
$lang->execution->menu->qa['subMenu']->testtask   = array('link' => "{$lang->testtask->common}|execution|testtask|executionID=%s", 'subModule' => 'testtask');
$lang->execution->menu->qa['subMenu']->testreport = array('link' => "{$lang->testreport->common}|execution|testreport|exeutionID=%s", 'subModule' => 'testreport');

$lang->execution->menu->qa['menuOrder'][5]  = 'qa';
$lang->execution->menu->qa['menuOrder'][10] = 'bug';
$lang->execution->menu->qa['menuOrder'][15] = 'testcase';
$lang->execution->menu->qa['menuOrder'][20] = 'testtask';

$lang->execution->menu->devops['subMenu']       = new stdclass();
$lang->execution->menu->devops['subMenu']->repo = array('link' => "{$lang->repo->common}|repo|browse|repoID=0&branchID=&objectID=%s", 'subModule' => 'repo', 'exclude' => 'repo-review');
$lang->execution->menu->devops['subMenu']->mr   = array('link' => "{$lang->devops->mr}|mr|browse|repoID=0&mode=status&param=opened&objectID=%s", 'subModule' => 'mr', 'alias' => 'create');

$lang->execution->menu->devops['menuOrder'][5]  = 'repo';
$lang->execution->menu->devops['menuOrder'][15] = 'mr';

$lang->execution->menu->settings['subMenu'] = new stdclass();
$lang->execution->menu->settings['subMenu']->view      = array('link' => "$lang->overview|execution|view|executionID=%s", 'subModule' => 'view', 'alias' => 'edit,start,suspend,putoff,close');
$lang->execution->menu->settings['subMenu']->products  = array('link' => "$lang->productCommon|execution|manageproducts|executionID=%s");
$lang->execution->menu->settings['subMenu']->team      = array('link' => "{$lang->team->common}|execution|team|executionID=%s", 'alias' => 'managemembers');
$lang->execution->menu->settings['subMenu']->whitelist = array('link' => "$lang->whitelist|execution|whitelist|executionID=%s", 'subModule' => 'personnel', 'alias' => 'addwhitelist');

$lang->execution->dividerMenu = ',story,build,';

$lang->project->noMultiple                          = new stdclass();
$lang->project->noMultiple->scrum                   = new stdclass();
$lang->project->noMultiple->scrum->menu             = new stdclass();
$lang->project->noMultiple->scrum->menu->task       = $lang->execution->menu->task;
$lang->project->noMultiple->scrum->menu->kanban     = $lang->execution->menu->kanban;
$lang->project->noMultiple->scrum->menu->burn       = $lang->execution->menu->burn;
$lang->project->noMultiple->scrum->menu->view       = $lang->execution->menu->view;
$lang->project->noMultiple->scrum->menu->story      = $lang->execution->menu->story;
$lang->project->noMultiple->scrum->menu->storyGroup = $lang->execution->menu->storyGroup;
$lang->project->noMultiple->scrum->menu->qa         = $lang->scrum->menu->qa;
$lang->project->noMultiple->scrum->menu->devops     = $lang->scrum->menu->devops;
$lang->project->noMultiple->scrum->menu->doc        = $lang->scrum->menu->doc;
$lang->project->noMultiple->scrum->menu->build      = $lang->scrum->menu->build;
$lang->project->noMultiple->scrum->menu->release    = $lang->scrum->menu->release;
$lang->project->noMultiple->scrum->menu->dynamic    = $lang->scrum->menu->dynamic;
$lang->project->noMultiple->scrum->menu->settings   = $lang->scrum->menu->settings;

$lang->project->noMultiple->kanban                 = new stdclass();
$lang->project->noMultiple->kanban->menu           = new stdclass();
$lang->project->noMultiple->kanban->menu->kanban   = array('link' => "{$lang->kanban->common}|execution|kanban|executionID=%s");
$lang->project->noMultiple->kanban->menu->CFD      = array('link' => "{$lang->execution->CFD}|execution|cfd|executionID=%s");
$lang->project->noMultiple->kanban->menu->build    = $lang->kanbanProject->menu->build;
$lang->project->noMultiple->kanban->menu->settings = $lang->kanbanProject->menu->settings;

$lang->project->noMultiple->scrum->dividerMenu  = ',story,build,';
$lang->project->noMultiple->kanban->dividerMenu = '';

$lang->project->noMultiple->scrum->menuOrder[5]  = 'task';
$lang->project->noMultiple->scrum->menuOrder[10] = 'kanban';
$lang->project->noMultiple->scrum->menuOrder[15] = 'burn';
$lang->project->noMultiple->scrum->menuOrder[20] = 'view';
$lang->project->noMultiple->scrum->menuOrder[25] = 'story';
$lang->project->noMultiple->scrum->menuOrder[30] = 'qa';
$lang->project->noMultiple->scrum->menuOrder[35] = 'devops';
$lang->project->noMultiple->scrum->menuOrder[40] = 'doc';
$lang->project->noMultiple->scrum->menuOrder[45] = 'build';
$lang->project->noMultiple->scrum->menuOrder[48] = 'release';
$lang->project->noMultiple->scrum->menuOrder[50] = 'dynamic';
$lang->project->noMultiple->scrum->menuOrder[55] = 'settings';

$lang->project->noMultiple->kanban->menuOrder[5]  = 'kanban';
$lang->project->noMultiple->kanban->menuOrder[10] = 'CFD';
$lang->project->noMultiple->kanban->menuOrder[15] = 'build';
$lang->project->noMultiple->kanban->menuOrder[20] = 'settings';

/* QA menu.*/
$lang->qa->menu = new stdclass();
$lang->qa->menu->index         = array('link' => "$lang->dashboard|qa|index");
$lang->qa->menu->bug           = array('link' => "{$lang->bug->common}|bug|browse|productID=%s", 'subModule' => 'bug');
$lang->qa->menu->testcase      = array('link' => "{$lang->testcase->shortCommon}|testcase|browse|productID=%s", 'subModule' => 'testcase,story');
$lang->qa->menu->testsuite     = array('link' => "{$lang->testcase->testsuite}|testsuite|browse|productID=%s", 'subModule' => 'testsuite');
$lang->qa->menu->testtask      = array('link' => "{$lang->testtask->common}|testtask|browse|productID=%s", 'subModule' => 'testtask', 'alias' => 'view,edit,linkcase,cases,start,close,batchrun,groupcase,report,importunitresult', 'exclude' => 'testtask-browseunits');
$lang->qa->menu->report        = array('link' => "{$lang->testreport->common}|testreport|browse|productID=%s", 'subModule' => 'testreport');
$lang->qa->menu->caselib       = array('link' => "{$lang->testcase->caselib}|caselib|browse|libID=0", 'subModule' => 'caselib');
$lang->qa->menu->automation    = array('link' => "{$lang->automation->common}:", 'subModule' => 'automation', 'alias' => '', 'class' => "qa-automation-menu");
$lang->qa->menu->zahost        = array('link' => "{$lang->zahost->common}|zahost|browse", 'subModule' => 'zahost');
$lang->qa->menu->zanode        = array('link' => "{$lang->zanode->common}|zanode|browse", 'subModule' => 'zanode');

/* QA menu order. */
$lang->qa->menuOrder[5]  = 'product';
$lang->qa->menuOrder[10] = 'index';
$lang->qa->menuOrder[15] = 'bug';
$lang->qa->menuOrder[20] = 'testcase';
$lang->qa->menuOrder[25] = 'testsuite';
$lang->qa->menuOrder[30] = 'testtask';
$lang->qa->menuOrder[35] = 'report';
$lang->qa->menuOrder[40] = 'caselib';
$lang->qa->menuOrder[45] = 'automation';

$lang->qa->dividerMenu = ',bug,testtask,caselib,automation,';

/* DevOps menu. */
$lang->devops->homeMenu = new stdclass();
$lang->devops->homeMenu->repos        = array('link' => "{$lang->devops->repo}|repo|maintain", 'alias' => 'create,edit,import,createrepo');
$lang->devops->homeMenu->compile      = array('link' => "{$lang->devops->compile}|job|browse", 'subModule' => 'compile,job');
$lang->devops->homeMenu->artifactrepo = array('link' => "{$lang->devops->artifactrepo}|artifactrepo|browse", 'alias' => 'create');
if($config->edition != 'open') $lang->devops->homeMenu->deploy = array('link' => "{$lang->devops->deploy}|deploy|browse", 'alias' => 'steps,managestep,create,edit,browse,view,scope,cases', 'subModule' => 'ops,deploy');
$lang->devops->homeMenu->apps = array('link' => "{$lang->app->common}|space|browse", 'subModule' => 'instance,store,gitlab,gitea,gogs,jenkins,sonarqube', 'alias' => 'createapplication,binduser,edit');

$lang->devops->menu = new stdclass();
$lang->devops->menu->code    = array('link' => "{$lang->repocode->common}|repo|browse|repoID=%s", 'alias' => 'diff,view,revision,log,blame,showsynccommit');
$lang->devops->menu->mr      = array('link' => "{$lang->devops->mr}|mr|browse|repoID=%s");
$lang->devops->menu->compile = array('link' => "{$lang->devops->compile}|job|browse|repoID=%s", 'subModule' => 'compile,job');

$lang->devops->menuOrder[10] = 'repos';
$lang->devops->menuOrder[15] = 'code';
$lang->devops->menuOrder[20] = 'mr';
$lang->devops->menuOrder[25] = 'compile';
$lang->devops->menuOrder[30] = 'artifactrepo';
$lang->devops->menuOrder[35] = 'apps';

$lang->devops->dividerMenu = ',apps,';

/* Kanban menu. */
$lang->kanban->menu = new stdclass();

/* Doc menu. */
$lang->doc->menu = new stdclass();
$lang->doc->menu->dashboard = array('link' => "{$lang->dashboard}|doc|index");
$lang->doc->menu->my        = array('link' => "{$lang->doc->mySpace}|doc|mySpace|type=mine", 'alias' => 'myspace');
$lang->doc->menu->product   = array('link' => "{$lang->doc->productSpace}|doc|productSpace|", 'alias' => 'productspace');
$lang->doc->menu->project   = array('link' => "{$lang->doc->projectSpace}|doc|projectSpace|", 'alias' => 'projectspace');
$lang->doc->menu->api       = array('link' => "{$lang->doc->apiSpace}|api|index", 'alias' => '', 'exclude' => 'index');
$lang->doc->menu->team      = array('link' => "{$lang->doc->teamSpace}|doc|teamSpace|", 'alias' => 'teamspace');

$lang->doc->dividerMenu = ',product,';

/* Doc menu order. */
$lang->doc->menuOrder[5]  = 'dashboard';
$lang->doc->menuOrder[10] = 'my';
$lang->doc->menuOrder[15] = 'product';
$lang->doc->menuOrder[20] = 'project';
$lang->doc->menuOrder[25] = 'api';
$lang->doc->menuOrder[30] = 'team';

/* BI menu.*/
$lang->bi->menu         = new stdclass();
$lang->bi->menu->screen = array('link' => "{$lang->screen->common}|screen|browse");
$lang->bi->menu->pivot  = array('link' => "{$lang->pivot->common}|pivot|preview");
$lang->bi->menu->chart  = array('link' => "{$lang->chart->common}|chart|preview");
$lang->bi->menu->metric = array('link' => "{$lang->metric->common}|metric|preview");

$lang->bi->dividerMenu = ',metric,';

/* BI menu order. */
$lang->bi->menuOrder[5]  = 'screen';
$lang->bi->menuOrder[10] = 'pivot';
$lang->bi->menuOrder[15] = 'chart';
$lang->bi->menuOrder[50] = 'metric';

/* Company menu.*/
$lang->company->menu              = new stdclass();
$lang->company->menu->browseUser  = array('link' => "{$lang->user->common}|company|browse", 'subModule' => ',user,');
$lang->company->menu->dept        = array('link' => "{$lang->dept->common}|dept|browse", 'subModule' => 'dept');
$lang->company->menu->browseGroup = array('link' => "$lang->priv|group|browse", 'subModule' => 'group');

/* Company menu order. */
$lang->company->menuOrder[5]  = 'browseUser';
$lang->company->menuOrder[10] = 'dept';
$lang->company->menuOrder[15] = 'browseGroup';
$lang->company->menuOrder[20] = 'addGroup';
$lang->company->menuOrder[25] = 'batchAddUser';
$lang->company->menuOrder[30] = 'addUser';

/* Admin menu. */
$lang->admin->menu = new stdclass();

$lang->subject->menu               = new stdclass();
$lang->subject->menu->storyConcept = array('link' => "{$lang->storyConcept}|custom|browsestoryconcept|");

/* System menu. */
$lang->system->menu          = new stdclass();
$lang->system->menu->team    = array('link' => "{$lang->team->common}|my|team|", 'subModule' => 'user');
$lang->system->menu->dynamic = array('link' => "$lang->dynamic|company|dynamic|");
$lang->system->menu->view    = array('link' => "{$lang->company->common}|company|view");

/* System menu order. */
$lang->system->menuOrder[5]  = 'team';
$lang->system->menuOrder[10] = 'calendar';
$lang->system->menuOrder[15] = 'dynamic';
$lang->system->menuOrder[20] = 'view';

/* Nav group.*/
$lang->navGroup = new stdclass();
$lang->navGroup->my               = 'my';
$lang->navGroup->effort           = 'my';
$lang->navGroup->score            = 'my';
$lang->navGroup->todo             = 'my';
$lang->navGroup->contact          = 'my';
$lang->navGroup->personalsettings = 'my';

$lang->navGroup->program            = 'program';
$lang->navGroup->personnel          = 'program';
$lang->navGroup->programstakeholder = 'program';

$lang->navGroup->product         = 'product';
$lang->navGroup->productplan     = 'product';
$lang->navGroup->release         = 'product';
$lang->navGroup->branch          = 'product';
$lang->navGroup->story           = 'product';
$lang->navGroup->requirement     = 'product';
$lang->navGroup->productsettings = 'product';

$lang->navGroup->project         = 'project';
$lang->navGroup->deploy          = 'project';
$lang->navGroup->programplan     = 'project';
$lang->navGroup->design          = 'project';
$lang->navGroup->stakeholder     = 'project';
$lang->navGroup->projectsettings = 'project';
$lang->navGroup->projectreview   = 'project';
$lang->navGroup->projecttrack    = 'project';
$lang->navGroup->projectqa       = 'project';

$lang->navGroup->projectbuild   = 'project';
$lang->navGroup->projectstory   = 'project';
$lang->navGroup->projectplan    = 'project';
$lang->navGroup->review         = 'project';
$lang->navGroup->reviewissue    = 'project';
$lang->navGroup->pssp           = 'project';
$lang->navGroup->auditplan      = 'project';
$lang->navGroup->cm             = 'project';
$lang->navGroup->nc             = 'project';
$lang->navGroup->projectrelease = 'project';
$lang->navGroup->build          = 'project';
$lang->navGroup->milestone      = 'project';
$lang->navGroup->researchplan   = 'project';
$lang->navGroup->workestimation = 'project';
$lang->navGroup->gapanalysis    = 'project';

$lang->navGroup->execution         = 'execution';
$lang->navGroup->task              = 'execution';
$lang->navGroup->build             = 'execution';
$lang->navGroup->team              = 'execution';
$lang->navGroup->executionview     = 'execution';
$lang->navGroup->executiongantt    = 'execution';
$lang->navGroup->executionkanban   = 'execution';
$lang->navGroup->executionburn     = 'execution';
$lang->navGroup->executioncfd      = 'execution';
$lang->navGroup->executionstory    = 'execution';
$lang->navGroup->executionqa       = 'execution';
$lang->navGroup->executionsettings = 'execution';

$lang->navGroup->managespace  = 'kanban';
$lang->navGroup->kanbanspace  = 'kanban';
$lang->navGroup->kanban       = 'kanban';
$lang->navGroup->kanbanregion = 'kanban';
$lang->navGroup->kanbanlane   = 'kanban';
$lang->navGroup->kanbancolumn = 'kanban';
$lang->navGroup->kanbancard   = 'kanban';

$lang->navGroup->doc    = 'doc';
$lang->navGroup->doclib = 'doc';
$lang->navGroup->api    = 'doc';

$lang->navGroup->screen   = 'bi';
$lang->navGroup->pivot    = 'bi';
$lang->navGroup->chart    = 'bi';
$lang->navGroup->bidesign = 'bi';
$lang->navGroup->report   = 'bi';
$lang->navGroup->metric   = 'bi';

$lang->navGroup->qa            = 'qa';
$lang->navGroup->bug           = 'qa';
$lang->navGroup->testcase      = 'qa';
$lang->navGroup->testtask      = 'qa';
$lang->navGroup->zahost        = 'qa';
$lang->navGroup->zanode        = 'qa';
$lang->navGroup->testreport    = 'qa';
$lang->navGroup->testcase      = 'qa';
$lang->navGroup->testtask      = 'qa';
$lang->navGroup->testsuite     = 'qa';
$lang->navGroup->caselib       = 'qa';

$lang->navGroup->devops           = 'devops';
$lang->navGroup->repo             = 'devops';
$lang->navGroup->repo_setRules    = 'admin';
$lang->navGroup->job              = 'devops';
$lang->navGroup->jenkins          = 'devops';
$lang->navGroup->mr               = 'devops';
$lang->navGroup->gitlab           = 'devops';
$lang->navGroup->gogs             = 'devops';
$lang->navGroup->gitea            = 'devops';
$lang->navGroup->sonarqube        = 'devops';
$lang->navGroup->sonarqubeproject = 'devops';
$lang->navGroup->compile          = 'devops';
$lang->navGroup->ci               = 'devops';
$lang->navGroup->svn              = 'devops';
$lang->navGroup->git              = 'devops';
$lang->navGroup->app              = 'devops';
$lang->navGroup->pipeline         = 'devops';
$lang->navGroup->devopssetting    = 'devops';
$lang->navGroup->space            = 'devops';
$lang->navGroup->store            = 'devops';
$lang->navGroup->instance         = 'devops';
$lang->navGroup->deploy           = 'devops';
$lang->navGroup->artifactrepo     = 'devops';

$lang->navGroup->company        = 'system';
$lang->navGroup->systemteam     = 'system';
$lang->navGroup->systemschedule = 'system';
$lang->navGroup->systemeffort   = 'system';
$lang->navGroup->systemdynamic  = 'system';
$lang->navGroup->systemcompany  = 'system';
$lang->navGroup->dataaccess     = 'system';

$lang->navGroup->attend   = 'attend';
$lang->navGroup->leave    = 'attend';
$lang->navGroup->makeup   = 'attend';
$lang->navGroup->overtime = 'attend';
$lang->navGroup->lieu     = 'attend';

$lang->navGroup->admin         = 'admin';
$lang->navGroup->dept          = 'admin';
$lang->navGroup->user          = 'admin';
$lang->navGroup->group         = 'admin';
$lang->navGroup->dept          = 'admin';
$lang->navGroup->webhook       = 'admin';
$lang->navGroup->sms           = 'admin';
$lang->navGroup->message       = 'admin';
$lang->navGroup->custom        = 'admin';
$lang->navGroup->cron          = 'admin';
$lang->navGroup->backup        = 'admin';
$lang->navGroup->mail          = 'admin';
$lang->navGroup->dev           = 'admin';
$lang->navGroup->editor        = 'admin';
$lang->navGroup->entry         = 'admin';
$lang->navGroup->extension     = 'admin';
$lang->navGroup->action        = 'admin';
$lang->navGroup->convert       = 'admin';
$lang->navGroup->stage         = 'admin';
$lang->navGroup->featureswitch = 'admin';
$lang->navGroup->importdata    = 'admin';
$lang->navGroup->systemsetting = 'admin';
$lang->navGroup->staffmanage   = 'admin';
$lang->navGroup->modelconfig   = 'admin';
$lang->navGroup->featureconfig = 'admin';
$lang->navGroup->doctemplate   = 'admin';
$lang->navGroup->notifysetting = 'admin';
$lang->navGroup->holidayseason = 'admin';
$lang->navGroup->system        = 'admin';
$lang->navGroup->holiday       = 'admin';
$lang->navGroup->serverroom    = 'admin';
$lang->navGroup->account       = 'admin';
$lang->navGroup->host          = 'admin';
$lang->navGroup->ops           = 'admin';
$lang->navGroup->service       = 'admin';
$lang->navGroup->domain        = 'admin';

$lang->navGroup->index   = 'index';
$lang->navGroup->misc    = 'misc';
$lang->navGroup->upgrade = 'upgrade';

if(!$config->URAndSR) unset($lang->product->menu->requirement, $lang->product->menuOrder[35]);
if(!helper::hasFeature('product_roadmap')) unset($lang->product->menu->roadmap, $lang->product->menuOrder[30]);
if(!helper::hasFeature('product_track'))
{
    unset($lang->product->menu->track, $lang->product->menuOrder[40]);
    $lang->product->dividerMenu = str_replace(',track,', ',doc,', $lang->product->dividerMenu);
}

if(!helper::hasFeature('devops'))
{
    unset($lang->mainNav->devops,         $lang->mainNav->menuOrder[35]);
    unset($lang->scrum->menu->devops,     $lang->scrum->menuOrder[25]);
    unset($lang->waterfall->menu->devops, $lang->waterfall->menuOrder[35]);
    unset($lang->execution->menu->devops, $lang->execution->menuOrder[45]);
    unset($lang->project->noMultiple->scrum->menu->devops, $lang->project->noMultiple->scrum->menuOrder[35]);
}

if(!helper::hasFeature('kanban'))
{
    unset($lang->mainNav->kanban, $lang->mainNav->menuOrder[40]);
    $lang->dividerMenu = str_replace(',kanban,' , ',doc,', $lang->dividerMenu);
}
