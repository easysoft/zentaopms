<?php
$lang->citask->common                  = '构建任务';
$lang->citask->browseBuild             = '构建历史';
$lang->citask->viewLogs                = '构建日志';

$lang->citask->exeNow                  = '立即执行';
$lang->citask->delete                  = '删除构建任务';
$lang->citask->confirmDelete           = '确认删除该构建任务吗？';

$lang->citask->buildStatus             = '构建状态';
$lang->citask->buildTime               = '构建时间';

$lang->citask->id                      = 'ID';
$lang->citask->name                    = '名称';
$lang->citask->repo                    = '代码库';
$lang->citask->jenkins                 = 'Jenkins服务';
$lang->citask->jenkinsTask             = 'Jenkins任务名';
$lang->citask->buildType               = '构建类型';
$lang->citask->triggerType             = '触发方式';
$lang->citask->scheduleType            = '时间计划';
$lang->citask->cornExpression          = 'Corn表达式';
$lang->citask->custom                  = '自定义';

$lang->citask->tagKeywords             = '标签关键字';
$lang->citask->commentKeywords         = '注释关键字';
$lang->citask->extTask                 = '执行任务';

$lang->citask->at                      = '在';
$lang->citask->time                    = '时间';
$lang->citask->exe                     = '执行';
$lang->citask->scheduleInterval        = '每隔';
$lang->citask->scheduleDay             = '天数';
$lang->citask->day                     = '天';
$lang->citask->lastExe                 = '最后执行';
$lang->citask->scheduleTime            = '时间';

$lang->citask->example                 = '举例';
$lang->citask->tagEx                   = 'build_#15，其中15为Jenkins任务编号';
$lang->citask->commitEx                = 'start build #15，其中15为Jenkins任务编号';
$lang->citask->cronSample              = '如 0 0 2 * * 2-6/1 表示每个工作日凌晨2点';

$lang->citask->buildStatus             = array('success'=>'成功', 'fail'=>'失败', 'created'=>'新建', 'building'=>'构建中');
$lang->citask->dayTypeList             = array('workDay'=>'工作日', 'everyDay'=>'每天');
$lang->citask->buildTypeList           = array('build'=>'仅构建', 'buildAndDeploy'=>'构建部署', 'buildAndTest'=>'构建测试');
$lang->citask->triggerTypeList         = array('tag'=>'打标签', 'commit'=>'代码提交注释', 'schedule'=>'定时计划');
$lang->citask->scheduleTypeList        = array('cron'=>'Cron表达式', 'custom'=>'自定义');