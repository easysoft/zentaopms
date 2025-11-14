#!/usr/bin/env php
<?php

/**

title=测试 taskZen::generalCreateResponse();
timeout=0
cid=18929

- 步骤1：continueAdding 场景，带有需求和模块属性result @success
- 步骤2：continueAdding 场景，无需求无模块属性result @success
- 步骤3：toTaskList 场景属性result @success
- 步骤4：默认场景属性result @success
- 步骤5：默认场景（其他afterChoose值）属性result @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

zenData('user')->gen(5);

su('admin');

$taskTest = new taskZenTest();

$task1 = new stdClass();
$task1->execution = 1;
$task1->story = 10;
$task1->module = 5;

$task2 = new stdClass();
$task2->execution = 2;
$task2->story = 0;
$task2->module = 0;

$task3 = new stdClass();
$task3->execution = 3;
$task3->story = 20;
$task3->module = 10;

r($taskTest->generalCreateResponseTest($task1, 1, 'continueAdding')) && p('result') && e('success'); // 步骤1：continueAdding 场景，带有需求和模块
r($taskTest->generalCreateResponseTest($task2, 2, 'continueAdding')) && p('result') && e('success'); // 步骤2：continueAdding 场景，无需求无模块
r($taskTest->generalCreateResponseTest($task3, 3, 'toTaskList')) && p('result') && e('success'); // 步骤3：toTaskList 场景
r($taskTest->generalCreateResponseTest($task1, 1, 'toStoryList')) && p('result') && e('success'); // 步骤4：默认场景
r($taskTest->generalCreateResponseTest($task2, 2, 'other')) && p('result') && e('success'); // 步骤5：默认场景（其他afterChoose值）