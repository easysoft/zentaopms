#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/todo.class.php';

su('admin');

function initData()
{
    zdTable('todo')->config('getvalidcyclelist')->gen(3);
}

/**

title=测试 todoModel->getValidCycleList();
timeout=0
cid=1

- 执行todo模块的getValidCycleList方法第1条的id属性 @1

*/

initData();

global $tester;
$tester->loadModel('todo');
r($tester->todo->getValidCycleList()) && p('1:id') && e('1');
