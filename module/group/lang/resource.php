<?php
/**
 * The group module zh-cn file of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id$
 * @link        http://www.zentao.net
 */
//---------------------------- 所有resource列表-------------------------*/

/* 首页。*/
$lang->resource->index->index = 'index';

/* 我的地盘。*/
$lang->resource->my->index       = 'index';
$lang->resource->my->todo        = 'todo';
$lang->resource->my->task        = 'task';
$lang->resource->my->bug         = 'bug';
$lang->resource->my->story       = 'story';
$lang->resource->my->project     = 'project';
$lang->resource->my->profile     = 'profile';
$lang->resource->my->editProfile = 'editProfile';

/* todo。*/
$lang->resource->todo->create    = 'create';
$lang->resource->todo->edit      = 'edit';
$lang->resource->todo->view      = 'view';
$lang->resource->todo->delete    = 'delete';
$lang->resource->todo->mark      = 'mark';
$lang->resource->todo->import2Today = 'import2Today';

/* 产品视图。*/
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

/* 需求。*/
$lang->resource->story->create  = 'create';
$lang->resource->story->edit    = 'edit';
$lang->resource->story->delete  = 'delete';
$lang->resource->story->view    = 'view';
$lang->resource->story->change  = 'lblChange';
$lang->resource->story->review  = 'lblReview';
$lang->resource->story->close   = 'lblClose';
$lang->resource->story->activate= 'lblActivate';
$lang->resource->story->tasks   = 'tasks';
$lang->resource->story->ajaxGetProjectStories = 'ajaxGetProjectStories';
$lang->resource->story->ajaxGetProductStories = 'ajaxGetProductStories';

/* 产品计划。*/
$lang->resource->productplan->browse      = 'browse';
$lang->resource->productplan->create      = 'create';
$lang->resource->productplan->edit        = 'edit';
$lang->resource->productplan->delete      = 'delete';
$lang->resource->productplan->view        = 'view';
$lang->resource->productplan->linkStory   = 'linkStory';
$lang->resource->productplan->unlinkStory = 'unlinkStory';

/* 发布。*/
$lang->resource->release->browse = 'browse';
$lang->resource->release->create = 'create';
$lang->resource->release->edit   = 'edit';
$lang->resource->release->delete = 'delete';
$lang->resource->release->view   = 'view';

/* 项目视图。*/
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

/* 任务。*/
$lang->resource->task->create              = 'create';
$lang->resource->task->edit                = 'edit';
$lang->resource->task->start               = 'start';
$lang->resource->task->complete            = 'complete';
$lang->resource->task->cancel              = 'complete';
$lang->resource->task->delete              = 'delete';
$lang->resource->task->view                = 'view';
$lang->resource->task->confirmStoryChange  = 'confirmStoryChange';
$lang->resource->task->ajaxGetUserTasks    = 'ajaxGetUserTasks';
$lang->resource->task->ajaxGetProjectTasks = 'ajaxGetProjectTasks';

/* build。*/
$lang->resource->build->create               = 'create';
$lang->resource->build->edit                 = 'edit';
$lang->resource->build->delete               = 'delete';
$lang->resource->build->view                 = 'view';
$lang->resource->build->ajaxGetProductBuilds = 'ajaxGetProductBuilds';
$lang->resource->build->ajaxGetProjectBuilds = 'ajaxGetProjectBuilds';

/* QA视图。*/
$lang->resource->qa->index = 'index';

/* Bug。*/
$lang->resource->bug->index               = 'index';
$lang->resource->bug->browse              = 'browse';
$lang->resource->bug->create              = 'create';
$lang->resource->bug->view                = 'view';
$lang->resource->bug->edit                = 'edit';
$lang->resource->bug->resolve             = 'resolve';
$lang->resource->bug->activate            = 'activate';
$lang->resource->bug->close               = 'close';
$lang->resource->bug->report              = 'reportChart';
$lang->resource->bug->confirmStoryChange  = 'confirmStoryChange';
$lang->resource->bug->delete              = 'delete';
$lang->resource->bug->saveTemplate        = 'saveTemplate';
$lang->resource->bug->deleteTemplate      = 'deleteTemplate';
$lang->resource->bug->customFields        = 'customFields';
$lang->resource->bug->ajaxGetUserBugs     = 'ajaxGetUserBugs';
$lang->resource->bug->ajaxGetModuleOwner  = 'ajaxGetModuleOwner';

/* 测试用例。*/
$lang->resource->testcase->index              = 'index';
$lang->resource->testcase->browse             = 'browse';
$lang->resource->testcase->create             = 'create';
$lang->resource->testcase->view               = 'view';
$lang->resource->testcase->edit               = 'edit';
$lang->resource->testcase->delete             = 'delete';
$lang->resource->testcase->confirmStoryChange = 'confirmStoryChange';

/* 测试任务。*/
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

/* 文档视图。*/
$lang->resource->doc->index = 'index';

/* 文档。*/
$lang->resource->doc->index     = 'index';
$lang->resource->doc->browse    = 'browse';
$lang->resource->doc->createLib = 'createLib';
$lang->resource->doc->editLib   = 'editLib';
$lang->resource->doc->deleteLib = 'deleteLib';
$lang->resource->doc->create    = 'create';
$lang->resource->doc->view      = 'view';
$lang->resource->doc->edit      = 'edit';
$lang->resource->doc->delete    = 'delete';
$lang->resource->doc->deleteFile= 'deleteFile';

/* 组织视图。*/
$lang->resource->company->index  = 'index';
$lang->resource->company->browse = 'browse';
$lang->resource->company->edit   = 'edit';

/* 部门。*/
$lang->resource->dept->browse      = 'browse';
$lang->resource->dept->updateOrder = 'updateOrder';
$lang->resource->dept->manageChild = 'manageChild';
$lang->resource->dept->delete      = 'delete';

/* 分组。*/
$lang->resource->group->browse       = 'browse';
$lang->resource->group->create       = 'create';
$lang->resource->group->edit         = 'edit';
$lang->resource->group->copy         = 'copy';
$lang->resource->group->delete       = 'delete';
$lang->resource->group->managePriv   = 'managePriv';
$lang->resource->group->manageMember = 'manageMember';

/* 用户。*/
$lang->resource->user->create = 'create';
$lang->resource->user->view   = 'view';
$lang->resource->user->edit   = 'edit';
$lang->resource->user->delete = 'delete';
$lang->resource->user->todo   = 'todo';
$lang->resource->user->task   = 'task';
$lang->resource->user->bug    = 'bug';
$lang->resource->user->project= 'project';
$lang->resource->user->profile= 'profile';

/* 模块维护。*/
$lang->resource->tree->browse            = 'browse';
$lang->resource->tree->updateOrder       = 'updateOrder';
$lang->resource->tree->manageChild       = 'manageChild';
$lang->resource->tree->edit              = 'edit';
$lang->resource->tree->delete            = 'delete';
$lang->resource->tree->ajaxGetOptionMenu = 'ajaxGetOptionMenu';
$lang->resource->tree->ajaxGetSonModules = 'ajaxGetSonModules';

/* 搜索*/
$lang->resource->search->buildForm    = 'buildForm';
$lang->resource->search->buildQuery   = 'buildQuery';
$lang->resource->search->saveQuery    = 'saveQuery';
$lang->resource->search->deleteQuery  = 'deleteQuery';

/* 后台管理。*/
$lang->resource->admin->index         = 'index';

/* 其他的一些杂项。*/
$lang->resource->api->getModel    = 'getModel';
$lang->resource->file->download   = 'download';
$lang->resource->file->export2CSV = 'export2CSV';
$lang->resource->misc->ping       = 'ping';
$lang->resource->action->trash    = 'trash';
$lang->resource->action->undelete = 'undelete';
