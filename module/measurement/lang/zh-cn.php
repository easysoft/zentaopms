<?php
$lang->measurement->common       = '度量';
$lang->measurement->setTips      = '区间提示';
$lang->measurement->scope        = '范围';
$lang->measurement->object       = '对象';
$lang->measurement->purpose      = '目的';
$lang->measurement->code         = '代号';
$lang->measurement->order        = '排序';
$lang->measurement->returns      = '返回类型';
$lang->measurement->definition   = '度量定义';
$lang->measurement->name         = '度量名称';
$lang->measurement->type         = '度量类型';
$lang->measurement->params       = '参数设置';
$lang->measurement->basicMeas    = '基本度量';
$lang->measurement->deriveMeas   = '衍生度量';
$lang->measurement->measList     = '度量列表';
$lang->measurement->reportList   = '报告列表';
$lang->measurement->saveReport   = '保存此报告';
$lang->measurement->saveReportAB = '保存报告';
$lang->measurement->test         = '测试度量';
$lang->measurement->batchEdit    = '批量编辑';
$lang->measurement->sqlBuilder   = '度量数据';
$lang->measurement->template     = '报表模板';
$lang->measurement->model        = '项目模型';

$lang->measurement->modelList['waterfall'] = '瀑布';
$lang->measurement->modelList['scrum']     = '敏捷';

$lang->measurement->report = new stdclass;
$lang->measurement->report->name        = '报告名称';
$lang->measurement->report->program     = '项目';
$lang->measurement->report->product     = '产品';
$lang->measurement->report->project     = '阶段';
$lang->measurement->report->createdBy   = '创建人';
$lang->measurement->report->createdDate = '创建时间';

$lang->measurement->searchMeas         = "搜索度量";
$lang->measurement->designPHP          = "设置PHP";
$lang->measurement->designSQL          = "设置度量SQL";
$lang->measurement->initCrontabQueue   = "初始化计划任务队列";
$lang->measurement->execCrontabQueue   = "处理计划任务队列";
$lang->measurement->saveSqlMeasSuccess = "查询成功，测试结果：%s";

$lang->measurement->actionConfig = "动作设置";
$lang->measurement->moduleName   = '模块名称';
$lang->measurement->actionName   = '动作名称';
$lang->measurement->cycleConfig  = "定时设置";
$lang->measurement->execTime     = "执行时间";
$lang->measurement->cycleDay     = '天';
$lang->measurement->cycleWeek    = '周';
$lang->measurement->cycleMonth   = '月';
$lang->measurement->every        = '间隔';
$lang->measurement->dayNames     = array(1 => '星期一', 2 => '星期二', 3 => '星期三', 4 => '星期四', 5 => '星期五', 6 => '星期六', 0 => '星期日');

$lang->measurement->cycleType['day']   = '每隔%s天';
$lang->measurement->cycleType['week']  = '每周%s';
$lang->measurement->cycleType['month'] = '每月%s';

$lang->measurement->scopeList[''] = '';
$lang->measurement->scopeList['project'] = '项目';
$lang->measurement->scopeList['product'] = '产品';
$lang->measurement->scopeList['sprint']  = '阶段';

$lang->measurement->purposeList[''] = '';
$lang->measurement->purposeList['scale']    = '规模';
$lang->measurement->purposeList['duration'] = '工期';
$lang->measurement->purposeList['workload'] = '工作量';
$lang->measurement->purposeList['cost']     = '成本';
$lang->measurement->purposeList['quality']  = '质量';

$lang->measurement->objectList[''] = '';
$lang->measurement->objectList['staff']       = '人员';
$lang->measurement->objectList['finance']     = '任务';
$lang->measurement->objectList['case']        = '用例';
$lang->measurement->objectList['bug']         = '缺陷';
$lang->measurement->objectList['review']      = '评审';
$lang->measurement->objectList['stage']       = '阶段';
$lang->measurement->objectList['program']     = '项目';
$lang->measurement->objectList['softRequest'] = '软件需求';
$lang->measurement->objectList['userRequest'] = '用户需求';

$lang->measurement->typeList['basic']  = $lang->measurement->basicMeas;

$lang->measurement->sysData = $lang->measurement->typeList;
$lang->measurement->sysData['report'] = '单一报表';

$lang->measurement->collectTypeList['crontab'] = '定时计划';
$lang->measurement->collectTypeList['action']  = '动作触发';

$lang->measurement->buildinParams = new stdclass;
$lang->measurement->buildinParams->program = '项目';
$lang->measurement->buildinParams->day     = '日期';

$lang->measurement->codeExistence    = '度量代号：%s已存在。';
$lang->measurement->codeEmpty        = 'ID：%s的度量代号不能为空。';
$lang->measurement->nameEmpty        = 'ID：%s的度量名称不能为空。';
$lang->measurement->unitEmpty        = 'ID：%s的度量单位不能为空。';
$lang->measurement->definitionEmpty  = 'ID：%s的度量定义不能为空。';

$lang->measurement->noticeScope      = '通知范围';
$lang->measurement->design           = '设计';
$lang->measurement->browse           = '浏览列表';
$lang->measurement->browseBasic      = '基本度量';
$lang->measurement->browseDerivation = '衍生度量';
$lang->measurement->create           = '创建新度量';
$lang->measurement->createBasic      = $lang->measurement->create;
$lang->measurement->editBasic        = '编辑基本度量';
$lang->measurement->editDerivation   = '编辑衍生度量';
$lang->measurement->delete           = '删除';
$lang->measurement->deleted          = '已删除';
$lang->measurement->collectType      = '收集方式';
$lang->measurement->collectConf      = '收集配置';
$lang->measurement->collectedBy      = '收集人';
$lang->measurement->unit             = '度量单位';
$lang->measurement->save             = '保存';
$lang->measurement->saveSuccess      = '保存成功';
$lang->measurement->reDesign         = '重新设计';
$lang->measurement->confirmDelete    = '确定删除吗？';
$lang->measurement->options          = '操作';
$lang->measurement->id               = '编号';
$lang->measurement->createTemplate   = '创建复合模板';
$lang->measurement->createSingle     = '创建单一模板';
$lang->measurement->editTemplate     = '编辑模板';
$lang->measurement->viewTemplate     = '查看模板';
$lang->measurement->content          = '内容';
$lang->measurement->addMeas          = '添加度量项';
$lang->measurement->dataSource       = '数据源';
$lang->measurement->dataName         = '数据标识';
$lang->measurement->setSQL           = '设置SQL语句';
$lang->measurement->setPHP           = '设置PHP代码';
$lang->measurement->callSqlBuilder   = '调用SQL构建器';
$lang->measurement->query            = '查询';
$lang->measurement->byQuery          = '搜索';
$lang->measurement->call             = '调用';
$lang->measurement->queryResult      = '查询结果：';
$lang->measurement->setParams        = '设置参数';
$lang->measurement->createdBy        = '由谁创建';
$lang->measurement->createdDate      = '创建日期';
$lang->measurement->purpose          = '目的';
$lang->measurement->aim              = '度量目标';
$lang->measurement->analyst          = '分析人';
$lang->measurement->analysisMethod   = '分析方法';

$lang->measurement->placeholder = new stdclass();
$lang->measurement->placeholder->sql = '请填写完整的mysql自定义函数语句。';
$lang->measurement->placeholder->php = '请按照系统要求的编码，类名不能修改，必须包含get 方法。';
$lang->measurement->codeTemplate = <<<EOT
<?php
class %sModel extends model
{
    public function get(\$param1)
    {
        return \$param1 + \$param2;
    }
}
?>
EOT;

$lang->measurement->sqlTemplate = <<<EOT
CREATE FUNCTION `%s`(%s) RETURNS
BEGIN

END
EOT;

$lang->measurement->param = new stdclass();
$lang->measurement->param->varName      = '变量名';
$lang->measurement->param->showName     = '显示名';
$lang->measurement->param->varType      = '类型';
$lang->measurement->param->defaultValue = '默认值';
$lang->measurement->param->queryValue   = '测试值';

$lang->measurement->param->typeList['input']   = '文本框';
$lang->measurement->param->typeList['date']    = '日期';
$lang->measurement->param->typeList['select']  = '下拉菜单';

$lang->measurement->param->options['project'] = '项目列表';
$lang->measurement->param->options['product'] = $lang->productCommon . '列表';
$lang->measurement->param->options['sprint']  = $lang->executionCommon . '列表';

$lang->measurement->tips = new stdclass();
$lang->measurement->tips->nameError        = 'Mysql 自定义函数名错误，请检查函数名。';
$lang->measurement->tips->createError      = "创建 Mysql 自定义函数失败，错误信息：<br/> %s";
$lang->measurement->tips->noticeSelect     = 'SQL语句只能是查询语句';
$lang->measurement->tips->noticeBlack      = 'SQL中含有禁用SQL关键字 %s';
$lang->measurement->tips->noticeVarName    = '变量名称没有设置';
$lang->measurement->tips->noticeVarType    = '变量 %s 的类型没有设置';
$lang->measurement->tips->noticeShowName   = '变量 %s 的显示名称没有设置';
$lang->measurement->tips->noticeQueryValue = '变量 %s 的测试值没有设置。';
$lang->measurement->tips->showNameMissed   = '变量 %s 的显示名没有设置。';
$lang->measurement->tips->errorSql         = 'SQL语句有错！错误：';
$lang->measurement->tips->click2SetParams  = '请先点击红色变量块设置参数，然后';
$lang->measurement->tips->view             = '预览';
$lang->measurement->tips->click2InsertData = "点击 <span class='ke-icon-holder'></span> 来插入度量指标或报表";

$lang->basicmeas = new stdclass();
$lang->basicmeas->name       = $lang->measurement->name;
$lang->basicmeas->code       = $lang->measurement->code;
$lang->basicmeas->unit       = $lang->measurement->unit;
$lang->basicmeas->definition = $lang->measurement->definition;

$lang->derivemeas = new stdclass();
$lang->derivemeas->name    = $lang->measurement->name;
$lang->derivemeas->purpose = $lang->measurement->purpose;

$lang->meastemplate = new stdclass();
$lang->meastemplate->id          = '编号';
$lang->meastemplate->single      = '单一模板';
$lang->meastemplate->complex     = '复合模板';
$lang->meastemplate->name        = '模板名称';
$lang->meastemplate->desc        = '描述';
$lang->meastemplate->content     = '内容';
$lang->meastemplate->createdBy   = '由谁创建';
$lang->meastemplate->createdDate = '创建时间';
$lang->meastemplate->addedBy     = '由谁创建';
$lang->meastemplate->addedDate   = '创建时间';

$lang->meastemplate->actions = array();
$lang->measurement->actions[] = '单元测试前';
$lang->measurement->actions[] = '测试完成后';
$lang->measurement->actions[] = '测试报告评审结束';
$lang->measurement->actions[] = '测试计划评审';
$lang->measurement->actions[] = '里程碑报告评审后';
$lang->measurement->actions[] = '里程碑评审过后';
$lang->measurement->actions[] = '量化项目监控过程中';
$lang->measurement->actions[] = '需求完成后';
$lang->measurement->actions[] = '需求评审后';
$lang->measurement->actions[] = '需求说明书编撰完成后';
$lang->measurement->actions[] = '需求说明书评审';
$lang->measurement->actions[] = '需求里程碑过后';
$lang->measurement->actions[] = '项目计划评审后';
$lang->measurement->actions[] = '项目计划评审结束';

$lang->measurement->actions['project.close'] = '项目结束';
