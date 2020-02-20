<?php
$lang->integration->browse        = '浏览构建任务';
$lang->integration->create        = '创建构建任务';
$lang->integration->edit          = '编辑构建任务';
$lang->integration->execNow       = '立即执行';
$lang->integration->delete        = '删除构建任务';
$lang->integration->confirmDelete = '确认删除该构建任务吗？';

$lang->integration->id             = 'ID';
$lang->integration->name           = '名称';
$lang->integration->repo           = '代码库';
$lang->integration->svnFolder      = 'SVN Tag监控路径';
$lang->integration->jenkins        = 'Jenkins服务';
$lang->integration->buildType      = '构建类型';
$lang->integration->jenkinsJob     = 'Jenkins任务名';
$lang->integration->triggerType    = '触发方式';
$lang->integration->scheduleType   = '时间计划';
$lang->integration->cronExpression = 'Cron表达式';
$lang->integration->custom         = '自定义';

$lang->integration->at               = '在';
$lang->integration->time             = '时间';
$lang->integration->exec             = '执行';
$lang->integration->scheduleInterval = '每隔';
$lang->integration->day              = '天';
$lang->integration->lastExec         = '最后执行';
$lang->integration->scheduleTime     = '时间';

$lang->integration->example    = '举例';
$lang->integration->tagEx      = 'build_#15，其中15为Jenkins任务编号';
$lang->integration->commitEx   = 'start build #15，其中15为Jenkins任务编号';
$lang->integration->cronSample = '如 0 0 2 * * 2-6/1 表示每个工作日凌晨2点';
$lang->integration->sendExec   = '发送执行请求成功！';

$lang->integration->dayTypeList['workDay']  = '工作日';
$lang->integration->dayTypeList['everyDay'] = '每天';

$lang->integration->buildTypeList['build']          = '仅构建';
$lang->integration->buildTypeList['buildAndDeploy'] = '构建部署';
$lang->integration->buildTypeList['buildAndTest']   = '构建测试';

$lang->integration->triggerTypeList['tag']      = '打标签';
$lang->integration->triggerTypeList['commit']   = '代码提交注释';
$lang->integration->triggerTypeList['schedule'] = '定时计划';

$lang->integration->scheduleTypeList['cron']   = 'Crontab';
$lang->integration->scheduleTypeList['custom'] = '自定义';
