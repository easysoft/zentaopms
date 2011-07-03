<?php
/**
 * The all avaliabe actions in ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id$
 * @link        http://www.zentao.net
 */

/* Index module. */
$lang->resource->index->index = 'index';

/* My module. */
$lang->resource->my->index       = 'index';
$lang->resource->my->todo        = 'todo';
$lang->resource->my->task        = 'task';
$lang->resource->my->bug         = 'bug';
$lang->resource->my->testTask    = 'testTask';
$lang->resource->my->testCase    = 'testCase';
$lang->resource->my->story       = 'story';
$lang->resource->my->project     = 'project';
$lang->resource->my->profile     = 'profile';
$lang->resource->my->dynamic     = 'dynamic';
$lang->resource->my->editProfile = 'editProfile';

/* Todo. */
$lang->resource->todo->create    = 'create';
$lang->resource->todo->edit      = 'edit';
$lang->resource->todo->view      = 'view';
$lang->resource->todo->delete    = 'delete';
$lang->resource->todo->mark      = 'mark';
$lang->resource->todo->import2Today = 'import2Today';

/* Product. */
$lang->resource->product->index  = 'index';
$lang->resource->product->browse = 'browse';
$lang->resource->product->create = 'create';
$lang->resource->product->view   = 'view';
$lang->resource->product->edit   = 'edit';
$lang->resource->product->delete = 'delete';
$lang->resource->product->roadmap= 'roadmap';
$lang->resource->product->doc    = 'doc';
$lang->resource->product->ajaxGetProjects = 'ajaxGetProjects';
$lang->resource->product->ajaxGetPlans    = 'ajaxGetPlans';

/* Story. */
$lang->resource->story->create  = 'create';
$lang->resource->story->edit    = 'edit';
$lang->resource->story->export  = 'export';
$lang->resource->story->delete  = 'delete';
$lang->resource->story->view    = 'view';
$lang->resource->story->change  = 'lblChange';
$lang->resource->story->review  = 'lblReview';
$lang->resource->story->close   = 'lblClose';
$lang->resource->story->activate= 'lblActivate';
$lang->resource->story->tasks   = 'tasks';
$lang->resource->story->report  = 'reportChart';
$lang->resource->story->ajaxGetProjectStories = 'ajaxGetProjectStories';
$lang->resource->story->ajaxGetProductStories = 'ajaxGetProductStories';

/* Product plan. */
$lang->resource->productplan->browse      = 'browse';
$lang->resource->productplan->create      = 'create';
$lang->resource->productplan->edit        = 'edit';
$lang->resource->productplan->delete      = 'delete';
$lang->resource->productplan->view        = 'view';
$lang->resource->productplan->linkStory   = 'linkStory';
$lang->resource->productplan->unlinkStory = 'unlinkStory';

/* Release. */
$lang->resource->release->browse = 'browse';
$lang->resource->release->create = 'create';
$lang->resource->release->edit   = 'edit';
$lang->resource->release->delete = 'delete';
$lang->resource->release->view   = 'view';

/* Project. */
$lang->resource->project->index          = 'index';
$lang->resource->project->view           = 'view';
$lang->resource->project->browse         = 'browse';
$lang->resource->project->create         = 'create';
$lang->resource->project->edit           = 'edit';
$lang->resource->project->delete         = 'delete';
$lang->resource->project->task           = 'task';
$lang->resource->project->grouptask      = 'groupTask';
$lang->resource->project->importtask     = 'importTask';
$lang->resource->project->story          = 'story';
$lang->resource->project->build          = 'build';
$lang->resource->project->bug            = 'bug';
$lang->resource->project->burn           = 'burn';
$lang->resource->project->computeBurn    = 'computeBurn';
$lang->resource->project->burnData       = 'burnData';
$lang->resource->project->team           = 'team';
$lang->resource->project->doc            = 'doc';
$lang->resource->project->manageProducts = 'manageProducts';
//$lang->resource->project->manageChilds   = 'manageChilds';
$lang->resource->project->manageMembers  = 'manageMembers';
$lang->resource->project->unlinkMember   = 'unlinkMember';
$lang->resource->project->linkStory      = 'linkStory';
$lang->resource->project->unlinkStory    = 'unlinkStory';
$lang->resource->project->ajaxGetProducts= 'ajaxGetProducts';

/* Task. */
$lang->resource->task->create              = 'create';
$lang->resource->task->edit                = 'edit';
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

/* Build. */
$lang->resource->build->create               = 'create';
$lang->resource->build->edit                 = 'edit';
$lang->resource->build->delete               = 'delete';
$lang->resource->build->view                 = 'view';
$lang->resource->build->ajaxGetProductBuilds = 'ajaxGetProductBuilds';
$lang->resource->build->ajaxGetProjectBuilds = 'ajaxGetProjectBuilds';

/* QA. */
$lang->resource->qa->index = 'index';

/* Bug. */
$lang->resource->bug->index               = 'index';
$lang->resource->bug->browse              = 'browse';
$lang->resource->bug->create              = 'create';
$lang->resource->bug->view                = 'view';
$lang->resource->bug->edit                = 'edit';
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

/* Test case. */
$lang->resource->testcase->index              = 'index';
$lang->resource->testcase->browse             = 'browse';
$lang->resource->testcase->create             = 'create';
$lang->resource->testcase->view               = 'view';
$lang->resource->testcase->edit               = 'edit';
$lang->resource->testcase->delete             = 'delete';
$lang->resource->testcase->export             = 'export';
$lang->resource->testcase->confirmStoryChange = 'confirmStoryChange';

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

/* Company. */
$lang->resource->company->index  = 'index';
$lang->resource->company->browse = 'browse';
$lang->resource->company->edit   = 'edit';
$lang->resource->company->dynamic= 'dynamic';

/* Department. */
$lang->resource->dept->browse      = 'browse';
$lang->resource->dept->updateOrder = 'updateOrder';
$lang->resource->dept->manageChild = 'manageChild';
$lang->resource->dept->delete      = 'delete';

/* Group. */
$lang->resource->group->browse       = 'browse';
$lang->resource->group->create       = 'create';
$lang->resource->group->edit         = 'edit';
$lang->resource->group->copy         = 'copy';
$lang->resource->group->delete       = 'delete';
$lang->resource->group->managePriv   = 'managePriv';
$lang->resource->group->manageMember = 'manageMember';

/* User. */
$lang->resource->user->create = 'create';
$lang->resource->user->view   = 'view';
$lang->resource->user->edit   = 'edit';
$lang->resource->user->delete = 'delete';
$lang->resource->user->todo   = 'todo';
$lang->resource->user->task   = 'task';
$lang->resource->user->bug    = 'bug';
$lang->resource->user->project= 'project';
$lang->resource->user->dynamic= 'dynamic';
$lang->resource->user->profile= 'profile';

/* Tree. */
$lang->resource->tree->browse            = 'browse';
$lang->resource->tree->updateOrder       = 'updateOrder';
$lang->resource->tree->manageChild       = 'manageChild';
$lang->resource->tree->edit              = 'edit';
$lang->resource->tree->delete            = 'delete';
$lang->resource->tree->ajaxGetOptionMenu = 'ajaxGetOptionMenu';
$lang->resource->tree->ajaxGetSonModules = 'ajaxGetSonModules';

/* Search. */
$lang->resource->search->buildForm    = 'buildForm';
$lang->resource->search->buildQuery   = 'buildQuery';
$lang->resource->search->saveQuery    = 'saveQuery';
$lang->resource->search->deleteQuery  = 'deleteQuery';
$lang->resource->search->select       = 'select';

/* Admin. */
$lang->resource->admin->index         = 'index';

/* Extension. */
$lang->resource->extension->browse     = 'browse';
$lang->resource->extension->obtain     = 'obtain';
$lang->resource->extension->install    = 'install';
$lang->resource->extension->uninstall  = 'uninstall';
$lang->resource->extension->activate   = 'activate';
$lang->resource->extension->deactivate = 'deactivate';
$lang->resource->extension->upload     = 'upload';
$lang->resource->extension->erase      = 'erase';
$lang->resource->extension->upgrade    = 'upgrade';

/* Others. */
$lang->resource->api->getModel     = 'getModel';
$lang->resource->file->download    = 'download';
$lang->resource->file->delete      = 'delete';
$lang->resource->file->ajaxUpload  = 'ajaxUpload';
$lang->resource->misc->ping        = 'ping';
$lang->resource->action->trash     = 'trash';
$lang->resource->action->undelete  = 'undelete';

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
