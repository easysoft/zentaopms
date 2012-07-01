<?php
/**
 * The common simplified chinese file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->arrow        = '<span class="icon-arrow">&nbsp; </span>';
$lang->colon        = '::';
$lang->comma        = ',';
$lang->dot          = '.';
$lang->at           = ' at ';
$lang->downArrow    = '↓';

$lang->ZenTaoPMS    = 'ZenTaoPMS';
$lang->welcome      = "Welcome to『%s』{$lang->colon} {$lang->ZenTaoPMS}";
$lang->myControl    = "Dashboard";
$lang->currentPos   = 'Current';
$lang->logout       = 'Logout';
$lang->login        = 'Login';
$lang->aboutZenTao  = 'About';
$lang->todayIs      = '%s, ';
$lang->runInfo      = "<div class='row'><div class='u-1 a-center' id='debugbar'>Time: %s ms, Memory: %s KB, Queries: %s.  </div></div>";

$lang->reset        = 'Reset';
$lang->edit         = 'Edit';
$lang->copy         = 'Copy';
$lang->delete       = 'Delete';
$lang->close        = 'Close';
$lang->link         = 'Link';
$lang->unlink       = 'Unlink';
$lang->import       = 'Import';
$lang->export       = 'Export';
$lang->setFileName  = 'Filename:';
$lang->activate     = 'Activate';
$lang->submitting   = 'Saving...';
$lang->save         = 'Save';
$lang->confirm      = 'Confirm';
$lang->preview      = 'View';
$lang->goback       = 'Back';
$lang->go           = 'GO';
$lang->more         = 'More';

$lang->actions      = 'Actions';
$lang->comment      = 'Comment';
$lang->history      = 'History';
$lang->attatch      = 'Attatch';
$lang->reverse      = '[Reverse]';
$lang->switchDisplay= '[Toggle Show]';
$lang->switchHelp   = 'Toggle Help';
$lang->addFiles     = 'Add Files';
$lang->files        = 'Files ';
$lang->unfold       = '+';
$lang->fold         = '-';

$lang->selectAll     = 'Select All';
$lang->selectReverse = 'Select Reverse';
$lang->notFound      = 'Sorry, the object not found.';
$lang->showAll       = '++ Show All ++';
$lang->hideClosed    = '-- Hide Closed--';

$lang->future       = 'Future';
$lang->year         = 'Year';
$lang->workingHour  = 'Hour';

$lang->idAB         = 'ID';
$lang->priAB        = 'P';
$lang->statusAB     = 'Status';
$lang->openedByAB   = 'Open';
$lang->assignedToAB = 'To';
$lang->typeAB       = 'Type';

$lang->common->common = 'Common module';

/* The main menu. */
$lang->menu->my      = '<span id="mainbg">&nbsp;</span>Dashboard|my|index';
$lang->menu->product = 'Product|product|index';
$lang->menu->project = 'Project|project|index';
$lang->menu->qa      = 'Test|qa|index';
$lang->menu->doc     = 'Doc|doc|index';
$lang->menu->report  = 'Report|report|index';
$lang->menu->company = 'Company|company|index';
$lang->menu->admin   = 'Admin|admin|index';

/* Order of main menu. */
$lang->menuOrder[5]  = 'my';
$lang->menuOrder[10] = 'product';
$lang->menuOrder[15] = 'project';
$lang->menuOrder[20] = 'qa';
$lang->menuOrder[25] = 'doc';
$lang->menuOrder[30] = 'report';
$lang->menuOrder[35] = 'company';
$lang->menuOrder[40] = 'admin';

/* The objects in the search box. */
$lang->searchObjects['bug']         = 'Bug';
$lang->searchObjects['story']       = 'Story';
$lang->searchObjects['task']        = 'Task';
$lang->searchObjects['testcase']    = 'Test Case';
$lang->searchObjects['project']     = 'Project';
$lang->searchObjects['product']     = 'Product';
$lang->searchObjects['user']        = 'User';
$lang->searchObjects['build']       = 'Build';
$lang->searchObjects['release']     = 'Release';
$lang->searchObjects['productplan'] = 'Plan';
$lang->searchObjects['testtask']    = 'Test Task';
$lang->searchObjects['doc']         = 'Doc';
$lang->searchTips                   = 'Id here(ctrl+g)';

/* Encode list of impot. */
$lang->importEncodeList['gbk']   = 'GBK';
$lang->importEncodeList['big5']  = 'BIG5';
$lang->importEncodeList['utf-8'] = 'UTF-8';

/* File type of export. */
$lang->exportFileTypeList['csv']  = 'csv';
$lang->exportFileTypeList['xml']  = 'xml';
$lang->exportFileTypeList['html'] = 'html';

/* Support charset. */
$lang->exportEncodeList['gbk']   = 'GBK';
$lang->exportEncodeList['big5']  = 'BIG5';
$lang->exportEncodeList['utf-8'] = 'UTF-8';

/* Themes. */
$lang->themes['default']   = 'Default';
$lang->themes['green']     = 'Green';
$lang->themes['red']       = 'Red';
$lang->themes['classblue'] = 'Blue';

/* Index mododule menu. */
$lang->index->menu->product = 'Products|product|browse';
$lang->index->menu->project = 'Projects|project|browse';

$lang->index->menuOrder[5]  = 'product';
$lang->index->menuOrder[10] = 'project';

/* Dashboard menu. */
$lang->my->menu->account        = '<span id="mybg">&nbsp;</span>%s' . $lang->arrow;
$lang->my->menu->index          = 'Index|my|index';
$lang->my->menu->todo           = array('link' => 'Todo|my|todo|', 'subModule' => 'todo');
$lang->my->menu->task           = 'Task|my|task|';
$lang->my->menu->bug            = 'Bug|my|bug|';
$lang->my->menu->testtask       = 'Test|my|testtask|';
$lang->my->menu->story          = 'Story|my|story|';
$lang->my->menu->myProject      = 'Project|my|project|';
$lang->my->menu->dynamic        = 'Dynamic|my|dynamic|';
$lang->my->menu->profile        = array('link' => 'Profile|my|profile|', 'alias' => 'editprofile');
$lang->my->menu->changePassword = 'Change Password|my|changePassword|';
$lang->todo->menu               = $lang->my->menu;

$lang->my->menuOrder[5]  = 'account';
$lang->my->menuOrder[10] = 'index';
$lang->my->menuOrder[15] = 'todo';
$lang->my->menuOrder[20] = 'task';
$lang->my->menuOrder[25] = 'bug';
$lang->my->menuOrder[30] = 'testtask';
$lang->my->menuOrder[35] = 'story';
$lang->my->menuOrder[40] = 'myProject';
$lang->my->menuOrder[45] = 'dynamic';
$lang->my->menuOrder[50] = 'profile';
$lang->my->menuOrder[55] = 'changePassword';
$lang->todo->menuOrder   = $lang->my->menuOrder;

/* Product menu. */
$lang->product->menu->list    = '%s';
$lang->product->menu->story   = array('link' => 'Story|product|browse|productID=%s',     'subModule' => 'story');
$lang->product->menu->dynamic = 'Dynamic|product|dynamic|productID=%s';
$lang->product->menu->plan    = array('link' => 'Plan|productplan|browse|productID=%s', 'subModule' => 'productplan');
$lang->product->menu->release = array('link' => 'Release|release|browse|productID=%s',     'subModule' => 'release');
$lang->product->menu->roadmap = 'Roadmap|product|roadmap|productID=%s';
$lang->product->menu->doc     = array('link' => 'Doc|product|doc|productID=%s', 'subModule' => 'doc');
$lang->product->menu->view    = 'Info|product|view|productID=%s';
$lang->product->menu->module  = 'Modules|tree|browse|productID=%s&view=story';
$lang->product->menu->project = 'Projects|product|project|status=all&productID=%s';
$lang->product->menu->order   = 'Order|product|order|productID=%s';
$lang->product->menu->create  = array('link' => '<span class="icon-add1">&nbsp;</span>New|product|create', 'float' => 'right');
$lang->product->menu->all     = array('link' => '<span class="icon-all">&nbsp;</span>All|product|index|locate=false', 'float' => 'right');
$lang->story->menu            = $lang->product->menu;
$lang->productplan->menu      = $lang->product->menu;
$lang->release->menu          = $lang->product->menu;

$lang->product->menuOrder[5]  = 'story';
$lang->product->menuOrder[10] = 'dynamic';
$lang->product->menuOrder[15] = 'plan';
$lang->product->menuOrder[20] = 'release';
$lang->product->menuOrder[25] = 'roadmap';
$lang->product->menuOrder[30] = 'doc';
$lang->product->menuOrder[35] = 'view';
$lang->product->menuOrder[40] = 'module';
$lang->product->menuOrder[45] = 'project';
$lang->product->menuOrder[50] = 'order';
$lang->product->menuOrder[55] = 'create';
$lang->product->menuOrder[60] = 'all';

$lang->story->menuOrder       = $lang->product->menuOrder;
$lang->productplan->menuOrder = $lang->product->menuOrder;
$lang->release->menuOrder     = $lang->product->menuOrder;

/* Project menu. */
$lang->project->menu->list      = '%s';
$lang->project->menu->task      = array('link' => 'Task|project|task|projectID=%s', 'subModule' => 'task', 'alias' => 'grouptask,importtask');
$lang->project->menu->story     = array('link' => 'Story|project|story|projectID=%s');
$lang->project->menu->bug       = 'Bug|project|bug|projectID=%s';
$lang->project->menu->dynamic   = 'Dynamic|project|dynamic|projectID=%s';
$lang->project->menu->build     = array('link' => 'Build|project|build|projectID=%s', 'subModule' => 'build');
$lang->project->menu->testtask  = 'Testtask|project|testtask|projectID=%s';
$lang->project->menu->burn      = 'Burn|project|burn|projectID=%s';
$lang->project->menu->team      = array('link' => 'Team|project|team|projectID=%s', 'alias' => 'managemembers');
$lang->project->menu->doc       = array('link' => 'Doc|project|doc|porjectID=%s', 'subModule' => 'doc');
$lang->project->menu->product   = 'Product|project|manageproducts|projectID=%s';
$lang->project->menu->linkstory = array('link' => 'Story|project|linkstory|projectID=%s');
$lang->project->menu->view      = 'Info|project|view|projectID=%s';
$lang->project->menu->order     = 'Order|project|order|projectID=%s';
$lang->project->menu->create    = array('link' => '<span class="icon-add1">&nbsp;</span>New|project|create', 'float' => 'right');
$lang->project->menu->copy      = array('link' => '<span class="icon-copy">&nbsp;</span>Copy|project|create|projectID=&copyProjectID=%s', 'float' => 'right');
$lang->project->menu->all       = array('link' => '<span class="icon-all">&nbsp;</span>Projects|project|index|locate=no', 'float' => 'right');
$lang->task->menu               = $lang->project->menu;
$lang->build->menu              = $lang->project->menu;

$lang->project->menuOrder[5]  = 'task';
$lang->project->menuOrder[10] = 'story';
$lang->project->menuOrder[15] = 'bug';
$lang->project->menuOrder[20] = 'build';
$lang->project->menuOrder[25] = 'testtask';
$lang->project->menuOrder[30] = 'burn';
$lang->project->menuOrder[35] = 'team';
$lang->project->menuOrder[40] = 'dynamic';
$lang->project->menuOrder[45] = 'doc';
$lang->project->menuOrder[50] = 'product';
$lang->project->menuOrder[55] = 'linkstory';
$lang->project->menuOrder[60] = 'view';
$lang->project->menuOrder[65] = 'order';
$lang->project->menuOrder[70] = 'create';
$lang->project->menuOrder[75] = 'copy';
$lang->project->menuOrder[80] = 'all';
$lang->task->menuOrder        = $lang->project->menuOrder;
$lang->build->menuOrder       = $lang->project->menuOrder;

/* QA menu. */
$lang->bug->menu->product  = '%s';
$lang->bug->menu->bug      = array('link' => 'Bug|bug|browse|productID=%s', 'alias' => 'view,create,edit,resolve,close,activate,report', 'subModule' => 'tree');
$lang->bug->menu->testcase = array('link' => 'Test Case|testcase|browse|productID=%s', 'alias' => 'view,create,edit');
$lang->bug->menu->testtask = array('link' => 'Test Task|testtask|browse|productID=%s');

$lang->testcase->menu->product  = '%s';
$lang->testcase->menu->bug      = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testcase->menu->testcase = array('link' => 'Test Case|testcase|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit', 'subModule' => 'tree');
$lang->testcase->menu->testtask = array('link' => 'Test Task|testtask|browse|productID=%s');

$lang->testtask->menu->product  = '%s';
$lang->testtask->menu->bug      = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testtask->menu->testcase = array('link' => 'Test Case|testcase|browse|productID=%s');
$lang->testtask->menu->testtask = array('link' => 'Test Task|testtask|browse|productID=%s', 'alias' => 'view,create,edit,linkcase,cases');
$lang->testtask->menu           = $lang->testcase->menu;

$lang->testcase->menuOrder[0]  = 'product';
$lang->testcase->menuOrder[5]  = 'bug';
$lang->testcase->menuOrder[10] = 'testcase';
$lang->testcase->menuOrder[15] = 'testtask';
$lang->testtask->menuOrder     = $lang->testcase->menuOrder;

/* Doc menu. */
$lang->doc->menu->list    = '%s';
$lang->doc->menu->browse  = array('link' => 'Doc|doc|browse|libID=%s');
$lang->doc->menu->edit    = 'Edit Library|doc|editLib|libID=%s';
$lang->doc->menu->module  = 'Modules|tree|browse|libID=%s&viewType=doc';
$lang->doc->menu->delete  = array('link' => 'Delete Library|doc|deleteLib|libID=%s', 'target' => 'hiddenwin');
$lang->doc->menu->create  = array('link' => '<span class="icon-add1">&nbsp;</span>New Library|doc|createLib', 'float' => 'right');

$lang->doc->menuOrder[5]  = 'browse';
$lang->doc->menuOrder[10] = 'edit';
$lang->doc->menuOrder[15] = 'module';
$lang->doc->menuOrder[20] = 'delete';
$lang->doc->menuOrder[25] = 'create';

/* Report menu. */
$lang->report->menu->prj     = array('link' => 'Project|report|projectdeviation');
$lang->report->menu->product = array('link' => 'Product|report|productinfo');
$lang->report->menu->test    = array('link' => 'Test|report|bugsummary');
$lang->report->menu->staff   = array('link' => 'Staff|report|workload');

$lang->report->menuOrder[5]  = 'prj';
$lang->report->menuOrder[10] = 'product';
$lang->report->menuOrder[15] = 'test';
$lang->report->menuOrder[20] = 'staff';

/* Company menu. */
$lang->company->menu->name        = '%s' . $lang->arrow;
$lang->company->menu->browseUser  = array('link' => 'Users|company|browse', 'subModule' => 'user');
$lang->company->menu->dept        = array('link' => 'Department|dept|browse', 'subModule' => 'dept');
$lang->company->menu->browseGroup = array('link' => 'Group|group|browse', 'subModule' => 'group');
$lang->company->menu->edit        = array('link' => 'Company|company|edit');
$lang->company->menu->dynamic     = 'Dynamic|company|dynamic|';
$lang->company->menu->addGroup    = array('link' => '<span class="icon-add1">&nbsp;</span>Add Group|group|create', 'float' => 'right');
$lang->company->menu->addUser     = array('link' => '<span class="icon-add1">&nbsp;</span>Add User|user|create|dept=%s&from=company', 'subModule' => 'user', 'float' => 'right');
$lang->dept->menu            = $lang->company->menu;
$lang->group->menu           = $lang->company->menu;

$lang->company->menuOrder[0]  = 'name';
$lang->company->menuOrder[5]  = 'browseUser';
$lang->company->menuOrder[10] = 'dept';
$lang->company->menuOrder[15] = 'browseGroup';
$lang->company->menuOrder[20] = 'edit';
$lang->company->menuOrder[25] = 'dynamic';
$lang->company->menuOrder[30] = 'addGroup';
$lang->company->menuOrder[35] = 'addUser';
$lang->dept->menuOrder        = $lang->company->menuOrder;
$lang->group->menuOrder       = $lang->company->menuOrder;

/* User menu. */
$lang->user->menu->account     = '%s' . $lang->arrow;
$lang->user->menu->todo        = array('link' => 'Todo|user|todo|account=%s', 'subModule' => 'todo');
$lang->user->menu->task        = 'Task|user|task|account=%s';
$lang->user->menu->bug         = 'Bug|user|bug|account=%s';
$lang->user->menu->dynamic     = 'Dynamic|user|dynamic|type=today&account=%s';
$lang->user->menu->projectList = 'Project|user|project|account=%s';
$lang->user->menu->profile     = array('link' => 'Profile|user|profile|account=%s', 'alias' => 'edit');
$lang->user->menu->browse      = array('link' => '<span class="icon-title">&nbsp;</span>Manage user|company|browse|', 'float' => 'right');

$lang->user->menuOrder[0]  = 'account';
$lang->user->menuOrder[5]  = 'todo';
$lang->user->menuOrder[10] = 'task';
$lang->user->menuOrder[15] = 'bug';
$lang->user->menuOrder[20] = 'dynamic';
$lang->user->menuOrder[25] = 'projectList';
$lang->user->menuOrder[30] = 'profile';
$lang->user->menuOrder[35] = 'browse';

/* Admin menu. */
$lang->admin->menu->index     = array('link' => 'Index|admin|index');
$lang->admin->menu->extension = array('link' => 'Extension|extension|browse', 'subModule' => 'extension');
$lang->admin->menu->editor    = array('link' => 'Extension editor|editor|index', 'subModule' => 'editor');
$lang->admin->menu->mail      = array('link' => 'EmailSetting|mail|index', 'subModule' => 'mail');
$lang->admin->menu->clearData = array('link' => 'ClearData|admin|cleardata');
$lang->admin->menu->convert   = array('link' => 'Import|convert|index', 'subModule' => 'convert');
$lang->admin->menu->trashes   = array('link' => 'Trash|action|trash', 'subModule' => 'action');
$lang->convert->menu          = $lang->admin->menu;
$lang->upgrade->menu          = $lang->admin->menu;
$lang->action->menu           = $lang->admin->menu;
$lang->extension->menu        = $lang->admin->menu;
$lang->editor->menu           = $lang->admin->menu;
$lang->mail->menu             = $lang->admin->menu;

$lang->admin->menuOrder[5] = 'index';
$lang->admin->menuOrder[10] = 'extension';
$lang->admin->menuOrder[15] = 'editor';
$lang->admin->menuOrder[20] = 'mail';
$lang->admin->menuOrder[25] = 'clearData';
$lang->admin->menuOrder[30] = 'convert';
$lang->admin->menuOrder[35] = 'trashes';
$lang->convert->menuOrder   = $lang->admin->menuOrder;
$lang->upgrade->menuOrder   = $lang->admin->menuOrder;
$lang->action->menuOrder    = $lang->admin->menuOrder;
$lang->extension->menuOrder = $lang->admin->menuOrder;
$lang->editor->menuOrder    = $lang->admin->menuOrder;
$lang->mail->menuOrder      = $lang->admin->menuOrder;

/* Groups. */
$lang->menugroup->release     = 'product';
$lang->menugroup->story       = 'product';
$lang->menugroup->productplan = 'product';
$lang->menugroup->task        = 'project';
$lang->menugroup->build       = 'project';
$lang->menugroup->convert     = 'admin';
$lang->menugroup->upgrade     = 'admin';
$lang->menugroup->user        = 'company';
$lang->menugroup->group       = 'company';
$lang->menugroup->bug         = 'qa';
$lang->menugroup->testcase    = 'qa';
$lang->menugroup->testtask    = 'qa';
$lang->menugroup->people      = 'company';
$lang->menugroup->dept        = 'company';
$lang->menugroup->todo        = 'my';
$lang->menugroup->action      = 'admin';
$lang->menugroup->extension   = 'admin';
$lang->menugroup->editor      = 'admin';
$lang->menugroup->mail        = 'admin';

/* Module order. */
$lang->moduleOrder[0]   = 'index';
$lang->moduleOrder[5]   = 'my';
$lang->moduleOrder[10]  = 'todo';
$lang->moduleOrder[15]  = 'product';
$lang->moduleOrder[20]  = 'story';
$lang->moduleOrder[25]  = 'productplan';
$lang->moduleOrder[30]  = 'release';
$lang->moduleOrder[35]  = 'project';
$lang->moduleOrder[40]  = 'task';
$lang->moduleOrder[45]  = 'build';
$lang->moduleOrder[50]  = 'qa';
$lang->moduleOrder[55]  = 'bug';
$lang->moduleOrder[60]  = 'testcase';
$lang->moduleOrder[65]  = 'testtask';
$lang->moduleOrder[70]  = 'doc';
$lang->moduleOrder[75]  = 'svn';
$lang->moduleOrder[80]  = 'company';
$lang->moduleOrder[85]  = 'dept';
$lang->moduleOrder[90]  = 'group';
$lang->moduleOrder[95]  = 'user';
$lang->moduleOrder[100] = 'tree';
$lang->moduleOrder[105] = 'search';
$lang->moduleOrder[110] = 'admin';
$lang->moduleOrder[115] = 'api';
$lang->moduleOrder[120] = 'file';
$lang->moduleOrder[125] = 'misc';
$lang->moduleOrder[130] = 'action';

/* Method order. */
$lang->index->methodOrder[0] = 'index';

$lang->my->methodOrder[0]  = 'index';
$lang->my->methodOrder[5]  = 'todo';
$lang->my->methodOrder[10] = 'task';
$lang->my->methodOrder[15] = 'bug';
$lang->my->methodOrder[20] = 'testTask';
$lang->my->methodOrder[25] = 'testCase';
$lang->my->methodOrder[30] = 'story';
$lang->my->methodOrder[35] = 'project';
$lang->my->methodOrder[40] = 'profile';
$lang->my->methodOrder[45] = 'dynamic';
$lang->my->methodOrder[50] = 'editProfile';
$lang->my->methodOrder[55] = 'changePassword';

$lang->todo->methodOrder[5]  = 'create';
$lang->todo->methodOrder[10] = 'batchCreate';
$lang->todo->methodOrder[15] = 'edit';
$lang->todo->methodOrder[20] = 'view';
$lang->todo->methodOrder[25] = 'delete';
$lang->todo->methodOrder[30] = 'export';
$lang->todo->methodOrder[35] = 'mark';
$lang->todo->methodOrder[40] = 'import2Today';

$lang->product->methodOrder[0]  = 'index';
$lang->product->methodOrder[5]  = 'browse';
$lang->product->methodOrder[10] = 'create';
$lang->product->methodOrder[15] = 'view';
$lang->product->methodOrder[20] = 'edit';
$lang->product->methodOrder[25] = 'order';
$lang->product->methodOrder[30] = 'delete';
$lang->product->methodOrder[35] = 'roadmap';
$lang->product->methodOrder[40] = 'doc';
$lang->product->methodOrder[45] = 'dynamic';
$lang->product->methodOrder[50] = 'project';
$lang->product->methodOrder[55] = 'ajaxGetProjects';
$lang->product->methodOrder[60] = 'ajaxGetPlans';

$lang->story->methodOrder[] = 'create';
$lang->story->methodOrder[] = 'batchCreate';
$lang->story->methodOrder[] = 'edit';
$lang->story->methodOrder[] = 'export';
$lang->story->methodOrder[] = 'delete';
$lang->story->methodOrder[] = 'view';
$lang->story->methodOrder[] = 'change';
$lang->story->methodOrder[] = 'review';
$lang->story->methodOrder[] = 'close';
$lang->story->methodOrder[] = 'batchClose';
$lang->story->methodOrder[] = 'activate';
$lang->story->methodOrder[] = 'tasks';
$lang->story->methodOrder[] = 'report';
$lang->story->methodOrder[] = 'ajaxGetProjectStories';
$lang->story->methodOrder[] = 'ajaxGetProductStories';

$lang->productplan->methodOrder[] = 'browse';
$lang->productplan->methodOrder[] = 'create';
$lang->productplan->methodOrder[] = 'edit';
$lang->productplan->methodOrder[] = 'delete';
$lang->productplan->methodOrder[] = 'view';
$lang->productplan->methodOrder[] = 'linkStory';
$lang->productplan->methodOrder[] = 'unlinkStory';

$lang->release->methodOrder[] = 'browse';
$lang->release->methodOrder[] = 'create';
$lang->release->methodOrder[] = 'edit';
$lang->release->methodOrder[] = 'delete';
$lang->release->methodOrder[] = 'view';
$lang->release->methodOrder[] = 'ajaxGetStoriesAndBugs';
$lang->release->methodOrder[] = 'exportStoriesAndBugs';

$lang->project->methodOrder[] = 'index';
$lang->project->methodOrder[] = 'view';
$lang->project->methodOrder[] = 'browse';
$lang->project->methodOrder[] = 'create';
$lang->project->methodOrder[] = 'edit';
$lang->project->methodOrder[] = 'order';
$lang->project->methodOrder[] = 'delete';
$lang->project->methodOrder[] = 'task';
$lang->project->methodOrder[] = 'grouptask';
$lang->project->methodOrder[] = 'importtask';
$lang->project->methodOrder[] = 'importBug';
$lang->project->methodOrder[] = 'story';
$lang->project->methodOrder[] = 'build';
$lang->project->methodOrder[] = 'testtask';
$lang->project->methodOrder[] = 'bug';
$lang->project->methodOrder[] = 'burn';
$lang->project->methodOrder[] = 'computeBurn';
$lang->project->methodOrder[] = 'burnData';
$lang->project->methodOrder[] = 'team';
$lang->project->methodOrder[] = 'doc';
$lang->project->methodOrder[] = 'dynamic';
$lang->project->methodOrder[] = 'manageProducts';
$lang->project->methodOrder[] = 'manageMembers';
$lang->project->methodOrder[] = 'unlinkMember';
$lang->project->methodOrder[] = 'linkStory';
$lang->project->methodOrder[] = 'unlinkStory';
$lang->project->methodOrder[] = 'ajaxGetProducts';

$lang->task->methodOrder[] = 'create';
$lang->task->methodOrder[] = 'batchCreate';
$lang->task->methodOrder[] = 'batchEdit';
$lang->task->methodOrder[] = 'edit';
$lang->task->methodOrder[] = 'assignTo';
$lang->task->methodOrder[] = 'start';
$lang->task->methodOrder[] = 'finish';
$lang->task->methodOrder[] = 'cancel';
$lang->task->methodOrder[] = 'close';
$lang->task->methodOrder[] = 'batchClose';
$lang->task->methodOrder[] = 'activate';
$lang->task->methodOrder[] = 'delete';
$lang->task->methodOrder[] = 'view';
$lang->task->methodOrder[] = 'export';
$lang->task->methodOrder[] = 'confirmStoryChange';
$lang->task->methodOrder[] = 'ajaxGetUserTasks';
$lang->task->methodOrder[] = 'ajaxGetProjectTasks';
$lang->task->methodOrder[] = 'report';

$lang->build->methodOrder[] = 'create';
$lang->build->methodOrder[] = 'edit';
$lang->build->methodOrder[] = 'delete';
$lang->build->methodOrder[] = 'view';
$lang->build->methodOrder[] = 'ajaxGetProductBuilds';
$lang->build->methodOrder[] = 'ajaxGetProjectBuilds';

$lang->qa->methodOrder[] = 'index';

$lang->bug->methodOrder[] = 'index';
$lang->bug->methodOrder[] = 'browse';
$lang->bug->methodOrder[] = 'create';
$lang->bug->methodOrder[] = 'confirmBug';
$lang->bug->methodOrder[] = 'view';
$lang->bug->methodOrder[] = 'edit';
$lang->bug->methodOrder[] = 'assignTo';
$lang->bug->methodOrder[] = 'resolve';
$lang->bug->methodOrder[] = 'activate';
$lang->bug->methodOrder[] = 'close';
$lang->bug->methodOrder[] = 'report';
$lang->bug->methodOrder[] = 'export';
$lang->bug->methodOrder[] = 'confirmStoryChange';
$lang->bug->methodOrder[] = 'delete';
$lang->bug->methodOrder[] = 'saveTemplate';
$lang->bug->methodOrder[] = 'deleteTemplate';
$lang->bug->methodOrder[] = 'customFields';
$lang->bug->methodOrder[] = 'ajaxGetUserBugs';
$lang->bug->methodOrder[] = 'ajaxGetModuleOwner';

$lang->testcase->methodOrder[] = 'index';
$lang->testcase->methodOrder[] = 'browse';
$lang->testcase->methodOrder[] = 'create';
$lang->testcase->methodOrder[] = 'batchCreate';
$lang->testcase->methodOrder[] = 'view';
$lang->testcase->methodOrder[] = 'edit';
$lang->testcase->methodOrder[] = 'delete';
$lang->testcase->methodOrder[] = 'export';
$lang->testcase->methodOrder[] = 'confirmStoryChange';

$lang->testtask->methodOrder[] = 'index';
$lang->testtask->methodOrder[] = 'create';
$lang->testtask->methodOrder[] = 'browse';
$lang->testtask->methodOrder[] = 'view';
$lang->testtask->methodOrder[] = 'cases';
$lang->testtask->methodOrder[] = 'edit';
$lang->testtask->methodOrder[] = 'delete';
$lang->testtask->methodOrder[] = 'batchAssign';
$lang->testtask->methodOrder[] = 'linkcase';
$lang->testtask->methodOrder[] = 'unlinkcase';
$lang->testtask->methodOrder[] = 'runcase';
$lang->testtask->methodOrder[] = 'results';

$lang->doc->methodOrder[] = 'index';
$lang->doc->methodOrder[] = 'browse';
$lang->doc->methodOrder[] = 'createLib';
$lang->doc->methodOrder[] = 'editLib';
$lang->doc->methodOrder[] = 'deleteLib';
$lang->doc->methodOrder[] = 'create';
$lang->doc->methodOrder[] = 'view';
$lang->doc->methodOrder[] = 'edit';
$lang->doc->methodOrder[] = 'delete';

$lang->svn->methodOrder[] = 'diff';
$lang->svn->methodOrder[] = 'cat';
$lang->svn->methodOrder[] = 'apiSync';

$lang->moduleOrder[80]  = 'company';
$lang->moduleOrder[85]  = 'dept';
$lang->moduleOrder[90]  = 'group';
$lang->moduleOrder[95]  = 'user';
$lang->moduleOrder[100] = 'tree';
$lang->moduleOrder[105] = 'search';
$lang->moduleOrder[110] = 'extension';
$lang->moduleOrder[115] = 'api';
$lang->moduleOrder[120] = 'file';
$lang->moduleOrder[125] = 'misc';
$lang->moduleOrder[130] = 'action';

$lang->company->methodOrder[] = 'index';
$lang->company->methodOrder[] = 'browse';
$lang->company->methodOrder[] = 'edit';
$lang->company->methodOrder[] = 'dynamic';
$lang->company->methodOrder[] = 'dffort';

$lang->dept->methodOrder[] = 'browse';
$lang->dept->methodOrder[] = 'updateOrder';
$lang->dept->methodOrder[] = 'manageChild';
$lang->dept->methodOrder[] = 'delete';

$lang->group->methodOrder[] = 'browse';
$lang->group->methodOrder[] = 'create';
$lang->group->methodOrder[] = 'edit';
$lang->group->methodOrder[] = 'copy';
$lang->group->methodOrder[] = 'delete';
$lang->group->methodOrder[] = 'managePriv';
$lang->group->methodOrder[] = 'manageMember';

$lang->user->methodOrder[] = 'create';
$lang->user->methodOrder[] = 'view';
$lang->user->methodOrder[] = 'edit';
$lang->user->methodOrder[] = 'delete';
$lang->user->methodOrder[] = 'todo';
$lang->user->methodOrder[] = 'task';
$lang->user->methodOrder[] = 'bug';
$lang->user->methodOrder[] = 'project';
$lang->user->methodOrder[] = 'dynamic';
$lang->user->methodOrder[] = 'profile';
$lang->user->methodOrder[] = 'ajaxGetUser';

$lang->tree->methodOrder[] = 'browse';
$lang->tree->methodOrder[] = 'updateOrder';
$lang->tree->methodOrder[] = 'manageChild';
$lang->tree->methodOrder[] = 'edit';
$lang->tree->methodOrder[] = 'delete';
$lang->tree->methodOrder[] = 'ajaxGetOptionMenu';
$lang->tree->methodOrder[] = 'ajaxGetSonModules';

$lang->search->methodOrder[] = 'buildForm';
$lang->search->methodOrder[] = 'buildQuery';
$lang->search->methodOrder[] = 'saveQuery';
$lang->search->methodOrder[] = 'deleteQuery';
$lang->search->methodOrder[] = 'select';

$lang->admin->methodOrder[] = 'index';

$lang->api->methodOrder[] = 'getModel';

$lang->file->methodOrder[] = 'download';
$lang->file->methodOrder[] = 'edit';
$lang->file->methodOrder[] = 'delete';
$lang->file->methodOrder[] = 'ajaxUpload';

$lang->misc->methodOrder[] = 'ping';

$lang->action->methodOrder[] = 'trash';
$lang->action->methodOrder[] = 'undelete';

/* Error info. */
$lang->error->companyNotFound = "The domain %s does not exist.";
$lang->error->length          = array("『%s』length should be『%s』", "『%s』length should between『%s』and 『%s』.");
$lang->error->reg             = "『%s』should like『%s』";
$lang->error->unique          = "『%s』has『%s』already.";
$lang->error->gt              = "『%s』must greater than『%s』.";
$lang->error->ge              = "『%s』must greater than or equal『%s』.";
$lang->error->notempty        = "『%s』can not be empty.";
$lang->error->empty           = "『%s』 must be empty.";
$lang->error->equal           = "『%s』must be『%s』.";
$lang->error->int             = array("『%s』should be interger", "『%s』should between『%s-%s』.");
$lang->error->float           = "『%s』should be a interger or float.";
$lang->error->email           = "『%s』should be email.";
$lang->error->date            = "『%s』should be date";
$lang->error->account         = "『%s』should be a valid account.";
$lang->error->passwordsame    = "Two passwords must be the same";
$lang->error->passwordrule    = "Password should more than six letters.";
$lang->error->accessDenied    = 'No purview';

/* Pager. */
$lang->pager->noRecord  = "No records yet.";
$lang->pager->digest    = "<strong>%s</strong> records, <strong>%s</strong> per page, <strong>%s/%s</strong> ";
$lang->pager->first     = "First";
$lang->pager->pre       = "Previous";
$lang->pager->next      = "Next";
$lang->pager->last      = "Last";
$lang->pager->locate    = "GO!";

$lang->zentaoSite     = "Official Site";
$lang->chinaScrum     = "<a href='http://www.zentao.net/goto.php?item=chinascrum' target='_blank'>Scrum community</a> ";
$lang->agileTraining  = "<a href='http://www.zentao.net/goto.php?item=agiletrain' target='_blank'>Training</a> ";
$lang->donate         = "<a href='http://www.zentao.net/goto.php?item=donate' target='_blank'>Donate</a> ";

$lang->suhosinInfo = "Warming:data is too large! Please enlarge the setting of <font color=red>sohusin.post.max_vars</font> and <font color=red>sohusin.request.max_vars</font> in php.ini. Otherwise partial data can't be saved.";

$lang->noResultsMatch = "No results match";

/* Date times. */
define('DT_DATETIME1',  'Y-m-d H:i:s');
define('DT_DATETIME2',  'y-m-d H:i');
define('DT_MONTHTIME1', 'n/d H:i');
define('DT_MONTHTIME2', 'F j, H:i');
define('DT_DATE1',     'Y-m-d');
define('DT_DATE2',     'Ymd');
define('DT_DATE3',     'F j, Y ');
define('DT_DATE4',     'M j');
define('DT_TIME1',     'H:i:s');
define('DT_TIME2',     'H:i');
