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
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     group
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
$lang->group->common       = '分组';
$lang->group->browse       = '浏览分组';
$lang->group->create       = '新增分组';
$lang->group->edit         = '编辑分组';
$lang->group->delete       = '删除分组';
$lang->group->managePriv   = '权限维护';
$lang->group->manageMember = '成员维护';
$lang->group->linkMember   = '关联用户';
$lang->group->unlinkMember = '移除用户';
$lang->group->confirmDelete= '您确定删除该用户分组吗？';
$lang->group->successSaved = '成功保存';

$lang->group->id    = '编号';
$lang->group->name  = '分组名称';
$lang->group->desc  = '分组描述';
$lang->group->users = '用户列表';
$lang->group->module= '模块';
$lang->group->method= '方法';
$lang->group->priv  = '权限';

/* 所有resource列表。*/
$lang->resource->index->index = 'index';
$lang->resource->index->ping  = 'ping';

$lang->resource->my->index       = 'index';
$lang->resource->my->todo        = 'todo';
$lang->resource->my->task        = 'task';
$lang->resource->my->bug         = 'bug';
$lang->resource->my->project     = 'project';
$lang->resource->my->profile     = 'profile';
$lang->resource->my->editProfile = 'editProfile';

$lang->resource->todo->create    = 'create';
$lang->resource->todo->edit      = 'edit';
$lang->resource->todo->delete    = 'delete';
$lang->resource->todo->mark      = 'mark';
$lang->resource->todo->import2Today = 'import2Today';

$lang->resource->product->index  = 'index';
$lang->resource->product->browse = 'browse';
$lang->resource->product->create = 'create';
$lang->resource->product->edit   = 'edit';
$lang->resource->product->delete = 'delete';
$lang->resource->product->ajaxGetProjects = 'ajaxGetProjects';

$lang->resource->story->create  = 'create';
$lang->resource->story->edit    = 'edit';
$lang->resource->story->delete  = 'delete';
$lang->resource->story->view    = 'view';
$lang->resource->story->tasks   = 'tasks';
$lang->resource->story->ajaxGetProjectStories = 'ajaxGetProjectStories';
$lang->resource->story->ajaxGetProductStories = 'ajaxGetProductStories';

$lang->resource->project->index  = 'index';
$lang->resource->project->view   = 'view';
$lang->resource->project->browse = 'browse';
$lang->resource->project->create = 'create';
$lang->resource->project->edit   = 'edit';
$lang->resource->project->delete = 'delete';
$lang->resource->project->task   = 'task';
$lang->resource->project->story  = 'story';
$lang->resource->project->bug    = 'bug';
$lang->resource->project->burn   = 'burn';
$lang->resource->project->burnData = 'burnData';
$lang->resource->project->team   = 'team';
$lang->resource->project->manageProducts = 'manageProducts';
$lang->resource->project->manageChilds   = 'manageChilds';
$lang->resource->project->manageMembers  = 'manageMembers';
$lang->resource->project->unlinkMember   = 'unlinkMember';
$lang->resource->project->linkStory      = 'linkStory';
$lang->resource->project->unlinkStory    = 'unlinkStory';

$lang->resource->task->create = 'create';
$lang->resource->task->edit   = 'edit';
$lang->resource->task->delete = 'delete';
$lang->resource->task->view   = 'view';
$lang->resource->task->ajaxGetUserTasks    = 'ajaxGetUserTasks';
$lang->resource->task->ajaxGetProjectTasks = 'ajaxGetProjectTasks';

$lang->resource->qa->index = 'index';

$lang->resource->bug->index   = 'index';
$lang->resource->bug->browse  = 'browse';
$lang->resource->bug->create  = 'create';
$lang->resource->bug->view    = 'view';
$lang->resource->bug->edit    = 'edit';
$lang->resource->bug->resolve = 'resolve';
$lang->resource->bug->activate= 'activate';
$lang->resource->bug->close   = 'close';
$lang->resource->bug->ajaxGetUserBugs = 'ajaxGetUserBugs';

$lang->resource->testcase->index   = 'index';
$lang->resource->testcase->browse  = 'browse';
$lang->resource->testcase->create  = 'create';
$lang->resource->testcase->view    = 'view';
$lang->resource->testcase->edit    = 'edit';

$lang->resource->company->index  = 'index';
$lang->resource->company->browse = 'browse';
$lang->resource->company->create = 'create';
$lang->resource->company->edit   = 'edit';
$lang->resource->company->delete = 'delete';

$lang->resource->dept->browse      = 'browse';
$lang->resource->dept->updateOrder = 'updateOrder';
$lang->resource->dept->manageChild = 'manageChild';
$lang->resource->dept->delete      = 'delete';

$lang->resource->group->browse       = 'browse';
$lang->resource->group->create       = 'create';
$lang->resource->group->edit         = 'edit';
$lang->resource->group->delete       = 'delete';
$lang->resource->group->managePriv   = 'managePriv';
$lang->resource->group->manageMember = 'manageMember';

$lang->resource->user->create = 'create';
$lang->resource->user->view   = 'view';
$lang->resource->user->edit   = 'edit';
$lang->resource->user->delete = 'delete';
$lang->resource->user->todo   = 'todo';
$lang->resource->user->task   = 'task';
$lang->resource->user->bug    = 'bug';
$lang->resource->user->project= 'project';
$lang->resource->user->profile= 'profile';

$lang->resource->tree->browse            = 'browse';
$lang->resource->tree->updateOrder       = 'updateOrder';
$lang->resource->tree->manageChild       = 'manageChild';
$lang->resource->tree->delete            = 'delete';
$lang->resource->tree->ajaxGetOptionMenu = 'ajaxGetOptionMenu';

$lang->resource->search->buildForm    = 'buildForm';
$lang->resource->search->buildQuery   = 'buildQuery';

$lang->resource->admin->index         = 'index';
$lang->resource->admin->browseCompany = 'browseCompany';
$lang->resource->admin->browseUser    = 'browseUser';
$lang->resource->admin->browseGroup   = 'browseGroup';
