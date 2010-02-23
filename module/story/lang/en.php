<?php
/**
 * The story module english file of ZenTaoMS.
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
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
$lang->story->browse    = "Story表";
$lang->story->create    = "Add Story";
$lang->story->change    = "Change";
$lang->story->review    = 'Review';
$lang->story->edit      = "Edit";
$lang->story->close     = 'Close';
$lang->story->activate  = 'Activate';
$lang->story->delete    = "Delete";
$lang->story->view      = "View";
$lang->story->tasks     = "Tasks";
$lang->story->bugs      = "Bug";
$lang->story->linkStory = 'Link Story';

$lang->story->common         = 'Story';
$lang->story->id             = 'ID';
$lang->story->product        = 'Product';
$lang->story->module         = 'Module';
$lang->story->release        = 'Release';
$lang->story->bug            = 'Bug';
$lang->story->title          = 'Title';
$lang->story->spec           = 'Spec';
$lang->story->type           = 'Type';
$lang->story->pri            = 'PRI';
$lang->story->estimate       = 'Estimate';
$lang->story->status         = 'Status';
$lang->story->stage          = 'Stage';
$lang->story->mailto         = 'CC';
$lang->story->openedBy       = 'Opened By';
$lang->story->openedDate     = 'Opened Date';
$lang->story->assignedTo     = 'Assigned To';
$lang->story->assignedDate   = 'Assigned Date';
$lang->story->lastEditedBy   = 'Last Edited By';
$lang->story->lastEditedDate = 'Last Edited Date';
$lang->story->lastEdited     = 'Laste Edited';
$lang->story->closedBy       = 'Closed By';
$lang->story->closedDate     = 'Closed Date';
$lang->story->closedReason   = 'Closed Reason';
$lang->story->rejectedReason = 'Rejected Reason';
$lang->story->reviewedBy     = 'Reviewed By';
$lang->story->reviewedDate   = 'Reviewed Date';
$lang->story->version        = 'Version';
$lang->story->project        = 'Project';
$lang->story->plan           = 'Plan';
$lang->story->comment        = 'Comment';
$lang->story->linkStories    = 'Related Story';
$lang->story->childStories   = 'Child Story';
$lang->story->duplicateStory = 'Duplicate Story';
$lang->story->reviewResult   = 'Review Result';
$lang->story->preVersion     = 'Previous Version';

$lang->story->statusList['']          = '';
$lang->story->statusList['draft']     = 'Draft';
$lang->story->statusList['active']    = 'Active';
$lang->story->statusList['closed']    = 'Closed';
$lang->story->statusList['changed']   = 'Changed';

$lang->story->stageList['']           = '';
$lang->story->stageList['wait']       = '未开始';
$lang->story->stageList['planned']    = '已计划';
$lang->story->stageList['projected']  = '已立项';
$lang->story->stageList['developing'] = '研发中';
$lang->story->stageList['developed']  = '研发完毕';
$lang->story->stageList['testing']    = '测试中';
$lang->story->stageList['tested']     = '测试完毕';
$lang->story->stageList['verified']   = '已验收';
$lang->story->stageList['released']   = '已发布';

$lang->story->reasonList['']           = '';
$lang->story->reasonList['done']       = '已完成';
$lang->story->reasonList['subdivided'] = '已细分';
$lang->story->reasonList['duplicate']  = '重复';
$lang->story->reasonList['postponed']  = '延期';
$lang->story->reasonList['willnotdo']  = '不做';
$lang->story->reasonList['cancel']     = '已取消';
$lang->story->reasonList['bydesign']   = '设计如此';
//$lang->story->reasonList['isbug']      = '是个Bug';

$lang->story->reviewResultList['']       = '';
$lang->story->reviewResultList['pass']   = '确认通过';
$lang->story->reviewResultList['revert'] = '撤销变更';
$lang->story->reviewResultList['clarify']= '有待明确';
$lang->story->reviewResultList['reject'] = '拒绝';

$lang->story->priList[3]  = '一般';
$lang->story->priList[1]  = '最高';
$lang->story->priList[2]  = '较高';
$lang->story->priList[4]  = '最低';

$lang->story->legendBasicInfo      = '基本信息';
$lang->story->legendLifeTime       = 'Story的一生';
$lang->story->legendRelated        = '相关信息';
$lang->story->legendMailto         = '抄送给';
$lang->story->legendAttatch        = '附件';
$lang->story->legendProjectAndTask = '项目任务';
$lang->story->legendLinkStories    = '相关Story';
$lang->story->legendChildStories   = '细分Story';
$lang->story->legendSpec           = 'Story描述';
$lang->story->legendHistory        = '历史记录';
$lang->story->legendVersion        = '历史版本';
$lang->story->legendMisc           = '其他相关';

$lang->story->specTemplate          = "建议参考的模板：<br />作为一名<<i class='red'>某种类型的用户</i>><br />我希望<<i class='red'>达成某些目的</i>><br />这样可以<<i class='red'>开发的价值</i>>";
$lang->story->needNotReview         = '不需要评审';
$lang->story->confirmDelete         = "您确认删除该Story吗?";
$lang->story->errorFormat           = 'Story数据有误';
$lang->story->errorEmptyTitle       = '标题不能为空';
$lang->story->mustChooseResult      = '必须选择评审结果';
$lang->story->mustChoosePreVersion  = '必须选择回溯的版本';
$lang->story->ajaxGetProjectStories = '接口:获取项目Story列表';
$lang->story->ajaxGetProductStories = '接口:获取产品Story列表';

$lang->story->action->reviewed = array('main' => '$date, 由 <strong>$actor</strong> 记录评审结果，结果为 <strong>$extra</strong>。', 'extra' => $lang->story->reviewResultList);
$lang->story->action->closed   = array('main' => '$date, 由 <strong>$actor</strong> 关闭，原因为 <strong>$extra</strong>。', 'extra' => $lang->story->reasonList);
