<?php
/**
 * The report module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     report
 * @version     $Id: zh-cn.php 5080 2013-07-10 00:46:59Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->report->index     = '统计首页';
$lang->report->list      = '透视表';
$lang->report->item      = '条目';
$lang->report->value     = '值';
$lang->report->percent   = '百分比';
$lang->report->undefined = '未设定';
$lang->report->project   = $lang->projectCommon;
$lang->report->PO        = 'PO';

$lang->report->colors[] = 'AFD8F8';
$lang->report->colors[] = 'F6BD0F';
$lang->report->colors[] = '8BBA00';
$lang->report->colors[] = 'FF8E46';
$lang->report->colors[] = '008E8E';
$lang->report->colors[] = 'D64646';
$lang->report->colors[] = '8E468E';
$lang->report->colors[] = '588526';
$lang->report->colors[] = 'B3AA00';
$lang->report->colors[] = '008ED6';
$lang->report->colors[] = '9D080D';
$lang->report->colors[] = 'A186BE';

$lang->report->assign['noassign'] = '未指派';
$lang->report->assign['assign']   = '已指派';

$lang->report->singleColor[] = 'F6BD0F';

$lang->report->projectDeviation = "{$lang->execution->common}偏差报表";
$lang->report->productSummary   = $lang->productCommon . '汇总表';
$lang->report->bugCreate        = 'Bug创建表';
$lang->report->bugAssign        = '未解决Bug指派表';
$lang->report->workload         = '员工负载表';
$lang->report->workloadAB       = '工作负载';
$lang->report->bugOpenedDate    = 'Bug创建时间';
$lang->report->beginAndEnd      = '起止时间';
$lang->report->begin            = '起始日期';
$lang->report->end              = '结束日期';
$lang->report->dept             = '部门';
$lang->report->deviationChart   = "{$lang->execution->common}偏差曲线";

$lang->report->id            = '编号';
$lang->report->execution     = $lang->execution->common;
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
$lang->report->unplanned     = "未计划";
$lang->report->workday       = '每天工时';
$lang->report->diffDays      = '工作日天数';

$lang->report->typeList['default'] = '默认';
$lang->report->typeList['pie']     = '饼图';
$lang->report->typeList['bar']     = '柱状图';
$lang->report->typeList['line']    = '折线图';

$lang->report->conditions    = '筛选条件：';
$lang->report->closedProduct = '关闭' . $lang->productCommon;
$lang->report->overduePlan   = "过期计划";

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
$lang->report->annualData->executions       = "{$lang->executionCommon}数据";
$lang->report->annualData->products         = "{$lang->productCommon}数据";
$lang->report->annualData->stories          = "需求数据";
$lang->report->annualData->tasks            = "任务数据";
$lang->report->annualData->bugs             = "Bug数据";
$lang->report->annualData->cases            = "用例数据";
$lang->report->annualData->statusStat       = "{$lang->SRCommon}/任务/Bug状态分布（截止今日）";

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

$lang->report->annualData->executionFields['name']  = "{$lang->executionCommon}名称";
$lang->report->annualData->executionFields['story'] = "完成{$lang->SRCommon}数";
$lang->report->annualData->executionFields['task']  = "完成任务数";
$lang->report->annualData->executionFields['bug']   = "解决Bug数";

$lang->report->annualData->productFields['name'] = "{$lang->productCommon}名称";
$lang->report->annualData->productFields['plan'] = "创建计划数";
global $config;
if(!empty($config->URAndSR))
{
    $lang->report->annualData->productFields['requirement'] = "创建{$lang->URCommon}数";
}
$lang->report->annualData->productFields['story']  = "创建研发需求数";
$lang->report->annualData->productFields['closed'] = "关闭研发需求数";

$lang->report->annualData->objectTypeList['product']     = $lang->productCommon;
$lang->report->annualData->objectTypeList['story']       = "需求";
$lang->report->annualData->objectTypeList['productplan'] = "计划";
$lang->report->annualData->objectTypeList['release']     = "发布";
$lang->report->annualData->objectTypeList['execution']   = $lang->executionCommon;
$lang->report->annualData->objectTypeList['task']        = '任务';
$lang->report->annualData->objectTypeList['repo']        = '代码';
$lang->report->annualData->objectTypeList['bug']         = 'Bug';
$lang->report->annualData->objectTypeList['build']       = '构建';
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
$lang->report->annualData->actionList['stop']      = '停止维护';
$lang->report->annualData->actionList['putoff']    = '延期';
$lang->report->annualData->actionList['suspend']   = '挂起';
$lang->report->annualData->actionList['change']    = '变更';
$lang->report->annualData->actionList['pause']     = '暂停';
$lang->report->annualData->actionList['cancel']    = '取消';
$lang->report->annualData->actionList['confirm']   = '确认';
$lang->report->annualData->actionList['createBug'] = '转Bug';
$lang->report->annualData->actionList['delete']    = '删除';

$lang->report->annualData->todoStatus['all']    = '所有待办';
$lang->report->annualData->todoStatus['undone'] = '未完成';
$lang->report->annualData->todoStatus['done']   = '已完成';

$lang->report->annualData->radarItems['product']   = "{$lang->productCommon}管理";
$lang->report->annualData->radarItems['execution'] = "{$lang->projectCommon}管理";
$lang->report->annualData->radarItems['devel']     = "研发";
$lang->report->annualData->radarItems['qa']        = "测试";
$lang->report->annualData->radarItems['other']     = "其他";

$lang->report->companyRadar        = "公司能力雷达图";
$lang->report->outputData          = "产出数据";
$lang->report->outputTotal         = "产出总数";
$lang->report->storyOutput         = "需求产出";
$lang->report->planOutput          = "计划产出";
$lang->report->releaseOutput       = "发布产出";
$lang->report->executionOutput     = "执行产出";
$lang->report->taskOutput          = "任务产出";
$lang->report->bugOutput           = "Bug产出";
$lang->report->caseOutput          = "用例产出";
$lang->report->bugProgress         = "Bug进展";
$lang->report->productProgress     = "{$lang->productCommon}进展";
$lang->report->executionProgress   = "执行进展";
$lang->report->projectProgress     = "{$lang->projectCommon}进展";
$lang->report->yearProjectOverview = "年度{$lang->projectCommon}总览";
$lang->report->projectOverview     = "截止目前{$lang->projectCommon}总览";

$lang->report->tips = new stdclass();
$lang->report->tips->basic = array();
$lang->report->tips->basic['company'] = '
1.公司总人数：系统所有用户个数求和，过滤已删除的用户。<br>
2.操作次数：系统某年的操作次数求和。<br>
3.消耗工时：系统某年的工时消耗求和。<br>
4.待办数：系统所有用户的待办数据求和。<br>
5.贡献数：系统所有用户的贡献数求和。';
$lang->report->tips->basic['dept'] = '
1.部门人数：某部门所有用户个数求和，过滤已删除的用户。<br>
2.操作次数：某部门用户在某年的操作次数求和。<br>
3.消耗工时：某部门用户在某年的工时消耗求和。<br>
4.待办数：某部门用户的待办数据求和。<br>
5.贡献数：某部门用户的贡献数据求和。';
$lang->report->tips->basic['user'] = '
1.登录次数：某用户某年的登录次数求和。<br>
2.操作次数：某用户在某年的操作次数求和。<br>
3.消耗工时：某用户在某年的工时消耗求和。<br>
4.待办数：某用户的待办数据求和。<br>
5.贡献数：某用户的贡献数据求和。';

$lang->report->tips->contribute['company'] = '不同系统对象在某年的操作次数求和。';
$lang->report->tips->contribute['dept']    = '不同系统对象在某年的操作次数求和，要求是操作用户属于选中的部门。';
$lang->report->tips->contribute['user']    = '不同系统对象在某年的操作次数求和，要求是操作用户属于选中的用户。';

$lang->report->tips->radar = '
1.产品管理包含：产品、计划、需求、发布相关的操作数据。<br>
2.项目管理包含：项目、迭代、版本、任务相关的操作数据。<br>
3.研发包含：任务、代码、Bug的解决相关的操作数据。<br>
4.测试包含：Bug的创建、Bug的激活、Bug的关闭、用例、测试单相关的操作数据。<br>
5.其他包含：其他零散的动态数据。';

$lang->report->tips->execution['company'] = '
完成需求数：{year}年创建的执行中研发需求个数求和，要求研发需求状态为已关闭、关闭原因为已完成，过滤已删除的研发需求。<br>
完成任务数：{year}年创建的执行中任务个数求和，状态为已完成，过滤已删除的任务。<br>
修复Bug数：{year}年创建的执行，状态为已关闭且解决方案为已解决的Bug数。';
$lang->report->tips->execution['dept'] = '
完成需求数：{year}年创建的执行中研发需求个数求和，要求研发需求状态为已关闭、关闭原因为已完成，过滤已删除的研发需求，创建人为已选中的部门用户。<br>
完成任务数：{year}年创建的执行中任务个数求和，状态为已完成，过滤已删除的任务，创建人为已选中的部门用户。<br>
修复Bug数：{year}年创建的执行，状态为已关闭且解决方案为已解决的Bug数，创建人为已选中的部门用户。';
$lang->report->tips->execution['user'] = '
完成需求数：{year}年创建的执行中研发需求个数求和，要求研发需求状态为已关闭、关闭原因为已完成，过滤已删除的研发需求，创建人为已选中的用户。<br>
完成任务数：{year}年创建的执行中任务个数求和，状态为已完成，过滤已删除的任务，创建人为已选中的用户。<br>
修复Bug数：{year}年创建的执行，状态为已关闭且解决方案为已解决的Bug数，创建人为已选中的用户。';

$lang->report->tips->product['company'] = '
计划数：产品中创建时间在某年的计划数。<br>
创建用户需求数：产品中创建时间在某年的用户需求数。<br>
创建研发需求数：产品中创建时间在某年的研发需求数。<br>
关闭研发需求数：产品中关闭时间在某年的研发需求数。';
$lang->report->tips->product['dept'] = '
计划数：产品中创建时间在某年的计划数，创建人为所选部门中的用户。<br>
创建用户需求数：产品中创建时间在某年的用户需求数，创建人为所选部门中的用户。<br>
创建研发需求数：产品中创建时间在某年的研发需求数，创建人为所选部门中的用户。<br>
关闭研发需求数：产品中关闭时间在某年的研发需求数，关闭人为所选部门中的用户。';
$lang->report->tips->product['user'] = '
计划数：产品中创建时间在某年的计划数，创建人为选中的用户。<br>
创建用户需求数：产品中创建时间在某年的用户需求数，创建人为选中的用户。<br>
创建研发需求数：产品中创建时间在某年的研发需求数，创建人为选中的用户。<br>
关闭研发需求数：产品中关闭时间在某年的研发需求数，创建人为选中的用户。';

$lang->report->tips->story['company'] = '
需求结果分布：不同状态的需求数据分布，要求是创建时间为某年。<br>
每月需求操作情况：需求的操作次数求和，要求是操作时间为某年。';
$lang->report->tips->story['dept'] = '
需求状态分布：不同状态的需求数据分布，要求是创建时间为某年，创建用户为选中的部门用户。<br>
每月需求操作情况：需求的操作次数求和，要求是操作时间为某年，操作用户为选中的部门用户。';
$lang->report->tips->story['user'] = '
需求状态分布：不同状态的需求数据分布，要求是创建时间为某年，创建用户为选中用户。<br>
每月需求操作情况：需求的操作次数求和，要求是操作时间为某年，操作用户为选中用户。';

$lang->report->tips->bug['company'] = '
Bug状态分布：不同状态的Bug数据分布，要求是创建时间为某年。<br>
每月Bug操作情况：Bug的操作次数求和，要求是操作时间为某年。';
$lang->report->tips->bug['dept'] = '
Bug状态分布：不同状态的Bug数据分布，要求是创建时间为某年，创建用户为选中的部门用户。<br>
每月Bug操作情况：Bug的操作次数求和，要求是操作时间为某年，操作用户为选中的部门用户。';
$lang->report->tips->bug['user'] = '
Bug状态分布：不同状态的Bug数据分布，要求是创建时间为某年，创建用户为选中用户。<br>
每月Bug操作情况：Bug的操作次数求和，要求是操作时间为某年，操作用户为选中用户。';

$lang->report->tips->case['company'] = '
用例结果分布：不同执行结果的用例数据分布，要求是创建时间为某年。<br>
每月用例操作情况：用例的操作次数求和，要求是操作时间为某年。';
$lang->report->tips->case['dept'] = '
用例状态分布：不同执行结果的用例数据分布，要求是创建时间为某年，创建用户为选中的部门用户。<br>
每月用例操作情况：用例的操作次数求和，要求是操作时间为某年，操作用户为选中的部门用户。';
$lang->report->tips->case['user'] = '
用例状态分布：不同执行结果的用例数据分布，要求是创建时间为某年，创建用户为选中用户。<br>
每月用例操作情况：用例的操作次数求和，要求是操作时间为某年，操作用户为选中用户。';

$lang->report->tips->task['company'] = '
任务状态分布：不同状态的任务数据分布，要求是创建时间为某年。<br>
每月任务操作情况：任务的操作次数求和，要求是操作时间为某年。';
$lang->report->tips->task['dept'] = '
任务状态分布：不同状态的任务数据分布，要求是创建时间为某年，创建用户为选中的部门用户。<br>
每月任务操作情况：任务的操作次数求和，要求是操作时间为某年，操作用户为选中的部门用户。';
$lang->report->tips->task['user'] = '
任务状态分布：不同状态的任务数据分布，要求是创建时间为某年，创建用户为选中用户。<br>
每月任务操作情况：任务的操作次数求和，要求是操作时间为某年，操作用户为选中用户。';
