<?php
/**
 * The execution module zh-cn file of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: zh-cn.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
/* 字段列表。*/
$lang->execution->allExecutions       = '所有' . $lang->execution->common;
$lang->execution->allExecutionAB      = "{$lang->execution->common}列表";
$lang->execution->id                  = $lang->executionCommon . '编号';
$lang->execution->type                = $lang->executionCommon . '类型';
$lang->execution->name                = $lang->executionCommon . '名称';
$lang->execution->code                = $lang->executionCommon . '代号';
$lang->execution->projectName         = '所属' . $lang->projectCommon;
$lang->execution->project             = '所属' . $lang->projectCommon;
$lang->execution->execId              = "{$lang->execution->common}编号";
$lang->execution->execName            = "{$lang->execution->common}名称";
$lang->execution->execCode            = "{$lang->execution->common}代号";
$lang->execution->execType            = "{$lang->execution->common}类型";
$lang->execution->lifetime            = $lang->projectCommon . '周期';
$lang->execution->attribute           = '阶段类型';
$lang->execution->percent             = '工作量占比';
$lang->execution->milestone           = '里程碑';
$lang->execution->parent              = '所属' . $lang->projectCommon;
$lang->execution->path                = '路径';
$lang->execution->grade               = '层级';
$lang->execution->output              = '输出';
$lang->execution->version             = '版本';
$lang->execution->parentVersion       = '父版本';
$lang->execution->planDuration        = '计划周期天数';
$lang->execution->realDuration        = '实际周期天数';
$lang->execution->openedVersion       = '创建版本';
$lang->execution->lastEditedBy        = '最后编辑人';
$lang->execution->lastEditedDate      = '最后编辑日期';
$lang->execution->suspendedDate       = '暂停日期';
$lang->execution->vision              = '界面';
$lang->execution->displayCards        = '每列最大卡片数';
$lang->execution->fluidBoard          = '列宽度';
$lang->execution->stage               = '阶段';
$lang->execution->pri                 = '优先级';
$lang->execution->openedBy            = '由谁创建';
$lang->execution->openedDate          = '创建日期';
$lang->execution->closedBy            = '由谁关闭';
$lang->execution->closedDate          = '关闭日期';
$lang->execution->canceledBy          = '由谁取消';
$lang->execution->canceledDate        = '取消日期';
$lang->execution->begin               = '计划开始';
$lang->execution->end                 = '计划完成';
$lang->execution->dateRange           = '计划起止日期';
$lang->execution->realBeganAB         = '实际开始';
$lang->execution->realEndAB           = '实际完成';
$lang->execution->teamCount           = '人数';
$lang->execution->realBegan           = '实际开始日期';
$lang->execution->realEnd             = '实际完成日期';
$lang->execution->to                  = '至';
$lang->execution->days                = '可用工作日';
$lang->execution->day                 = '天';
$lang->execution->workHour            = '工时';
$lang->execution->workHourUnit        = 'h';
$lang->execution->totalHours          = '可用工时';
$lang->execution->totalDays           = '可用工日';
$lang->execution->status              = $lang->executionCommon . '状态';
$lang->execution->execStatus          = "{$lang->execution->common}状态";
$lang->execution->subStatus           = '子状态';
$lang->execution->desc                = $lang->executionCommon . '描述';
$lang->execution->execDesc            = "{$lang->execution->common}描述";
$lang->execution->owner               = '负责人';
$lang->execution->PO                  = $lang->productCommon . '负责人';
$lang->execution->PM                  = $lang->executionCommon . '负责人';
$lang->execution->execPM              = "{$lang->execution->common}负责人";
$lang->execution->QD                  = '测试负责人';
$lang->execution->RD                  = '发布负责人';
$lang->execution->release             = '发布';
$lang->execution->acl                 = '访问控制';
$lang->execution->auth                = '权限控制';
$lang->execution->teamName            = '团队名称';
$lang->execution->teamSetting         = '团队设置';
$lang->execution->updateOrder         = '排序';
$lang->execution->order               = $lang->executionCommon . '排序';
$lang->execution->orderAB             = '排序';
$lang->execution->products            = '相关' . $lang->productCommon;
$lang->execution->whitelist           = '白名单';
$lang->execution->addWhitelist        = '添加白名单';
$lang->execution->unbindWhitelist     = '移除白名单';
$lang->execution->totalEstimate       = '预计';
$lang->execution->totalConsumed       = '消耗';
$lang->execution->totalLeft           = '剩余';
$lang->execution->progress            = '进度';
$lang->execution->hours               = '预计 %s 消耗 %s 剩余 %s';
$lang->execution->viewBug             = '查看bug';
$lang->execution->noProduct           = "无{$lang->executionCommon}";
$lang->execution->createStory         = "提{$lang->SRCommon}";
$lang->execution->storyTitle          = "{$lang->SRCommon}名称";
$lang->execution->storyView           = "{$lang->SRCommon}详情";
$lang->execution->all                 = '所有';
$lang->execution->undone              = '未完成';
$lang->execution->unclosed            = '未关闭';
$lang->execution->closedExecution     = '已关闭的执行';
$lang->execution->typeDesc            = "运维{$lang->executionCommon}没有{$lang->SRCommon}、bug、版本、测试功能。";
$lang->execution->mine                = '我负责：';
$lang->execution->involved            = '我参与';
$lang->execution->other               = '其他';
$lang->execution->deleted             = '已删除';
$lang->execution->delayed             = '已延期';
$lang->execution->product             = $lang->execution->products;
$lang->execution->readjustTime        = "调整{$lang->executionCommon}起止时间";
$lang->execution->readjustTask        = '顺延任务的起止时间';
$lang->execution->effort              = '工时';
$lang->execution->storyEstimate       = '需求估算';
$lang->execution->newEstimate         = '新一轮估算';
$lang->execution->reestimate          = '重新估算';
$lang->execution->selectRound         = '选择轮次';
$lang->execution->average             = '平均值';
$lang->execution->relatedMember       = '相关成员';
$lang->execution->member              = '成员';
$lang->execution->watermark           = '由禅道导出';
$lang->execution->burnXUnit           = '(日期)';
$lang->execution->burnYUnit           = '(工时)';
$lang->execution->count               = '(数量)';
$lang->execution->waitTasks           = '待处理';
$lang->execution->viewByUser          = '按用户查看';
$lang->execution->oneProduct          = "阶段只能关联一个{$lang->productCommon}";
$lang->execution->noLinkProduct       = "关联{$lang->productCommon}不能为空！";
$lang->execution->recent              = '近期访问：';
$lang->execution->copyNoExecution     = '没有可用的' . $lang->executionCommon . '来复制';
$lang->execution->noTeam              = '暂时没有团队成员';
$lang->execution->or                  = '或';
$lang->execution->selectProject       = '请选择' . $lang->projectCommon;
$lang->execution->unfoldClosed        = '展开已结束';
$lang->execution->editName            = '编辑名称';
$lang->execution->setWIP              = '在制品数量设置（WIP）';
$lang->execution->sortColumn          = '看板列卡片排序';
$lang->execution->batchCreateStory    = "批量新建{$lang->SRCommon}";
$lang->execution->batchCreateTask     = '批量建任务';
$lang->execution->kanbanNoLinkProduct = "看板没有关联{$lang->productCommon}";
$lang->execution->myTask              = "我的任务";
$lang->execution->list                = '列表';
$lang->execution->allProject          = '全部' . $lang->projectCommon;
$lang->execution->method              = '管理方法';
$lang->execution->sameAsParent        = "同父阶段";
$lang->execution->selectStoryPlan     = '选择计划';

/* Fields of zt_team. */
$lang->execution->root          = '源ID';
$lang->execution->estimate      = '预计';
$lang->execution->estimateHours = '预计工时';
$lang->execution->consumed      = '消耗';
$lang->execution->consumedHours = '消耗工时';
$lang->execution->left          = '剩余';
$lang->execution->leftHours     = '预计剩余';

$lang->execution->copyTeamTip        = "可以选择复制{$lang->projectCommon}或{$lang->execution->common}团队的成员";
$lang->execution->daysGreaterProject = '可用工日不能大于执行的可用工日『%s』';
$lang->execution->errorHours         = '可用工时/天不能大于『24』';
$lang->execution->agileplusMethodTip = "融合敏捷{$lang->projectCommon}创建执行时，支持{$lang->executionCommon}和看板两种管理方法。";
$lang->execution->typeTip            = '“综合”类型的父阶段可以创建其它类型的子级，其它父子层级的类型均一致。';
$lang->execution->waterfallTip       = "瀑布{$lang->projectCommon}和融合瀑布{$lang->projectCommon}中，";
$lang->execution->progressTip        = '总进度 = 已消耗工时 / (已消耗工时 + 剩余工时)';

$lang->execution->start    = "开始";
$lang->execution->activate = "激活";
$lang->execution->putoff   = "延期";
$lang->execution->suspend  = "挂起";
$lang->execution->close    = "关闭";
$lang->execution->export   = "导出";
$lang->execution->next     = "下一步";

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

$lang->execution->cfdTypeList['story'] = "按{$lang->SRCommon}查看";
$lang->execution->cfdTypeList['task']  = "按任务查看";
$lang->execution->cfdTypeList['bug']   = "按Bug查看";

$lang->team->account    = '用户';
$lang->team->realname   = '姓名';
$lang->team->role       = '角色';
$lang->team->roleAB     = '我的角色';
$lang->team->join       = '加盟日期';
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
$lang->execution->aclList['private'] = "私有（团队成员和{$lang->projectCommon}负责人、干系人可访问）";
$lang->execution->aclList['open']    = "继承{$lang->projectCommon}访问权限（能访问当前{$lang->projectCommon}，即可访问）";

$lang->execution->kanbanAclList['private'] = '私有';
$lang->execution->kanbanAclList['open']    = "继承{$lang->projectCommon}";

$lang->execution->storyPoint = '故事点';

$lang->execution->burnByList['left']       = '按剩余工时查看';
$lang->execution->burnByList['estimate']   = "按计划工时查看";
$lang->execution->burnByList['storyPoint'] = '按故事点查看';

/* 方法列表。*/
$lang->execution->index                     = "{$lang->execution->common}主页";
$lang->execution->task                      = '任务列表';
$lang->execution->groupTask                 = '分组浏览任务';
$lang->execution->story                     = "{$lang->SRCommon}列表";
$lang->execution->qa                        = '测试仪表盘';
$lang->execution->bug                       = 'Bug列表';
$lang->execution->testcase                  = '用例列表';
$lang->execution->dynamic                   = '动态';
$lang->execution->latestDynamic             = '最新动态';
$lang->execution->build                     = '版本列表';
$lang->execution->testtask                  = '测试单';
$lang->execution->burn                      = '燃尽图';
$lang->execution->computeBurn               = '更新燃尽图';
$lang->execution->computeCFD                = '更新累积流图';
$lang->execution->fixFirst                  = '修改首天工时';
$lang->execution->team                      = '团队成员';
$lang->execution->doc                       = '文档列表';
$lang->execution->doclib                    = '文档库';
$lang->execution->manageProducts            = '关联' . $lang->productCommon;
$lang->execution->linkStory                 = "关联{$lang->SRCommon}";
$lang->execution->linkStoryByPlan           = "按照计划关联";
$lang->execution->linkPlan                  = "关联计划";
$lang->execution->unlinkStoryTasks          = "未关联{$lang->SRCommon}任务";
$lang->execution->linkedProducts            = '已关联';
$lang->execution->unlinkedProducts          = '未关联';
$lang->execution->view                      = "{$lang->execution->common}概况";
$lang->execution->startAction               = "开始{$lang->execution->common}";
$lang->execution->activateAction            = "激活{$lang->execution->common}";
$lang->execution->delayAction               = "延期{$lang->execution->common}";
$lang->execution->suspendAction             = "挂起{$lang->execution->common}";
$lang->execution->closeAction               = "关闭{$lang->execution->common}";
$lang->execution->testtaskAction            = "{$lang->execution->common}测试单";
$lang->execution->teamAction                = "{$lang->execution->common}团队";
$lang->execution->kanbanAction              = "{$lang->execution->common}看板";
$lang->execution->printKanbanAction         = "打印看板";
$lang->execution->treeAction                = "{$lang->execution->common}树状图";
$lang->execution->exportAction              = "导出{$lang->execution->common}";
$lang->execution->computeBurnAction         = "计算燃尽图";
$lang->execution->create                    = "添加{$lang->executionCommon}";
$lang->execution->createExec                = "添加{$lang->execution->common}";
$lang->execution->createAction              = "添加{$lang->execution->common}";
$lang->execution->copyExec                  = "复制{$lang->execution->common}";
$lang->execution->copy                      = "复制{$lang->executionCommon}";
$lang->execution->delete                    = "删除{$lang->executionCommon}";
$lang->execution->deleteAB                  = "删除{$lang->execution->common}";
$lang->execution->browse                    = "浏览{$lang->execution->common}";
$lang->execution->edit                      = "设置{$lang->executionCommon}";
$lang->execution->editAction                = "编辑{$lang->execution->common}";
$lang->execution->batchEdit                 = "编辑";
$lang->execution->batchEditAction           = "批量编辑";
$lang->execution->batchChangeStatus         = "批量修改状态";
$lang->execution->manageMembers             = '团队管理';
$lang->execution->unlinkMember              = '移除成员';
$lang->execution->unlinkStory               = "移除{$lang->SRCommon}";
$lang->execution->unlinkStoryAB             = "移除{$lang->SRCommon}";
$lang->execution->batchUnlinkStory          = "批量移除{$lang->SRCommon}";
$lang->execution->importTask                = '转入任务';
$lang->execution->importPlanStories         = "按计划关联{$lang->SRCommon}";
$lang->execution->importBug                 = '导入Bug';
$lang->execution->tree                      = '树状图';
$lang->execution->treeTask                  = '只看任务';
$lang->execution->treeStory                 = "只看{$lang->SRCommon}";
$lang->execution->treeViewTask              = '树状图查看任务';
$lang->execution->treeViewStory             = "树状图查看{$lang->SRCommon}";
$lang->execution->storyKanban               = "{$lang->SRCommon}看板";
$lang->execution->storySort                 = "{$lang->SRCommon}排序";
$lang->execution->importPlanStory           = "创建{$lang->executionCommon}成功！\n是否导入计划关联的相关" . $lang->SRCommon . '？导入时只能导入激活状态的' . $lang->SRCommon . '。';
$lang->execution->importEditPlanStory       = "编辑{$lang->executionCommon}成功！\n是否导入计划关联的相关" . $lang->SRCommon . '？导入时将自动过滤掉草稿状态的' . $lang->SRCommon . '。';
$lang->execution->importBranchPlanStory     = "创建{$lang->executionCommon}成功！\n是否导入计划关联的相关" . $lang->SRCommon . '？导入时将只关联本' . $lang->executionCommon . '所关联分支的激活需求。';
$lang->execution->importBranchEditPlanStory = "编辑{$lang->executionCommon}成功！\n是否导入计划关联的相关" . $lang->SRCommon . '？导入时将只关联本' . $lang->executionCommon . '所关联分支的激活需求。';
$lang->execution->needLinkProducts          = "该执行还未关联任何{$lang->productCommon}，相关功能无法使用，请先关联{$lang->productCommon}后再试。";
$lang->execution->iteration                 = '版本迭代';
$lang->execution->iterationInfo             = '迭代%s次';
$lang->execution->viewAll                   = '查看所有';
$lang->execution->testreport                = '测试报告';
$lang->execution->taskKanban                = '任务看板';
$lang->execution->RDKanban                  = '研发看板';

/* 分组浏览。*/
$lang->execution->allTasks     = '所有';
$lang->execution->assignedToMe = '指派给我';
$lang->execution->myInvolved   = '由我参与';
$lang->execution->assignedByMe = '由我指派';

$lang->execution->statusSelects['']             = '更多';
$lang->execution->statusSelects['wait']         = '未开始';
$lang->execution->statusSelects['doing']        = '进行中';
$lang->execution->statusSelects['undone']       = '未完成';
$lang->execution->statusSelects['finishedbyme'] = '我完成';
$lang->execution->statusSelects['done']         = '已完成';
$lang->execution->statusSelects['closed']       = '已关闭';
$lang->execution->statusSelects['cancel']       = '已取消';
$lang->execution->statusSelects['delayed']      = '已延期';

$lang->execution->groups['']           = '分组查看';
$lang->execution->groups['story']      = "{$lang->SRCommon}分组";
$lang->execution->groups['status']     = '状态分组';
$lang->execution->groups['pri']        = '优先级分组';
$lang->execution->groups['assignedTo'] = '指派给分组';
$lang->execution->groups['finishedBy'] = '完成者分组';
$lang->execution->groups['closedBy']   = '关闭者分组';
$lang->execution->groups['type']       = '类型分组';

$lang->execution->groupFilter['story']['all']         = '全部';
$lang->execution->groupFilter['story']['linked']      = "已关联{$lang->SRCommon}的任务";
$lang->execution->groupFilter['pri']['all']           = '全部';
$lang->execution->groupFilter['pri']['noset']         = '未设置';
$lang->execution->groupFilter['assignedTo']['undone'] = '未完成';
$lang->execution->groupFilter['assignedTo']['all']    = '全部';

$lang->execution->byQuery = '搜索';

/* 查询条件列表。*/
$lang->execution->allExecution      = "所有{$lang->executionCommon}";
$lang->execution->aboveAllProduct   = "以上所有{$lang->productCommon}";
$lang->execution->aboveAllExecution = "以上所有{$lang->executionCommon}";

/* 页面提示。*/
$lang->execution->linkStoryByPlanTips  = "此操作会将所选计划下面的{$lang->SRCommon}全部关联到此{$lang->executionCommon}中";
$lang->execution->batchCreateStoryTips = "请选择需要批量新建研发需求的{$lang->productCommon}";
$lang->execution->selectExecution      = "请选择{$lang->execution->common}";
$lang->execution->beginAndEnd          = '起止时间';
$lang->execution->lblStats             = '工时信息';
$lang->execution->DurationStats        = '工期信息';
$lang->execution->stats                = '可用工时 <strong>%s</strong> 工时，总共预计 <strong>%s</strong> 工时，已经消耗 <strong>%s</strong> 工时，预计剩余 <strong>%s</strong> 工时';
$lang->execution->taskSummary          = "本页共 <strong>%s</strong> 个任务，未开始 <strong>%s</strong>，进行中 <strong>%s</strong>，总预计 <strong>%s</strong> 工时，已消耗 <strong>%s</strong> 工时，剩余 <strong>%s</strong> 工时。";
$lang->execution->pageSummary          = "本页共 <strong>%total%</strong> 个任务，未开始 <strong>%wait%</strong>，进行中 <strong>%doing%</strong>，总预计 <strong>%estimate%</strong> 工时，已消耗 <strong>%consumed%</strong> 工时，剩余 <strong>%left%</strong> 工时。";
$lang->execution->checkedSummary       = "选中 <strong>%total%</strong> 个任务，未开始 <strong>%wait%</strong>，进行中 <strong>%doing%</strong>，总预计 <strong>%estimate%</strong> 工时，已消耗 <strong>%consumed%</strong> 工时，剩余 <strong>%left%</strong> 工时。";
$lang->execution->executionSummary     = "本页共 <strong>%s</strong> 个{$lang->executionCommon}。";
$lang->execution->pageExecSummary      = "本页共 <strong>%total%</strong> 个{$lang->executionCommon}，未开始 <strong>%wait%</strong>，进行中 <strong>%doing%</strong>。";
$lang->execution->checkedExecSummary   = "选中 <strong>%total%</strong> 个{$lang->executionCommon}，未开始 <strong>%wait%</strong>，进行中 <strong>%doing%</strong>。";
$lang->execution->memberHoursAB        = "<div>%s有 <strong>%s</strong> 工时</div>";
$lang->execution->memberHours          = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">%s可用工时</div><div class="segment-value">%s</div></div></div></div>';
$lang->execution->countSummary         = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">总任务</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">进行中</div><div class="segment-value"><span class="label label-dot primary"></span> %s</div></div><div class="segment"><div class="segment-title">未开始</div><div class="segment-value"><span class="label label-dot secondary"></span> %s</div></div></div></div>';
$lang->execution->timeSummary          = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">总预计</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">已消耗</div><div class="segment-value text-red">%s</div></div><div class="segment"><div class="segment-title">剩余</div><div class="segment-value">%s</div></div></div></div>';
$lang->execution->groupSummaryAB       = "<div>总任务 <strong>%s : </strong><span class='text-muted'>未开始</span> %s &nbsp; <span class='text-muted'>进行中</span> %s</div><div>总预计 <strong>%s : </strong><span class='text-muted'>已消耗</span> %s &nbsp; <span class='text-muted'>剩余</span> %s</div>";
$lang->execution->wbs                  = "分解任务";
$lang->execution->batchWBS             = "批量分解";
$lang->execution->howToUpdateBurn      = "<a href='https://api.zentao.net/goto.php?item=burndown&lang=zh-cn' target='_blank' title='如何更新燃尽图？'>帮助 <i class='icon icon-help text-gray'></i></a>";
$lang->execution->whyNoStories         = "看起来没有{$lang->SRCommon}可以关联。请检查下{$lang->executionCommon}关联的{$lang->productCommon}中有没有{$lang->SRCommon}，而且要确保它们已经审核通过。";
$lang->execution->projectNoStories     = "看起来没有{$lang->SRCommon}可以关联。请检查下{$lang->projectCommon}中有没有{$lang->SRCommon}，而且要确保它们已经审核通过。";
$lang->execution->productStories       = "{$lang->executionCommon}关联的{$lang->SRCommon}是{$lang->productCommon}{$lang->SRCommon}的子集，并且只有评审通过的{$lang->SRCommon}才能关联。请<a href='%s'>关联{$lang->SRCommon}</a>。";
$lang->execution->haveBranchDraft      = "导入完成！有%s条非激活状态或不是{$lang->executionCommon}关联分支的{$lang->SRCommon}无法导入";
$lang->execution->haveDraft            = "导入完成！有%s条非激活状态的{$lang->SRCommon}无法导入";
$lang->execution->doneExecutions       = '已结束';
$lang->execution->selectDept           = '选择部门';
$lang->execution->selectDeptTitle      = '选择一个部门的成员';
$lang->execution->copyTeam             = '复制团队';
$lang->execution->copyFromTeam         = "复制自{$lang->execution->common}团队： <strong>%s</strong>";
$lang->execution->noMatched            = "找不到包含'%s'的{$lang->execution->common}";
$lang->execution->copyTitle            = "请选择一个{$lang->execution->common}来复制";
$lang->execution->copyNoExecution      = "没有可用的{$lang->execution->common}来复制";
$lang->execution->copyFromExecution    = "复制自{$lang->execution->common} <strong>%s</strong>";
$lang->execution->cancelCopy           = '取消复制';
$lang->execution->byPeriod             = '按时间段';
$lang->execution->byUser               = '按用户';
$lang->execution->noExecution          = "暂时没有{$lang->executionCommon}。";
$lang->execution->noExecutions         = "暂时没有{$lang->execution->common}。";
$lang->execution->noPrintData          = "暂无数据可打印";
$lang->execution->noMembers            = '暂时没有团队成员。';
$lang->execution->workloadTotal        = "工作量占比累计不应当超过100%s, 当前{$lang->productCommon}下的工作量之和为%s";
$lang->execution->linkAllStoryTip      = "({$lang->projectCommon}下还未关联{$lang->SRCommon}，可直接关联该{$lang->execution->common}所关联{$lang->productCommon}的{$lang->SRCommon})";
$lang->execution->copyTeamTitle        = "选择一个{$lang->project->common}或{$lang->execution->common}团队";

/* 交互提示。*/
$lang->execution->confirmDelete                = "您确定删除{$lang->executionCommon}[%s]吗？";
$lang->execution->confirmUnlinkMember          = "您确定从该{$lang->executionCommon}中移除该用户吗？";
$lang->execution->confirmUnlinkStory           = "移除该{$lang->SRCommon}后，该{$lang->SRCommon}关联的用例将被移除，该{$lang->SRCommon}关联的任务将被取消，请确认。";
$lang->execution->confirmSync                  = "修改所属{$lang->projectCommon}后,为了保持数据的一致性，该执行所关联的{$lang->productCommon}、{$lang->SRCommon}、团队和白名单数据将会同步到新的{$lang->projectCommon}中，请知悉。";
$lang->execution->confirmUnlinkExecutionStory  = "您确定从该{$lang->projectCommon}中移除该{$lang->SRCommon}吗？";
$lang->execution->notAllowedUnlinkStory        = "该{$lang->SRCommon}已经与{$lang->projectCommon}下{$lang->executionCommon}相关联，请从{$lang->executionCommon}中移除后再操作。";
$lang->execution->notAllowRemoveProducts       = "该{$lang->productCommon}中的{$lang->SRCommon}%s已与该{$lang->executionCommon}进行了关联，请取消关联后再操作。";
$lang->execution->errorNoLinkedProducts        = "该{$lang->executionCommon}没有关联的{$lang->productCommon}，系统将转到{$lang->productCommon}关联页面";
$lang->execution->errorSameProducts            = "{$lang->executionCommon}不能关联多个相同的{$lang->productCommon}。";
$lang->execution->errorSameBranches            = "{$lang->executionCommon}不能关联多个相同的分支。";
$lang->execution->errorBegin                   = "{$lang->executionCommon}的开始时间不能小于所属{$lang->projectCommon}的开始时间%s。";
$lang->execution->errorEnd                     = "{$lang->executionCommon}的截止时间不能大于所属{$lang->projectCommon}的结束时间%s。";
$lang->execution->errorLesserProject           = "{$lang->executionCommon}的计划开始时间不能小于所属{$lang->projectCommon}的计划开始时间%s。";
$lang->execution->errorGreaterProject          = "{$lang->executionCommon}的计划完成时间不能大于所属{$lang->projectCommon}的计划完成时间%s。";
$lang->execution->errorCommonBegin             = $lang->executionCommon . "开始日期应大于等于{$lang->projectCommon}的开始日期：%s。";
$lang->execution->errorCommonEnd               = $lang->executionCommon . "截止日期应小于等于{$lang->projectCommon}的截止日期：%s。";
$lang->execution->errorLesserParent            = '计划开始时间不能小于所属父阶段的计划开始时间：%s。';
$lang->execution->errorGreaterParent           = '计划完成时间不能大于所属父阶段的计划完成时间：%s。';
$lang->execution->errorNameRepeat              = "相同父阶段的子%s名称不能相同";
$lang->execution->errorAttrMatch               = "父阶段类型为[%s]，阶段类型需与父阶段一致";
$lang->execution->errorLesserPlan              = "『%s』应当不小于计划开始时间『%s』。";
$lang->execution->accessDenied                 = "您无权访问该{$lang->executionCommon}！";
$lang->execution->tips                         = '提示';
$lang->execution->afterInfo                    = "{$lang->executionCommon}添加成功，您现在可以进行以下操作：";
$lang->execution->setTeam                      = '设置团队';
$lang->execution->linkStory                    = "关联{$lang->SRCommon}";
$lang->execution->createTask                   = '创建任务';
$lang->execution->goback                       = "返回任务列表";
$lang->execution->gobackExecution              = "返回{$lang->executionCommon}列表";
$lang->execution->noweekend                    = '去除周末';
$lang->execution->nodelay                      = '去除延期日期';
$lang->execution->withweekend                  = '显示周末';
$lang->execution->withdelay                    = '显示延期日期';
$lang->execution->interval                     = '间隔';
$lang->execution->fixFirstWithLeft             = '修改剩余工时';
$lang->execution->unfinishedExecution          = "该{$lang->executionCommon}下还有";
$lang->execution->unfinishedTask               = "[%s]个未完成的任务，";
$lang->execution->unresolvedBug                = "[%s]个未解决的bug，";
$lang->execution->projectNotEmpty              = "所属{$lang->projectCommon}不能为空。";
$lang->execution->confirmStoryToTask           = '%s' . $lang->SRCommon . '已经在当前' . $lang->execution->common . '中转了任务，请确认是否重复转任务。';
$lang->execution->ge                           = "『%s』应当不小于实际开始时间『%s』。";
$lang->execution->storyDragError               = "该{$lang->SRCommon}不是激活状态，请激活后再拖动";
$lang->execution->countTip                     = '（%s人）';
$lang->execution->pleaseInput                  = "请输入";
$lang->execution->week                         = '周';
$lang->execution->checkedExecutions            = "共选中%s个{$lang->executionCommon}。";
$lang->execution->hasStartedTaskOrSubStage     = "%s%s下的任务或子阶段已经开始，无法修改，已过滤。";
$lang->execution->hasSuspendedOrClosedChildren = "阶段%s下的子阶段未全部挂起或关闭，无法修改，已过滤。";
$lang->execution->hasNotClosedChildren         = "阶段%s下的子阶段未全部关闭，无法修改，已过滤。";
$lang->execution->hasStartedTask               = "%s%s下的任务已经开始，无法修改，已过滤。";
$lang->execution->cannotManageProducts         = "当前{$lang->execution->common}的{$lang->project->common}为%s{$lang->project->common}，不能关联{$lang->productCommon}。";

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
$lang->execution->charts->burn->graph->delay        = '延期';

$lang->execution->charts->cfd = new stdclass();
$lang->execution->charts->cfd->cfdTip        = "<p>
1.累积流图反应各个阶段累积处理的工作项数量随时间的变化趋势。</br>
2.横轴代表日期，纵轴代表工作项数量。</br>
3.通过此图可计算出在制品数量，交付速率以及平均前置时间，从而了解团队的交付情况。</p>";
$lang->execution->charts->cfd->cycleTime     = '平均周期时间';
$lang->execution->charts->cfd->cycleTimeTip  = '平均每个卡片从开发启动到完成的周期时间';
$lang->execution->charts->cfd->throughput    = '吞吐率';
$lang->execution->charts->cfd->throughputTip = '吞吐率 = 在制品 / 平均周期时间';

$lang->execution->charts->cfd->begin          = '开始日期';
$lang->execution->charts->cfd->end            = '结束日期';
$lang->execution->charts->cfd->errorBegin     = '开始日期应小于结束日期';
$lang->execution->charts->cfd->errorDateRange = '累积流图只提供3个月内的数据展示';
$lang->execution->charts->cfd->dateRangeTip   = '累积流图只展示3个月内的数据';

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
$lang->execution->setKanban     = "设置看板";
$lang->execution->resetKanban   = "恢复默认";
$lang->execution->printKanban   = "打印看板";
$lang->execution->fullScreen    = "看板全屏展示";
$lang->execution->bugList       = "Bug列表";

$lang->execution->kanbanHideCols   = '看板隐藏已关闭、已取消列';
$lang->execution->kanbanShowOption = '显示折叠信息';
$lang->execution->kanbanColsColor  = '看板列自定义颜色';
$lang->execution->kanbanCardsUnit  = '个';

$lang->execution->kanbanViewList['all']   = '综合看板';
$lang->execution->kanbanViewList['story'] = "{$lang->SRCommon}看板";
$lang->execution->kanbanViewList['bug']   = 'Bug看板';
$lang->execution->kanbanViewList['task']  = '任务看板';

$lang->execution->teamWords  = '团队';

$lang->kanbanSetting = new stdclass();
$lang->kanbanSetting->noticeReset     = '是否恢复看板默认设置？';
$lang->kanbanSetting->optionList['0'] = '隐藏';
$lang->kanbanSetting->optionList['1'] = '显示';

$lang->printKanban = new stdclass();
$lang->printKanban->common  = '打印看板';
$lang->printKanban->content = '内容';
$lang->printKanban->print   = '打印';

$lang->printKanban->taskStatus = '状态';

$lang->printKanban->typeList['all']       = '全部';
$lang->printKanban->typeList['increment'] = '增量';

$lang->execution->typeList['']       = '';
$lang->execution->typeList['stage']  = '阶段';
$lang->execution->typeList['sprint'] = $lang->executionCommon;
$lang->execution->typeList['kanban'] = '看板';

$lang->execution->featureBar['tree']['all'] = '全部';

$lang->execution->featureBar['task']['all']          = '全部';
$lang->execution->featureBar['task']['unclosed']     = $lang->execution->unclosed;
$lang->execution->featureBar['task']['assignedtome'] = $lang->execution->assignedToMe;
$lang->execution->featureBar['task']['myinvolved']   = $lang->execution->myInvolved;
$lang->execution->featureBar['task']['assignedbyme'] = $lang->execution->assignedByMe;
$lang->execution->featureBar['task']['needconfirm']  = "{$lang->SRCommon}变更";
$lang->execution->featureBar['task']['status']       = $lang->more;

$lang->execution->moreSelects['task']['status']['wait']         = '未开始';
$lang->execution->moreSelects['task']['status']['doing']        = '进行中';
$lang->execution->moreSelects['task']['status']['undone']       = '未完成';
$lang->execution->moreSelects['task']['status']['finishedbyme'] = '我完成';
$lang->execution->moreSelects['task']['status']['done']         = '已完成';
$lang->execution->moreSelects['task']['status']['closed']       = '已关闭';
$lang->execution->moreSelects['task']['status']['cancel']       = '已取消';
$lang->execution->moreSelects['task']['status']['delayed']      = '已延期';

$lang->execution->featureBar['all']['all']       = '全部';
$lang->execution->featureBar['all']['undone']    = $lang->execution->undone;
$lang->execution->featureBar['all']['wait']      = $lang->execution->statusList['wait'];
$lang->execution->featureBar['all']['doing']     = $lang->execution->statusList['doing'];
$lang->execution->featureBar['all']['suspended'] = $lang->execution->statusList['suspended'];
$lang->execution->featureBar['all']['closed']    = $lang->execution->statusList['closed'];

$lang->execution->featureBar['bug']['all']        = '全部';
$lang->execution->featureBar['bug']['unresolved'] = '未解决';

$lang->execution->featureBar['build']['all'] = '全部版本';

$lang->execution->featureBar['story']['all']       = '全部';
$lang->execution->featureBar['story']['unclosed']  = '未关闭';
$lang->execution->featureBar['story']['draft']     = '草稿';
$lang->execution->featureBar['story']['reviewing'] = '评审中';

$lang->execution->featureBar['testcase']['all'] = '全部';

$lang->execution->featureBar['importtask']['all'] = $lang->execution->importTask;

$lang->execution->featureBar['importbug']['all'] = $lang->execution->importBug;

$lang->execution->myExecutions = '我参与的';
$lang->execution->doingProject = "进行中的{$lang->projectCommon}";

$lang->execution->kanbanColType['wait']      = $lang->execution->statusList['wait']      . '的' . $lang->execution->common;
$lang->execution->kanbanColType['doing']     = $lang->execution->statusList['doing']     . '的' . $lang->execution->common;
$lang->execution->kanbanColType['suspended'] = $lang->execution->statusList['suspended'] . '的' . $lang->execution->common;
$lang->execution->kanbanColType['closed']    = $lang->execution->statusList['closed']    . '的' . $lang->execution->common . '(最近2期)';

$lang->execution->treeLevel = array();
$lang->execution->treeLevel['all']   = '全部展开';
$lang->execution->treeLevel['root']  = '全部折叠';
$lang->execution->treeLevel['task']  = '全部显示';
$lang->execution->treeLevel['story'] = "只看{$lang->SRCommon}";

$lang->execution->action = new stdclass();
$lang->execution->action->opened               = '$date, 由 <strong>$actor</strong> 创建。$extra' . "\n";
$lang->execution->action->managed              = '$date, 由 <strong>$actor</strong> 维护。$extra' . "\n";
$lang->execution->action->edited               = '$date, 由 <strong>$actor</strong> 编辑。$extra' . "\n";
$lang->execution->action->extra                = "相关{$lang->productCommon}为 %s。";
$lang->execution->action->startbychildactivate = '$date, 系统判断由于子阶段激活，将' . $lang->executionCommon . '状态置为进行中。' . "\n";
$lang->execution->action->waitbychilddelete    = '$date, 系统判断由于子阶段删除，将' . $lang->executionCommon . '状态置为未开始。' . "\n";
$lang->execution->action->closebychilddelete   = '$date, 系统判断由于子阶段删除，将' . $lang->executionCommon . '状态置为已关闭。' . "\n";
$lang->execution->action->closebychildclose    = '$date, 系统判断由于子阶段关闭，将' . $lang->executionCommon . '状态置为已关闭。' . "\n";
$lang->execution->action->waitbychild          = '$date, 系统判断由于子阶段 <strong>全部为未开始</strong> ，将阶段状态置为 <strong>未开始</strong> 。';
$lang->execution->action->suspendedbychild     = '$date, 系统判断由于子阶段 <strong>全部挂起</strong> ，将阶段状态置为 <strong>已挂起</strong> 。';
$lang->execution->action->closedbychild        = '$date, 系统判断由于子阶段 <strong>全部关闭</strong> ，将阶段状态置为 <strong>已关闭</strong> 。';
$lang->execution->action->startbychildstart    = '$date, 系统判断由于子阶段 <strong>开始</strong> ，将阶段状态置为 <strong>进行中</strong> 。';
$lang->execution->action->startbychildactivate = '$date, 系统判断由于子阶段 <strong>激活</strong> ，将阶段状态置为 <strong>进行中</strong> 。';
$lang->execution->action->startbychildsuspend  = '$date, 系统判断由于子阶段 <strong>挂起</strong> ，将阶段状态置为 <strong>进行中</strong> 。';
$lang->execution->action->startbychildclose    = '$date, 系统判断由于子阶段 <strong>关闭</strong> ，将阶段状态置为 <strong>进行中</strong> 。';
$lang->execution->action->startbychildcreate   = '$date, 系统判断由于 <strong>创建</strong> 子阶段 ，将阶段状态置为 <strong>进行中</strong> 。';
$lang->execution->action->startbychildedit     = '$date, 系统判断由于子阶段 <strong>状态修改</strong> ，将阶段状态置为 <strong>进行中</strong> 。';
$lang->execution->action->startbychild         = '$date, 系统判断由于子阶段 <strong>激活</strong> ，将阶段状态置为 <strong>进行中</strong> 。';
$lang->execution->action->waitbychild          = '$date, 系统判断由于子阶段 <strong>状态修改</strong> ，将阶段状态置为 <strong>未开始</strong> 。';
$lang->execution->action->suspendbychild       = '$date, 系统判断由于子阶段 <strong>状态修改</strong> ，将阶段状态置为 <strong>已挂起</strong> 。';
$lang->execution->action->closebychild         = '$date, 系统判断由于子阶段 <strong>状态修改</strong> ，将阶段状态置为 <strong>已关闭</strong> 。';

$lang->execution->startbychildactivate = '激活了';
$lang->execution->waitbychilddelete    = '停止了';
$lang->execution->closebychilddelete   = '关闭了';
$lang->execution->closebychildclose    = '关闭了';
$lang->execution->waitbychild          = '激活了';
$lang->execution->suspendedbychild     = '挂起了';
$lang->execution->closedbychild        = '关闭了';
$lang->execution->startbychildstart    = '开始了';
$lang->execution->startbychildactivate = '激活了';
$lang->execution->startbychildsuspend  = '激活了';
$lang->execution->startbychildclose    = '激活了';
$lang->execution->startbychildcreate   = '激活了';
$lang->execution->startbychildedit     = '激活了';
$lang->execution->startbychild         = '激活了';
$lang->execution->waitbychild          = '停止了';
$lang->execution->suspendbychild       = '挂起了';
$lang->execution->closebychild         = '关闭了';

$lang->execution->statusColorList = array();
$lang->execution->statusColorList['wait']      = '#0991FF';
$lang->execution->statusColorList['doing']     = '#0BD986';
$lang->execution->statusColorList['suspended'] = '#fdc137';
$lang->execution->statusColorList['closed']    = '#838A9D';

if(!isset($lang->execution->gantt)) $lang->execution->gantt = new stdclass();
$lang->execution->gantt->progressColor[0] = '#B7B7B7';
$lang->execution->gantt->progressColor[1] = '#FF8287';
$lang->execution->gantt->progressColor[2] = '#FFC73A';
$lang->execution->gantt->progressColor[3] = '#6BD5F5';
$lang->execution->gantt->progressColor[4] = '#9DE88A';
$lang->execution->gantt->progressColor[5] = '#9BA8FF';

$lang->execution->gantt->color[0] = '#E7E7E7';
$lang->execution->gantt->color[1] = '#FFDADB';
$lang->execution->gantt->color[2] = '#FCECC1';
$lang->execution->gantt->color[3] = '#D3F3FD';
$lang->execution->gantt->color[4] = '#DFF5D9';
$lang->execution->gantt->color[5] = '#EBDCF9';

$lang->execution->gantt->textColor[0] = '#2D2D2D';
$lang->execution->gantt->textColor[1] = '#8D0308';
$lang->execution->gantt->textColor[2] = '#9D4200';
$lang->execution->gantt->textColor[3] = '#006D8E';
$lang->execution->gantt->textColor[4] = '#1A8100';
$lang->execution->gantt->textColor[5] = '#660ABC';

$lang->execution->gantt->stage = new stdclass();
$lang->execution->gantt->stage->progressColor = '#70B8FE';
$lang->execution->gantt->stage->color         = '#D2E7FC';
$lang->execution->gantt->stage->textColor     = '#0050A7';

$lang->execution->gantt->defaultColor         = '#EBDCF9';
$lang->execution->gantt->defaultProgressColor = '#9BA8FF';
$lang->execution->gantt->defaultTextColor     = '#660ABC';

$lang->execution->gantt->bar_height = '24';

$lang->execution->gantt->exportImg  = '导出图片';
$lang->execution->gantt->exportPDF  = '导出 PDF';
$lang->execution->gantt->exporting  = '正在导出……';
$lang->execution->gantt->exportFail = '导出失败。';

$lang->execution->boardColorList = array('#32C5FF', '#006AF1', '#9D28B2', '#FF8F26', '#7FBB00', '#424BAC', '#66c5f8', '#EC2761');

$lang->execution->linkBranchStoryByPlanTips = "{$lang->execution->common}按计划关联需求时，只导入本{$lang->execution->common}所关联%s的激活状态的需求。";
$lang->execution->linkNormalStoryByPlanTips = "{$lang->execution->common}按计划关联需求时，只导入激活状态的需求。";

$lang->execution->featureBar['dynamic']['all']       = '全部';
$lang->execution->featureBar['dynamic']['today']     = '今天';
$lang->execution->featureBar['dynamic']['yesterday'] = '昨天';
$lang->execution->featureBar['dynamic']['thisWeek']  = '本周';
$lang->execution->featureBar['dynamic']['lastWeek']  = '上周';
$lang->execution->featureBar['dynamic']['thisMonth'] = '本月';
$lang->execution->featureBar['dynamic']['lastMonth'] = '上月';

$lang->execution->featureBar['team']['all'] = '团队成员';

$lang->execution->featureBar['managemembers']['all'] = '团队管理';
