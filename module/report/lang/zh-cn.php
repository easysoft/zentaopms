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
$lang->report->assign['assign']   = '已指派';

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
$lang->report->annualData->title            = "%s %s年工作汇总";
$lang->report->annualData->exportByZentao   = "由禅道系统导出";
$lang->report->annualData->scope            = "统计范围";
$lang->report->annualData->allUser          = "所有用户";
$lang->report->annualData->allDept          = "全公司";
$lang->report->annualData->soFar            = "（%s年）";
$lang->report->annualData->baseInfo         = "基本数据";
$lang->report->annualData->actionData       = "操作数据";
$lang->report->annualData->contributionData = "贡献数据";
$lang->report->annualData->radar            = "能力雷达图";
$lang->report->annualData->projects         = "{$lang->projectCommon}数据";
$lang->report->annualData->products         = "{$lang->productCommon}数据";
$lang->report->annualData->stories          = "需求数据";
$lang->report->annualData->tasks            = "任务数据";
$lang->report->annualData->bugs             = "Bug数据";
$lang->report->annualData->cases            = "用例数据";
$lang->report->annualData->statusStat       = "{$lang->storyCommon}/任务/Bug状态分布（截止今日）";

$lang->report->annualData->companyUsers     = "公司总人数";
$lang->report->annualData->deptUsers        = "部门人数";
$lang->report->annualData->logins           = "登录次数";
$lang->report->annualData->actions          = "操作次数";
$lang->report->annualData->contributions    = "贡献数";
$lang->report->annualData->consumed         = "消耗工时";
$lang->report->annualData->todos            = "待办数";

$lang->report->annualData->storyStatusStat = "需求状态分布";
$lang->report->annualData->taskStatusStat  = "任务状态分布";
$lang->report->annualData->bugStatusStat   = "Bug状态分布";
$lang->report->annualData->caseResultStat  = "用例结果分布";
$lang->report->annualData->allStory        = "总需求";
$lang->report->annualData->allTask         = "总任务";
$lang->report->annualData->allBug          = "总Bug";
$lang->report->annualData->undone          = "未完成";
$lang->report->annualData->unresolve       = "未解决";

$lang->report->annualData->storyMonthActions = "每月需求操作情况";
$lang->report->annualData->taskMonthActions  = "每月任务操作情况";
$lang->report->annualData->bugMonthActions   = "每月Bug操作情况";
$lang->report->annualData->caseMonthActions  = "每月用例操作情况";

$lang->report->annualData->projectFields['name']  = "{$lang->projectCommon}名称";
$lang->report->annualData->projectFields['story'] = "完成{$lang->storyCommon}数";
$lang->report->annualData->projectFields['task']  = "完成任务数";
$lang->report->annualData->projectFields['bug']   = "解决Bug数";

$lang->report->annualData->productFields['name'] = "{$lang->productCommon}名称";
$lang->report->annualData->productFields['plan'] = "计划数";
global $config;
if(!empty($config->URAndSR))
{
    $lang->report->annualData->productFields['requirement'] = "创建{$lang->URCommon}数";
}
$lang->report->annualData->productFields['story']    = "创建{$lang->storyCommon}数";
$lang->report->annualData->productFields['finished'] = "完成需求数";

$lang->report->annualData->objectTypeList['product']     = $lang->productCommon;
$lang->report->annualData->objectTypeList['story']       = "需求";
$lang->report->annualData->objectTypeList['productplan'] = "计划";
$lang->report->annualData->objectTypeList['release']     = "发布";
$lang->report->annualData->objectTypeList['project']     = $lang->projectCommon;
$lang->report->annualData->objectTypeList['task']        = '任务';
$lang->report->annualData->objectTypeList['repo']        = '代码';
$lang->report->annualData->objectTypeList['bug']         = 'Bug';
$lang->report->annualData->objectTypeList['build']       = '版本';
$lang->report->annualData->objectTypeList['testtask']    = '测试单';
$lang->report->annualData->objectTypeList['case']        = '用例';
$lang->report->annualData->objectTypeList['doc']         = '文档';

$lang->report->annualData->actionList['create']    = '创建';
$lang->report->annualData->actionList['edit']      = '编辑';
$lang->report->annualData->actionList['close']     = '关闭';
$lang->report->annualData->actionList['review']    = '评审';
$lang->report->annualData->actionList['gitCommit'] = 'GIT提交';
$lang->report->annualData->actionList['svnCommit'] = 'SVN提交';
$lang->report->annualData->actionList['start']     = '开始';
$lang->report->annualData->actionList['finish']    = '完成';
$lang->report->annualData->actionList['assign']    = '指派';
$lang->report->annualData->actionList['activate']  = '激活';
$lang->report->annualData->actionList['resolve']   = '解决';
$lang->report->annualData->actionList['run']       = '执行';
$lang->report->annualData->actionList['change']    = '变更';
$lang->report->annualData->actionList['pause']     = '暂停';
$lang->report->annualData->actionList['cancel']    = '取消';
$lang->report->annualData->actionList['confirm']   = '确认';
$lang->report->annualData->actionList['createBug'] = '转Bug';

$lang->report->annualData->todoStatus['all']    = '所有待办';
$lang->report->annualData->todoStatus['undone'] = '未完成';
$lang->report->annualData->todoStatus['done']   = '已完成';

$lang->report->annualData->radarItems['product'] = '产品';
$lang->report->annualData->radarItems['project'] = '项目';
$lang->report->annualData->radarItems['devel']   = "研发";
$lang->report->annualData->radarItems['qa']      = "测试";
$lang->report->annualData->radarItems['other']   = "其他";
