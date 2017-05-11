<?php
/**
 * The project module zh-cn file of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: zh-cn.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
/* 字段列表。*/
$lang->project->common        = $lang->projectCommon . '视图';
$lang->project->allProjects   = '全部';
$lang->project->type          = $lang->projectCommon . '类型';
$lang->project->name          = $lang->projectCommon . '名称';
$lang->project->code          = $lang->projectCommon . '代号';
$lang->project->begin         = '开始日期';
$lang->project->end           = '结束日期';
$lang->project->dateRange     = '起始日期';
$lang->project->to            = '至';
$lang->project->days          = '可用工作日';
$lang->project->day           = '天';
$lang->project->workHour      = '工时';
$lang->project->status        = $lang->projectCommon . '状态';
$lang->project->desc          = $lang->projectCommon . '描述';
$lang->project->owner         = '负责人';
$lang->project->PO            = $lang->productCommon . '负责人';
$lang->project->PM            = $lang->projectCommon . '负责人';
$lang->project->QD            = '测试负责人';
$lang->project->RD            = '发布负责人';
$lang->project->acl           = '访问控制';
$lang->project->teamname      = '团队名称';
$lang->project->order         = $lang->projectCommon . '排序';
$lang->project->products      = '相关' . $lang->productCommon;
$lang->project->whitelist     = '分组白名单';
$lang->project->totalEstimate = '总预计';
$lang->project->totalConsumed = '总消耗';
$lang->project->totalLeft     = '总剩余';
$lang->project->Left          = '剩余';
$lang->project->progess       = '进度';
$lang->project->hours         = '预计 %s 消耗 %s 剩余 %s';
$lang->project->viewBug       = '查看bug';
$lang->project->noProduct     = "无{$lang->productCommon}{$lang->projectCommon}";
$lang->project->createStory   = "新增需求";
$lang->project->all           = '所有';
$lang->project->undone        = '未完成';
$lang->project->unclosed      = '未关闭';
$lang->project->typeDesc      = "运维{$lang->projectCommon}没有需求、bug、版本、测试功能，同时禁用燃尽图。";
$lang->project->mine          = '我负责：';
$lang->project->other         = '其他：';
$lang->project->deleted       = '已删除';
$lang->project->delayed       = '已延期';

$lang->project->start    = '开始';
$lang->project->activate = '激活';
$lang->project->putoff   = '延期';
$lang->project->suspend  = '挂起';
$lang->project->close    = '关闭';

$lang->project->typeList['sprint']    = "短期$lang->projectCommon";
$lang->project->typeList['waterfall'] = "长期$lang->projectCommon";
$lang->project->typeList['ops']       = "运维$lang->projectCommon";

$lang->project->endList[7]    = '一星期';
$lang->project->endList[14]   = '两星期';
$lang->project->endList[31]   = '一个月';
$lang->project->endList[62]   = '两个月';
$lang->project->endList[93]   = '三个月';
$lang->project->endList[186]  = '半年';
$lang->project->endList[365]  = '一年';

$lang->team = new stdclass();
$lang->team->account    = '用户';
$lang->team->role       = '角色';
$lang->team->join       = '加盟日';
$lang->team->hours      = '可用工时/天';
$lang->team->days       = '可用工日';
$lang->team->totalHours = '总计';
 
$lang->project->basicInfo = '基本信息';
$lang->project->otherInfo = '其他信息';

/* 字段取值列表。*/
$lang->project->statusList['wait']      = '未开始';
$lang->project->statusList['doing']     = '进行中';
$lang->project->statusList['suspended'] = '已挂起';
$lang->project->statusList['done']      = '已完成';

$lang->project->aclList['open']    = "默认设置(有{$lang->projectCommon}视图权限，即可访问)";
$lang->project->aclList['private'] = "私有{$lang->projectCommon}(只有{$lang->projectCommon}团队成员才能访问)";
$lang->project->aclList['custom']  = "自定义白名单(团队成员和白名单的成员可以访问)";

/* 方法列表。*/
$lang->project->index            = "{$lang->projectCommon}首页";
$lang->project->task             = '任务列表';
$lang->project->groupTask        = '分组浏览任务';
$lang->project->story            = '需求列表';
$lang->project->bug              = 'Bug列表';
$lang->project->dynamic          = '动态';
$lang->project->build            = '版本列表';
$lang->project->testtask         = '测试任务';
$lang->project->burn             = '燃尽图';
$lang->project->baseline         = '基准线';
$lang->project->computeBurn      = '更新';
$lang->project->burnData         = '燃尽图数据';
$lang->project->fixFirst         = '修改首天工时';
$lang->project->team             = '团队成员';
$lang->project->doc              = '文档列表';
$lang->project->manageProducts   = '关联' . $lang->productCommon;
$lang->project->linkStory        = '关联需求';
$lang->project->unlinkStoryTasks = '未关联需求任务';
$lang->project->linkedProducts   = '已关联';
$lang->project->unlinkedProducts = '未关联';
$lang->project->view             = "{$lang->projectCommon}概况";
$lang->project->create           = "添加{$lang->projectCommon}";
$lang->project->copy             = "复制{$lang->projectCommon}";
$lang->project->delete           = "删除{$lang->projectCommon}";
$lang->project->browse           = "浏览{$lang->projectCommon}";
$lang->project->edit             = "编辑{$lang->projectCommon}";
$lang->project->batchEdit        = "批量编辑";
$lang->project->manageMembers    = '团队管理';
$lang->project->unlinkMember     = '移除成员';
$lang->project->unlinkStory      = '移除需求';
$lang->project->batchUnlinkStory = '批量移除需求';
$lang->project->importTask       = '转入任务';
$lang->project->importBug        = '导入Bug';
$lang->project->updateOrder      = '排序';
$lang->project->tree             = '树状图';

/* 分组浏览。*/
$lang->project->allTasks             = '所有';
$lang->project->assignedToMe         = '指派给我';

$lang->project->statusSelects['']             = '更多';
$lang->project->statusSelects['wait']         = '未开始';
$lang->project->statusSelects['doing']        = '进行中';
$lang->project->statusSelects['finishedbyme'] = '我完成';
$lang->project->statusSelects['done']         = '已完成';
$lang->project->statusSelects['closed']       = '已关闭';
$lang->project->statusSelects['cancel']       = '已取消';

$lang->project->groups['']           = '分组查看';
$lang->project->groups['story']      = '需求分组';
$lang->project->groups['status']     = '状态分组';
$lang->project->groups['pri']        = '优先级分组';
$lang->project->groups['assignedTo'] = '指派给分组';
$lang->project->groups['finishedBy'] = '完成者分组';
$lang->project->groups['closedBy']   = '关闭者分组';
$lang->project->groups['type']       = '类型分组';

$lang->project->groupFilter['story']['all']         = $lang->project->all;
$lang->project->groupFilter['story']['linked']      = '已关联需求的任务';
$lang->project->groupFilter['pri']['all']           = $lang->project->all;
$lang->project->groupFilter['pri']['noset']         = '未设置';
$lang->project->groupFilter['assignedTo']['undone'] = '未完成';
$lang->project->groupFilter['assignedTo']['all']    = $lang->project->all;

$lang->project->byQuery              = '搜索';

/* 查询条件列表。*/
$lang->project->allProject      = "所有{$lang->projectCommon}";
$lang->project->aboveAllProduct = "以上所有{$lang->productCommon}";
$lang->project->aboveAllProject = "以上所有{$lang->projectCommon}";

/* 页面提示。*/
$lang->project->selectProject   = "请选择{$lang->projectCommon}";
$lang->project->beginAndEnd     = '起止时间';
$lang->project->lblStats        = '工时统计';
$lang->project->stats           = '可用工时<strong>%s</strong>工时，总共预计<strong>%s</strong>工时，已经消耗<strong>%s</strong>工时，预计剩余<strong>%s</strong>工时';
$lang->project->taskSummary     = "本页共 <strong>%s</strong> 个任务，未开始 <strong>%s</strong>，进行中 <strong>%s</strong>，总预计<strong>%s</strong>工时，已消耗<strong>%s</strong>工时，剩余<strong>%s</strong>工时。";
$lang->project->memberHours     = "%s共有 <strong>%s</strong> 个可用工时，";
$lang->project->groupSummary    = "本组共 <strong>%s</strong> 个任务，未开始 <strong>%s</strong>，进行中 <strong>%s</strong>，总预计<strong>%s</strong>工时，已消耗<strong>%s</strong>工时，剩余<strong>%s</strong>工时。";
$lang->project->groupSummaryAB  = "总任务<strong>%s</strong> ，未开始<strong>%s</strong>，进行中<strong>%s</strong>。<br />总预计<strong>%s</strong>，已消耗<strong>%s</strong>，剩余<strong>%s</strong>。";
$lang->project->noTimeSummary   = "本组共 <strong>%s</strong> 个任务，未开始 <strong>%s</strong>，进行中 <strong>%s</strong>";
$lang->project->wbs             = "分解任务";
$lang->project->batchWBS        = "批量分解";
$lang->project->howToUpdateBurn = "<a href='http://api.zentao.net/goto.php?item=burndown&lang=zh-cn' target='_blank' title='如何更新燃尽图？' class='btn btn-sm'>帮助</a>";
$lang->project->whyNoStories    = "看起来没有需求可以关联。请检查下{$lang->projectCommon}关联的{$lang->productCommon}中有没有需求，而且要确保它们已经审核通过。";
$lang->project->productStories  = "{$lang->projectCommon}关联的需求是{$lang->productCommon}需求的子集，并且只有评审通过的需求才能关联。请<a href='%s'>关联需求</a>。";
$lang->project->doneProjects    = '已结束';
$lang->project->selectDept      = '选择部门';
$lang->project->selectDeptTitle = '选择一个部门的成员';
$lang->project->copyTeam        = '复制团队';
$lang->project->copyFromTeam    = "复制自{$lang->projectCommon}团队： <strong>%s</strong>";
$lang->project->noMatched       = "找不到包含'%s'的$lang->projectCommon";
$lang->project->copyTitle       = "请选择一个{$lang->projectCommon}来复制";
$lang->project->copyTeamTitle   = "选择一个{$lang->projectCommon}团队来复制";
$lang->project->copyNoProject   = "没有可用的{$lang->projectCommon}来复制";
$lang->project->copyFromProject = "复制自{$lang->projectCommon} <strong>%s</strong>";
$lang->project->cancelCopy      = '取消复制';
$lang->project->byPeriod        = '按时间段';
$lang->project->byUser          = '按用户';

/* 交互提示。*/
$lang->project->confirmDelete         = "您确定删除{$lang->projectCommon}[%s]吗？";
$lang->project->confirmUnlinkMember   = "您确定从该{$lang->projectCommon}中移除该用户吗？";
$lang->project->confirmUnlinkStory    = "您确定从该{$lang->projectCommon}中移除该需求吗？";
$lang->project->errorNoLinkedProducts = "该{$lang->projectCommon}没有关联的{$lang->productCommon}，系统将转到{$lang->productCommon}关联页面";
$lang->project->accessDenied          = "您无权访问该{$lang->projectCommon}！";
$lang->project->tips                  = '提示';
$lang->project->afterInfo             = "{$lang->projectCommon}添加成功，您现在可以进行以下操作：";
$lang->project->setTeam               = '设置团队';
$lang->project->linkStory             = '关联需求';
$lang->project->createTask            = '添加任务';
$lang->project->goback                = "返回任务列表";
$lang->project->noweekend             = '去除周末';
$lang->project->withweekend           = '显示周末';
$lang->project->interval              = '间隔';
$lang->project->fixFirstWithLeft      = '修改剩余工时';

/* 统计。*/
$lang->project->charts = new stdclass();
$lang->project->charts->burn = new stdclass();
$lang->project->charts->burn->graph = new stdclass();
$lang->project->charts->burn->graph->caption      = "燃尽图";
$lang->project->charts->burn->graph->xAxisName    = "日期";
$lang->project->charts->burn->graph->yAxisName    = "HOUR";
$lang->project->charts->burn->graph->baseFontSize = 12;
$lang->project->charts->burn->graph->formatNumber = 0;
$lang->project->charts->burn->graph->animation    = 0;
$lang->project->charts->burn->graph->rotateNames  = 1;
$lang->project->charts->burn->graph->showValues   = 0;

$lang->project->placeholder = new stdclass();
$lang->project->placeholder->code      = '团队内部的简称';
$lang->project->placeholder->totalLeft = '项目开始时的总预计工时';

$lang->project->selectGroup = new stdclass();
$lang->project->selectGroup->done = '(已结束)';

$lang->project->orderList['pri_asc']    = "需求优先级正序";
$lang->project->orderList['pri_desc']   = "需求优先级倒序";
$lang->project->orderList['id_asc']     = "需求ID正序";
$lang->project->orderList['id_desc']    = "需求ID倒序";
$lang->project->orderList['stage_asc']  = "需求阶段正序";
$lang->project->orderList['stage_desc'] = "需求阶段倒序";

$lang->project->kanban      = "看板";
$lang->project->printKanban = "打印看板";
$lang->project->bugList     = "Bug列表";

$lang->printKanban = new stdclass();
$lang->printKanban->common  = '看板打印';
$lang->printKanban->content = '内容';
$lang->printKanban->print   = '打印';

$lang->printKanban->taskStatus = '状态';

$lang->printKanban->typeList['all']       = '全部';
$lang->printKanban->typeList['increment'] = '增量';

$lang->project->featureBar['task']['unclosed']     = $lang->project->unclosed;
$lang->project->featureBar['task']['all']          = $lang->project->allTasks;
$lang->project->featureBar['task']['assignedtome'] = $lang->project->assignedToMe;
$lang->project->featureBar['task']['delayed']      = '已延期';
$lang->project->featureBar['task']['needconfirm']  = '需求变动';
$lang->project->featureBar['task']['status']       = $lang->project->statusSelects[''];

$lang->project->treeLevel = array();
$lang->project->treeLevel['root']    = '全部折叠';
$lang->project->treeLevel['story']   = '显示需求';
$lang->project->treeLevel['task']    = '显示任务';
$lang->project->treeLevel['all']     = '全部展开';

global $config;
if($config->global->flow == 'onlyTask')
{
    unset($lang->project->groups['story']);
    unset($lang->project->featureBar['task']['needconfirm']);
}
