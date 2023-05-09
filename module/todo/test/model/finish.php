#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/todo.class.php';
su('admin');

function initData()
{
    zdTable('todo')->config('finish')->gen(10);
}

/**

title=测试完成待办 todoModel->finish();
timeout=0
cid=1

- 结束一个状态为wait的todo属性status @done

- 结束一个状态为doing的todo属性status @done

- 结束一个状态为done的todo属性status @done



*/

initData();

$todoIDList = range(1,3);

$todo = new todoTest();

r($todo->finishTest($todoIDList[0])) && p('status') && e('done'); // 结束一个状态为wait的todo
r($todo->finishTest($todoIDList[1])) && p('status') && e('done'); // 结束一个状态为doing的todo
r($todo->finishTest($todoIDList[2])) && p('status') && e('done'); // 结束一个状态为done的todo
