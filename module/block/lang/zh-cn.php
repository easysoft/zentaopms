<?php
/**
 * The zh-cn file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
global $config;
$lang->block->id         = '编号';
$lang->block->params     = '参数';
$lang->block->name       = '区块名称';
$lang->block->style      = '外观';
$lang->block->grid       = '位置';
$lang->block->color      = '颜色';
$lang->block->reset      = '恢复默认';
$lang->block->story      = '需求';
$lang->block->investment = '投入';
$lang->block->estimate   = '预计工时';
$lang->block->last       = '近期';

$lang->block->account = '所属用户';
$lang->block->module  = '所属模块';
$lang->block->title   = '区块名称';
$lang->block->source  = '来源模块';
$lang->block->block   = '来源区块';
$lang->block->order   = '排序';
$lang->block->height  = '高度';
$lang->block->role    = '角色';

$lang->block->lblModule    = '模块';
$lang->block->lblBlock     = '区块';
$lang->block->lblNum       = '条数';
$lang->block->lblHtml      = 'HTML内容';
$lang->block->dynamic      = '最新动态';
$lang->block->assignToMe   = '待处理';
$lang->block->wait         = '未开始';
$lang->block->doing        = '进行中';
$lang->block->done         = '已完成';
$lang->block->lblFlowchart = '流程图';
$lang->block->welcome      = '欢迎总览';
$lang->block->lblTesttask  = '查看测试详情';
$lang->block->contribute   = '我的贡献';
$lang->block->finish       = '已完成';
$lang->block->guide        = '使用帮助';

$lang->block->leftToday           = '今天剩余工作总计';
$lang->block->myTask              = '我的任务';
$lang->block->myStory             = "我的{$lang->SRCommon}";
$lang->block->myBug               = '我的BUG';
$lang->block->myExecution         = '未关闭的' . $lang->executionCommon;
$lang->block->myProduct           = '未关闭的' . $lang->productCommon;
$lang->block->delayed             = '已延期';
$lang->block->noData              = '当前统计类型下暂无数据';
$lang->block->emptyTip            = '暂无数据';
$lang->block->createdTodos        = '创建的待办数';
$lang->block->createdRequirements = '创建的' . $lang->URCommon . '数';
$lang->block->createdStories      = '创建的' . $lang->SRCommon . '数';
$lang->block->finishedTasks       = '完成的任务数';
$lang->block->createdBugs         = '提交的Bug数';
$lang->block->resolvedBugs        = '解决的Bug数';
$lang->block->createdCases        = '创建的用例数';
$lang->block->createdRisks        = '创建的风险数';
$lang->block->resolvedRisks       = '解决的风险数';
$lang->block->createdIssues       = '创建的问题数';
$lang->block->resolvedIssues      = '解决的问题数';
$lang->block->createdDocs         = '创建的文档数';
$lang->block->allExecutions       = '所有' . $lang->executionCommon;
$lang->block->doingExecution      = '进行中的' . $lang->executionCommon;
$lang->block->finishExecution     = '累积' . $lang->executionCommon;
$lang->block->estimatedHours      = '预计';
$lang->block->consumedHours       = '已消耗';
$lang->block->time                = '第';
$lang->block->week                = '周';
$lang->block->month               = '月';
$lang->block->selectProduct       = "选择{$lang->productCommon}";
$lang->block->of                  = '的';
$lang->block->remain              = '剩余工时';
$lang->block->allStories          = '总需求';

$lang->block->createBlock        = '添加区块';
$lang->block->editBlock          = '编辑区块';
$lang->block->ordersSaved        = '排序已保存';
$lang->block->confirmRemoveBlock = '确定隐藏区块吗？';
$lang->block->noticeNewBlock     = '10.0版本以后各个视图主页提供了全新的视图，您要启用新的视图布局吗？';
$lang->block->confirmReset       = '是否恢复默认布局？';
$lang->block->closeForever       = '永久关闭';
$lang->block->confirmClose       = '确定永久关闭该区块吗？关闭后所有人都将无法使用该区块，可以在后台自定义中打开。';
$lang->block->remove             = '移除';
$lang->block->refresh            = '刷新';
$lang->block->nbsp               = '';
$lang->block->hidden             = '隐藏';
$lang->block->dynamicInfo        = "<span class='timeline-tag'>%s</span> <span class='timeline-text'>%s<span class='label-action'>%s</span>%s<a href='%s' title='%s'>%s</a></span>";
$lang->block->noLinkDynamic      = "<span class='timeline-tag'>%s</span> <span class='timeline-text' title='%s'>%s<span class='label-action'>%s</span>%s<span class='label-name'>%s</span></span>";
$lang->block->cannotPlaceInLeft  = '此区块无法放置在左侧。';
$lang->block->cannotPlaceInRight = '此区块无法放置在右侧。';
$lang->block->tutorial           = '进入新手教程';

$lang->block->productName  = $lang->productCommon . '名称';
$lang->block->totalStory   = '总' . $lang->SRCommon;
$lang->block->totalBug     = '总Bug';
$lang->block->totalRelease = '发布次数';
$lang->block->totalTask    = '总' . $lang->task->common;

$lang->block->totalInvestment = '总投入';
$lang->block->totalPeople     = '总人数';
$lang->block->spent           = '已花费';
$lang->block->budget          = '预算';
$lang->block->left            = '剩余';

$lang->block->titleList['flowchart']      = '流程图';
$lang->block->titleList['guide']          = '使用帮助';
$lang->block->titleList['statistic']      = "{$lang->projectCommon}统计";
$lang->block->titleList['recentproject']  = "我近期参与的{$lang->projectCommon}";
$lang->block->titleList['assigntome']     = '待处理';
$lang->block->titleList['projectteam']    = "{$lang->projectCommon}人力投入";
$lang->block->titleList['project']        = "{$lang->projectCommon}列表";
$lang->block->titleList['dynamic']        = '最新动态';
$lang->block->titleList['list']           = '我的待办';
$lang->block->titleList['contribute']     = '我的贡献';
$lang->block->titleList['scrumoverview']  = "{$lang->projectCommon}概况";
$lang->block->titleList['scrumtest']      = '待测版本';
$lang->block->titleList['scrumlist']      = '迭代列表';
$lang->block->titleList['sprint']         = '迭代总览';
$lang->block->titleList['projectdynamic'] = '最新动态';
$lang->block->titleList['bug']            = '指派给我的Bug';
$lang->block->titleList['case']           = '指派给我的用例';
$lang->block->titleList['testtask']       = '待测版本列表';

$lang->block->default['waterfall']['project']['3']['title']  = "{$lang->projectCommon}计划";
$lang->block->default['waterfall']['project']['3']['block']  = 'waterfallgantt';
$lang->block->default['waterfall']['project']['3']['source'] = 'project';
$lang->block->default['waterfall']['project']['3']['grid']   = 8;

$lang->block->default['waterfall']['project']['6']['title']  = '最新动态';
$lang->block->default['waterfall']['project']['6']['block']  = 'projectdynamic';
$lang->block->default['waterfall']['project']['6']['grid']   = 4;
$lang->block->default['waterfall']['project']['6']['source'] = 'project';

$lang->block->default['waterfallplus'] = $lang->block->default['waterfall'];
$lang->block->default['ipd']           = $lang->block->default['waterfall'];

$lang->block->default['scrum']['project']['1']['title'] = $lang->projectCommon . '概况';
$lang->block->default['scrum']['project']['1']['block'] = 'scrumoverview';
$lang->block->default['scrum']['project']['1']['grid']  = 8;

$lang->block->default['scrum']['project']['2']['title'] = $lang->executionCommon . '列表';
$lang->block->default['scrum']['project']['2']['block'] = 'scrumlist';
$lang->block->default['scrum']['project']['2']['grid']  = 8;

$lang->block->default['scrum']['project']['2']['params']['type']    = 'undone';
$lang->block->default['scrum']['project']['2']['params']['count']   = '20';
$lang->block->default['scrum']['project']['2']['params']['orderBy'] = 'id_desc';

$lang->block->default['scrum']['project']['3']['title'] = '待测版本';
$lang->block->default['scrum']['project']['3']['block'] = 'scrumtest';
$lang->block->default['scrum']['project']['3']['grid']  = 8;

$lang->block->default['scrum']['project']['3']['params']['type']    = 'wait';
$lang->block->default['scrum']['project']['3']['params']['count']   = '15';
$lang->block->default['scrum']['project']['3']['params']['orderBy'] = 'id_desc';

$lang->block->default['scrum']['project']['4']['title'] = $lang->executionCommon . '总览';
$lang->block->default['scrum']['project']['4']['block'] = 'sprint';
$lang->block->default['scrum']['project']['4']['grid']  = 4;

$lang->block->default['scrum']['project']['5']['title'] = '最新动态';
$lang->block->default['scrum']['project']['5']['block'] = 'projectdynamic';
$lang->block->default['scrum']['project']['5']['grid']  = 4;
$lang->block->default['kanban']    = $lang->block->default['scrum'];
$lang->block->default['agileplus'] = $lang->block->default['scrum'];

$lang->block->default['product']['1']['title'] = $lang->productCommon . '统计';
$lang->block->default['product']['1']['block'] = 'statistic';
$lang->block->default['product']['1']['grid']  = 8;

$lang->block->default['product']['1']['params']['type']  = 'all';
$lang->block->default['product']['1']['params']['count'] = '20';

$lang->block->default['product']['2']['title'] = $lang->productCommon . '总览';
$lang->block->default['product']['2']['block'] = 'overview';
$lang->block->default['product']['2']['grid']  = 4;

$lang->block->default['product']['3']['title'] = '未关闭的' . $lang->productCommon;
$lang->block->default['product']['3']['block'] = 'list';
$lang->block->default['product']['3']['grid']  = 8;

$lang->block->default['product']['3']['params']['count'] = 15;
$lang->block->default['product']['3']['params']['type']  = 'noclosed';

$lang->block->default['product']['4']['title'] = "指派给我的{$lang->SRCommon}";
$lang->block->default['product']['4']['block'] = 'story';
$lang->block->default['product']['4']['grid']  = 4;

$lang->block->default['product']['4']['params']['count']   = 15;
$lang->block->default['product']['4']['params']['orderBy'] = 'id_desc';
$lang->block->default['product']['4']['params']['type']    = 'assignedTo';

$lang->block->default['execution']['1']['title'] = '执行统计';
$lang->block->default['execution']['1']['block'] = 'statistic';
$lang->block->default['execution']['1']['grid']  = 8;

$lang->block->default['execution']['1']['params']['type']  = 'all';
$lang->block->default['execution']['1']['params']['count'] = '20';

$lang->block->default['execution']['2']['title'] = '执行总览';
$lang->block->default['execution']['2']['block'] = 'overview';
$lang->block->default['execution']['2']['grid']  = 4;

$lang->block->default['execution']['3']['title'] = '未关闭的执行';
$lang->block->default['execution']['3']['block'] = 'list';
$lang->block->default['execution']['3']['grid']  = 8;

$lang->block->default['execution']['3']['params']['count']   = 15;
$lang->block->default['execution']['3']['params']['orderBy'] = 'id_desc';
$lang->block->default['execution']['3']['params']['type']    = 'undone';

$lang->block->default['execution']['4']['title'] = '指派给我的任务';
$lang->block->default['execution']['4']['block'] = 'task';
$lang->block->default['execution']['4']['grid']  = 4;

$lang->block->default['execution']['4']['params']['count']   = 15;
$lang->block->default['execution']['4']['params']['orderBy'] = 'id_desc';
$lang->block->default['execution']['4']['params']['type']    = 'assignedTo';

$lang->block->default['execution']['5']['title'] = '版本列表';
$lang->block->default['execution']['5']['block'] = 'build';
$lang->block->default['execution']['5']['grid']  = 8;

$lang->block->default['execution']['5']['params']['count']   = 15;
$lang->block->default['execution']['5']['params']['orderBy'] = 'id_desc';

$lang->block->default['qa']['1']['title'] = '测试统计';
$lang->block->default['qa']['1']['block'] = 'statistic';
$lang->block->default['qa']['1']['grid']  = 8;

$lang->block->default['qa']['1']['params']['type']  = 'noclosed';
$lang->block->default['qa']['1']['params']['count'] = '20';

//$lang->block->default['qa']['2']['title'] = '测试用例总览';
//$lang->block->default['qa']['2']['block'] = 'overview';
//$lang->block->default['qa']['2']['grid']  = 4;

$lang->block->default['qa']['2']['title'] = '指派给我的Bug';
$lang->block->default['qa']['2']['block'] = 'bug';
$lang->block->default['qa']['2']['grid']  = 4;

$lang->block->default['qa']['2']['params']['count']   = 15;
$lang->block->default['qa']['2']['params']['orderBy'] = 'id_desc';
$lang->block->default['qa']['2']['params']['type']    = 'assignedTo';

$lang->block->default['qa']['3']['title'] = '指派给我的用例';
$lang->block->default['qa']['3']['block'] = 'case';
$lang->block->default['qa']['3']['grid']  = 4;

$lang->block->default['qa']['3']['params']['count']   = 15;
$lang->block->default['qa']['3']['params']['orderBy'] = 'id_desc';
$lang->block->default['qa']['3']['params']['type']    = 'assigntome';

$lang->block->default['qa']['4']['title'] = '待测版本列表';
$lang->block->default['qa']['4']['block'] = 'testtask';
$lang->block->default['qa']['4']['grid']  = 8;

$lang->block->default['qa']['4']['params']['count']   = 15;
$lang->block->default['qa']['4']['params']['orderBy'] = 'id_desc';
$lang->block->default['qa']['4']['params']['type']    = 'wait';

$lang->block->default['full']['my']['1']['title']  = '欢迎';
$lang->block->default['full']['my']['1']['block']  = 'welcome';
$lang->block->default['full']['my']['1']['grid']   = 8;
$lang->block->default['full']['my']['1']['source'] = '';

$lang->block->default['full']['my']['2']['title']  = '最新动态';
$lang->block->default['full']['my']['2']['block']  = 'dynamic';
$lang->block->default['full']['my']['2']['grid']   = 4;
$lang->block->default['full']['my']['2']['source'] = '';

$lang->block->default['full']['my']['3']['title']  = '使用帮助';
$lang->block->default['full']['my']['3']['block']  = 'guide';
$lang->block->default['full']['my']['3']['source'] = '';
$lang->block->default['full']['my']['3']['grid']   = 8;

$lang->block->default['full']['my']['4']['title']           = '我的待办';
$lang->block->default['full']['my']['4']['block']           = 'list';
$lang->block->default['full']['my']['4']['grid']            = 4;
$lang->block->default['full']['my']['4']['source']          = 'todo';
$lang->block->default['full']['my']['4']['params']['count'] = '20';

$lang->block->default['full']['my']['5']['title']           = "{$lang->projectCommon}统计";
$lang->block->default['full']['my']['5']['block']           = 'statistic';
$lang->block->default['full']['my']['5']['source']          = 'project';
$lang->block->default['full']['my']['5']['grid']            = 8;
$lang->block->default['full']['my']['5']['params']['count'] = '20';

$lang->block->default['full']['my']['6']['title']  = '我的贡献';
$lang->block->default['full']['my']['6']['block']  = 'contribute';
$lang->block->default['full']['my']['6']['source'] = '';
$lang->block->default['full']['my']['6']['grid']   = 4;

$lang->block->default['full']['my']['7']['title']  = "我近期参与的{$lang->projectCommon}";
$lang->block->default['full']['my']['7']['block']  = 'recentproject';
$lang->block->default['full']['my']['7']['source'] = 'project';
$lang->block->default['full']['my']['7']['grid']   = 8;

$lang->block->default['full']['my']['8']['title']  = '我的待处理';
$lang->block->default['full']['my']['8']['block']  = 'assigntome';
$lang->block->default['full']['my']['8']['source'] = '';
$lang->block->default['full']['my']['8']['grid']   = 8;

$lang->block->default['full']['my']['8']['params']['todoCount']     = '20';
$lang->block->default['full']['my']['8']['params']['taskCount']     = '20';
$lang->block->default['full']['my']['8']['params']['bugCount']      = '20';
$lang->block->default['full']['my']['8']['params']['riskCount']     = '20';
$lang->block->default['full']['my']['8']['params']['issueCount']    = '20';
$lang->block->default['full']['my']['8']['params']['storyCount']    = '20';
$lang->block->default['full']['my']['8']['params']['reviewCount']   = '20';
$lang->block->default['full']['my']['8']['params']['meetingCount']  = '20';
$lang->block->default['full']['my']['8']['params']['feedbackCount'] = '20';

$lang->block->default['full']['my']['9']['title']  = "{$lang->projectCommon}人力投入";
$lang->block->default['full']['my']['9']['block']  = 'projectteam';
$lang->block->default['full']['my']['9']['source'] = 'project';
$lang->block->default['full']['my']['9']['grid']   = 8;

$lang->block->default['full']['my']['10']['title']  = "{$lang->projectCommon}列表";
$lang->block->default['full']['my']['10']['block']  = 'project';
$lang->block->default['full']['my']['10']['source'] = 'project';
$lang->block->default['full']['my']['10']['grid']   = 8;

$lang->block->default['full']['my']['10']['params']['orderBy'] = 'id_desc';
$lang->block->default['full']['my']['10']['params']['count']   = '15';

/* Doc module block. */
$lang->block->default['doc']['1']['title'] = '文档统计';
$lang->block->default['doc']['1']['block'] = 'docstatistic';
$lang->block->default['doc']['1']['grid']  = 8;

$lang->block->default['doc']['2']['title'] = '文档动态';
$lang->block->default['doc']['2']['block'] = 'docdynamic';
$lang->block->default['doc']['2']['grid']  = 4;

$lang->block->default['doc']['3']['title'] = '我收藏的文档';
$lang->block->default['doc']['3']['block'] = 'docmycollection';
$lang->block->default['doc']['3']['grid']  = 8;

$lang->block->default['doc']['4']['title'] = '最近更新的文档';
$lang->block->default['doc']['4']['block'] = 'docrecentupdate';
$lang->block->default['doc']['4']['grid']  = 8;

$lang->block->default['doc']['5']['title'] = '浏览排行榜';
$lang->block->default['doc']['5']['block'] = 'docviewlist';
$lang->block->default['doc']['5']['grid']  = 4;

if($config->vision == 'rnd')
{
    $lang->block->default['doc']['6']['title'] = $lang->productCommon . '文档';
    $lang->block->default['doc']['6']['block'] = 'productdoc';
    $lang->block->default['doc']['6']['grid']  = 8;

    $lang->block->default['doc']['6']['params']['count'] = '20';
}

$lang->block->default['doc']['7']['title'] = '收藏排行榜';
$lang->block->default['doc']['7']['block'] = 'doccollectlist';
$lang->block->default['doc']['7']['grid']  = 4;

$lang->block->default['doc']['8']['title'] = $lang->projectCommon . '文档';
$lang->block->default['doc']['8']['block'] = 'projectdoc';
$lang->block->default['doc']['8']['grid']  = 8;

$lang->block->default['doc']['8']['params']['count'] = '20';

$lang->block->count   = '数量';
$lang->block->type    = '类型';
$lang->block->orderBy = '排序';

$lang->block->availableBlocks              = new stdclass();
$lang->block->availableBlocks->todo        = '日程';
$lang->block->availableBlocks->task        = '任务';
$lang->block->availableBlocks->bug         = 'Bug';
$lang->block->availableBlocks->case        = '用例';
$lang->block->availableBlocks->story       = "{$lang->SRCommon}";
$lang->block->availableBlocks->requirement = "{$lang->URCommon}";
$lang->block->availableBlocks->product     = $lang->productCommon . '列表';
$lang->block->availableBlocks->execution   = $lang->executionCommon . '列表';
$lang->block->availableBlocks->plan        = "计划列表";
$lang->block->availableBlocks->release     = '发布列表';
$lang->block->availableBlocks->build       = '版本列表';
$lang->block->availableBlocks->testtask    = '测试版本列表';
$lang->block->availableBlocks->risk        = '风险';
$lang->block->availableBlocks->issue       = '问题';
$lang->block->availableBlocks->meeting     = '会议';
$lang->block->availableBlocks->feedback    = '反馈';
$lang->block->availableBlocks->ticket      = '工单';

$lang->block->moduleList['product']   = $lang->productCommon;
$lang->block->moduleList['project']   = $lang->projectCommon;
$lang->block->moduleList['execution'] = $lang->execution->common;
$lang->block->moduleList['qa']        = '测试';
$lang->block->moduleList['todo']      = '待办';
$lang->block->moduleList['doc']       = '文档';

$lang->block->modules['project'] = new stdclass();
$lang->block->modules['project']->availableBlocks = new stdclass();
$lang->block->modules['project']->availableBlocks->project       = "{$lang->projectCommon}列表";
$lang->block->modules['project']->availableBlocks->recentproject = "近期{$lang->projectCommon}";
$lang->block->modules['project']->availableBlocks->statistic     = "{$lang->projectCommon}统计";
$lang->block->modules['project']->availableBlocks->projectteam   = "{$lang->projectCommon}人力投入";

$lang->block->modules['scrum']['index'] = new stdclass();
$lang->block->modules['scrum']['index']->availableBlocks = new stdclass();
$lang->block->modules['scrum']['index']->availableBlocks->scrumoverview  = "{$lang->projectCommon}概况";
$lang->block->modules['scrum']['index']->availableBlocks->scrumlist      = $lang->executionCommon . '列表';
$lang->block->modules['scrum']['index']->availableBlocks->sprint         = $lang->executionCommon . '总览';
$lang->block->modules['scrum']['index']->availableBlocks->scrumtest      = '待测版本';
$lang->block->modules['scrum']['index']->availableBlocks->projectdynamic = '最新动态';

$lang->block->modules['agileplus']['index'] = $lang->block->modules['scrum']['index'];

$lang->block->modules['waterfall']['index'] = new stdclass();
$lang->block->modules['waterfall']['index']->availableBlocks = new stdclass();
$lang->block->modules['waterfall']['index']->availableBlocks->waterfallgantt    = "{$lang->projectCommon}计划";
$lang->block->modules['waterfall']['index']->availableBlocks->projectdynamic    = '最新动态';

$lang->block->modules['waterfallplus']['index'] = $lang->block->modules['waterfall']['index'];
$lang->block->modules['ipd']['index']           = $lang->block->modules['waterfall']['index'];

$lang->block->modules['product'] = new stdclass();
$lang->block->modules['product']->availableBlocks = new stdclass();
$lang->block->modules['product']->availableBlocks->overview  = $lang->productCommon . '总览';
if($this->config->vision != 'or')
{
    $lang->block->modules['product']->availableBlocks->statistic = $lang->productCommon . '统计';
    $lang->block->modules['product']->availableBlocks->list      = $lang->productCommon . '列表';
    $lang->block->modules['product']->availableBlocks->story     = "{$lang->SRCommon}列表";
    $lang->block->modules['product']->availableBlocks->plan      = "计划列表";
    $lang->block->modules['product']->availableBlocks->release   = '发布列表';
}

$lang->block->modules['execution'] = new stdclass();
$lang->block->modules['execution']->availableBlocks = new stdclass();
$lang->block->modules['execution']->availableBlocks->statistic = $lang->execution->common . '统计';
$lang->block->modules['execution']->availableBlocks->overview  = $lang->execution->common . '总览';
$lang->block->modules['execution']->availableBlocks->list      = $lang->execution->common . '列表';
$lang->block->modules['execution']->availableBlocks->task      = '任务列表';
$lang->block->modules['execution']->availableBlocks->build     = '版本列表';

$lang->block->modules['qa'] = new stdclass();
$lang->block->modules['qa']->availableBlocks = new stdclass();
$lang->block->modules['qa']->availableBlocks->statistic = '测试统计';
//$lang->block->modules['qa']->availableBlocks->overview  = '测试用例总览';
$lang->block->modules['qa']->availableBlocks->bug       = 'Bug列表';
$lang->block->modules['qa']->availableBlocks->case      = '用例列表';
$lang->block->modules['qa']->availableBlocks->testtask  = '版本列表';

$lang->block->modules['todo'] = new stdclass();
$lang->block->modules['todo']->availableBlocks = new stdclass();
$lang->block->modules['todo']->availableBlocks->list = '待办列表';

$lang->block->modules['doc'] = new stdclass();
$lang->block->modules['doc']->availableBlocks = new stdclass();
$lang->block->modules['doc']->availableBlocks->docstatistic    = '文档统计';
$lang->block->modules['doc']->availableBlocks->docdynamic      = '文档动态';
$lang->block->modules['doc']->availableBlocks->docmycollection = '我的收藏';
$lang->block->modules['doc']->availableBlocks->docrecentupdate = '最近更新';
$lang->block->modules['doc']->availableBlocks->docviewlist     = '浏览排行榜';
if($config->vision == 'rnd') $lang->block->modules['doc']->availableBlocks->productdoc      = $lang->productCommon . '文档';
$lang->block->modules['doc']->availableBlocks->doccollectlist  = '收藏排行榜';
$lang->block->modules['doc']->availableBlocks->projectdoc      = $lang->projectCommon . '文档';

$lang->block->orderByList = new stdclass();

$lang->block->orderByList->product = array();
$lang->block->orderByList->product['id_asc']      = 'ID 递增';
$lang->block->orderByList->product['id_desc']     = 'ID 递减';
$lang->block->orderByList->product['status_asc']  = '状态正序';
$lang->block->orderByList->product['status_desc'] = '状态倒序';

$lang->block->orderByList->project = array();
$lang->block->orderByList->project['id_asc']      = 'ID 递增';
$lang->block->orderByList->project['id_desc']     = 'ID 递减';
$lang->block->orderByList->project['status_asc']  = '状态正序';
$lang->block->orderByList->project['status_desc'] = '状态倒序';

$lang->block->orderByList->execution = array();
$lang->block->orderByList->execution['id_asc']      = 'ID 递增';
$lang->block->orderByList->execution['id_desc']     = 'ID 递减';
$lang->block->orderByList->execution['status_asc']  = '状态正序';
$lang->block->orderByList->execution['status_desc'] = '状态倒序';

$lang->block->orderByList->task = array();
$lang->block->orderByList->task['id_asc']        = 'ID 递增';
$lang->block->orderByList->task['id_desc']       = 'ID 递减';
$lang->block->orderByList->task['pri_asc']       = '优先级递增';
$lang->block->orderByList->task['pri_desc']      = '优先级递减';
$lang->block->orderByList->task['estimate_asc']  = '预计时间递增';
$lang->block->orderByList->task['estimate_desc'] = '预计时间递减';
$lang->block->orderByList->task['status_asc']    = '状态正序';
$lang->block->orderByList->task['status_desc']   = '状态倒序';
$lang->block->orderByList->task['deadline_asc']  = '截止日期递增';
$lang->block->orderByList->task['deadline_desc'] = '截止日期递减';

$lang->block->orderByList->bug = array();
$lang->block->orderByList->bug['id_asc']        = 'ID 递增';
$lang->block->orderByList->bug['id_desc']       = 'ID 递减';
$lang->block->orderByList->bug['pri_asc']       = '优先级递增';
$lang->block->orderByList->bug['pri_desc']      = '优先级递减';
$lang->block->orderByList->bug['severity_asc']  = '级别递增';
$lang->block->orderByList->bug['severity_desc'] = '级别递减';

$lang->block->orderByList->case = array();
$lang->block->orderByList->case['id_asc']   = 'ID 递增';
$lang->block->orderByList->case['id_desc']  = 'ID 递减';
$lang->block->orderByList->case['pri_asc']  = '优先级递增';
$lang->block->orderByList->case['pri_desc'] = '优先级递减';

$lang->block->orderByList->story = array();
$lang->block->orderByList->story['id_asc']      = 'ID 递增';
$lang->block->orderByList->story['id_desc']     = 'ID 递减';
$lang->block->orderByList->story['pri_asc']     = '优先级递增';
$lang->block->orderByList->story['pri_desc']    = '优先级递减';
$lang->block->orderByList->story['status_asc']  = '状态正序';
$lang->block->orderByList->story['status_desc'] = '状态倒序';
$lang->block->orderByList->story['stage_asc']   = '阶段正序';
$lang->block->orderByList->story['stage_desc']  = '阶段倒序';

$lang->block->todoCount     = '待办数';
$lang->block->taskCount     = '任务数';
$lang->block->bugCount      = 'Bug数';
$lang->block->riskCount     = '风险数';
$lang->block->issueCount    = '问题数';
$lang->block->storyCount    = '需求数';
$lang->block->reviewCount   = '审批数';
$lang->block->meetingCount  = '会议数';
$lang->block->feedbackCount = '反馈数';
$lang->block->ticketCount   = '工单数';

$lang->block->typeList = new stdclass();

$lang->block->typeList->task['assignedTo'] = '指派给我';
$lang->block->typeList->task['openedBy']   = '由我创建';
$lang->block->typeList->task['finishedBy'] = '由我完成';
$lang->block->typeList->task['closedBy']   = '由我关闭';
$lang->block->typeList->task['canceledBy'] = '由我取消';

$lang->block->typeList->bug['assignedTo'] = '指派给我';
$lang->block->typeList->bug['openedBy']   = '由我创建';
$lang->block->typeList->bug['resolvedBy'] = '由我解决';
$lang->block->typeList->bug['closedBy']   = '由我关闭';

$lang->block->typeList->case['assigntome'] = '指派给我';
$lang->block->typeList->case['openedbyme'] = '由我创建';

$lang->block->typeList->story['assignedTo'] = '指派给我';
$lang->block->typeList->story['openedBy']   = '由我创建';
$lang->block->typeList->story['reviewedBy'] = '由我评审';
$lang->block->typeList->story['closedBy']   = '由我关闭';

$lang->block->typeList->product['noclosed'] = '未关闭';
$lang->block->typeList->product['closed']   = '已关闭';
$lang->block->typeList->product['all']      = '全部';
$lang->block->typeList->product['involved'] = '我参与';

$lang->block->typeList->project['undone']   = '未完成';
$lang->block->typeList->project['doing']    = '进行中';
$lang->block->typeList->project['all']      = '全部';
$lang->block->typeList->project['involved'] = '我参与的';

$lang->block->typeList->execution['undone']   = '未完成';
$lang->block->typeList->execution['doing']    = '进行中';
$lang->block->typeList->execution['all']      = '所有';
$lang->block->typeList->execution['involved'] = '我参与';

$lang->block->typeList->scrum['undone']   = '未完成';
$lang->block->typeList->scrum['doing']    = '进行中';
$lang->block->typeList->scrum['all']      = '全部';
$lang->block->typeList->scrum['involved'] = '我参与';

$lang->block->typeList->testtask['wait']    = '待测版本';
$lang->block->typeList->testtask['doing']   = '测试中版本';
$lang->block->typeList->testtask['blocked'] = '阻塞版本';
$lang->block->typeList->testtask['done']    = '已测版本';
$lang->block->typeList->testtask['all']     = '全部';

$lang->block->welcomeList['06:00'] = '%s，早上好！';
$lang->block->welcomeList['11:30'] = '%s，中午好！';
$lang->block->welcomeList['13:30'] = '%s，下午好！';
$lang->block->welcomeList['19:00'] = '%s，晚上好！';

$lang->block->gridOptions[8] = '左侧';
$lang->block->gridOptions[4] = '右侧';

$lang->block->flowchart            = array();
$lang->block->flowchart['admin']   = array('管理员', '维护部门', '添加用户', '维护权限');
if($config->systemMode == 'ALM') $lang->block->flowchart['program'] = array('项目集负责人', '创建项目集', "关联{$lang->productCommon}", "创建{$lang->projectCommon}", "制定预算和规划", '添加干系人');
$lang->block->flowchart['product'] = array($lang->productCommon . '经理', '创建' . $lang->productCommon, '维护模块', "维护计划", "维护需求", '创建发布');
$lang->block->flowchart['project'] = array('项目经理', "创建{$lang->productCommon}、" . $lang->execution->common, '维护团队', "关联需求", '分解任务', '跟踪进度');
$lang->block->flowchart['dev']     = array('研发人员', '领取任务和Bug', '设计实现方案', '更新状态', '完成任务和Bug', '提交代码');
$lang->block->flowchart['tester']  = array('测试人员', '撰写用例', '执行用例', '提交Bug', '验证Bug', '关闭Bug');

$lang->block->zentaoapp = new stdclass();
$lang->block->zentaoapp->common               = '禅道移动端';
$lang->block->zentaoapp->thisYearInvestment   = '今年投入';
$lang->block->zentaoapp->sinceTotalInvestment = '从使用至今，总投入';
$lang->block->zentaoapp->myStory              = '我的需求';
$lang->block->zentaoapp->allStorySum          = '需求总数';
$lang->block->zentaoapp->storyCompleteRate    = '需求完成率';
$lang->block->zentaoapp->latestExecution      = '近期执行';
$lang->block->zentaoapp->involvedExecution    = '我参与的执行';
$lang->block->zentaoapp->mangedProduct        = "负责{$lang->productCommon}";
$lang->block->zentaoapp->involvedProject      = "参与{$lang->projectCommon}";
$lang->block->zentaoapp->customIndexCard      = '定制首页卡片';
$lang->block->zentaoapp->createStory          = '提需求';
$lang->block->zentaoapp->createEffort         = '记日志';
$lang->block->zentaoapp->createDoc            = '建文档';
$lang->block->zentaoapp->createTodo           = '建待办';
$lang->block->zentaoapp->workbench            = '工作台';
$lang->block->zentaoapp->notSupportKanban     = '移动端暂不支持研发看板模式';
$lang->block->zentaoapp->notSupportVersion    = '移动端暂不支持该禅道版本';
$lang->block->zentaoapp->incompatibleVersion  = '当前禅道版本较低，请升级至最新版本后再试';
$lang->block->zentaoapp->canNotGetVersion     = '获取禅道版本失败，请确认网址是否正确';
$lang->block->zentaoapp->desc                 = "禅道移动端为您提供移动办公的环境，方便随时管理个人待办事务，跟进{$lang->projectCommon}进度，增强了{$lang->projectCommon}管理的灵活性和敏捷性。";
$lang->block->zentaoapp->downloadTip          = '扫描二维码下载';

$lang->block->zentaoclient = new stdClass();
$lang->block->zentaoclient->common = '禅道客户端';
$lang->block->zentaoclient->desc   = '您可以使用禅道桌面客户端直接使用禅道，无需频繁切换浏览器。除此之外，客户端还提供了聊天，信息通知，机器人，内嵌禅道小程序等功能，团队协作更方便。';

$lang->block->zentaoclient->edition = new stdclass();
$lang->block->zentaoclient->edition->win64   = 'Windows版';
$lang->block->zentaoclient->edition->linux64 = 'Linux版';
$lang->block->zentaoclient->edition->mac64   = 'Mac版';

$lang->block->guideTabs['flowchart']      = '流程图';
//$lang->block->guideTabs['systemMode']     = '运行模式';
$lang->block->guideTabs['visionSwitch']   = '界面切换';
$lang->block->guideTabs['themeSwitch']    = '主题切换';
$lang->block->guideTabs['preference']     = '个性化设置';
$lang->block->guideTabs['downloadClient'] = '客户端下载';
$lang->block->guideTabs['downloadMoblie'] = '移动端下载';

$lang->block->themes['default']    = '禅道蓝';
$lang->block->themes['blue']       = '青春蓝';
$lang->block->themes['green']      = '叶兰绿';
$lang->block->themes['red']        = '赤诚红';
$lang->block->themes['pink']       = '芙蕖粉';
$lang->block->themes['blackberry'] = '露莓黑';
$lang->block->themes['classic']    = '经典蓝';
$lang->block->themes['purple']     = '玉烟紫';

$lang->block->visionTitle            = '禅道使用界面分为【研发综合界面】和【运营管理界面】。';
$lang->block->visions['rnd']         = new stdclass();
$lang->block->visions['rnd']->key    = 'rnd';
$lang->block->visions['rnd']->title  = '研发综合界面';
$lang->block->visions['rnd']->text   = "集项目集、{$lang->productCommon}、{$lang->projectCommon}、执行、测试等多维度管理于一体，提供全过程{$lang->projectCommon}管理解决方案。";
$lang->block->visions['lite']        = new stdclass();
$lang->block->visions['lite']->key   = 'lite';
$lang->block->visions['lite']->title = '运营管理界面';
$lang->block->visions['lite']->text  = "专为非研发团队打造，主要以直观、可视化的看板{$lang->projectCommon}管理模型为主。";
if($config->edition == 'ipd')
{
    $lang->block->visionTitle = '禅道使用界面分为【需求与市场管理界面】【IPD研发管理界面】和【运营管理界面】。';

    $lang->block->visions['rnd']->title = 'IPD研发管理界面';
    $lang->block->visions['rnd']->text  = "正确地做事，集项目集、{$lang->productCommon}、{$lang->projectCommon}、执行、测试等多维度管理于一体，提供全过程{$lang->projectCommon}管理解决方案。";

    $lang->block->visions['or']        = new stdclass();
    $lang->block->visions['or']->key   = 'or';
    $lang->block->visions['or']->title = '需求与市场管理界面';
    $lang->block->visions['or']->text  = "做正确的事，融合了需求池、需求、{$lang->productCommon}、路标规划、立项等需求与市场管理功能。";
}

$lang->block->customModes['light'] = '轻量管理模式';
$lang->block->customModes['ALM']   = '全生命周期管理模式';

$lang->block->customModeTip = new stdClass();
$lang->block->customModeTip->common = '禅道运行模式分为【轻量级管理模式】和【全生命周期管理模式】。';
$lang->block->customModeTip->ALM    = '适用于中大型团队的管理模式，概念更加完整、严谨，功能更丰富。';
$lang->block->customModeTip->light  = "适用于小型研发团队的管理模式，提供{$lang->projectCommon}管理的核心功能。";
