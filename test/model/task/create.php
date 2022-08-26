#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
$db->switchDB();
su('admin');

/**

title=测试productModel->create();
cid=1
pid=1

测试正常的创建开发任务 >> 开发任务一
测试正常的创建设计任务 >> 11
测试正常的创建需求任务 >> 101
测试正常的创建测试任务 >> test
测试正常的创建研究任务 >> 3
测试正常的创建讨论任务 >> wait
测试正常的创建界面任务 >> admin
测试正常的创建事务任务 >> 2021-01-10
测试正常的创建其他任务 >> 2021-03-19
测试不输入名称创建任务 >> 『任务名称』不能为空。
测试不输入类型创建任务 >> 『任务类型』不能为空。
测试开始时间大于结束时间 >> 『截止日期』应当不小于『2021-04-10』。
测试指派人任务 >> user92

*/

$executionID = '101';

$t_devel     = array('name' => '开发任务一', 'type' => 'devel');
$t_design    = array('name' => '设计任务一', 'type' => 'design');
$t_request   = array('name' => '需求任务一', 'type' => 'request');
$t_test      = array('name' => '测试任务一', 'type' => 'test');
$t_study     = array('name' => '研究任务一', 'type' => 'study');
$t_discuss   = array('name' => '讨论任务一', 'type' => 'discuss');
$t_ui        = array('name' => '界面任务一', 'type' => 'ui');
$t_affair    = array('name' => '事务任务一', 'type' => 'affair');
$t_misc      = array('name' => '其他任务一', 'type' => 'misc');
$t_noname    = array('name' => '', 'type' => 'devel');
$t_notype    = array('name' => '特殊任务一', 'type' => '');
$t_errortime = array('name' => '特殊任务二', 'type' => 'devel', 'estStarted' => '2021-04-10', 'deadline' => '2021-03-19');
$assignedTo  = array('user92');
$t_assign    = array('name' => '指派人user92任务', 'type' => 'devel', 'assignedTo' => $assignedTo);

$task=new taskTest();
r($task->createObject($t_devel, $executionID))     && p('name')       && e('开发任务一');                             // 测试正常的创建开发任务
r($task->createObject($t_design, $executionID))    && p('project')    && e('11');                                     // 测试正常的创建设计任务
r($task->createObject($t_request, $executionID))   && p('execution')  && e('101');                                    // 测试正常的创建需求任务
r($task->createObject($t_test, $executionID))      && p('type')       && e('test');                                   // 测试正常的创建测试任务
r($task->createObject($t_study, $executionID))     && p('pri')        && e('3');                                      // 测试正常的创建研究任务
r($task->createObject($t_discuss, $executionID))   && p('status')     && e('wait');                                   // 测试正常的创建讨论任务
r($task->createObject($t_ui, $executionID))        && p('openedBy')   && e('admin');                                  // 测试正常的创建界面任务
r($task->createObject($t_affair, $executionID))    && p('estStarted') && e('2021-01-10');                             // 测试正常的创建事务任务
r($task->createObject($t_misc, $executionID))      && p('deadline')   && e('2021-03-19');                             // 测试正常的创建其他任务
r($task->createObject($t_noname, $executionID))    && p('name:0')     && e('『任务名称』不能为空。');                 // 测试不输入名称创建任务
r($task->createObject($t_notype, $executionID))    && p('type:0')     && e('『任务类型』不能为空。');                 // 测试不输入类型创建任务
r($task->createObject($t_errortime, $executionID)) && p('deadline:0') && e('『截止日期』应当不小于『2021-04-10』。'); // 测试开始时间大于结束时间
r($task->createObject($t_assign, $executionID))    && p('assignedTo') && e('user92');                                 // 测试指派人任务

$db->restoreDB();