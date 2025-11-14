#!/usr/bin/env php
<?php

/**

title=测试 todoZen::batchEditFromMyTodo();
cid=19288

- 测试步骤1：正常空参数调用批量编辑 >> 期望返回基本数据结构
- 测试步骤2：指定待办ID列表批量编辑 >> 期望正确处理指定待办
- 测试步骤3：指定用户ID和类型参数 >> 期望正确设置用户和类型
- 测试步骤4：不同状态参数测试 >> 期望正确处理状态参数
- 测试步骤5：大量待办测试suhosin警告 >> 期望正确检测输入变量限制

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

zenData('user')->gen(5);
zenData('todo')->gen(10);

su('admin');

$todoTest = new todoTest();

r($todoTest->batchEditFromMyTodoTest()) && p('editedTodosCount,type,userID,status') && e('5,today,1,all');
r($todoTest->batchEditFromMyTodoTest(array(1, 2, 3))) && p('editedTodosCount,objectIdListCount') && e('3,1');
r($todoTest->batchEditFromMyTodoTest(array(), 'thisweek', 2, 'wait')) && p('type,userID,status') && e('thisweek,2,wait');
r($todoTest->batchEditFromMyTodoTest(array(), 'all', 1, 'done')) && p('type,status,bugsCount,tasksCount') && e('all,done,2,2');
r($todoTest->batchEditFromMyTodoTest(array(), 'today', 1, 'all')) && p('showSuhosinInfo,countInputVars') && e('0,35');