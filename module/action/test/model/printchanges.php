#!/usr/bin/env php
<?php

/**

title=测试 actionModel::printChanges();
timeout=0
cid=14920

- 执行actionTest模块的printChangesTest方法，参数是'task', 1, array  @
- 执行actionTest模块的printChangesTest方法，参数是'task', 1, $histories1  @修改了 <strong><i>任务状态</i></strong>，旧值为 "待处理"，新值为 "进行中"。<br />' . "\n"
- 执行actionTest模块的printChangesTest方法，参数是'task', 1, $histories2  @修改了 <strong><i>指派给</i></strong>，旧值为 "admin"，新值为 "user1"。<br />' . "\n" . '修改了 <strong><i>任务状态</i></strong>，旧值为 "待处理"，新值为 "进行中"。<br />' . "\n"
- 执行actionTest模块的printChangesTest方法，参数是'task', 1, $histories3  @修改了 <strong><i>任务描述</i></strong>，区别为：' . "\n" . "<blockquote class='textdiff'></blockquote>" . "\n" . "<blockquote class='original'>&lt;del&gt;旧描述&lt;/del&gt;&lt;ins&gt;新描述&lt;/ins&gt;</blockquote>"
- 执行actionTest模块的printChangesTest方法，参数是'task', 1, $histories3, false  @修改了 <strong><i>任务描述</i></strong>，区别为：' . "\n" . "<blockquote class='textdiff'></blockquote>" . "\n" . "<blockquote class='original'>&lt;del&gt;旧描述&lt;/del&gt;&lt;ins&gt;新描述&lt;/ins&gt;</blockquote>"

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

// 准备测试数据
zenData('company')->gen(1);
zenData('user')->gen(5);

su('admin');

$actionTest = new actionTest();

// 步骤1：空历史记录测试
r($actionTest->printChangesTest('task', 1, array())) && p() && e('');

// 步骤2：单个字段变更测试
$histories1 = array(
    (object)array('field' => 'status', 'old' => '待处理', 'new' => '进行中', 'diff' => '')
);
r($actionTest->printChangesTest('task', 1, $histories1)) && p() && e('修改了 <strong><i>任务状态</i></strong>，旧值为 "待处理"，新值为 "进行中"。<br />' . "\n");

// 步骤3：多个字段变更测试
$histories2 = array(
    (object)array('field' => 'assignedTo', 'old' => 'admin', 'new' => 'user1', 'diff' => ''),
    (object)array('field' => 'status', 'old' => '待处理', 'new' => '进行中', 'diff' => '')
);
r($actionTest->printChangesTest('task', 1, $histories2)) && p() && e('修改了 <strong><i>指派给</i></strong>，旧值为 "admin"，新值为 "user1"。<br />' . "\n" . '修改了 <strong><i>任务状态</i></strong>，旧值为 "待处理"，新值为 "进行中"。<br />' . "\n");

// 步骤4：包含diff信息的变更测试
$histories3 = array(
    (object)array('field' => 'desc', 'old' => '旧描述', 'new' => '新描述', 'diff' => '<del>旧描述</del><ins>新描述</ins>')
);
r($actionTest->printChangesTest('task', 1, $histories3)) && p() && e('修改了 <strong><i>任务描述</i></strong>，区别为：' . "\n" . "<blockquote class='textdiff'></blockquote>" . "\n" . "<blockquote class='original'>&lt;del&gt;旧描述&lt;/del&gt;&lt;ins&gt;新描述&lt;/ins&gt;</blockquote>");

// 步骤5：canChangeTag为false的diff测试
r($actionTest->printChangesTest('task', 1, $histories3, false)) && p() && e('修改了 <strong><i>任务描述</i></strong>，区别为：' . "\n" . "<blockquote class='textdiff'></blockquote>" . "\n" . "<blockquote class='original'>&lt;del&gt;旧描述&lt;/del&gt;&lt;ins&gt;新描述&lt;/ins&gt;</blockquote>");