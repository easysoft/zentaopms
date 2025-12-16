#!/usr/bin/env php
<?php

/**

title=测试 myModel::getAssignedByMe();
timeout=0
cid=17280

- 步骤1：正常获取admin用户指派的task @0
- 步骤2：正常获取admin用户指派的bug @4
- 步骤3：获取不存在用户指派的记录 @0
- 步骤4：获取admin用户指派的story @2
- 步骤5：测试排序功能 @4
- 步骤6：测试其他用户指派的记录 @0
- 步骤7：测试空类型默认行为 @0

*/

declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unittest.class.php';

// 准备测试数据
$actions = zenData('action');
$actions->objectType->range('task,bug,story,task,bug,story,task,bug,story,task,bug,story,task,bug');
$actions->objectID->range('1-10');
$actions->actor->range('admin{12},user1{2}');
$actions->action->range('assigned{12},opened{2}');
$actions->date->range('(-30D):1D')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$actions->gen(14);

zenData('bug')->gen(10);
zenData('task')->gen(10);
zenData('story')->gen(10);
zenData('user')->gen(5);
zenData('project')->gen(5);
zenData('product')->gen(5);
zenData('risk')->gen(5);
zenData('issue')->gen(5);

su('admin');

$myTest = new myTest();

r($myTest->getAssignedByMeTest('admin', null, 'id_desc', 'task')) && p() && e('0'); // 步骤1：正常获取admin用户指派的task
r($myTest->getAssignedByMeTest('admin', null, 'id_desc', 'bug')) && p() && e('4'); // 步骤2：正常获取admin用户指派的bug
r($myTest->getAssignedByMeTest('nonexist', null, 'id_desc', 'task')) && p() && e('0'); // 步骤3：获取不存在用户指派的记录
r($myTest->getAssignedByMeTest('admin', null, 'id_desc', 'story')) && p() && e('2'); // 步骤4：获取admin用户指派的story
r($myTest->getAssignedByMeTest('admin', null, 'id_asc', 'bug')) && p() && e('4'); // 步骤5：测试排序功能
r($myTest->getAssignedByMeTest('user1', null, 'id_desc', 'task')) && p() && e('0'); // 步骤6：测试其他用户指派的记录
r($myTest->getAssignedByMeTest('admin', null, 'id_desc', '')) && p() && e('0'); // 步骤7：测试空类型默认行为
