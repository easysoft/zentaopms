<?php
/**
 * The pivot module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     pivot
 * @version     $Id: zh-cn.php 5080 2013-07-10 00:46:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->pivot->index     = '透视表首页';
$lang->pivot->list      = '透视表';
$lang->pivot->preview   = '查看透视表';
$lang->pivot->item      = '条目';
$lang->pivot->value     = '值';
$lang->pivot->percent   = '百分比';
$lang->pivot->undefined = '未设定';
$lang->pivot->query     = '查询';
$lang->pivot->project   = $lang->projectCommon;
$lang->pivot->PO        = 'PO';

$lang->pivot->colors[] = 'AFD8F8';
$lang->pivot->colors[] = 'F6BD0F';
$lang->pivot->colors[] = '8BBA00';
$lang->pivot->colors[] = 'FF8E46';
$lang->pivot->colors[] = '008E8E';
$lang->pivot->colors[] = 'D64646';
$lang->pivot->colors[] = '8E468E';
$lang->pivot->colors[] = '588526';
$lang->pivot->colors[] = 'B3AA00';
$lang->pivot->colors[] = '008ED6';
$lang->pivot->colors[] = '9D080D';
$lang->pivot->colors[] = 'A186BE';

$lang->pivot->assign['noassign'] = '未指派';
$lang->pivot->assign['assign']   = '已指派';

$lang->pivot->singleColor[] = 'F6BD0F';

$lang->pivot->projectDeviation = "{$lang->execution->common}偏差报表";
$lang->pivot->productSummary   = $lang->productCommon . '汇总表';
$lang->pivot->bugCreate        = 'Bug创建表';
$lang->pivot->bugAssign        = 'Bug指派表';
$lang->pivot->workload         = '员工负载表';
$lang->pivot->workloadAB       = '工作负载';
$lang->pivot->bugOpenedDate    = 'Bug创建时间';
$lang->pivot->beginAndEnd      = '起止时间';
$lang->pivot->begin            = '起始日期';
$lang->pivot->end              = '结束日期';
$lang->pivot->dept             = '部门';
$lang->pivot->deviationChart   = "{$lang->execution->common}偏差曲线";

$lang->pivotList = new stdclass();
$lang->pivotList->product = new stdclass();
$lang->pivotList->project = new stdclass();
$lang->pivotList->test    = new stdclass();
$lang->pivotList->staff   = new stdclass();

$lang->pivotList->product->lists[10] = $lang->productCommon . '汇总表|pivot|productsummary';
$lang->pivotList->project->lists[10] = "{$lang->execution->common}偏差报表|pivot|projectdeviation";
$lang->pivotList->test->lists[10]    = 'Bug创建表|pivot|bugcreate';
$lang->pivotList->test->lists[13]    = 'Bug指派表|pivot|bugassign';
$lang->pivotList->staff->lists[10]   = '员工负载表|pivot|workload';

$lang->pivot->id            = '编号';
$lang->pivot->execution     = $lang->execution->common;
$lang->pivot->product       = $lang->productCommon;
$lang->pivot->user          = '姓名';
$lang->pivot->bugTotal      = 'Bug';
$lang->pivot->task          = '任务数';
$lang->pivot->estimate      = '总预计';
$lang->pivot->consumed      = '总消耗';
$lang->pivot->remain        = '剩余工时';
$lang->pivot->deviation     = '偏差';
$lang->pivot->deviationRate = '偏差率';
$lang->pivot->total         = '总计';
$lang->pivot->to            = '至';
$lang->pivot->taskTotal     = "总任务数";
$lang->pivot->manhourTotal  = "总工时";
$lang->pivot->validRate     = "有效率";
$lang->pivot->validRateTips = "方案为已解决或延期/状态为已解决或已关闭";
$lang->pivot->unplanned     = "未计划";
$lang->pivot->workday       = '每天工时';
$lang->pivot->diffDays      = '工作日天数';

$lang->pivot->typeList['default'] = '默认';
$lang->pivot->typeList['pie']     = '饼图';
$lang->pivot->typeList['bar']     = '柱状图';
$lang->pivot->typeList['line']    = '折线图';

$lang->pivot->conditions    = '筛选条件：';
$lang->pivot->closedProduct = '关闭' . $lang->productCommon;
$lang->pivot->overduePlan   = "过期计划";

$lang->pivot->idAB         = 'ID';
$lang->pivot->bugTitle     = 'Bug标题';
$lang->pivot->taskName     = '任务名称';
$lang->pivot->todoName     = '待办名称';
$lang->pivot->testTaskName = '版本名称';
$lang->pivot->deadline     = '截止日期';

$lang->pivot->deviationDesc = '按照已关闭执行统计偏差率（偏差率 = (总消耗 - 总预计) / 总预计），总预计为0时偏差率为n/a。';
$lang->pivot->workloadDesc  = '工作负载=用户所有任务剩余工时之和/选择的时间天数*每天的工时。例如：起止时间设为1月1日~1月7日、工作日天数5天、每天工时8h，统计的是所有指派给该人员的未完成的任务，在5天内，每天8h的情况下的工作负载。';

$lang->pivot->featureBar = array();
$lang->pivot->featureBar['preview']['product'] = $lang->product->common;
$lang->pivot->featureBar['preview']['project'] = $lang->project->common;
$lang->pivot->featureBar['preview']['test']    = $lang->qa->common;
$lang->pivot->featureBar['preview']['staff']   = $lang->system->common;
