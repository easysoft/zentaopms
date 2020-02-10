<?php
$lang->ci->common         = '持续集成';
$lang->ci->at             = '于';

$lang->ci->jenkins        = 'Jenkins';
$lang->ci->repo           = '版本库';
$lang->ci->job            = '构建';
$lang->ci->browse         = '浏览';
$lang->ci->create         = '新建';
$lang->ci->edit           = '编辑';

$lang->job = new stdclass();
$lang->job->browseBuild   = '构建历史';
$lang->job->viewLogs      = '构建日志';

$lang->job->create        = '新建构建任务';
$lang->job->edit          = '编辑构建任务';
$lang->job->exeNow        = '立即执行';
$lang->job->delete        = '删除构建任务';
$lang->job->confirmDelete = '确认删除该构建任务吗？';

$lang->job->repo   = '构建状态';
$lang->job->buildStatus   = '构建状态';
$lang->job->buildStatus   = '构建状态';
$lang->job->buildStatus   = '构建状态';
$lang->job->buildTime     = '构建时间';

$lang->job->id             = 'ID';
$lang->job->name           = '名称';
$lang->job->repo           = '代码库';
$lang->job->svnFolder      = 'SVN Tag 父URL';
$lang->job->jenkins        = 'Jenkins服务';
$lang->job->buildType      = '构建类型';
$lang->job->jenkinsJob     = 'Jenkins任务名';
$lang->job->triggerType    = '触发方式';
$lang->job->scheduleType   = '时间计划';
$lang->job->cronExpression = 'Cron表达式';
$lang->job->custom         = '自定义';

$lang->job->at               = '在';
$lang->job->time             = '时间';
$lang->job->exe              = '执行';
$lang->job->scheduleInterval = '每隔';
$lang->job->day              = '天';
$lang->job->lastExe          = '最后执行';
$lang->job->scheduleTime     = '时间';

$lang->job->example    = '举例';
$lang->job->tagEx      = 'build_#15，其中15为Jenkins任务编号';
$lang->job->commitEx   = 'start build #15，其中15为Jenkins任务编号';
$lang->job->cronSample = '如 0 0 2 * * 2-6/1 表示每个工作日凌晨2点';

$lang->job->buildStatusList  = array('success' => '成功', 'fail' => '失败', 'created' => '新建', 'building' => '构建中', 'create_fail' => '创建失败', 'timeout' => '执行超时');
$lang->job->dayTypeList      = array('workDay' => '工作日', 'everyDay' => '每天');
$lang->job->buildTypeList    = array('build' => '仅构建', 'buildAndDeploy' => '构建部署', 'buildAndTest' => '构建测试');
$lang->job->triggerTypeList  = array('tag' => '打标签', 'commit' => '代码提交注释', 'schedule' => '定时计划');
$lang->job->scheduleTypeList = array('cron' => 'Crontab', 'custom' => '自定义');
