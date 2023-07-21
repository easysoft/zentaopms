<?php
$lang->job->common        = '构建任务';
$lang->job->browse        = '浏览构建任务';
$lang->job->create        = '添加流水线';
$lang->job->edit          = '编辑流水线';
$lang->job->exec          = '执行流水线';
$lang->job->runPipeline   = '运行流水线';
$lang->job->view          = '流水线详情';
$lang->job->delete        = '删除流水线';
$lang->job->confirmDelete = '确认删除该流水线';
$lang->job->dirChange     = '目录改动';
$lang->job->buildTag      = '打标签';
$lang->job->execSuccess   = '执行成功';

$lang->job->browseAction = '流水线列表';

$lang->job->id              = 'ID';
$lang->job->name            = '流水线名称';
$lang->job->repo            = '关联代码库';
$lang->job->branch          = '分支';
$lang->job->product         = '关联' . $lang->productCommon;
$lang->job->svnDir          = 'SVN监控路径';
$lang->job->jenkins         = 'Jenkins';
$lang->job->jkHost          = 'Jenkins服务器';
$lang->job->jkJob           = 'Jenkins任务';
$lang->job->buildSpec       = '构建对象'; // 'pipeline@server'
$lang->job->engine          = '引擎';
$lang->job->server          = '服务器';
$lang->job->pipeline        = '流水线';
$lang->job->buildType       = '构建类型';
$lang->job->frame           = '工具/框架';
$lang->job->triggerType     = '触发方式';
$lang->job->atDay           = '自定义日期';
$lang->job->atTime          = '执行时间';
$lang->job->lastStatus      = '最后执行状态';
$lang->job->lastExec        = '最后执行时间';
$lang->job->comment         = '匹配关键字';
$lang->job->customParam     = '自定义构建参数';
$lang->job->paramName       = '名称';
$lang->job->paramValue      = '值';
$lang->job->custom          = '自定义';
$lang->job->createdBy       = '由谁创建';
$lang->job->createdDate     = '创建日期';
$lang->job->editedBy        = '由谁编辑';
$lang->job->editedDate      = '编辑日期';
$lang->job->lastTag         = '最后标签';
$lang->job->deleted         = '已删除';
$lang->job->repoServer      = '版本库服务器';
$lang->job->sonarqubeServer = 'SonarQube服务器';
$lang->job->projectKey      = 'SonarQube项目';

$lang->job->lblBasic = '基本信息';

$lang->job->example        = '举例';
$lang->job->commitEx       = "用于匹配创建构建任务的关键字，多个关键字用','分割";
$lang->job->cronSample     = '如 0 0 2 * * 2-6/1 表示每个工作日凌晨2点';
$lang->job->sendExec       = '发送执行请求成功！执行结果：%s';
$lang->job->inputName      = '请输入参数名称。';
$lang->job->invalidName    = '参数名称应该是英文字母、数字或下划线的组合。';
$lang->job->repoExists     = '此版本库已关联构建任务『%s』';
$lang->job->projectExists  = '此SonarQube项目已关联构建任务『%s』';
$lang->job->mustUseJenkins = 'SonarQube工具/框架仅在构建引擎为JenKins的情况下使用';
$lang->job->jobIsDeleted   = '此版本库已关联构建任务,请从回收站查看数据';
$lang->job->selectPipeline = '请选择流水线';

$lang->job->buildTypeList['build']          = '仅构建';
$lang->job->buildTypeList['buildAndDeploy'] = '构建部署';
$lang->job->buildTypeList['buildAndTest']   = '构建测试';

$lang->job->triggerTypeList['tag']      = '打标签';
$lang->job->triggerTypeList['commit']   = '提交注释包含关键字';
$lang->job->triggerTypeList['schedule'] = '定时计划';

$lang->job->frameList['']          = '';
$lang->job->frameList['junit']     = 'JUnit';
$lang->job->frameList['testng']    = 'TestNG';
$lang->job->frameList['phpunit']   = 'PHPUnit';
$lang->job->frameList['pytest']    = 'Pytest';
$lang->job->frameList['jtest']     = 'JTest';
$lang->job->frameList['cppunit']   = 'CppUnit';
$lang->job->frameList['gtest']     = 'GTest';
$lang->job->frameList['qtest']     = 'QTest';
$lang->job->frameList['sonarqube'] = 'SonarQube';

$lang->job->paramValueList['']                 = '';
$lang->job->paramValueList['$zentao_version']  = '当前版本号';
$lang->job->paramValueList['$zentao_account']  = '当前用户名';
$lang->job->paramValueList['$zentao_product']  = "当前{$lang->productCommon}ID";
$lang->job->paramValueList['$zentao_repopath'] = '当前版本库路径';

$lang->job->engineList = array();
$lang->job->engineList['']        = '';
$lang->job->engineList['gitlab']  = 'GitLab';
$lang->job->engineList['jenkins'] = 'Jenkins';

$lang->job->engineTips = new stdclass;
$lang->job->engineTips->success = '构建引擎将使用GitLab项目内置的流水线。';
$lang->job->engineTips->error   = '当前GitLab项目内没有可用的流水线，请先前往GitLab配置。';

$lang->job->pipelineTips                      = "选择要运行流水线的分支名或标签名";
$lang->job->pipelineVariables                 = "变量";
$lang->job->pipelineVariablesKeyPlaceHolder   = "输入变量的名称";
$lang->job->pipelineVariablesValuePlaceHolder = "输入变量的值";
$lang->job->pipelineVariablesTips             = "指定要在此次运行中使用的变量值。CI/CD设置中指定的值将用作默认值。";
$lang->job->setReferenceTips                  = "在执行构建前，请先设置代码库的分支信息。";

$lang->job->featureBar['browse']['job']     = '列表';
$lang->job->featureBar['browse']['compile'] = '执行历史';
