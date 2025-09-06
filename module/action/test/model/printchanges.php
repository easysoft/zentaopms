#!/usr/bin/env php
<?php

/**

title=测试 actionModel::printChanges();
timeout=0
cid=0

- 步骤1：空历史记录 @0
- 步骤2：单个字段变更 @~任务状态~
- 步骤3：指派给字段变更 @~指派给~
- 步骤4：包含diff信息的变更 @~diff~
- 步骤5：canChangeTag为false的测试 @~任务名称~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

su('admin');

$actionTest = new actionTest();

r($actionTest->printChangesTest('task', 1, array())) && p() && e('0'); // 步骤1：空历史记录
r($actionTest->printChangesTest('task', 1, array((object)array('field' => 'status', 'old' => '待处理', 'new' => '进行中', 'diff' => '')))) && p() && e('~任务状态~'); // 步骤2：单个字段变更
r($actionTest->printChangesTest('task', 1, array((object)array('field' => 'assignedTo', 'old' => 'admin', 'new' => 'user1', 'diff' => '')))) && p() && e('~指派给~'); // 步骤3：指派给字段变更
r($actionTest->printChangesTest('task', 1, array((object)array('field' => 'description', 'old' => '旧描述', 'new' => '新描述', 'diff' => '<del>旧描述</del><ins>新描述</ins>')))) && p() && e('~diff~'); // 步骤4：包含diff信息的变更
r($actionTest->printChangesTest('task', 1, array((object)array('field' => 'title', 'old' => '旧标题', 'new' => '新标题', 'diff' => '')), false)) && p() && e('~任务名称~'); // 步骤5：canChangeTag为false的测试