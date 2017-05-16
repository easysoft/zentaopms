<?php
/**
 * The all avaliabe actions in ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
$lang->moduleOrder[70]  = 'testsuite';
$lang->moduleOrder[75]  = 'testreport';
$lang->moduleOrder[80]  = 'caselib';

$lang->moduleOrder[85]  = 'doc';
$lang->moduleOrder[90]  = 'report';

$lang->moduleOrder[95]  = 'company';
$lang->moduleOrder[100] = 'dept';
$lang->moduleOrder[105] = 'group';
$lang->moduleOrder[110] = 'user';

$lang->moduleOrder[115] = 'admin';
$lang->moduleOrder[120] = 'extension';
$lang->moduleOrder[125] = 'custom';
$lang->moduleOrder[130] = 'editor';
$lang->moduleOrder[135] = 'convert';
$lang->moduleOrder[140] = 'action';

$lang->moduleOrder[145] = 'mail';
$lang->moduleOrder[150] = 'svn';
$lang->moduleOrder[155] = 'git';
$lang->moduleOrder[160] = 'search';
$lang->moduleOrder[165] = 'tree';
$lang->moduleOrder[170] = 'api';
$lang->moduleOrder[175] = 'file';
$lang->moduleOrder[180] = 'misc';
$lang->moduleOrder[185] = 'backup';
$lang->moduleOrder[190] = 'cron';
$lang->moduleOrder[195] = 'dev';

$lang->resource = new stdclass();

/* Index module. */
$lang->resource->index = new stdclass();
$lang->resource->index->index = 'index';

$lang->index->methodOrder[0] = 'index';

/* My module. */
$lang->resource->my = new stdclass();
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
$lang->resource->my->unbind         = 'unbind';
$lang->resource->my->manageContacts = 'manageContacts';
$lang->resource->my->deleteContacts = 'deleteContacts';

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
$lang->my->methodOrder[60] = 'unbind';
$lang->my->methodOrder[65] = 'manageContacts';
$lang->my->methodOrder[75] = 'deleteContacts';

/* Todo. */
$lang->resource->todo = new stdclass();
$lang->resource->todo->create       = 'create';
$lang->resource->todo->batchCreate  = 'batchCreate';
$lang->resource->todo->edit         = 'edit';
$lang->resource->todo->batchEdit    = 'batchEdit';
$lang->resource->todo->view         = 'view';
$lang->resource->todo->delete       = 'delete';
$lang->resource->todo->export       = 'export';
$lang->resource->todo->finish       = 'finish';
$lang->resource->todo->batchFinish  = 'batchFinish';
$lang->resource->todo->import2Today = 'import2Today';

$lang->todo->methodOrder[5]  = 'create';
$lang->todo->methodOrder[10] = 'batchCreate';
$lang->todo->methodOrder[15] = 'edit';
$lang->todo->methodOrder[20] = 'view';
$lang->todo->methodOrder[25] = 'delete';
$lang->todo->methodOrder[30] = 'export';
$lang->todo->methodOrder[35] = 'finish';
$lang->todo->methodOrder[40] = 'import2Today';

/* Product. */
$lang->resource->product = new stdclass();
$lang->resource->product->index       = 'index';
$lang->resource->product->browse      = 'browse';
$lang->resource->product->create      = 'create';
$lang->resource->product->view        = 'view';
$lang->resource->product->edit        = 'edit';
$lang->resource->product->batchEdit   = 'batchEdit';
$lang->resource->product->delete      = 'delete';
$lang->resource->product->roadmap     = 'roadmap';
$lang->resource->product->doc         = 'doc';
$lang->resource->product->dynamic     = 'dynamic';
$lang->resource->product->project     = 'project';
$lang->resource->product->close       = 'close';
$lang->resource->product->updateOrder = 'updateOrder';
$lang->resource->product->all         = 'all';
$lang->resource->product->build       = 'build';

$lang->product->methodOrder[0]  = 'index';
$lang->product->methodOrder[5]  = 'browse';
$lang->product->methodOrder[10] = 'create';
$lang->product->methodOrder[15] = 'view';
$lang->product->methodOrder[20] = 'edit';
$lang->product->methodOrder[25] = 'batchEdit';
$lang->product->methodOrder[35] = 'delete';
$lang->product->methodOrder[40] = 'roadmap';
//$lang->product->methodOrder[45] = 'doc';
$lang->product->methodOrder[50] = 'dynamic';
$lang->product->methodOrder[55] = 'project';
$lang->product->methodOrder[60] = 'close';
$lang->product->methodOrder[65] = 'updateOrder';
$lang->product->methodOrder[70] = 'all';
$lang->product->methodOrder[75] = 'build';

/* Branch. */
$lang->resource->branch = new stdclass();
$lang->resource->branch->manage = 'manage';
$lang->resource->branch->sort   = 'sort';
$lang->resource->branch->delete = 'delete';

$lang->branch->methodOrder[0]  = 'manage';
$lang->branch->methodOrder[5]  = 'sort';
$lang->branch->methodOrder[10] = 'delete';

/* Story. */
$lang->resource->story = new stdclass();
$lang->resource->story->create      = 'create';
$lang->resource->story->batchCreate = 'batchCreate';
$lang->resource->story->edit        = 'edit';
$lang->resource->story->linkStory   = 'linkStory';
$lang->resource->story->unlinkStory = 'unlinkStory';
$lang->resource->story->batchEdit   = 'batchEdit';
$lang->resource->story->export      = 'export';
$lang->resource->story->delete      = 'delete';
$lang->resource->story->view        = 'view';
$lang->resource->story->change      = 'lblChange';
$lang->resource->story->review      = 'lblReview';
$lang->resource->story->batchReview = 'batchReview';
$lang->resource->story->close       = 'lblClose';
$lang->resource->story->batchClose  = 'batchClose';
$lang->resource->story->activate    = 'lblActivate';
$lang->resource->story->tasks       = 'tasks';
$lang->resource->story->bugs        = 'bugs';
$lang->resource->story->cases       = 'cases';
$lang->resource->story->zeroCase    = 'zeroCase';
$lang->resource->story->report      = 'reportChart';
$lang->resource->story->batchChangePlan   = 'batchChangePlan';
$lang->resource->story->batchChangeBranch = 'batchChangeBranch';
$lang->resource->story->batchChangeStage  = 'batchChangeStage';
$lang->resource->story->batchAssignTo     = 'batchAssignTo';
$lang->resource->story->batchChangeModule = 'batchChangeModule';

$lang->story->methodOrder[5]   = 'create';
$lang->story->methodOrder[10]  = 'batchCreate';
$lang->story->methodOrder[15]  = 'edit';
$lang->story->methodOrder[20]  = 'export';
$lang->story->methodOrder[25]  = 'delete';
$lang->story->methodOrder[30]  = 'view';
$lang->story->methodOrder[35]  = 'change';
$lang->story->methodOrder[40]  = 'review';
$lang->story->methodOrder[45]  = 'batchReview';
$lang->story->methodOrder[50]  = 'close';
$lang->story->methodOrder[55]  = 'batchClose';
$lang->story->methodOrder[60]  = 'batchChangePlan';
$lang->story->methodOrder[65]  = 'batchChangeStage';
$lang->story->methodOrder[70]  = 'batchAssignTo';
$lang->story->methodOrder[75]  = 'activate';
$lang->story->methodOrder[80]  = 'tasks';
$lang->story->methodOrder[85]  = 'bugs';
$lang->story->methodOrder[90]  = 'cases';
$lang->story->methodOrder[95]  = 'zeroCase';
$lang->story->methodOrder[100] = 'report';
$lang->story->methodOrder[105] = 'linkStory';
$lang->story->methodOrder[110] = 'unlinkStory';
$lang->story->methodOrder[115] = 'batchChangeBranch';
$lang->story->methodOrder[120] = 'batchChangeModule';

/* Product plan. */
$lang->resource->productplan = new stdclass();
$lang->resource->productplan->browse           = 'browse';
$lang->resource->productplan->create           = 'create';
$lang->resource->productplan->edit             = 'edit';
$lang->resource->productplan->delete           = 'delete';
$lang->resource->productplan->view             = 'view';
$lang->resource->productplan->linkStory        = 'linkStory';
$lang->resource->productplan->unlinkStory      = 'unlinkStory';
$lang->resource->productplan->batchUnlinkStory = 'batchUnlinkStory';
$lang->resource->productplan->linkBug          = 'linkBug';
$lang->resource->productplan->unlinkBug        = 'unlinkBug';
$lang->resource->productplan->batchUnlinkBug   = 'batchUnlinkBug';
$lang->resource->productplan->batchEdit        = 'batchEdit';

$lang->productplan->methodOrder[5]  = 'browse';
$lang->productplan->methodOrder[10] = 'create';
$lang->productplan->methodOrder[15] = 'edit';
$lang->productplan->methodOrder[20] = 'delete';
$lang->productplan->methodOrder[25] = 'view';
$lang->productplan->methodOrder[30] = 'linkStory';
$lang->productplan->methodOrder[35] = 'unlinkStory';
$lang->productplan->methodOrder[40] = 'batchUnlinkStory';
$lang->productplan->methodOrder[45] = 'linkBug';
$lang->productplan->methodOrder[50] = 'unlinkBug';
$lang->productplan->methodOrder[55] = 'batchUnlinkBug';
$lang->productplan->methodOrder[60] = 'batchEdit';

/* Release. */
$lang->resource->release = new stdclass();
$lang->resource->release->browse           = 'browse';
$lang->resource->release->create           = 'create';
$lang->resource->release->edit             = 'edit';
$lang->resource->release->delete           = 'delete';
$lang->resource->release->view             = 'view';
$lang->resource->release->export           = 'export';
$lang->resource->release->linkStory        = 'linkStory';
$lang->resource->release->unlinkStory      = 'unlinkStory';
$lang->resource->release->batchUnlinkStory = 'batchUnlinkStory';
$lang->resource->release->linkBug          = 'linkBug';
$lang->resource->release->unlinkBug        = 'unlinkBug';
$lang->resource->release->batchUnlinkBug   = 'batchUnlinkBug';
$lang->resource->release->changeStatus     = 'changeStatus';

$lang->release->methodOrder[5]  = 'browse';
$lang->release->methodOrder[10] = 'create';
$lang->release->methodOrder[15] = 'edit';
$lang->release->methodOrder[20] = 'delete';
$lang->release->methodOrder[25] = 'view';
$lang->release->methodOrder[35] = 'export';
$lang->release->methodOrder[40] = 'linkStory';
$lang->release->methodOrder[45] = 'unlinkStory';
$lang->release->methodOrder[50] = 'batchUnlinkStory';
$lang->release->methodOrder[55] = 'linkBug';
$lang->release->methodOrder[60] = 'unlinkBug';
$lang->release->methodOrder[65] = 'batchUnlinkBug';
$lang->release->methodOrder[70] = 'changeStatus';

/* Project. */
$lang->resource->project = new stdclass();
$lang->resource->project->index          = 'index';
$lang->resource->project->view           = 'view';
$lang->resource->project->browse         = 'browse';
$lang->resource->project->create         = 'create';
$lang->resource->project->edit           = 'edit';
$lang->resource->project->batchedit      = 'batchEdit';
$lang->resource->project->start          = 'start';
$lang->resource->project->activate       = 'activate';
$lang->resource->project->putoff         = 'putoff';
$lang->resource->project->suspend        = 'suspend';
$lang->resource->project->close          = 'close';
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
$lang->resource->project->fixFirst       = 'fixFirst';
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
$lang->resource->project->batchUnlinkStory = 'batchUnlinkStory';
$lang->resource->project->updateOrder      = 'updateOrder';
$lang->resource->project->kanban           = 'kanban';
$lang->resource->project->printKanban      = 'printKanban';
$lang->resource->project->tree             = 'tree';
$lang->resource->project->all              = 'all';

$lang->project->methodOrder[0]   = 'index';
$lang->project->methodOrder[5]   = 'view';
$lang->project->methodOrder[10]  = 'browse';
$lang->project->methodOrder[15]  = 'create';
$lang->project->methodOrder[20]  = 'edit';
$lang->project->methodOrder[25]  = 'batchedit';
$lang->project->methodOrder[30]  = 'start';
$lang->project->methodOrder[35]  = 'activate';
$lang->project->methodOrder[40]  = 'putoff';
$lang->project->methodOrder[45]  = 'suspend';
$lang->project->methodOrder[50]  = 'close';
$lang->project->methodOrder[60]  = 'delete';
$lang->project->methodOrder[65]  = 'task';
$lang->project->methodOrder[70]  = 'grouptask';
$lang->project->methodOrder[75]  = 'importtask';
$lang->project->methodOrder[80]  = 'importBug';
$lang->project->methodOrder[85]  = 'story';
$lang->project->methodOrder[90]  = 'build';
$lang->project->methodOrder[95]  = 'testtask';
$lang->project->methodOrder[100] = 'bug';
$lang->project->methodOrder[105] = 'burn';
$lang->project->methodOrder[110] = 'computeBurn';
$lang->project->methodOrder[115] = 'fixFirst';
$lang->project->methodOrder[120] = 'burnData';
$lang->project->methodOrder[125] = 'team';
//$lang->project->methodOrder[130] = 'doc';
$lang->project->methodOrder[135] = 'dynamic';
$lang->project->methodOrder[140] = 'manageProducts';
$lang->project->methodOrder[145] = 'manageMembers';
$lang->project->methodOrder[150] = 'unlinkMember';
$lang->project->methodOrder[155] = 'linkStory';
$lang->project->methodOrder[160] = 'unlinkStory';
$lang->project->methodOrder[165] = 'batchUnlinkStory';
$lang->project->methodOrder[170] = 'updateOrder';
$lang->project->methodOrder[175] = 'kanban';
$lang->project->methodOrder[180] = 'printKanban';
$lang->project->methodOrder[185] = 'tree';
$lang->project->methodOrder[190] = 'all';

/* Task. */
$lang->resource->task = new stdclass();
$lang->resource->task->create             = 'create';
$lang->resource->task->edit               = 'edit';
$lang->resource->task->assignTo           = 'assign';
$lang->resource->task->start              = 'start';
$lang->resource->task->pause              = 'pause';
$lang->resource->task->restart            = 'restart';
$lang->resource->task->finish             = 'finish';
$lang->resource->task->cancel             = 'cancel';
$lang->resource->task->close              = 'close';
$lang->resource->task->batchCreate        = 'batchCreate';
$lang->resource->task->batchEdit          = 'batchEdit';
$lang->resource->task->batchClose         = 'batchClose';
$lang->resource->task->batchCancel        = 'batchCancel';
$lang->resource->task->batchAssignTo      = 'batchAssignTo';
$lang->resource->task->batchChangeModule  = 'batchChangeModule';
$lang->resource->task->activate           = 'activate';
$lang->resource->task->delete             = 'delete';
$lang->resource->task->view               = 'view';
$lang->resource->task->export             = 'export';
$lang->resource->task->confirmStoryChange = 'confirmStoryChange';
$lang->resource->task->recordEstimate     = 'recordEstimate';
$lang->resource->task->editEstimate       = 'editEstimate';
$lang->resource->task->deleteEstimate     = 'deleteEstimate';
$lang->resource->task->report             = 'reportChart';

$lang->task->methodOrder[5]   = 'create';
$lang->task->methodOrder[10]  = 'batchCreate';
$lang->task->methodOrder[15]  = 'batchEdit';
$lang->task->methodOrder[20]  = 'edit';
$lang->task->methodOrder[25]  = 'assignTo';
$lang->task->methodOrder[30]  = 'batchAssignTo';
$lang->task->methodOrder[35]  = 'start';
$lang->task->methodOrder[40]  = 'pause';
$lang->task->methodOrder[45]  = 'restart';
$lang->task->methodOrder[50]  = 'finish';
$lang->task->methodOrder[55]  = 'cancel';
$lang->task->methodOrder[60]  = 'close';
$lang->task->methodOrder[65]  = 'batchClose';
$lang->task->methodOrder[70]  = 'activate';
$lang->task->methodOrder[75]  = 'delete';
$lang->task->methodOrder[80]  = 'view';
$lang->task->methodOrder[85]  = 'export';
$lang->task->methodOrder[90]  = 'confirmStoryChange';
$lang->task->methodOrder[95]  = 'recordEstimate';
$lang->task->methodOrder[100] = 'editEstimate';
$lang->task->methodOrder[105] = 'deleteEstimate';
$lang->task->methodOrder[110] = 'report';
$lang->task->methodOrder[115] = 'batchChangeModule';

/* Build. */
$lang->resource->build = new stdclass();
$lang->resource->build->create           = 'create';
$lang->resource->build->edit             = 'edit';
$lang->resource->build->delete           = 'delete';
$lang->resource->build->view             = 'view';
$lang->resource->build->linkStory        = 'linkStory';
$lang->resource->build->unlinkStory      = 'unlinkStory';
$lang->resource->build->batchUnlinkStory = 'batchUnlinkStory';
$lang->resource->build->linkBug          = 'linkBug';
$lang->resource->build->unlinkBug        = 'unlinkBug';
$lang->resource->build->batchUnlinkBug   = 'batchUnlinkBug';

$lang->build->methodOrder[5]  = 'create';
$lang->build->methodOrder[10] = 'edit';
$lang->build->methodOrder[15] = 'delete';
$lang->build->methodOrder[20] = 'view';
$lang->build->methodOrder[25] = 'linkStory';
$lang->build->methodOrder[30] = 'unlinkStory';
$lang->build->methodOrder[35] = 'batchUnlinkStory';
$lang->build->methodOrder[40] = 'linkBug';
$lang->build->methodOrder[45] = 'unlinkBug';
$lang->build->methodOrder[50] = 'batchUnlinkBug';

/* QA. */
$lang->resource->qa = new stdclass();
$lang->resource->qa->index = 'index';

$lang->qa->methodOrder[0] = 'index';

/* Bug. */
$lang->resource->bug = new stdclass();
$lang->resource->bug->index              = 'index';
$lang->resource->bug->browse             = 'browse';
$lang->resource->bug->create             = 'create';
$lang->resource->bug->batchCreate        = 'batchCreate';
$lang->resource->bug->confirmBug         = 'confirmBug';
$lang->resource->bug->batchConfirm       = 'batchConfirm';
$lang->resource->bug->view               = 'view';
$lang->resource->bug->edit               = 'edit';
$lang->resource->bug->linkBugs           = 'linkBugs';
$lang->resource->bug->unlinkBug          = 'unlinkBug';
$lang->resource->bug->batchEdit          = 'batchEdit';
$lang->resource->bug->batchClose         = 'batchClose';
$lang->resource->bug->assignTo           = 'assignTo';
$lang->resource->bug->batchAssignTo      = 'batchAssignTo';
$lang->resource->bug->resolve            = 'resolve';
$lang->resource->bug->batchResolve       = 'batchResolve';
$lang->resource->bug->activate           = 'activate';
$lang->resource->bug->close              = 'close';
$lang->resource->bug->report             = 'reportChart';
$lang->resource->bug->export             = 'export';
$lang->resource->bug->confirmStoryChange = 'confirmStoryChange';
$lang->resource->bug->delete             = 'delete';
$lang->resource->bug->saveTemplate       = 'saveTemplate';
$lang->resource->bug->deleteTemplate     = 'deleteTemplate';
$lang->resource->bug->setPublic          = 'setPublic';
$lang->resource->bug->batchChangeModule  = 'batchChangeModule';

$lang->bug->methodOrder[0]   = 'index';
$lang->bug->methodOrder[5]   = 'browse';
$lang->bug->methodOrder[10]  = 'create';
$lang->bug->methodOrder[15]  = 'batchCreate';
$lang->bug->methodOrder[20]  = 'batchEdit';
$lang->bug->methodOrder[25]  = 'confirmBug';
$lang->bug->methodOrder[30]  = 'batchConfirm';
$lang->bug->methodOrder[35]  = 'view';
$lang->bug->methodOrder[40]  = 'edit';
$lang->bug->methodOrder[45]  = 'assignTo';
$lang->bug->methodOrder[50]  = 'batchAssignTo';
$lang->bug->methodOrder[55]  = 'resolve';
$lang->bug->methodOrder[60]  = 'batchResolve';
$lang->bug->methodOrder[65]  = 'batchClose';
$lang->bug->methodOrder[70]  = 'activate';
$lang->bug->methodOrder[75]  = 'close';
$lang->bug->methodOrder[80]  = 'report';
$lang->bug->methodOrder[85]  = 'export';
$lang->bug->methodOrder[90]  = 'confirmStoryChange';
$lang->bug->methodOrder[95]  = 'delete';
$lang->bug->methodOrder[100] = 'saveTemplate';
$lang->bug->methodOrder[105] = 'deleteTemplate';
$lang->bug->methodOrder[110] = 'setPublic';
$lang->bug->methodOrder[115] = 'linkBugs';
$lang->bug->methodOrder[120] = 'unlinkBug';
$lang->bug->methodOrder[125] = 'batchChangeModule';

/* Test case. */
$lang->resource->testcase = new stdclass();
$lang->resource->testcase->index              = 'index';
$lang->resource->testcase->browse             = 'browse';
$lang->resource->testcase->groupCase          = 'groupCase';
$lang->resource->testcase->create             = 'create';
$lang->resource->testcase->batchCreate        = 'batchCreate';
$lang->resource->testcase->createBug          = 'createBug';
$lang->resource->testcase->view               = 'view';
$lang->resource->testcase->edit               = 'edit';
$lang->resource->testcase->linkCases          = 'linkCases';
$lang->resource->testcase->unlinkCase         = 'unlinkCase';
$lang->resource->testcase->batchEdit          = 'batchEdit';
$lang->resource->testcase->delete             = 'delete';
$lang->resource->testcase->batchDelete        = 'batchDelete';
$lang->resource->testcase->export             = 'export';
$lang->resource->testcase->exportTemplet      = 'exportTemplet';
$lang->resource->testcase->import             = 'import';
$lang->resource->testcase->showImport         = 'showImport';
$lang->resource->testcase->confirmChange      = 'confirmChange';
$lang->resource->testcase->confirmStoryChange = 'confirmStoryChange';
$lang->resource->testcase->batchChangeModule  = 'batchChangeModule';
$lang->resource->testcase->bugs               = 'bugs';
$lang->resource->testcase->review             = 'review';
$lang->resource->testcase->batchReview        = 'batchReview';
$lang->resource->testcase->importFromLib      = 'importFromLib';
$lang->resource->testcase->batchCaseTypeChange = 'batchCaseTypeChange';
$lang->resource->testcase->batchConfirmStoryChange = 'batchConfirmStoryChange';

$lang->testcase->methodOrder[0]   = 'index';
$lang->testcase->methodOrder[5]   = 'browse';
$lang->testcase->methodOrder[10]  = 'groupCase';
$lang->testcase->methodOrder[15]  = 'create';
$lang->testcase->methodOrder[20]  = 'batchCreate';
$lang->testcase->methodOrder[25]  = 'createBug';
$lang->testcase->methodOrder[30]  = 'view';
$lang->testcase->methodOrder[35]  = 'edit';
$lang->testcase->methodOrder[40]  = 'delete';
$lang->testcase->methodOrder[45]  = 'export';
$lang->testcase->methodOrder[50]  = 'confirmChange';
$lang->testcase->methodOrder[55]  = 'confirmStoryChange';
$lang->testcase->methodOrder[60]  = 'batchEdit';
$lang->testcase->methodOrder[65]  = 'batchDelete';
$lang->testcase->methodOrder[70]  = 'batchChangeModule';
$lang->testcase->methodOrder[75]  = 'linkCases';
$lang->testcase->methodOrder[80]  = 'unlinkCase';
$lang->testcase->methodOrder[85]  = 'bugs';
$lang->testcase->methodOrder[95]  = 'review';
$lang->testcase->methodOrder[100] = 'batchReview';
$lang->testcase->methodOrder[105] = 'batchConfirmStoryChange';
$lang->testcase->methodOrder[110] = 'importFromLib';
$lang->testcase->methodOrder[115] = 'batchCaseTypeChange';

/* Test task. */
$lang->resource->testtask = new stdclass();
$lang->resource->testtask->index            = 'index';
$lang->resource->testtask->create           = 'create';
$lang->resource->testtask->browse           = 'browse';
$lang->resource->testtask->view             = 'view';
$lang->resource->testtask->cases            = 'lblCases';
$lang->resource->testtask->groupCase        = 'groupCase';
$lang->resource->testtask->edit             = 'edit';
$lang->resource->testtask->start            = 'start';
$lang->resource->testtask->close            = 'close';
$lang->resource->testtask->delete           = 'delete';
$lang->resource->testtask->batchAssign      = 'batchAssign';
$lang->resource->testtask->linkcase         = 'linkCase';
$lang->resource->testtask->unlinkcase       = 'lblUnlinkCase';
$lang->resource->testtask->batchUnlinkCases = 'batchUnlinkCases';
$lang->resource->testtask->runcase          = 'lblRunCase';
$lang->resource->testtask->results          = 'lblResults';
$lang->resource->testtask->results          = 'lblResults';
$lang->resource->testtask->batchRun         = 'batchRun';
$lang->resource->testtask->activate         = 'activate';
$lang->resource->testtask->block            = 'block';
$lang->resource->testtask->report           = 'reportChart';

$lang->testtask->methodOrder[0]  = 'index';
$lang->testtask->methodOrder[5]  = 'create';
$lang->testtask->methodOrder[10] = 'browse';
$lang->testtask->methodOrder[15] = 'view';
$lang->testtask->methodOrder[20] = 'cases';
$lang->testtask->methodOrder[25] = 'groupCase';
$lang->testtask->methodOrder[30] = 'edit';
$lang->testtask->methodOrder[35] = 'start';
$lang->testtask->methodOrder[40] = 'activate';
$lang->testtask->methodOrder[45] = 'block';
$lang->testtask->methodOrder[50] = 'close';
$lang->testtask->methodOrder[55] = 'delete';
$lang->testtask->methodOrder[60] = 'batchAssign';
$lang->testtask->methodOrder[65] = 'linkcase';
$lang->testtask->methodOrder[70] = 'unlinkcase';
$lang->testtask->methodOrder[75] = 'runcase';
$lang->testtask->methodOrder[80] = 'results';
$lang->testtask->methodOrder[85] = 'batchUnlinkCases';
$lang->testtask->methodOrder[90] = 'report';

$lang->resource->testreport = new stdclass();
$lang->resource->testreport->browse = 'browse';
$lang->resource->testreport->create = 'create';
$lang->resource->testreport->view   = 'view';
$lang->resource->testreport->delete = 'delete';
$lang->resource->testreport->edit   = 'edit';

$lang->testreport->methodOrder[0]  = 'browse';
$lang->testreport->methodOrder[5]  = 'create';
$lang->testreport->methodOrder[10] = 'view';
$lang->testreport->methodOrder[15] = 'delete';
$lang->testreport->methodOrder[20] = 'edit';

$lang->resource->testsuite = new stdclass();
$lang->resource->testsuite->index            = 'index';
$lang->resource->testsuite->browse           = 'browse';
$lang->resource->testsuite->create           = 'create';
$lang->resource->testsuite->view             = 'view';
$lang->resource->testsuite->edit             = 'edit';
$lang->resource->testsuite->delete           = 'delete';
$lang->resource->testsuite->linkCase         = 'linkCase';
$lang->resource->testsuite->unlinkCase       = 'unlinkCase';
$lang->resource->testsuite->batchUnlinkCases = 'batchUnlinkCases';
$lang->resource->testsuite->batchCreateCase  = 'batchCreateCase';
$lang->resource->testsuite->exportTemplet    = 'exportTemplet';
$lang->resource->testsuite->import           = 'import';
$lang->resource->testsuite->showImport       = 'showImport';

$lang->testsuite->methodOrder[0]  = 'index';
$lang->testsuite->methodOrder[5]  = 'browse';
$lang->testsuite->methodOrder[10] = 'create';
$lang->testsuite->methodOrder[15] = 'view';
$lang->testsuite->methodOrder[20] = 'edit';
$lang->testsuite->methodOrder[25] = 'delete';
$lang->testsuite->methodOrder[30] = 'linkCase';
$lang->testsuite->methodOrder[35] = 'unlinkCase';
$lang->testsuite->methodOrder[40] = 'batchUnlinkCases';
$lang->testsuite->methodOrder[45] = 'batchCreateCase';
$lang->testsuite->methodOrder[50] = 'exportTemplet';
$lang->testsuite->methodOrder[55] = 'import';
$lang->testsuite->methodOrder[60] = 'showImport';

$lang->resource->caselib = new stdclass();
$lang->resource->caselib->library    = 'library';
$lang->resource->caselib->createLib  = 'createLib';
$lang->resource->caselib->edit       = 'editLib';
$lang->resource->caselib->createCase = 'createCase';
$lang->resource->caselib->libView    = 'libView';

$lang->caselib->methodOrder[0]  = 'library';
$lang->caselib->methodOrder[5]  = 'createLib';
$lang->caselib->methodOrder[10] = 'edit';
$lang->caselib->methodOrder[15] = 'createCase';
$lang->caselib->methodOrder[20] = 'libView';

/* Doc. */
$lang->resource->doc = new stdclass();
$lang->resource->doc->index      = 'index';
$lang->resource->doc->browse     = 'browse';
$lang->resource->doc->createLib  = 'createLib';
$lang->resource->doc->editLib    = 'editLib';
$lang->resource->doc->deleteLib  = 'deleteLib';
$lang->resource->doc->create     = 'create';
$lang->resource->doc->view       = 'view';
$lang->resource->doc->edit       = 'edit';
$lang->resource->doc->delete     = 'delete';
$lang->resource->doc->allLibs    = 'allLibs';
$lang->resource->doc->objectLibs = 'objectLibs';
$lang->resource->doc->showFiles  = 'showFiles';
$lang->resource->doc->sort       = 'sort';
//$lang->resource->doc->diff       = 'diff';

$lang->doc->methodOrder[0]  = 'index';
$lang->doc->methodOrder[5]  = 'browse';
$lang->doc->methodOrder[10] = 'createLib';
$lang->doc->methodOrder[15] = 'editLib';
$lang->doc->methodOrder[20] = 'deleteLib';
$lang->doc->methodOrder[25] = 'create';
$lang->doc->methodOrder[30] = 'view';
$lang->doc->methodOrder[35] = 'edit';
$lang->doc->methodOrder[40] = 'delete';
$lang->doc->methodOrder[45] = 'allLibs';
$lang->doc->methodOrder[50] = 'showFiles';
$lang->doc->methodOrder[55] = 'objectLibs';
$lang->doc->methodOrder[60] = 'sort';
//$lang->doc->methodOrder[55] = 'diff';

/* mail. */
$lang->resource->mail = new stdclass();
$lang->resource->mail->index  = 'index';
$lang->resource->mail->detect = 'detect';
$lang->resource->mail->edit   = 'edit';
$lang->resource->mail->save   = 'save';
$lang->resource->mail->test   = 'test';
$lang->resource->mail->reset  = 'reset';
$lang->resource->mail->browse = 'browse';
$lang->resource->mail->delete = 'delete';
$lang->resource->mail->resend = 'resend';
$lang->resource->mail->batchDelete   = 'batchDelete';
$lang->resource->mail->sendCloud     = 'sendCloud';
$lang->resource->mail->sendcloudUser = 'sendcloudUser';

$lang->mail->methodOrder[5]  = 'index';
$lang->mail->methodOrder[10] = 'detect';
$lang->mail->methodOrder[15] = 'edit';
$lang->mail->methodOrder[20] = 'save';
$lang->mail->methodOrder[25] = 'test';
$lang->mail->methodOrder[30] = 'reset';
$lang->mail->methodOrder[35] = 'browse';
$lang->mail->methodOrder[40] = 'delete';
$lang->mail->methodOrder[45] = 'batchDelete';
$lang->mail->methodOrder[50] = 'resend';
$lang->mail->methodOrder[55] = 'sendCloud';
$lang->mail->methodOrder[60] = 'sendcloudUser';

/* custom. */
$lang->resource->custom = new stdclass();
$lang->resource->custom->index   = 'index';
$lang->resource->custom->set     = 'set';
$lang->resource->custom->restore = 'restore';
$lang->resource->custom->flow    = 'flow';
$lang->resource->custom->working = 'working';

$lang->custom->methodOrder[5]  = 'index';
$lang->custom->methodOrder[10] = 'set';
$lang->custom->methodOrder[15] = 'restore';
$lang->custom->methodOrder[20] = 'flow';
$lang->custom->methodOrder[25] = 'working';

/* Subversion. */
$lang->resource->svn = new stdclass();
$lang->resource->svn->diff    = 'diff';
$lang->resource->svn->cat     = 'cat';
$lang->resource->svn->apiSync = 'apiSync';

$lang->svn->methodOrder[5]  = 'diff';
$lang->svn->methodOrder[10] = 'cat';
$lang->svn->methodOrder[15] = 'apiSync';

/* Git. */
$lang->resource->git = new stdclass();
$lang->resource->git->diff    = 'diff';
$lang->resource->git->cat     = 'cat';
$lang->resource->git->apiSync = 'apiSync';

$lang->git->methodOrder[5]  = 'diff';
$lang->git->methodOrder[10] = 'cat';
$lang->git->methodOrder[15] = 'apiSync';

/* Company. */
$lang->resource->company = new stdclass();
$lang->resource->company->index  = 'index';
$lang->resource->company->browse = 'browse';
$lang->resource->company->edit   = 'edit';
$lang->resource->company->view   = 'view';
$lang->resource->company->dynamic= 'dynamic';

$lang->company->methodOrder[0]  = 'index';
$lang->company->methodOrder[5]  = 'browse';
$lang->company->methodOrder[15] = 'edit';
$lang->company->methodOrder[25] = 'dynamic';

/* Department. */
$lang->resource->dept = new stdclass();
$lang->resource->dept->browse      = 'browse';
$lang->resource->dept->updateOrder = 'updateOrder';
$lang->resource->dept->manageChild = 'manageChild';
$lang->resource->dept->edit        = 'edit';
$lang->resource->dept->delete      = 'delete';

$lang->dept->methodOrder[5]  = 'browse';
$lang->dept->methodOrder[10] = 'updateOrder';
$lang->dept->methodOrder[15] = 'manageChild';
$lang->dept->methodOrder[20] = 'edit';
$lang->dept->methodOrder[25] = 'delete';

/* Group. */
$lang->resource->group = new stdclass();
$lang->resource->group->browse       = 'browse';
$lang->resource->group->create       = 'create';
$lang->resource->group->edit         = 'edit';
$lang->resource->group->copy         = 'copy';
$lang->resource->group->delete       = 'delete';
$lang->resource->group->manageView   = 'manageView';
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
$lang->resource->user = new stdclass();
$lang->resource->user->create         = 'create';
$lang->resource->user->batchCreate    = 'batchCreate';
$lang->resource->user->view           = 'view';
$lang->resource->user->edit           = 'edit';
$lang->resource->user->unlock         = 'unlock';
$lang->resource->user->delete         = 'delete';
$lang->resource->user->todo           = 'todo';
$lang->resource->user->story          = 'story';
$lang->resource->user->task           = 'task';
$lang->resource->user->bug            = 'bug';
$lang->resource->user->testTask       = 'testTask';
$lang->resource->user->testCase       = 'testCase';
$lang->resource->user->project        = 'project';
$lang->resource->user->dynamic        = 'dynamic';
$lang->resource->user->profile        = 'profile';
$lang->resource->user->batchEdit      = 'batchEdit';
$lang->resource->user->unbind         = 'unbind';

$lang->user->methodOrder[5]  = 'create';
$lang->user->methodOrder[7]  = 'batchCreate';
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
$lang->user->methodOrder[60] = 'batchEdit';
$lang->user->methodOrder[75] = 'unbind';

/* Tree. */
$lang->resource->tree = new stdclass();
$lang->resource->tree->browse      = 'browse';
$lang->resource->tree->browseTask  = 'browseTask';
$lang->resource->tree->updateOrder = 'updateOrder';
$lang->resource->tree->manageChild = 'manageChild';
$lang->resource->tree->edit        = 'edit';
$lang->resource->tree->fix         = 'fix';
$lang->resource->tree->delete      = 'delete';

$lang->tree->methodOrder[5]  = 'browse';
$lang->tree->methodOrder[10] = 'browseTask';
$lang->tree->methodOrder[15] = 'updateOrder';
$lang->tree->methodOrder[20] = 'manageChild';
$lang->tree->methodOrder[25] = 'edit';
$lang->tree->methodOrder[30] = 'delete';

/* Report. */
$lang->resource->report = new stdclass();
$lang->resource->report->index            = 'index';
$lang->resource->report->projectDeviation = 'projectDeviation';
$lang->resource->report->productSummary   = 'productSummary';
$lang->resource->report->bugCreate        = 'bugCreate';
$lang->resource->report->bugAssign        = 'bugAssign';
$lang->resource->report->workload         = 'workload';

$lang->report->methodOrder[0]  = 'index';
$lang->report->methodOrder[5]  = 'projectDeviation';
$lang->report->methodOrder[10] = 'productSummary';
$lang->report->methodOrder[15] = 'bugCreate';
$lang->report->methodOrder[20] = 'workload';

/* Search. */
$lang->resource->search = new stdclass();
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
$lang->resource->admin = new stdclass();
$lang->resource->admin->index     = 'index';
$lang->resource->admin->checkDB   = 'checkDB';
$lang->resource->admin->safe      = 'safeIndex';
$lang->resource->admin->checkWeak = 'checkWeak';
$lang->resource->admin->sso       = 'sso';

$lang->admin->methodOrder[0]  = 'index';
$lang->admin->methodOrder[5]  = 'checkDB';
$lang->admin->methodOrder[10] = 'safeIndex';
$lang->admin->methodOrder[15] = 'checkWeak';
$lang->admin->methodOrder[20] = 'sso';

/* Extension. */
$lang->resource->extension = new stdclass();
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

/* Editor. */
$lang->resource->editor = new stdclass();
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
$lang->resource->convert = new stdclass();
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

/* Others. */
$lang->resource->api = new stdclass();
$lang->resource->api->getModel    = 'getModel';
$lang->resource->api->debug       = 'debug';
$lang->resource->api->sql         = 'sql';

$lang->api->methodOrder[5]  = 'getModel';
$lang->api->methodOrder[10] = 'debug';
$lang->api->methodOrder[15] = 'sql';

$lang->resource->file = new stdclass();
$lang->resource->file->download     = 'download';
$lang->resource->file->edit         = 'edit';
$lang->resource->file->delete       = 'delete';
$lang->resource->file->uploadImages = 'uploadImages';
$lang->resource->file->setPublic     = 'setPublic';

$lang->file->methodOrder[5]  = 'download';
$lang->file->methodOrder[10] = 'edit';
$lang->file->methodOrder[15] = 'delete';
$lang->file->methodOrder[20] = 'uploadImages';
$lang->file->methodOrder[25] = 'setPublic';

$lang->resource->misc = new stdclass();
$lang->resource->misc->ping = 'ping';

$lang->misc->methodOrder[5] = 'ping';

$lang->resource->action = new stdclass();
$lang->resource->action->trash    = 'trash';
$lang->resource->action->undelete = 'undelete';
$lang->resource->action->hideOne  = 'hideOne';
$lang->resource->action->hideAll  = 'hideAll';
$lang->resource->action->editComment = 'editComment';

$lang->action->methodOrder[5]  = 'trash';
$lang->action->methodOrder[10] = 'undelete';
$lang->action->methodOrder[15] = 'hideOne';
$lang->action->methodOrder[20] = 'hideAll';

$lang->resource->backup = new stdclass();
$lang->resource->backup->index   = 'index';
$lang->resource->backup->backup  = 'backup';
$lang->resource->backup->restore = 'restore';
$lang->resource->backup->change  = 'change';
$lang->resource->backup->delete  = 'delete';

$lang->backup->methodOrder[5]  = 'index';
$lang->backup->methodOrder[10] = 'backup';
$lang->backup->methodOrder[15] = 'restore';
$lang->backup->methodOrder[20] = 'delete';

$lang->resource->cron = new stdclass();
$lang->resource->cron->index   = 'index';
$lang->resource->cron->turnon  = 'turnon';
$lang->resource->cron->create  = 'create';
$lang->resource->cron->edit    = 'edit';
$lang->resource->cron->toggle  = 'toggle';
$lang->resource->cron->delete  = 'delete';
$lang->resource->cron->openProcess = 'openProcess';

$lang->cron->methodOrder[5]  = 'index';
$lang->cron->methodOrder[10] = 'turnon';
$lang->cron->methodOrder[15] = 'create';
$lang->cron->methodOrder[20] = 'edit';
$lang->cron->methodOrder[25] = 'toggle';
$lang->cron->methodOrder[30] = 'delete';
$lang->cron->methodOrder[35] = 'openProcess';

$lang->resource->dev = new stdclass();
$lang->resource->dev->api = 'api';
$lang->resource->dev->db  = 'db';

$lang->dev->methodOrder[5]  = 'api';
$lang->dev->methodOrder[10] = 'db';

/* Every version of new privilege. */
$lang->changelog['1.0.1'][] = 'project-computeBurn';

$lang->changelog['1.1'][]   = 'search-saveQuery';
$lang->changelog['1.1'][]   = 'search-deleteQuery';

$lang->changelog['1.2'][]   = 'product-doc';
$lang->changelog['1.2'][]   = 'project-doc';
$lang->changelog['1.2'][]   = 'bug-saveTemplate';
$lang->changelog['1.2'][]   = 'bug-deleteTemplate';
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
$lang->changelog['1.3'][]   = 'file-delete';

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

$lang->changelog['2.4'][]   = 'task-assign';
$lang->changelog['2.4'][]   = 'project-testtask';
$lang->changelog['2.4'][]   = 'todo-export';
$lang->changelog['2.4'][]   = 'product-project';

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
$lang->changelog['3.2'][] = 'report-productSummary';
$lang->changelog['3.2'][] = 'report-bugCreate';
$lang->changelog['3.2'][] = 'report-workload';
$lang->changelog['3.2'][] = 'tree-fix';

$lang->changelog['3.3'][] = 'report-bugAssign';

$lang->changelog['4.0.beta1'][] = 'user-batchCreate';
$lang->changelog['4.0.beta1'][] = 'user-unlock';
$lang->changelog['4.0.beta1'][] = 'admin-checkDB';

$lang->changelog['4.0.beta2'][] = 'todo-batchEdit';
$lang->changelog['4.0.beta2'][] = 'story-batchEdit';
$lang->changelog['4.0.beta2'][] = 'bug-batchEdit';
$lang->changelog['4.0.beta2'][] = 'testcase-batchEdit';
$lang->changelog['4.0.beta2'][] = 'testtask-batchRun';
$lang->changelog['4.0.beta2'][] = 'user-batchEdit';
$lang->changelog['4.0.beta2'][] = 'user-manageContacts';
$lang->changelog['4.0.beta2'][] = 'user-deleteContacts';

$lang->changelog['4.0'][] = 'todo-finish';
$lang->changelog['4.0'][] = 'product-close';
$lang->changelog['4.0'][] = 'project-start';
$lang->changelog['4.0'][] = 'project-activate';
$lang->changelog['4.0'][] = 'project-putoff';
$lang->changelog['4.0'][] = 'project-suspend';
$lang->changelog['4.0'][] = 'project-close';
$lang->changelog['4.0'][] = 'task-record';
$lang->changelog['4.0'][] = 'testtask-start';
$lang->changelog['4.0'][] = 'testtask-close';
$lang->changelog['4.0'][] = 'action-hideOne';
$lang->changelog['4.0'][] = 'action-hideAll';
$lang->changelog['4.0'][] = 'task-editEstimate';
$lang->changelog['4.0'][] = 'task-deleteEstimate';

$lang->changelog['4.1'][] = 'todo-batchFinish';
$lang->changelog['4.1'][] = 'productplan-batchUnlinkStory';
$lang->changelog['4.1'][] = 'company-view';
$lang->changelog['4.1'][] = 'user-story';
$lang->changelog['4.1'][] = 'user-testTask';
$lang->changelog['4.1'][] = 'user-testCase';

$lang->changelog['4.2.beta'][] = 'tree-browseTask';

$lang->changelog['4.3.beta'][] = 'product-batchEdit';
$lang->changelog['4.3.beta'][] = 'project-batchEdit';
$lang->changelog['4.3.beta'][] = 'story-batchReview';
$lang->changelog['4.3.beta'][] = 'story-batchChangePlan';
$lang->changelog['4.3.beta'][] = 'story-batchChangeStage';
$lang->changelog['4.3.beta'][] = 'productplan-linkBug';
$lang->changelog['4.3.beta'][] = 'productplan-unlinkBug';
$lang->changelog['4.3.beta'][] = 'productplan-batchUnlinkBug';
$lang->changelog['4.3.beta'][] = 'bug-batchCreate';
$lang->changelog['4.3.beta'][] = 'testcase-exportTemplet';
$lang->changelog['4.3.beta'][] = 'testcase-import';
$lang->changelog['4.3.beta'][] = 'testcase-showImport';
$lang->changelog['4.3.beta'][] = 'testcase-confirmChange';
$lang->changelog['4.3.beta'][] = 'mail-reset';
$lang->changelog['4.3.beta'][] = 'api-debug';
$lang->changelog['4.3.beta'][] = 'action-editComment';

$lang->changelog['5.0.beta1'][] = 'bug-batchConfirm';
$lang->changelog['5.0.beta1'][] = 'bug-batchResolve';
$lang->changelog['5.0.beta1'][] = 'custom-index';
$lang->changelog['5.0.beta1'][] = 'custom-set';
$lang->changelog['5.0.beta1'][] = 'custom-restore';

$lang->changelog['5.0.beta2'][] = 'git-diff';
$lang->changelog['5.0.beta2'][] = 'git-cat';
$lang->changelog['5.0.beta2'][] = 'git-apiSync';

$lang->changelog['5.3'][] = 'bug-batchClose';

$lang->changelog['6.1'][] = 'story-zeroCase';
$lang->changelog['6.1'][] = 'testcase-groupCase';
$lang->changelog['6.1'][] = 'testtask-groupCase';

$lang->changelog['6.2'][] = 'task-pause';
$lang->changelog['6.2'][] = 'task-restart';
$lang->changelog['6.2'][] = 'testcase-createBug';

$lang->changelog['6.3'][] = 'bug-batchAssignTo';
$lang->changelog['6.3'][] = 'task-batchAssignTo';
$lang->changelog['6.3'][] = 'file-uploadImages';
$lang->changelog['6.3'][] = 'project-batchUnlinkStory';

$lang->changelog['6.4'][] = 'api-sql';
$lang->changelog['6.4'][] = 'backup-index';
$lang->changelog['6.4'][] = 'backup-backup';
$lang->changelog['6.4'][] = 'backup-restore';
$lang->changelog['6.4'][] = 'backup-delete';
$lang->changelog['6.4'][] = 'build-linkStory';
$lang->changelog['6.4'][] = 'build-unlinkStory';
$lang->changelog['6.4'][] = 'build-batchUnlinkStory';
$lang->changelog['6.4'][] = 'build-linkBug';
$lang->changelog['6.4'][] = 'build-unlinkBug';
$lang->changelog['6.4'][] = 'build-batchUnlinkBug';
$lang->changelog['6.4'][] = 'dept-edit';
$lang->changelog['6.4'][] = 'release-linkStory';
$lang->changelog['6.4'][] = 'release-unlinkStory';
$lang->changelog['6.4'][] = 'release-batchUnlinkStory';
$lang->changelog['6.4'][] = 'release-linkBug';
$lang->changelog['6.4'][] = 'release-unlinkBug';
$lang->changelog['6.4'][] = 'release-batchUnlinkBug';
$lang->changelog['6.4'][] = 'story-batchAssignTo';

$lang->changelog['7.1'][] = 'cron-index';
$lang->changelog['7.1'][] = 'cron-turnon';
$lang->changelog['7.1'][] = 'cron-create';
$lang->changelog['7.1'][] = 'cron-edit';
$lang->changelog['7.1'][] = 'cron-toggle';
$lang->changelog['7.1'][] = 'cron-delete';
$lang->changelog['7.1'][] = 'mail-browse';
$lang->changelog['7.1'][] = 'mail-delete';
$lang->changelog['7.1'][] = 'mail-batchDelete';
$lang->changelog['7.1'][] = 'dev-api';
$lang->changelog['7.1'][] = 'dev-db';

$lang->changelog['7.2'][] = 'admin-safeIndex';
$lang->changelog['7.2'][] = 'admin-checkWeak';
$lang->changelog['7.2'][] = 'backup-change';
$lang->changelog['7.2'][] = 'custom-flow';
$lang->changelog['7.2'][] = 'group-manageView';
$lang->changelog['7.2'][] = 'product-updateOrder';
$lang->changelog['7.2'][] = 'project-updateOrder';

$lang->changelog['7.3'][] = 'project-fixFirst';
$lang->changelog['7.3'][] = 'productplan-batchEdit';
$lang->changelog['7.3'][] = 'admin-sso';
$lang->changelog['7.3'][] = 'cron-openProcess';
$lang->changelog['7.3'][] = 'mail-sendCloud';
$lang->changelog['7.3'][] = 'mail-sendcloudUser';

$lang->changelog['7.4.beta'][] = 'release-changeStatus';
$lang->changelog['7.4.beta'][] = 'user-unbind';
$lang->changelog['7.4.beta'][] = 'branch-manage';
$lang->changelog['7.4.beta'][] = 'branch-delete';
$lang->changelog['7.4.beta'][] = 'my-unbind';

$lang->changelog['8.0'][] = 'story-batchChangeBranch';

$lang->changelog['8.0.1'][] = 'bug-linkBugs';
$lang->changelog['8.0.1'][] = 'bug-unlinkBug';
$lang->changelog['8.0.1'][] = 'story-linkStory';
$lang->changelog['8.0.1'][] = 'story-unlinkStory';
$lang->changelog['8.0.1'][] = 'testcase-linkCases';
$lang->changelog['8.0.1'][] = 'testcase-unlinkCase';

$lang->changelog['8.1.3'][] = 'story-batchChangeModule';
$lang->changelog['8.1.3'][] = 'task-batchChangeModule';
$lang->changelog['8.1.3'][] = 'bug-batchChangeModule';
$lang->changelog['8.1.3'][] = 'testcase-batchChangeModule';
$lang->changelog['8.1.3'][] = 'my-manageContacts';
$lang->changelog['8.1.3'][] = 'my-deleteContacts';

$lang->changelog['8.2.beta'][] = 'product-all';
$lang->changelog['8.2.beta'][] = 'project-tree';
$lang->changelog['8.2.beta'][] = 'project-all';
$lang->changelog['8.2.beta'][] = 'project-kanban';
$lang->changelog['8.2.beta'][] = 'project-tree';

$lang->changelog['8.3'][] = 'doc-allLibs';
$lang->changelog['8.3'][] = 'doc-objectLibs';
$lang->changelog['8.3'][] = 'doc-showFiles';

$lang->changelog['8.4'][] = 'branch-sort';
$lang->changelog['8.4'][] = 'story-bugs';
$lang->changelog['8.4'][] = 'story-cases';

$lang->changelog['9.0'][] = 'testcase-bugs';
$lang->changelog['9.0'][] = 'mail-resend';

$lang->changelog['9.1'][] = 'testcase-review';
$lang->changelog['9.1'][] = 'testcase-batchReview';
$lang->changelog['9.1'][] = 'testcase-importFromLib';
$lang->changelog['9.1'][] = 'testcase-batchCaseTypeChange';
$lang->changelog['9.1'][] = 'testcase-batchConfirmStoryChange';
$lang->changelog['9.1'][] = 'testreport-browse';
$lang->changelog['9.1'][] = 'testreport-create';
$lang->changelog['9.1'][] = 'testreport-view';
$lang->changelog['9.1'][] = 'testreport-delete';
$lang->changelog['9.1'][] = 'testreport-edit';
$lang->changelog['9.1'][] = 'testsuite-index';
$lang->changelog['9.1'][] = 'testsuite-browse';
$lang->changelog['9.1'][] = 'testsuite-create';
$lang->changelog['9.1'][] = 'testsuite-view';
$lang->changelog['9.1'][] = 'testsuite-edit';
$lang->changelog['9.1'][] = 'testsuite-delete';
$lang->changelog['9.1'][] = 'testsuite-linkCase';
$lang->changelog['9.1'][] = 'testsuite-unlinkCase';
$lang->changelog['9.1'][] = 'testsuite-batchUnlinkCases';
$lang->changelog['9.1'][] = 'testsuite-library';
$lang->changelog['9.1'][] = 'testsuite-createLib';
$lang->changelog['9.1'][] = 'testsuite-createCase';
$lang->changelog['9.1'][] = 'testsuite-libView';
$lang->changelog['9.1'][] = 'caselib-library';
$lang->changelog['9.1'][] = 'caselib-createLib';
$lang->changelog['9.1'][] = 'caselib-edit';
$lang->changelog['9.1'][] = 'caselib-createCase';
$lang->changelog['9.1'][] = 'caselib-libView';
$lang->changelog['9.1'][] = 'testtask-activate';
$lang->changelog['9.1'][] = 'testtask-block';
$lang->changelog['9.1'][] = 'testtask-report';

$lang->changelog['9.2'][] = 'custom-working';
$lang->changelog['9.2'][] = 'doc-sort';
$lang->changelog['9.2'][] = 'product-build';
$lang->changelog['9.2'][] = 'testsuite-batchCreateCase';
$lang->changelog['9.2'][] = 'testsuite-exportTemplet';
$lang->changelog['9.2'][] = 'testsuite-import';
$lang->changelog['9.2'][] = 'testsuite-showImport';

global $config;
if($config->global->flow != 'full')
{
    unset($lang->moduleOrder[10]);
    unset($lang->resource->qa);
    unset($lang->moduleOrder[50]);
    unset($lang->resource->report);
    unset($lang->moduleOrder[90]);
}

if($config->global->flow == 'onlyStory' || $config->global->flow == 'onlyTask')
{
    unset($lang->resource->build);
    unset($lang->moduleOrder[45]);
    unset($lang->resource->bug);
    unset($lang->moduleOrder[55]);
    unset($lang->resource->testcase);
    unset($lang->moduleOrder[60]);
    unset($lang->resource->testtask);
    unset($lang->moduleOrder[65]);

    unset($lang->resource->my->bug);
    unset($lang->resource->my->testTask);
    unset($lang->resource->my->testCase);
}

if($config->global->flow == 'onlyStory' || $config->global->flow == 'onlyTest')
{
    unset($lang->resource->project);
    unset($lang->moduleOrder[35]);
    unset($lang->resource->task);
    unset($lang->moduleOrder[40]);

    unset($lang->resource->my->task);
    unset($lang->resource->my->project);

    unset($lang->resource->product->project);
}

if($config->global->flow == 'onlyTask' || $config->global->flow == 'onlyTest')
{
    unset($lang->resource->story);
    unset($lang->moduleOrder[20]);
    unset($lang->resource->productplan);
    unset($lang->moduleOrder[25]);
    unset($lang->resource->release);
    unset($lang->moduleOrder[30]);

    unset($lang->resource->my->story);
}

if($config->global->flow == 'onlyStory')
{
    unset($lang->resource->svn);
    unset($lang->moduleOrder[150]);
    unset($lang->resource->git);
    unset($lang->moduleOrder[155]);
    
    unset($lang->resource->story->tasks);
}

if($config->global->flow == 'onlyTask')
{
    unset($lang->resource->product);
    unset($lang->moduleOrder[15]);
    unset($lang->resource->bug);
    unset($lang->moduleOrder[55]);

    unset($lang->resource->project->importbug);
    unset($lang->resource->project->story);
    unset($lang->resource->project->bug);
    unset($lang->resource->project->linkStory);
    unset($lang->resource->project->unlinkStory);
    unset($lang->resource->project->ajaxGetProducts);
}

if($config->global->flow == 'onlyTest')
{
    unset($lang->resource->product->browse);
    unset($lang->resource->product->roadmap);
    unset($lang->resource->product->dynamic);
    unset($lang->resource->product->ajaxGetProjects);
    unset($lang->resource->product->ajaxGetPlans);

    unset($lang->resource->build->ajaxGetProjectBuilds);
    unset($lang->build->methodOrder[30]);

    unset($lang->resource->bug->confirmStoryChange);
    unset($lang->bug->methodOrder[60]);

    unset($lang->resource->testcase->confirmStoryChange);
    unset($lang->testcase->methodOrder[40]);

    $lang->resource->product->build = 'build';
    $lang->product->methodOrder[5]  = 'build';
}
