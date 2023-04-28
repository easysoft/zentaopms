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

title=测试 todoModel->editDateTest();
cid=1
pid=1

*/


global $tester;
$tester->loadModel('todo');

initData();

r($tester->todo->editDate(array(1),    '2023-06-07')) && p() && e(true);  // 获取id为1的待办的日期
r($tester->todo->editDate(array(2, 3), '2023-04-27')) && p() && e(true);  // 获取id 为1,2待办的日期
#r($tester->todo->editDate(array(9),   '2023-04-17')) && p() && e('0'); // 修改不存在的待办的日期
