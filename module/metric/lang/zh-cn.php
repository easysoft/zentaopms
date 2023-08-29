<?php
$lang->metric->common        = "度量项";
$lang->metric->name          = "度量名称";
$lang->metric->stage         = "阶段";
$lang->metric->scope         = "度量范围";
$lang->metric->object        = "度量对象";
$lang->metric->purpose       = "度量目的";
$lang->metric->unit          = "度量单位";
$lang->metric->code          = "度量代号";
$lang->metric->desc          = "度量项描述";
$lang->metric->definition    = "定义";
$lang->metric->formula       = "计算规则";
$lang->metric->when          = "收集方式";
$lang->metric->createdBy     = "创建者";
$lang->metric->implementedBy = "由谁实现";
$lang->metric->offlineBy     = "由谁下架";
$lang->metric->lastEdited    = "最后修改";
$lang->metric->value         = "数值";
$lang->metric->date          = "日期";
$lang->metric->metricData    = "度量数据";
$lang->metric->system        = "system";
$lang->metric->weekCell      = "%s年第%s周";
$lang->metric->create        = "创建" . $this->lang->metric->common;
$lang->metric->afterCreate   = "保存后";
$lang->metric->definition    = "计算规则";
$lang->metric->customUnit    = "自定义";

$lang->metric->descTip       = '请输入度量项含义、目的和作用等';
$lang->metric->definitionTip = '请输入度量项的计算规则及过滤条件等';

$lang->metric->noDesc    = "暂无描述";
$lang->metric->noFormula = "暂无计算规则";
$lang->metric->noCalc    = "暂未实现度量项PHP算法";

$lang->metric->legendBasicInfo  = '基本信息';
$lang->metric->legendCreateInfo = '创建编辑信息';

$lang->metric->confirmDelete = "确认要删除吗？";

$lang->metric->browseAction = '度量项列表';

$lang->metric->stageList = array();
$lang->metric->stageList['wait']     = "未发布";
$lang->metric->stageList['released'] = "已发布";

$lang->metric->unitList = array();
$lang->metric->unitList[''] = '';

$lang->metric->afterCreateList = array();
$lang->metric->afterCreateList['back']   = '返回列表页';
$lang->metric->afterCreateList['finish'] = '去实现度量项';

$lang->metric->dateList = array();
$lang->metric->dateList['year']  = '年';
$lang->metric->dateList['month'] = '月';
$lang->metric->dateList['week']  = '周';
$lang->metric->dateList['day']   = '日';

$lang->metric->purposeList = array();
$lang->metric->purposeList['scale'] = "规模估算";
$lang->metric->purposeList['time']  = "工期控制";
$lang->metric->purposeList['cost']  = "成本计算";
$lang->metric->purposeList['hour']  = "工时统计";
$lang->metric->purposeList['qc']    = "质量控制";
$lang->metric->purposeList['rate']  = "效率提升";

$lang->metric->scopeList = array();
$lang->metric->scopeList['system']    = "系统";
$lang->metric->scopeList['program']   = "项目集";
$lang->metric->scopeList['product']   = "产品";
$lang->metric->scopeList['project']   = "项目";
$lang->metric->scopeList['execution'] = "执行";
$lang->metric->scopeList['dept']      = "团队";
$lang->metric->scopeList['user']      = "个人";
$lang->metric->scopeList['code']      = "代码库";
$lang->metric->scopeList['pipeline']  = "流水线";

global $config;
$lang->metric->objectList = array();
$lang->metric->objectList['program']     = "项目集";
$lang->metric->objectList['line']        = "产品线";
$lang->metric->objectList['product']     = "产品";
$lang->metric->objectList['project']     = "项目";
$lang->metric->objectList['productplan'] = "计划";
$lang->metric->objectList['execution']   = "执行";
$lang->metric->objectList['release']     = "发布";
$lang->metric->objectList['story']       = "研发需求";
$lang->metric->objectList['requirement'] = "用户需求";
$lang->metric->objectList['task']        = "任务";
$lang->metric->objectList['bug']         = "Bug";
$lang->metric->objectList['case']        = "用例";
$lang->metric->objectList['user']        = "人员";
$lang->metric->objectList['effort']      = "工时";
$lang->metric->objectList['doc']         = "文档";
if($config->edition != 'open')
{
    $lang->metric->objectList['feedback']    = "反馈";
    $lang->metric->objectList['risk']        = "风险";
    $lang->metric->objectList['issue']       = "问题";
}
