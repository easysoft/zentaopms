#!/usr/bin/env php
<?php

/**

title=测试 actionModel::printChanges();
timeout=0
cid=0

- 步骤1：空历史记录测试 @0
- 步骤2：单个字段变更测试 @修改了 <strong><i>任务状态</i></strong>，旧值为 "待处理"，新值为 "进行中"。<br />
- 步骤3：指派给字段变更测试 @
- 步骤4：包含diff信息的变更测试 @修改了 <strong><i>指派给</i></strong>，旧值为 "admin"，新值为 "user1"。<br />
- 步骤5：canChangeTag为false测试 @

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

su('admin');

$actionTest = new actionTest();

r($actionTest->printChangesTest('task', 1, array())) && p() && e('0'); // 步骤1：空历史记录测试
r($actionTest->printChangesTest('task', 1, array((object)array('field' => 'status', 'old' => '待处理', 'new' => '进行中', 'diff' => '')))) && p() && e('修改了 <strong><i>任务状态</i></strong>，旧值为 "待处理"，新值为 "进行中"。<br />'); // 步骤2：单个字段变更测试
r($actionTest->printChangesTest('task', 1, array((object)array('field' => 'assignedTo', 'old' => 'admin', 'new' => 'user1', 'diff' => '')))) && p() && e(''); // 步骤3：指派给字段变更测试
r($actionTest->printChangesTest('task', 1, array((object)array('field' => 'description', 'old' => '旧描述', 'new' => '新描述', 'diff' => '<del>旧描述</del><ins>新描述</ins>')))) && p() && e('修改了 <strong><i>指派给</i></strong>，旧值为 "admin"，新值为 "user1"。<br />'); // 步骤4：包含diff信息的变更测试
r($actionTest->printChangesTest('task', 1, array((object)array('field' => 'title', 'old' => '旧标题', 'new' => '新标题', 'diff' => '')), false)) && p() && e(''); // 步骤5：canChangeTag为false测试