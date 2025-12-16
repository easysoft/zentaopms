#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';

/**

title=测试Tao层的关闭待办 todoTao::closeTodo()
timeout=0
cid=19269

- 将wait状态更新为close状态并判断是否关闭成功
 - 属性oldStatus @wait
 - 属性newStatus @closed
 - 属性isClosed @1
- 将wait状态更新为close状态并判断是否关闭成功
 - 属性oldStatus @doing
 - 属性newStatus @closed
 - 属性isClosed @1
- 将wait状态更新为close状态并判断是否关闭成功
 - 属性oldStatus @done
 - 属性newStatus @closed
 - 属性isClosed @1
- 将wait状态更新为close状态并判断是否关闭成功
 - 属性oldStatus @closed
 - 属性newStatus @closed
 - 属性isClosed @1
- 将wait状态更新为close状态并判断是否关闭成功
 - 属性oldStatus @wait
 - 属性newStatus @closed
 - 属性isClosed @1

*/

su('admin');

zenData('todo')->loadYaml('closetodo')->gen(10);

global $tester;
$todo = new todoTest();
r($todo->closeTodoTest(1)) && p('oldStatus,newStatus,isClosed') && e('wait,closed,1');   // 将wait状态更新为close状态并判断是否关闭成功
r($todo->closeTodoTest(2)) && p('oldStatus,newStatus,isClosed') && e('doing,closed,1');  // 将wait状态更新为close状态并判断是否关闭成功
r($todo->closeTodoTest(3)) && p('oldStatus,newStatus,isClosed') && e('done,closed,1');   // 将wait状态更新为close状态并判断是否关闭成功
r($todo->closeTodoTest(4)) && p('oldStatus,newStatus,isClosed') && e('closed,closed,1'); // 将wait状态更新为close状态并判断是否关闭成功
r($todo->closeTodoTest(5)) && p('oldStatus,newStatus,isClosed') && e('wait,closed,1');   // 将wait状态更新为close状态并判断是否关闭成功