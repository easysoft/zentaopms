#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';

su('admin');

function initData()
{
    zenData('todo')->loadYaml('getvalidcyclelist')->gen(3);
}

/**

title=测试 todoModel->getValidCycleList();
timeout=0
cid=1

*/

initData();

global $tester;
$tester->loadModel('todo');
r($tester->todo->getValidCycleList()) && p('1:id') && e('1'); // 获取有效的周期待办列表的第一条数据的ID，结果为1
