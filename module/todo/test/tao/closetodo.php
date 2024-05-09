#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';
su('admin');

/**
title=测试Tao层的关闭待办 todoTao::closeTodo()
timeout=0
cid=1
*/

zenData('todo')->loadYaml('closetodo')->gen(1);

global $tester;
$todo = new todoTest();
r($todo->closeTodoTest(1)) && p('oldStatus,newStatus,isClosed') && e('wait,closed,1'); // 将wait状态更新为close状态并判断是否关闭成功
