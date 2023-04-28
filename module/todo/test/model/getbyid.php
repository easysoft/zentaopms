#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/todo.class.php';
su('admin');

function initData()
{
    $project = zdTable('todo');
    $project->id->range('1-5');
    $project->name->prefix("自定义的待办")->range('1-5');
    $project->type->range(1);
    $project->status->range("wait,doing,done,closed,closed");
    $project->gen(5);
}


/**

title=测试 todoModel->getById();
cid=1
pid=1

*/

$todoIDList = array('1', '2', '3', '5', '100000');

$todo = new todoTest();

initData();

r($todo->getByIdTest($todoIDList[0])) && p('name,status') && e('自定义的待办1,wait'); // 获取id为1的todo信息
r($todo->getByIdTest($todoIDList[1])) && p('name,status') && e('自定义的待办2,doing'); // 获取id为2的todo信息
r($todo->getByIdTest($todoIDList[3])) && p('name,status') && e('自定义的待办5,closed'); // 获取id为5的todo信息
r($todo->getByIdTest($todoIDList[4])) && p() && e('0');    // 获取id不存在的todo信息
