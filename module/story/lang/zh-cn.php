<?php
/**
 * The story module zh-cn file of ZenTaoMS.
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
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
$lang->story->common          = '需求';
$lang->story->create          = "新增需求";
$lang->story->edit            = "编辑需求";
$lang->story->change          = "变更";
$lang->story->delete          = "删除需求";
$lang->story->browse          = "需求列表";
$lang->story->view            = "需求详情";
$lang->story->manage          = "操作";
$lang->story->tasks           = "相关任务";
$lang->story->bugs            = "Bug";

$lang->story->confirmDelete         = "您确认删除该需求吗?";
$lang->story->errorFormat           = '需求数据有误';
$lang->story->errorEmptyTitle       = '标题不能为空';
$lang->story->mustChooseResult      = '必须选择评审结果';
$lang->story->mustChoosePreVersion  = '必须选择回溯的版本';
$lang->story->linkStory             = '关联需求';
$lang->story->ajaxGetProjectStories = '接口:获取项目需求列表';
$lang->story->ajaxGetProductStories = '接口:获取产品需求列表';

$lang->story->statusList['']          = '';
$lang->story->statusList['draft']     = '草稿';
$lang->story->statusList['active']    = '激活';
$lang->story->statusList['closed']    = '已关闭';
$lang->story->statusList['changed']   = '已变更';

$lang->story->stageList['']           = '';
$lang->story->stageList['wait']       = '未开始';
$lang->story->stageList['planned']    = '已安排';
$lang->story->stageList['developing'] = '研发中';
$lang->story->stageList['developed']  = '研发完毕';
$lang->story->stageList['testing']    = '测试中';
$lang->story->stageList['tested']     = '测试完毕';
$lang->story->stageList['verified']   = '已验收';
$lang->story->stageList['released']   = '已发布';

$lang->story->reasonList['']           = '';
$lang->story->reasonList['done']       = '完成';
$lang->story->reasonList['subdivided'] = '已细分';
$lang->story->reasonList['duplicate']  = '重复';
$lang->story->reasonList['postponed']  = '延期';
$lang->story->reasonList['willnotdo']  = '不做';
$lang->story->reasonList['cancel']     = '已取消';
//$lang->story->reasonList['isbug']      = '是个Bug';

$lang->story->reviewResultList['']       = '';
$lang->story->reviewResultList['pass']   = '通过';
$lang->story->reviewResultList['clarify']= '需求不明确';
$lang->story->reviewResultList['revert'] = '撤销变更';
$lang->story->reviewResultList['reject'] = '拒绝并关闭';

$lang->story->priList[3]  = '一般';
$lang->story->priList[1]  = '最高';
$lang->story->priList[2]  = '较高';
$lang->story->priList[4]  = '最低';

$lang->story->legendBasicInfo      = '基本信息';
$lang->story->legendLifeTime       = '需求的一生';
$lang->story->legendRelated        = '相关信息';
$lang->story->legendMailto         = '抄送给';
$lang->story->legendAttatch        = '附件';
$lang->story->legendProjectAndTask = '项目任务';
$lang->story->legendLinkStories    = '相关需求';
$lang->story->legendChildStories   = '细分需求';
$lang->story->legendSpec           = '需求描述';
$lang->story->legendHistory        = '历史记录';
$lang->story->legendVersion        = '历史版本';
$lang->story->legendMisc           = '其他相关';

$lang->story->review = '评审';
$lang->story->close  = '关闭';

$lang->story->id             = '编号';
$lang->story->product        = '所属产品';
$lang->story->module         = '所属模块';
$lang->story->replease       = '发布计划';
$lang->story->bug            = '相关bug';
$lang->story->title          = '需求名称';
$lang->story->spec           = '需求描述';
$lang->story->specNote       = "建议的需求模板：<br />作为一个<某种类型的用户><br />我要<达成某些目的><br />我这么做的原因是<开发的价值>";
$lang->story->type           = '需求类型 ';
$lang->story->pri            = '优先级';
$lang->story->estimate       = '预计工时';
$lang->story->status         = '当前状态';
$lang->story->stage          = '所处阶段';
$lang->story->mailto         = '抄送给';
$lang->story->openedBy       = '由谁创建';
$lang->story->openedDate     = '创建日期';
$lang->story->assignedTo     = '指派给';
$lang->story->assignedDate   = '指派日期';
$lang->story->lastEditedBy   = '最后修改';
$lang->story->lastEditedDate = '最后修改日期';
$lang->story->lastEdited     = '最后修改';
$lang->story->closedBy       = '由谁关闭';
$lang->story->closedDate     = '关闭日期';
$lang->story->closedReason   = '关闭原因';
$lang->story->reviewedBy     = '由谁评审';
$lang->story->reviewedDate   = '评审时间';
$lang->story->version        = '版本号';
$lang->story->project        = '所属项目';
$lang->story->plan           = '所属计划';
$lang->story->comment        = '备注';
$lang->story->linkStories    = '相关需求';
$lang->story->childStories   = '细分需求';
$lang->story->duplicateStory = '重复需求';
$lang->story->reviewResult   = '评审结果';
$lang->story->preVersion     = '之前版本';

$lang->story->action->reviewed = array('main' => '$date, 由 <strong>$actor</strong> 评审，结果为 <strong>$extra</strong>。', 'extra' => $lang->story->reviewResultList);
$lang->story->action->closed   = array('main' => '$date, 由 <strong>$actor</strong> 关闭，原因为 <strong>$extra</strong>。', 'extra' => $lang->story->reasonList);
