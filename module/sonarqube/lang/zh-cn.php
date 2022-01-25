<?php
$lang->sonarqube = new stdclass;
$lang->sonarqube->common            = 'SonarQube';
$lang->sonarqube->browse            = '浏览SonarQube';
$lang->sonarqube->search            = '搜索';
$lang->sonarqube->create            = '添加SonarQube';
$lang->sonarqube->edit              = '编辑SonarQube';
$lang->sonarqube->delete            = '删除SonarQube';
$lang->sonarqube->serverFail        = '连接SonarQube服务器异常，请检查SonarQube服务器。';
$lang->sonarqube->browseProject     = "SonarQube项目列表";
$lang->sonarqube->createProject     = "创建SonarQube项目";
$lang->sonarqube->deleteProject     = "删除SonarQube项目";
$lang->sonarqube->placeholderSearch = '请输入项目名称';
$lang->sonarqube->execJob           = "执行SonarQube任务";
$lang->sonarqube->desc              = '描述';
$lang->sonarqube->reportView        = "SonarQube报告";

$lang->sonarqube->id             = 'ID';
$lang->sonarqube->name           = "服务器名称";
$lang->sonarqube->url            = '服务器地址';
$lang->sonarqube->account        = '用户名';
$lang->sonarqube->password       = '密码';
$lang->sonarqube->token          = 'Token';
$lang->sonarqube->defaultProject = '默认项目';
$lang->sonarqube->private        = 'MD5验证';

$lang->sonarqube->createServer  = '添加SonarQube服务器';
$lang->sonarqube->editServer    = '修改SonarQube服务器';
$lang->sonarqube->desc          = '描述';
$lang->sonarqube->createSuccess = "创建成功";

$lang->sonarqube->placeholder = new stdclass;
$lang->sonarqube->placeholder->name = '';
$lang->sonarqube->placeholder->url  = "请填写SonarQube Server首页的访问地址，如：https://sonarqube.zentao.net。";

$lang->sonarqube->nameRepeatError      = "服务器名称已存在！";
$lang->sonarqube->urlRepeatError       = "服务器地址已存在！";
$lang->sonarqube->validError           = "SonarQube 用户权限认证失败！";
$lang->sonarqube->hostError            = "无效的SonarQube服务地址。";
$lang->sonarqube->confirmDelete        = '确认删除该SonarQube吗？';
$lang->sonarqube->confirmDeleteProject = '确认删除该SonarQube项目吗？';
$lang->sonarqube->noReport             = "暂无报告";

$lang->sonarqube->projectKey          = '项目标识';
$lang->sonarqube->projectName         = '项目名称';
$lang->sonarqube->projectlastAnalysis = '最后执行时间';

$lang->sonarqube->report = new stdclass();
$lang->sonarqube->report->bugs                       = 'Bugs';
$lang->sonarqube->report->vulnerabilities            = '弱点';
$lang->sonarqube->report->security_hotspots_reviewed = '复审热点';
$lang->sonarqube->report->code_smells                = '异味';
$lang->sonarqube->report->coverage                   = '覆盖率';
$lang->sonarqube->report->duplicated_lines_density   = '重复率';
$lang->sonarqube->report->ncloc                      = '行数';

$lang->sonarqube->qualitygateList = array();
$lang->sonarqube->qualitygateList['OK']    = 'Passed';
$lang->sonarqube->qualitygateList['WARN']  = 'Warning';
$lang->sonarqube->qualitygateList['ERROR'] = 'Failed';
