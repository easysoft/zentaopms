<?php
/**
 * The report module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     report
 * @version     $Id: zh-cn.php 5080 2013-07-10 00:46:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->report->common     = '统计视图';
$lang->report->index      = '统计首页';
$lang->report->list       = '统计报表';
$lang->report->item       = '条目';
$lang->report->value      = '值';
$lang->report->percent    = '百分比';
$lang->report->undefined  = '未设定';
$lang->report->query      = '查询';
$lang->report->annual     = '年度总结';

$lang->report->colors[]   = 'AFD8F8';
$lang->report->colors[]   = 'F6BD0F';
$lang->report->colors[]   = '8BBA00';
$lang->report->colors[]   = 'FF8E46';
$lang->report->colors[]   = '008E8E';
$lang->report->colors[]   = 'D64646';
$lang->report->colors[]   = '8E468E';
$lang->report->colors[]   = '588526';
$lang->report->colors[]   = 'B3AA00';
$lang->report->colors[]   = '008ED6';
$lang->report->colors[]   = '9D080D';
$lang->report->colors[]   = 'A186BE';

$lang->report->assign['noassign'] = '未指派';
$lang->report->assign['assign'] = '已指派';

$lang->report->singleColor[] = 'F6BD0F';

$lang->report->projectDeviation = $lang->projectCommon . '偏差报表';
$lang->report->productSummary   = $lang->productCommon . '汇总表';
$lang->report->bugCreate        = 'Bug创建表';
$lang->report->bugAssign        = 'Bug指派表';
$lang->report->workload         = '员工负载表';
$lang->report->workloadAB       = '工作负载';
$lang->report->bugOpenedDate    = 'Bug创建时间';
$lang->report->beginAndEnd      = '起止时间';
$lang->report->dept             = '部门';
$lang->report->deviationChart   = $lang->projectCommon . '偏差曲线';

$lang->reportList->project->lists[10] = $lang->projectCommon . '偏差报表|report|projectdeviation';
$lang->reportList->product->lists[10] = $lang->productCommon . '汇总表|report|productsummary';
$lang->reportList->test->lists[10]    = 'Bug创建表|report|bugcreate';
$lang->reportList->test->lists[13]    = 'Bug指派表|report|bugassign';
$lang->reportList->staff->lists[10]   = '员工负载表|report|workload';

$lang->report->id            = '编号';
$lang->report->project       = $lang->projectCommon;
$lang->report->product       = $lang->productCommon;
$lang->report->user          = '姓名';
$lang->report->bugTotal      = 'Bug';
$lang->report->task          = '任务数';
$lang->report->estimate      = '总预计';
$lang->report->consumed      = '总消耗';
$lang->report->remain        = '剩余工时';
$lang->report->deviation     = '偏差';
$lang->report->deviationRate = '偏差率';
$lang->report->total         = '总计';
$lang->report->to            = '至';
$lang->report->taskTotal     = "总任务数";
$lang->report->manhourTotal  = "总工时";
$lang->report->validRate     = "有效率";
$lang->report->validRateTips = "方案为已解决或延期/状态为已解决或已关闭";
$lang->report->unplanned     = '未计划';
$lang->report->workday       = '每天工时';
$lang->report->diffDays      = '工作日天数';

$lang->report->typeList['default'] = '默认';
$lang->report->typeList['pie']     = '饼图';
$lang->report->typeList['bar']     = '柱状图';
$lang->report->typeList['line']    = '折线图';

$lang->report->conditions    = '筛选条件：';
$lang->report->closedProduct = '关闭' . $lang->productCommon;
$lang->report->overduePlan   = '过期计划';

/* daily reminder. */
$lang->report->idAB         = 'ID';
$lang->report->bugTitle     = 'Bug标题';
$lang->report->taskName     = '任务名称';
$lang->report->todoName     = '待办名称';
$lang->report->testTaskName = '版本名称';
$lang->report->deadline     = '截止日期';

$lang->report->mailTitle           = new stdclass();
$lang->report->mailTitle->begin    = '提醒：您有';
$lang->report->mailTitle->bug      = " Bug(%s),";
$lang->report->mailTitle->task     = " 任务(%s),";
$lang->report->mailTitle->todo     = " 待办(%s),";
$lang->report->mailTitle->testTask = " 测试版本(%s),";

$lang->report->proVersion   = '<a href="https://api.zentao.net/goto.php?item=proversion&from=reportpage" target="_blank">更多精彩，尽在专业版！</a>';
$lang->report->proVersionEn = '<a href="http://api.zentao.pm/goto.php?item=proversion&from=reportpage" target="_blank">Try ZenTao Pro for more!</a>';

$lang->report->annualData = new stdclass();
$lang->report->annualData->title            = "%s年工作内容统计一览表 —— %s";
$lang->report->annualData->baseInfo         = "基本数据信息";
$lang->report->annualData->logins           = "累计登录次数";
$lang->report->annualData->actions          = "累计动态数";
$lang->report->annualData->efforts          = "累计日志数";
$lang->report->annualData->consumed         = "累计工时数";
$lang->report->annualData->foundBugs        = "累计创建Bug数";
$lang->report->annualData->createdCases     = "累计创建用例数";
$lang->report->annualData->involvedProducts = "累计参与{$lang->productCommon}数";
$lang->report->annualData->createdPlans     = "累计创建计划数";
$lang->report->annualData->createdStories   = "累计创建{$lang->storyCommon}数";

$lang->report->annualData->productOverview = "{$lang->productCommon}创建{$lang->storyCommon}数及占比";
$lang->report->annualData->qaOverview      = "{$lang->productCommon}创建Bug数及占比";
$lang->report->annualData->projectOverview = "参与{$lang->projectCommon}概览";
$lang->report->annualData->doneProject     = "已完成的{$lang->projectCommon}";
$lang->report->annualData->doingProject    = "正在进行的{$lang->projectCommon}";
$lang->report->annualData->suspendProject  = "已挂起的{$lang->projectCommon}";

$lang->report->annualData->projectName   = "{$lang->projectCommon}名称";
$lang->report->annualData->finishedStory = "完成{$lang->storyCommon}数";
$lang->report->annualData->finishedTask  = '完成任务数';
$lang->report->annualData->foundBug      = '创建Bug数';
$lang->report->annualData->resolvedBug   = '解决Bug数';
$lang->report->annualData->productName   = "{$lang->productCommon}名称";
$lang->report->annualData->planCount     = '计划数';
$lang->report->annualData->storyCount    = "{$lang->storyCommon}数";

$lang->report->annualData->qaData           = "累计创建Bug数和创建用例数";
$lang->report->annualData->totalCreatedBug  = '累计创建Bug数';
$lang->report->annualData->totalCreatedCase = '累计创建用例数';

$lang->report->annualData->devData           = "完成任务数和解决Bug数";
$lang->report->annualData->totalFinishedTask = '完成任务数';
$lang->report->annualData->totalResolvedBug  = '解决Bug数';
$lang->report->annualData->totalConsumed     = '累计工时';

$lang->report->annualData->poData          = "所创建{$lang->storyCommon}数和对应的优先级及状态";
$lang->report->annualData->totalStoryPri   = "创建{$lang->storyCommon}优先级分布";
$lang->report->annualData->totalStoryStage = "创建{$lang->storyCommon}阶段分布";

$lang->report->annualData->qaStatistics  = "月创建Bug数和创建用例数";
$lang->report->annualData->poStatistics  = "月创建{$lang->storyCommon}数";
$lang->report->annualData->devStatistics = "月完成任务数及累计工时和解决Bug数";

$lang->report->annualData->unit = "个";
