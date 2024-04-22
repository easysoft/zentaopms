#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';
su('admin');

function initData()
{
    zenData('todo')->loadYaml('fetch')->gen(10);
}

/**

title=测试完成待办 todoModel->finish();
timeout=0
cid=1

*/

initData();

global $tester;
$tester->loadModel('todo')->todoTao;

r($tester->todo->fetch(1)) && p('id,status') && e('1,wait'); // 查询id=1的todo并且验证状态
r($tester->todo->fetch(9)) && p('id,status') && e('9,done'); // 查询id=9的todo并且验证状态
