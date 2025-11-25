#!/usr/bin/env php
<?php

/**

title=测试 todoZen::printStartConfirm();
timeout=0
cid=19305

- 执行todoTest模块的printStartConfirmTest方法，参数是$bugTodo
 - 属性result @success
 - 属性app @qa
 - 属性type @bug
 - 属性objectID @123
- 执行todoTest模块的printStartConfirmTest方法，参数是$taskTodo
 - 属性result @success
 - 属性app @execution
 - 属性type @task
 - 属性objectID @456
- 执行todoTest模块的printStartConfirmTest方法，参数是$storyTodo
 - 属性result @success
 - 属性app @product
 - 属性type @story
 - 属性objectID @789
- 执行todoTest模块的printStartConfirmTest方法，参数是$orTaskTodo
 - 属性result @success
 - 属性app @market
 - 属性type @researchtask
 - 属性objectID @101
- 执行todoTest模块的printStartConfirmTest方法，参数是$invalidTodo
 - 属性result @success
 - 属性type @unknown
 - 属性objectID @999
 - 属性hasCallback @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

su('admin');

$todoTest = new todoTest();

// 测试步骤1：测试bug类型待办的确认弹框
$bugTodo = new stdClass();
$bugTodo->id = 1;
$bugTodo->type = 'bug';
$bugTodo->objectID = 123;
$bugTodo->name = '测试Bug';
r($todoTest->printStartConfirmTest($bugTodo)) && p('result,app,type,objectID') && e('success,qa,bug,123');

// 测试步骤2：测试task类型待办的确认弹框
$taskTodo = new stdClass();
$taskTodo->id = 2;
$taskTodo->type = 'task';
$taskTodo->objectID = 456;
$taskTodo->name = '测试任务';
r($todoTest->printStartConfirmTest($taskTodo)) && p('result,app,type,objectID') && e('success,execution,task,456');

// 测试步骤3：测试story类型待办的确认弹框
$storyTodo = new stdClass();
$storyTodo->id = 3;
$storyTodo->type = 'story';
$storyTodo->objectID = 789;
$storyTodo->name = '测试需求';
r($todoTest->printStartConfirmTest($storyTodo)) && p('result,app,type,objectID') && e('success,product,story,789');

// 测试步骤4：测试or vision下task类型转换为researchtask
global $config;
$config->vision = 'or';
$orTaskTodo = new stdClass();
$orTaskTodo->id = 4;
$orTaskTodo->type = 'task';
$orTaskTodo->objectID = 101;
$orTaskTodo->name = '研究任务';
r($todoTest->printStartConfirmTest($orTaskTodo)) && p('result,app,type,objectID') && e('success,market,researchtask,101');

// 测试步骤5：测试无效类型待办的确认弹框
$invalidTodo = new stdClass();
$invalidTodo->id = 5;
$invalidTodo->type = 'unknown';
$invalidTodo->objectID = 999;
$invalidTodo->name = '未知类型待办';
r($todoTest->printStartConfirmTest($invalidTodo)) && p('result,type,objectID,hasCallback') && e('success,unknown,999,1');