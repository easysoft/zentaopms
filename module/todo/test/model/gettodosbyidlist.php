#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

function initData()
{
    zenData('todo')->loadYaml('gettodosbyidlist')->gen(5);
}

/**

title=测试 todoModel->getTodosByIdList();
timeout=0
cid=1

*/

initData();

global $tester;
$tester->loadModel('todo');

$todoIdList      = array(1, 2, 3);
$todoIdListError = array(110);

r($tester->todo->gettodosbyidlist($todoIdList))      && p('1:name') && e('自定义的待办1'); // 获取数据库中存在的待办信息
r($tester->todo->gettodosbyidlist($todoIdListError)) && p()         && e('0');           // 获取数据库中不存在的待办信息
