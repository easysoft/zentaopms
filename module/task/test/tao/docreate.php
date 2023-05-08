#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/task.class.php';

zdTable('task')->gen(1);
zdTable('taskspec')->gen(1);

/**

title=taskModel->doCreate();
timeout=0
cid=2

sed: can't read /home/tianshujie/repo/zentaopms/test/config/my.php: No such file or directory
- 测试正常的创建开发任务
 - 属性name @开发任务一
 - 属性execution @101
 - 属性type @devel
 - 属性estimate @1
 - 属性version @1
 - 属性estStarted @0000-00-00
 - 属性deadline @0000-00-00

- 测试正常的创建设计任务
 - 属性name @设计任务一
 - 属性execution @101
 - 属性type @design
 - 属性estimate @1
 - 属性version @1
 - 属性estStarted @2023-04-01
 - 属性deadline @0000-00-00

- 测试正常的创建需求任务
 - 属性name @需求任务一
 - 属性execution @101
 - 属性type @request
 - 属性estimate @1
 - 属性version @1
 - 属性estStarted @0000-00-00
 - 属性deadline @2024-01-01

- 测试正常的创建测试任务
 - 属性name @测试任务一
 - 属性execution @101
 - 属性type @test
 - 属性estimate @1
 - 属性version @1
 - 属性estStarted @0000-00-00
 - 属性deadline @0000-00-00

- 测试正常的创建研究任务
 - 属性name @研究任务一
 - 属性execution @101
 - 属性type @study
 - 属性estimate @1
 - 属性version @1
 - 属性estStarted @2023-04-01
 - 属性deadline @2024-01-01

- 测试正常的创建讨论任务
 - 属性name @讨论任务一
 - 属性execution @101
 - 属性type @discuss
 - 属性estimate @0
 - 属性version @1
 - 属性estStarted @2021-01-10
 - 属性deadline @2021-03-19

- 测试正常的创建界面任务
 - 属性name @界面任务一
 - 属性execution @101
 - 属性type @ui
 - 属性estimate @1
 - 属性version @1
 - 属性estStarted @2021-01-10
 - 属性deadline @2021-03-19

- 测试正常的创建事务任务
 - 属性name @事务任务一
 - 属性execution @101
 - 属性type @affair
 - 属性estimate @1
 - 属性version @1
 - 属性estStarted @2021-01-10
 - 属性deadline @2021-03-19

- 测试正常的创建其他任务
 - 属性name @其他任务一
 - 属性execution @101
 - 属性type @misc
 - 属性estimate @1
 - 属性version @1
 - 属性estStarted @2021-01-10
 - 属性deadline @2021-03-19

- 测试不输入执行创建任务 @『所属执行』不能为空。

- 测试不输入名称创建任务 @『任务名称』不能为空。

- 测试不输入类型创建任务 @『任务类型』不能为空。

- 测试不输入类型创建任务 @『最初预计』应当是数字，可以是小数。

*/
$devel         = array('execution' => 101, 'name' => '开发任务一', 'type' => 'devel', 'estimate' => 1, 'version' => 1,  'estStarted' => null, 'deadline' => null);
$design        = array('execution' => 101, 'name' => '设计任务一', 'type' => 'design', 'estimate' => 1, 'version' => 1,  'estStarted' => '2023-04-01', 'deadline' => null);
$request       = array('execution' => 101, 'name' => '需求任务一', 'type' => 'request', 'estimate' => 1, 'version' => 1,  'estStarted' => null, 'deadline' => '2024-01-01');
$test          = array('execution' => 101, 'name' => '测试任务一', 'type' => 'test', 'estimate' => 1, 'version' => 1,  'estStarted' => null, 'deadline' => null);
$study         = array('execution' => 101, 'name' => '研究任务一', 'type' => 'study', 'estimate' => 1, 'version' => 1,  'estStarted' => '2023-04-01', 'deadline' => '2024-01-01');
$discuss       = array('execution' => 101, 'name' => '讨论任务一', 'type' => 'discuss', 'estimate' => '', 'version' => 1);
$ui            = array('execution' => 101, 'name' => '界面任务一', 'type' => 'ui', 'estimate' => 1, 'version' => 1);
$affair        = array('execution' => 101, 'name' => '事务任务一', 'type' => 'affair', 'estimate' => 1, 'version' => 1);
$misc          = array('execution' => 101, 'name' => '其他任务一', 'type' => 'misc', 'estimate' => 1, 'version' => 1);
$noexecution   = array('execution' => 0, 'name' => '特殊任务一', 'type' => 'devel', 'estStarted' => '2021-04-10', 'deadline' => '2022-03-19', 'estimate' => 1, 'version' => 1);
$noname        = array('execution' => 101, 'name' => '', 'type' => 'devel', 'estimate' => 1, 'version' => 1);
$notype        = array('execution' => 101, 'name' => '特殊任务二', 'type' => '', 'estimate' => 1, 'version' => 1);
$errorEstimate = array('execution' => 101, 'name' => '特殊任务三', 'type' => 'devel', 'estimate' => '2a', 'version' => 1);

$task = new taskTest();

r($task->doCreateObject($devel))         && p('name,execution,type,estimate,version,estStarted,deadline') && e('开发任务一,101,devel,1,1,0000-00-00,0000-00-00');   // 测试正常的创建开发任务
r($task->doCreateObject($design))        && p('name,execution,type,estimate,version,estStarted,deadline') && e('设计任务一,101,design,1,1,2023-04-01,0000-00-00');  // 测试正常的创建设计任务
r($task->doCreateObject($request))       && p('name,execution,type,estimate,version,estStarted,deadline') && e('需求任务一,101,request,1,1,0000-00-00,2024-01-01'); // 测试正常的创建需求任务
r($task->doCreateObject($test))          && p('name,execution,type,estimate,version,estStarted,deadline') && e('测试任务一,101,test,1,1,0000-00-00,0000-00-00');    // 测试正常的创建测试任务
r($task->doCreateObject($study))         && p('name,execution,type,estimate,version,estStarted,deadline') && e('研究任务一,101,study,1,1,2023-04-01,2024-01-01');   // 测试正常的创建研究任务
r($task->doCreateObject($discuss))       && p('name,execution,type,estimate,version,estStarted,deadline') && e('讨论任务一,101,discuss,0,1,2021-01-10,2021-03-19'); // 测试正常的创建讨论任务
r($task->doCreateObject($ui))            && p('name,execution,type,estimate,version,estStarted,deadline') && e('界面任务一,101,ui,1,1,2021-01-10,2021-03-19');      // 测试正常的创建界面任务
r($task->doCreateObject($affair))        && p('name,execution,type,estimate,version,estStarted,deadline') && e('事务任务一,101,affair,1,1,2021-01-10,2021-03-19');  // 测试正常的创建事务任务
r($task->doCreateObject($misc))          && p('name,execution,type,estimate,version,estStarted,deadline') && e('其他任务一,101,misc,1,1,2021-01-10,2021-03-19');    // 测试正常的创建其他任务
r($task->doCreateObject($noexecution))   && p('execution:0')                                              && e('『所属执行』不能为空。');                           // 测试不输入执行创建任务
r($task->doCreateObject($noname))        && p('name:0')                                                   && e('『任务名称』不能为空。');                           // 测试不输入名称创建任务
r($task->doCreateObject($notype))        && p('type:0')                                                   && e('『任务类型』不能为空。');                           // 测试不输入类型创建任务
r($task->doCreateObject($errorEstimate)) && p('estimate:0')                                               && e('『最初预计』应当是数字，可以是小数。');             // 测试不输入类型创建任务