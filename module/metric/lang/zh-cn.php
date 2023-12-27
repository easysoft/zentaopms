<?php
$lang->metric->common        = "度量项";
$lang->metric->name          = "名称";
$lang->metric->stage         = "阶段";
$lang->metric->scope         = "范围";
$lang->metric->object        = "对象";
$lang->metric->purpose       = "目的";
$lang->metric->unit          = "单位";
$lang->metric->code          = "代号";
$lang->metric->desc          = "描述";
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
$lang->metric->weekS         = "%s周";
$lang->metric->create        = "创建" . $this->lang->metric->common;
$lang->metric->edit          = "编辑" . $this->lang->metric->common;
$lang->metric->view          = '查看' . $this->lang->metric->common;
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
$lang->metric->remove        = '移除';
$lang->metric->zAnalysis     = 'Z分析';
$lang->metric->sqlStatement  = "SQL语句";
$lang->metric->other         = '其他';
$lang->metric->collectType   = '收集方式';
$lang->metric->oldMetricInfo = '旧版详情';
$lang->metric->collectConf   = '定时设置';
$lang->metric->verifyFile    = '校验文件';
$lang->metric->verifyResult  = '校验结果';
$lang->metric->publish       = '发布';
$lang->metric->moveFailTip   = '移动度量项文件失败';
$lang->metric->selectCount   = '已选<span class="font-medium checked-count">%s</span>项';
$lang->metric->testMetric    = '测试度量';
$lang->metric->calcTime      = '采集时间';
$lang->metric->to            = '至';
$lang->metric->year          = '年份';
$lang->metric->month         = '月份';
$lang->metric->week          = '周';
$lang->metric->day           = '日期';
$lang->metric->nodate        = '采集日期';

$lang->metric->placeholder = new stdclass();
$lang->metric->placeholder->select    = "请选择";
$lang->metric->placeholder->project   = "全部项目";
$lang->metric->placeholder->product   = "全部产品";
$lang->metric->placeholder->execution = "全部执行";
$lang->metric->placeholder->dept      = "全部团队";
$lang->metric->placeholder->user      = "全部用户";
$lang->metric->placeholder->program   = "全部项目集";

$lang->metric->query = new stdclass();
$lang->metric->query->action = '查询';

$lang->metric->query->scope = array();
$lang->metric->query->scope['project']   = '项目';
$lang->metric->query->scope['product']   = '产品';
$lang->metric->query->scope['execution'] = '执行';
$lang->metric->query->scope['dept']      = '团队';
$lang->metric->query->scope['user']      = '姓名';
$lang->metric->query->scope['program']   = '项目集';

$lang->metric->query->yearLabels = array();
$lang->metric->query->yearLabels['3']   = '近3年';
$lang->metric->query->yearLabels['5']   = '近5年';
$lang->metric->query->yearLabels['10']  = '近10年';
$lang->metric->query->yearLabels['all'] = '全部';

$lang->metric->query->monthLabels = array();
$lang->metric->query->monthLabels['6']   = '近6个月';
$lang->metric->query->monthLabels['12']  = '近12个月';
$lang->metric->query->monthLabels['24']  = '近24个月';
$lang->metric->query->monthLabels['36']  = '近36个月';

$lang->metric->query->weekLabels = array();
$lang->metric->query->weekLabels['4']  = '近4周';
$lang->metric->query->weekLabels['8']  = '近8周';
$lang->metric->query->weekLabels['12'] = '近12周';
$lang->metric->query->weekLabels['16'] = '近16周';

$lang->metric->query->dayLabels = array();
$lang->metric->query->dayLabels['7']  = '近7天';
$lang->metric->query->dayLabels['14'] = '近14天';
$lang->metric->query->dayLabels['21'] = '近21天';
$lang->metric->query->dayLabels['28'] = '近28天';

$lang->metric->viewType = new stdclass();
$lang->metric->viewType->single   = '单独查看';
$lang->metric->viewType->multiple = '组合查看';

$lang->metric->descTip            = '请输入度量项含义、目的和作用等';
$lang->metric->definitionTip      = '请输入度量项的计算规则及过滤条件等';
$lang->metric->collectConfText    = "每%s的%s的%s";
$lang->metric->emptyCollect       = '暂时没有收藏度量项。';
$lang->metric->moveFailTip        = '移动度量项文件失败。';
$lang->metric->maxSelect          = '最多选择%s个度量项';
$lang->metric->maxSelectTip       = '可跨范围勾选多个度量项，最多选择%s个。';
$lang->metric->upgradeTip         = '此度量项为旧版本支持的度量项，若想进行编辑，请根据新版本度量项的配置规则进行重新配置。同时请注意，新版本度量项不再支持SQL编辑器，暂时无法被报表模板引用。请确认是否需要进行编辑操作。';
$lang->metric->saveSqlMeasSuccess = "查询成功，测试结果：%s";
$lang->metric->monthText          = "%s号";
$lang->metric->errorDateRange     = "开始日期不能大于结束日期";
$lang->metric->errorCalcTimeRange = "采集开始时间不能大于采集结束时间";
$lang->metric->updateTimeTip      = "更新快照时间：%s";

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
$lang->metric->delistAction    = '下架度量项';
$lang->metric->detailsAction   = '度量项详情';

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
// $lang->metric->featureBar['preview']['system']    = '系统';
// $lang->metric->featureBar['preview']['code']      = '代码库';
$lang->metric->featureBar['preview']['pipeline']  = '流水线';

$lang->metric->more        = '更多';
$lang->metric->collect     = '我收藏的';
$lang->metric->collectStar = '收藏';

$lang->metric->oldMetric      = new stdclass();
$lang->metric->oldMetric->sql = 'SQL';
$lang->metric->oldMetric->tip = '这是旧版度量项的实现方式';

$lang->metric->oldMetric->dayNames = array(1 => '星期一', 2 => '星期二', 3 => '星期三', 4 => '星期四', 5 => '星期五', 6 => '星期六', 0 => '星期日');

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
// $lang->metric->scopeList['code']      = "代码库";
// $lang->metric->scopeList['pipeline']  = "流水线";
$lang->metric->scopeList['other']     = "其他";

global $config;
$lang->metric->objectList = array();
$lang->metric->objectList['program']       = "项目集";
$lang->metric->objectList['line']          = "产品线";
$lang->metric->objectList['product']       = "产品";
$lang->metric->objectList['project']       = "项目";
$lang->metric->objectList['productplan']   = "计划";
$lang->metric->objectList['execution']     = "执行";
$lang->metric->objectList['release']       = "发布";
$lang->metric->objectList['story']         = $lang->SRCommon;
$lang->metric->objectList['requirement']   = $lang->URCommon;
$lang->metric->objectList['task']          = "任务";
$lang->metric->objectList['bug']           = "Bug";
$lang->metric->objectList['case']          = "用例";
$lang->metric->objectList['user']          = "人员";
$lang->metric->objectList['effort']        = "工时";
$lang->metric->objectList['doc']           = "文档";
$lang->metric->objectList['codebase']      = "代码库";
$lang->metric->objectList['pipeline']      = "流水线";
$lang->metric->objectList['artifact']      = "制品库";
$lang->metric->objectList['deployment']    = "上线";
$lang->metric->objectList['node']          = "节点";
$lang->metric->objectList['application']   = "应用";
$lang->metric->objectList['cpu']           = "CPU";
$lang->metric->objectList['memory']        = "内存";
$lang->metric->objectList['commit']        = "代码提交";
$lang->metric->objectList['mergeRequest']  = "合并请求";
$lang->metric->objectList['code']          = "代码";
$lang->metric->objectList['vulnerability'] = "安全漏洞";
$lang->metric->objectList['codeAnalysis']  = "代码分析";
if(in_array($config->edition, array('biz', 'max', 'ipd')))
{
    $lang->metric->objectList['feedback'] = "反馈";
}
if(in_array($config->edition, array('max', 'ipd')))
{
    $lang->metric->objectList['risk']     = "风险";
    $lang->metric->objectList['issue']    = "问题";
}
$lang->metric->objectList['review'] = "评审";
$lang->metric->objectList['other']  = "其他";

$lang->metric->chartTypeList = array();
$lang->metric->chartTypeList['line'] = '折线图';
$lang->metric->chartTypeList['barX'] = '柱形图';
$lang->metric->chartTypeList['barY'] = '条形图';
$lang->metric->chartTypeList['pie']  = '饼图';

$lang->metric->filter = new stdclass();
$lang->metric->filter->common  = '筛选';
$lang->metric->filter->scope   = '范围';
$lang->metric->filter->object  = '对象';
$lang->metric->filter->purpose = '目的';
$lang->metric->filter->clear   = '全部清除';

$lang->metric->filter->clearAction = '清除已选%s';
$lang->metric->filter->checkedInfo = '已筛选：范围(%s)、对象(%s)、目的(%s)';
$lang->metric->filter->filterTotal = '筛选结果(%s)';

$lang->metric->implement = new stdclass();
$lang->metric->implement->common      = "实现";
$lang->metric->implement->tip         = "请通过PHP实现该该度量项的计算逻辑。";
$lang->metric->implement->instruction = "实现说明";
$lang->metric->implement->downloadPHP = "下载度量模板";

$lang->metric->implement->instructionTips = array();
$lang->metric->implement->instructionTips[] = '1.下载度量项模板文件，对文件进行编码开发操作，操作参考手册。<a class="btn text-primary ghost" target="_blank" href="https://www.zentao.net/book/zentaopms/1103.html">查看参考手册>></a>';
$lang->metric->implement->instructionTips[] = '2.请将开发后的文件放到下方目录，<strong>需保持文件名称与度量代号一致</strong>。<br/> <span class="label code-slate">{tmpRoot}metric</span>';
$lang->metric->implement->instructionTips[] = '3.执行命令赋予文件可执行权限：<p><span class="label code-slate">chmod 777 {tmpRoot}metric</span></p><p><span class="label code-slate">chmod 777 {tmpRoot}metric/{code}.php</span></p>';

$lang->metric->verifyCustom = new stdclass();
$lang->metric->verifyCustom->checkCustomCalcExists = array();
$lang->metric->verifyCustom->checkCustomCalcExists['text']       = '检查度量项文件是否存在';
$lang->metric->verifyCustom->checkCustomCalcExists['error']      = '度量项文件不存在';

$lang->metric->verifyCustom->checkCustomCalcSyntax = array();
$lang->metric->verifyCustom->checkCustomCalcSyntax['text']       = '检查语法错误';
$lang->metric->verifyCustom->checkCustomCalcSyntax['error']      = '语法错误';

$lang->metric->verifyCustom->checkCustomCalcClassName = array();
$lang->metric->verifyCustom->checkCustomCalcClassName['text']    = '检查度量项类名是否正确';
$lang->metric->verifyCustom->checkCustomCalcClassName['error']   = '度量项类名错误';

$lang->metric->verifyCustom->checkCustomCalcClassMethod = array();
$lang->metric->verifyCustom->checkCustomCalcClassMethod['text']  = '检查度量项是否定义了必须的方法';
$lang->metric->verifyCustom->checkCustomCalcClassMethod['error'] = '度量项没有定义必须的方法';

$lang->metric->verifyCustom->checkCustomCalcRuntime = array();
$lang->metric->verifyCustom->checkCustomCalcRuntime['text']      = '检查度量项运行时错误';
$lang->metric->verifyCustom->checkCustomCalcRuntime['error']     = '';

$lang->metric->weekList = array();
$lang->metric->weekList['1'] = '星期一';
$lang->metric->weekList['2'] = '星期二';
$lang->metric->weekList['3'] = '星期三';
$lang->metric->weekList['4'] = '星期四';
$lang->metric->weekList['5'] = '星期五';
$lang->metric->weekList['6'] = '星期六';
$lang->metric->weekList['0'] = '星期日';

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

$lang->metric->tips = new stdclass();
$lang->metric->tips->nameError        = 'Mysql 自定义函数名错误，请检查函数名。';
$lang->metric->tips->createError      = "创建 Mysql 自定义函数失败，错误信息：<br/> %s";
$lang->metric->tips->noticeSelect     = 'SQL语句只能是查询语句';
$lang->metric->tips->noticeBlack      = 'SQL中含有禁用SQL关键字 %s';
$lang->metric->tips->noticeVarName    = '变量名称没有设置';
$lang->metric->tips->noticeVarType    = '变量 %s 的类型没有设置';
$lang->metric->tips->noticeShowName   = '变量 %s 的显示名称没有设置';
$lang->metric->tips->noticeQueryValue = '变量 %s 的测试值没有设置。';
$lang->metric->tips->showNameMissed   = '变量 %s 的显示名没有设置。';
$lang->metric->tips->errorSql         = 'SQL语句有错！错误：';
$lang->metric->tips->click2SetParams  = '请先点击红色变量块设置参数，然后';
$lang->metric->tips->view             = '预览';
$lang->metric->tips->click2InsertData = "点击 <span class='ke-icon-holder'></span> 来插入度量指标或报表";

$lang->metric->param = new stdclass();
$lang->metric->param->varName      = '变量名';
$lang->metric->param->showName     = '显示名';
$lang->metric->param->varType      = '类型';
$lang->metric->param->defaultValue = '默认值';
$lang->metric->param->queryValue   = '测试值';

$lang->metric->param->typeList['input']  = '文本框';
$lang->metric->param->typeList['date']   = '日期';
$lang->metric->param->typeList['select'] = '下拉菜单';

$lang->metric->param->options['project'] = '项目列表';
$lang->metric->param->options['product'] = $lang->productCommon . '列表';
$lang->metric->param->options['sprint']  = $lang->executionCommon . '列表';
