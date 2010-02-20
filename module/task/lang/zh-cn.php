<?php
/**
 * The task module zh-cn file of ZenTaoMS.
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
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
$lang->task->index     = "任务一览";
$lang->task->create    = "新增任务";
$lang->task->import    = "导入之前未完任务";
$lang->task->edit      = "更新任务";
$lang->task->delete    = "删除任务";
$lang->task->view      = "查看任务";

$lang->task->common       = '任务';
$lang->task->id           = '编号';
$lang->task->project      = '所属项目';
$lang->task->story        = '相关需求';
$lang->task->storyVersion = '需求版本';
$lang->task->name         = '任务名称';
$lang->task->type         = '任务类型';
$lang->task->pri          = '优先级';
$lang->task->owner        = '指派给';
$lang->task->estimate     = '最初预计';
$lang->task->left         = '预计剩余';
$lang->task->consumed     = '已消耗';
$lang->task->status       = '任务状态';
$lang->task->desc         = '任务描述';
$lang->task->statusCustom = '状态排序';

$lang->task->statusList->wait  = '未开始';
$lang->task->statusList->doing = '进行中';
$lang->task->statusList->done  = '已完成';
$lang->task->statusList->cancel= '已取消';

$lang->task->typeList[''] = '';
$lang->task->typeList['design'] = '设计';
$lang->task->typeList['devel']  = '开发';
$lang->task->typeList['test']   = '测试';
$lang->task->typeList['study']  = '研究';
$lang->task->typeList['discuss']= '讨论';
$lang->task->typeList['ui']     = '界面';
$lang->task->typeList['misc']   = '其他';

$lang->task->priList[3]  = '一般';
$lang->task->priList[1]  = '最高';
$lang->task->priList[2]  = '较高';
$lang->task->priList[4]  = '最低';

$lang->task->afterChoices['continueAdding'] = '继续为该需求添加任务';
$lang->task->afterChoices['toTastList']     = '返回任务列表';
$lang->task->afterChoices['toStoryList']    = '返回需求列表';

$lang->task->buttonEdit       = '编辑';
$lang->task->buttonBackToList = '返回';

$lang->task->legendBasic  = '基本信息';
$lang->task->legendDesc   = '任务描述';
$lang->task->legendAction = '操作';

$lang->task->ajaxGetUserTasks    = "接口:我的任务";
$lang->task->ajaxGetProjectTasks = "接口:项目任务";
$lang->task->confirmDelete       = "您确定要删除这个任务吗？";
$lang->task->copyStoryTitle      = "同需求";
$lang->task->afterSubmit         = "添加之后";
$lang->task->successSaved        = "成功添加，";
