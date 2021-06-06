<?php
/**
 * The execution module zh-cn file of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: zh-cn.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
/* 字段列表。*/
$lang->execution->allExecutions   = '所有' . $lang->executionCommon;
$lang->execution->allExecutionAB  = "所有{$lang->execution->common}";
$lang->execution->id              = $lang->executionCommon . '编号';
$lang->execution->type            = $lang->executionCommon . '类型';
$lang->execution->name            = $lang->executionCommon . '名称';
$lang->execution->code            = $lang->executionCommon . '代号';
$lang->execution->project         = '所属项目';
$lang->execution->execName        = "{$lang->execution->common}名称";
$lang->execution->execCode        = "{$lang->execution->common}代号";
$lang->execution->execType        = "{$lang->execution->common}类型";
$lang->execution->stage           = '阶段';
$lang->execution->pri             = '优先级';
$lang->execution->openedBy        = '由谁创建';
$lang->execution->openedDate      = '创建日期';
$lang->execution->closedBy        = '由谁关闭';
$lang->execution->closedDate      = '关闭日期';
$lang->execution->canceledBy      = '由谁取消';
$lang->execution->canceledDate    = '取消日期';
$lang->execution->begin           = '开始日期';
$lang->execution->end             = '结束日期';
$lang->execution->dateRange       = '起始日期';
$lang->execution->to              = '至';
$lang->execution->days            = '可用工作日';
$lang->execution->day             = '天';
$lang->execution->workHour        = '工时';
$lang->execution->workHourUnit    = 'h';
$lang->execution->totalHours      = '可用工时';
$lang->execution->totalDays       = '可用工日';
$lang->execution->status          = $lang->executionCommon . '状态';
$lang->execution->execStatus      = "{$lang->execution->common}状态";
$lang->execution->subStatus       = '子状态';
$lang->execution->desc            = $lang->executionCommon . '描述';
$lang->execution->execDesc        = "{$lang->execution->common}描述";
$lang->execution->owner           = '负责人';
$lang->execution->PO              = $lang->productCommon . '负责人';
$lang->execution->PM              = $lang->executionCommon . '负责人';
$lang->execution->execPM          = "{$lang->execution->common}负责人";
$lang->execution->QD              = '测试负责人';
$lang->execution->RD              = '发布负责人';
$lang->execution->release         = '发布';
$lang->execution->acl             = '访问控制';
$lang->execution->teamname        = '团队名称';
$lang->execution->order           = $lang->executionCommon . '排序';
$lang->execution->orderAB         = '排序';
$lang->execution->products        = '相关' . $lang->productCommon;
$lang->execution->whitelist       = '白名单';
$lang->execution->addWhitelist    = '添加白名单';
$lang->execution->unbindWhitelist = '删除白名单';
$lang->execution->totalEstimate   = '预计';
$lang->execution->totalConsumed   = '消耗';
$lang->execution->totalLeft       = '剩余';
$lang->execution->progress        = '进度';
$lang->execution->hours           = '预计 %s 消耗 %s 剩余 %s';
$lang->execution->viewBug         = '查看bug';
$lang->execution->noProduct       = "无{$lang->executionCommon}";
$lang->execution->createStory     = "提{$lang->SRCommon}";
$lang->execution->storyTitle      = "{$lang->SRCommon}名称";
$lang->execution->all             = '所有';
$lang->execution->undone          = '未完成';
$lang->execution->unclosed        = '未关闭';
$lang->execution->typeDesc        = "运维{$lang->executionCommon}没有{$lang->SRCommon}、bug、版本、测试功能。";
$lang->execution->mine            = '我负责：';
$lang->execution->involved        = '我参与：';
$lang->execution->other           = '其他：';
$lang->execution->deleted         = '已删除';
$lang->execution->delayed         = '已延期';
$lang->execution->product         = $lang->execution->products;
$lang->execution->readjustTime    = "调整{$lang->executionCommon}起止时间";
$lang->execution->readjustTask    = '顺延任务的起止时间';
$lang->execution->effort          = '日志';
$lang->execution->storyEstimate   = '需求估算';
$lang->execution->newEstimate     = '新一轮估算';
$lang->execution->reestimate      = '重新估算';
$lang->execution->selectRound     = '选择轮次';
$lang->execution->average         = '平均值';
$lang->execution->relatedMember   = '相关成员';
$lang->execution->watermark       = '由禅道导出';
$lang->execution->burnXUnit       = '(日期)';
$lang->execution->burnYUnit       = '(工时)';
$lang->execution->waitTasks       = '待处理';
$lang->execution->viewByUser      = '按用户查看';
$lang->execution->oneProduct      = "阶段只能关联一个{$lang->productCommon}";
$lang->execution->noLinkProduct   = "阶段没有关联{$lang->productCommon}";
$lang->execution->recent          = '近期访问：';
$lang->execution->copyNoExecution = '没有可用的' . $lang->executionCommon . '来复制';
$lang->execution->noTeam          = '暂时没有团队成员';

$lang->execution->start    = "开始";
$lang->execution->activate = "激活";
$lang->execution->putoff   = "延期";
$lang->execution->suspend  = "挂起";
$lang->execution->close    = "关闭";
$lang->execution->export   = "导出";

$lang->execution->endList[7]   = '一星期';
$lang->execution->endList[14]  = '两星期';
$lang->execution->endList[31]  = '一个月';
$lang->execution->endList[62]  = '两个月';
$lang->execution->endList[93]  = '三个月';
$lang->execution->endList[186] = '半年';
$lang->execution->endList[365] = '一年';

$lang->execution->lifeTimeList['short'] = "短期";
$lang->execution->lifeTimeList['long']  = "长期";
$lang->execution->lifeTimeList['ops']   = "运维";

$lang->team = new stdclass();
$lang->team->account    = '用户';
$lang->team->role       = '角色';
$lang->team->join       = '加盟日';
$lang->team->hours      = '可用工时/天';
$lang->team->days       = '可用工日';
$lang->team->totalHours = '总计';

$lang->team->limited            = '受限用户';
$lang->team->limitedList['yes'] = '是';
$lang->team->limitedList['no']  = '否';

$lang->execution->basicInfo = '基本信息';
$lang->execution->otherInfo = '其他信息';

/* 字段取值列表。*/
$lang->execution->statusList['wait']      = '未开始';
$lang->execution->statusList['doing']     = '进行中';
$lang->execution->statusList['suspended'] = '已挂起';
$lang->execution->statusList['closed']    = '已关闭';

global $config;
if($config->systemMode == 'new')
{
    $lang->execution->aclList['private'] = "私有（团队成员和项目负责人、干系人可访问）";
    $lang->execution->aclList['open']    = "继承项目访问权限（能访问当前项目，即可访问）";
}
else
{
    $lang->execution->aclList['private'] = "私有（团队成员和{$lang->executionCommon}负责人可访问）";
    $lang->execution->aclList['open']    = "公开（有{$lang->executionCommon}视图权限即可访问）";
}

$lang->execution->storyPoint = '故事点';

$lang->execution->burnByList['left']       = '按剩余工时查看';
$lang->execution->burnByList['estimate']   = "按计划工时查看";
$lang->execution->burnByList['storyPoint'] = '按故事点查看';

/* 方法列表。*/
$lang->execution->index             = "{$lang->execution->common}主页";
$lang->execution->task              = '任务列表';
$lang->execution->groupTask         = '分组浏览任务';
$lang->execution->story             = "{$lang->SRCommon}列表";
$lang->execution->qa                = '测试仪表盘';
$lang->execution->bug               = 'Bug列表';
$lang->execution->testcase          = '用例列表';
$lang->execution->dynamic           = '动态';
$lang->execution->latestDynamic     = '最新动态';
$lang->execution->build             = '所有版本';
$lang->execution->testtask          = '测试单';
$lang->execution->burn              = '燃尽图';
$lang->execution->computeBurn       = '更新燃尽图';
$lang->execution->burnData          = '燃尽图数据';
$lang->execution->fixFirst          = '修改首天工时';
$lang->execution->team              = '团队成员';
$lang->execution->doc               = '文档列表';
$lang->execution->doclib            = '文档库列表';
$lang->execution->manageProducts    = '关联' . $lang->productCommon;
$lang->execution->linkStory         = "关联{$lang->SRCommon}";
$lang->execution->linkStoryByPlan   = "按照计划关联";
$lang->execution->linkPlan          = "关联计划";
$lang->execution->unlinkStoryTasks  = "未关联{$lang->SRCommon}任务";
$lang->execution->linkedProducts    = '已关联';
$lang->execution->unlinkedProducts  = '未关联';
$lang->execution->view              = "{$lang->execution->common}概况";
$lang->execution->startAction       = "开始{$lang->execution->common}";
$lang->execution->activateAction    = "激活{$lang->execution->common}";
$lang->execution->delayAction       = "延期{$lang->execution->common}";
$lang->execution->suspendAction     = "挂起{$lang->execution->common}";
$lang->execution->closeAction       = "关闭{$lang->execution->common}";
$lang->execution->testtaskAction    = "{$lang->execution->common}测试单";
$lang->execution->teamAction        = "{$lang->execution->common}团队";
$lang->execution->kanbanAction      = "{$lang->execution->common}看板";
$lang->execution->printKanbanAction = "打印看板";
$lang->execution->treeAction        = "{$lang->execution->common}树状图";
$lang->execution->exportAction      = "导出{$lang->execution->common}";
$lang->execution->computeBurnAction = "计算燃尽图";
$lang->execution->create            = "添加{$lang->executionCommon}";
$lang->execution->createExec        = "添加{$lang->execution->common}";
$lang->execution->copyExec          = "复制{$lang->execution->common}";
$lang->execution->copy              = "复制{$lang->executionCommon}";
$lang->execution->delete            = "删除{$lang->executionCommon}";
$lang->execution->deleteAB          = "删除{$lang->execution->common}";
$lang->execution->browse            = "浏览{$lang->execution->common}";
$lang->execution->edit              = "编辑{$lang->executionCommon}";
$lang->execution->editAction        = "编辑{$lang->execution->common}";
$lang->execution->batchEdit         = "编辑";
$lang->execution->batchEditAction   = "批量编辑";
$lang->execution->manageMembers     = '团队管理';
$lang->execution->unlinkMember      = '移除成员';
$lang->execution->unlinkStory       = "移除{$lang->SRCommon}";
$lang->execution->unlinkStoryAB     = "移除{$lang->SRCommon}";
$lang->execution->batchUnlinkStory  = "批量移除{$lang->SRCommon}";
$lang->execution->importTask        = '转入任务';
$lang->execution->importPlanStories = "按计划关联{$lang->SRCommon}";
$lang->execution->importBug         = '导入Bug';
$lang->execution->tree              = '树状图';
$lang->execution->treeTask          = '只看任务';
$lang->execution->treeStory         = "只看{$lang->SRCommon}";
$lang->execution->treeOnlyTask      = '树状图只看任务';
$lang->execution->treeOnlyStory     = "树状图只看{$lang->SRCommon}";
$lang->execution->storyKanban       = "{$lang->SRCommon}看板";
$lang->execution->storySort         = "{$lang->SRCommon}排序";
$lang->execution->importPlanStory   = '创建' . $lang->executionCommon . '成功！\n是否导入计划关联的相关' . $lang->SRCommon . '？';
$lang->execution->iteration         = '版本迭代';
$lang->execution->iterationInfo     = '迭代%s次';
$lang->execution->viewAll           = '查看所有';
$lang->execution->testreport        = '测试报告';

/* 分组浏览。*/
$lang->execution->allTasks     = '所有';
$lang->execution->assignedToMe = '指派给我';
$lang->execution->myInvolved   = '由我参与';

$lang->execution->statusSelects['']             = '更多';
$lang->execution->statusSelects['wait']         = '未开始';
$lang->execution->statusSelects['doing']        = '进行中';
$lang->execution->statusSelects['undone']       = '未完成';
$lang->execution->statusSelects['finishedbyme'] = '我完成';
$lang->execution->statusSelects['done']         = '已完成';
$lang->execution->statusSelects['closed']       = '已关闭';
$lang->execution->statusSelects['cancel']       = '已取消';

$lang->execution->groups['']           = '分组查看';
$lang->execution->groups['story']      = "{$lang->SRCommon}分组";
$lang->execution->groups['status']     = '状态分组';
$lang->execution->groups['pri']        = '优先级分组';
$lang->execution->groups['assignedTo'] = '指派给分组';
$lang->execution->groups['finishedBy'] = '完成者分组';
$lang->execution->groups['closedBy']   = '关闭者分组';
$lang->execution->groups['type']       = '类型分组';

$lang->execution->groupFilter['story']['all']         = '所有';
$lang->execution->groupFilter['story']['linked']      = "已关联{$lang->SRCommon}的任务";
$lang->execution->groupFilter['pri']['all']           = '所有';
$lang->execution->groupFilter['pri']['noset']         = '未设置';
$lang->execution->groupFilter['assignedTo']['undone'] = '未完成';
$lang->execution->groupFilter['assignedTo']['all']    = '所有';

$lang->execution->byQuery = '搜索';

/* 查询条件列表。*/
$lang->execution->allExecution      = "所有{$lang->executionCommon}";
$lang->execution->aboveAllProduct   = "以上所有{$lang->productCommon}";
$lang->execution->aboveAllExecution = "以上所有{$lang->executionCommon}";

/* 页面提示。*/
$lang->execution->linkStoryByPlanTips = "此操作会将所选计划下面的{$lang->SRCommon}全部关联到此{$lang->executionCommon}中";
$lang->execution->selectExecution     = "请选择{$lang->execution->common}";
$lang->execution->beginAndEnd         = '起止时间';
$lang->execution->lblStats            = '工时统计';
$lang->execution->stats               = '可用工时 <strong>%s</strong> 工时，总共预计 <strong>%s</strong> 工时，已经消耗 <strong>%s</strong> 工时，预计剩余 <strong>%s</strong> 工时';
$lang->execution->taskSummary         = "本页共 <strong>%s</strong> 个任务，未开始 <strong>%s</strong>，进行中 <strong>%s</strong>，总预计 <strong>%s</strong> 工时，已消耗 <strong>%s</strong> 工时，剩余 <strong>%s</strong> 工时。";
$lang->execution->pageSummary         = "本页共 <strong>%total%</strong> 个任务，未开始 <strong>%wait%</strong>，进行中 <strong>%doing%</strong>，总预计 <strong>%estimate%</strong> 工时，已消耗 <strong>%consumed%</strong> 工时，剩余 <strong>%left%</strong> 工时。";
$lang->execution->checkedSummary      = "选中 <strong>%total%</strong> 个任务，未开始 <strong>%wait%</strong>，进行中 <strong>%doing%</strong>，总预计 <strong>%estimate%</strong> 工时，已消耗 <strong>%consumed%</strong> 工时，剩余 <strong>%left%</strong> 工时。";
$lang->execution->memberHoursAB       = "<div>%s有 <strong>%s</strong> 工时</div>";
$lang->execution->memberHours         = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">%s可用工时</div><div class="segment-value">%s</div></div></div></div>';
$lang->execution->countSummary        = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">总任务</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">进行中</div><div class="segment-value"><span class="label label-dot label-primary"></span> %s</div></div><div class="segment"><div class="segment-title">未开始</div><div class="segment-value"><span class="label label-dot label-primary muted"></span> %s</div></div></div></div>';
$lang->execution->timeSummary         = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">总预计</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">已消耗</div><div class="segment-value text-red">%s</div></div><div class="segment"><div class="segment-title">剩余</div><div class="segment-value">%s</div></div></div></div>';
$lang->execution->groupSummaryAB      = "<div>总任务 <strong>%s : </strong><span class='text-muted'>未开始</span> %s &nbsp; <span class='text-muted'>进行中</span> %s</div><div>总预计 <strong>%s : </strong><span class='text-muted'>已消耗</span> %s &nbsp; <span class='text-muted'>剩余</span> %s</div>";
$lang->execution->wbs                 = "分解任务";
$lang->execution->batchWBS            = "批量分解";
$lang->execution->howToUpdateBurn     = "<a href='https://api.zentao.net/goto.php?item=burndown&lang=zh-cn' target='_blank' title='如何更新燃尽图？' class='btn btn-link'>帮助 <i class='icon icon-help'></i></a>";
$lang->execution->whyNoStories        = "看起来没有{$lang->SRCommon}可以关联。请检查下{$lang->executionCommon}关联的{$lang->productCommon}中有没有{$lang->SRCommon}，而且要确保它们已经审核通过。";
$lang->execution->productStories      = "{$lang->executionCommon}关联的{$lang->SRCommon}是{$lang->productCommon}{$lang->SRCommon}的子集，并且只有评审通过的{$lang->SRCommon}才能关联。请<a href='%s'>关联{$lang->SRCommon}</a>。";
$lang->execution->haveDraft           = "有%s条草稿状态的{$lang->SRCommon}无法关联到该{$lang->executionCommon}";
$lang->execution->doneExecutions      = '已结束';
$lang->execution->selectDept          = '选择部门';
$lang->execution->selectDeptTitle     = '选择一个部门的成员';
$lang->execution->copyTeam            = '复制团队';
$lang->execution->copyFromTeam        = "复制自{$lang->executionCommon}团队： <strong>%s</strong>";
$lang->execution->noMatched           = "找不到包含'%s'的$lang->executionCommon";
$lang->execution->copyTitle           = "请选择一个{$lang->executionCommon}来复制";
$lang->execution->copyTeamTitle       = "选择一个{$lang->executionCommon}团队来复制";
$lang->execution->copyNoExecution     = "没有可用的{$lang->executionCommon}来复制";
$lang->execution->copyFromExecution   = "复制自{$lang->executionCommon} <strong>%s</strong>";
$lang->execution->cancelCopy          = '取消复制';
$lang->execution->byPeriod            = '按时间段';
$lang->execution->byUser              = '按用户';
$lang->execution->noExecution         = "暂时没有{$lang->executionCommon}。";
$lang->execution->noExecutions        = "暂时没有{$lang->execution->common}。";
$lang->execution->noMembers           = '暂时没有团队成员。';
$lang->execution->workloadTotal       = "工作量占比累计不应当超过100, 当前产品下的工作量之和为%s";
// $lang->execution->linkProjectStoryTip = "(关联{$lang->SRCommon}来源于项目下所关联的{$lang->SRCommon})";
$lang->execution->linkAllStoryTip     = "(项目下还未关联{$lang->SRCommon}，可直接关联该{$lang->execution->common}所关联产品的{$lang->SRCommon})";

/* 交互提示。*/
$lang->execution->confirmDelete             = "您确定删除{$lang->executionCommon}[%s]吗？";
$lang->execution->confirmUnlinkMember       = "您确定从该{$lang->executionCommon}中移除该用户吗？";
$lang->execution->confirmUnlinkStory        = "您确定从该{$lang->executionCommon}中移除该{$lang->SRCommon}吗？";
$lang->execution->confirmUnlinkExecutionStory = "您确定从该项目中移除该{$lang->SRCommon}吗？";
$lang->execution->notAllowedUnlinkStory     = "该{$lang->SRCommon}已经与项目下{$lang->executionCommon}相关联，请从{$lang->executionCommon}中移除后再操作。";
$lang->execution->notAllowRemoveProducts    = "该{$lang->productCommon}中的{$lang->SRCommon}已与该{$lang->executionCommon}进行了关联，请取消关联后再操作。";
$lang->execution->errorNoLinkedProducts     = "该{$lang->executionCommon}没有关联的{$lang->productCommon}，系统将转到{$lang->productCommon}关联页面";
$lang->execution->errorSameProducts         = "{$lang->executionCommon}不能关联多个相同的{$lang->productCommon}。";
$lang->execution->errorBegin                = "{$lang->executionCommon}的开始时间不能小于所属项目的开始时间%s。";
$lang->execution->errorEnd                  = "{$lang->executionCommon}的结束时间不能大于所属项目的结束时间%s。";
$lang->execution->accessDenied              = "您无权访问该{$lang->executionCommon}！";
$lang->execution->tips                      = '提示';
$lang->execution->afterInfo                 = "{$lang->executionCommon}添加成功，您现在可以进行以下操作：";
$lang->execution->setTeam                   = '设置团队';
$lang->execution->linkStory                 = "关联{$lang->SRCommon}";
$lang->execution->createTask                = '创建任务';
$lang->execution->goback                    = "返回任务列表";
$lang->execution->noweekend                 = '去除周末';
$lang->execution->withweekend               = '显示周末';
$lang->execution->interval                  = '间隔';
$lang->execution->fixFirstWithLeft          = '修改剩余工时';
$lang->execution->unfinishedExecution         = "该{$lang->executionCommon}下还有";
$lang->execution->unfinishedTask            = "[%s]个未完成的任务，";
$lang->execution->unresolvedBug             = "[%s]个未解决的bug，";
$lang->execution->projectNotEmpty           = '所属项目不能为空。';

/* 统计。*/
$lang->execution->charts = new stdclass();
$lang->execution->charts->burn = new stdclass();
$lang->execution->charts->burn->graph = new stdclass();
$lang->execution->charts->burn->graph->caption      = "燃尽图";
$lang->execution->charts->burn->graph->xAxisName    = "日期";
$lang->execution->charts->burn->graph->yAxisName    = "HOUR";
$lang->execution->charts->burn->graph->baseFontSize = 12;
$lang->execution->charts->burn->graph->formatNumber = 0;
$lang->execution->charts->burn->graph->animation    = 0;
$lang->execution->charts->burn->graph->rotateNames  = 1;
$lang->execution->charts->burn->graph->showValues   = 0;
$lang->execution->charts->burn->graph->reference    = '参考';
$lang->execution->charts->burn->graph->actuality    = '实际';

$lang->execution->placeholder = new stdclass();
$lang->execution->placeholder->code      = '团队内部的简称';
$lang->execution->placeholder->totalLeft = "{$lang->executionCommon}开始时的总预计工时";

$lang->execution->selectGroup = new stdclass();
$lang->execution->selectGroup->done = '(已结束)';

$lang->execution->orderList['order_asc']  = "{$lang->SRCommon}排序正序";
$lang->execution->orderList['order_desc'] = "{$lang->SRCommon}排序倒序";
$lang->execution->orderList['pri_asc']    = "{$lang->SRCommon}优先级正序";
$lang->execution->orderList['pri_desc']   = "{$lang->SRCommon}优先级倒序";
$lang->execution->orderList['stage_asc']  = "{$lang->SRCommon}阶段正序";
$lang->execution->orderList['stage_desc'] = "{$lang->SRCommon}阶段倒序";

$lang->execution->kanban        = "看板";
$lang->execution->kanbanSetting = "看板设置";
$lang->execution->resetKanban   = "恢复默认";
$lang->execution->printKanban   = "打印看板";
$lang->execution->bugList       = "Bug列表";

$lang->execution->kanbanHideCols   = '看板隐藏已关闭、已取消列';
$lang->execution->kanbanShowOption = '显示折叠信息';
$lang->execution->kanbanColsColor  = '看板列自定义颜色';

$lang->kanbanSetting = new stdclass();
$lang->kanbanSetting->noticeReset     = '是否恢复看板默认设置？';
$lang->kanbanSetting->optionList['0'] = '隐藏';
$lang->kanbanSetting->optionList['1'] = '显示';

$lang->printKanban = new stdclass();
$lang->printKanban->common  = '看板打印';
$lang->printKanban->content = '内容';
$lang->printKanban->print   = '打印';

$lang->printKanban->taskStatus = '状态';

$lang->printKanban->typeList['all']       = '全部';
$lang->printKanban->typeList['increment'] = '增量';

$lang->execution->typeList['']       = '';
$lang->execution->typeList['stage']  = '阶段';
$lang->execution->typeList['sprint'] = $lang->executionCommon;

$lang->execution->featureBar['task']['all']          = $lang->execution->allTasks;
$lang->execution->featureBar['task']['unclosed']     = $lang->execution->unclosed;
$lang->execution->featureBar['task']['assignedtome'] = $lang->execution->assignedToMe;
$lang->execution->featureBar['task']['myinvolved']   = $lang->execution->myInvolved;
$lang->execution->featureBar['task']['delayed']      = '已延期';
$lang->execution->featureBar['task']['needconfirm']  = "{$lang->SRCommon}变更";
$lang->execution->featureBar['task']['status']       = $lang->execution->statusSelects[''];

$lang->execution->featureBar['all']['all']       = $lang->execution->all;
$lang->execution->featureBar['all']['undone']    = $lang->execution->undone;
$lang->execution->featureBar['all']['wait']      = $lang->execution->statusList['wait'];
$lang->execution->featureBar['all']['doing']     = $lang->execution->statusList['doing'];
$lang->execution->featureBar['all']['suspended'] = $lang->execution->statusList['suspended'];
$lang->execution->featureBar['all']['closed']    = $lang->execution->statusList['closed'];

$lang->execution->treeLevel = array();
$lang->execution->treeLevel['all']   = '全部展开';
$lang->execution->treeLevel['root']  = '全部折叠';
$lang->execution->treeLevel['task']  = '全部显示';
$lang->execution->treeLevel['story'] = "只看{$lang->SRCommon}";

$lang->execution->action = new stdclass();
$lang->execution->action->opened  = '$date, 由 <strong>$actor</strong> 创建。$extra' . "\n";
$lang->execution->action->managed = '$date, 由 <strong>$actor</strong> 维护。$extra' . "\n";
$lang->execution->action->edited  = '$date, 由 <strong>$actor</strong> 编辑。$extra' . "\n";
$lang->execution->action->extra   = '相关产品为 %s。';
