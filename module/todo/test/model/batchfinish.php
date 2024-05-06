#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';
su('admin');

function initData()
{
    zenData('todo')->loadYaml('batchfinish')->gen(10);
}

/**

title=测试批量完成待办 todoModel->batchFinish();
timeout=0
cid=1

*/

initData();
$todoIDList = range(1,3);

$todo = new todoTest();
$todo->batchFinishTest($todoIDList);

r($todo->getByIdTest($todoIDList[0])) && p('status') && e('done'); // 批量完成todo验证状态wait->done
r($todo->getByIdTest($todoIDList[1])) && p('status') && e('done'); // 批量完成todo验证状态donging->done
r($todo->getByIdTest($todoIDList[2])) && p('status') && e('done'); // 批量完成todo验证状态done
