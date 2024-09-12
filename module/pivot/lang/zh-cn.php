<?php
/**
 * The pivot module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     pivot
 * @version     $Id: zh-cn.php 5080 2013-07-10 00:46:59Z wyd621@gmail.com $
 * @link        https://www.zentao.net
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
$lang->pivot->designAB     = '设计';
$lang->pivot->exportType   = '导出格式';
$lang->pivot->exportRange  = '导出范围';
$lang->pivot->story        = '需求';

$lang->pivot->accessDenied  = '您无权访问该透视表';
$lang->pivot->acl = '访问控制';
$lang->pivot->aclList['open']    = '公开（有透视表视图权限与所在维度的访问权限即可访问）';
$lang->pivot->aclList['private'] = '私有（仅创建者和白名单用户可访问）';

$lang->pivot->otherLang = new stdclass();
$lang->pivot->otherLang->product       = '产品';
$lang->pivot->otherLang->productStatus = '产品状态';
$lang->pivot->otherLang->productType   = '产品类型';

$lang->pivot->cancelAndBack = '取消保存并返回';

$lang->pivot->deleteTip = '您确认要删除吗？';

$lang->pivot->rangeList['current'] = '当前页';
$lang->pivot->rangeList['all']     = '全部';

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
$lang->pivot->varCode     = '代号';
$lang->pivot->varLabel    = '变量标签';
$lang->pivot->monopolize  = '独占一列';
$lang->pivot->varNameTip  = '请输入字母';
$lang->pivot->item        = '条目';
$lang->pivot->percent     = '百分比';
$lang->pivot->undefined   = '未设定';
$lang->pivot->project     = $lang->projectCommon;
$lang->pivot->PO          = '产品负责人';
$lang->pivot->showPivot   = '查看透视表';
$lang->pivot->showOrigin  = '查看原始数据';
$lang->pivot->empty       = '空';

$lang->pivot->showOriginItem = '展示原始条目';
$lang->pivot->recTotalTip    = '共 <strong> %s </strong> 项';
$lang->pivot->recPerPageTip  = "每页 <strong>%s</strong> 项";

$lang->pivot->showOriginPlaceholder = new stdclass();
$lang->pivot->showOriginPlaceholder->slice    = '展示原始条目后无需配置切片';
$lang->pivot->showOriginPlaceholder->stat     = '展示原始条目后无需配置计算方式';
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
$lang->pivot->featureBar['preview'] = array();

$lang->pivot->moreSelects['preview'] = array();
$lang->pivot->moreSelects['preview']['more'] = array();

$lang->pivot->showProduct = '所有' . $lang->productCommon . '统计数据';
$lang->pivot->showProject = '所有' . $lang->projectCommon . '统计数据';

$lang->pivot->columnIndex  = '第 %s 列';
$lang->pivot->deleteColumn = '您确认删除该列吗？';
$lang->pivot->addColumn    = '添加列';
$lang->pivot->slice        = '选择切片字段';
$lang->pivot->stat         = '值的计算方式';
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
$lang->pivot->showAs          = '显示为';
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
$lang->pivot->confirmLeave  = '当前步骤未保存,确认要离开吗？';
$lang->pivot->nameEmpty     = '『名称』不能为空';
$lang->pivot->notEmpty      = '不能为空。';

$lang->pivot->noPivotTip      = '保存设置后，即可显示透视表';
$lang->pivot->noQueryTip      = '暂时没有筛选器。';
$lang->pivot->noPivot         = '暂时没有透视表';
$lang->pivot->noDrillTip      = '未配置数据下钻。';
$lang->pivot->dataError       = '"%s" 填写的不是合法的值';
$lang->pivot->noChartSelected = '请选择至少一个图表。';
$lang->pivot->beginGtEnd      = '开始时间不得大于结束时间。';
$lang->pivot->resetSettings   = '查询数据的配置已修改，是否清空透视表设计，重新设计。';
$lang->pivot->clearSettings   = '查询数据的配置已修改，是否清空透视表设计并保存。';
$lang->pivot->draftSave       = '该透视表已发布，将变为草稿态，是否继续？';
$lang->pivot->cannotAddQuery  = '已添加结果筛选器，无法添加查询筛选器';
$lang->pivot->cannotAddResult = '已添加查询筛选器，无法添加结果筛选器';
//$lang->pivot->cannotAddDrill  = '查询语句中存在GROUP BY或配置了筛选器，暂时无法配置数据下钻';
$lang->pivot->permissionDenied = '目录 %s 权限不足，执行命令 chmod 777 %s 修改权限。';

$lang->pivot->drillModalTip       = <<<EOT
1.请先选择需要下钻的列及其下钻的目标对象，系统将根据您的选择自动生成下钻SQL语句。
2.请对照下方灰色面板展示的查询语句（第一步【查询数据】的SQL查询语句）调整自动生成的下钻SQL语句，并配置对应的查询条件。
3.点击预览，查看您配置的下钻数据。
4.点击保存，完成此条下钻配置。
EOT;
$lang->pivot->drillConditionTip = <<<EOT
1.请根据第二步中配置的行分组字段和切片列字段以及第三步配置的筛选器字段配置相应查询条件。
2.查询结果字段下拉菜单中展示的是第一步“查询数据”中的结果集字段。
EOT;

$lang->pivot->step1QueryTip       = '为后续配置数据下钻，请确保查询结果集中包含查询对象的id字段。';
$lang->pivot->drillingTip         = '系统会为您自动配置一些可下钻的列并展示在此，您可检查调整，或为其他列添加数据下钻配置。';
$lang->pivot->queryConditionTip   = '根据步骤一中的SQL查询语句，调整下钻语句中的查询条件；请确保查询结果集中包含查询对象的id字段以便正确展示下钻内容。';
$lang->pivot->drillSQLTip         = '您可以将右侧第一步的查询语句复制到此处，并做相应的修改。';
$lang->pivot->drillResultEmptyTip = '点击“预览”按钮，即可在此查看下钻结果。';
$lang->pivot->previewResultTip    = '下钻结果将显示对象相关字段，默认只显示10行数据。';
$lang->pivot->emptyDrillTip       = '暂无数据';

$lang->pivot->emptyGroupError       = '分组不能为空。';
$lang->pivot->emptyColumnFieldError = '列字段不能为空。';
$lang->pivot->emptyColumnStatError  = '计算方式不能为空。';

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
$lang->pivot->designStepNav['query']   = '查询数据';
$lang->pivot->designStepNav['design']  = '设计透视表';
$lang->pivot->designStepNav['drill']   = '数据下钻';
$lang->pivot->designStepNav['filter']  = '配置筛选器';
$lang->pivot->designStepNav['publish'] = '准备发布';

$lang->pivot->nextButton = array();
$lang->pivot->nextButton['query']   = '去设计';
$lang->pivot->nextButton['design']  = '去下钻';
$lang->pivot->nextButton['drill']   = '去配置';
$lang->pivot->nextButton['filter']  = '去准备';
$lang->pivot->nextButton['publish'] = '发布';

$lang->pivot->displayList = array();
$lang->pivot->displayList['value']   = '显示值';
$lang->pivot->displayList['percent'] = '百分比';

$lang->pivot->typeOptions = array();
$lang->pivot->typeOptions['user']      = '用户';
$lang->pivot->typeOptions['product']   = '产品';
$lang->pivot->typeOptions['project']   = '项目';
$lang->pivot->typeOptions['execution'] = '执行';
$lang->pivot->typeOptions['dept']      = '部门';

$lang->pivot->stepDesign = new stdclass();
$lang->pivot->stepDesign->group       = '行分组';
$lang->pivot->stepDesign->summary     = '汇总设置';
$lang->pivot->stepDesign->column      = '列设置';
$lang->pivot->stepDesign->groupTip    = '选择字段';
$lang->pivot->stepDesign->groupNum    = array('一', '二', '三');
$lang->pivot->stepDesign->selectField = '选择字段';
$lang->pivot->stepDesign->selectStat  = '选择统计方式';
$lang->pivot->stepDesign->add         = '添加';
$lang->pivot->stepDesign->delete      = '删除';
$lang->pivot->stepDesign->groupField  = '分组字段';
$lang->pivot->stepDesign->columnField = '字段';
$lang->pivot->stepDesign->stat        = '计算方式';

$lang->pivot->stepDesign->moreThanOne = '至少选择一个分组字段。';
$lang->pivot->stepDesign->summaryTip  = '勾选后，可配置行分组、列设置、显示列的汇总。';
$lang->pivot->stepDesign->groupsTip   = '通过选择分组字段，对SQL查询的数据进行分组，并分层级显示在透视表中。';
$lang->pivot->stepDesign->columnsTip  = '在透视表中添加1列并对其进行设置。';

$lang->pivot->stepDesign->columnTotal    = '显示列的汇总';
$lang->pivot->stepDesign->columnTotalTip = '增加一行显示每一列的汇总数据。';
$lang->pivot->stepDesign->total          = '总计';

$lang->pivot->stepDesign->columnTotalList = array();
$lang->pivot->stepDesign->columnTotalList['noShow'] = '不显示';
$lang->pivot->stepDesign->columnTotalList['sum']    = '求和';

$lang->pivot->stepDesign->sliceFieldList = array();
$lang->pivot->stepDesign->sliceFieldList['noSlice'] = '不切片';

$lang->pivot->stepDesign->showModeList = array();
$lang->pivot->stepDesign->showModeList['default'] = '默认（数值）';
$lang->pivot->stepDesign->showModeList['total']   = '总计百分比';
$lang->pivot->stepDesign->showModeList['row']     = '行汇总百分比';
$lang->pivot->stepDesign->showModeList['column']  = '列汇总百分比';

$lang->pivot->stepDesign->showTotalList = array();
$lang->pivot->stepDesign->showTotalList['noShow'] = '不显示';
$lang->pivot->stepDesign->showTotalList['sum']    = '求和';

$lang->pivot->stepDesign->statList = array();
$lang->pivot->stepDesign->statList['']         = '';
$lang->pivot->stepDesign->statList['count']    = '计数';
$lang->pivot->stepDesign->statList['distinct'] = '去重后计数';
$lang->pivot->stepDesign->statList['avg']      = '平均值';
$lang->pivot->stepDesign->statList['sum']      = '求和';
$lang->pivot->stepDesign->statList['max']      = '最大值';
$lang->pivot->stepDesign->statList['min']      = '最小值';

$lang->pivot->stepDrill = new stdclass();
$lang->pivot->stepDrill->drill       = '数据下钻';
$lang->pivot->stepDrill->addDrill    = '数据下钻';
$lang->pivot->stepDrill->drillView   = '数据详情';
$lang->pivot->stepDrill->fieldEmpty  = '下钻列不能为空';
$lang->pivot->stepDrill->objectEmpty = '目标对象不能为空';
$lang->pivot->stepDrill->drillEmpty  = '下钻字段不能为空';
$lang->pivot->stepDrill->queryEmpty  = '查询字段不能为空';

$lang->datepicker->dpText->TEXT_WEEK_MONDAY = '本周一';
$lang->datepicker->dpText->TEXT_WEEK_SUNDAY = '本周日';
$lang->datepicker->dpText->TEXT_MONTH_BEGIN = '本月初';
$lang->datepicker->dpText->TEXT_MONTH_END   = '本月末';

$lang->pivot->drill = new stdclass();
$lang->pivot->drill->common           = '数据下钻';
$lang->pivot->drill->drillCondition   = '下钻条件';
$lang->pivot->drill->drillResult      = '下钻结果';
$lang->pivot->drill->selectField      = '选择要下钻的列';
$lang->pivot->drill->selectObject     = '关联目标对象';
$lang->pivot->drill->setCondition     = '设置下钻的查询条件';
$lang->pivot->drill->equal            = '=';
$lang->pivot->drill->inDrillField     = '下钻查询表中的';
$lang->pivot->drill->inQueryField     = '查询结果字段';
$lang->pivot->drill->preview          = '预览';
$lang->pivot->drill->save             = '保存';
$lang->pivot->drill->drillFieldText   = "%s(%s).%s";
$lang->pivot->drill->storyName        = '需求名称';
$lang->pivot->drill->releaseStories   = "完成的需求";
$lang->pivot->drill->productName      = "产品名称";
$lang->pivot->drill->activatedBug     = "激活的Bug数";
$lang->pivot->drill->auto             = "自动";
$lang->pivot->drill->designChangedTip = '设计变更，请检查';
