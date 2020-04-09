<?php
$lang->job->common        = '构建任务';
$lang->job->browse        = '浏览构建任务';
$lang->job->create        = '创建构建任务';
$lang->job->edit          = '编辑构建任务';
$lang->job->exec          = '执行构建';
$lang->job->view          = '执行详情';
$lang->job->delete        = '删除构建任务';
$lang->job->confirmDelete = '确认删除该构建任务';
$lang->job->dirChange     = '目录改动';
$lang->job->buildTag      = '打标签';

$lang->job->id          = 'ID';
$lang->job->name        = '名称';
$lang->job->repo        = '代码库';
$lang->job->product     = '关联' . $lang->productCommon;
$lang->job->svnDir      = 'SVN监控路径';
$lang->job->jenkins     = 'Jenkins';
$lang->job->jkHost      = 'Jenkins服务器';
$lang->job->buildType   = '构建类型';
$lang->job->jkJob       = 'Jenkins任务';
$lang->job->frame       = '工具/框架';
$lang->job->triggerType = '触发方式';
$lang->job->atDay       = '自定义日期';
$lang->job->atTime      = '执行时间';
$lang->job->lastStatus  = '最后执行状态';
$lang->job->lastExec    = '最后执行时间';
$lang->job->comment     = '匹配关键字';

$lang->job->lblBasic = '基本信息';

$lang->job->example    = '举例';
$lang->job->commitEx   = "用于匹配创建构建任务的关键字，多个关键字用','分割";
$lang->job->cronSample = '如 0 0 2 * * 2-6/1 表示每个工作日凌晨2点';
$lang->job->sendExec   = '发送执行请求成功！执行结果：%s';

$lang->job->buildTypeList['build']          = '仅构建';
$lang->job->buildTypeList['buildAndDeploy'] = '构建部署';
$lang->job->buildTypeList['buildAndTest']   = '构建测试';

$lang->job->triggerTypeList['tag']      = '打标签';
$lang->job->triggerTypeList['commit']   = '提交注释包含关键字';
$lang->job->triggerTypeList['schedule'] = '定时计划';

$lang->job->frameList['']        = '';
$lang->job->frameList['junit']   = 'JUnit';
$lang->job->frameList['testng']  = 'TestNG';
$lang->job->frameList['phpunit'] = 'PHPUnit';
$lang->job->frameList['pytest']  = 'Pytest';
$lang->job->frameList['jtest']   = 'JTest';
$lang->job->frameList['cppunit'] = 'CppUnit';
$lang->job->frameList['gtest']   = 'GTest';
$lang->job->frameList['qtest']   = 'QTest';
