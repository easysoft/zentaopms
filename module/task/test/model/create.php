#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

zdTable('task')->gen(1);
zdTable('taskspec')->gen(1);

$execution = zdTable('project');
$execution->id->range('1-6');
$execution->name->range('项目1,项目2,迭代1,迭代2,阶段1,阶段2');
$execution->type->range('project{2},sprint{2},stage{2}');
$execution->project->range('0{2},1{2},2{2}');
$execution->lifetime->range('[]{3},ops,[]{2}');
$execution->model->range('scrum,waterfall,[]{4}');
$execution->attribute->range('[]{4},request,review');
$execution->status->range('doing');
$execution->parent->range('0,0,1,1,2');
$execution->grade->range('2{2},1{4}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`, `2,6`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230612 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(6);

/**

title=测试taskModel->create();
timeout=0
cid=1

*/

$devel         = array('execution' => 3, 'name' => '开发任务一', 'type' => 'devel',   'estimate' => 1,    'version' => 1, 'estStarted' => '2023-04-01', 'deadline' => '2024-01-01');
$design        = array('execution' => 3, 'name' => '设计任务一', 'type' => 'design',  'estimate' => 1,    'version' => 1, 'estStarted' => '2023-04-01', 'deadline' => '2024-01-01');
$request       = array('execution' => 3, 'name' => '需求任务一', 'type' => 'request', 'estimate' => 1,    'version' => 1, 'estStarted' => '2023-04-01', 'deadline' => '2024-01-01');
$test          = array('execution' => 3, 'name' => '测试任务一', 'type' => 'test',    'estimate' => 0,    'version' => 1, 'estStarted' => '2023-04-01', 'deadline' => '2024-01-01');
$study         = array('execution' => 3, 'name' => '研究任务一', 'type' => 'study',   'estimate' => 1,    'version' => 1, 'estStarted' => '2023-04-01', 'deadline' => '2024-01-01');
$discuss       = array('execution' => 3, 'name' => '讨论任务一', 'type' => 'discuss', 'estimate' => '',   'version' => 1, 'estStarted' => '2023-04-01', 'deadline' => '2024-01-01');
$ui            = array('execution' => 3, 'name' => '界面任务一', 'type' => 'ui',      'estimate' => 1,    'version' => 1, 'estStarted' => '2023-04-01', 'deadline' => '2024-01-01');
$affair        = array('execution' => 3, 'name' => '事务任务一', 'type' => 'affair',  'estimate' => 1,    'version' => 1, 'estStarted' => '2023-04-01', 'deadline' => '2024-01-01');
$misc          = array('execution' => 3, 'name' => '其他任务一', 'type' => 'misc',    'estimate' => 1,    'version' => 1, 'estStarted' => '2023-04-01', 'deadline' => '2024-01-01');
$noexecution   = array('execution' => 0, 'name' => '特殊任务一', 'type' => 'devel',   'estimate' => 1,    'version' => 1, 'estStarted' => '2023-04-01', 'deadline' => '2024-01-01');
$noname        = array('execution' => 3, 'name' => '',           'type' => 'devel',   'estimate' => 1,    'version' => 1, 'estStarted' => '2023-04-01', 'deadline' => '2024-01-01');
$notype        = array('execution' => 3, 'name' => '特殊任务二', 'type' => '',        'estimate' => 1,    'version' => 1, 'estStarted' => '2023-04-01', 'deadline' => '2024-01-01');
$errorEstimate = array('execution' => 3, 'name' => '特殊任务三', 'type' => 'devel',   'estimate' => '2a', 'version' => 1, 'estStarted' => '2023-04-01', 'deadline' => '2024-01-01');
$noModule      = array('execution' => 3, 'name' => '特殊任务四', 'type' => 'devel',   'estimate' => 1,    'version' => 1, 'estStarted' => '2023-04-01', 'deadline' => '2024-01-01', 'module' => 0);
$noStory       = array('execution' => 3, 'name' => '特殊任务五', 'type' => 'devel',   'estimate' => 1,    'version' => 1, 'estStarted' => '2023-04-01', 'deadline' => '2024-01-01', 'story'  => 0);
$noPri         = array('execution' => 3, 'name' => '特殊任务六', 'type' => 'devel',   'estimate' => 1,    'version' => 1, 'estStarted' => '2023-04-01', 'deadline' => '2024-01-01', 'pri'    => 0);
$noDesc        = array('execution' => 3, 'name' => '特殊任务七', 'type' => 'devel',   'estimate' => 1,    'version' => 1, 'estStarted' => '2023-04-01', 'deadline' => '2024-01-01', 'desc'   => '');
$noEstStarted  = array('execution' => 3, 'name' => '特殊任务八', 'type' => 'devel',   'estimate' => 1,    'version' => 1, 'estStarted' => ''          , 'deadline' => '2024-01-01');
$noDeadline    = array('execution' => 3, 'name' => '特殊任务九', 'type' => 'devel',   'estimate' => 1,    'version' => 1, 'estStarted' => '2023-04-01', 'deadline' => '');
$linearTask    = array('execution' => 3, 'name' => '并行任务一', 'type' => 'design',  'estimate' => 1,    'version' => 1, 'estStarted' => '2023-04-01', 'deadline' => '2024-01-01', 'mode' => 'linear');
$multiTask     = array('execution' => 3, 'name' => '并行任务一', 'type' => 'ui',      'estimate' => 1,    'version' => 1, 'estStarted' => '2023-04-01', 'deadline' => '2024-01-01', 'mode' => 'multi');

$assignedToList['empty']    = array();
$assignedToList['single']   = array('admin');
$assignedToList['multiple'] = array('admin', 'user1', 'user2');

$multiple    = 1;
$notMultiple = 0;

$teamList         = array('admin', 'user1', 'user2');
$teamSourceList   = array('admin', 'user1', 'user2');
$teamEstimateList = array(1, 2, 3);
$teamConsumedList = array(4, 3, 2);
$teamLeftList     = array(1, 2, 3);

$selectTestStory    = true;
$notSelectTestStory = false;

$requiredFieldList = array('estimate', 'module', 'story', 'pri', 'desc', 'estStarted', 'deadline');

$taskTester = new taskTest();

/* Create a common task. */
r($taskTester->createTest($devel,   $assignedToList['single']))                                                                                                 && p('name,type,status,assignedTo') && e('开发任务一,devel,wait,admin');   // 测试创建开发类型的任务
r($taskTester->createTest($design,  $assignedToList['single']))                                                                                                 && p('name,type,status,assignedTo') && e('设计任务一,design,wait,admin');  // 测试创建设计类型的任务
r($taskTester->createTest($request, $assignedToList['single']))                                                                                                 && p('name,type,status,assignedTo') && e('需求任务一,request,wait,admin'); // 测试创建需求类型的任务
r($taskTester->createTest($study,   $assignedToList['single']))                                                                                                 && p('name,type,status,assignedTo') && e('研究任务一,study,wait,admin');   // 测试创建研究类型的任务
r($taskTester->createTest($discuss, $assignedToList['single']))                                                                                                 && p('name,type,status,assignedTo') && e('讨论任务一,discuss,wait,admin'); // 测试创建讨论类型的任务
r($taskTester->createTest($ui,      $assignedToList['single']))                                                                                                 && p('name,type,status,assignedTo') && e('界面任务一,ui,wait,admin');      // 测试创建界面类型的任务
r($taskTester->createTest($affair,  $assignedToList['multiple']))                                                                                               && p('name,type,status,assignedTo') && e('事务任务一,affair,wait,admin');  // 测试创建事务类型的任务
r($taskTester->createTest($misc,    $assignedToList['single']))                                                                                                 && p('name,type,status,assignedTo') && e('其他任务一,misc,wait,admin');    // 测试创建其他类型的任务
r($taskTester->createTest($test,    $assignedToList['single'], $notMultiple, array(), $selectTestStory, array(), array(), false, false, $requiredFieldList[0])) && p('name,type,status,assignedTo') && e('测试任务一,test,wait,admin');    // 测试创建测试类型的任务

/* Check required. */
r($taskTester->createTest($noexecution,   $assignedToList['single']))                                                                                                    && p('execution:0')  && e('『所属执行』不能为空。');               // 测试所属执行的必填判断
r($taskTester->createTest($noname,        $assignedToList['single']))                                                                                                    && p('name:0')       && e('『任务名称』不能为空。');               // 测试任务名称的必填判断
r($taskTester->createTest($notype,        $assignedToList['single']))                                                                                                    && p('type:0')       && e('『任务类型』不能为空。');               // 测试任务类型的必填判断
r($taskTester->createTest($errorEstimate, $assignedToList['single']))                                                                                                    && p('estimate:0')   && e('『最初预计』应当是数字，可以是小数。'); // 测试预计工时格式的正确性
r($taskTester->createTest($noModule,      $assignedToList['single'], $notMultiple, array(), $notSelectTestStory, array(), array(), false, false, $requiredFieldList[1])) && p('module:0')     && e('『所属模块』不能为空。');               // 测试所属模块的必填判断
r($taskTester->createTest($noStory,       $assignedToList['single'], $notMultiple, array(), $notSelectTestStory, array(), array(), false, false, $requiredFieldList[2])) && p('story:0')      && e('『相关研发需求』不能为空。');           // 测试相关研发需求的必填判断
r($taskTester->createTest($noPri,         $assignedToList['single'], $notMultiple, array(), $notSelectTestStory, array(), array(), false, false, $requiredFieldList[3])) && p('pri:0')        && e('『优先级』不能为空。');                 // 测试优先级的必填判断
r($taskTester->createTest($noDesc,        $assignedToList['single'], $notMultiple, array(), $notSelectTestStory, array(), array(), false, false, $requiredFieldList[4])) && p('desc:0')       && e('『任务描述』不能为空。');               // 测试任务描述的必填判断
r($taskTester->createTest($noEstStarted,  $assignedToList['single'], $notMultiple, array(), $notSelectTestStory, array(), array(), false, false, $requiredFieldList[5])) && p('estStarted:0') && e('『预计开始』不能为空。');               // 测试预计开始的必填判断
r($taskTester->createTest($noDeadline,    $assignedToList['single'], $notMultiple, array(), $notSelectTestStory, array(), array(), false, false, $requiredFieldList[6])) && p('deadline:0')   && e('『截止日期』不能为空。');               // 测试截止日期的必填判断

/* Create a multiplayer task when mode is empty. */
r($taskTester->createTest($devel, $assignedToList['single'], $multiple))                                                                                                       && p('mode') && e('`^$`'); // 测试多人任务团队数量小于2的情况
r($taskTester->createTest($devel, $assignedToList['single'], $multiple, $teamList))                                                                                            && p('mode') && e('`^$`'); // 测试多人任务团队来源为空的情况
r($taskTester->createTest($devel, $assignedToList['single'], $multiple, $teamList, $notSelectTestStory, $teamSourceList))                                                      && p('mode') && e('`^$`'); // 测试多人任务预计工时为空的情况
r($taskTester->createTest($devel, $assignedToList['single'], $multiple, $teamList, $notSelectTestStory, $teamSourceList, $teamEstimateList))                                   && p('mode') && e('`^$`'); // 测试多人任务预计工时为空的情况
r($taskTester->createTest($devel, $assignedToList['single'], $multiple, $teamList, $notSelectTestStory, $teamSourceList, $teamEstimateList, $teamConsumedList))                && p('mode') && e('`^$`'); // 测试多人任务消耗工时为空的情况
r($taskTester->createTest($devel, $assignedToList['single'], $multiple, $teamList, $notSelectTestStory, $teamSourceList, $teamEstimateList, $teamConsumedList, $teamLeftList)) && p('mode') && e('`^$`'); // 测试多人任务预计剩余为空的情况

/* Create a linear task. */
r($taskTester->createTest($linearTask, $assignedToList['single'], $multiple))                                                                                                       && p('assignedTo,estimate,consumed,left') && e('`^$`,1,0,0');  // 测试多人任务团队数量小于2的情况
r($taskTester->createTest($linearTask, $assignedToList['single'], $multiple, $teamList))                                                                                            && p('assignedTo,estimate,consumed,left') && e('admin,0,0,0'); // 测试多人任务团队来源为空的情况
r($taskTester->createTest($linearTask, $assignedToList['single'], $multiple, $teamList, $notSelectTestStory, $teamSourceList))                                                      && p('assignedTo,estimate,consumed,left') && e('admin,0,0,0'); // 测试多人任务预计工时为空的情况
r($taskTester->createTest($linearTask, $assignedToList['single'], $multiple, $teamList, $notSelectTestStory, $teamSourceList, $teamEstimateList))                                   && p('assignedTo,estimate,consumed,left') && e('admin,6,0,6'); // 测试多人任务预计工时为空的情况
r($taskTester->createTest($linearTask, $assignedToList['single'], $multiple, $teamList, $notSelectTestStory, $teamSourceList, $teamEstimateList, $teamConsumedList))                && p('assignedTo,estimate,consumed,left') && e('admin,6,0,6'); // 测试多人任务消耗工时为空的情况
r($taskTester->createTest($linearTask, $assignedToList['single'], $multiple, $teamList, $notSelectTestStory, $teamSourceList, $teamEstimateList, $teamConsumedList, $teamLeftList)) && p('assignedTo,estimate,consumed,left') && e('admin,6,0,6'); // 测试多人任务预计剩余为空的情况

/* Create a multi task. */
r($taskTester->createTest($multiTask, $assignedToList['single'], $multiple))                                                                                                       && p('assignedTo,estimate,consumed,left') && e('`^$`,1,0,0'); // 测试多人任务团队数量小于2的情况
r($taskTester->createTest($multiTask, $assignedToList['single'], $multiple, $teamList))                                                                                            && p('assignedTo,estimate,consumed,left') && e('`^$`,0,0,0'); // 测试多人任务团队来源为空的情况
r($taskTester->createTest($multiTask, $assignedToList['single'], $multiple, $teamList, $notSelectTestStory, $teamSourceList))                                                      && p('assignedTo,estimate,consumed,left') && e('`^$`,0,0,0'); // 测试多人任务预计工时为空的情况
r($taskTester->createTest($multiTask, $assignedToList['single'], $multiple, $teamList, $notSelectTestStory, $teamSourceList, $teamEstimateList))                                   && p('assignedTo,estimate,consumed,left') && e('`^$`,6,0,6'); // 测试多人任务预计工时为空的情况
r($taskTester->createTest($multiTask, $assignedToList['single'], $multiple, $teamList, $notSelectTestStory, $teamSourceList, $teamEstimateList, $teamConsumedList))                && p('assignedTo,estimate,consumed,left') && e('`^$`,6,0,6'); // 测试多人任务消耗工时为空的情况
r($taskTester->createTest($multiTask, $assignedToList['single'], $multiple, $teamList, $notSelectTestStory, $teamSourceList, $teamEstimateList, $teamConsumedList, $teamLeftList)) && p('assignedTo,estimate,consumed,left') && e('`^$`,6,0,6'); // 测试多人任务预计剩余为空的情况
