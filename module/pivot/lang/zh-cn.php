<?php
/**
 * The pivot module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     pivot
 * @version     $Id: zh-cn.php 5080 2013-07-10 00:46:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->pivot->index        = '透视表首页';
$lang->pivot->list         = '透视表';
$lang->pivot->preview      = '查看透视表';
$lang->pivot->create       = '创建透视表';
$lang->pivot->edit         = '编辑透视表';
$lang->pivot->browse       = '浏览透视表';
$lang->pivot->delete       = '删除透视表';
$lang->pivot->design       = '设计透视表';
$lang->pivot->export       = '导出透视表';
$lang->pivot->query        = '查询';
$lang->pivot->browseAction = '进入透视表设计';

$lang->pivot->id          = 'ID';
$lang->pivot->name        = '名称';
$lang->pivot->dataset     = '数据集';
$lang->pivot->dataview    = '数据';
$lang->pivot->type        = '类型';
$lang->pivot->config      = '参数配置';
$lang->pivot->desc        = '描述';
$lang->pivot->xaxis       = 'X轴';
$lang->pivot->yaxis       = 'Y轴';
$lang->pivot->group       = '所属分组';
$lang->pivot->metric      = '指标';
$lang->pivot->column      = '列';
$lang->pivot->field       = '关联字段';
$lang->pivot->operator    = '条件';
$lang->pivot->orderby     = '排序';
$lang->pivot->order       = '排序';
$lang->pivot->add         = '添加';
$lang->pivot->valOrAgg    = '值/汇总';
$lang->pivot->value       = '值';
$lang->pivot->agg         = '汇总';
$lang->pivot->display     = '显示名称';
$lang->pivot->filterValue = '筛选值';
$lang->pivot->save        = '保存';
$lang->pivot->cancel      = '取消';
$lang->pivot->run         = '执行查询';
$lang->pivot->must        = '请选择';
$lang->pivot->split       = '分列显示';
$lang->pivot->chooseField = '选择字段';
$lang->pivot->aggType     = '统计方式';
$lang->pivot->other       = '其他';
$lang->pivot->publish     = '发布';
$lang->pivot->draft       = '存为草稿';
$lang->pivot->draftIcon   = '草稿';
$lang->pivot->nextStep    = '下一步';
$lang->pivot->saveSetting = '保存设置';
$lang->pivot->add         = '添加';
$lang->pivot->baseSetting = '基础设置';
$lang->pivot->setLang     = '设置语言项';
$lang->pivot->toDesign    = '进入设计';
$lang->pivot->toPreview   = '退出设计';
$lang->pivot->variable    = '变量名称';
$lang->pivot->varCode     = '变量代号';
$lang->pivot->varLabel    = '变量标签';
$lang->pivot->monopolize  = '独占一列';
$lang->pivot->varNameTip  = '请输入字母';
$lang->pivot->item        = '条目';
$lang->pivot->percent     = '百分比';
$lang->pivot->undefined   = '未设定';
$lang->pivot->project     = $lang->projectCommon;
$lang->pivot->PO          = 'PO';

$lang->pivot->showOriginItem = '展示原始条目';

$lang->pivot->showOriginPlaceholder = new stdclass();
$lang->pivot->showOriginPlaceholder->slice    = '展示原始条目后无需配置切片';
$lang->pivot->showOriginPlaceholder->calcMode = '展示原始条目后无需配置计算方式';
$lang->pivot->showOriginPlaceholder->showMode = '展示原始条目后无需配置显示方式';

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
$lang->pivot->bugAssign        = '未解决Bug指派表';
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
$lang->pivotList->test->lists[13]    = '未解决Bug指派表|pivot|bugassign';
$lang->pivotList->staff->lists[10]   = '员工负载表|pivot|workload';

$lang->pivot->id            = '编号';
$lang->pivot->execution     = $lang->execution->common;
$lang->pivot->product       = $lang->productCommon;
$lang->pivot->user          = '姓名';
$lang->pivot->bug           = 'Bug';
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
$lang->pivot->workhour      = '每天工时';
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
$lang->pivot->featureBar['preview'] = array();

$lang->pivot->showProduct = '所有' . $lang->productCommon . '统计数据';
$lang->pivot->showProject = '所有' . $lang->projectCommon . '统计数据';

$lang->pivot->columnIndex  = '第 %s 列';
$lang->pivot->deleteColumn = '您确认删除该列吗？';
$lang->pivot->addColumn    = '添加列';
$lang->pivot->sliceField   = '选择切片字段';
$lang->pivot->calcMode     = '值的计算方式';
$lang->pivot->showMode     = '值的显示方式';
$lang->pivot->showTotal    = '显示行的汇总';
$lang->pivot->colLabel     = '{$field}的{$stat}';
$lang->pivot->colShowMode  = '(%s)';

$lang->pivot->errorList = new stdclass();
$lang->pivot->errorList->cantequal = '%s 取值不能与 %s 相同, 请重新设计';

$lang->pivot->pie = new stdclass();
$lang->pivot->pie->group  = '扇区分组';
$lang->pivot->pie->metric = '扇区数值';
$lang->pivot->pie->stat   = '统计方式';

$lang->pivot->cluBarX = new stdclass();
$lang->pivot->cluBarX->xaxis = 'X轴';
$lang->pivot->cluBarX->yaxis = 'Y轴';
$lang->pivot->cluBarX->stat  = '统计方式';

$lang->pivot->cluBarY = new stdclass();
$lang->pivot->cluBarY->yaxis = 'X轴';
$lang->pivot->cluBarY->xaxis = 'Y轴';
$lang->pivot->cluBarY->stat  = '统计方式';

$lang->pivot->radar = new stdclass();
$lang->pivot->radar->xaxis = '维度';
$lang->pivot->radar->yaxis = '极坐标轴';

$lang->pivot->line = $lang->pivot->cluBarX;

$lang->pivot->stackedBar  = $lang->pivot->cluBarX;
$lang->pivot->stackedBarY = $lang->pivot->cluBarY;

$lang->pivot->browseGroup = '维护分组';
$lang->pivot->allGroup    = '所有分组';
$lang->pivot->noGroup     = '暂时没有分组';
$lang->pivot->groupName   = '分组名称';
$lang->pivot->manageGroup = '维护分组';
$lang->pivot->dragAndSort = '拖放排序';
$lang->pivot->editGroup   = '编辑分组';
$lang->pivot->deleteGroup = '删除分组';
$lang->pivot->childTitle  = '子分组';

$lang->pivot->filter          = '筛选器';
$lang->pivot->resultFilter    = '结果筛选器';
$lang->pivot->queryFilter     = '查询筛选器';
$lang->pivot->noName          = '未命名';
$lang->pivot->filterName      = '名称';
$lang->pivot->default         = '默认值';
$lang->pivot->unlimited       = '不限';
$lang->pivot->colon           = '至';
$lang->pivot->legendBasicInfo = '基础信息';
$lang->pivot->legendConfig    = '全局设置';

$lang->pivot->fieldTypeList = array();
$lang->pivot->fieldTypeList['input']    = '文本框';
$lang->pivot->fieldTypeList['date']     = '日期';
$lang->pivot->fieldTypeList['datetime'] = '时间';
$lang->pivot->fieldTypeList['select']   = '下拉菜单';

$lang->pivot->count      = '个数';
$lang->pivot->project    = '项目名称';
$lang->pivot->customer   = '客户名称';
$lang->pivot->cusBuild   = '客户版本';
$lang->pivot->period     = '测试周期';
$lang->pivot->purpose    = '测试目的';
$lang->pivot->stage      = '阶段';
$lang->pivot->users      = '测试人数';
$lang->pivot->testtasks  = '提交测试单';
$lang->pivot->comment    = '备注';
$lang->pivot->major      = '软件主测';
$lang->pivot->conclusion = '结论';
$lang->pivot->result     = '基本测试结果';
$lang->pivot->caseCount  = '用例总数';
$lang->pivot->runCount   = '完成数';
$lang->pivot->runRate    = '完成率';
$lang->pivot->manpower   = '投入人数';
$lang->pivot->bugs       = '提交Bug数';
$lang->pivot->day        = '天';
$lang->pivot->reportDate = '报告日期';
$lang->pivot->hours      = '耗时(小时)';
$lang->pivot->pastDays   = '距今天数';

$lang->pivot->saveAsDataview = '存为中间表';

$lang->pivot->confirmDelete = '您确认要删除吗?';
$lang->pivot->nameEmpty     = '『名称』不能为空';

$lang->pivot->noPivotTip      = '保存设置后，即可显示透视表';
$lang->pivot->noQueryTip      = '暂时没有筛选器。';
$lang->pivot->noPivot         = '暂时没有透视表';
$lang->pivot->dataError       = '"%s" 填写的不是合法的值';
$lang->pivot->noChartSelected = '请选择至少一个图表。';
$lang->pivot->beginGtEnd      = '开始时间不得大于结束时间。';
$lang->pivot->resetSettings   = '查询数据的配置已修改，是否清空透视表设计，并重新设计。';
$lang->pivot->clearSettings   = '查询数据的配置已修改，是否清空透视表设计并保存。';
$lang->pivot->draftSave       = '已发布的内容被编辑，将覆盖，是否继续?';
$lang->pivot->cannotAddQuery  = '已添加结果筛选器，无法添加查询筛选器';
$lang->pivot->cannotAddResult = '已添加查询筛选器，无法添加结果筛选器';

$lang->pivot->confirm = new stdclass();
$lang->pivot->confirm->design  = '该透视表被已发布的大屏引用，是否继续？';
$lang->pivot->confirm->publish = '该透视表被已发布的大屏引用，发布后将在大屏上显示为修改后的透视表，是否继续？';
$lang->pivot->confirm->draft   = '该透视表被已发布的大屏引用，存为草稿后将在大屏上显示为提示信息，是否继续？';
$lang->pivot->confirm->delete  = '该透视表被已发布的大屏引用，删除后将在大屏上显示为提示信息，是否继续？';

$lang->pivot->orderList = array();
$lang->pivot->orderList['asc']  = '正序';
$lang->pivot->orderList['desc'] = '倒序';

$lang->pivot->typeList = array();
$lang->pivot->typeList['pie']        = '饼图';
$lang->pivot->typeList['line']       = '折线图';
$lang->pivot->typeList['radar']      = '雷达图';
$lang->pivot->typeList['cluBarY']    = '簇状条形图';
$lang->pivot->typeList['stackedBarY'] = '堆积条形图';
$lang->pivot->typeList['cluBarX']    = '簇状柱形图';
$lang->pivot->typeList['stackedBar'] = '堆积柱形图';

$lang->pivot->aggList = array();
$lang->pivot->aggList['count'] = '计数';
$lang->pivot->aggList['avg']   = '平均值';
$lang->pivot->aggList['sum']   = '求和';

$lang->pivot->operatorList = array();
$lang->pivot->operatorList['=']       = '=';
$lang->pivot->operatorList['!=']      = '!=';
$lang->pivot->operatorList['<']       = '<';
$lang->pivot->operatorList['>']       = '>';
$lang->pivot->operatorList['<=']      = '<=';
$lang->pivot->operatorList['>=']      = '>=';
$lang->pivot->operatorList['in']      = 'IN';
$lang->pivot->operatorList['notin']   = 'NOT IN';
$lang->pivot->operatorList['notnull'] = 'IS NOT NULL';
$lang->pivot->operatorList['null']    = 'IS NULL';

$lang->pivot->dateList = array();
$lang->pivot->dateList['day']   = '天';
$lang->pivot->dateList['week']  = '周';
$lang->pivot->dateList['month'] = '月';
$lang->pivot->dateList['year']  = '年';

$lang->pivot->designStepNav = array();
$lang->pivot->designStepNav['1'] = '查询数据';
$lang->pivot->designStepNav['2'] = '设计透视表';
$lang->pivot->designStepNav['3'] = '配置筛选器';
$lang->pivot->designStepNav['4'] = '准备发布';

$lang->pivot->nextButton = array();
$lang->pivot->nextButton['1'] = '去设计';
$lang->pivot->nextButton['2'] = '去配置';
$lang->pivot->nextButton['3'] = '去准备';
$lang->pivot->nextButton['4'] = '发布';

$lang->pivot->displayList = array();
$lang->pivot->displayList['value']   = '显示值';
$lang->pivot->displayList['percent'] = '百分比';

$lang->pivot->typeOptions = array();
$lang->pivot->typeOptions['user']      = '用户';
$lang->pivot->typeOptions['product']   = '产品';
$lang->pivot->typeOptions['project']   = '项目';
$lang->pivot->typeOptions['execution'] = '执行';
$lang->pivot->typeOptions['dept']      = '部门';

$lang->pivot->step2 = new stdclass();
$lang->pivot->step2->group       = '行分组';
$lang->pivot->step2->summary     = '汇总设置';
$lang->pivot->step2->column      = '列设置';
$lang->pivot->step2->groupTip    = '选择字段';
$lang->pivot->step2->groupNum    = array('一', '二', '三');
$lang->pivot->step2->selectField = '选择字段';
$lang->pivot->step2->selectStat  = '选择统计方式';
$lang->pivot->step2->add         = '添加';
$lang->pivot->step2->delete      = '删除';
$lang->pivot->step2->groupField  = '分组字段';
$lang->pivot->step2->columnField = '字段';
$lang->pivot->step2->calcMode    = '计算方式';

$lang->pivot->step2->moreThanOne = '至少选择一个分组字段。';
$lang->pivot->step2->summaryTip  = '勾选后，可配置行分组、列设置、显示列的汇总。';
$lang->pivot->step2->groupsTip   = '通过选择分组字段，对SQL查询的数据进行分组，并分层级显示在透视表中。';
$lang->pivot->step2->columnsTip  = '在透视表中添加1列并对其进行设置。';

$lang->pivot->step2->columnTotal    = '显示列的汇总';
$lang->pivot->step2->columnTotalTip = '增加一行显示每一列的汇总数据。';
$lang->pivot->step2->total          = '总计';

$lang->pivot->step2->columnTotalList = array();
$lang->pivot->step2->columnTotalList['noShow'] = '不显示';
$lang->pivot->step2->columnTotalList['sum']    = '求和';

$lang->pivot->step2->sliceFieldList = array();
$lang->pivot->step2->sliceFieldList['noSlice'] = '不切片';

$lang->pivot->step2->showModeList = array();
$lang->pivot->step2->showModeList['default'] = '默认（数值）';
$lang->pivot->step2->showModeList['total']   = '总计百分比';
$lang->pivot->step2->showModeList['row']     = '行汇总百分比';
$lang->pivot->step2->showModeList['column']  = '列汇总百分比';

$lang->pivot->step2->showTotalList = array();
$lang->pivot->step2->showTotalList['noShow'] = '不显示';
$lang->pivot->step2->showTotalList['sum']    = '求和';

$lang->pivot->step2->statList = array();
$lang->pivot->step2->statList['']         = '';
$lang->pivot->step2->statList['count']    = '计数';
$lang->pivot->step2->statList['distinct'] = '去重后计数';
$lang->pivot->step2->statList['avg']      = '平均值';
$lang->pivot->step2->statList['sum']      = '求和';
$lang->pivot->step2->statList['max']      = '最大值';
$lang->pivot->step2->statList['min']      = '最小值';

$lang->datepicker->dpText->TEXT_WEEK_MONDAY = '本周一';
$lang->datepicker->dpText->TEXT_WEEK_SUNDAY = '本周日';
$lang->datepicker->dpText->TEXT_MONTH_BEGIN = '本月初';
$lang->datepicker->dpText->TEXT_MONTH_END   = '本月末';
