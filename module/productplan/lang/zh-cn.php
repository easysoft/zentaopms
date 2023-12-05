<?php
/**
 * The productplan module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id: zh-cn.php 4659 2013-04-17 06:45:08Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->productplan->common     = $lang->productCommon . '计划';
$lang->productplan->browse     = "计划列表";
$lang->productplan->index      = "计划列表";
$lang->productplan->create     = "创建计划";
$lang->productplan->edit       = "编辑计划";
$lang->productplan->delete     = "删除计划";
$lang->productplan->start      = "开始计划";
$lang->productplan->finish     = "完成计划";
$lang->productplan->close      = "关闭计划";
$lang->productplan->activate   = "激活计划";
$lang->productplan->startAB    = "开始";
$lang->productplan->finishAB   = "完成";
$lang->productplan->closeAB    = "关闭";
$lang->productplan->activateAB = "激活";
$lang->productplan->view       = "计划详情";
$lang->productplan->bugSummary = "本页共 <strong>%s</strong> 个Bug";
$lang->productplan->basicInfo  = '基本信息';
$lang->productplan->batchEdit  = '批量编辑';
$lang->productplan->project    = $lang->projectCommon;
$lang->productplan->plan       = '计划';
$lang->productplan->allAB      = '所有';
$lang->productplan->to         = '至';
$lang->productplan->more       = '更多操作';
$lang->productplan->comment    = '备注';
$lang->productplan->storyPoint = '故事点';

$lang->productplan->batchEditAction   = '批量编辑计划';
$lang->productplan->batchUnlink       = "批量移除";
$lang->productplan->batchClose        = "批量关闭";
$lang->productplan->batchChangeStatus = "批量修改状态";
$lang->productplan->unlinkAB          = "移除";
$lang->productplan->linkStory         = "关联{$lang->SRCommon}";
$lang->productplan->unlinkStory       = "移除{$lang->SRCommon}";
$lang->productplan->unlinkStoryAB     = "移除";
$lang->productplan->batchUnlinkStory  = "批量移除{$lang->SRCommon}";
$lang->productplan->linkedStories     = $lang->SRCommon;
$lang->productplan->unlinkedStories   = "未关联{$lang->SRCommon}";
$lang->productplan->updateOrder       = '排序';
$lang->productplan->createChildren    = "创建子计划";
$lang->productplan->createExecution   = "创建{$lang->execution->common}";
$lang->productplan->list              = '列表';
$lang->productplan->kanban            = '看板';

$lang->productplan->linkBug          = "关联Bug";
$lang->productplan->unlinkBug        = "移除Bug";
$lang->productplan->batchUnlinkBug   = "批量移除Bug";
$lang->productplan->linkedBugs       = 'Bug';
$lang->productplan->unlinkedBugs     = '未关联Bug';
$lang->productplan->unexpired        = "未过期";
$lang->productplan->noAssigned       = '未指派';
$lang->productplan->all              = "所有计划";
$lang->productplan->setDate          = "设置计划起止时间";
$lang->productplan->expired          = "已过期";
$lang->productplan->closedReason     = "关闭原因";

$lang->productplan->confirmDelete      = "您确认删除该计划吗？";
$lang->productplan->confirmUnlinkStory = "您确认移除该{$lang->SRCommon}吗？";
$lang->productplan->confirmUnlinkBug   = "您确认移除该Bug吗？";
$lang->productplan->confirmStart       = "您确认开始该计划吗？";
$lang->productplan->confirmFinish      = "您确认完成该计划吗？";
$lang->productplan->confirmClose       = "您确认关闭该计划吗？";
$lang->productplan->confirmActivate    = "您确认激活该计划吗？";
$lang->productplan->noPlan             = "暂时没有计划。";
$lang->productplan->cannotDeleteParent = "不能删除父计划";
$lang->productplan->selectProjects     = "请选择所属" . $lang->projectCommon;
$lang->productplan->projectNotEmpty    = "所属{$lang->projectCommon}不能为空。";
$lang->productplan->nextStep           = "下一步";
$lang->productplan->summary            = "本页共 <strong>%s</strong> 个计划，父计划 <strong>%s</strong>，子计划 <strong>%s</strong>，独立计划 <strong>%s</strong>。";
$lang->productplan->checkedSummary     = "共选中 <strong>%total%</strong> 个计划，父计划 <strong>%parent%</strong>，子计划 <strong>%child%</strong>，独立计划 <strong>%independent%</strong>。";
$lang->productplan->confirmChangePlan  = "分支『%s』解除关联后，分支下的%s个{$lang->SRCommon}和%s个Bug将同步从计划中移除，是否解除？";
$lang->productplan->confirmRemoveStory = "分支『%s』解除关联后，分支下的%s个{$lang->SRCommon}将同步从计划中移除，是否解除？";
$lang->productplan->confirmRemoveBug   = "分支『%s』解除关联后，分支下的%s个Bug将同步从计划中移除，是否解除？";

$lang->productplan->id         = '编号';
$lang->productplan->product    = $lang->productCommon;
$lang->productplan->branch     = '平台/分支';
$lang->productplan->title      = '名称';
$lang->productplan->desc       = '描述';
$lang->productplan->begin      = '开始日期';
$lang->productplan->end        = '结束日期';
$lang->productplan->status     = '状态';
$lang->productplan->last       = "上次计划";
$lang->productplan->future     = '待定';
$lang->productplan->stories    = "{$lang->SRCommon}数";
$lang->productplan->bugs       = 'Bug数';
$lang->productplan->hour       = $lang->hourCommon;
$lang->productplan->execution  = $lang->execution->common;
$lang->productplan->parent     = "父计划";
$lang->productplan->parentAB   = "父";
$lang->productplan->children   = "子计划";
$lang->productplan->childrenAB = "子";
$lang->productplan->order      = "排序";
$lang->productplan->deleted    = "已删除";
$lang->productplan->mailto     = "抄送给";
$lang->productplan->planStatus = "状态";

$lang->productplan->statusList['wait']   = '未开始';
$lang->productplan->statusList['doing']  = '进行中';
$lang->productplan->statusList['done']   = '已完成';
$lang->productplan->statusList['closed'] = '已关闭';

$lang->productplan->closedReasonList['done']   = '已完成';
$lang->productplan->closedReasonList['cancel'] = '已取消';

$lang->productplan->parentActionList['startedbychild']   = '系统判断由于子计划 <strong>开始</strong> ，将计划状态置为 <strong>进行中</strong> 。';
$lang->productplan->parentActionList['finishedbychild']  = '系统判断由于子计划 <strong>全部完成</strong> ，将计划状态置为 <strong>已完成</strong> 。';
$lang->productplan->parentActionList['closedbychild']    = '系统判断由于子计划 <strong>全部关闭</strong> ，将计划状态置为 <strong>已关闭</strong> 。';
$lang->productplan->parentActionList['activatedbychild'] = '系统判断由于子计划 <strong>激活</strong> ，将计划状态置为 <strong>进行中</strong> 。';
$lang->productplan->parentActionList['createchild']      = '系统判断由于 <strong>创建</strong> 子计划 ，将计划状态置为 <strong>进行中</strong> 。';

$lang->productplan->endList[7]   = '一星期';
$lang->productplan->endList[14]  = '两星期';
$lang->productplan->endList[31]  = '一个月';
$lang->productplan->endList[62]  = '两个月';
$lang->productplan->endList[93]  = '三个月';
$lang->productplan->endList[186] = '半年';
$lang->productplan->endList[365] = '一年';

$lang->productplan->errorNoTitle         = 'ID %s 标题不能为空';
$lang->productplan->errorNoBegin         = 'ID %s 开始时间不能为空';
$lang->productplan->errorNoEnd           = 'ID %s 结束时间不能为空';
$lang->productplan->beginGeEnd           = 'ID %s 开始时间不能大于结束时间';
$lang->productplan->beginLessThanParent  = "父计划的开始日期：%s，开始日期不能小于父计划的开始日期";
$lang->productplan->endGreatThanParent   = "父计划的完成日期：%s，完成日期不能大于父计划的完成日期";
$lang->productplan->beginGreaterChild    = "子计划的开始日期：%s，开始日期不能大于子计划的开始日期";
$lang->productplan->endLessThanChild     = "子计划的完成日期：%s，完成日期不能小于子计划的完成日期";
$lang->productplan->noLinkedProject      = "当前{$lang->productCommon}还未关联{$lang->projectCommon}，请进入{$lang->productCommon}的{$lang->projectCommon}列表关联或创建一个{$lang->projectCommon}";
$lang->productplan->enterProjectList     = "进入{$lang->productCommon}的{$lang->projectCommon}列表";
$lang->productplan->beginGreaterChildTip = "父计划[%s]的开始日期：%s，不能大于子计划的开始日期: %s";
$lang->productplan->endLessThanChildTip    = "父计划[%s]的完成日期：%s，不能小于子计划的完成日期: %s";
$lang->productplan->beginLessThanParentTip = "子计划[%s]的开始日期：%s，不能小于父计划的开始日期: %s";
$lang->productplan->endGreatThanParentTip  = "子计划[%s]的完成日期：%s，不能大于父计划的完成日期: %s";
$lang->productplan->diffBranchesTip      = "父计划的@branch@『%s』未被子计划关联，对应@branch@的需求和bug将自动从计划中移除，是否保存？";
$lang->productplan->deleteBranchTip      = "@branch@『%s』被子计划关联，无法修改。";

$lang->productplan->featureBar['browse']['all']    = '全部';
$lang->productplan->featureBar['browse']['undone'] = '未完成';
$lang->productplan->featureBar['browse']['wait']   = '未开始';
$lang->productplan->featureBar['browse']['doing']  = '进行中';
$lang->productplan->featureBar['browse']['done']   = '已完成';
$lang->productplan->featureBar['browse']['closed'] = '已关闭';

$lang->productplan->orderList['begin_desc'] = '计划开始时间倒序';
$lang->productplan->orderList['begin_asc']  = '计划开始时间正序';
$lang->productplan->orderList['title_desc'] = '计划名称倒序';
$lang->productplan->orderList['title_asc']  = '计划名称正序';

$lang->productplan->action = new stdclass();
$lang->productplan->action->changebychild = array('main' => '$date, $extra', 'extra' => 'parentActionList');
