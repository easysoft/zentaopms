<?php
$lang->pipeline->browse        = '浏览流水线';
$lang->pipeline->create        = '创建流水线';
$lang->pipeline->edit          = '编辑流水线';
$lang->pipeline->exec          = '执行流水线';
$lang->pipeline->runPipeline   = '运行流水线';
$lang->pipeline->view          = '流水线详情';
$lang->pipeline->delete        = '删除流水线';
$lang->pipeline->confirmDelete = '确认删除该流水线';
$lang->pipeline->dirChange     = '目录改动';
$lang->pipeline->buildTag      = '打标签';
$lang->pipeline->execSuccess   = '执行成功';
$lang->pipeline->trigger       = '触发器';
$lang->pipeline->autoRun       = '是否触发';
$lang->pipeline->addTrigger    = '添加触发方式';
$lang->pipeline->createType    = '创建方式';
$lang->pipeline->existPipeline = '现有流水线';
$lang->pipeline->execution     = '执行历史';
$lang->pipeline->log           = '执行日志';

$lang->pipeline->browseAction = '流水线列表';

$lang->pipeline->id              = 'ID';
$lang->pipeline->name            = '名称';
$lang->pipeline->desc            = '描述';
$lang->pipeline->repo            = '关联代码库';
$lang->pipeline->branch          = '分支';
$lang->pipeline->product         = '关联' . $lang->productCommon;
$lang->pipeline->svnDir          = 'SVN监控路径';
$lang->pipeline->jenkins         = 'Jenkins';
$lang->pipeline->jkHost          = 'Jenkins服务器';
$lang->pipeline->jkJob           = 'Jenkins任务';
$lang->pipeline->buildSpec       = '构建对象'; // 'pipeline@server'
$lang->pipeline->engine          = '引擎';
$lang->pipeline->server          = '服务器';
$lang->pipeline->pipeline        = '流水线';
$lang->pipeline->buildType       = '构建类型';
$lang->pipeline->frame           = '工具/框架';
$lang->pipeline->useZentao       = '禅道触发';
$lang->pipeline->triggerType     = '触发方式';
$lang->pipeline->atDay           = '自定义日期';
$lang->pipeline->atTime          = '执行时间';
$lang->pipeline->lastStatus      = '最后执行状态';
$lang->pipeline->lastExec        = '最后执行时间';
$lang->pipeline->comment         = '匹配关键字';
$lang->pipeline->customParam     = '自定义构建参数';
$lang->pipeline->paramName       = '名称';
$lang->pipeline->paramValue      = '值';
$lang->pipeline->custom          = '自定义';
$lang->pipeline->createdBy       = '由谁创建';
$lang->pipeline->createdDate     = '创建日期';
$lang->pipeline->editedBy        = '由谁编辑';
$lang->pipeline->editedDate      = '编辑日期';
$lang->pipeline->lastTag         = '最后标签';
$lang->pipeline->deleted         = '已删除';
$lang->pipeline->repoServer      = '版本库服务器';
$lang->pipeline->sonarqubeServer = 'SonarQube服务器';
$lang->pipeline->projectKey      = 'SonarQube项目';
$lang->pipeline->space           = '空间';
$lang->pipeline->status          = '状态';
$lang->pipeline->triggerPerson   = '触发人';
$lang->pipeline->triggerDate     = '触发时间';
$lang->pipeline->duration        = '执行时长';
$lang->pipeline->pipelineName    = '流水线名称';

$lang->pipeline->lblBasic = '基本信息';

$lang->pipeline->auto           = '自动';
$lang->pipeline->example        = '举例';
$lang->pipeline->commitEx       = "用于匹配创建构建任务的关键字，多个关键字用','分割";
$lang->pipeline->cronSample     = '如 0 0 2 * * 2-6/1 表示每个工作日凌晨2点';
$lang->pipeline->sendExec       = '发送执行请求成功！执行结果：%s';
$lang->pipeline->inputName      = '请输入参数名称。';
$lang->pipeline->invalidName    = '参数名称应该是英文字母、数字或下划线的组合。';
$lang->pipeline->repoExists     = '此代码库已关联流水线『%s』';
$lang->pipeline->projectExists  = '此SonarQube项目已关联构建任务『%s』';
$lang->pipeline->mustUseJenkins = 'SonarQube工具/框架仅在构建引擎为JenKins的情况下使用';
$lang->pipeline->jobIsDeleted   = '此版本库已关联构建任务,请从回收站查看数据';
$lang->pipeline->selectPipeline = '请选择流水线';
$lang->pipeline->triggerRepeat  = '触发方式不能重复';

$lang->pipeline->buildTypeList['build']          = '仅构建';
$lang->pipeline->buildTypeList['buildAndDeploy'] = '构建部署';
$lang->pipeline->buildTypeList['buildAndTest']   = '构建测试';

$lang->pipeline->triggerTypeList['tag']      = '打标签';
$lang->pipeline->triggerTypeList['commit']   = '提交注释包含关键字';
$lang->pipeline->triggerTypeList['schedule'] = '定时计划';

$lang->pipeline->frameList['']          = '';
$lang->pipeline->frameList['junit']     = 'JUnit';
$lang->pipeline->frameList['testng']    = 'TestNG';
$lang->pipeline->frameList['phpunit']   = 'PHPUnit';
$lang->pipeline->frameList['pytest']    = 'Pytest';
$lang->pipeline->frameList['jtest']     = 'JTest';
$lang->pipeline->frameList['cppunit']   = 'CppUnit';
$lang->pipeline->frameList['gtest']     = 'GTest';
$lang->pipeline->frameList['qtest']     = 'QTest';
$lang->pipeline->frameList['sonarqube'] = 'SonarQube';

$lang->pipeline->paramValueList['']                 = '';
$lang->pipeline->paramValueList['$zentao_version']  = '当前版本号';
$lang->pipeline->paramValueList['$zentao_account']  = '当前用户名';
$lang->pipeline->paramValueList['$zentao_product']  = "当前{$lang->productCommon}ID";
$lang->pipeline->paramValueList['$zentao_repopath'] = '当前版本库路径';

$lang->pipeline->engineList = array();
$lang->pipeline->engineList['']        = '';
$lang->pipeline->engineList['gitlab']  = 'GitLab';
$lang->pipeline->engineList['jenkins'] = 'Jenkins';

$lang->pipeline->engineTips = new stdclass;
$lang->pipeline->engineTips->success = '构建引擎将使用GitLab项目内置的流水线。';
$lang->pipeline->engineTips->error   = '当前GitLab项目内没有可用的流水线，请先前往GitLab配置。';

$lang->pipeline->pipelineTips                      = "选择要运行流水线的分支名或标签名";
$lang->pipeline->pipelineVariables                 = "变量";
$lang->pipeline->pipelineVariablesKeyPlaceHolder   = "输入变量的名称";
$lang->pipeline->pipelineVariablesValuePlaceHolder = "输入变量的值";
$lang->pipeline->pipelineVariablesTips             = "指定要在此次运行中使用的变量值。CI/CD设置中指定的值将用作默认值。";
$lang->pipeline->setReferenceTips                  = "在执行构建前，请先设置代码库的分支信息。";

$lang->pipeline->featureBar['browse']['all']   = '全部';
$lang->pipeline->featureBar['browse']['space'] = '空间下流水线';
$lang->pipeline->featureBar['browse']['repo']  = '代码库下流水线';

$lang->pipeline->featureBar['execution']['all']   = '全部';
$lang->pipeline->featureBar['execution']['space'] = '空间下执行历史';
$lang->pipeline->featureBar['execution']['repo']  = '代码库下执行历史';

$lang->pipeline->createTypeList = array();
$lang->pipeline->createTypeList['new']  = '创建空白流水线';
$lang->pipeline->createTypeList['copy'] = '从现有流水线复制';

$lang->pipeline->statusList = array();
$lang->pipeline->statusList['draft']  = '草稿';
$lang->pipeline->statusList['active'] = '激活';

$lang->pipeline->execStatusList = array();
$lang->pipeline->execStatusList['success']  = '成功';
$lang->pipeline->execStatusList['failure']  = '失败';
$lang->pipeline->execStatusList['running']  = '运行中';
$lang->pipeline->execStatusList['pending']  = '等待中';
$lang->pipeline->execStatusList['error']    = '错误';
$lang->pipeline->execStatusList['skipped']  = '跳过';
$lang->pipeline->execStatusList['blocked']  = '阻塞';
$lang->pipeline->execStatusList['declined'] = '拒绝';

$lang->pipeline->triggerTypeList = array();
$lang->pipeline->triggerTypeList['push']         = '代码推送';
$lang->pipeline->triggerTypeList['manual']       = '手动触发';
$lang->pipeline->triggerTypeList['cron']         = '定时触发';
$lang->pipeline->triggerTypeList['pull_request'] = '拉取请求';
$lang->pipeline->triggerTypeList['tag']          = '打标签';

$lang->pipeline->notice = new stdclass();
$lang->pipeline->notice->saveFailed = "保存失败,请重试。";
