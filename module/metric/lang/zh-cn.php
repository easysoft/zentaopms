<?php
$lang->metric->common        = "度量项";
$lang->metric->name          = "名称";
$lang->metric->stage         = "阶段";
$lang->metric->scope         = "度量范围";
$lang->metric->object        = "度量对象";
$lang->metric->purpose       = "度量目的";
$lang->metric->unit          = "度量单位";
$lang->metric->code          = "度量项代号";
$lang->metric->desc          = "度量项描述";
$lang->metric->formula       = "计算规则";
$lang->metric->when          = "收集方式";
$lang->metric->createdBy     = "创建者";
$lang->metric->implement     = "实现";
$lang->metric->delist        = "下架";
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
$lang->metric->declaration   = "度量定义";
$lang->metric->customUnit    = "自定义";
$lang->metric->delist        = "下架";
$lang->metric->preview       = "预览";
$lang->metric->metricList    = "度量项列表";
$lang->metric->manage        = "管理度量项";
$lang->metric->exitManage    = "退出管理";
$lang->metric->filters       = '筛选器配置';
$lang->metric->details       = '详情';
$lang->metric->zAnalysis     = 'Z分析';
$lang->metric->sqlStatement  = "SQL语句";
$lang->metric->other         = '其他';
$lang->metric->collectType   = '收集方式';
$lang->metric->oldMetricInfo = '旧版详情';
$lang->metric->collectConf   = '定时设置';
$lang->metric->verifyFile    = '校验文件';
$lang->metric->verifyResult  = '校验结果';
$lang->metric->publish       = '发布';

$lang->metric->viewType = new stdclass();
$lang->metric->viewType->single   = '单独查看';
$lang->metric->viewType->multiple = '组合查看';

$lang->metric->descTip         = '请输入度量项含义、目的和作用等';
$lang->metric->definitionTip   = '请输入度量项的计算规则及过滤条件等';
$lang->metric->collectConfText = "每%s的%s的%s";
$lang->metric->emptyCollect    = '暂时没有收藏度量项。';
$lang->metric->moveFailTip     = '移动度量项文件失败。';

$lang->metric->noDesc    = "暂无描述";
$lang->metric->noFormula = "暂无计算规则";
$lang->metric->noCalc    = "暂未实现度量项PHP算法";
$lang->metric->noSQL     = "暂无";

$lang->metric->legendBasicInfo  = '基本信息';
$lang->metric->legendCreateInfo = '创建编辑信息';

$lang->metric->confirmDelete = "确认要删除吗？";
$lang->metric->confirmDelist = "确认要下架吗？";
$lang->metric->notExist      = "度量项不存在";

$lang->metric->browse          = '浏览度量项';
$lang->metric->browseAction    = '度量项列表';
$lang->metric->viewAction      = '查看度量项';
$lang->metric->editAction      = '编辑度量项';
$lang->metric->implementAction = '实现度量项';
$lang->metric->deleteAction    = '删除度量项';

$lang->metric->stageList = array();
$lang->metric->stageList['wait']     = "未发布";
$lang->metric->stageList['released'] = "已发布";

$lang->metric->featureBar['browse']['all']      = '全部';
$lang->metric->featureBar['browse']['wait']     = '未发布';
$lang->metric->featureBar['browse']['released'] = '已发布';

$lang->metric->featureBar['preview']['project']   = '项目';
$lang->metric->featureBar['preview']['product']   = '产品';
$lang->metric->featureBar['preview']['execution'] = '执行';
$lang->metric->featureBar['preview']['dept']      = '团队';
$lang->metric->featureBar['preview']['user']      = '个人';
$lang->metric->featureBar['preview']['program']   = '项目集';
$lang->metric->featureBar['preview']['system']    = '系统';
$lang->metric->featureBar['preview']['code']      = '代码库';
$lang->metric->featureBar['preview']['pipeline']  = '流水线';

$lang->metric->more    = '更多';
$lang->metric->collect = '我收藏的';

$lang->metric->moreSelects = array();

$lang->metric->unitList = array();
$lang->metric->unitList['count']   = '个';
$lang->metric->unitList['measure'] = '工时';
$lang->metric->unitList['hour']    = '小时';
$lang->metric->unitList['day']     = '天';
$lang->metric->unitList['manday']  = '人天';

$lang->metric->afterCreateList = array();
$lang->metric->afterCreateList['back']      = '返回列表页';
$lang->metric->afterCreateList['implement'] = '去实现度量项';

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
$lang->metric->purposeList['other'] = "其他";

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
$lang->metric->scopeList['other']     = "其他";

global $config;
$lang->metric->objectList = array();
$lang->metric->objectList['program']     = "项目集";
$lang->metric->objectList['line']        = "产品线";
$lang->metric->objectList['product']     = "产品";
$lang->metric->objectList['project']     = "项目";
$lang->metric->objectList['productplan'] = "计划";
$lang->metric->objectList['execution']   = "执行";
$lang->metric->objectList['release']     = "发布";
$lang->metric->objectList['story']       = $lang->SRCommon;
$lang->metric->objectList['requirement'] = $lang->URCommon;
$lang->metric->objectList['task']        = "任务";
$lang->metric->objectList['bug']         = "Bug";
$lang->metric->objectList['case']        = "用例";
$lang->metric->objectList['user']        = "人员";
$lang->metric->objectList['effort']      = "工时";
$lang->metric->objectList['doc']         = "文档";
if($config->edition != 'open')
{
    $lang->metric->objectList['feedback'] = "反馈";
    $lang->metric->objectList['risk']     = "风险";
    $lang->metric->objectList['issue']    = "问题";
}
$lang->metric->objectList['review'] = "评审";
$lang->metric->objectList['other']  = "其他";

$lang->metric->implementInstructions = "实现说明";
$lang->metric->implementTips = array();
$lang->metric->implementTips[] = '1.下载度量项模板code.php，注意：文件名称要与度量代号保持一致。';
$lang->metric->implementTips[] = '2.对文件进行编码开发操作，操作参考手册。';
$lang->metric->implementTips[] = '3.请将开发后的code.php文件放到[用户禅道目录]/tmp/metric目录下。';
$lang->metric->implementTips[] = '4.执行命令赋予文件可执行权限。';

$lang->metric->old = new stdclass();

$lang->metric->old->scopeList = array();
$lang->metric->old->scopeList['project'] = '项目';
$lang->metric->old->scopeList['product'] = '产品';
$lang->metric->old->scopeList['sprint']  = '阶段';

$lang->metric->old->purposeList = array();
$lang->metric->old->purposeList['scale']    = '规模';
$lang->metric->old->purposeList['duration'] = '工期';
$lang->metric->old->purposeList['workload'] = '工作量';
$lang->metric->old->purposeList['cost']     = '成本';
$lang->metric->old->purposeList['quality']  = '质量';

$lang->metric->old->objectList = array();
$lang->metric->old->objectList['staff']       = '人员';
$lang->metric->old->objectList['finance']     = '任务';
$lang->metric->old->objectList['case']        = '用例';
$lang->metric->old->objectList['bug']         = '缺陷';
$lang->metric->old->objectList['review']      = '评审';
$lang->metric->old->objectList['stage']       = '阶段';
$lang->metric->old->objectList['program']     = '项目';
$lang->metric->old->objectList['softRequest'] = '软件需求';
$lang->metric->old->objectList['userRequest'] = '用户需求';

$lang->metric->old->collectTypeList = array();
$lang->metric->old->collectTypeList['crontab'] = '定时计划';
$lang->metric->old->collectTypeList['action']  = '动作触发';
