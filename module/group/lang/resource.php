<?php
/**
 * The all avaliabe actions in ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id$
 * @link        http://www.zentao.net
 */

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
$lang->moduleOrder[75]  = 'report';

$lang->moduleOrder[80]  = 'company';
$lang->moduleOrder[85]  = 'dept';
$lang->moduleOrder[90]  = 'group';
$lang->moduleOrder[95]  = 'user';

$lang->moduleOrder[100] = 'admin';
$lang->moduleOrder[105] = 'extension';
$lang->moduleOrder[105] = 'extension';
$lang->moduleOrder[110] = 'editor';
$lang->moduleOrder[115] = 'convert';
$lang->moduleOrder[120] = 'action';

$lang->moduleOrder[125] = 'mail';
$lang->moduleOrder[130] = 'svn';
$lang->moduleOrder[135] = 'search';
$lang->moduleOrder[140] = 'tree';
$lang->moduleOrder[145] = 'api';
$lang->moduleOrder[150] = 'file';
$lang->moduleOrder[155] = 'misc';

/* Index module. */
$lang->resource->index->index = 'index';

$lang->index->methodOrder[0] = 'index';

/* My module. */
$lang->resource->my->index          = 'index';
$lang->resource->my->todo           = 'todo';
$lang->resource->my->task           = 'task';
$lang->resource->my->bug            = 'bug';
$lang->resource->my->testTask       = 'testTask';
$lang->resource->my->testCase       = 'testCase';
$lang->resource->my->story          = 'story';
$lang->resource->my->project        = 'myProject';
$lang->resource->my->profile        = 'profile';
$lang->resource->my->dynamic        = 'dynamic';
$lang->resource->my->editProfile    = 'editProfile';
$lang->resource->my->changePassword = 'changePassword';

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

/* Todo. */
$lang->resource->todo->create       = 'create';
$lang->resource->todo->batchCreate  = 'batchCreate';
$lang->resource->todo->edit         = 'edit';
$lang->resource->todo->view         = 'view';
$lang->resource->todo->delete       = 'delete';
$lang->resource->todo->export       = 'export';
$lang->resource->todo->mark         = 'mark';
$lang->resource->todo->import2Today = 'import2Today';

$lang->todo->methodOrder[5]  = 'create';
$lang->todo->methodOrder[10] = 'batchCreate';
$lang->todo->methodOrder[15] = 'edit';
$lang->todo->methodOrder[20] = 'view';
$lang->todo->methodOrder[25] = 'delete';
$lang->todo->methodOrder[30] = 'export';
$lang->todo->methodOrder[35] = 'mark';
$lang->todo->methodOrder[40] = 'import2Today';

/* Product. */
$lang->resource->product->index  = 'index';
$lang->resource->product->browse = 'browse';
$lang->resource->product->create = 'create';
$lang->resource->product->view   = 'view';
$lang->resource->product->edit   = 'edit';
$lang->resource->product->order  = 'order';
$lang->resource->product->delete = 'delete';
$lang->resource->product->roadmap= 'roadmap';
$lang->resource->product->doc    = 'doc';
$lang->resource->product->dynamic= 'dynamic';
$lang->resource->product->project= 'project';
$lang->resource->product->ajaxGetProjects = 'ajaxGetProjects';
$lang->resource->product->ajaxGetPlans    = 'ajaxGetPlans';

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

/* Story. */
$lang->resource->story->create      = 'create';
$lang->resource->story->batchCreate = 'batchCreate';
$lang->resource->story->edit        = 'edit';
$lang->resource->story->export      = 'export';
$lang->resource->story->delete      = 'delete';
$lang->resource->story->view        = 'view';
$lang->resource->story->change      = 'lblChange';
$lang->resource->story->review      = 'lblReview';
$lang->resource->story->close       = 'lblClose';
$lang->resource->story->batchClose  = 'batchClose';
$lang->resource->story->activate    = 'lblActivate';
$lang->resource->story->tasks       = 'tasks';
$lang->resource->story->report      = 'reportChart';
$lang->resource->story->ajaxGetProjectStories = 'ajaxGetProjectStories';
$lang->resource->story->ajaxGetProductStories = 'ajaxGetProductStories';

$lang->story->methodOrder[5]  = 'create';
$lang->story->methodOrder[10] = 'batchCreate';
$lang->story->methodOrder[15] = 'edit';
$lang->story->methodOrder[20] = 'export';
$lang->story->methodOrder[25] = 'delete';
$lang->story->methodOrder[30] = 'view';
$lang->story->methodOrder[35] = 'change';
$lang->story->methodOrder[40] = 'review';
$lang->story->methodOrder[45] = 'close';
$lang->story->methodOrder[50] = 'batchClose';
$lang->story->methodOrder[55] = 'activate';
$lang->story->methodOrder[60] = 'tasks';
$lang->story->methodOrder[65] = 'report';
$lang->story->methodOrder[70] = 'ajaxGetProjectStories';
$lang->story->methodOrder[75] = 'ajaxGetProductStories';

/* Product plan. */
$lang->resource->productplan->browse      = 'browse';
$lang->resource->productplan->create      = 'create';
$lang->resource->productplan->edit        = 'edit';
$lang->resource->productplan->delete      = 'delete';
$lang->resource->productplan->view        = 'view';
$lang->resource->productplan->linkStory   = 'linkStory';
$lang->resource->productplan->unlinkStory = 'unlinkStory';

$lang->productplan->methodOrder[5]  = 'browse';
$lang->productplan->methodOrder[10] = 'create';
$lang->productplan->methodOrder[15] = 'edit';
$lang->productplan->methodOrder[20] = 'delete';
$lang->productplan->methodOrder[25] = 'view';
$lang->productplan->methodOrder[30] = 'linkStory';
$lang->productplan->methodOrder[35] = 'unlinkStory';

/* Release. */
$lang->resource->release->browse = 'browse';
$lang->resource->release->create = 'create';
$lang->resource->release->edit   = 'edit';
$lang->resource->release->delete = 'delete';
$lang->resource->release->view   = 'view';
$lang->resource->release->export = 'export';
$lang->resource->release->ajaxGetStoriesAndBugs = 'ajaxGetStoriesAndBugs';

$lang->release->methodOrder[5]  = 'browse';
$lang->release->methodOrder[10] = 'create';
$lang->release->methodOrder[15] = 'edit';
$lang->release->methodOrder[20] = 'delete';
$lang->release->methodOrder[25] = 'view';
$lang->release->methodOrder[30] = 'ajaxGetStoriesAndBugs';
$lang->release->methodOrder[35] = 'export';

/* Project. */
$lang->resource->project->index          = 'index';
$lang->resource->project->view           = 'view';
$lang->resource->project->browse         = 'browse';
$lang->resource->project->create         = 'create';
$lang->resource->project->edit           = 'edit';
$lang->resource->project->order          = 'order';
$lang->resource->project->delete         = 'delete';
$lang->resource->project->task           = 'task';
$lang->resource->project->grouptask      = 'groupTask';
$lang->resource->project->importtask     = 'importTask';
$lang->resource->project->importBug      = 'importBug';
$lang->resource->project->story          = 'story';
$lang->resource->project->build          = 'build';
$lang->resource->project->testtask       = 'testtask';
$lang->resource->project->bug            = 'bug';
$lang->resource->project->burn           = 'burn';
$lang->resource->project->computeBurn    = 'computeBurn';
$lang->resource->project->burnData       = 'burnData';
$lang->resource->project->team           = 'team';
$lang->resource->project->doc            = 'doc';
$lang->resource->project->dynamic        = 'dynamic';
$lang->resource->project->manageProducts = 'manageProducts';
//$lang->resource->project->manageChilds   = 'manageChilds';
$lang->resource->project->manageMembers  = 'manageMembers';
$lang->resource->project->unlinkMember   = 'unlinkMember';
$lang->resource->project->linkStory      = 'linkStory';
$lang->resource->project->unlinkStory    = 'unlinkStory';
$lang->resource->project->ajaxGetProducts= 'ajaxGetProducts';

$lang->project->methodOrder[0]   = 'index';
$lang->project->methodOrder[5]   = 'view';
$lang->project->methodOrder[10]  = 'browse';
$lang->project->methodOrder[15]  = 'create';
$lang->project->methodOrder[20]  = 'edit';
$lang->project->methodOrder[25]  = 'order';
$lang->project->methodOrder[30]  = 'delete';
$lang->project->methodOrder[35]  = 'task';
$lang->project->methodOrder[40]  = 'grouptask';
$lang->project->methodOrder[45]  = 'importtask';
$lang->project->methodOrder[50]  = 'importBug';
$lang->project->methodOrder[55]  = 'story';
$lang->project->methodOrder[60]  = 'build';
$lang->project->methodOrder[65]  = 'testtask';
$lang->project->methodOrder[70]  = 'bug';
$lang->project->methodOrder[75]  = 'burn';
$lang->project->methodOrder[80]  = 'computeBurn';
$lang->project->methodOrder[85]  = 'burnData';
$lang->project->methodOrder[90]  = 'team';
$lang->project->methodOrder[95]  = 'doc';
$lang->project->methodOrder[100] = 'dynamic';
$lang->project->methodOrder[105] = 'manageProducts';
$lang->project->methodOrder[110] = 'manageMembers';
$lang->project->methodOrder[115] = 'unlinkMember';
$lang->project->methodOrder[120] = 'linkStory';
$lang->project->methodOrder[125] = 'unlinkStory';
$lang->project->methodOrder[130] = 'ajaxGetProducts';

/* Task. */
$lang->resource->task->create              = 'create';
$lang->resource->task->batchCreate         = 'batchCreate';
$lang->resource->task->batchEdit           = 'batchEdit';
$lang->resource->task->edit                = 'edit';
$lang->resource->task->assignTo            = 'assign';
$lang->resource->task->start               = 'start';
$lang->resource->task->finish              = 'finish';
$lang->resource->task->cancel              = 'cancel';
$lang->resource->task->close               = 'close';
$lang->resource->task->batchClose          = 'batchClose';
$lang->resource->task->activate            = 'activate';
$lang->resource->task->delete              = 'delete';
$lang->resource->task->view                = 'view';
$lang->resource->task->export              = 'export';
$lang->resource->task->confirmStoryChange  = 'confirmStoryChange';
$lang->resource->task->ajaxGetUserTasks    = 'ajaxGetUserTasks';
$lang->resource->task->ajaxGetProjectTasks = 'ajaxGetProjectTasks';
$lang->resource->task->report              = 'reportChart';

$lang->task->methodOrder[5]  = 'create';
$lang->task->methodOrder[10] = 'batchCreate';
$lang->task->methodOrder[15] = 'batchEdit';
$lang->task->methodOrder[20] = 'edit';
$lang->task->methodOrder[25] = 'assignTo';
$lang->task->methodOrder[30] = 'start';
$lang->task->methodOrder[35] = 'finish';
$lang->task->methodOrder[40] = 'cancel';
$lang->task->methodOrder[45] = 'close';
$lang->task->methodOrder[50] = 'batchClose';
$lang->task->methodOrder[55] = 'activate';
$lang->task->methodOrder[60] = 'delete';
$lang->task->methodOrder[65] = 'view';
$lang->task->methodOrder[70] = 'export';
$lang->task->methodOrder[75] = 'confirmStoryChange';
$lang->task->methodOrder[80] = 'ajaxGetUserTasks';
$lang->task->methodOrder[85] = 'ajaxGetProjectTasks';
$lang->task->methodOrder[90] = 'report';

/* Build. */
$lang->resource->build->create               = 'create';
$lang->resource->build->edit                 = 'edit';
$lang->resource->build->delete               = 'delete';
$lang->resource->build->view                 = 'view';
$lang->resource->build->ajaxGetProductBuilds = 'ajaxGetProductBuilds';
$lang->resource->build->ajaxGetProjectBuilds = 'ajaxGetProjectBuilds';

$lang->build->methodOrder[5]  = 'create';
$lang->build->methodOrder[10] = 'edit';
$lang->build->methodOrder[15] = 'delete';
$lang->build->methodOrder[20] = 'view';
$lang->build->methodOrder[25] = 'ajaxGetProductBuilds';
$lang->build->methodOrder[30] = 'ajaxGetProjectBuilds';

/* QA. */
$lang->resource->qa->index = 'index';

$lang->qa->methodOrder[0] = 'index';

/* Bug. */
$lang->resource->bug->index               = 'index';
$lang->resource->bug->browse              = 'browse';
$lang->resource->bug->create              = 'create';
$lang->resource->bug->confirmBug          = 'confirmBug';
$lang->resource->bug->view                = 'view';
$lang->resource->bug->edit                = 'edit';
$lang->resource->bug->assignTo            = 'assignTo';
$lang->resource->bug->resolve             = 'resolve';
$lang->resource->bug->activate            = 'activate';
$lang->resource->bug->close               = 'close';
$lang->resource->bug->report              = 'reportChart';
$lang->resource->bug->export              = 'export';
$lang->resource->bug->confirmStoryChange  = 'confirmStoryChange';
$lang->resource->bug->delete              = 'delete';
$lang->resource->bug->saveTemplate        = 'saveTemplate';
$lang->resource->bug->deleteTemplate      = 'deleteTemplate';
$lang->resource->bug->customFields        = 'customFields';
$lang->resource->bug->ajaxGetUserBugs     = 'ajaxGetUserBugs';
$lang->resource->bug->ajaxGetModuleOwner  = 'ajaxGetModuleOwner';

$lang->bug->methodOrder[0]  = 'index';
$lang->bug->methodOrder[5]  = 'browse';
$lang->bug->methodOrder[10] = 'create';
$lang->bug->methodOrder[15] = 'confirmBug';
$lang->bug->methodOrder[20] = 'view';
$lang->bug->methodOrder[25] = 'edit';
$lang->bug->methodOrder[30] = 'assignTo';
$lang->bug->methodOrder[35] = 'resolve';
$lang->bug->methodOrder[40] = 'activate';
$lang->bug->methodOrder[45] = 'close';
$lang->bug->methodOrder[50] = 'report';
$lang->bug->methodOrder[55] = 'export';
$lang->bug->methodOrder[60] = 'confirmStoryChange';
$lang->bug->methodOrder[65] = 'delete';
$lang->bug->methodOrder[70] = 'saveTemplate';
$lang->bug->methodOrder[75] = 'deleteTemplate';
$lang->bug->methodOrder[80] = 'customFields';
$lang->bug->methodOrder[85] = 'ajaxGetUserBugs';
$lang->bug->methodOrder[90] = 'ajaxGetModuleOwner';

/* Test case. */
$lang->resource->testcase->index              = 'index';
$lang->resource->testcase->browse             = 'browse';
$lang->resource->testcase->create             = 'create';
$lang->resource->testcase->batchCreate        = 'batchCreate';
$lang->resource->testcase->view               = 'view';
$lang->resource->testcase->edit               = 'edit';
$lang->resource->testcase->delete             = 'delete';
$lang->resource->testcase->export             = 'export';
$lang->resource->testcase->confirmStoryChange = 'confirmStoryChange';

$lang->testcase->methodOrder[0] = 'index';
$lang->testcase->methodOrder[5] = 'browse';
$lang->testcase->methodOrder[10] = 'create';
$lang->testcase->methodOrder[15] = 'batchCreate';
$lang->testcase->methodOrder[20] = 'view';
$lang->testcase->methodOrder[25] = 'edit';
$lang->testcase->methodOrder[30] = 'delete';
$lang->testcase->methodOrder[35] = 'export';
$lang->testcase->methodOrder[40] = 'confirmStoryChange';

/* Test task. */
$lang->resource->testtask->index       = 'index';
$lang->resource->testtask->create      = 'create';
$lang->resource->testtask->browse      = 'browse';
$lang->resource->testtask->view        = 'view';
$lang->resource->testtask->cases       = 'lblCases';
$lang->resource->testtask->edit        = 'edit';
$lang->resource->testtask->delete      = 'delete';
$lang->resource->testtask->batchAssign = 'batchAssign';
$lang->resource->testtask->linkcase    = 'linkCase';
$lang->resource->testtask->unlinkcase  = 'lblUnlinkCase';
$lang->resource->testtask->runcase     = 'lblRunCase';
$lang->resource->testtask->results     = 'lblResults';

$lang->testtask->methodOrder[0]  = 'index';
$lang->testtask->methodOrder[5]  = 'create';
$lang->testtask->methodOrder[10] = 'browse';
$lang->testtask->methodOrder[15] = 'view';
$lang->testtask->methodOrder[20] = 'cases';
$lang->testtask->methodOrder[25] = 'edit';
$lang->testtask->methodOrder[30] = 'delete';
$lang->testtask->methodOrder[35] = 'batchAssign';
$lang->testtask->methodOrder[40] = 'linkcase';
$lang->testtask->methodOrder[45] = 'unlinkcase';
$lang->testtask->methodOrder[50] = 'runcase';
$lang->testtask->methodOrder[55] = 'results';

/* Doc. */
$lang->resource->doc->index     = 'index';
$lang->resource->doc->browse    = 'browse';
$lang->resource->doc->createLib = 'createLib';
$lang->resource->doc->editLib   = 'editLib';
$lang->resource->doc->deleteLib = 'deleteLib';
$lang->resource->doc->create    = 'create';
$lang->resource->doc->view      = 'view';
$lang->resource->doc->edit      = 'edit';
$lang->resource->doc->delete    = 'delete';

$lang->doc->methodOrder[0]  = 'index';
$lang->doc->methodOrder[5]  = 'browse';
$lang->doc->methodOrder[10] = 'createLib';
$lang->doc->methodOrder[15] = 'editLib';
$lang->doc->methodOrder[20] = 'deleteLib';
$lang->doc->methodOrder[25] = 'create';
$lang->doc->methodOrder[30] = 'view';
$lang->doc->methodOrder[35] = 'edit';
$lang->doc->methodOrder[40] = 'delete';

/* mail. */
$lang->resource->mail->index  = 'index';
$lang->resource->mail->detect = 'detect';
$lang->resource->mail->edit   = 'edit';
$lang->resource->mail->save   = 'save';
$lang->resource->mail->test   = 'test';

$lang->mail->methodOrder[5]  = 'index';
$lang->mail->methodOrder[10] = 'detect';
$lang->mail->methodOrder[15] = 'edit';
$lang->mail->methodOrder[20] = 'save';
$lang->mail->methodOrder[25] = 'test';

/* Subversion. */
$lang->resource->svn->diff    = 'diff';
$lang->resource->svn->cat     = 'cat';
$lang->resource->svn->apiSync = 'apiSync';

$lang->svn->methodOrder[5]  = 'diff';
$lang->svn->methodOrder[10] = 'cat';
$lang->svn->methodOrder[15] = 'apiSync';

/* Company. */
$lang->resource->company->index  = 'index';
$lang->resource->company->browse = 'browse';
$lang->resource->company->create = 'create';
$lang->resource->company->edit   = 'edit';
$lang->resource->company->delete = 'delete';
$lang->resource->company->dynamic= 'dynamic';

$lang->company->methodOrder[0]  = 'index';
$lang->company->methodOrder[5]  = 'browse';
$lang->company->methodOrder[10] = 'create';
$lang->company->methodOrder[15] = 'edit';
$lang->company->methodOrder[20] = 'delete';
$lang->company->methodOrder[25] = 'dynamic';

/* Department. */
$lang->resource->dept->browse      = 'browse';
$lang->resource->dept->updateOrder = 'updateOrder';
$lang->resource->dept->manageChild = 'manageChild';
$lang->resource->dept->delete      = 'delete';

$lang->dept->methodOrder[5]  = 'browse';
$lang->dept->methodOrder[10] = 'updateOrder';
$lang->dept->methodOrder[15] = 'manageChild';
$lang->dept->methodOrder[20] = 'delete';

/* Group. */
$lang->resource->group->browse       = 'browse';
$lang->resource->group->create       = 'create';
$lang->resource->group->edit         = 'edit';
$lang->resource->group->copy         = 'copy';
$lang->resource->group->delete       = 'delete';
$lang->resource->group->managePriv   = 'managePriv';
$lang->resource->group->manageMember = 'manageMember';

$lang->group->methodOrder[5]  = 'browse';
$lang->group->methodOrder[10] = 'create';
$lang->group->methodOrder[15] = 'edit';
$lang->group->methodOrder[20] = 'copy';
$lang->group->methodOrder[25] = 'delete';
$lang->group->methodOrder[30] = 'managePriv';
$lang->group->methodOrder[35] = 'manageMember';

/* User. */
$lang->resource->user->create      = 'create';
$lang->resource->user->batchCreate = 'batchCreate';
$lang->resource->user->view        = 'view';
$lang->resource->user->edit        = 'edit';
$lang->resource->user->unlock      = 'unlock';
$lang->resource->user->delete      = 'delete';
$lang->resource->user->todo        = 'todo';
$lang->resource->user->task        = 'task';
$lang->resource->user->bug         = 'bug';
$lang->resource->user->project     = 'project';
$lang->resource->user->dynamic     = 'dynamic';
$lang->resource->user->profile     = 'profile';
$lang->resource->user->ajaxGetUser = 'ajaxGetUser';

$lang->user->methodOrder[5]  = 'create';
$lang->user->methodOrder[10] = 'view';
$lang->user->methodOrder[15] = 'edit';
$lang->user->methodOrder[20] = 'unlock';
$lang->user->methodOrder[25] = 'delete';
$lang->user->methodOrder[30] = 'todo';
$lang->user->methodOrder[35] = 'task';
$lang->user->methodOrder[40] = 'bug';
$lang->user->methodOrder[45] = 'project';
$lang->user->methodOrder[50] = 'dynamic';
$lang->user->methodOrder[55] = 'profile';
$lang->user->methodOrder[60] = 'ajaxGetUser';

/* Tree. */
$lang->resource->tree->browse            = 'browse';
$lang->resource->tree->updateOrder       = 'updateOrder';
$lang->resource->tree->manageChild       = 'manageChild';
$lang->resource->tree->edit              = 'edit';
$lang->resource->tree->fix               = 'fix';
$lang->resource->tree->delete            = 'delete';
$lang->resource->tree->ajaxGetOptionMenu = 'ajaxGetOptionMenu';
$lang->resource->tree->ajaxGetSonModules = 'ajaxGetSonModules';

$lang->tree->methodOrder[5]  = 'browse';
$lang->tree->methodOrder[10] = 'updateOrder';
$lang->tree->methodOrder[15] = 'manageChild';
$lang->tree->methodOrder[20] = 'edit';
$lang->tree->methodOrder[25] = 'delete';
$lang->tree->methodOrder[30] = 'ajaxGetOptionMenu';
$lang->tree->methodOrder[35] = 'ajaxGetSonModules';

/* Report. */
$lang->resource->report->index            = 'index';
$lang->resource->report->projectDeviation = 'projectDeviation';
$lang->resource->report->productInfo      = 'productInfo';
$lang->resource->report->bugSummary       = 'bugSummary';
$lang->resource->report->bugAssign        = 'bugAssign';
$lang->resource->report->workload         = 'workload';

$lang->report->methodOrder[0]  = 'index';
$lang->report->methodOrder[5]  = 'projectDeviation';
$lang->report->methodOrder[10] = 'productInfo';
$lang->report->methodOrder[15] = 'bugSummary';
$lang->report->methodOrder[17] = 'bugSummary';
$lang->report->methodOrder[20] = 'workload';

/* Search. */
$lang->resource->search->buildForm    = 'buildForm';
$lang->resource->search->buildQuery   = 'buildQuery';
$lang->resource->search->saveQuery    = 'saveQuery';
$lang->resource->search->deleteQuery  = 'deleteQuery';
$lang->resource->search->select       = 'select';

$lang->search->methodOrder[5]  = 'buildForm';
$lang->search->methodOrder[10] = 'buildQuery';
$lang->search->methodOrder[15] = 'saveQuery';
$lang->search->methodOrder[20] = 'deleteQuery';
$lang->search->methodOrder[25] = 'select';

/* Admin. */
$lang->resource->admin->index     = 'index';
$lang->resource->admin->checkDB   = 'checkDB';
$lang->resource->admin->clearData = 'clearData';

$lang->admin->methodOrder[0]  = 'index';
$lang->admin->methodOrder[5]  = 'checkDB';
$lang->admin->methodOrder[10] = 'clearData';

/* Extension. */
$lang->resource->extension->browse     = 'browse';
$lang->resource->extension->obtain     = 'obtain';
$lang->resource->extension->structure  = 'structure';
$lang->resource->extension->install    = 'install';
$lang->resource->extension->uninstall  = 'uninstall';
$lang->resource->extension->activate   = 'activate';
$lang->resource->extension->deactivate = 'deactivate';
$lang->resource->extension->upload     = 'upload';
$lang->resource->extension->erase      = 'erase';
$lang->resource->extension->upgrade    = 'upgrade';

$lang->extension->methodOrder[5]  = 'browse';
$lang->extension->methodOrder[10] = 'obtain';
$lang->extension->methodOrder[15] = 'structure';
$lang->extension->methodOrder[20] = 'install';
$lang->extension->methodOrder[25] = 'uninstall';
$lang->extension->methodOrder[30] = 'activate';
$lang->extension->methodOrder[35] = 'deactivate';
$lang->extension->methodOrder[40] = 'upload';
$lang->extension->methodOrder[45] = 'erase';
$lang->extension->methodOrder[50] = 'upgrade';

/* Editor . */
$lang->resource->editor->index   = 'index';
$lang->resource->editor->extend  = 'extend';
$lang->resource->editor->edit    = 'edit';
$lang->resource->editor->newPage = 'newPage';
$lang->resource->editor->save    = 'save';
$lang->resource->editor->delete  = 'delete';

$lang->editor->methodOrder[5]  = 'index';
$lang->editor->methodOrder[10] = 'extend';
$lang->editor->methodOrder[15] = 'edit';
$lang->editor->methodOrder[20] = 'newPage';
$lang->editor->methodOrder[25] = 'save';
$lang->editor->methodOrder[30] = 'delete';

/* Convert. */
$lang->resource->convert->index          = 'index';
$lang->resource->convert->selectSource   = 'selectSource';  
$lang->resource->convert->setConfig      = 'setConfig';
$lang->resource->convert->setBugfree     = 'setBugfree';
$lang->resource->convert->setRedmine     = 'setRedmine';
$lang->resource->convert->checkConfig    = 'checkConfig';
$lang->resource->convert->checkBugFree   = 'checkBugFree';
$lang->resource->convert->checkRedmine   = 'checkRedmine';
$lang->resource->convert->execute        = 'execute';
$lang->resource->convert->convertBugFree = 'convertBugFree';
$lang->resource->convert->convertRedmine = 'convertRedmine';

$lang->convert->methodOrder[5]  = 'index';
$lang->convert->methodOrder[10] = 'selectSource';
$lang->convert->methodOrder[15] = 'setConfig';
$lang->convert->methodOrder[20] = 'setBugfree';
$lang->convert->methodOrder[25] = 'setRedmine';
$lang->convert->methodOrder[30] = 'checkConfig';
$lang->convert->methodOrder[35] = 'checkBugFree';
$lang->convert->methodOrder[40] = 'checkRedmine';
$lang->convert->methodOrder[45] = 'execute';
$lang->convert->methodOrder[50] = 'convertBugFree';
$lang->convert->methodOrder[55] = 'convertRedmine';

//$lang->resource->webapp->index     = 'index';
//$lang->resource->webapp->obtain    = 'obtain';
//$lang->resource->webapp->create    = 'create';
//$lang->resource->webapp->edit      = 'edit';
//$lang->resource->webapp->install   = 'install';
//$lang->resource->webapp->uninstall = 'uninstall';

//$lang->webapp->methodOrder[5] = 'index';
//$lang->webapp->methodOrder[10] = 'obtain';
//$lang->webapp->methodOrder[15] = 'create';
//$lang->webapp->methodOrder[20] = 'edit';
//$lang->webapp->methodOrder[25] = 'install';
//$lang->webapp->methodOrder[30] = 'uninstall';

/* Others. */
$lang->resource->api->getModel    = 'getModel';

$lang->api->methodOrder[5] = 'getModel';

$lang->resource->file->download   = 'download';
$lang->resource->file->edit       = 'edit';
$lang->resource->file->delete     = 'delete';
$lang->resource->file->ajaxUpload = 'ajaxUpload';

$lang->file->methodOrder[5]  = 'download';
$lang->file->methodOrder[10] = 'edit';
$lang->file->methodOrder[15] = 'delete';
$lang->file->methodOrder[20] = 'ajaxUpload';

$lang->resource->misc->ping       = 'ping';

$lang->misc->methodOrder[5] = 'ping';

$lang->resource->action->trash    = 'trash';
$lang->resource->action->undelete = 'undelete';
$lang->resource->action->hide     = 'hide';

$lang->action->methodOrder[5]  = 'trash';
$lang->action->methodOrder[10] = 'undelete';
$lang->action->methodOrder[15] = 'hide';

/* Every version of new privilege. */
$lang->changelog['1.0.1'][] = 'project-computeBurn';

$lang->changelog['1.1'][]   = 'search-saveQuery';
$lang->changelog['1.1'][]   = 'search-deleteQuery';

$lang->changelog['1.2'][]   = 'product-doc';
$lang->changelog['1.2'][]   = 'project-doc';
$lang->changelog['1.2'][]   = 'project-ajaxGetProducts';
$lang->changelog['1.2'][]   = 'bug-saveTemplate';
$lang->changelog['1.2'][]   = 'bug-deleteTemplate';
$lang->changelog['1.2'][]   = 'bug-customFields';
$lang->changelog['1.2'][]   = 'bug-ajaxGetModuleOwner';
$lang->changelog['1.2'][]   = 'doc-index';
$lang->changelog['1.2'][]   = 'doc-browse';
$lang->changelog['1.2'][]   = 'doc-createLib';
$lang->changelog['1.2'][]   = 'doc-editLib';
$lang->changelog['1.2'][]   = 'doc-deleteLib';
$lang->changelog['1.2'][]   = 'doc-create';
$lang->changelog['1.2'][]   = 'doc-view';
$lang->changelog['1.2'][]   = 'doc-edit';
$lang->changelog['1.2'][]   = 'doc-delete';
$lang->changelog['1.2'][]   = 'doc-deleteFile';

$lang->changelog['1.3'][]   = 'task-start';
$lang->changelog['1.3'][]   = 'task-complete';
$lang->changelog['1.3'][]   = 'task-cancel';
$lang->changelog['1.3'][]   = 'tree-ajaxGetSonModules';
$lang->changelog['1.3'][]   = 'file-delete';
$lang->changelog['1.3'][]   = 'file-ajaxUpload';

$lang->changelog['1.4'][]   = 'my-testTask';
$lang->changelog['1.4'][]   = 'my-testCase';
$lang->changelog['1.4'][]   = 'task-finish';
$lang->changelog['1.4'][]   = 'task-close';
$lang->changelog['1.4'][]   = 'task-activate';
$lang->changelog['1.4'][]   = 'search-select';

$lang->changelog['1.5'][]   = 'task-batchClose';

$lang->changelog['2.0'][]   = 'my-dynamic';
$lang->changelog['2.0'][]   = 'bug-export';
$lang->changelog['2.0'][]   = 'story-export';
$lang->changelog['2.0'][]   = 'story-reportChart';
$lang->changelog['2.0'][]   = 'task-export';
$lang->changelog['2.0'][]   = 'task-reportChart';
$lang->changelog['2.0'][]   = 'taskcase-export';
$lang->changelog['2.0'][]   = 'company-dynamic';
$lang->changelog['2.0'][]   = 'user-dynamic';
$lang->changelog['2.0'][]   = 'extension-browse';
$lang->changelog['2.0'][]   = 'extension-obtain';
$lang->changelog['2.0'][]   = 'extension-install';
$lang->changelog['2.0'][]   = 'extension-uninstall';
$lang->changelog['2.0'][]   = 'extension-activate';
$lang->changelog['2.0'][]   = 'extension-deactivate';
$lang->changelog['2.0'][]   = 'extension-upload';
$lang->changelog['2.0'][]   = 'extension-erase';

$lang->changelog['2.1'][]   = 'extension-upgrade';

$lang->changelog['2.2'][]   = 'file-edit';

$lang->changelog['2.3'][]   = 'product-dynamic';
$lang->changelog['2.3'][]   = 'project-dynamic';
$lang->changelog['2.3'][]   = 'project-importBug';
$lang->changelog['2.3'][]   = 'story-batchCreate';
$lang->changelog['2.3'][]   = 'task-batchCreate';
$lang->changelog['2.3'][]   = 'testcase-batchCreate';
$lang->changelog['2.3'][]   = 'bug-confirmBug';
$lang->changelog['2.3'][]   = 'svn-diff';
$lang->changelog['2.3'][]   = 'svn-cat';
$lang->changelog['2.3'][]   = 'svn-apiSync';

$lang->changelog['2.4'][]   = 'user-ajaxGetUser';
$lang->changelog['2.4'][]   = 'task-assign';
$lang->changelog['2.4'][]   = 'project-testtask';
$lang->changelog['2.4'][]   = 'todo-export';
$lang->changelog['2.4'][]   = 'product-project';

$lang->changelog['3.0.beta1'][] = 'release-ajaxGetStoriesAndBugs';

$lang->changelog['3.0.beta2'][] = 'extension-structure';
$lang->changelog['3.0.beta2'][] = 'product-order';
$lang->changelog['3.0.beta2'][] = 'project-order';

$lang->changelog['3.1'][] = 'todo-batchCreate';

$lang->changelog['3.2'][] = 'my-changePassword';
$lang->changelog['3.2'][] = 'story-batchClose';
$lang->changelog['3.2'][] = 'task-batchEdit';
$lang->changelog['3.2'][] = 'release-export';
$lang->changelog['3.2'][] = 'report-index';
$lang->changelog['3.2'][] = 'report-projectDeviation';
$lang->changelog['3.2'][] = 'report-productInfo';
$lang->changelog['3.2'][] = 'report-bugSummary';
$lang->changelog['3.2'][] = 'report-workload';
$lang->changelog['3.2'][] = 'tree-fix';

$lang->changelog['3.3'][] = 'report-bugAssign';
