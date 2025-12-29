#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

zenData('task')->gen(1);
zenData('taskspec')->gen(1);
zenData('project')->loadYaml('project')->gen(5);

/**

title=taskModel->create();
timeout=0
cid=18780

- 测试正常的创建开发任务
 - 属性name @开发任务一
 - 属性execution @2
 - 属性type @devel
 - 属性estimate @1.00
 - 属性version @1
 - 属性estStarted @`^$`
 - 属性deadline @`^$`
- 测试正常的创建设计任务
 - 属性name @设计任务一
 - 属性execution @2
 - 属性type @design
 - 属性estimate @1.00
 - 属性version @1
 - 属性estStarted @2023-04-01
 - 属性deadline @`^$`
- 测试正常的创建需求任务
 - 属性name @需求任务一
 - 属性execution @2
 - 属性type @request
 - 属性estimate @1.00
 - 属性version @1
 - 属性estStarted @`^$`
 - 属性deadline @2024-01-01
- 测试正常的创建测试任务
 - 属性name @测试任务一
 - 属性execution @2
 - 属性type @test
 - 属性estimate @1.00
 - 属性version @1
 - 属性estStarted @`^$`
 - 属性deadline @`^$`
- 测试正常的创建研究任务
 - 属性name @研究任务一
 - 属性execution @2
 - 属性type @study
 - 属性estimate @1.00
 - 属性version @1
 - 属性estStarted @2023-04-01
 - 属性deadline @2024-01-01
- 测试正常的创建讨论任务
 - 属性name @讨论任务一
 - 属性execution @2
 - 属性type @discuss
 - 属性estimate @1.00
 - 属性version @1
 - 属性estStarted @2021-01-10
 - 属性deadline @2021-03-19
- 测试正常的创建界面任务
 - 属性name @界面任务一
 - 属性execution @2
 - 属性type @ui
 - 属性estimate @1.00
 - 属性version @1
 - 属性estStarted @2021-01-10
 - 属性deadline @2021-03-19
- 测试正常的创建事务任务
 - 属性name @事务任务一
 - 属性execution @2
 - 属性type @affair
 - 属性estimate @1.00
 - 属性version @1
 - 属性estStarted @2021-01-10
 - 属性deadline @2021-03-19
- 测试正常的创建其他任务
 - 属性name @其他任务一
 - 属性execution @2
 - 属性type @misc
 - 属性estimate @1.00
 - 属性version @1
 - 属性estStarted @2021-01-10
 - 属性deadline @2021-03-19
- 测试不输入执行创建任务第execution条的0属性 @『所属执行』不能为空。
- 测试不输入名称创建任务第name条的0属性 @『任务名称』不能为空。
- 测试不输入类型创建任务第type条的0属性 @『任务类型』不能为空。

*/
$devel         = array('execution' => 2, 'name' => '开发任务一', 'type' => 'devel', 'estimate' => 1, 'version' => 1,  'estStarted' => null, 'deadline' => null, 'left' => 1);
$design        = array('execution' => 2, 'name' => '设计任务一', 'type' => 'design', 'estimate' => 1, 'version' => 1,  'estStarted' => '2023-04-01', 'deadline' => null);
$request       = array('execution' => 2, 'name' => '需求任务一', 'type' => 'request', 'estimate' => 1, 'version' => 1,  'estStarted' => null, 'deadline' => '2024-01-01');
$test          = array('execution' => 2, 'name' => '测试任务一', 'type' => 'test', 'estimate' => 1, 'version' => 1,  'estStarted' => null, 'deadline' => null);
$study         = array('execution' => 2, 'name' => '研究任务一', 'type' => 'study', 'estimate' => 1, 'version' => 1,  'estStarted' => '2023-04-01', 'deadline' => '2024-01-01');
$discuss       = array('execution' => 2, 'name' => '讨论任务一', 'type' => 'discuss', 'estimate' => 1, 'version' => 1);
$ui            = array('execution' => 2, 'name' => '界面任务一', 'type' => 'ui', 'estimate' => 1, 'version' => 1);
$affair        = array('execution' => 2, 'name' => '事务任务一', 'type' => 'affair', 'estimate' => 1, 'version' => 1);
$misc          = array('execution' => 2, 'name' => '其他任务一', 'type' => 'misc', 'estimate' => 1, 'version' => 1);
$noexecution   = array('execution' => 0, 'name' => '特殊任务一', 'type' => 'devel', 'estStarted' => '2021-04-10', 'deadline' => '2022-03-19', 'estimate' => 1, 'version' => 1);
$noname        = array('execution' => 2, 'name' => '', 'type' => 'devel', 'estimate' => 1, 'version' => 1);
$notype        = array('execution' => 2, 'name' => '特殊任务二', 'type' => '', 'estimate' => 1, 'version' => 1);

$task = new taskTest();

r($task->createObject($devel))       && p('name,execution,type,estimate,version,estStarted,deadline') && e('开发任务一,2,devel,1.00,1,`^$`,`^$`');               // 测试正常的创建开发任务
r($task->createObject($design))      && p('name,execution,type,estimate,version,estStarted,deadline') && e('设计任务一,2,design,1.00,1,2023-04-01,`^$`');        // 测试正常的创建设计任务
r($task->createObject($request))     && p('name,execution,type,estimate,version,estStarted,deadline') && e('需求任务一,2,request,1.00,1,`^$`,2024-01-01');       // 测试正常的创建需求任务
r($task->createObject($test))        && p('name,execution,type,estimate,version,estStarted,deadline') && e('测试任务一,2,test,1.00,1,`^$`,`^$`');                // 测试正常的创建测试任务
r($task->createObject($study))       && p('name,execution,type,estimate,version,estStarted,deadline') && e('研究任务一,2,study,1.00,1,2023-04-01,2024-01-01');   // 测试正常的创建研究任务
r($task->createObject($discuss))     && p('name,execution,type,estimate,version,estStarted,deadline') && e('讨论任务一,2,discuss,1.00,1,2021-01-10,2021-03-19'); // 测试正常的创建讨论任务
r($task->createObject($ui))          && p('name,execution,type,estimate,version,estStarted,deadline') && e('界面任务一,2,ui,1.00,1,2021-01-10,2021-03-19');      // 测试正常的创建界面任务
r($task->createObject($affair))      && p('name,execution,type,estimate,version,estStarted,deadline') && e('事务任务一,2,affair,1.00,1,2021-01-10,2021-03-19');  // 测试正常的创建事务任务
r($task->createObject($misc))        && p('name,execution,type,estimate,version,estStarted,deadline') && e('其他任务一,2,misc,1.00,1,2021-01-10,2021-03-19');    // 测试正常的创建其他任务
r($task->createObject($noexecution)) && p('execution:0')                                              && e('『所属执行』不能为空。');                            // 测试不输入执行创建任务
r($task->createObject($noname))      && p('name:0')                                                   && e('『任务名称』不能为空。');                            // 测试不输入名称创建任务
r($task->createObject($notype))      && p('type:0')                                                   && e('『任务类型』不能为空。');                            // 测试不输入类型创建任务
